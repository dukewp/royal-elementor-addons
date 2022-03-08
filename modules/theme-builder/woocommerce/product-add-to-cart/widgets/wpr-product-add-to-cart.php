<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductAddToCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_AddToCart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Add to Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-add-to-cart';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-add-to-cart', 'product', 'add-to-cart' ];//tmp
	}

	public function get_script_depends() {
		return ['wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product'];
	}


	protected function _register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_title',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        
        $this->add_control(
            'quantity_btn_position',
            [
                'label'   => esc_html__('Button Style', 'wpr-addons'),
                'type'    => Controls_Manager::SELECT,
				'render_type' => 'template',
                'default' => 'default',
				'prefix_class' => 'wpr-product-add-to-cart-',
                'options' => [
                    'default'   => esc_html__('Default', 'wpr-addons'),
                    'both'      => esc_html__('Both Side', 'wpr-addons'),
                    'before'    => esc_html__('Both Left', 'wpr-addons'),
                    'after'     => esc_html__('Both Right', 'wpr-addons'),
                ],
            ]
        );

		$this->add_control(
			'product_meta_layout',
			[
				'label' => esc_html__( 'List Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'column' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'wpr-add-to-cart-layout-',
				'selectors_dictionary' => [
					'row' => 'display: flex; align-items: center;',
					'column' => 'display: flex; flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart' => '{{VALUE}};'
                ],
				'default' => 'column',
				'label_block' => false,
			]
		);

		$this->add_control(
			'product_buttons_layout',
			[
				'label' => esc_html__( 'Buttons Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'column' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'wpr-buttons-layout-',
				'selectors_dictionary' => [
					'row' => 'flex-direction: row;',
					'column' => 'flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wpr-product-add-to-cart .woocommerce-variation-add-to-cart' => '{{VALUE}};'
                ],
				'default' => 'column',
				'label_block' => false,
			]
		);

        $this->add_responsive_control(
            'add_to_cart_alignment',
            [
                'label'     => esc_html__('Align', 'wpr-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'prefix_class' => 'wpr-product-add-to-cart-',
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_buttons_vertical_alignment',
            [
                'label'     => esc_html__('Vertical Align', 'wpr-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'end'   => [
                        'title' => esc_html__('Left', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'start'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart button' => 'align-self: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'align-self: {{VALUE}}',
                ],
				'condition' => [
					'product_meta_layout' => 'row'
				]
            ]
        );

		$this->add_responsive_control(
			'table_distance',
			[
				'label' => esc_html__( 'Table Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-add-to-cart-layout-row table' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-column table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-row table.variations' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-column table.variations' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_variation_styles',
			[
				'label' => esc_html__( 'Variations', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'add_to_cart_group',
			[
				'label'     => esc_html__('Grouped', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_group_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label',
			[
				'label'     => esc_html__('Labels', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_label_color',
			[
				'label'     => esc_html__('Label Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .variations th label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => [
					'{{WRAPPER}} .variations th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .variations td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .variations tr th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_value',
			[
				'label'     => esc_html__('Values', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => [
					'{{WRAPPER}} .variations tr td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_value_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_table_label_width',
			[
				'label' => esc_html__( 'Label Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .variations tr th' => 'width: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'variations_table_border_type',
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
					'{{WRAPPER}} .variations td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .variations th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variations_table_border_width',
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
					'{{WRAPPER}} .variations td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .variations th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_table_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_variations_select',
			[
				'label' => esc_html__( 'Variations Select', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->start_controls_tabs(
			'variation_select_style_tabs'
		);
		
		$this->start_controls_tab(
			'variation_select_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_select',
			[
				'label'     => esc_html__('Select', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#F2F2F2',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_select_border_type',
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
					'{{WRAPPER}} .variations select' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variations_select_border_width',
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
					'{{WRAPPER}} .variations select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'variations_select_border_radius',
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
					'{{WRAPPER}} .variations select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_select_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color_focus',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color_focus',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#101010',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_select_border_type_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variations_select_border_width_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'variations_select_border_radius_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'variation_select_padding',
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
					'{{WRAPPER}} .variations select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // variations select section

		$this->start_controls_section(
			'section_style_variations_description',
			[
				'label' => esc_html__( 'Variation Item Info', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'variation_title_heading',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label a',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label label'
			]
		);

		$this->add_control(
			'variation_description_heading',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-description p'
			]
		);

		$this->add_control(
			'variation_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_heading',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span',
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price span'
			]
		);

		$this->add_control(
			'variation_price_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'description' => esc_html__('For Variable Products Only', 'wpr-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_heading',
			[
				'label' => esc_html__( 'Availability', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_color_in_stock',
			[
				'label'  => esc_html__( 'In Stock Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-variation-availability p.in-stock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'variation_availability_color_out_of_stock',
			[
				'label'  => esc_html__( 'Out Of Stock Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D21616',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock.out-of-stock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_availability_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-availability p.stock'
			]
		);

		$this->add_control(
			'variation_availability_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_grid_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'add_to_cart_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button'
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'add_to_cart_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 125,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// $this->add_responsive_control(
		// 	'add_to_cart_padding',
		// 	[
		// 		'label' => esc_html__( 'Padding', 'wpr-addons' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', '%' ],
		// 		'default' => [
		// 			'top' => 0,
		// 			'right' => 0,
		// 			'bottom' => 0,
		// 			'left' => 0,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 		// 'render_type' => 'template',
		// 	]
		// );

		$this->add_responsive_control(
			'add_to_cart_margin',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'render_type' => 'template',
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		
		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart_icons',
			[
				'label' => esc_html__( 'Add to Cart quantity', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_add_to_cart_icons_style' );

		$this->start_controls_tab(
			'tab_grid_add_to_cart_icons_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_icons_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_icons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_icons_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_add_to_cart_icons_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_icons_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_icons_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_icons_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_icons_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'add_to_cart_icons_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'add_to_cart_icons_border_type',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_icons_border_width',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'quantity_dimensions',
			[
				'label' => esc_html__( 'Quantity', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'quantity_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty'
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-buttons-layout-row .wpr-product-add-to-cart .wpr-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-buttons-layout-column .wpr-product-add-to-cart .wpr-quantity-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_quantity_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'height: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'height: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.wpr-product-add-to-cart-both .wpr-product-add-to-cart .quantity i' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-add-to-cart-both .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_quantity_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_icons_dimensions',
			[
				'label' => esc_html__( 'Icons', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icons_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .quantity i'
			]
		);

		$this->add_control(
			'add_to_cart_icons_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_icons_radius',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Reset', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'reset_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .reset_variations' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'reset_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .reset_variations' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'reset_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .reset_variations'
			]
		);

		$this->add_responsive_control(
			'reset_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .reset_variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'reset_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .reset_variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
	}

	public function wpr_change_clear_text() {
	   echo '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>';
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'add_to_cart_wrapper',
			[
				'id' => 'add-to-cart-attributes',
				'class' => [ 'wpr-product-add-to-cart' ],
				'layout-settings' => $settings['quantity_btn_position'],
			]
		);

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$btn_arg = [
			'position' => $settings['quantity_btn_position']
		];

		add_action('woocommerce_before_add_to_cart_quantity', function () use ($btn_arg) {

			echo '<div class="wpr-quantity-wrapper">';

			if($btn_arg['position'] === 'before') {
				echo '<div class="wpr-add-to-cart-icons-wrap"><i class="fa fa-plus plus"></i><i class="fa fa-minus minus"></i></div>';
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fa fa-minus minus"></i>';
			}

		});

		add_action('woocommerce_after_add_to_cart_quantity', function () use ($btn_arg) {

			if($btn_arg['position'] === 'after') {
				echo '<div class="wpr-add-to-cart-icons-wrap"><i class="fa fa-plus plus"></i><i class="fa fa-minus minus"></i></div>';
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fa fa-plus plus"></i>';
			}

			echo '</div>';

		});
		
		add_action( 'woocommerce_reset_variations_link' , [$this, 'wpr_change_clear_text'], 15 );

		echo '<div' . ' ' . $this->get_render_attribute_string( 'add_to_cart_wrapper' ) .'>';

			echo woocommerce_template_single_add_to_cart();

		echo '</div>';
	}
	
}