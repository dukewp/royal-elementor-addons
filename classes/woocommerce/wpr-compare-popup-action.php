<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Update_Mini_Wishlist setup
 *
 * @since 1.0
 */
class WPR_Compare_Popup_Action { 

    public function __construct() {
        add_action('wp_ajax_wpr_get_page_content', [$this, 'wpr_get_page_content']);
        add_action('wp_ajax_nopriv_wpr_get_page_content', [$this, 'wpr_get_page_content']);
    }

    function wpr_get_page_content() {
        $page_id = $_POST['wpr_compare_page_id'];
        
        // Check if the page was created with Elementor
        if (\Elementor\Plugin::$instance->db->is_built_with_elementor($page_id)) {
            $content = \Elementor\Plugin::$instance->frontend->get_builder_content($page_id);
            wp_send_json_success(array('content' => $content));
        } else {
            $page = get_post($page_id);  
            if ($page) {
                $content = apply_filters('the_content', $page->post_content);
                wp_send_json_success(array('content' => $content));
            } else {
                wp_send_json_error(array('message' => 'Page not found'));
            }
        }
        wp_die();
    }
}

new WPR_Compare_Popup_Action();