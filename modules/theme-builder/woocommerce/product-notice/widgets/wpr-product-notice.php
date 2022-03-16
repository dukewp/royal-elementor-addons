<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductNotice\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Notice extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-notice';
	}

	public function get_title() {
		return esc_html__( 'Product Notice', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-woocommerce-notices';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'product notice', 'product', 'notice', 'woocommerce notice', 'message', 'woocommerce message' ];//tmp
	}

	public function get_script_depends() {
		return ['wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product'];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_success_notice_styles',
			[
				'label' => esc_html__( 'Success Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'success_notice_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'success_notice_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_info_notice_styles',
			[
				'label' => esc_html__( 'Info Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'info_notice_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'info_notice_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_error_notice_styles',
			[
				'label' => esc_html__( 'Error Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'error_notice_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-error' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'error_notice_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-error' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) { ?>
            <div class="woocommerce-notices-wrapper">
                <div class="woocommerce-message" role="alert">
                    <a href="http://localhost/royal-wp/cart/" tabindex="1" class="button wc-forward">View cart</a> “V-Neck T-Shirt” has been added to your cart.
                </div>
            </div>
            <div class="woocommerce-notices-wrapper">
                <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
                    <a class="woocommerce-Button button" href="http://localhost/royal-wp/shop/"> Browse products</a> No downloads available yet.
                </div>
            </div>
            <div class="woocommerce-notices-wrapper">
                <ul class="woocommerce-error" role="alert">
			        <li data-id="account_first_name">
			            <strong>First name</strong> is a required field.
                    </li>
                </ul>
            </div>
        <?php } else {
            echo '<div class="wpr-addons-checkout-notice">';
                 // echo is_single() ? wc_print_notices() : '';
                 echo is_single() ? woocommerce_output_all_notices() : '';
             echo '</div>';
        }
    }
}