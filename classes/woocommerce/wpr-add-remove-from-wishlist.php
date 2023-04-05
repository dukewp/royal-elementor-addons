<?php
namespace WprAddons\Classes;

use WprAddons\Classes\Utilities;

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
        // add_action('init', [$this, 'register_wishlist_cpt']);
        add_action( 'wp_ajax_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_nopriv_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
        add_action( 'wp_ajax_nopriv_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
    }

    // // Register Post Type
    // function register_wishlist_cpt() {
    //     $args = array(
    //         'label'				  => esc_html__( 'Royal Wishlist', 'wpr-addons' ),
    //         'public'              => true,
    //         'publicly_queryable'  => true,
    //         'rewrite'             => false,
    //         'show_ui'             => true,
    //         'show_in_menu'        => true,
    //         'show_in_nav_menus'   => false,
    //         'exclude_from_search' => true,
    //         'capability_type'     => 'post',
    //         'supports'            => array( 'title', 'editor', 'elementor' ),
    //         'hierarchical'        => false,
    //     );
    
    //     register_post_type( 'wishlist', $args );
    // }
    
    function add_to_wishlist() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();
        
        // if ( ! $wishlist ) {
        //     $wishlist = array();
        // }
        // if ( in_array( $product_id, $wishlist ) ) {
        //     wp_send_json_error( array( 'message' => esc_html__('Product is already in wishlist.') ) );
        //     return;
        // }
        // $wishlist[] = $product_id;
        // update_user_meta( get_current_user_id(), 'wpr_wishlist', $wishlist );
        
        // NEW CODE
        if ($user_id > 0) {
            $wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        if (in_array($product_id, $wishlist)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in wishlist.')));
            return;
        }
    
        $wishlist[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }
    
    function remove_from_wishlist() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'wpr_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        $wishlist = array_diff($wishlist, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'wpr_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }
    
        // $wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
        // if ( ! $wishlist ) {
        //     $wishlist = array();
        // }
        // $wishlist = array_diff( $wishlist, array( $product_id ) );
        // update_user_meta( get_current_user_id(), 'wpr_wishlist', $wishlist );

        wp_send_json_success();
    }
    
    function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        }
        return array();
    }
    
    function set_wishlist_to_cookie($wishlist) {
        setcookie('wpr_wishlist', json_encode($wishlist), time() + (86400 * 10), '/'); // Expires in 7 days
    }
}

new WPR_Add_Remove_From_Wishlist();