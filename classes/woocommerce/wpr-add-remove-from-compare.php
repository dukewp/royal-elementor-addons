<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Add_Remove_From_Compare setup
 *
 * @since 1.0
 */
class WPR_Add_Remove_From_Compare { 

    /**
    ** Constructor
    */
    public function __construct() {
        // add_action('init', [$this, 'register_compare_cpt']);
        add_action( 'wp_ajax_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_nopriv_add_to_compare',[$this, 'add_to_compare'] );
        add_action( 'wp_ajax_remove_from_compare', [$this, 'remove_from_compare'] );
        add_action( 'wp_ajax_nopriv_remove_from_compare', [$this, 'remove_from_compare'] );
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
    
    function add_to_compare() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        if ( ! $compare ) {
            $compare = array();
        }
        if ( in_array( $product_id, $compare ) ) {
            wp_send_json_error( array( 'message' => esc_html__('Product is already in Compare.') ) );
            return;
        }
        $compare[] = $product_id;
        update_user_meta( get_current_user_id(), 'wpr_compare', $compare );
        wp_send_json_success();
    }
    
    function remove_from_compare() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
        if ( ! $compare ) {
            $compare = array();
        }
        $compare = array_diff( $compare, array( $product_id ) );
        update_user_meta( get_current_user_id(), 'wpr_compare', $compare );
        wp_send_json_success();
    }
}

new WPR_Add_Remove_From_Compare();