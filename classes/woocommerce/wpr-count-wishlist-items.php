<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Count_Wishlist_Items setup
 *
 * @since 1.0
 */
class WPR_Count_Wishlist_Items { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_count_wishlist_items',[$this, 'count_wishlist_items'] );
        add_action( 'wp_ajax_nopriv_count_wishlist_items',[$this, 'count_wishlist_items'] );
    }

	// Add two new functions for handling cookies
	public function get_wishlist_from_cookie() {
		if (isset($_COOKIE['wpr_wishlist'])) {
			return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
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
       $wishlist_count = sizeof($wishlist);
       wp_send_json($product_data);

       wp_die();
    }
}

new WPR_Count_Wishlist_Items();