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
		return esc_html__( 'Page Cart', 'wpr-addons' );
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
			'section_cart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
		// 		'render_type' => 'none',
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
						'max' => 80,
					],
				],
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-cart-horizontal .woocommerce-cart-form' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-cart-horizontal .cart-collaterals' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'separator' => 'before',
				'condition' => [
					'cart_layout' => 'horizontal'
				]
			]
		);

        $this->end_controls_section();

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
				],
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
					'{{WRAPPER}} .wpr-cart-section .input-text' => 'width: 100% !important;',
					'{{WRAPPER}} .wpr-cart-section .coupon-col-start' => 'width: {{SIZE}}{{UNIT}} !important;',
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
					'{{WRAPPER}} .wpr-cart-section .coupon-col-start' => 'margin-right: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .wpr-cart-section',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_cart_tabs_forms',
			[
				'label' => esc_html__( 'Forms', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'forms_rows_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'default' => [ 'px' => 0 ],
				'selectors' => [
					'{{WRAPPER}}' => '--forms-rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'forms_field_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Field', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'forms_field_typography',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .cart-collaterals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
			]
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 'forms_fields_normal_styles', [ 'label' => esc_html__( 'Normal', 'elementor-pro' ) ] );

		$this->add_control(
			'forms_fields_normal_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-normal-color: {{VALUE}};',
					'.e-woo-select2-wrapper .select2-results__option' => 'color: {{VALUE}};',
					// style select2 arrow
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow b' => 'border-color: {{VALUE}} transparent transparent transparent;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .e-cart-totals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_fields_focus_styles', [ 'label' => esc_html__( 'Focus', 'elementor-pro' ) ] );

		$this->add_control(
			'forms_fields_focus_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-color: {{VALUE}}',
					'.e-woo-select2-wrapper .select2-results__option:focus' => 'color: {{VALUE}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .coupon .input-text:focus, {{WRAPPER}} .e-cart-totals .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .select2-selection--single:focus',
			]
		);

		$this->add_control(
			'forms_fields_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-border-color: {{VALUE}}',
				],
				'condition' => [
					'forms_fields_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'forms_fields_focus_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'elementor-pro' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-focus-transition-duration: {{SIZE}}ms',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'forms_fields_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => '--forms-fields-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'forms_fields_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} ' => '--forms-fields-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// style select2
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container--default .select2-selection--single' => 'height: auto;',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'forms_button_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Buttons', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'forms_button_typography',
				'selector' => '{{WRAPPER}} .shop_table .button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'forms_button_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .shop_table .button',
			]
		);

		$this->start_controls_tabs( 'forms_buttons_styles' );

		$this->start_controls_tab( 'forms_buttons_normal_styles', [ 'label' => esc_html__( 'Normal', 'elementor-pro' ) ] );

		$this->add_control(
			'forms_buttons_normal_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-normal-text-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_buttons_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .shop_table .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_buttons_hover_styles', [ 'label' => esc_html__( 'Hover', 'elementor-pro' ) ] );

		$this->add_control(
			'forms_buttons_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-hover-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_buttons_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .shop_table .button:hover',
			]
		);

		$this->add_control(
			'forms_buttons_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-hover-border-color: {{VALUE}}',
				],
				'condition' => [
					'forms_buttons_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'forms_buttons_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'elementor-pro' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-hover-transition-duration: {{SIZE}}ms',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
			]
		);

		$this->add_control(
			'forms_buttons_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementor-pro' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'forms_buttons_border_type',
			[
				'label' => esc_html__( 'Border Type', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-border-type: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'forms_buttons_border_width',
			[
				'label' => esc_html__( 'Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .shop_table .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'forms_buttons_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'forms_buttons_border_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-border-color: {{VALUE}};',
				],
				'condition' => [
					'forms_buttons_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'forms_buttons_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'forms_buttons_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--forms-buttons-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --forms-buttons-width: auto;',
				],
			]
		);

		$this->end_controls_section();
        
    }

	public static function get_custom_border_type_options() {
		return [
			'none' => esc_html__( 'None', 'elementor-pro' ),
			'solid' => esc_html__( 'Solid', 'elementor-pro' ),
			'double' => esc_html__( 'Double', 'elementor-pro' ),
			'dotted' => esc_html__( 'Dotted', 'elementor-pro' ),
			'dashed' => esc_html__( 'Dashed', 'elementor-pro' ),
			'groove' => esc_html__( 'Groove', 'elementor-pro' ),
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
		$button_classes = [ 'button', 'e-apply-coupon' ];
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
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'elementor-pro' ); ?>" />
					</div>
					<div class="coupon-col-end">
						<button <?php $this->print_render_attribute_string( 'button_coupon' ); ?> value="<?php esc_attr_e( 'Apply coupon', 'elementor-pro' ); ?>"><?php esc_attr_e( 'Apply coupon', 'wpr-addons' ); ?></button>
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
		add_action( 'woocommerce_after_cart_table', [ $this, 'woocommerce_after_cart_table' ] );
		add_action( 'woocommerce_before_cart_table', [ $this, 'woocommerce_before_cart_table' ] );
		// add_action( 'woocommerce_before_cart_collaterals', [ $this, 'woocommerce_before_cart_collaterals' ] );
		add_action( 'woocommerce_after_cart', [ $this, 'woocommerce_after_cart' ] );
		add_action( 'woocommerce_cart_contents', [ $this, 'disable_cart_coupon' ] );
		add_action( 'woocommerce_after_cart_contents', [ $this, 'enable_cart_coupon' ] );

			echo do_shortcode( '[woocommerce_cart]' );

		remove_action( 'woocommerce_before_cart', [ $this, 'woocommerce_before_cart' ] );
		remove_action( 'woocommerce_after_cart_table', [ $this, 'woocommerce_after_cart_table' ] );
		remove_action( 'woocommerce_before_cart_table', [ $this, 'woocommerce_before_cart_table' ] );
		// remove_action( 'woocommerce_before_cart_collaterals', [ $this, 'woocommerce_before_cart_collaterals' ] );
		remove_action( 'woocommerce_after_cart', [ $this, 'woocommerce_after_cart' ] );
		remove_filter( 'woocommerce_coupons_enabled', [ $this, 'hide_coupon_field_on_cart' ] );
    }
}