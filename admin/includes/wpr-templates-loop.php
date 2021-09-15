<?php

namespace WprAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Includes\WPR_Templates_Data;
use WprAddons\Classes\Utilities;

/**
** WPR_Templates_Loop setup
*/
class WPR_Templates_Loop {

	/**
	** Loop Through Templates
	*/
	public static function get_predefined_templates( $template ) {
		// Loop User Templates
		WPR_Templates_Loop::get_user_templates( $template );

		// Deny if NOT Predefined
		if ( strpos( $template, 'other' ) === 0 ) {
			return;
		}

		// Loop Predefined Templates
		$data = WPR_Templates_Data::get( $template );
		$image_url = 'https://wp-royal.com/test/elementor/'. $template .'/images';

		foreach ( $data as $item ) {
			$slug = sanitize_title( $item );

	        echo '<div class="wpr-'. $template .' template-grid-item">';
	            echo '<div class="wpr-screenshot">';
	                echo '<img src="'. esc_attr($image_url .'/'. $slug) .'.png">';
	            echo '</div>';
	            echo '<footer>';
	                echo '<div class="wpr-title">'. $item .'</div>';
	                WPR_Templates_Loop::render_action_buttons( $slug );
	            echo '</footer>';
	        echo '</div>';
		}
	}

	/**
	** Loop Through Custom Templates
	*/
	public static function get_user_templates( $template ) {
		// Default Image
		$image_url = 'https://wp-royal.com/test/elementor/images';

		// WP_Query arguments
		$args = array (
			'post_type'   => array( 'wpr_templates' ),
			'post_status' => array( 'publish' ),
			'posts_per_page' => -1,
			'tax_query'   => array(
				array(
					'taxonomy' => 'wpr_template_type',
					'field'    => 'slug',
					'terms'    => [ $template, 'user' ],
					'operator' => 'AND'
				)
			)
		);

		// The Query
		$user_templates = get_posts( $args );

		// The Loop
		if ( ! empty( $user_templates ) ) {
			foreach ( $user_templates as $user_template ) {
				$slug = $user_template->post_name;
				$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );

				echo '<div class="wpr-'. $template .' template-grid-item">';
				    echo '<div class="wpr-screenshot">';
				    	if ( '' !== get_the_post_thumbnail($user_template->ID) ) {
				    		echo get_the_post_thumbnail($user_template->ID);
				    	} else {
				        	echo '<img src="'. esc_url($image_url) .'/custom.png">';
				    	}
				    echo '</div>';
				    echo '<footer>';
				        echo '<div class="wpr-title">'. esc_html($user_template->post_title) .'</div>';

				        echo '<div class="wpr-action-buttons">';
							// Activate
							echo '<span class="button wpr-activate" data-slug="'. esc_attr($slug) .'">'. esc_html__( 'Activate', 'wpr-addons' ) .'</span>';
							// Edit
							echo '<a href="'. esc_url($edit_url) .'" class="wpr-edit button">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
							// Delete
							echo '<span class="wpr-reset button" data-slug="'. esc_attr($slug) .'">'. esc_html__( 'Delete', 'wpr-addons' ) .'</span>';
				        echo '</div>';
				    echo '</footer>';
				echo '</div>';
			}
		}

		// Restore original Post Data
		wp_reset_postdata();

	}

	/**
	** Loop Through My Templates
	*/
	public static function get_my_templates() {

		// WP_Query arguments
		$args = array (
			'post_type'   => array( 'elementor_library' ),
			'post_status' => array( 'publish' ),
			'meta_key' 	  => '_elementor_template_type',
			'meta_value'  => ['page', 'section'],
			'numberposts'  => -1
		);

		// The Query
		$user_templates = get_posts( $args );

		// My Templates List
		echo '<ul class="wpr-my-templates-list striped">';

		// The Loop
		if ( ! empty( $user_templates ) ) {
			foreach ( $user_templates as $user_template ) {
				// Edit URL
				$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );

				// List
				echo '<li>';
					echo '<h3>'. $user_template->post_title .'</h3>';
					echo '<span class="wpr-action-buttons">';
						echo '<a href="'. esc_url($edit_url) .'"class="button button-primary">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
						echo '<span class="wpr-reset button" data-slug="'. esc_attr($user_template->post_name) .'"><span class="dashicons dashicons-no-alt"></span></span>';
					echo '</span>';
				echo '</li>';
			}
		}
		
		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();
	}

	/**
	** Render Action Buttons
	*/
	public static function render_action_buttons( $slug ) {
		 echo '<div class="wpr-action-buttons">';

			// Import
			$text = esc_html__( 'Import', 'wpr-addons' );
			$import_class = ' wpr-import';

			// Activate
			if ( false !== WPR_Templates_Loop::template_exists($slug) ) {
				$text = esc_html__( 'Activate', 'wpr-addons' );
				$import_class = ' wpr-activate';
			}

			// Edit
			$edit_url = str_replace( 'edit', 'wpr-addons', get_edit_post_link( Utilities::get_template_id( $slug ) ) );
			$hidden_class = false !== WPR_Templates_Loop::template_exists($slug) ? '' : ' hidden';

			// Preview
			echo '<a class="wpr-preview button">'. esc_html__( 'Preview', 'wpr-addons' ) .'</a>';
			// Import/Activate
			echo '<span class="button'. esc_attr($import_class) .'" data-slug="'. esc_attr($slug) .'">'. esc_html($text) .'</span>';
			// Edit
			echo '<a href="'. esc_url($edit_url) .'" class="wpr-edit button'. esc_attr($hidden_class) .'">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
			// Reset
			echo '<span class="wpr-reset button'. esc_attr($hidden_class) .'" data-slug="'. esc_attr($slug) .'">'. esc_html__( 'Reset', 'wpr-addons' ) .'</span>';

		echo '</div>';
	}

	/**
	** Check if Library Template Exists
	*/
	public static function template_exists( $slug ) {
		$result = false;
		$wpr_templates = get_posts( ['post_type' => 'wpr_templates', 'posts_per_page' => '-1'] );

		foreach ( $wpr_templates as $post ) {

			if ( $slug === $post->post_name ) {
				$result = true;
			}
		}

		return $result;
	}

}