<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Add_Remove_From_Wishlist setup
 *
 * @since 1.0
 */
class WPR_Add_Remove_From_Wishlist { 

    /**
    ** Constructor
    */
    public function __construct() {
        add_action( 'wp_ajax_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_nopriv_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
        add_action( 'wp_ajax_nopriv_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
    }

    function add_to_wishlist() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $wishlist = get_user_meta( get_current_user_id(), 'wishlist', true );
        if ( ! $wishlist ) {
            $wishlist = array();
        }
        if ( in_array( $product_id, $wishlist ) ) {
            wp_send_json_error( array( 'message' => esc_html__('Product is already in wishlist.') ) );
            return;
        }
        $wishlist[] = $product_id;
        update_user_meta( get_current_user_id(), 'wishlist', $wishlist );
        wp_send_json_success();
    }
    
    function remove_from_wishlist() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $wishlist = get_user_meta( get_current_user_id(), 'wishlist', true );
        if ( ! $wishlist ) {
            $wishlist = array();
        }
        $wishlist = array_diff( $wishlist, array( $product_id ) );
        update_user_meta( get_current_user_id(), 'wishlist', $wishlist );
        wp_send_json_success();
    }
}

new WPR_Add_Remove_From_Wishlist();