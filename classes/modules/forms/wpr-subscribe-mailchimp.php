<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * WPR_Subscribe_Mailchimp setup
 *
 * @since 3.4.6
 */

 class WPR_Subscribe_Mailchimp {

    public function __construct() {
        // For form builder
        add_action( 'wp_ajax_wpr_form_builder_mailchimp', [$this, 'wpr_form_builder_mailchimp'] );
        add_action( 'wp_ajax_nopriv_wpr_form_builder_mailchimp', [$this, 'wpr_form_builder_mailchimp'] );
    }

	/**
	** Mailchimp AJAX Subscribe
	*/
	public static function wpr_form_builder_mailchimp() {
		// API Key
        $api_key = get_option('wpr_mailchimp_api_key') ? get_option('wpr_mailchimp_api_key') : '';

        $api_key_sufix = explode( '-', $api_key )[1];

        // // List ID
        $list_id = isset($_POST['listId']) ? sanitize_text_field(wp_unslash($_POST['listId'])) : '';

        // // Get Available Fileds (PHPCS - fields are sanitized later on input)
		$fields = isset($_POST['form_data']) ? $_POST['form_data'] : []; // phpcs:ignore
        // wp_parse_str( $available_fields, $fields );
			// 'ADDRESS' => [
			// 	'addr1' => !empty ( $fields['address_field'] ) ? sanitize_text_field($fields['address_field']) : '',
			// 	'addr2' => '', // You can add a second address line if needed
			// 	'city' => '',  // Add city information
			// 	'state' => '', // Add state information
			// 	'zip' => '',   // Add zip code information
			// 	'country' => '',
			// ],

			// 'id' => $fields['group_id'],
			// 'groups' => $fields['group_name']

        // Merge Additional Fields
        $merge_fields = [
            'FNAME' => !empty( $fields['first_name_field'] ) ? sanitize_text_field($fields['first_name_field']) : '',
            'LNAME' => !empty( $fields['last_name_field'] ) ? sanitize_text_field($fields['last_name_field']) : '',
			'PHONE' => !empty ( $fields['phone_field'] ) ? sanitize_text_field($fields['phone_field']) : '',
			'ADDRESS' => !empty ( $fields['address_field'] ) ? sanitize_text_field($fields['address_field']) : '',
			'BIRTHDAY' => !empty ( $fields['birthday_field'] ) ? sanitize_text_field($fields['birthday_field']) : '',
			// 'GROUPS' => [
			// 	[
			// 		'id' => $fields['group_id']
			// 	]
			// ]
		];

        // API URL
        $api_url = 'https://'. $api_key_sufix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(sanitize_text_field($fields['email_field'])));

        // API Args
        $api_args = [
			'method' => 'PUT',
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'apikey '. $api_key,
			],
			'body' => json_encode([
				'email_address' => sanitize_text_field($fields[ 'email_field' ]),
				'status' => 'subscribed',
				'merge_fields' => $merge_fields,
			]),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error($request) ) {
			$request = json_decode( wp_remote_retrieve_body($request) );

			// Set Status
			if ( ! empty($request) ) {
				if ($request->status == 'subscribed') {

					wp_send_json_success(array(
						'action' => 'wpr_form_builder_mailchimp',
						'status' => 'subscribed'
					));

				} else {
					wp_send_json_error([ 
						'action' => 'wpr_form_builder_mailchimp',
						'status' => $request->title
					]);
				}
			}
		} else {

			wp_send_json_error([ 
				'action' => 'wpr_form_builder_mailchimp',
				'request' => $request
			]);
		}
	}
 }

 new WPR_Subscribe_Mailchimp();