<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageCheckout\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

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
		return Utilities::show_theme_buider_widget_on('product_single') ? [] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'checkout', 'product', 'page', 'checkout page', 'page checkout' ];//tmp
	}

	public function get_script_depends() {
		return [];
	}

	protected function register_controls() {

		// Tab: Style ==============
		// Section: Settings -------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply Changes</button></div>',
            ]
        );

		$this->add_control(
			'checkout_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'vertical',
				'prefix_class' => 'wpr-checkout-',
				'options' => [
					'vertical' => esc_html__( 'One Column', 'wpr-addons' ),
					'horizontal' => esc_html__( 'Two Columns', 'wpr-addons' ),
				],
				'label_block' => false,
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: General --------
		$this->start_controls_section(
			'checkout_general_styles',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkout_general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .col-1' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .col-2' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CCC',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .col-1' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .col-2' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_type',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col-1' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col-2' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_general_border_width',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'checkout_general_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_radius',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'checkout_general_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'checkout_general_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-checkout-horizontal .woocommerce-checkout .col2-set' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-horizontal .col-1' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-horizontal .wpr-checkout-order-review-table-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .col-1' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .col-2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .wpr-checkout-order-review-table-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Forms ----------
		$this->start_controls_section(
			'section_checkout_forms',
			[
				'label' => esc_html__( 'Forms', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_labels_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_labels_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .col2-set label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_labels_typography',
				'selector' => '{{WRAPPER}} .col2-set label',
			]
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 
            'forms_fields_normal_styles',
            [ 
                'label' => esc_html__( 'Normal', 'wpr-addons' ) 
            ] 
        );

		$this->add_control(
			'forms_fields_normal_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F3F3F3',
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .input-text, {{WRAPPER}} .select2-container',
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
					'{{WRAPPER}} .col2-set select' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .col2-set .select2-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set select' => 'border-width: {{VALUE}};',
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
					'{{WRAPPER}} .col2-set .select2-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_fields_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Orders ---------
		$this->start_controls_section(
			'checkout_order_styles',
			[
				'label' => __( 'Orders Table', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkout_table_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Heading', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_table_heading_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'checkout_table_heading_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.woocommerce-orders-table th, {{WRAPPER}} table.shop_table thead th, {{WRAPPER}} table.shop_table tfoot th',
			]
		);

		$this->add_responsive_control(
			'checkout_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'checkout_table_heading_alignment',
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
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table thead th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'checkout_table_description_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Description', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_table_description_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_table_description_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.shop_table td',
			]
		);

		$this->add_responsive_control(
			'checkout_table_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'checkout_table_description_alignment',
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
					'{{WRAPPER}} table.shop_table td' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table .variation' => 'justify-content: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Place Order ------
		$this->start_controls_section(
			'section_style_place_order',
			[
				'label' => esc_html__( 'Place Order', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'payment_methods_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} #payment .place-order' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c36',
				'selectors' => [
					'{{WRAPPER}} #payment .woocommerce-privacy-policy-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_link_hover_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} #payment .woocommerce-privacy-policy-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'place_order_payment_methods_inputs',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'payment_methods_labels_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} li.wc_payment_method label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'payment_methods_inputs_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} ul.payment_methods li.wc_payment_method .input-radio' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'place_order_payment_methods_tooltips',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Tooltips', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'payment_methods_tooltips_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .payment_box p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_tooltips_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} #payment .payment_box' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} #payment .payment_box::before' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Place Order Button ------
		$this->start_controls_section(
			'section_style_place_order_button',
			[
				'label' => esc_html__( 'Place Order Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'place_order_button_styles' );

		$this->start_controls_tab(
			'place_order_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_order_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'place_order_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'place_order_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_order_button_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .button,
				{{WRAPPER}} .place-order button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'place_order_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_order_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'place_order_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'place_order_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_order_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .place-order button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'place_order_button_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'place_order_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'place_order_button_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'place_order_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .actions .button, 
				{{WRAPPER}} .place-order button, {{WRAPPER}} .coupon .button',
			]
		);

		$this->add_control(
			'place_order_button_border_type',
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
					'{{WRAPPER}} .place-order button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'place_order_button_border_width',
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
					'{{WRAPPER}} .place-order button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'place_order_button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'place_order_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 15,
					'bottom' => 10,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'place_order_button_margin',
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
					'{{WRAPPER}} .place-order button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_order_button_radius',
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
					'{{WRAPPER}} .place-order button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

    }

	public function woocommerce_checkout_before_customer_details() {
		echo '<div class="wpr-customer-details-wrapper">';
	}
	public function woocommerce_checkout_before_order_review_heading() {
        echo '<div class="wpr-checkout-order-review-table">';
        echo '<div class="wpr-checkout-order-review-table-inner">';
	}

	public function woocommerce_checkout_after_order_review() {
		echo '</div>';
		echo '</div>';
        echo '</div>';
	}

	public function woocommerce_checkout_order_review() {
		echo '</div>';
		echo '</div>';
		echo '<div class="wpr-checkout-order-review">';
	}

	private function should_render_coupon() {
		return ( WC()->cart->needs_payment() || \Elementor\Plugin::$instance->editor->is_edit_mode() ) && wc_coupons_enabled();
	}

    protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'woocommerce_checkout_before_customer_details' ], 95 );

		add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'woocommerce_checkout_before_order_review_heading' ], 95 );

		add_action( 'woocommerce_checkout_order_review', [ $this, 'woocommerce_checkout_order_review' ], 15 );

		add_action( 'woocommerce_checkout_after_order_review', [ $this, 'woocommerce_checkout_after_order_review' ], 95 );

        echo do_shortcode( '[woocommerce_checkout]' );

		remove_action( 'woocommerce_checkout_before_customer_details', [ $this, 'woocommerce_checkout_before_customer_details' ], 95 );

		remove_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'woocommerce_checkout_before_order_review_heading' ], 95 );

		remove_action( 'woocommerce_checkout_order_review', [ $this, 'woocommerce_checkout_order_review' ], 15 );

		remove_action( 'woocommerce_checkout_after_order_review', [ $this, 'woocommerce_checkout_after_order_review' ], 95 );

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
    }
}

