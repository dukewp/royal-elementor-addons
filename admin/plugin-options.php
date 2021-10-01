<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Classes\Utilities;
use WprAddons\Admin\Includes\WPR_Templates_Loop;

// Register Menus
function wpr_addons_add_admin_menu() {
	add_menu_page( 'Royal Addons', 'Royal Addons', 'manage_options', 'wpr-addons', 'wpr_addons_settings_page', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTciIGhlaWdodD0iNzUiIHZpZXdCb3g9IjAgMCA5NyA3NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAuMDM2NDA4NiAyMy4yODlDLTAuNTc1NDkgMTguNTIxIDYuNjg4NzMgMTYuMzY2NiA5LjU0OSAyMC40Njc4TDQyLjgzNjUgNjguMTk3MkM0NC45MTgxIDcxLjE4MiA0Mi40NDk0IDc1IDM4LjQzNzggNzVIMTEuMjc1NkM4LjY1NDc1IDc1IDYuNDUyNjQgNzMuMjg1NSA2LjE2MTcgNzEuMDE4NEwwLjAzNjQwODYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTk2Ljk2MzYgMjMuMjg5Qzk3LjU3NTUgMTguNTIxIDkwLjMxMTMgMTYuMzY2NiA4Ny40NTEgMjAuNDY3OEw1NC4xNjM1IDY4LjE5NzJDNTIuMDgxOCA3MS4xODIgNTQuNTUwNiA3NSA1OC41NjIyIDc1SDg1LjcyNDRDODguMzQ1MiA3NSA5MC41NDc0IDczLjI4NTUgOTAuODM4MyA3MS4wMTg0TDk2Ljk2MzYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTUzLjI0MTIgNC40ODUyN0M1My4yNDEyIC0wLjI3MDc2MSA0NS44NDg1IC0xLjc0ODAzIDQzLjQ2NTEgMi41MzE3NEw2LjY4OTkxIDY4LjU2NzdDNS4wMzM0OSA3MS41NDIxIDcuNTIyNzIgNzUgMTEuMzIwMyA3NUg0OC4wOTU1QzUwLjkzNzQgNzUgNTMuMjQxMiA3Mi45OTQ4IDUzLjI0MTIgNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTQzLjc1ODggNC40ODUyN0M0My43NTg4IC0wLjI3MDc2MSA1MS4xNTE1IC0xLjc0ODAzIDUzLjUzNDkgMi41MzE3NEw5MC4zMTAxIDY4LjU2NzdDOTEuOTY2NSA3MS41NDIxIDg5LjQ3NzMgNzUgODUuNjc5NyA3NUg0OC45MDQ1QzQ2LjA2MjYgNzUgNDMuNzU4OCA3Mi45OTQ4IDQzLjc1ODggNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==', '58.6' );
	add_action( 'admin_init', 'wpr_register_addons_settings' );
    add_filter( 'plugin_action_links_royal-elementor-addons/wpr-addons.php', 'wpr_settings_link' );
}
add_action( 'admin_menu', 'wpr_addons_add_admin_menu' );

// Add Options page link to plugins screen
function wpr_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wpr-addons">Settings</a>';
    array_push( $links, $settings_link );

    return $links;
}

// Register Settings
function wpr_register_addons_settings() {
    // Integrations
    register_setting( 'wpr-settings', 'wpr_google_map_api_key' );
    register_setting( 'wpr-settings', 'wpr_mailchimp_api_key' );

    // Lightbox
    register_setting( 'wpr-settings', 'wpr_lb_bg_color' );
    register_setting( 'wpr-settings', 'wpr_lb_toolbar_color' );
    register_setting( 'wpr-settings', 'wpr_lb_caption_color' );
    register_setting( 'wpr-settings', 'wpr_lb_gallery_color' );
    register_setting( 'wpr-settings', 'wpr_lb_pb_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_hr_color' );
    register_setting( 'wpr-settings', 'wpr_lb_text_color' );
    register_setting( 'wpr-settings', 'wpr_lb_icon_size' );
    register_setting( 'wpr-settings', 'wpr_lb_arrow_size' );
    register_setting( 'wpr-settings', 'wpr_lb_text_size' );

    // Element Toggle
    foreach ( Utilities::get_registered_modules() as $title => $data ) {
        $slug = $data[0];
        register_setting( 'wpr-elements-settings', 'wpr-element-'. $slug, [ 'default' => 'on' ] );
    }
    register_setting( 'wpr-elements-settings', 'wpr-element-toggle-all', [ 'default' => 'on' ]  );
}

