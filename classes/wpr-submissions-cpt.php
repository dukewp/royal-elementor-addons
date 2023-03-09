<?php
namespace WprAddons\Classes;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Add_To_Submissions setup
 *
 * @since 3.4.6
 */

 class WPR_Add_To_Submissions {

    public function __construct() {
        add_action('wp_ajax_wpr_add_to_submissions' , [$this, 'add_to_submissions']);
        add_action('wp_ajax_nopriv_wpr_add_to_submissions',[$this, 'add_to_submissions']);
    }

    public function add_to_submissions() {
        $new = array(
            'post_title' => $_POST['title'],
            'post_content' => $_POST['content'],
            'post_status' => 'publish',
            'post_type' => 'wpr_submissions'
        );
        
        $post_id = wp_insert_post( $new );

        update_post_meta($post_id, 'submitter_email', $_POST['email']);
        
        if( $post_id ){
            echo "Post successfully published!";
        } else {
            echo "Something went wrong, try again.";
        }
    }
 }

 new WPR_Add_To_Submissions();