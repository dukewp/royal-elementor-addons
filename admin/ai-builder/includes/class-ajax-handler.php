<?php
namespace WprAddons\Admin\AiBuilder\Includes;

use WprAddons\Admin\AiBuilder\WPR_AI_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_AI_Ajax_Handler {

	public function __construct() {
		add_action( 'wp_ajax_wpr_ai_generate_page', [ $this, 'generate_page' ] );
		add_action( 'wp_ajax_wpr_ai_test_connection', [ $this, 'test_connection' ] );
		add_action( 'wp_ajax_wpr_ai_save_settings', [ $this, 'save_settings' ] );
	}

	public function generate_page() {
		check_ajax_referer( 'wpr-ai-builder', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized.' );
		}

		$prompt = isset( $_POST['prompt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['prompt'] ) ) : '';
		if ( empty( $prompt ) ) {
			wp_send_json_error( 'Please describe the page you want to create.' );
		}

		$args = [
			'prompt'           => $prompt,
			'page_type'        => isset( $_POST['page_type'] ) ? sanitize_text_field( wp_unslash( $_POST['page_type'] ) ) : 'landing',
			'business_name'    => isset( $_POST['business_name'] ) ? sanitize_text_field( wp_unslash( $_POST['business_name'] ) ) : '',
			'style'            => isset( $_POST['style'] ) ? sanitize_text_field( wp_unslash( $_POST['style'] ) ) : 'modern',
			'color_preference' => isset( $_POST['color_preference'] ) ? sanitize_text_field( wp_unslash( $_POST['color_preference'] ) ) : '',
			'is_pro'           => defined( 'WPR_ADDONS_PRO_VERSION' ) && function_exists( 'wpr_fs' ) && wpr_fs()->can_use_premium_code(),
		];

		// Step 1: Build the prompt (fixed sections mode)
		$prompt_builder = new WPR_Prompt_Builder();

		$system_prompt = $prompt_builder->build_system_prompt();
		$user_prompt = $prompt_builder->build_user_prompt( $args );

		// Step 2: Call AI — only generates text, doesn't pick sections
		$ai_client = new WPR_AI_Client();
		$ai_response = $ai_client->send_message( $system_prompt, $user_prompt );

		if ( is_wp_error( $ai_response ) ) {
			wp_send_json_error( 'AI Error: ' . $ai_response->get_error_message() );
		}

		// Step 3: Parse AI response
		$parsed = $this->parse_ai_response( $ai_response );
		if ( is_wp_error( $parsed ) ) {
			wp_send_json_error( 'Parse Error: ' . $parsed->get_error_message() );
		}

		// Ensure all fixed sections are present (AI might miss some)
		$fixed_ids = $prompt_builder->get_fixed_section_ids();
		$returned_ids = array_column( $parsed['sections'], 'id' );
		foreach ( $fixed_ids as $fid ) {
			if ( ! in_array( $fid, $returned_ids, true ) ) {
				$parsed['sections'][] = [ 'id' => $fid, 'texts' => [] ];
			}
		}

		// Reorder sections to match fixed order
		$ordered = [];
		foreach ( $fixed_ids as $fid ) {
			foreach ( $parsed['sections'] as $s ) {
				if ( $s['id'] === $fid ) {
					$ordered[] = $s;
					break;
				}
			}
		}
		$parsed['sections'] = $ordered;

		// Step 4: Build Elementor data from selected sections
		$elementor_builder = new WPR_Elementor_Builder();
		$elementor_data = $elementor_builder->build( $parsed );

		if ( empty( $elementor_data ) ) {
			wp_send_json_error( 'Failed to build page structure. No valid sections were generated.' );
		}

		// Step 4b: Build original (unmodified) page for comparison
		$original_data = $elementor_builder->build_original( $parsed );

		// Step 5: Create WordPress pages
		$page_creator = new WPR_Page_Creator();
		$page_title = ! empty( $parsed['page_title'] ) ? $parsed['page_title'] : $args['business_name'] . ' - ' . ucfirst( $args['page_type'] );
		$result = $page_creator->create( $page_title, $elementor_data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( 'Page Creation Error: ' . $result->get_error_message() );
		}

		// Create original comparison page
		$original_result = $page_creator->create( $page_title . ' (Original)', $original_data );
		$original_url = ! is_wp_error( $original_result ) ? $original_result['view_url'] : '';

		wp_send_json_success( [
			'page_id'         => $result['page_id'],
			'edit_url'        => $result['edit_url'],
			'view_url'        => $result['view_url'],
			'original_url'    => $original_url,
			'title'           => $result['title'],
			'sections_used'   => count( $parsed['sections'] ),
			'ai_response_raw' => $parsed,
		] );
	}

	private function parse_ai_response( $response_text ) {
		// Log raw response for debugging
		error_log( 'WPR AI Builder raw response: ' . substr( $response_text, 0, 500 ) );

		$response_text = trim( $response_text );

		// Remove markdown code blocks if present
		if ( preg_match( '/```(?:json)?\s*([\s\S]*?)\s*```/', $response_text, $matches ) ) {
			$response_text = trim( $matches[1] );
		}

		// Try to find JSON object in the response - find first { and last }
		$first_brace = strpos( $response_text, '{' );
		$last_brace = strrpos( $response_text, '}' );
		if ( $first_brace !== false && $last_brace !== false && $last_brace > $first_brace ) {
			$response_text = substr( $response_text, $first_brace, $last_brace - $first_brace + 1 );
		}

		$data = json_decode( $response_text, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new \WP_Error( 'json_parse', 'AI returned invalid JSON: ' . json_last_error_msg() . ' | Raw start: ' . substr( $response_text, 0, 200 ) );
		}

		if ( empty( $data['sections'] ) || ! is_array( $data['sections'] ) ) {
			return new \WP_Error( 'no_sections', 'AI response missing sections array.' );
		}

		// Validate section IDs against fixed sections
		$prompt_builder = new WPR_Prompt_Builder();
		$valid_ids = $prompt_builder->get_fixed_section_ids();

		foreach ( $data['sections'] as $key => $section ) {
			if ( ! isset( $section['id'] ) || ! in_array( $section['id'], $valid_ids, true ) ) {
				unset( $data['sections'][ $key ] );
			}
		}

		$data['sections'] = array_values( $data['sections'] );

		return $data;
	}

	public function test_connection() {
		check_ajax_referer( 'wpr-ai-builder', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized.' );
		}

		$ai_client = new WPR_AI_Client();
		$result = $ai_client->test_connection();

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success( 'Connection successful!' );
	}

	public function save_settings() {
		check_ajax_referer( 'wpr-ai-builder', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized.' );
		}

		$provider = isset( $_POST['provider'] ) ? sanitize_text_field( wp_unslash( $_POST['provider'] ) ) : 'claude';
		$model = isset( $_POST['model'] ) ? sanitize_text_field( wp_unslash( $_POST['model'] ) ) : '';
		$api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

		// Validate provider
		if ( ! in_array( $provider, [ 'claude', 'openai' ], true ) ) {
			wp_send_json_error( 'Invalid provider.' );
		}

		update_option( 'wpr_ai_provider', $provider );

		if ( ! empty( $model ) ) {
			update_option( 'wpr_ai_model', $model );
		}

		if ( ! empty( $api_key ) ) {
			$encrypted = WPR_AI_Builder::encrypt_key( $api_key );
			update_option( 'wpr_ai_' . $provider . '_key', $encrypted );
		}

		wp_send_json_success( 'Settings saved.' );
	}
}
