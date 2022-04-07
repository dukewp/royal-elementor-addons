<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprPluginUpdateNotice {
    public function __construct() {
        // delete_option('wpr_plugin_update_dismiss_notice');

        if ( current_user_can('administrator') ) {
            if ( !get_option('wpr_plugin_update_dismiss_notice') ) {
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
        global $current_screen;

        if ( is_admin() ) {
            if ( 'royal-addons_page_wpr-templates-kit' === $current_screen->id ) {
                return;
            }

            echo '<div class="notice wpr-plugin-update-notice is-dismissible">
                        <div class="wpr-plugin-update-notice-logo">
                            <img src="'. WPR_ADDONS_ASSETS_URL .'/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3><span>New Feature</span><br> Royal Theme Builder</h3>
                            <p>
                                Royal Elementor Theme Builder lets you customize every fundamental part of your WordPress site<br> without coding including your Header, Footer, Archives, Posts, Default Pages, 404 Pages, etc..
                                <br><strong>Please Note:</strong> WooCommerce Products and Product Archives Templates are comming soon!.
                                <br><br><strong>New Theme Builder Template Kits:</strong>
                                <a href="https://demosites.royal-elementor-addons.com/personal-blog-v1/?ref=rea-plugin-backend-update-notice" target="_blank">Personal Blog</a>, 
                                <a href="https://demosites.royal-elementor-addons.com/food-blog-v1/?ref=rea-plugin-backend-update-notice" target="_blank">Food Blog</a>, 
                                <a href="https://demosites.royal-elementor-addons.com/magazine-blog-v1/?ref=rea-plugin-backend-update-notice" target="_blank">Magazine Blog</a>, 
                                <a href="https://demosites.royal-elementor-addons.com/travel-blog-v1/?ref=rea-plugin-backend-update-notice" target="_blank">Travel Blog</a>.
                            </p>
                            <br>
                            <div>
                                <a href="'. get_admin_url() .'admin.php?page=wpr-templates-kit" class="wpr-get-started-button button button-primary">Go to Templates Library</span></a>
                                <a href="'. get_admin_url() .'admin.php?page=wpr-theme-builder" class="wpr-get-started-button button button-secondary">Go to Theme Builder</span></a>
                            </div>
                        </div>
                        <div class="image-wrap"><img src="'. WPR_ADDONS_ASSETS_URL .'/img/theme-builder.png"></div>
                        <canvas id="wpr-notice-confetti"></canvas>
                </div>';
        }
    }
    
    public static function enqueue_scripts() {
        // Load Confetti
        wp_enqueue_script( 'wpr-confetti-js', WPR_ADDONS_URL .'assets/js/lib/confetti/confetti.min.js', ['jquery'] );

        // Scripts & Styles
        echo "
        <script>
        jQuery( document ).ready( function() {

            if ( jQuery('#wpr-notice-confetti').length ) {
                const canvas = document.getElementById('wpr-notice-confetti');
                const jsConfetti = new JSConfetti({ canvas });

                setTimeout(function(){
                    jsConfetti.addConfetti({
                      confettiRadius: 2,
                      confettiNumber: 800,
                    });
                }, 1000);
            }

            jQuery(document).on( 'click', '.wpr-plugin-update-notice .notice-dismiss', function() {
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
                position: relative;
                display: flex;
                align-items: center;
                margin-top: 20px;
                margin-bottom: 20px;
                padding: 30px;
                border: 0 !important;
                box-shadow: 0 0 5px rgb(0 0 0 / 0.1);

                padding-left: 40px;
            }

            .wpr-plugin-update-notice-logo {
                display: none;
                margin-right: 30px;
            }

            .wpr-plugin-update-notice-logo img {
                max-width: 100%;
            }

            .wpr-plugin-update-notice h3 {
                font-size: 36px;
                margin-top: 0;
                margin-bottom: 35px;
            }

            .wpr-plugin-update-notice h3 span {
              display: inline-block;
              margin-bottom: 15px;
              font-size: 12px;
              color: #fff;
              background-color: #f51f3d;
              padding: 2px 12px 4px;
              border-radius: 3px;
            }

            .wpr-plugin-update-notice p {
              margin-top: 10px;
              margin-bottom: 15px;
              font-size: 14px;
            }

            .wpr-get-started-button {
                padding: 5px 25px !important;
            }

            .wpr-get-started-button .dashicons {
              font-size: 12px;
              line-height: 28px;
            }
            
            .wpr-plugin-update-notice .image-wrap {
              margin-left: auto;
            }

            .wpr-plugin-update-notice .image-wrap img {
              zoom: 0.5;
            }

            @media screen and (max-width: 1366px) {
                .wpr-plugin-update-notice .image-wrap img {
                  zoom: 0.4;
                }
            }

            #wpr-notice-confetti {
              position: absolute;
              top: 50px;
              left: 0;
              width: 50%;
              height: 200px;
              pointer-events: none;
            }
        </style>";

        
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprPluginUpdateNotice();
}