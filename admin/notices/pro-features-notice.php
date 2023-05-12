<?php 
namespace WprAddons\Admin\Notices;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprProFeaturesNotice {
    public function __construct() {

        if ( current_user_can('administrator') ) {

            // delete_option('wpr_pro_features_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']);

            if ( !get_option('wpr_pro_features_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']) ) {
                add_action( 'admin_init', [$this, 'render_notice'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_pro_features_dismiss_notice', [$this, 'wpr_pro_features_dismiss_notice'] );
    }

    public function render_notice() {
        add_action( 'admin_notices', [$this, 'render_pro_features_notice' ]);
    }
    
    public function wpr_pro_features_dismiss_notice() {
        add_option( 'wpr_pro_features_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version'], true );
    }

    public function render_pro_features_notice() {
        global $current_screen;

        if ( is_admin() ) {
            echo '<div class="wpr-pro-features-notice-wrap">';
            echo '<div class="notice wpr-pro-features-notice is-dismissible">
                        <div class="wpr-pro-features-notice-logo">
                            <img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3><span>New Features</span><br>Pro Features Notice</h3>
                            <p>We are excited to announce that we have added new Widgets, Template Kits, and other Elementor<br> features to enhance your website building experience. Stay tuned for the weekly updates!</p>
                            <ul class="wpr-new-widgets-list">
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-mega-menu-widget/?ref=rea-plugin-backend-update-notice">Mega Menu</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-offcanvas-menu-widget/?ref=rea-plugin-backend-update-notice">Off-Canvas Content</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-instagram-feed-widget/?ref=rea-plugin-backend-update-notice">Instagram Feed</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-twitter-feed-widget/?ref=rea-plugin-backend-update-notice">Twitter Feed</a></li>
                                <li><a target="_blank" href="https://royal-elementor-addons.com/elementor-image-accordion-widget/?ref=rea-plugin-backend-update-notice">Image Accordion</a></li>
                            </ul>
                        </div>
                        <div class="image-wrap"><img src="'. esc_url(WPR_ADDONS_ASSETS_URL) .'img/new-theme-builder.png"></div>
                        <canvas id="wpr-notice-confetti"></canvas>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    public static function enqueue_scripts() {
        // Load Confetti
        wp_enqueue_script( 'wpr-confetti-js', WPR_ADDONS_URL .'assets/js/admin/lib/confetti/confetti.min.js', ['jquery'] );

        // Scripts & Styles
        echo "
        <script>
        jQuery( document ).ready( function($) {

            $('body').addClass('wpr-pro-features-body');
            $(document).find('.wpr-pro-features-notice-wrap').css('opacity', 1);

            if ( jQuery('#wpr-notice-confetti').length ) {
                const wprConfetti = confetti.create( document.getElementById('wpr-notice-confetti'), {
                    resize: true
                });

                setTimeout( function () {
                    wprConfetti( {
                        particleCount: 150,
                        origin: { x: 1, y: 2 },
                        gravity: 0.3,
                        spread: 50,
                        ticks: 150,
                        angle: 120,
                        startVelocity: 60,
                        colors: [
                            '#0e6ef1',
                            '#f5b800',
                            '#ff344c',
                            '#98e027',
                            '#9900f1',
                        ],
                    } );
                }, 500 );

                setTimeout( function () {
                    wprConfetti( {
                        particleCount: 150,
                        origin: { x: 0, y: 2 },
                        gravity: 0.3,
                        spread: 50,
                        ticks: 200,
                        angle: 60,
                        startVelocity: 60,
                        colors: [
                            '#0e6ef1',
                            '#f5b800',
                            '#ff344c',
                            '#98e027',
                            '#9900f1',
                        ],
                    } );
                }, 900 );
            }

            jQuery(document).on( 'click', '.wpr-pro-features-notice .notice-dismiss', function() {

                jQuery('body').removeClass('wpr-pro-features-body');

                jQuery(document).find('.wpr-pro-features-notice-wrap').fadeOut();
                jQuery(document).find('.wpr-pro-features-notice').slideUp();
                console.log('works update dismiss');
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_pro_features_dismiss_notice',
                    }
                });
              }); 
            });
        </script>

        <style>
            .wpr-pro-features-body {
                overflow: hidden;
            }

            .wpr-pro-features-notice-wrap {
                position: absolute;
                display: block;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 999;
                background-color: rgba(0, 0, 0, 0.2);
                opacity: 0;
            }

            .wpr-pro-features-notice {
                position: absolute !important;
                top: 20%;
                left: 20%;
                height: 60%;
                width: 60%;
                display: flex !important;
                align-items: center;
                margin-top: 20px;
                margin-bottom: 20px;
                padding: 30px;
                border: 0 !important;
                box-shadow: 0 0 5px rgb(0 0 0 / 0.1);
                padding-left: 40px;
                z-index: 999;
            }

            .wpr-pro-features-notice-logo {
                display: none;
                margin-right: 30px;
            }

            .wpr-pro-features-notice-logo img {
                max-width: 100%;
            }

            .wpr-pro-features-notice h3 {
                font-size: 36px;
                margin-top: 0;
                margin-bottom: 20px;
            }

            .wpr-pro-features-notice h3 span {
              display: inline-block;
              margin-bottom: 15px;
              font-size: 12px;
              color: #fff;
              background-color: #f51f3d;
              padding: 2px 12px 4px;
              border-radius: 3px;
            }

            .wpr-pro-features-notice p {
              margin-top: 10px;
              margin-bottom: 15px;
              font-size: 14px;
            }

            .wpr-pro-features-notice ul {
                display: flex;
            }
            
            .wpr-pro-features-notice ul a {
            display: block;
            text-decoration: none;
            }
            
            .wpr-pro-features-notice ul li a:after {
            content: ' ';
            display: inline-block;
            position: relative;
            top: -2px;
            width: 5px;
            height: 5px;
            margin-left: 12px;
            margin-right: 12px;
            background-color: #e0e0e0;
            transform: rotate(45deg);
            }
            
            .wpr-pro-features-notice ul li:last-child a:after {
            display: none;
            }
            .wpr-get-started-button.button-primary {
            background-color: #6A4BFF;
            }

            .wpr-get-started-button.button-primary:hover {
            background-color: #583ed7;
            }

            .wpr-get-started-button.button-secondary {
            border: 1px solid #6A4BFF;
            color: #6A4BFF;
            }

            .wpr-get-started-button.button-secondary:hover {
            background-color: #6A4BFF;
            border: 2px solid #6A4BFF;
            color: #fff;
            }

            .wpr-get-started-button {
                padding: 5px 25px !important;
            }

            .wpr-get-started-button .dashicons {
              font-size: 12px;
              line-height: 28px;
            }
            
            .wpr-pro-features-notice .image-wrap {
              margin-left: auto;
            }

            .wpr-pro-features-notice .image-wrap img {
              zoom: 0.45;
            }

            @media screen and (max-width: 1366px) {
                .wpr-pro-features-notice h3 {
                    font-size: 32px;
                }

                .wpr-pro-features-notice .image-wrap img {
                  zoom: 0.4;
                }
            }

            @media screen and (max-width: 1280px) {
                .wpr-pro-features-notice .image-wrap img {
                  zoom: 0.35;
                }
            }

            #wpr-notice-confetti {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              pointer-events: none;
            }
        </style>";

        
    }
}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprProFeaturesNotice();
}