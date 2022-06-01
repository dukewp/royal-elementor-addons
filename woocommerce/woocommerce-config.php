<?php

class WPR_WooCommerce_Config {

	public function __construct() {
		

			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );
	}

	public function init() {
		add_action('wp_ajax_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		add_action('wp_ajax_nopriv_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		add_filter('woocommerce_single_product_carousel_options', [$this, 'wpr_update_woo_flexslider_options']);
		// add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );

		// Change number of products that are displayed per page (shop page)
		// add_filter( 'loop_shop_per_page', [$this, 'new_loop_shop_per_page'], 20 );

		// Rewrite WC Default Templates
		// add_filter( 'wc_get_template', [ $this, 'rewrite_default_wc_templates' ], 10, 3 );
	}

	public function add_cart_single_product_ajax() {
		add_action( 'wp_loaded', [ 'WC_Form_Handler', 'add_to_cart_action' ], 20 );
	
		if ( is_callable( [ 'WC_AJAX', 'get_refreshed_fragments' ] ) ) {
			WC_AJAX::get_refreshed_fragments();
		}
	
		die();
	}
	
	public function wpr_update_woo_flexslider_options( $options ) {
		$options['directionNav'] = true;
		return $options;
	}
	
	public function new_loop_shop_per_page( $cols ) {
	  // $cols contains the current number of products per page based on the value stored on Options –> Reading
	  // Return the number of products you wanna show per page.
	  $cols = 4;
	  return $cols;
	}

	public function rewrite_default_wc_templates( $located, $template_name ) {
		// Cart template
		if ( $template_name === 'cart/cart.php' ) {
			$located = WPR_ADDONS_PATH .'woocommerce/templates/cart.php';
		}

		// if ( $template_name === 'cart/cart-empty.php' ) {
		// 	$custom_template = $this->get_custom_empty_cart_template();

		// 	if ( $custom_template && 'default' !== $custom_template ) {
		// 		$located = jet_woo_builder()->get_template( 'woocommerce/cart/cart-empty.php' );
		// 	}
		// }
	}

}

new WPR_WooCommerce_Config();