function wpr_addons_settings_page() {

?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php esc_html_e( 'Royal Elementor Addons', 'wpr-addons' ); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?></p>

    <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-plugin-prev-btn#widgets" target="_blank" class="button wpr-options-button">
        <span><?php echo esc_html( 'View Plugin Demo', 'wpr-addons' ); ?></span>
    </a>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_elements';

    // Render Create Templte Popup
    WPR_Templates_Loop::render_create_template_popup();
    
    ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <a href="?page=wpr-addons&tab=wpr_tab_elements" data-title="Elements" class="nav-tab <?php echo $active_tab == 'wpr_tab_elements' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Elements', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-addons&tab=wpr_tab_my_templates" data-title="My Templates" class="nav-tab <?php echo $active_tab == 'wpr_tab_my_templates' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'My Templates', 'wpr-addons' ); ?>
        </a>
        <a href="?page=wpr-addons&tab=wpr_tab_settings" data-title="Settings" class="nav-tab <?php echo $active_tab == 'wpr_tab_settings' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Settings', 'wpr-addons' ); ?>
        </a>
    </div>

    <?php if ( $active_tab == 'wpr_tab_elements' ) : ?>

    <?php

    // Settings
    settings_fields( 'wpr-elements-settings' );
    do_settings_sections( 'wpr-elements-settings' );

    ?>

    <div class="wpr-elements-toggle">
        <div>
            <h3><?php esc_html_e( 'Toggle all Elements', 'wpr-addons' ); ?></h3>
            <input type="checkbox" name="wpr-element-toggle-all" id="wpr-element-toggle-all" <?php checked( get_option('wpr-element-toggle-all', 'on'), 'on', true ); ?>>
            <label for="wpr-element-toggle-all"></label>
        </div>
        <p><?php esc_html_e( 'You can disable some elements for faster page speed.', 'wpr-addons' ); ?></p>
    </div>

    <div class="wpr-elements">

    <?php

    foreach ( Utilities::get_registered_modules() as $title => $data ) {
        $slug = $data[0];
        $url  = $data[1];
        $reff = '?ref=rea-plugin-backend-elements-widget-prev'. $data[2];

        echo '<div class="wpr-element">';
            echo '<div class="wpr-element-info">';
                echo '<h3>'. $title .'</h3>';
                echo '<input type="checkbox" name="wpr-element-'. $slug .'" id="wpr-element-'. $slug .'" '. checked( get_option('wpr-element-'. $slug, 'on'), 'on', false ) .'>';
                echo '<label for="wpr-element-'. $slug .'"></label>';
                echo ( '' !== $url ) ? '<a href="'. $url . $reff .'" target="_blank">'. esc_html('View Widget Demo', 'wpr-addons') .'</a>' : '';
            echo '</div>';
        echo '</div>';
    }
    
    ?>

    </div>

    <?php submit_button( '', 'wpr-options-button' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_my_templates' ) : ?>

        <!-- Custom Template -->
        <?php if ( 'wpr_tab_my_templates' === $_GET['tab'] ) : ?>
        <div class="wpr-user-template">
            <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="plus-icon">+</span>
        </div>
        <?php endif; ?>

        <?php Wpr_Templates_Loop::render_elementor_saved_templates(); ?>

    <?php elseif ( $active_tab == 'wpr_tab_settings' ) : ?>

        <?php

        // Settings
        settings_fields( 'wpr-settings' );
        do_settings_sections( 'wpr-settings' );

        ?>

        <div class="wpr-settings">

        <?php submit_button( '', 'wpr-options-button' ); ?>

        <div class="wpr-settings-group">
            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Integrations', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'Google Map API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://www.youtube.com/watch?v=O5cUoVpVUjU" target="_blank"><?php esc_html_e( 'How to get Google Map API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_google_map_api_key" id="wpr_google_map_api_key" value="<?php echo esc_attr(get_option('wpr_google_map_api_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'MailChimp API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://mailchimp.com/help/about-api-keys/" target="_blank"><?php esc_html_e( 'How to get MailChimp API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_mailchimp_api_key" id="wpr_mailchimp_api_key" value="<?php echo esc_attr(get_option('wpr_mailchimp_api_key')); ?>">
            </div>
        </div>

        <div class="wpr-settings-group">
            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Lightbox', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Background Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_bg_color" id="wpr_lb_bg_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_bg_color','rgba(0,0,0,0.6)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Toolbar BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_toolbar_color" id="wpr_lb_toolbar_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_toolbar_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Caption BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_caption_color" id="wpr_lb_caption_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_caption_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Gallery BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_gallery_color" id="wpr_lb_gallery_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_gallery_color','#444444')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Progress Bar Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_pb_color" id="wpr_lb_pb_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_pb_color','#a90707')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_color" id="wpr_lb_ui_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Hover Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_hr_color" id="wpr_lb_ui_hr_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_hr_color','#ffffff')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_text_color" id="wpr_lb_text_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_text_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Icon Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_icon_size" id="wpr_lb_icon_size" value="<?php echo esc_attr(get_option('wpr_lb_icon_size','20')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Navigation Arrow Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_arrow_size" id="wpr_lb_arrow_size" value="<?php echo esc_attr(get_option('wpr_lb_arrow_size','35')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_text_size" id="wpr_lb_text_size" value="<?php echo esc_attr(get_option('wpr_lb_text_size','14')); ?>">
            </div>
        </div>

        <?php submit_button( '', 'wpr-options-button' ); ?>

        </div>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_settings_page()