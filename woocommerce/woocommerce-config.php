<?php

class WPR_WooCommerce_Config {

	public function __construct() {
		add_action('wp_ajax_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		add_action('wp_ajax_nopriv_wpr_addons_add_cart_single_product', [$this, 'add_cart_single_product_ajax']);
		add_filter('woocommerce_single_product_carousel_options', [$this, 'wpr_update_woo_flexslider_options']);
		// add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );

		// Change number of products that are displayed per page (shop page)
		// add_filter( 'loop_shop_per_page', [$this, 'new_loop_shop_per_page'], 20 );

		// Rewrite WC Default Templates
		add_filter( 'wc_get_template', [ $this, 'rewrite_default_wc_templates' ], 10, 3 );

		add_filter( 'woocommerce_add_to_cart_fragments', [$this, 'wc_refresh_mini_cart_count']);
	}

	function wc_refresh_mini_cart_count($fragments) {
		ob_start();
		$items_count = WC()->cart->get_cart_contents_count();
		?>
		<span class="wpr-mini-cart-icon-count"><?php echo $items_count ? $items_count : '&nbsp;'; ?></span>
		<?php
		$fragments['.wpr-mini-cart-icon-count'] = ob_get_clean();

		ob_start();
		$sub_total = WC()->cart->get_cart_subtotal();
		?>
				<span class="wpr-mini-cart-btn-price">
					<?php
							echo $sub_total; 
					?>
				</span>
		<?php
		$fragments['.wpr-mini-cart-btn-price'] = ob_get_clean();

		return $fragments;
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
			$located = WPR_ADDONS_PATH .'woocommerce/templates/cart/cart.php';
		}

		// Mini-cart template
		if ( $template_name === 'cart/mini-cart.php') {
			$located = WPR_ADDONS_PATH .'woocommerce/templates/cart/mini-cart.php';
		}

		// if ( $template_name === 'cart/cart-empty.php' ) {

		// }

		// if ( $template_name === 'checkout/form-checkout.php' ) {

		// }

		return $located;
	}

}

new WPR_WooCommerce_Config();