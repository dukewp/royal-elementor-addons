<?php 
namespace WprAddons\Classes;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Check plugin.php includes function
class RatingNotice {
    public function __construct() {
        if(current_user_can('administrator')) {
            if(empty(get_option('my_dismiss_notice')) and empty(get_option('already_rated')) and empty(get_option('maybe_later'))) {
                add_action( 'admin_init', [$this, 'wpr_check_installation_time'] );
            }
        }

        global $pagenow;
        if ( $pagenow == 'plugins.php' || $pagenow == 'index.php' || $pagenow == 'edit.php') {
            // add_action( 'admin_enqueue_scripts', [$this, 'add_script'] );
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        if(strtotime('now') - get_option('dismiss_notice_no_sorry') > 7) {
            delete_option('dismiss_notice_no_sorry');
            delete_option('my_dismiss_notice');
        }
        if (strtotime('now') - get_option('dismiss_notice_already_rated') > 20) {
            delete_option('dismiss_notice_already_rated');
            delete_option('already_rated');
        }
        if (strtotime('now') - get_option('dismiss_notice_maybe_later') > 10) {
            delete_option('dismiss_notice_maybe_later');
            delete_option('maybe_later');
        }   

        add_action( 'wp_ajax_my_dismiss_notice', [$this, 'my_dismiss_notice'] );
        add_action( 'wp_ajax_maybe_later', [$this, 'maybe_later'] );
        add_action( 'wp_ajax_already_rated', [$this, 'already_rated'] );
	}

    public function wpr_check_installation_time() {   
        $install_date = get_option( 'royal_elementor_addons_activation_time' );
        $past_date = strtotime( '-7 seconds' );
        if ( $past_date >= $install_date ) {
            add_action( 'admin_notices', [$this, 'rating_admin_notice' ]);
        }    
    }

    function already_rated(){    
        update_option( 'already_rated' , TRUE );
        update_option('dismiss_notice_already_rated', strtotime('now'));
    }
    
    public function my_dismiss_notice() {
        update_option( 'my_dismiss_notice', true );
        update_option('dismiss_notice_no_sorry', strtotime('now'));
    }

    public function maybe_later() {
        update_option( 'maybe_later', true );
        update_option('dismiss_notice_maybe_later', strtotime('now'));
    }

    public function add_script() {
            wp_register_script( 'notice-update', WPR_ADDONS_ASSETS_URL . '/js/admin/update-notice.js', ['jquery'],'1.0', false );
            
            wp_localize_script( 'notice-update', 'notice_params', array(
                'ajaxurl' => get_admin_url() . 'admin-ajax.php', 
            ));
            
            wp_enqueue_script(  'notice-update' );
    }

    public function rating_admin_notice(){
        global $pagenow;
        if ( $pagenow == 'plugins.php' OR $pagenow == 'index.php') {
            $plugin_info = get_plugin_data( __FILE__ , true, true );
            $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );
             echo '<div class="notice notice-for-rating is-dismissible my-dismiss-notice" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div style="margin-right: 15px;">
                            <img style="width: 97px; height: 97px;" src="' . WPR_ADDONS_ASSETS_URL . '/img/icon-128x128.png">
                        </div>
                        <div>
                            <h1>Enjoying <b>Royal Addons</b>?</h1>
                            <p style="font-weight: 300; font-size: 1.1rem">Why don\'t you give us five star rating to contribute to our increasing popularity and boost our motivation?</p>
                            <p style="margin: 14px 0;">
                                <a href="https://wordpress.org/support/plugin/royal-elementor-addons/reviews/#new-post" target = "_blank" class="wpr-you-deserve-it">🌾 Ok, you deserve it</a>
                                <a href="https://royal-elementor-addons.com/contact/" target = "_blank" class="wpr-need-support">💁🏽‍♂️ I need support!</a>
                                <button class="wpr-already-rated">🥙 Already rated</button>
                                <button class="wpr-maybe-later">🕔 Maybe Later?</button>
                                <button class="wpr-notice-dismiss-2">💔 No, Sorry</button>
                            </p>
                        </div>
                    </div>';
        }
    }

    public static function enqueue_scripts() {
        echo "
        <script>
        jQuery( document ).ready( function() {

            jQuery(document).on( 'click', '.my-dismiss-notice .wpr-notice-dismiss-2', function() {
                jQuery(document).find('.my-dismiss-notice').fadeOut();
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'my_dismiss_notice',
                    }
                })
            
            })
        
            jQuery(document).on( 'click', '.my-dismiss-notice .wpr-maybe-later', function() {
                jQuery(document).find('.my-dismiss-notice').fadeOut();
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'maybe_later',
                    }
                })
            
            })
        
            jQuery(document).on( 'click', '.my-dismiss-notice .wpr-already-rated', function() {
                jQuery(document).find('.my-dismiss-notice').slideUp();
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'already_rated',
                    }
                })
            
            })
        });
        </script>
        <style>
        .wpr-you-deserve-it {
            font-size: 1rem;
            border: 1px solid #7A75FF;
            background: #7A75FF!important;
            color: white; padding: 7px 13px;
            border-radius: 5px; 
            text-decoration: none;
        }

        .wpr-need-support {
            font-size: 1rem;
            border: 1px solid #7A75FF;
            background: #7A75FF!important;
            color: white;
            padding: 7px 13px;
            border-radius: 5px;
            text-decoration: none;
        }
        .wpr-already-rated {
            cursor: pointer;
            font-size: 1rem;
            border: 1px solid #7A75FF!important;
            color: #7A75FF;
            padding: 7px 13px;
            border-radius: 5px;
            text-decoration: none; outline: none;
        }

        .wpr-maybe-later {
            cursor: pointer;
            font-size: 1rem;
            border: 1px solid #7A75FF!important;
            color: #7A75FF;
            padding: 7px 13px;
            border-radius: 5px;
            text-decoration: none; outline: none;
        }

        .wpr-notice-dismiss-2 {
            cursor: pointer;
            font-size: 1rem;
            border: 1px solid #ffbc75!important;
            color: #ffbc75;
            padding: 7px 13px;
            border-radius: 5px;
            text-decoration: none;
            outline: none;
        }
        .my-dismiss-notice .notice-dismiss {
            display: none;
        }

        </style>
        ";
    }

}

new RatingNotice();
?>