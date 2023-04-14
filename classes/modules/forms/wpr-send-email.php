<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
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
        add_action('wp_ajax_wpr_form_builder_email' , [$this, 'send_email']);
        add_action('wp_ajax_nopriv_wpr_form_builder_email',[$this, 'send_email']);
    }

    public function send_email() {
        
        $message_body = [];
        
        foreach ( $_POST['form_content'] as $key => $value ) {
            $message_body[] = trim($value[2]) . ': ' . $value[1];
        }

        $meta_keys = get_option('wpr_meta_keys_'. $_POST['wpr_form_id']);
        $meta_fields = [];

		foreach ( $meta_keys as $metadata_type ) {
			switch ( $metadata_type ) {
				case 'date':
					$meta_fields['date'] = [
						'title' => esc_html__( 'Date', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'date_format' ) ),
					];
					break;

				case 'time':
					$meta_fields['time'] = [
						'title' => esc_html__( 'Time', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'time_format' ) ),
					];
					break;

				case 'page_url':
					$meta_fields['page_url'] = [
						'title' => esc_html__( 'Page URL', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? esc_url_raw( wp_unslash( get_option('wpr_referrer_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'page_title':
					$meta_fields['page_title'] = [
						'title' => esc_html__( 'Page Title', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? sanitize_text_field( wp_unslash( get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'user_agent':
					$meta_fields['user_agent'] = [
						'title' => esc_html__( 'User Agent', 'wpr-addons' ),
						'value' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
					];
					break;

				case 'remote_ip':
					$meta_fields['remote_ip'] = [
						'title' => esc_html__( 'Remote IP', 'wpr-addons' ),
						'value' => Utilities::get_client_ip(),
					];
					break;
                    
				case 'credit':
					$meta_fields['credit'] = [
						'title' => esc_html__( 'Powered by', 'wpr-addons' ),
						'value' => esc_html__( 'Royal Addons', 'wpr-addons' ), // is it necessary ?
					];
					break;
			}
		}

        $email_meta = [];

        foreach( $meta_fields as $key => $value ) {
            $email_meta[] = $value['title'] . ': ' . $value['value'];
        }

        $to = get_option('wpr_email_to_'. $_POST['wpr_form_id']);
        $subject = get_option('wpr_email_subject_'. $_POST['wpr_form_id']);
        $body = implode("<br>", $message_body) . '<br>' . '---' . '<br>' . '<br>' . implode("<br>", $email_meta);

		$cc_header = '';
		if ( ! empty( get_option('wpr_cc_header_'. $_POST['wpr_form_id']) ) ) {
			$cc_header = 'Cc: ' . get_option('wpr_cc_header_'. $_POST['wpr_form_id']);
		}

        $headers = array('Content-Type: text/html; charset=UTF-8', $cc_header);
      
        // Send email using wp_mail() function
        $sent = wp_mail( $to, $subject, $body, $headers);
      
        if ( $sent ) {
			wp_send_json_success(array(
				'action' => 'wpr_form_builder_email',
				'message' => 'Message Sent Successfully',
				'status' => 'success'
			));
        } else {
			wp_send_json_error(array(
				'action' => 'wpr_form_builder_email',
				'message' => 'Message could not be sent',
				'status' => 'error'
			));
        }
    }
 }

 new WPR_Send_Email();