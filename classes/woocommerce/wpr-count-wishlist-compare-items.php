<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Count_Wishlist_Compare_Items setup
 *
 * @since 1.0
 */
class WPR_Count_Wishlist_Compare_Items { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_nopriv_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_count_compare_items',[$this, 'count_compare_items'] );
        add_action( 'wp_ajax_nopriv_count_compare_items',[$this, 'count_compare_items'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
		if (isset($_COOKIE['wpr_wishlist'])) {
			return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
		}
		return array();
	}
    
    function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        }
        return array();
    }
    
    function count_wishlist_items() {
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }

        $product_data = [];

       $product_data['wishlist_count'] = sizeof($wishlist);
       $new_product_array = [];
            
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);

            if ($product) {
                $new_product_array[] = [
                    'product_id' => $product_id,
                    'product_image' => $product->get_image(),
                    'product_title' => $product->get_title(),
                    'product_url' => $product->get_permalink(),
                    'product_price' => $product->get_price_html(),
                ];
            }
        }

        $product_data['wishlist_items'] = $new_product_array;

       wp_send_json($product_data);

       wp_die();
    }
    
    function count_compare_items() {
        $user_id = get_current_user_id();
        
        if ($user_id > 0) {
            $compare = get_user_meta($user_id, 'wpr_compare', true);
            if (!$compare) {
                $compare = array();
            }
        } else {
            $compare = $this->get_compare_from_cookie();
        }

        $product_data = [];

       $product_data['compare_count'] = sizeof($compare);

       wp_send_json($product_data);

       wp_die();
    }
}

new WPR_Count_Wishlist_Compare_Items();