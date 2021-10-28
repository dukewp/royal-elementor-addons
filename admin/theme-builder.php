<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Includes\WPR_Templates_Loop;

// Register Menus
function wpr_addons_add_theme_builder_menu() {
	add_submenu_page( 'wpr-addons', 'Header & Footer', 'Header & Footer', 'manage_options', 'wpr-theme-builder', 'wpr_addons_theme_builder_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_theme_builder_menu' );

function wpr_addons_theme_builder_page() {

?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php esc_html_e( 'Royal Elementor Addons', 'wpr-addons' ); ?></h1>
    <p>
        <?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?>
        <br>
        <?php esc_html_e( 'Please Note: Header & Footer builder works best with ', 'wpr-addons' ); ?>
        <a href="https://wordpress.org/themes/ashe/" target="_blank"><?php esc_html_e( 'Ashe', 'wpr-addons' ); ?></a>
        <?php esc_html_e( ' and ', 'wpr-addons' ); ?>
        <a href="https://wordpress.org/themes/bard/" target="_blank"><?php esc_html_e( 'Bard', 'wpr-addons' ); ?></a>
        <?php esc_html_e( ' Themes', 'wpr-addons' ); ?>
    </p>

    <!-- Custom Template -->
    <div class="wpr-user-template">
        <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
        <span class="plus-icon">+</span>
    </div>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_header';

    ?>

    <!-- Template ID Holder -->
    <input type="hidden" name="wpr_template" id="wpr_template" value="">

    <!-- Conditions Popup -->
    <?php WPR_Templates_Loop::render_conditions_popup(); ?>

    <!-- Create Templte Popup -->
    <?php WPR_Templates_Loop::render_create_template_popup(); ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <a href="?page=wpr-theme-builder&tab=wpr_tab_header" data-title="Header" class="nav-tab <?php echo $active_tab == 'wpr_tab_header' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Header', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-theme-builder&tab=wpr_tab_footer" data-title="Footer" class="nav-tab <?php echo $active_tab == 'wpr_tab_footer' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Footer', 'wpr-addons' ); ?>
        </a>
    </div>

    <?php if ( $active_tab == 'wpr_tab_header' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_header_conditions" id="wpr_header_conditions" value="<?php echo esc_attr(get_option('wpr_header_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'header' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_footer' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_footer_conditions" id="wpr_footer_conditions" value="<?php echo esc_attr(get_option('wpr_footer_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'footer' ); ?>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_theme_builder_page()