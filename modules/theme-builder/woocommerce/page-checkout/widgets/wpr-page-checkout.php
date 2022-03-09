<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageCheckout\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_Checkout extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-checkout';
	}

	public function get_title() {
		return esc_html__( 'Checkout', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-checkout';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'checkout', 'product', 'page', 'checkout-page', 'page-checkout' ];//tmp
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
		$is_editor = Plugin::$instance->editor->is_edit_mode();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		echo do_shortcode( '[woocommerce_checkout]' );

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
    }
}

