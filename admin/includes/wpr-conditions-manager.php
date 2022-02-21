<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Conditions_Manager setup
 *
 * @since 1.0
 */
class WPR_Conditions_Manager {

    /**
    ** Header & Footer Conditions
    */
    public static function header_footer_display_conditions( $conditions ) {
        $template = NULL;

        // Custom
        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
	        if ( !empty($conditions) ) {

				// Archive Pages (includes search)
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions );
				}

	        	// Single Pages
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions );
				}

	        }
        } else {
        	$template = Utilities::get_template_slug( $conditions, 'global' );
        }

        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {

        	if ( 'header' === Utilities::get_wpr_template_type(get_the_ID()) || 'footer' ===Utilities::get_wpr_template_type(get_the_ID()) ) {
        		$template = NULL;
        	}
        }

	    return $template;
    }

    /**
    ** Canvas Content Conditions
    */
    public static function canvas_page_content_display_conditions() {
        $template = NULL;

		// Get Conditions
		$archives = json_decode( get_option( 'wpr_archive_conditions' ), true );
		$singles  = json_decode( get_option( 'wpr_single_conditions' ), true );

        // Custom
        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {

	        if ( !empty($archives) || !empty($singles) ) {

				// Archive Pages (includes search)
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $archives ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $archives );
				}

		    	// Single Pages
				if ( !is_null( \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $singles ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $singles );
				}

	        }
        } else {//TODO: Set kinda "ALL" for default (free version)
        	// $template = Utilities::get_template_slug( $conditions, 'global' );
        }

	    return $template;
    }
}