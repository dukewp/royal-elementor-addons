<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprPluginUpdateNotice {
    public function __construct() {
        if ( current_user_can('administrator') ) {
            if ( empty(get_option('wpr_plugin_update_dismiss_notice', false)) ) {
                add_action( 'admin_init', [$this, 'render_notice'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_plugin_update_dismiss_notice', [$this, 'wpr_plugin_update_dismiss_notice'] );
    }

    public function render_notice() {
        add_action( 'admin_notices', [$this, 'render_plugin_update_notice' ]);
    }
    
    public function wpr_plugin_update_dismiss_notice() {
        add_option( 'wpr_plugin_update_dismiss_notice', true );
    }

    public function render_plugin_update_notice() {
        global $pagenow;

        if ( is_admin() ) {

            echo '<div class="notice wpr-plugin-update-notice is-dismissible" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div class="wpr-plugin-update-notice-logo">
                            <img src="' . WPR_ADDONS_ASSETS_URL . '/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3>Introducing Royal Theme Builder!</h3>
                            <p>Designer-made premade blocks which can be imported in one click.</p>
                            <p>
                                <a href="' . get_admin_url() . 'admin.php?page=wpr-theme-builder" target="_blank" class="wpr-you-deserve-it button button-primary">Click Me!</a>
                            </p>
                        </div>
                </div>';
        }
    }
    
    public static function enqueue_scripts() {
        echo "
        <script>
        jQuery( document ).ready( function() {
            jQuery(document).on( 'click', '.notice-dismiss', function() {
                jQuery(document).find('.wpr-plugin-update-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_plugin_update_dismiss_notice',
                    }
                });
              }); 
            });
        </script>

        <style>
            .wpr-plugin-update-notice {
              padding: 10px 20px;
              border-top: 0;
              border-bottom: 0;
            }

            .wpr-plugin-update-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }

            .wpr-plugin-update-notice-logo img {
                max-width: 100%;
            }

            .wpr-plugin-update-notice h3 {
              margin-bottom: 0;
            }

            .wpr-plugin-update-notice p {
              margin-top: 3px;
              margin-bottom: 15px;
            }
        </style>";
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprPluginUpdateNotice();
}