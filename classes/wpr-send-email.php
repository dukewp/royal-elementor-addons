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
        
        $message_body = [];
        
        var_dump($_POST['mail_content']);
        foreach ( $_POST['mail_content'] as $key => $value ) {
            $message_body[] = $value[2] . ': ' . $value[1];
        }

        // $to = $_POST['email_to'];
        $to = 'geobeatnic@gmail.com';
        // $subject = $_POST['email_subject'];
        $subject = 'New Test Mail';
        $body = implode("<br>", $message_body);
        $headers = array('Content-Type: text/html; charset=UTF-8');
      
        // Send email using wp_mail() function
        $sent = wp_mail( $to, $subject, $body, $headers );
      
        if ( $sent ) {
            $response = 'Message sent successfully';
        } else {
            $response = 'Message could not be sent';
        }

        // Return response to AJAX request
        wp_send_json( $response );
    }
 }

 new WPR_Send_Email();