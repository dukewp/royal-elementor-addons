<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\WooCategoryGrid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;
use WprAddons\Classes\WPR_Post_Likes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Woo_Category_Grid extends Widget_Base {
	
	public function get_name() {
		return 'wpr-woo-category-grid';
	}

	public function get_title() {
		return esc_html__( 'Woo Category Grid', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? [ 'wpr-woocommerce-builder-widgets' ] : ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'product category grid', 'woocommerce category', 'product categories'];
	}

	public function get_script_depends() {
		return [ 'wpr-isotope', 'wpr-slick', 'wpr-lightgallery' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-woo-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function get_product_taxonomies() {
		return [
			'product_cat' => esc_html__( 'Product Categories', 'wpr-addons' ),
			'product_tag' => esc_html__( 'Product Tags', 'wpr-addons' )
		];
	}

    public function register_controls() {

		// Tab: Content ==============
		// Section: Query ------------
		$this->start_controls_section(
			'section_woo_category_query',
			[
				'label' => esc_html__( 'Query', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
        

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Select Taxonomy', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'product_cat',
				'options' => $this->get_product_taxonomies(),
			]
		);

        $this->end_controls_section();
    }

    public function render() {

    }
}