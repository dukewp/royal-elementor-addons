<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageMyAccount\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_My_Account extends Widget_Base {
	
	public function get_name() {
		return 'wpr-my-account';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-my-account';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'account', 'product', 'page', 'account page', 'page checkout', 'My Account' ];
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply Changes</button></div>',
            ]
        );

		$this->end_controls_section();
    }

    protected function render() {
        echo do_shortcode('[woocommerce_my_account]');
    }
}