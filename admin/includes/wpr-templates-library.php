<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Includes\WPR_Templates_All;
use WprAddons\Admin\Includes\WPR_Templates_Shortcode;
use WprAddons\Admin\Includes\WPR_Templates_Popups;
use WprAddons\Admin\Includes\WPR_Templates_Actions;
use WprAddons\Admin\Templates\WPR_Templates_Blocks;
use WprAddons\Admin\Templates\WPR_Templates_Pages;

/**
 * WPR_Templates_Library setup
 *
 * @since 1.0
 */
class WPR_Templates_Library {

	/**
	** Constructor
	*/
	public function __construct() {

		// Register CPTs
		add_action( 'init', [ $this, 'register_templates_library_cpt' ] );
		add_action( 'template_redirect', [ $this, 'block_template_frontend' ] );

		// Templates Shortcode
		new WPR_Templates_Shortcode();

		// Popups
		new WPR_Templates_Popups();

		// All Templates
		new WPR_Templates_All();

		// Template Actions
		new WPR_Templates_Actions();

		// Template Actions
		new WPR_Templates_Blocks();

		// Template Actions
		new WPR_Templates_Pages();

	}

	/**
	** Register Templates Library
	*/
	public function register_templates_library_cpt() {

		$args = array(
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'wpr-addons' ),
		);

		register_post_type( 'wpr_templates', $args );

		$tax_args = [
			'hierarchical' 		=> true,
			'show_ui' 			=> true,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'query_var' 		=> is_admin(),
			'rewrite' 			=> false,
			'public' 			=> false,
		];
		
	    register_taxonomy( 'wpr_template_type', 'wpr_templates', $tax_args );

	}

	/**
	** Don't display on the frontend for non edit_posts capable users
	*/
	public function block_template_frontend() {
		if ( is_singular( 'wpr_templates' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

}

new WPR_Templates_Library();