<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductSalesBadge\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_SalesBadge extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-sales-badge';
	}

	public function get_title() {
		return esc_html__( 'Product Sales Badge', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-info';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-sales-badge', 'product', 'sales-badge', 'sales', 'badge' ];//tmp
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_sales_badge_styles',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sales_badge_text',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Sale Badge Text', 'wpr-addons' ),
				'default'     => 'Sale!',
			]
		);

		$this->add_control(
			'sales_badge_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sales_badge_background',
			[
				'label'     => esc_html__( 'Background color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sales_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sales_badge_typography',
				'selector' => '{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sales_badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale',
			]
		);

		$this->add_responsive_control(
			'sales_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sales_badge_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge .wpr-onsale' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sales_badge_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'sales_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-product-sales-badge .wpr-onsale'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'sales_badge_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'wpr-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-sales-badge' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
		// var_dump($settings);
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );

		$sales_badge_text = '<span class="wpr-onsale">' . $settings['sales_badge_text'] . '</span>';

        echo '<div class="wpr-product-sales-badge">';
			if ( $product->is_on_sale() ) {
				echo apply_filters( 'woocommerce_sale_flash', $sales_badge_text, $post, $product );
			} else {
				echo '<p> No sale on this product :( </p>';
			}
        echo '</div>';
    }

}