<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Classes\Utilities;
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
    <p><?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?></p>

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
    <div class="wpr-condition-popup-wrap wpr-admin-popup-wrap">
        <div class="wpr-condition-popup wpr-admin-popup">
            <h2><?php esc_html_e( 'Where Do You Want to Display Your Template?', 'wpr-addons' ); ?></h2>
            <p>
                <?php esc_html_e( 'Set the conditions that determine where your Template is used throughout your site.', 'wpr-addons' ); ?><br>
                <?php esc_html_e( 'For example, choose \'Entire Site\' to display the template across your site.', 'wpr-addons' ); ?>
            </p>
            <span class="close-popup dashicons dashicons-no-alt"></span>

            <!-- Conditions -->
            <div class="wpr-conditions-wrap">
                <div class="wpr-conditions-sample">
                    <!-- Global -->
                    <select name="global_condition_select" class="global-condition-select">
                        <option value="global"><?php esc_html_e( 'Entire Site', 'wpr-addons' ); ?></option>
                        <option value="archive"><?php esc_html_e( 'Archives', 'wpr-addons' ); ?></option>
                        <option value="single"><?php esc_html_e( 'Singular', 'wpr-addons' ); ?></option>
                    </select>
                    <!-- Archive -->
                    <select name="archives_condition_select" class="archives-condition-select">
                        <option value="posts"><?php esc_html_e( 'Posts Archive', 'wpr-addons' ); ?></option>
                        <option value="author"><?php esc_html_e( 'Author Archive', 'wpr-addons' ); ?></option>
                        <option value="date"><?php esc_html_e( 'Date Archive', 'wpr-addons' ); ?></option>
                        <option value="search"><?php esc_html_e( 'Search Results', 'wpr-addons' ); ?></option>
                        <option value="categories" class="custom-ids"><?php esc_html_e( 'Post Categories', 'wpr-addons' ); ?></option>
                        <option value="tags" class="custom-ids"><?php esc_html_e( 'Post Tags', 'wpr-addons' ); ?></option>
                        <?php // Custom Taxonomies
                            $custom_taxonomies = Utilities::get_custom_types_of( 'tax', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                // Add Shop Archives
                                if ( 'product_cat' === $key ) {
                                    echo '<option value="products">'. esc_html__( 'Products Archive', 'wpr-addons' ) .'</option>';
                                }
                                // List Taxonomies
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .'</option>';
                            }
                        ?>
                    </select>
                    <!-- Single -->
                    <select name="singles_condition_select" class="singles-condition-select">
                        <option value="front_page"><?php esc_html_e( 'Front Page', 'wpr-addons' ); ?></option>
                        <option value="page_404"><?php esc_html_e( '404 Page', 'wpr-addons' ); ?></option>
                        <option value="pages" class="custom-ids"><?php esc_html_e( 'Pages', 'wpr-addons' ); ?></option>
                        <option value="posts" class="custom-ids"><?php esc_html_e( 'Posts', 'wpr-addons' ); ?></option>
                        <?php // Custom Post Types
                            $custom_taxonomies = Utilities::get_custom_types_of( 'post', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .'</option>';
                            }
                        ?>
                    </select>
                    <input type="text" name="condition_input_ids" class="wpr-condition-input-ids">
                    <span class="wpr-delete-conditions dashicons dashicons-no-alt"></span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <span class="wpr-add-conditions button"><?php esc_html_e( 'Add Conditions', 'wpr-addons' ); ?></span>
            <span class="wpr-save-conditions button button-primary"><?php esc_html_e( 'Save Conditions', 'wpr-addons' ); ?></span>

        </div>
    </div>

    <!-- Render Create Templte Popup -->
    <?php WPR_Templates_Loop::create_template_popup(); ?>

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
        
        <?php WPR_Templates_Loop::get_theme_builder_templates( 'header' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_footer' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_footer_conditions" id="wpr_footer_conditions" value="<?php echo esc_attr(get_option('wpr_footer_conditions', '[]')); ?>">
        
        <?php WPR_Templates_Loop::get_theme_builder_templates( 'footer' ); ?>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_theme_builder_page()