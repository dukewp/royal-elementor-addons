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
			'quantity_plus_icon',
			[
				'label'   => esc_html__('Plus Icon', 'wpr-addons'),
				'type'    => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'quantity_btn_position!' => 'default',
				]
			]
		);

		$this->add_control(
			'quantity_minus_icon',
			[
				'label'   => esc_html__('Minus Icon', 'wpr-addons'),
				'type'    => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'quantity_btn_position!' => 'default',
				]
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
				'selectors_dictionary' => [
					'row' => 'display: flex; align-items: center;',
					'column' => '',
				],
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart' => '{{VALUE}};'
                ],
				'default' => 'column',
				'label_block' => false,
			]
		);

        $this->add_responsive_control(
            'add_to_cart_alignment',
            [
                'label'     => esc_html__('Alignment', 'wpr-addons'),
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
                ],
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-title'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-title',
				'separator' => 'after',
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

		$this->add_responsive_control(
			'add_to_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

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
				'render_type' => 'template',
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
				'label' => esc_html__( 'Add to Cart Icons', 'wpr-addons' ),
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
				],
			]
		);

		$this->add_control(
			'add_to_cart_icons_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
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
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'add_to_cart_icons_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

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

		extract($settings);
		// plus minus button
		$btn_arg = [
			'plus_icon'  => $quantity_plus_icon,
			'minus_icon' => $quantity_minus_icon,
			'position'   => $quantity_btn_position,
		];

		echo '<div'. $this->get_render_attribute_string( 'add_to_cart_wrapper' ) .'>';

			echo woocommerce_template_single_add_to_cart();

		echo '</div>';
	}
	
}