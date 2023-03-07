<?php
namespace WprAddons\Classes;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Send_Email setup
 *
 * @since 3.4.6
 */

 class WPR_Send_Email {

    public function __construct() {
        add_action('wp_ajax_wpr_send_email' , [$this, 'send_email']);
        add_action('wp_ajax_nopriv_wpr_send_email',[$this, 'send_email']);
    }

    public function send_email() {
        // $name = $_POST['name'];
        // $email = $_POST['email'];
        // $message = $_POST['message'];
      
        // Set up email parameters
        $to = 'geobeatnic@gmail.com';
        $subject = 'New form submission';
        $body = "Message Body";
      
        // Send email using wp_mail() function
        $sent = wp_mail( $to, $subject, $body );
      
        if ( $sent ) {
            return 'Success wpr';
        } else {
            return 'Error wpr';
        }
      
        wp_die();
    }
 }

 new WPR_Send_Email();