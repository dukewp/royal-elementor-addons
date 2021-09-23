<?php
/*
 * Plugin Name: Royal Elementor Addons
 * Description: The only plugin you need for Elementor page builder.
 * Plugin URI: https://royal-elementor-addons.com/
 * Author: WP Royal
 * Version: 1.1.1
 * License: GPLv3
 * Author URI: https://wp-royal.com/
 * Elementor tested up to: 3.4.4
 * Elementor Pro tested up to: 3.4.1
 * 
 * Text Domain: wpr-addons
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WPR_ADDONS_VERSION', '1.1.0' );

define( 'WPR_ADDONS__FILE__', __FILE__ );
define( 'WPR_ADDONS_PLUGIN_BASE', plugin_basename( WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_PATH', plugin_dir_path( WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_MODULES_PATH', WPR_ADDONS_PATH . 'modules/' );
define( 'WPR_ADDONS_URL', plugins_url( '/', WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_ASSETS_URL', WPR_ADDONS_URL . 'assets/' );
define( 'WPR_ADDONS_MODULES_URL', WPR_ADDONS_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpr_addons_load_plugin() {
	load_plugin_textdomain( 'wpr-addons' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'wpr_addons_fail_load' );
		return;
	}

	// $elementor_version_required = '1.0.6';
	$elementor_version_required = '1.0.6';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'wpr_addons_fail_load_out_of_date' );
		return;
	}

	require( WPR_ADDONS_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'wpr_addons_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpr_addons_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you need to activate the Elementor plugin.', 'wpr-addons' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'wpr-addons' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you need to install the Elemenor plugin', 'wpr-addons' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'wpr-addons' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function wpr_addons_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you are using an old version of Elementor.', 'wpr-addons' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'wpr-addons' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


/**
 * Redirect to Options Page
 *
 * @since 1.0.0
 *
 */

function wpr_plugin_activate() {
	set_transient('wpr_plugin_do_activation_redirect', true, 60);
}

function wpr_plugin_redirect() {
	if (get_transient('wpr_plugin_do_activation_redirect')) {
		delete_transient('wpr_plugin_do_activation_redirect');

		if ( !isset($_GET['activate-multi']) ) {
			wp_redirect('admin.php?page=wpr-addons');
		}
	}
}

if ( did_action( 'elementor/loaded' ) ) {
	
	register_activation_hook(__FILE__, 'wpr_plugin_activate');
	add_action('admin_init', 'wpr_plugin_redirect');
}

// Try to locate it in rating-notice later if possible
function royal_elementor_addons_activation_time(){
	$get_activation_time = strtotime("now");
	add_option('royal_elementor_addons_activation_time', $get_activation_time );
}
// register_activation_hook( __FILE__, 'royal_elementor_addons_activation_time' );