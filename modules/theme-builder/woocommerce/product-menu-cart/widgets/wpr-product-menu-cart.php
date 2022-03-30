<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMenuCart\Widgets;

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

class Wpr_Product_Menu_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-menu-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Menu Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-menu-cart', 'product', 'menu', 'cart' ];//tmp
	}

	// public function get_script_depends() {
	// 	return [ 'flexslider', 'zoom', 'wc-single-product'  ];
	// }

	protected function register_controls() {

		// Tab: Styles ==============
		// Section: Button ----------
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
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a46497',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a46497',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-menu-cart-toggle-btn',
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Title Typography', 'my-plugin-domain' ),
                'scheme' => Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .wpr-menu-cart-toggle-btn',
                // 'fields_options' => [
                //     'font_weight' => [
                //         'default' => '500',
                //     ],
                //     'font_family' => [
                //         'default' => 'Rubik',
                //     ],
                //     'font_size'   => [
                //         'default' => '14px',
                //     ],
                // ],
            ]
        );

		$this->add_responsive_control(
			'mini_cart_btn_padding',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 6,
					'right' => 10,
					'bottom' => 6,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'mini_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_border_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-menu-cart',
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

		$this->add_control(
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-menu-cart' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-menu-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-menu-cart' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-menu-cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-menu-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    } 

	public function render_menu_cart_toggle() {

		if ( null === WC()->cart ) {
			return;
		}

		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		?>

		<div class="wpr-menu-cart-toggle-wrap">
			<button id="wpr-menu-cart-toggle-btn" href="#" class="wpr-menu-cart-toggle-btn" aria-expanded="false">
				<span class="wpr-menu-cart-btn-text"><?php echo $sub_total; ?></span>
				<span class="wpr-menu-cart-btn-icon" <?php echo $counter_attr; ?>>
					<i class="eicon">
                        <sup class="wpr-menu-cart-icon-count"><?php echo $product_count ?></sup>
                    </i>
					<span class=""><?php esc_html_e( 'Cart', 'wpr-addons' ); ?></span>
				</span>
			</button>
		</div>
		<?php
	}

	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		?>
		<div class="wpr-menu-cart">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
				<div class="">
					<div class="" aria-hidden="true">
						<div class="" aria-hidden="true">
							<div class=""></div>
							<div class="widget_shopping_cart_content">
								<?php if ( $is_edit_mode ) {
									woocommerce_mini_cart();
								} ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
    
    protected function render() {
        echo '<div class="wpr-menu-cart-wrap">';
            $this->render_menu_cart_toggle();
            $this->render_menu_cart();
        echo '</div>';
    }    
}        
