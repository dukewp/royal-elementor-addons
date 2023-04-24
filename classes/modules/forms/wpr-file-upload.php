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

 class WPR_File_Upload {
    public function __construct() {
        add_action('wp_ajax_wpr_addons_upload_file', [$this, 'handle_file_upload']);
        add_action('wp_ajax_nopriv_wpr_addons_upload_file', [$this, 'handle_file_upload']);
    }
    
    public function handle_file_upload() {
        // Check if a file is uploaded.
        if (isset($_FILES['uploaded_file'])) {
            $file = $_FILES['uploaded_file'];
    
            // Set the upload directory.
		
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'] . '/wpr-addons/forms';

            wp_mkdir_p( $upload_path );
    
            // Generate a unique file name.
            $filename = wp_unique_filename($upload_path, $file['name']);
    
            // Move the uploaded file to the uploads directory.
            if (move_uploaded_file($file['tmp_name'], $upload_path . '/' . $filename)) {
                // Return the uploaded file's URL.
                wp_send_json_success(array(
                    'url' => $upload_dir['baseurl'] . '/wpr-addons/forms/' . $filename
                ));
            } else {
                wp_send_json_error(array(
                    'message' => 'Failed to upload the file.'
                ));
            }
        }
    
        wp_send_json_error(array(
            'message' => 'No file was uploaded.'
        ));
    }
 }

 new WPR_File_Upload();