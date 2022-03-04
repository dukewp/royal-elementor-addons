<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-cart';
	}

	public function get_title() {
		return esc_html__( 'Page Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-woo-cart';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'cart', 'product', 'page', 'cart-page', 'page-cart' ];//tmp
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {
        
		$this->start_controls_section(
			'section_cart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->end_controls_section();
        
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    }
}