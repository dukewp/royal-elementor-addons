<?php

namespace WprAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Classes\Utilities;

/**
** WPR_Templates_Loop setup
*/
class WPR_Templates_Loop {

	/**
	** Loop Through Custom Templates
	*/
	public static function get_theme_builder_templates( $template ) {
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
		echo '<ul class="wpr-'. $template .' wpr-my-templates-list striped">';

			if ( ! empty( $user_templates ) ) {
				foreach ( $user_templates as $user_template ) {
					$slug = $user_template->post_name;
					$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );

					echo '<li>';
				        echo '<div class="wpr-title">'. esc_html($user_template->post_title) .'</div>';

				        echo '<div class="wpr-action-buttons">';
							// Activate
							echo '<span class="wpr-template-conditions button button-primary" data-slug="'. esc_attr($slug) .'">'. esc_html__( 'Conditions', 'wpr-addons' ) .'</span>';
							// Edit
							echo '<a href="'. esc_url($edit_url) .'" class="wpr-edit button button-primary">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
							// Delete
							echo '<span class="wpr-delete button button-primary" data-slug="'. esc_attr($slug) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'wpr-addons' ) .'"><span class="dashicons dashicons-no-alt"></span></span>';
				        echo '</div>';
					echo '</li>';
				}
			} else {
				// echo '<li>You don\'t have any headers yet!</li>'; //TODO: add Styles to this message
			}

		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();

	}

	/**
	** Loop Through My Templates
	*/
	public static function get_elementor_saved_templates() {

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
						echo '<a href="'. esc_url($edit_url) .'" class="button button-primary">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
						echo '<span class="wpr-delete button button-primary" data-slug="'. esc_attr($user_template->post_name) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'wpr-addons' ) .'"><span class="dashicons dashicons-no-alt"></span></span>';
					echo '</span>';
				echo '</li>';
			}
		}
		
		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();
	}

	/**
	** Render Create Template Popup
	*/
	public static function create_template_popup() {
	?>

    <!-- Custom Template Popup -->
    <div class="wpr-user-template-popup-wrap wpr-admin-popup-wrap">
        <div class="wpr-user-template-popup wpr-admin-popup">
        	<header>
	            <h2><?php esc_html_e( 'Templates Help You Work Efficiently!', 'wpr-addons' ); ?></h2>
	            <p><?php esc_html_e( 'Use templates to create the different pieces of your site, and reuse them with one click whenever needed.', 'wpr-addons' ); ?></p>
			</header>
			
            <input type="text" name="user_template_title" class="wpr-user-template-title" placeholder="<?php esc_html_e( 'Enter Template Title', 'wpr-addons' ); ?>">
            <input type="hidden" name="user_template_type" class="user-template-type">
            <span class="wpr-create-template"><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="close-popup dashicons dashicons-no-alt"></span>
        </div>
    </div>

	<?php
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