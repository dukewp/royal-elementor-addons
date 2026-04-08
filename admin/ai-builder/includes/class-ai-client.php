<?php
namespace WprAddons\Admin\AiBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_AI_Client {

	private $provider;
	private $api_key;
	private $model;

	public function __construct( $provider = null, $api_key = null, $model = null ) {
		$this->provider = $provider ?: \WprAddons\Admin\AiBuilder\WPR_AI_Builder::get_ai_provider();
		$this->api_key  = $api_key ?: \WprAddons\Admin\AiBuilder\WPR_AI_Builder::get_ai_api_key();
		$this->model    = $model ?: \WprAddons\Admin\AiBuilder\WPR_AI_Builder::get_ai_model();
	}

	public function send_message( $system_prompt, $user_message ) {
		if ( empty( $this->api_key ) ) {
			return new \WP_Error( 'no_api_key', 'API key is not configured.' );
		}

		if ( 'claude' === $this->provider ) {
			return $this->call_claude( $system_prompt, $user_message );
		}

		return $this->call_openai( $system_prompt, $user_message );
	}

	private function call_claude( $system_prompt, $user_message ) {
		$response = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
			'timeout' => 120,
			'headers' => [
				'Content-Type'      => 'application/json',
				'x-api-key'         => $this->api_key,
				'anthropic-version' => '2023-06-01',
			],
			'body' => wp_json_encode( [
				'model'      => $this->model,
				'max_tokens' => 8192,
				'system'     => $system_prompt,
				'messages'   => [
					[ 'role' => 'user', 'content' => $user_message ],
				],
			] ),
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code !== 200 ) {
			$msg = isset( $body['error']['message'] ) ? $body['error']['message'] : 'Unknown API error';
			return new \WP_Error( 'api_error', $msg );
		}

		if ( isset( $body['content'][0]['text'] ) ) {
			return $body['content'][0]['text'];
		}

		return new \WP_Error( 'parse_error', 'Could not parse AI response.' );
	}

	private function call_openai( $system_prompt, $user_message ) {
		$response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', [
			'timeout' => 120,
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $this->api_key,
			],
			'body' => wp_json_encode( [
				'model'           => $this->model,
				'max_tokens'      => 4096,
				'response_format' => [ 'type' => 'json_object' ],
				'messages'        => [
					[ 'role' => 'system', 'content' => $system_prompt ],
					[ 'role' => 'user', 'content' => $user_message ],
				],
			] ),
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code !== 200 ) {
			$msg = isset( $body['error']['message'] ) ? $body['error']['message'] : 'Unknown API error';
			return new \WP_Error( 'api_error', $msg );
		}

		if ( isset( $body['choices'][0]['message']['content'] ) ) {
			return $body['choices'][0]['message']['content'];
		}

		return new \WP_Error( 'parse_error', 'Could not parse AI response.' );
	}

	public function test_connection() {
		$result = $this->send_message(
			'You are a test. Respond in json format with exactly: {"status":"ok"}',
			'Test connection. Reply with json.'
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}
}
