<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprRatingNotice {
    private $past_date;

    public function __construct() {
        global $pagenow;

        $this->past_date = strtotime( '-14 days' );

        if ( current_user_can('administrator') ) {
            if ( empty(get_option('wpr_rating_dismiss_notice', false)) && empty(get_option('wpr_rating_already_rated', false)) ) {
                add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
            }
        }

        if ( $pagenow == 'plugins.php' || $pagenow == 'index.php' || $pagenow == 'edit.php') {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_rating_dismiss_notice', [$this, 'wpr_rating_dismiss_notice'] );
        add_action( 'wp_ajax_wpr_rating_already_rated', [$this, 'wpr_rating_already_rated'] );
        add_action( 'wp_ajax_wpr_rating_need_help', [$this, 'wpr_rating_need_help'] );
    }

    public function check_plugin_install_time() {   
        $install_date = get_option('royal_elementor_addons_activation_time');

        if ( false !== $install_date && $this->past_date >= $install_date ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice' ]);
        }    
    }

    function wpr_rating_already_rated() {    
        update_option( 'wpr_rating_already_rated' , true );
    }
    
    public function wpr_rating_dismiss_notice() {
        update_option( 'wpr_rating_dismiss_notice', true );
    }

    public function wpr_rating_need_help() {
        // Reset Activation Time if user Needs Help
        update_option( 'royal_elementor_addons_activation_time', strtotime('now') );
    }

    public function render_rating_notice() {
        global $pagenow;

        if ( $pagenow == 'plugins.php' || $pagenow == 'index.php' ) {
            $plugin_info = get_plugin_data( __FILE__ , true, true );
            $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );

            echo '<div class="notice wpr-rating-notice is-dismissible" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div class="wpr-rating-notice-logo">
                            <img src="' . WPR_ADDONS_ASSETS_URL . '/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3>Thank you for using Royal Elementor Addons to build this website!</h3>
                            <p style="">Could you please do us a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.</p>
                            <p>
                                <a href="https://wordpress.org/support/plugin/royal-elementor-addons/reviews/?filter=5#new-post" target="_blank" class="wpr-you-deserve-it button button-primary">OK, you deserve it!</a>
                                <a class="wpr-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                                <a href="https://royal-elementor-addons.com/contact/?ref=rea-plugin-backend-rating-support" target="_blank" class="wpr-need-support"><span class="dashicons dashicons-sos"></span> I need support!</a>
                                <a class="wpr-notice-dismiss-2"><span class="dashicons dashicons-thumbs-down"></span> NO, not good enough</a>
                            </p>
                        </div>
                </div>';
        }
    }

    public static function enqueue_scripts() {
        echo "
        <script>
        jQuery( document ).ready( function() {

            jQuery(document).on( 'click', '.wpr-notice-dismiss-2', function() {
                jQuery(document).find('.wpr-rating-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_rating_dismiss_notice',
                    }
                })
            });
        
            jQuery(document).on( 'click', '.wpr-already-rated', function() {
                jQuery(document).find('.wpr-rating-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_rating_already_rated',
                    }
                })
            });
        
            jQuery(document).on( 'click', '.wpr-need-support', function() {
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_rating_need_help',
                    }
                })
            });
        });
        </script>

        <style>
            .wpr-rating-notice {
              padding: 0 15px;
            }

            .wpr-rating-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }

            .wpr-rating-notice-logo img {
                max-width: 100%;
            }

            .wpr-rating-notice h3 {
              margin-bottom: 0;
            }

            .wpr-rating-notice p {
              margin-top: 3px;
              margin-bottom: 15px;
            }

            .wpr-already-rated,
            .wpr-need-support,
            .wpr-notice-dismiss-2 {
              text-decoration: none;
              margin-left: 12px;
              font-size: 14px;
              cursor: pointer;
            }

            .wpr-already-rated .dashicons,
            .wpr-need-support .dashicons {
              vertical-align: sub;
            }

            .wpr-notice-dismiss-2 .dashicons {
              vertical-align: middle;
            }

            .wpr-rating-notice .notice-dismiss {
                display: none;
            }

        </style>
        ";
    }

}

if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprRatingNotice();
}