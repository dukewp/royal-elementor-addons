<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprRatingNotice {
    private $past_date;

    public function __construct() {
        global $pagenow;
        $this->past_date = false == get_option('wpr_maybe_later_time') ? strtotime( '-14 days' ) : strtotime('-7 days');

        if ( current_user_can('administrator') ) {
            if ( empty(get_option('wpr_rating_dismiss_notice', false)) && empty(get_option('wpr_rating_already_rated', false)) ) {
                add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_wpr_rating_dismiss_notice', [$this, 'wpr_rating_dismiss_notice'] );
        add_action( 'wp_ajax_wpr_rating_maybe_later', [$this, 'wpr_rating_maybe_later'] );
        add_action( 'wp_ajax_wpr_rating_already_rated', [$this, 'wpr_rating_already_rated'] );
        add_action( 'wp_ajax_wpr_rating_need_help', [$this, 'wpr_rating_need_help'] );
    }

    public function check_plugin_install_time() {
        $install_date = get_option('royal_elementor_addons_activation_time');
        
        if ( false == get_option('wpr_maybe_later_time') && false !== $install_date && $this->past_date >= $install_date ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice' ]);
        } else if ( false != get_option('wpr_maybe_later_time') && $this->past_date >= get_option('wpr_maybe_later_time') ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice' ]);
        }
    }

    public function wpr_rating_maybe_later() {
        update_option( 'wpr_maybe_later_time', strtotime('now') );
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

        if ( is_admin() ) {
            $plugin_info = get_plugin_data( __FILE__ , true, true );
            $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );

            echo '<div class="notice wpr-rating-notice is-dismissible">
                        <div style="flex-shrink: 0;">
                            <h3><span class="dashicons dashicons-megaphone"></span> Win Royal Elementor Addons Pro Lifetime License!</h3>
                            <p style="margin-top: 15px; margin-bottom: 0; font-style: italic;">For participation follow the steps below:</p>
                            <ul>
                                <li>
                                    <img src="' . WPR_ADDONS_ASSETS_URL . '/img/check-mark.png">
                                    Go to 
                                    <strong><a href="https://wordpress.org/support/plugin/royal-elementor-addons/reviews/?filter=5#new-post" target="_blank">Royal Elementor Addons</a></strong> 
                                    WordPress repository page and submit your honest review.
                                </li>

                                <li>
                                    <img src="' . WPR_ADDONS_ASSETS_URL . '/img/check-mark.png">
                                    After submitting your review, just fill-up 
                                    <strong><a href="https://forms.clickup.com/1856033/f/1rmh1-4865/67J5MQV345BK1XMNJ7" target="_blank">this simple form</a></strong> 
                                    to join our giveaway program.
                                </li>
                            </ul>
                            <p>That\'s all, We will select <strong>3 random users</strong> from the WodPress review page every month.</p>
                            <p style="margin-top: 20px;">
                                <a class="wpr-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                <a class="wpr-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                            </p>
                        </div>
                        <div class="image-wrap"><img src="'. WPR_ADDONS_ASSETS_URL .'/img/rating-notice.png"></div>

                        <canvas id="wpr-notice-confetti"></canvas>
                </div>';

            // Dev note: old rating text could be found in v1.3.8
            // <a href="https://wordpress.org/support/plugin/royal-elementor-addons/" target="_blank" class="wpr-need-support"><span class="dashicons dashicons-sos"></span> I need support!</a>
            // <a class="wpr-notice-dismiss-2"><span class="dashicons dashicons-thumbs-down"></span> NO, not good enough</a>
        }
    }

    public static function enqueue_scripts() {
        // Load Confetti
        wp_enqueue_script( 'wpr-confetti-js', WPR_ADDONS_URL .'assets/js/lib/confetti/confetti.min.js', ['jquery'] );

        echo "
        <script>
        jQuery( document ).ready( function() {

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
                    dispatch( {
                        type: 'set',
                        confettiDone: true,
                    } );
                }, 900 );
            }

            jQuery(document).on( 'click', '.wpr-notice-dismiss-2', function() {
                jQuery(document).find('.wpr-rating-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_rating_dismiss_notice',
                    }
                })
            });

            jQuery(document).on( 'click', '.wpr-maybe-later', function() {
                jQuery(document).find('.wpr-rating-notice').slideUp();
                jQuery.post({
                    url: ajaxurl,
                    data: {
                        action: 'wpr_rating_maybe_later',
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

            .wpr-rating-notice .image-wrap {
                margin-left: auto;
            }

            .wpr-rating-notice .image-wrap img {
                display: inherit;
                max-width: 90%;
                margin-left: auto;
            }

            .wpr-rating-notice ul {
                margin-left: 15px;
            }

            .wpr-rating-notice ul img {
                display: inline-block;
                width: 10px;
                margin-right: 5px;
            }

            .wpr-rating-notice-logo {
                margin-top: 15px;
                margin-right: 30px;
            }

            .wpr-rating-notice-logo img {
                max-width: 100%;
            }

            .wpr-rating-notice h3 {
                font-size: 24px;
                margin: 20px 0;
            }

            .wpr-rating-notice p {
              margin-top: 3px;
              margin-bottom: 15px;
            }

            .wpr-maybe-later,
            .wpr-already-rated {
                display: inline-flex;
                align-items: center;
            }

            .wpr-maybe-later,
            .wpr-already-rated,
            .wpr-need-support,
            .wpr-notice-dismiss-2 {
              text-decoration: none;
              margin-left: 12px;
              font-size: 14px;
              cursor: pointer;
            }

            .wpr-maybe-later {
                margin-left: 0;
            }

            .wpr-already-rated .dashicons,
            .wpr-maybe-later .dashicons,
            .wpr-need-support .dashicons {
              margin-right: 5px;
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