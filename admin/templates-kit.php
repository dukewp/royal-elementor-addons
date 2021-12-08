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
add_action( 'wp_ajax_wpr_final_settings_setup', 'wpr_final_settings_setup' );


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
                            echo '<img src="https://royal-elementor-addons.com/library/templates-kit/'. $kit_id .'/home.jpg">';
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
            <a href="https://royal-elementor-addons.com/" class="preview-demo button" target="_blank"><?php _e('Preview Demo', 'wpr-addons'); ?> <span class="dashicons dashicons-external"></span></a>

            <div class="import-template-buttons">
                <button class="import-kit button"><?php _e('Import Templates Kit', 'wpr-addons'); ?> <span class="dashicons dashicons-download"></span></button>
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
        $file = sanitize_file_name($_POST['wpr_templates_kit_single']);

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
    $remote_file_url = 'https://royal-elementor-addons.com/library/templates-kit/'. $kit .'/main.xml';
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
    $args = array(
        'post_type' => ['wpr_templates', 'page'],
        'posts_per_page' => '-1',
        'meta_key' => '_elementor_version'
    );
    $elementor_pages = new WP_Query ( $args );

    // Check that we have query results.
    if ( $elementor_pages->have_posts() ) {
     
        // Start looping over the query results.
        while ( $elementor_pages->have_posts() ) {

            $elementor_pages->the_post();

            // Replace Demo with Current
            $site_url      = get_site_url();
            $site_url      = str_replace( '/', '\/', $site_url );
            $demo_site_url = 'https://demosites.royal-elementor-addons.com/' . get_option('wpr-import-kit-id');
            $demo_site_url = str_replace( '/', '\/', $demo_site_url );

            // Elementor Data
            $data = get_post_meta( get_the_ID(), '_elementor_data', true );

            if ( ! empty( $data ) ) {
                $data          = preg_replace('/\\\{1}\/sites\\\{1}\/\d/', '', $data);
                $data          = str_replace( $demo_site_url, $site_url, $data );
                $data          = json_decode( $data, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_data', $data );

            // Elementor Page Settings
            $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
            $page_settings = json_encode($page_settings);

            if ( ! empty( $page_settings ) ) {
                $page_settings          = preg_replace('/\\\{1}\/sites\\\{1}\/\d/', '', $page_settings);
                $page_settings          = str_replace( $demo_site_url, $site_url, $page_settings );
                $page_settings          = json_decode( $page_settings, true );
            }

            update_metadata( 'post', get_the_ID(), '_elementor_page_settings', $page_settings );

        }
     
    }

    // Clear Elementor Cache
    Plugin::$instance->files_manager->clear_cache();
}

/**
** Final Settings Setup
*/
function wpr_final_settings_setup() {
    $kit = get_option('wpr-import-kit-id');

    // Fix Elementor Images
    wpr_fix_elementor_images();

    // Set Home Page
    $page = get_page_by_path('home-'. $kit);
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $page->ID );

    // Set Headers and Footers
    update_option('wpr_header_conditions', '{"user-header-'. $kit .'":["global"]}');
    update_post_meta( Utilities::get_template_id('user-header-'. $kit), 'wpr_header_show_on_canvas', 'true' );
    update_option('wpr_footer_conditions', '{"user-footer-'. $kit .'":["global"]}');
    update_post_meta( Utilities::get_template_id('user-footer-'. $kit), 'wpr_footer_show_on_canvas', 'true' );

    // Clear DB
    delete_option('wpr-import-kit-id');

    // Delete Hello World Post
    $post = get_page_by_path('hello-world', OBJECT, 'post');
    if ( $post ) {
        wp_delete_post($post->ID,true);
    }
}

/**
 *  Allow SVG Import - Add Mime Types
 */
function wpr_svgs_upload_mimes( $mimes = array() ) {

    // allow SVG file upload
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;

}
add_filter( 'upload_mimes', 'wpr_svgs_upload_mimes', 99 );

/**
 * Check Mime Types
 */
function wpr_svgs_upload_check( $checked, $file, $filename, $mimes ) {

    if ( ! $checked['type'] ) {

        $check_filetype     = wp_check_filetype( $filename, $mimes );
        $ext                = $check_filetype['ext'];
        $type               = $check_filetype['type'];
        $proper_filename    = $filename;

        if ( $type && 0 === strpos( $type, 'image/' ) && $ext !== 'svg' ) {
            $ext = $type = false;
        }

        $checked = compact( 'ext','type','proper_filename' );
    }

    return $checked;

}
add_filter( 'wp_check_filetype_and_ext', 'wpr_svgs_upload_check', 10, 4 );

/**
 * Mime Check fix for WP 4.7.1 / 4.7.2
 *
 * Fixes uploads for these 2 version of WordPress.
 * Issue was fixed in 4.7.3 core.
 */
function wpr_svgs_allow_svg_upload( $data, $file, $filename, $mimes ) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' || $wp_version !== '4.7.2' ) {
        return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return [
        'ext'               => $filetype['ext'],
        'type'              => $filetype['type'],
        'proper_filename'   => $data['proper_filename']
    ];

}
add_filter( 'wp_check_filetype_and_ext', 'wpr_svgs_allow_svg_upload', 10, 4 );