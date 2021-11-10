<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Classes\Utilities;

// Register Menus
function wpr_addons_add_templates_kit_menu() {
	add_submenu_page( 'wpr-addons', 'Templates Kit', 'Templates Kit', 'manage_options', 'wpr-templates-kit', 'wpr_addons_templates_kit_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_templates_kit_menu' );

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
        <div class="grid-item" data-pages="home,about,services,contact," data-kit-id="food-restaurant">
            <div class="image-wrap">
                <img src="<?php echo WPR_ADDONS_ASSETS_URL .'img/tmp/1.jpg'; ?>" alt="">
                <div class="image-overlay"><span class="dashicons dashicons-search"></span></div>
            </div>
            <footer>
                <h3>Food Restaurant</h3>
            </footer>
        </div>
        <div class="grid-item">
            <div class="image-wrap">
                <img src="<?php echo WPR_ADDONS_ASSETS_URL .'img/tmp/2.jpg'; ?>" alt="">
                <div class="image-overlay"><span class="dashicons dashicons-search"></span></div>
            </div>
            <footer>
                <h3>Main Demo</h3>
            </footer>
        </div>
        <div class="grid-item">
            <div class="image-wrap">
                <img src="<?php echo WPR_ADDONS_ASSETS_URL .'img/tmp/3.jpg'; ?>" alt="">
                <div class="image-overlay"><span class="dashicons dashicons-search"></span></div>
            </div>
            <footer>
                <h3>Black Site</h3>
            </footer>
        </div>
        <div class="grid-item">
            <div class="image-wrap">
                <img src="<?php echo WPR_ADDONS_ASSETS_URL .'img/tmp/4.jpg'; ?>" alt="">
                <div class="image-overlay"><span class="dashicons dashicons-search"></span></div>
            </div>
            <footer>
                <h3>Surfing Style</h3>
            </footer>
        </div>
        <div class="grid-item">
            <div class="image-wrap">
                <img src="<?php echo WPR_ADDONS_ASSETS_URL .'img/tmp/1.jpg'; ?>" alt="">
                <div class="image-overlay"><span class="dashicons dashicons-search"></span></div>
            </div>
            <footer>
                <h3>Food Restaurant</h3>
            </footer>
        </div>
    </div>

    <div class="wpr-templates-kit-single">
        <div class="wpr-templates-kit-grid single-grid"></div>

        <footer class="action-buttons-wrap">
            <a href="https://royal-elementor-addons.com/" class="button"><?php _e('Preview Demo <span class="dashicons dashicons-external"></span>', 'wpr-addons'); ?></a>

            <div class="import-template-buttons">
                <button class="import-kit button"><?php _e('Import Kit', 'wpr-addons'); ?></button>
                <button class="import-template button"><?php _e('Import "<span></span>" Template', 'wpr-addons'); ?></button>
            </div>
        </footer>
    </div>

</div>


<?php

} // End wpr_addons_templates_kit_page()