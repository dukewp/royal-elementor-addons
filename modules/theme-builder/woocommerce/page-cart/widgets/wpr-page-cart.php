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
		return esc_html__( 'Cart', 'wpr-addons' );
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
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				// 'condition' => [
				// 	'update_cart_automatically' => '',
				// ],
			]
		);

		$this->add_control(
			'cart_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'prefix_class' => 'wpr-cart-',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'label_block' => false,
			]
		);

		// $this->add_control(
		// 	'sticky_right_column',
		// 	[
		// 		'label' => esc_html__( 'Sticky Totals', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'label_on' => esc_html__( 'Yes', 'wpr-addons' ),
		// 		'label_off' => esc_html__( 'No', 'wpr-addons' ),
		// 		'frontend_available' => true,
		// 		'prefix_class' => 'wpr-cart-sticky-',
		// 		'condition' => [
		// 			'cart_layout!' => 'vertical',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'heading_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1200,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-cart-horizontal .woocommerce-cart-form' => 'width: 100%;',
					'{{WRAPPER}}.wpr-cart-horizontal table.cart' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-cart-horizontal .cart-collaterals' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'separator' => 'before',
				'condition' => [
					'cart_layout' => 'horizontal'
				]
			]
		);

		$this->add_control(
			'totals_title_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'section_totals_title_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cart_totals h2' => 'text-align: {{VALUE}};',
				], //TODO: doesnt work
			]
		);

		$this->add_control(
			'checkout_button_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkout Button', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'checkout_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'wpr-checkout-flex-',
				'default' => 'justify',
				'selectors_dictionary' => [
					'start' => 'justify-content: flex-start;',
					'center' => 'justify-content: center;',
					'end' => 'justify-content: flex-end;',
					'justify' => 'justify-content: stretch;',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout' => 'display: flex; {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'coupon_input_width',
			[
				'label' => esc_html__( 'Coupon Input Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					// '{{WRAPPER}} table.cart td.actions .input-text' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .input-text:not(.qty)' => 'width: 100% !important;',
					'{{WRAPPER}} .coupon' => 'width: {{SIZE}}{{UNIT}} !important;',
					// '{{WRAPPER}} .wpr-cart-section .input-text' => 'width: 100% !important;',
					// '{{WRAPPER}} .wpr-cart-section .coupon-col-start' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'coupon_input_gutter',
			[
				'label' => esc_html__( 'gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} input[name="coupon_code"]' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_cart_styles',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'update_cart_automatically' => '',
				// ],
			]
		);

		$this->add_control(
			'cart_sections_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table td' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-cart-section' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cart_sections_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .cart_totals, {{WRAPPER}} table.cart',
			]
		);

		$this->add_control(
			'cart_tables_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} table th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} table td' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cart_tables_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} table td' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'cart_tables_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'cart_tables_border_width',
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
					'{{WRAPPER}} table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cart_tables_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'totals_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .cart_totals h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'cart_table_heading_styles',
			[
				'label' => __( 'Table Heading', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cart_table_heading_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_table_heading_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table th' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.cart th',
			]
		);

		$this->add_responsive_control(
			'cart_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cart_table_heading_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'text-align: {{VALUE}};',
				], //TODO: doesnt work
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cart_table_description_styles',
			[
				'label' => __( 'Table Description', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cart_table_description_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_table_description_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.cart tr.cart_item td',
			]
		);

		$this->add_responsive_control(
			'cart_table_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cart_table_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'text-align: {{VALUE}};',
				], //TODO: doesnt work
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_product_image',
			[
				'label' => esc_html__( 'Product Image', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .product-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_forms',
			[
				'label' => esc_html__( 'Forms', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'forms_field_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Field', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'forms_field_typography',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .cart-collaterals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single, {{WRAPPER}} .form-row input',
			]
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 'forms_fields_normal_styles', [ 'label' => esc_html__( 'Normal', 'wpr-addons' ) ] );

		$this->add_control(
			'forms_fields_normal_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .coupon-col-start .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .input-text, {{WRAPPER}} select, {{WRAPPER}} .woocommerce-shipping-calculator span',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_fields_focus_styles', 
			[ 
				'label' => esc_html__( 'Focus', 'wpr-addons' )
			] 
		);

		$this->add_control(
			'forms_fields_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span:focus' => 'color: {{VALUE}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .input-text:focus, {{WRAPPER}} select:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_fields_border_type',
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
					'{{WRAPPER}} .input-text' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} select' => 'border-style: {{VALUE}};',
					// '{{WRAPPER}} .form-row' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_fields_border_width',
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
					'{{WRAPPER}} .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .form-row' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'form_fields_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'form_fields_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .form-row' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Buttons ------
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'button_styles' );

		$this->start_controls_tab(
			'cart_buttons_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'buttons_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'buttons_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .button, {{WRAPPER}} .shipping-calculator-form .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'buttons_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'buttons_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'buttons_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'buttons_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow_hr',
				'selector' => '{{WRAPPER}} .actions .button:hover',
				'{{WRAPPER}} .coupon .button:hover',
				'{{WRAPPER}} .shipping-calculator-form .button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'buttons_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'buttons_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .coupon .button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'buttons_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'buttons_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .actions .button',
				'{{WRAPPER}} .coupon .button',
				'{{WRAPPER}} .shipping-calculator-form .button'
			]
		);

		$this->add_control(
			'buttons_border_type',
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
					'{{WRAPPER}} .actions .button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .coupon .button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'buttons_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .coupon .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'buttons_border_type!' => 'none',
				],
			]
		);
		$this->add_control(
			'buttons_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Checkout Button ------
		$this->start_controls_section(
			'section_style_checkout_button',
			[
				'label' => esc_html__( 'Checkout Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'checkout_button_styles' );

		$this->start_controls_tab(
			'cart_checkout_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'checkout_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'checkout_button_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .button,
				{{WRAPPER}} .wc-proceed-to-checkout .checkout-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkout_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'checkout_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'checkout_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'checkout_button_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'checkout_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'checkout_button_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .actions .button',
				'{{WRAPPER}} .coupon .button',
				'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button'
			]
		);

		$this->add_control(
			'checkout_button_border_type',
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
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'buttons_border_type!' => 'none',
				],
			]
		);

		// $this->add_control(
		// 	'add_to_cart_width',
		// 	[
		// 		'label' => esc_html__( 'Width', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px'],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 300,
		// 			],
		// 		],
		// 		'default' => [
		// 			'size' => 125,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'add_to_cart_height',
		// 	[
		// 		'label' => esc_html__( 'Height', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px'],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'size' => 50,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
		// 		]
		// 	]
		// );

		$this->add_responsive_control(
			'checkout_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'checkout_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
        
    }

	public static function get_custom_border_type_options() {
		return [
			'none' => esc_html__( 'None', 'wpr-addons' ),
			'solid' => esc_html__( 'Solid', 'wpr-addons' ),
			'double' => esc_html__( 'Double', 'wpr-addons' ),
			'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
			'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
			'groove' => esc_html__( 'Groove', 'wpr-addons' ),
		];
	}

	public function woocommerce_before_cart() {
		echo '<div class="wpr-cart-wrapper">';
	}

	public function woocommerce_after_cart() {
		// closing wrapper div
		echo '</div>';
	}

	public function woocommerce_after_cart_table() {
		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$this->render_woocommerce_cart_coupon_form();
		}
	}

	public function woocommerce_before_cart_table() {
		echo '<div class="wpr-cart-section-wrap">';
	}

	protected function is_wc_feature_active( $feature ) {
		switch ( $feature ) {
			case 'checkout_login_reminder':
				return 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );
			case 'shipping':
				if ( class_exists( 'WC_Shipping_Zones' ) ) {
					$all_zones = \WC_Shipping_Zones::get_zones();
					if ( count( $all_zones ) > 0 ) {
						return true;
					}
				}
				break;
			case 'coupons':
				return function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled();
			case 'signup_and_login_from_checkout':
				return 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
			case 'additional_options':
				return ! wc_ship_to_billing_address_only();
			case 'ship_to_billing_address_only':
				return wc_ship_to_billing_address_only();
		}

		return false;
	}


	public function render_woocommerce_cart_coupon_form() {
		$settings = $this->get_settings_for_display();
		$button_classes = [ 'button' ];
		// if ( $settings['forms_buttons_hover_animation'] ) {
		// 	$button_classes[] = 'elementor-animation-' . $settings['forms_buttons_hover_animation'];
		// }
		$this->add_render_attribute(
			'button_coupon', [
				'class' => $button_classes,
				'name' => 'apply_coupon',
				'type' => 'submit',
			]
		);
		?>
			<div class="coupon wpr-cart-section shop_table">
				<div class="form-row coupon-col">
					<div class="coupon-col-start">
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'wpr-addons' ); ?>" />
					</div>
					<div class="coupon-col-end">
						<button <?php $this->print_render_attribute_string( 'button_coupon' ); ?> value="<?php esc_attr_e( 'Apply coupon', 'wpr-addons' ); ?>"><?php esc_attr_e( 'Apply coupon', 'wpr-addons' ); ?></button>
					</div>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			</div>
		</div>
		<?php // locate last tag to close after table
	}

	public function hide_coupon_field_on_cart( $enabled ) {
		return is_cart() ? false : $enabled;
	}
	public function disable_cart_coupon() {
		add_filter( 'woocommerce_coupons_enabled', [ $this, 'cart_coupon_return_false' ], 90 );
	}
	public function enable_cart_coupon() {
		remove_filter( 'woocommerce_coupons_enabled', [ $this, 'cart_coupon_return_false' ], 90 );
	}
	public function cart_coupon_return_false() {
		return false;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		// add_filter( 'gettext', [ $this, 'filter_gettext' ], 20, 3 );
		add_action( 'woocommerce_before_cart', [ $this, 'woocommerce_before_cart' ] );
		// add_action( 'woocommerce_after_cart_table', [ $this, 'woocommerce_after_cart_table' ] );
		add_action( 'woocommerce_before_cart_table', [ $this, 'woocommerce_before_cart_table' ] );
		// add_action( 'woocommerce_before_cart_collaterals', [ $this, 'woocommerce_before_cart_collaterals' ] );
		add_action( 'woocommerce_after_cart', [ $this, 'woocommerce_after_cart' ] );
		// add_action( 'woocommerce_cart_contents', [ $this, 'disable_cart_coupon' ] );
		add_action( 'woocommerce_after_cart_contents', [ $this, 'enable_cart_coupon' ] );

			echo do_shortcode( '[woocommerce_cart]' );

		remove_action( 'woocommerce_before_cart', [ $this, 'woocommerce_before_cart' ] );
		// remove_action( 'woocommerce_after_cart_table', [ $this, 'woocommerce_after_cart_table' ] );
		remove_action( 'woocommerce_before_cart_table', [ $this, 'woocommerce_before_cart_table' ] );
		// remove_action( 'woocommerce_before_cart_collaterals', [ $this, 'woocommerce_before_cart_collaterals' ] );
		remove_action( 'woocommerce_after_cart', [ $this, 'woocommerce_after_cart' ] );
		// remove_filter( 'woocommerce_coupons_enabled', [ $this, 'hide_coupon_field_on_cart' ] );
    }
}