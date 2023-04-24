<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Form_Builder_Submissions setup
 *
 * @since 3.4.6
 */

 class WPR_Form_Builder_Submissions {

    public function __construct() {
        add_action('wp_ajax_wpr_form_builder_submissions' , [$this, 'add_to_submissions']);
        add_action('wp_ajax_nopriv_wpr_form_builder_submissions',[$this, 'add_to_submissions']);
        add_action('wp_ajax_wpr_submissions_meta' , [$this, 'submissions_meta']);
        add_action('wp_ajax_nopriv_wpr_submissions_meta',[$this, 'submissions_meta']);
    }

    public function add_to_submissions() {

        $new = [
            'post_status' => 'publish',
            'post_type' => 'wpr_submissions'
        ];
        
        $post_id = wp_insert_post( $new );
        foreach ( $_POST['form_content'] as $key => $value ) {
            update_post_meta($post_id, $key, [$value[0], $value[1]]);
        }

        update_post_meta($post_id, 'wpr_form_name', $_POST['form_name']);
        update_post_meta($post_id, 'wpr_form_id', $_POST['form_id']);
        update_post_meta($post_id, 'wpr_form_page', $_POST['form_page']);
        update_post_meta($post_id, 'wpr_form_page_id', $_POST['form_page_id']);
        update_post_meta($post_id, 'wpr_user_agent', sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ));
        update_post_meta($post_id, 'wpr_user_ip', Utilities::get_client_ip());
        
        if( $post_id ) {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'message' => 'Post created successfully',
				'status' => 'success'
            ));
        } else {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'message' => 'Something Went Wrong',
				'status' => 'error'
            ));
        }
    }

    public function submissions_meta() {
       global $post;
    //    if (isset($_GET['post']) && !empty($_GET['post'])) {
    //        $post_id = intval($_GET['post']);
    //        $post = get_post($post_id);
    //        $form_name = get_post_meta($post->ID, 'wpr_form_name', true);
    //        $form_id = get_post_meta($post->ID, 'wpr_form_id', true);
    //        $wpr_form_page = get_post_meta($post->ID, 'wpr_form_page', true);
    //        $wpr_form_page_id = get_post_meta($post->ID, 'wpr_form_page_id', true);

    //        $response = [
    //             'form_name'    => $form_name,
    //             'form_id'      => $form_id,
    //             'form_page'    => $wpr_form_page,
    //             'form_page_id' => $wpr_form_page_id
    //        ];
    
    //        wp_send_json($response);
    //    }
    }
 }

 new WPR_Form_Builder_Submissions();