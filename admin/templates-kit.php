<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Templates\WPR_Templates_Data;
use WprAddons\Classes\Utilities;
use Elementor\Plugin;

// Register Menus
function wpr_addons_add_templates_kit_menu() {
	add_submenu_page( 'wpr-addons', 'Templates Kit', 'Templates Kit', 'manage_options', 'wpr-templates-kit', 'wpr_addons_templates_kit_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_templates_kit_menu' );

// Import Template Kit
add_action( 'wp_ajax_wpr_install_reuired_plugins', 'wpr_install_reuired_plugins' );
add_action( 'wp_ajax_wpr_import_templates_kit', 'wpr_import_templates_kit' );
add_action( 'wp_ajax_wpr_fix_elementor_images', 'wpr_fix_elementor_images' );


/**
** Render Templates Kit Page
*/
function wpr_addons_templates_kit_page() {

?>

<div class="wpr-templates-kit-page">

    <header>
        <div class="wpr-templates-kit-logo">
            <div><img src="<?php echo !empty(get_option('wpr_wl_plugin_logo')) ? wp_get_attachment_image_src(get_option('wpr_wl_plugin_logo'), 'full')[0] : WPR_ADDONS_ASSETS_URL .'img/logo-40x40.png'; ?>"></div>
            <div class="back-btn"><?php _e('<span class="dashicons dashicons-arrow-left-alt2"></span> Back to Library', 'wpr-addons'); ?></div>
        </div>

        <div class="wpr-templates-kit-filters">
            <div>Filter: All</div>
            <ul>
                <li data-filter="all">Blog</li>
                <li data-filter="blog">Blog</li>
                <li data-filter="business">Business</li>
                <li data-filter="ecommerce">eCommerce</li>
                <li data-filter="beauty">Beauty</li>
            </ul>
        </div>
    </header>

    <div class="wpr-templates-kit-grid main-grid">
        <?php
            $kits = WPR_Templates_Data::get_available_kits();

            foreach ($kits as $slug => $kit) {
                foreach ($kit as $version => $data ) {
                   $kit_id = $slug .'-'. $version;
                   $kit_title = ucfirst($slug) .' '. ucfirst($version);

                    echo '<div class="grid-item" data-kit-id="'. $kit_id .'" data-plugins="'. esc_attr($data['plugins']) .'" data-pages="'. $data['pages'] .'">';
                        echo '<div class="image-wrap">';
                            echo '<img src="'. WPR_ADDONS_ASSETS_URL .'img/tmp/'. $kit_id .'.png">';
                            echo '<div class="image-overlay"><span class="dashicons dashicons-search"></span></div>';
                        echo '</div>';
                        echo '<footer>';
                            echo '<h3>'. $kit_title .'</h3>';
                        echo '</footer>';
                    echo '</div>';
                }
            }
        ?>

    </div>

    <div class="wpr-templates-kit-single">
        <div class="wpr-templates-kit-grid single-grid"></div>

        <footer class="action-buttons-wrap">
            <a href="https://royal-elementor-addons.com/" class="preview-demo button" target="_blank"><?php _e('Preview Demo <span class="dashicons dashicons-external"></span>', 'wpr-addons'); ?></a>

            <div class="import-template-buttons">
                <button class="import-kit button"><?php _e('Import Kit', 'wpr-addons'); ?></button>
                <button class="import-template button"><?php _e('Import <strong></strong> Template', 'wpr-addons'); ?></button>
            </div>
        </footer>
    </div>

    <div class="wpr-import-kit-popup-wrap">
        <div class="overlay"></div>
        <div class="wpr-import-kit-popup">
            <header>
                <h3><?php _e('Template Kit is being imported...', 'wpr-addons'); ?></h3>
                <span class="dashicons dashicons-no-alt close-btn"></span>
            </header>
            <div class="content">
                <p><?php _e('The import process can take a few minutes depending on the size of the kit you are importing and speed of the connection.', 'wpr-addons'); ?></p>
                <p><?php _e('Please do NOT close this browser window until import is completed.', 'wpr-addons'); ?></p>

                <div class="progress-wrap">
                    <div class="progress-bar"></div>
                    <strong></strong>
                </div>
            </div>
        </div>
    </div>

</div>


<?php

} // End wpr_addons_templates_kit_page()


/**
** Install/Activate Required Plugins
*/
function wpr_install_reuired_plugins() {
    // Getcurrently active plugins
    $active_plugins = (array) get_option( 'active_plugins', array() );

    // Add Required Plugins
    if ( 'contact-form-7' == $_POST['plugin'] ) {
        array_push( $active_plugins, 'contact-form-7/wp-contact-form-7.php' );
    } elseif ( 'ashe-extra' == $_POST['plugin'] ) {
        array_push( $active_plugins, 'ashe-extra/ashe-extra.php' );
    }

    // Set Active Plugins
    update_option( 'active_plugins', $active_plugins );
}

/**
** Import Template Kit
*/
function wpr_import_templates_kit() {

    // Temp Define Importers
    if ( ! defined('WP_LOAD_IMPORTERS') ) {
        define('WP_LOAD_IMPORTERS', true);
    }

    // Include if Class Does NOT Exist
    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = WPR_ADDONS_PATH .'admin/import/class-wordpress-importer.php';
        if ( file_exists( $class_wp_importer ) ) {
            require $class_wp_importer;
        }
    }

    if ( class_exists( 'WP_Import' ) ) {
        $kit = sanitize_file_name($_POST['wpr_templates_kit']);
        $file = rest_sanitize_boolean($_POST['wpr_templates_kit_single']);

        // Tmp
        update_option( 'wpr-import-kit-id', $kit );

        // Download Import File
        $local_file_path = download_template( $kit, $file );

        // Prepare for Import
        $wp_import = new WP_Import( $local_file_path, ['fetch_attachments' => true] );

        // Import
        ob_start();
            $wp_import->run();
        ob_end_clean();

        // Delete Import File
        unlink( $local_file_path );

        // Send to JS
        echo serialize( $wp_import );
    }

}

/**
** Download Template
*/
function download_template( $kit, $file ) {
    $file = ! $file ? 'main' : $file;

    // Remote and Local Files
    // $remote_file_url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/wxr.xml';//astra
    $remote_file_url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/'. $file .'.xml';
    $local_file_path = WPR_ADDONS_PATH .'admin/import/tmp.xml';

    // No Limit for Execution
    set_time_limit(0);

    // Copy File From Server
    copy( $remote_file_url, $local_file_path );

    return $local_file_path;
}

/**
** Fix Elementor Images
*/
function wpr_fix_elementor_images() {
    $pages = get_pages([ 'meta_key' => '_elementor_version' ]);

    foreach ($pages as $key => $page) {
        $data = get_post_meta( $page->ID, '_elementor_data', true );

        if ( ! empty( $data ) ) {
            $site_url      = get_site_url();
            $site_url      = str_replace( '/', '\/', $site_url );
            $demo_site_url = 'https:' . '//illarithmetic.tastewp.com';
            $demo_site_url = 'https://staging-demosites.kinsta.cloud/' . get_option('wpr-import-kit-id');
            $demo_site_url = str_replace( '/', '\/', $demo_site_url );
            $data          = preg_replace('/\\\{1}\/sites\\\{1}\/\d/', '', $data);
            $data          = str_replace( $demo_site_url, $site_url, $data );
            $data          = json_decode( $data, true );
        }

        update_metadata( 'post', $page->ID, '_elementor_data', $data );
    }

    // Clear Elementor Cache
    Plugin::$instance->files_manager->clear_cache();

    // Clear DB
    delete_option('wpr-import-kit-id');
}