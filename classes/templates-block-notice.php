<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprTemplatesBlockNotice {
    public function __construct() {
        if ( current_user_can('administrator') ) {
            if ( empty(get_option('wpr_templates_block_dismiss_notice', false)) ) {
                add_action( 'admin_init', [$this, 'check_test_notice_condition'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_templates_block_dismiss_notice', [$this, 'wpr_templates_block_dismiss_notice'] );
    }

    public function check_test_notice_condition() {
        add_action( 'admin_notices', [$this, 'render_test_notice' ]);
    }
    
    public function wpr_templates_block_dismiss_notice() {
        add_option( 'wpr_templates_block_dismiss_notice', true );
    }

    public function render_test_notice() {
        global $pagenow;

        if ( is_admin() ) {

            echo '<div class="notice wpr-test-notice is-dismissible" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div class="wpr-test-notice-logo">
                            <img src="' . WPR_ADDONS_ASSETS_URL . '/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3>Introducing Elementor Premade Blocks!!</h3>
                            <p>Designer-made premade blocks which can be imported in one click.</p
                            <p>
                                <a href="https://wordpress.org/support/plugin/royal-elementor-addons/reviews/?filter=5#new-post" target="_blank" class="wpr-you-deserve-it button button-primary">Click Me!</a>
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
                jQuery(document).find('.wpr-test-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_templates_block_dismiss_notice',
                    }
                });
              }); 
            });
        </script>

        <style>
            .wpr-test-notice {
            padding: 0 15px;
            }

            .wpr-test-notice-logo img {
                max-width: 100%;
            }

            .wpr-test-notice h3 {
            margin-bottom: 0;
            }

            .wpr-test-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }
        </style>";
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprTemplatesBlockNotice();
}