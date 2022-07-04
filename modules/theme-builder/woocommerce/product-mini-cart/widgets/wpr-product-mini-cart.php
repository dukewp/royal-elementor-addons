<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMiniCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Mini_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-mini-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Mini Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-ini-cart', 'product', 'mini', 'cart' ];//tmp
	}

	// public function get_script_depends() {
	// 	return [ 'flexslider', 'zoom', 'wc-single-product'  ];
	// }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mini_cart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
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
			'icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'cart-light' => esc_html__( 'Cart Light', 'wpr-addons' ),
					'cart-medium' => esc_html__( 'Cart Medium', 'wpr-addons' ),
					'cart-solid' => esc_html__( 'Cart Solid', 'wpr-addons' ),
					'basket-light' => esc_html__( 'Basket Light', 'wpr-addons' ),
					'basket-medium' => esc_html__( 'Basket Medium', 'wpr-addons' ),
					'basket-solid' => esc_html__( 'Basket Solid', 'wpr-addons' ),
					'bag-light' => esc_html__( 'Bag Light', 'wpr-addons' ),
					'bag-medium' => esc_html__( 'Bag Medium', 'wpr-addons' ),
					'bag-solid' => esc_html__( 'Bag Solid', 'wpr-addons' )
				],
				'default' => 'cart-medium',
				'prefix_class' => 'wpr-toggle-icon-',
			]
		);

		$this->add_control(
			'toggle_text',
			[
				'label' => esc_html__( 'Toggle Text', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'price' => esc_html__( 'Total Price', 'wpr-addons' ),
					'title' => esc_html__( 'Extra Text', 'wpr-addons' )
				],
				'default' => 'price',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_title',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Cart', 'wpr-addons' ),
				'default' => esc_html__( 'Cart', 'wpr-addons' ),
				'condition' => [
					'toggle_text' => 'title'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_text_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-text' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mini-cart-btn-price' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'separator' => 'after',
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_alignment',
			[
				'label' => esc_html__( 'Button Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-wrap' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_alignment',
			[
				'label' => esc_html__( 'Cart Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'render_type' => 'template',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'prefix_class' => 'wpr-mini-cart-align-',
				'selectors_dictionary' => [
					'left' => 'left: 0;',
					'right' => 'right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => '{{VALUE}}',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => '{{VALUE}}'
				]
			]
		);

		$this->add_control(
			'mini_cart_style',
			[
				'label' => esc_html__( 'Cart Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'dropdown' => esc_html__( 'Dropdown', 'wpr-addons' ),
					'sidebar' => esc_html__( 'Sidebar', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-mini-cart-',
				'default' => 'dropdown'
			]
		); 

		$this->add_control(
			'mini_cart_entrance',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mini-cart-',
				'condition' => [
						'mini_cart_style' => 'dropdown'
				]
			]
		);

        $this->add_control(
            'mini_cart_entrance_speed',
            [
                'label' => __( 'Entrance Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'render_type' => 'template',
            ]
        );

		$this->add_responsive_control(
			'mini_cart_subtotal_alignment',
			[
				'label' => esc_html__( 'Subtotal & Buttons', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'bottom',
				'render_type' => 'template',
				'options' => [
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'auto' => [
						'title' => esc_html__( 'Auto', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					]
				],
				'prefix_class' => 'wpr-subtotal-align-',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'mini_cart_separators',
			[
				'label'     => esc_html__('Separator', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'separator_style',
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
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-style: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'separator_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors'    => [
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-width: {{SIZE}}{{UNIT}}'
				],
				'condition' => [
					'separator_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_close_btn',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'show_mini_cart_close_btn',
			[
				'label' => esc_html__( 'Show', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'wpr-close-btn-',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'show_close_cart_heading',
			[
				'label' => esc_html__( 'Sidebar Heading', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'render_type' => 'template',
				'prefix_class' => 'wpr-sidebar-heading-',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'close_cart_heading',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Shopping Cart', 'wpr-addons' ),
				'default' => esc_html__( 'Shopping Cart', 'wpr-addons' ),
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'show_close_cart_heading' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_close_btn_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'show_close_cart_heading!' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_heading_align',
			[
				'label' => esc_html__( 'Title Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors_dictionary' => [
					'left' => '',
					'right' => 'flex-direction: row-reverse;'
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-sidebar-heading-yes .wpr-close-cart' => '{{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'show_close_cart_heading' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_mini_cart_button',
			[
				'label' => esc_html__( 'Cart Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mini_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mini-cart-toggle-btn',
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Title Typography', 'my-plugin-domain' ),
                'scheme' => Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .wpr-mini-cart-toggle-btn, {{WRAPPER}} .wpr-mini-cart-icon-count',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				]
            ]
        );

		$this->add_responsive_control(
			'mini_cart_btn_padding',
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
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_type',
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
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_width',
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
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_border_radius',
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
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_cart_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-icon' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Count', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_item_count_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_font_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Styles ==============
		// Section: Mini Cart ----------
		$this->start_controls_section(
			'section_mini_cart',
			[
				'label' => esc_html__( 'Mini Cart', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mini_cart_close_btn_styles',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart span:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_close_btn_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart span:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_close_btn_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_sidebar_heading',
			[
				'label'     => esc_html__('Heading', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'show_close_cart_heading' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_sidebar_heading_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}}.wpr-sidebar-heading-yes .wpr-close-cart h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'show_close_cart_heading' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_sidebar_heading_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}.wpr-sidebar-heading-yes .wpr-close-cart h2',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					]
					],
					'condition' => [
						'mini_cart_style' => 'sidebar',
						'show_mini_cart_close_btn' => 'yes',
						'show_close_cart_heading' => 'yes'
					]
			]
		);

		$this->add_control(
			'mini_cart_product_title',
			[
				'label'     => esc_html__('Title', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_title_color_hover',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart-item a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'line_height'    => [
						'default' => [
							'size' => '1.1',
							'unit' => 'em',
						],
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'mini_cart_product_attributes',
			[
				'label'     => esc_html__('Attributes', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_attributes_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} dl.variation dt' => 'color: {{VALUE}}',
					'{{WRAPPER}} dl.variation dd' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_attributes_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} dl.variation',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'mini_cart_product_quantity',
			[
				'label'     => esc_html__('Quantity & Price ', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_quantity_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .quantity' => 'color: {{VALUE}}',
					'{{WRAPPER}} .quantity .woocommerce-Price-amount' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_quantity_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .quantity, {{WRAPPER}} .quantity .woocommerce-Price-amount',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'mini_cart_subtotal',
			[
				'label'     => esc_html__('Subtotal', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_subtotal_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__empty-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_subtotal_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total, {{WRAPPER}} .woocommerce-mini-cart__empty-message',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'subtotal_align',
			[
				'label'     => esc_html__('Alignment', 'wpr-addons'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-shopping-cart-wrap ' => 'background: {{VALUE}}'
				],
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mini-cart',
                'fields_options' => [
                    'box_shadow_type' =>
                        [ 
                            'default' =>'yes' 
                        ],
                    'box_shadow' => [
                        'default' =>
                            [
                                'horizontal' => 0,
                                'vertical' => 0,
                                'blur' => 0,
                                'spread' => 0,
                                'color' => 'rgba(0,0,0,0.3)'
                            ]
                    ]
                ]
			]
        );

		$this->add_responsive_control(
			'mini_cart_distance',
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'mini_cart_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 375,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-mini-cart-dropdown .wpr-mini-cart' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'width: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->add_responsive_control(
			'mini_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_border_type',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_border_width',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'mini_cart_border_radius',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Tab: Styles ==============
		// Section: Mini Cart List ----------
		$this->start_controls_section(
			'section_mini_cart_items',
			[
				'label' => esc_html__( 'Mini Cart List', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'mini_cart_list_height',
			[
				'label' => esc_html__( 'List Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
				],
				'default' => [
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: scroll;',
                ]
			]
		);

		$this->add_responsive_control(
			'mini_cart_list_distance',
			[
				'label' => esc_html__( 'List Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'margin-bottom: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}; padding-top: 0;',
					'{{WRAPPER}} .woocommerce-mini-cart-item:last-child' => 'margin-bottom: 0;',
                ]
			]
		);

		$this->add_control(
			'scrollbar_thumb_color',
			[
				'label' => esc_html__( 'Scrollbar Color', 'wpr-addons' ),
				'default' => '#605BE5A1',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart::-webkit-scrollbar-thumb' => 'border-right-color: {{VALUE}};',
				]
			]
		);

		// $this->add_control(
		// 	'scrollbar_track_color',
		// 	[
		// 		'label' => esc_html__( 'Background Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .woocommerce-mini-cart::-webkit-scrollbar-track' => 'background-color: {{VALUE}};',
		// 		]
		// 	]
		// );

		$this->add_control(
			'mini_cart_image',
			[
				'label'     => esc_html__('Image', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'mini_cart_image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-wrap .woocommerce-mini-cart-item' => 'grid-template-columns: {{SIZE}}% auto;'
                ]
			]
		);

		$this->add_responsive_control(
			'mini_cart_image_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-image' => 'margin-right: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->end_controls_section();

		// Tab: Styles ==============
		// Section: Remove Icon ----------
		$this->start_controls_section(
			'section_remove_icon',
			[
				'label' => esc_html__( 'Remove Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'remove_icon_styles' );

		$this->start_controls_tab( 
			'remove_icon_styles_normal', 
			[ 
				'label' => esc_html__( 'Normal', 'wpr-addons' ) 
			] 
		);

		$this->add_control(
			'remove_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.remove' => 'color: {{VALUE}} !important;',
				]
			]
		);

		$this->add_control(
			'remove_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.remove' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab( 
			'remove_icon_styles_hover', 
			[ 
				'label' => esc_html__( 'Hover', 'wpr-addons' ) 
			] 
		);

		$this->add_control(
			'remove_icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.remove:hover' => 'color: {{VALUE}} !important;',
				]
			]
		);

		$this->add_control(
			'remove_icon_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.remove:hover' => 'background-color: {{VALUE}} !important;',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'mini_cart_remove_icon_align_vr',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'separator' => 'before',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'top: 0;',
					'middle' => 'top: 50%; transform: translateY(-50%);',
					'bottom' => 'bottom: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-remove' => '{{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'remove_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 12
				],
				'selectors' => [
					'{{WRAPPER}} a.remove::before' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'remove_icon_bg_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} a.remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mini-cart-remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'remove_icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} a.remove' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Buttons --------
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Mini Cart Buttons', 'wpr-addons' ),
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
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'buttons_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .woocommerce-mini-cart__buttons a.button',
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
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'buttons_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow_hr',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover',
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
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'transition-duration: {{VALUE}}s'
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
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons a.button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '600',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'buttons_border_width',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 12,
					'bottom' => 12,
					'left' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'buttons_distance_vertical',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons' => 'margin-top: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->add_responsive_control(
			'buttons_inner_gutter',
			[
				'label' => esc_html__( 'Inner Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:first-child' => 'margin-right: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->add_responsive_control(
			'buttons_outer_gutter',
			[
				'label' => esc_html__( 'Outer Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->end_controls_section();
    } 

	public function render_mini_cart_toggle($settings) {

		if ( null === WC()->cart ) {
			return;
		}

		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		?>

		<span class="wpr-mini-cart-toggle-wrap">
			<button id="wpr-mini-cart-toggle-btn" href="#" class="wpr-mini-cart-toggle-btn" aria-expanded="false">
				<?php if ( 'none' !== $settings['toggle_text']) :
						if ( 'price' == $settings['toggle_text'] ) { ?>
							<span class="wpr-mini-cart-btn-price">
								<?php echo $sub_total;  ?>
							</span>
						<?php } else { ?>
							<span class="wpr-mini-cart-btn-text">
								 <?php esc_html_e( $settings['toggle_title'], 'wpr-addons' ); ?>
							</span>
						<?php } 
				endif; ?>
				<span class="wpr-mini-cart-btn-icon" <?php echo $counter_attr; ?>>
					<i class="eicon">
                        <span class="wpr-mini-cart-icon-count <?php echo $product_count ? '' : 'wpr-mini-cart-icon-count-hidden'; ?>"><span><?php echo $product_count ?></span></span>
                    </i>
				</span>
			</button>
		</span>
		<?php
	}

	public function render_close_cart_icon () {
		echo '<div class="wpr-close-cart">';
			echo '<span></span>';
		echo '</div>';
	}

	public static function render_mini_cart($settings) {
		if ( null === WC()->cart ) {
			return;
		}

		$close_cart_heading = isset($settings['close_cart_heading']) ? $settings['close_cart_heading'] : 'Shopping Cart';
		$show_close_cart_heading = isset($settings['show_close_cart_heading']) ? $settings['show_close_cart_heading'] : 'no';

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		?>
		<div class="wpr-mini-cart">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
				<div class="">
					<div class="" aria-hidden="true">
						<div class="wpr-shopping-cart-wrap" aria-hidden="true">
							<div class="widget_shopping_cart_content">
								<?php woocommerce_mini_cart(['close_cart_heading' => $close_cart_heading, 'show_close_cart_heading' => $show_close_cart_heading]); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
    
    protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'mini_cart_attributes',
			[
				'data-animation' => $settings['mini_cart_entrance_speed']
			]
		);

		// add_action('woocommerce_before_mini_cart', [$this, 'render_close_cart_icon']);

        echo '<div class="wpr-mini-cart-wrap woocommerce"' . $this->get_render_attribute_string( 'mini_cart_attributes' ) . '>';
			echo '<span class="wpr-mini-cart-inner">';
				$this->render_mini_cart_toggle($settings);
				$this->render_mini_cart($settings);
			echo '</span>';
        echo '</div>';
    }    
}        
