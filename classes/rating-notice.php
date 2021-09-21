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
        if ( $pagenow == 'plugins.php' OR $pagenow == 'index.php') {
            add_action( 'admin_enqueue_scripts', [$this, 'add_script'] );
        }

        if(strtotime('now') - get_option('time_before_reappear_notice') > 7) {
            delete_option('time_before_reappear_notice');
            delete_option('already_rated');
            delete_option('my_dismiss_notice');
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
        update_option('time_before_reappear_notice', strtotime('now'));
    }
    
    public function my_dismiss_notice() {
        update_option( 'my_dismiss_notice', true );
        update_option('time_before_reappear_notice', strtotime('now'));
    }

    public function maybe_later() {
        update_option( 'maybe_later', true );
        update_option('time_before_reappear_notice', strtotime('now'));
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
                            <p style="font-weight: 300; font-size: 1.1rem">Why don\'t you give us five star rating to contribute in our increasing popularity and boost our motivation?</p>
                            <p style="margin: 14px 0;">
                                <a href="https://wordpress.org/support/plugin/elementskit-lite/reviews/#new-post" target = "_blank" style="font-size: 1rem; border: 1px solid #7A75FF; background: #7A75FF!important; color: white; padding: 7px 13px; border-radius: 5px; text-decoration: none;">🌾 Ok, you deserve it</a>
                                <a href="https://royal-elementor-addons.com/contact/" target = "_blank" style="font-size: 1rem; border: 1px solid #7A75FF; background: #7A75FF!important; color: white; padding: 7px 13px; border-radius: 5px; text-decoration: none;">💁🏽‍♂️ I need support!</a>
                                <button class="already-rated" style="cursor: pointer; font-size: 1rem; border: 1px solid #7A75FF!important; color: #7A75FF; padding: 7px 13px; border-radius: 5px; text-decoration: none; outline: none;">🥙 Already rated</button>
                                <button class="maybe-later" style="cursor: pointer; font-size: 1rem; border: 1px solid #7A75FF!important; color: #7A75FF; padding: 7px 13px; border-radius: 5px; text-decoration: none; outline: none;">🕔 Maybe Later?</button>
                                <button class="notice-dismiss-2" style="cursor: pointer; font-size: 1rem; border: 1px solid #ffbc75!important;color: #ffbc75; padding: 7px 13px; border-radius: 5px; text-decoration: none; outline: none;">💔 No, Sorry</button>
                            </p>
                        </div>
                    </div>';
        }
    }
}

new RatingNotice();
?>