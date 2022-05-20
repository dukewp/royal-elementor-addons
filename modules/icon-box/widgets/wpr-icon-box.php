<?php
namespace WprAddons\Modules\Grid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Grid extends Widget_Base {

    public function get_name() {
        return 'wpr-grid';
    }

    public function get_title() {
        return esc_html__( 'Post Grid/Slider/Carousel', 'wpr-addons' );
    }

    public function get_icon() {
        return 'wpr-icon eicon-gallery-grid';
    }

    public function get_categories() {
        return Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [ 'wpr-widgets'];
    }

    public function get_keywords() {
        return [ 'royal', 'blog', 'portfolio grid', 'posts', 'post grid', 'posts grid', 'post slider', 'posts slider', 'post carousel', 'posts carousel', 'massonry grid', 'isotope', 'post gallery', 'posts gallery', 'filterable grid' ];
    }

    public function get_script_depends() {
        return [ 'wpr-isotope', 'wpr-slick', 'wpr-lightgallery' ];
    }

    public function get_style_depends() {
        return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
    }

    public function get_custom_help_url() {
        if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
            return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {

        // Tab: Content ==============
        // Section: Query ------------
        $this->start_controls_section(
            'section_grid_query',
            [
                'label' => esc_html__( 'Query', 'wpr-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

        $this->end_controls_section();

    }

    protected function render() {
        // Get Settings
        $settings = $this->get_settings_for_display();
    }
}