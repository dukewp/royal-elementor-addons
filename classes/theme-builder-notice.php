<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprThemeBuilderNotice {
    public function __construct() {
        if ( current_user_can('administrator') ) {
            delete_option('wpr_theme_builder_dismiss_notice');
            if ( empty(get_option('wpr_theme_builder_dismiss_notice', false)) ) {
                add_action( 'admin_init', [$this, 'check_theme_builder_notice_condition'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_theme_builder_dismiss_notice', [$this, 'wpr_theme_builder_dismiss_notice'] );
    }

    public function check_theme_builder_notice_condition() {
        add_action( 'admin_notices', [$this, 'render_theme_builder_notice' ]);
    }
    
    public function wpr_theme_builder_dismiss_notice() {
        add_option( 'wpr_theme_builder_dismiss_notice', true );
    }

    public function render_theme_builder_notice() {
        global $pagenow;

        if ( is_admin() ) {

            echo '<div class="notice wpr-theme-builder-notice is-dismissible" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div class="wpr-theme-builder-notice-logo">
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
                jQuery(document).find('.wpr-theme-builder-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_theme_builder_dismiss_notice',
                    }
                });
              }); 
            });
        </script>

        <style>
            .wpr-theme-builder-notice {
            padding: 0 15px;
            }

            .wpr-theme-builder-notice-logo img {
                max-width: 100%;
            }

            .wpr-theme-builder-notice h3 {
            margin-bottom: 0;
            }

            .wpr-theme-builder-notice p {
                margin-bottom: 10px;
            }

            .wpr-theme-builder-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }
        </style>";
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprThemeBuilderNotice();
}