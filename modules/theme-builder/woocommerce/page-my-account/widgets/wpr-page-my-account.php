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
		return [ 'qq', 'account', 'product', 'page', 'account page', 'page account', 'My Account' ];
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {

		// Tab: Content ==============
		// Section: Settings ---------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'tabs_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
				],
				'default' => 'vertical',
				'render_type' => 'template',
				'prefix_class' => 'wpr-my-account-tabs-',
			]
		);

		$this->add_control(
			'tabs_spacing',
			[
				'label' => esc_html__( 'Tabs Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					// '{{WRAPPER}}.wpr-my-account-tabs-horizontal .woocommerce-MyAccount-navigation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-navigation' => 'margin-right: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'width: calc(70% - {{SIZE}}px);'
					'{{WRAPPER}}.wpr-my-account-tabs-horizontal .woocommerce-MyAccount-content' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'width: calc(70% - {{SIZE}}px);'
				],
				'separator' => 'before',
			]
		);


		$this->add_control( //TODO: change approach
			'text_align',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'text-align: {{VALUE}};'
				]
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

		// Tab: Style ==============
		// Section: Tab Labels ---------
		$this->start_controls_section(
			'section_tab_styles',
			[
				'label' => esc_html__( 'Tab Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab( 
			'normal_tabs_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tabs_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link a',
			]
		);

		$this->add_control(
			'tab_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'tab_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_padding',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				// 'allowed_dimensions' => ['left', 'right'],
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'hover_tabs_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'hover_tab_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography_hover',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'active_tabs_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'active_tab_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'active_tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'alpha' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography_active',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a',
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Tab Content ---------
		$this->start_controls_section(
			'section_tab_content_styles',
			[
				'label' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper'
			]
		);

		$this->add_control(
			'title_paragraph_distance',
			[
				'label' => esc_html__( 'Headlines Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper>p:first-child' => 'margin-bottom: {{SIZE}}px'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Orders ---------
		$this->start_controls_section(
			'my_account_order_styles',
			[
				'label' => __( 'Orders Table', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'my_account_table_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Heading', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'my_account_table_heading_color',
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
			'my_account_table_heading_bg_color',
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
				'name'     => 'my_account_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.woocommerce-orders-table th, {{WRAPPER}} table.shop_table thead th, {{WRAPPER}} table.shop_table tfoot th',
			]
		);

		$this->add_responsive_control(
			'my_account_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'my_account_table_heading_alignment',
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
				] //TODO: doesnt work
			]
		);

		$this->add_control(
			'my_account_table_description_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Description', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'my_account_table_description_color',
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
			'my_account_table_description_bg_color',
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
				'name'     => 'my_account_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.shop_table td',
			]
		);

		$this->add_responsive_control(
			'my_account_table_description_padding',
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
					'{{WRAPPER}} table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'my_account_table_description_alignment',
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
					'{{WRAPPER}} table.shop_table .variation' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} table.shop_table .wc-item-meta' => 'justify-content: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Addresses ---------
		$this->start_controls_section(
			'my_account_addresses_styles',
			[
				'label' => __( 'Addresses', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Forms ---------
		$this->start_controls_section(
			'section_account_forms',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_labels_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper label',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text:focus' => 'color: {{VALUE}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text:focus, {{WRAPPER}} select:focus',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button ---------
		$this->start_controls_section(
			'section_style_account_details_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'account_details_button_styles' );

		$this->start_controls_tab(
			'account_details_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'account_details_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} button.button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'account_details_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'account_details_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} button.button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'account_details_button_box_shadow',
				'selector' => '{{WRAPPER}} button.button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'account_details_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'account_details_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'account_details_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'account_details_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'account_details_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} button.button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'account_details_button_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'account_details_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} button.button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'account_details_button_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'account_details_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} button.button',
			]
		);

		$this->add_control(
			'account_details_button_border_type',
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
					'{{WRAPPER}} button.button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'account_details_button_border_width',
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
					'{{WRAPPER}} button.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'account_details_buttons_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'account_details_button_padding',
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
					'{{WRAPPER}} button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'account_details_button_margin',
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
					'{{WRAPPER}} button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'account_details_button_radius',
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
					'{{WRAPPER}} button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .edit-account>p:last-child' => 'text-align: {{VALUE}};',
				],
			]
		); // TODO: determine location and selectors

		$this->end_controls_section();
    }

	public function before_account_content() {
		$wrapper_class = $this->get_account_content_wrapper( [ 'context' => 'frontend' ] );

		echo '<div class="' . sanitize_html_class( $wrapper_class ) . '">';
	}

	public function after_account_content() {
		echo '</div>';
	}

	public function woocommerce_get_myaccount_page_permalink( $bool ) {
		return get_permalink();
	}

	public function woocommerce_logout_default_redirect_url( $redirect ) {
		return $redirect . '?wpr-addons_wc_logout=true&wpr-addons_my_account_redirect=' . esc_url( get_permalink() );
	} // TODO: what to use instead wpr-addons prefix
	
	private function render_html_front_end() {
		$current_endpoint = $this->get_current_endpoint();
		?>
		<div class="wpr-my-account-tab wpr-my-account-tab__<?php echo sanitize_html_class( $current_endpoint ); ?>">
			<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
		</div>
		<?php
	}

	private function render_html_editor() {
		$settings = $this->get_settings_for_display();
		// Add .wpr-my-account-tab-dashboard as the default class when the editor loads.
		// This class will be replaced with JS when tabs are switched.
		?>
		<div class="wpr-my-account-tab wpr-my-account-tab-dashboard">
			<div class="woocommerce">
			<?php
				wc_get_template( 'myaccount/navigation.php' );

			// In the editor, output all the tabs in order to allow for switching between them via JS.
			$pages = $this->get_account_pages();

			global $wp_query;
			foreach ( $pages as $page => $page_value ) {
				foreach ( $pages as $unset_tab => $unset_tab_value ) {
					unset( $wp_query->query_vars[ $unset_tab ] );
				}
				$wp_query->query_vars[ $page ] = $page_value;

				$wrapper_class = $this->get_account_content_wrapper( [
					'context' => 'editor',
					'page' => $page,
				] );
				?>
				<div class="woocommerce-MyAccount-content" <?php echo $page ? 'wpr-my-account-page="' . esc_attr( $page ) . '"' : ''; ?>>
					<div class="<?php echo sanitize_html_class( $wrapper_class ); ?>">
						<?php
						if ( 'dashboard' === $page ) {
							wc_get_template(
								'myaccount/dashboard.php',
								[
									'current_user' => get_user_by( 'id', get_current_user_id() ),
								]
							);
						} else {
							do_action( 'woocommerce_account_' . $page . '_endpoint', $page_value );
						}
						?>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
		<?php
	}

	private function get_current_endpoint() {
		global $wp_query;
		$current = '';

		$pages = $this->get_account_pages();

		foreach ( $pages as $page => $val ) {
			if ( isset( $wp_query->query[ $page ] ) ) {
				$current = $page;
				break;
			}
		}

		if ( '' === $current && isset( $wp_query->query_vars['page'] ) ) {
			$current = 'dashboard'; // Dashboard is not an endpoint so it needs a custom check.
		}

		return $current;
	}

	private function get_account_content_wrapper( $args ) {
		$user_id = get_current_user_id();
		$num_orders = wc_get_customer_order_count( $user_id );
		$num_downloads = count( wc_get_customer_available_downloads( $user_id ) );
		$class = 'woocommerce-MyAccount-content-wrapper';

		/* we need to render a different css class if there are no orders/downloads to display
		 * as the no orders/downloads screen should not have the default padding and border
		 * around it but show the 'no orders/downloads' notification only
		 */
		if ( 'frontend' === $args['context'] ) { // Front-end display
			global $wp_query;
			if ( ( 0 === $num_orders && isset( $wp_query->query_vars['orders'] ) ) || ( 0 === $num_downloads && isset( $wp_query->query_vars['downloads'] ) ) ) {
				$class .= '-no-data';
			}
		} else { // Editor display
			if ( ( 0 === $num_orders && 'orders' === $args['page'] ) || ( 0 === $num_downloads && 'downloads' === $args['page'] ) ) {
				$class .= '-no-data';
			}
		}

		return $class;
	}
	
	private function get_account_pages() {
		$pages = [
			'dashboard' => '',
			'orders' => '',
			'downloads' => '',
			'edit-address' => '',
		];

		// Check if payment gateways support add new payment methods.
		$support_payment_methods = false;
		foreach ( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {
			if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
				$support_payment_methods = true;
				break;
			}
		}

		if ( $support_payment_methods ) {
			$pages['payment-methods'] = '';
		}

		// Edit account.
		$pages['edit-account'] = '';

		// Get the latest order (if there is one) for view-order (order preview) page.
		$recent_order = wc_get_orders( [
			'limit' => 1,
			'orderby'  => 'date',
			'order'    => 'DESC',
		] );

		if ( ! empty( $recent_order ) ) {
			$pages['view-order'] = $recent_order[0]->get_id();
		}

		return $pages;
	}

    protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		// Add actions & filters before displaying our Widget.
		add_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 2 );
		add_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		add_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		// add_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );

		// Display our Widget.
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_html_front_end();
		} else {
			$this->render_html_editor();
		}

		// Remove actions & filters after displaying our Widget.
		remove_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 5 );
		remove_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		remove_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		// remove_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
    }
}