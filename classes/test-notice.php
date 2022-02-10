<?php 
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WprTestNotice {
    public function __construct() {
        if ( current_user_can('administrator') ) {
            if ( empty(get_option('wpr_test_dismiss_notice', false)) ) {
                add_action( 'admin_init', [$this, 'check_test_notice_condition'] );
            }
        }
    }

    public function check_test_notice_condition() {
        add_action( 'admin_notices', [$this, 'render_test_notice' ]);
    }

    public function render_test_notice() {
        global $pagenow;

        if ( is_admin() ) {
            // $plugin_info = get_plugin_data( __FILE__ , true, true );
            // $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );

            echo '<div class="notice wpr-rating-notice is-dismissible" style="border-left-color: #7A75FF!important; display: flex; align-items: center;">
                        <div class="wpr-rating-notice-logo">
                            <img src="' . WPR_ADDONS_ASSETS_URL . '/img/logo-128x128.png">
                        </div>
                        <div>
                            <h3>Introducing Elementor Premade Blocks!!</h3>
                            <p>Designer-made premade blocks which can be imported in one click.</p
                            <p>
                                <a href="https://wordpress.org/support/plugin/royal-elementor-addons/reviews/?filter=5#new-post" target="_blank" class="wpr-you-deserve-it button button-primary">OK, you deserve it!</a>
                                <a class="wpr-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                <a class="wpr-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                            </p>
                        </div>
                </div>';
        }
    }
}

// if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new WprTestNotice();
// }