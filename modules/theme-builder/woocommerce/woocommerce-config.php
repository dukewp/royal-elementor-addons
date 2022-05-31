<?php
function add_cart_single_product_ajax() {
	
	add_action( 'wp_loaded', [ 'WC_Form_Handler', 'add_to_cart_action' ], 20 );

	if ( is_callable( [ 'WC_AJAX', 'get_refreshed_fragments' ] ) ) {
		WC_AJAX::get_refreshed_fragments();
	}

	die();

}

function wpr_update_woo_flexslider_options( $options ) {

	$options['directionNav'] = true;

	return $options;
}

add_action('wp_ajax_wpr_addons_add_cart_single_product', 'add_cart_single_product_ajax');
add_action('wp_ajax_nopriv_wpr_addons_add_cart_single_product', 'add_cart_single_product_ajax');
add_filter('woocommerce_single_product_carousel_options', 'wpr_update_woo_flexslider_options');
// add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );




/**
 * Change number of products that are displayed per page (shop page)
 */
// add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {

  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 4;
  return $cols;
}