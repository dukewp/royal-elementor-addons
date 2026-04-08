<?php
namespace WprAddons\Admin\AiBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_Page_Creator {

	/**
	 * Create a WordPress page with Elementor data
	 */
	public function create( $title, $elementor_data ) {
		$page_id = wp_insert_post( [
			'post_title'  => sanitize_text_field( $title ),
			'post_status' => 'draft',
			'post_type'   => 'page',
			'post_content' => '',
		] );

		if ( is_wp_error( $page_id ) ) {
			return $page_id;
		}

		// Save Elementor data
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $elementor_data ) ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
		update_post_meta( $page_id, '_wp_page_template', 'elementor_canvas' );

		// Mark as AI-generated
		update_post_meta( $page_id, '_wpr_ai_generated', '1' );
		update_post_meta( $page_id, '_wpr_ai_generated_date', current_time( 'mysql' ) );

		// Generate Elementor CSS
		$this->generate_elementor_css( $page_id );

		return [
			'page_id'    => $page_id,
			'edit_url'   => admin_url( 'post.php?post=' . $page_id . '&action=elementor' ),
			'view_url'   => get_permalink( $page_id ),
			'title'      => get_the_title( $page_id ),
		];
	}

	private function generate_elementor_css( $page_id ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		// Clear Elementor cache for this post
		if ( method_exists( \Elementor\Plugin::$instance->files_manager, 'clear_cache' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}
}
