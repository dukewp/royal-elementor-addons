<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductTabs\Widgets;

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

class Wpr_Product_Tabs extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Tabs', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-tabs';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-tabs', 'product', 'tabs' ];//tmp
	}

	public function get_script_depends() {
		return [ 'wc-single-product' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_tabs_content',
			[
				'label' => esc_html__( 'Tabs', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'tabs_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'default' => 'above',
				'options' => [
					'above' => esc_html__( 'Default', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-tabs-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'tabs_hr_position',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'justify',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
						'title' => esc_html__( 'Stretch', 'wpr-addons' ),
						'icon' => 'eicon-h-align-stretch',
					],
                ],
				'prefix_class' => 'wpr-tabs-hr-position-',
				'render_type' => 'template',
				'condition' => [
					'tabs_position' => 'above',
				],
            ]
        );

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
	            'tabs_align_pro_notice',
	            [
					'raw' => 'Horizontal Align option is fully supported in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-tabs-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Horizontal Align option is fully supported in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'tabs_hr_position!' => 'justify',
					],
				]
	        );
		}

		$this->add_control(
			'tabs_vr_position',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'top',
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
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs-wrapper .wc-tab' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'tabs_position!' => 'above',
				],
			]
		);

		$this->add_control( //TODO: change approach
			'text_align',
			[
				'label' => esc_html__( 'Label Alignment', 'wpr-addons' ),
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs li' => 'display: flex; align-items: {{VALUE}}; justify-content: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tabs_width',
			[
				'label' => esc_html__( 'Label Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs li' => 'min-width: {{SIZE}}px;'
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tabs_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'click' => esc_html__( 'Click', 'wpr-addons' ),
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'active_tab',
			[
				'label' => esc_html__( 'Active Tab Index', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'min' => 1,
				'default' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);
		
		$this->add_control(
			'content_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'condition' => [
					'content_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
				],
				'condition' => [
					'content_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab( 'normal_tabs_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tab_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tabs_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					// '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'active_tabs_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'active_tab_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'active_tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F6EFEF',
				'alpha' => false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel, {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-bottom-color: {{VALUE}}',
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
					// '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					// '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-color: {{VALUE}} {{VALUE}} {{active_tab_bg_color.VALUE}} {{VALUE}}',
					// '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li:not(.active)' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'tab_margin_active',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => -1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'separator_tabs_style',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
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
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-style: {{VALUE}};',
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
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tab_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
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
					'{{WRAPPER}} .wc-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
					'{{WRAPPER}} .wc-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_panel_style',
			[
				'label' => esc_html__( 'Panel', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

		$this->add_control(
			'heading_panel_heading_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_heading_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel h2',
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					// 'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'panel_border_width',
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
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; margin-top: -{{TOP}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'panel_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'margin-left: {{TOP}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'panel_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        wc_get_template( 'single-product/tabs/tabs.php' );

        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
            </script>
            <?php
        }
    }

    public function render_plain_content() {}

    public function get_group_name() {
        return 'woocommerce';
    }
}