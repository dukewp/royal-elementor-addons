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
use Elementor\Group_Control_Background;
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
		return [ 'wc-single-product', 'photoswipe-ui-default', 'photoswipe-default-skin', 'zoom', 'flexslider' ];
	}

	public function get_style_depends() {
		return [ 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'photoswipe-ui-default', 'photoswipe-default-skin' ];
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
					// '{{WRAPPER}} .wc-tabs-wrapper .wc-tab' => 'align-self: {{VALUE}};',
					'{{WRAPPER}} .wc-tabs-wrapper .wc-tabs' => 'justify-content: {{VALUE}};',
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
				// 'selectors_dictionary' => [
				// 	'left' => 'flex-start',
				// 	'center' => 'center',
				// 	'right' => 'flex-end'
				// ],
				'selectors' => [
					// '{{WRAPPER}} .wc-tabs li' => 'display: flex; align-items: {{VALUE}}; justify-content: {{VALUE}};',
					'{{WRAPPER}} .wc-tabs li' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wc-tabs li a' => 'text-align: {{VALUE}};'
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
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
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
				'alpha' => true,
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
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
				'label' => esc_html__( 'Color', 'wpr-addons' ),
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
				'default' => '#FFF',
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography_active',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
					'{{WRAPPER}} .wc-tabs li a.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
					'{{WRAPPER}} .wc-tabs li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .wc-tabs li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
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
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				],
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

		$this->add_control(
			'panel_heading_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
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

		$this->add_responsive_control(
			'heading_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'condition' => [
					'content_border_type!' => 'none',
				]
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

		$this->add_responsive_control(
			'panel_padding',
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
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // li or a ?
				],
				'separator' => 'before',
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

		$this->start_controls_section(
			'section_product_tabs_comments',
			[
				'label' => esc_html__( 'Comments', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_align',
			[
				'label' => esc_html__( 'Form Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
				'default' => 'left',
				'prefix_class' => 'wpr-forms-align-',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .wpr-forms-container .wpcf7-form' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .wpr-forms-container .wpforms-field-container' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .wpr-forms-container .nf-form-wrap' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .wpr-forms-container .nf-form-wrap .field-wrap' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sectoion_avatar_styles',
			[
				'label' => esc_html__( 'Avatar', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
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
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce #reviews #comments ol.commentlist li img.avatar' => 'width: {{SIZE}}px; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					// '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce #reviews #comments ol.commentlist li img.avatar' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'avatar_border_type',
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
					'{{WRAPPER}} .woocommerce #reviews #comments ol.commentlist li img.avatar' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avatar_border_width',
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
					'{{WRAPPER}} .woocommerce #reviews #comments ol.commentlist li img.avatar' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'avatar_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'avatar_border_radius',
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
					'{{WRAPPER}} .woocommerce #reviews #comments ol.commentlist li img.avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_margin',
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
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs #reviews #comments ol.commentlist li .comment-text' => 'margin-left: calc({{SIZE}}px + {{avatar_size.SIZE}}px);'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_info_syles',
			[
				'label' => esc_html__('Additional Info', 'wpr-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'additional_info_divider_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'alpha'		=> false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce table.shop_attributes' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes td' => 'border-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		// additional info label
		$this->add_control(
			'additional_info_label',
			[
				'label'     => esc_html__('Label', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
                'separator'  => 'before',
			]
		);

		$this->add_control(
			'additional_info_label_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'alpha'		=> false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_label_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'alpha'		=> false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr th' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_th_typography',
				'label'          => esc_html__('Typography', 'wpr-addons'),
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel tr :is(th)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'wpr-addons'),
						'default' => [
							'size' => '19',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'wpr-addons'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_control(
			'additional_info_th_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
				// 'prefix_class' => 'wpr-forms-submit-',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr th' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'additional_info_label_width',
			[
				'label'      => esc_html__('Width', 'wpr-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 25,
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr th' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// additional info value
		$this->add_control(
			'additional_info_value_heading',
			[
				'label'     => esc_html__('Value', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
                'separator'  => 'before',
			]
		);

		$this->add_control(
			'additional_information_value_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#101010',
				'alpha'		=> false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr td p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_information_value_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdfdfd',
				'alpha'		=> false,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr td' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'additional_info_td_typography',
				'label'          => esc_html__('Typography', 'wpr-addons'),
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel tr :is(td, p)',
				'exclude'        => ['font_family', 'text_transform', 'text_decoration'],
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					],
					'font_weight'    => [
						'default' => '400',
					],
					'text_transform' => [
						'default' => 'none',
					],
					'line_height'     => [
						'label'      => esc_html__('Line Height (px)', 'wpr-addons'),
						'default' => [
							'size' => '19',
							'unit' => 'px',
						],
						'size_units' => ['px'],
						'tablet_default' => [
							'unit' => 'px',
						],
						'mobile_default' => [
							'unit' => 'px',
						],
					],
					'letter_spacing' => [
						'label'      => esc_html__('Letter Spacing (px)', 'wpr-addons'),
						'size_units' => ['px'],
					],
				],
			]
		);

		$this->add_control(
			'additional_info_td_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
				// 'prefix_class' => 'wpr-forms-submit-',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr td' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'      => esc_html__('Padding', 'wpr-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'top'      => '15',
					'right'    => '35',
					'bottom'   => '15',
					'left'     => '35',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'separator' => 'before',
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-Tabs-panel tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-Tabs-panel tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'additional_info_border_type',
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
					'{{WRAPPER}} .woocommerce table.shop_attributes' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes td' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'additional_info_border_width',
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
					'{{WRAPPER}} .woocommerce table.shop_attributes' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce table.shop_attributes td' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'additional_info_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles ====================
		// Section: Title & Description
		$this->start_controls_section(
			'section_style_field_label',
			[
				'label' => esc_html__( 'Field Label', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				// 'conditions' => [
				// 	'relation' => 'or',
				// 	'terms' => [
				// 		[
				// 			'name' => 'show_form_title',
				// 			'operator' => '!==',
				// 			'value' => '',
				// 		],
				// 		[
				// 			'name' => 'show_form_description',
				// 			'operator' => '!==',
				// 			'value' => '',
				// 		],
				// 	],
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_label_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .comment-form-comment label',
			]
		);

		$this->add_responsive_control(
			'header_distance',
			[
				'label' => esc_html__( 'Distance Top', 'wpr-addons' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce #review_form #respond p' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .comment-reply-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'field_label_distance',
			[
				'label' => esc_html__( 'Distance Bottom', 'wpr-addons' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .comment-reply-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
		// Styles ====================
		// Section: Inputs -----------
		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__( 'Fields (Textarea)', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_forms_inputs_style' );

		$this->start_controls_tab(
			'tab_inputs_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474747',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ADADAD',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea::placeholder' => 'color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'show_field_placeholders' => 'yes'
				// ]
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_inputs_hover',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color_fc',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea:focus' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color_fc',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea:focus::placeholder' => 'color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'show_field_placeholders' => 'yes'
				// ]
			]
		);

		$this->add_control(
			'input_background_color_fc',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea:focus' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color_fc',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea:focus' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .comment-form-comment textarea',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'input_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .comment-form-comment textarea',
			]
		);

		$this->add_control(
			'input_border_type',
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
					'{{WRAPPER}} .comment-form-comment textarea' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_width',
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
					'{{WRAPPER}} .comment-form-comment textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'input_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'input_border_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' => esc_html__( 'Input Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 500,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'input_textarea_height',
			[
				'label' => esc_html__( 'Textarea (Message) Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea#comment' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_width',
			[
				'label' => esc_html__( 'Textarea Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 500,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'plugin_select!' => 'caldera'
				]
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 15,
					'bottom' => 0,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_radius',
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
					'{{WRAPPER}} .comment-form-comment textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_spacing',
			[
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles ====================
		// Section: Submit Button ----
		$this->start_controls_section(
			'section_style_submit_btn',
			[
				'label' => esc_html__( 'Submit Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'submit_btn_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					], //TODO:: remove later if not needed
				],
				// 'prefix_class' => 'wpr-forms-submit-',
				'selectors' => [
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}}',
				],
				'default' => 'left',
			]
		);

		$this->add_control(
			'submit_btn_align_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'tabs_submit_btn_style' );

		$this->start_controls_tab(
			'tab_submit_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		// $this->add_group_control(
		// 	Group_Control_Background::get_type(),
		// 	[
		// 		'name' => 'submit_btn_bg_color',
		// 		'label' => esc_html__( 'Background', 'wpr-addons' ),
		// 		'types' => [ 'classic', 'gradient' ],
		// 		'fields_options' => [
		// 			'color' => [
		// 				'default' => '#605BE5',
		// 			],
		// 		],
		// 		'selector' => '{{WRAPPER}} #respond .comment-form .form-submit input#submit'
		// 	]
		// );

		$this->add_control(
			'submit_btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} #respond .comment-form .form-submit input#submit',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submit_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'submit_btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}}  #respond .comment-form .form-submit input#submit:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		// $this->add_group_control(
		// 	Group_Control_Background::get_type(),
		// 	[
		// 		'name' => 'submit_btn_bg_color_hover',
		// 		'label' => esc_html__( 'Background', 'wpr-addons' ),
		// 		'types' => [ 'classic', 'gradient' ],
		// 		'fields_options' => [
		// 			'color' => [
		// 				'default' => '#4A45D2',
		// 			],
		// 		],
		// 		'selector' => '{{WRAPPER}}  #respond .comment-form .form-submit input#submit:hover'
		// 	]
		// );

		$this->add_control(
			'submit_btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} #respond .comment-form .form-submit input#submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'submit_btn_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'submit_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} #respond .comment-form .form-submit input#submit'
			]
		);

		$this->add_control(
			'submit_btn_border_type',
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
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_btn_border_width',
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
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'submit_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 12,
					'right' => 30,
					'bottom' => 12,
					'left' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'submit_btn_radius',
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
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'submit_btn_spacing',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
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
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} #respond .comment-form .form-submit input#submit' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_rating',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'rating_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars.selected a.active:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars:hover a:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars.selected a:not(.active):before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars.selected a.active:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars a:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_color_2',
			[
				'label' => esc_html__( 'Color (Locate)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs .star-rating span::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_color_3',
			[
				'label' => esc_html__( 'Color (Locate)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs .star-rating::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'selectors' => [
					'{{WRAPPER}} p.stars' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .comment-form-rating' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'panel_icons_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Your Rating Icons', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-wpr-product-tabs p.stars a::before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icons Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} p.stars a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// $this->add_control(
		// 	'panel_stats_style',
		// 	[
		// 		'type' => Controls_Manager::HEADING,
		// 		'label' => esc_html__( 'Stats', 'wpr-addons' ),
		// 		'separator' => 'before',
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'stats_size',
		// 	[
		// 		'label' => esc_html__( 'Stats Size', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 50,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 22,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.elementor-widget-wpr-product-tabs .wpr-individual-rating span' => 'font-size: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'average_rating_bar_width',
		// 	[
		// 		'label'      => esc_html__('Rating Bar Width', 'wpr-addons'),
		// 		'type'       => Controls_Manager::SLIDER,
		// 		'size_units' => ['px', '%'],
		// 		'range'      => [
		// 			'px' => [
		// 				'min'  => 0,
		// 				'max'  => 200,
		// 				'step' => 5,
		// 			],
		// 			'%'  => [
		// 				'min'  => 0,
		// 				'max'  => 100,
		// 				'step' => 5,
		// 			],
		// 		],
		// 		'default'    => [
		// 			'unit' => 'px',
		// 			'size' => 150,
		// 		],
		// 		'selectors'  => [
		// 			'{{WRAPPER}}.elementor-widget-wpr-product-tabs .wpr-individual-rating-cont' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'average_rating_bar_height',
		// 	[
		// 		'label'      => esc_html__('Rating Bar Height (px)', 'wpr-addons'),
		// 		'type'       => Controls_Manager::SLIDER,
		// 		'size_units' => ['px'],
		// 		'range'      => [
		// 			'%' => [
		// 				'min'  => 0,
		// 				'max'  => 200,
		// 				'step' => 5,
		// 			],
		// 		],
		// 		'default'    => [
		// 			'unit' => 'px',
		// 			'size' => 10,
		// 		],
		// 		'selectors'  => [
		// 			'{{WRAPPER}}.elementor-widget-wpr-product-tabs .wpr-individual-rating-cont' => 'height: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'rating_percentage_bar_gutter',
		// 	[
		// 		'type' => Controls_Manager::SLIDER,
		// 		'label' => esc_html__( 'Bar Gutter', 'wpr-addons' ),
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => -5,
		// 				'max' => 50,
		// 			]
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 0,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-individual-rating-cont' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}}',
		// 		],	
		// 	]
		// );

		$this->add_responsive_control(
			'rating_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
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
					'{{WRAPPER}} p.stars a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
    }

	public function previous_tag() {
		echo '<div class="wpr-woo-wrap woocommerce woocommerce-page">';
	}

	public function next_tag() {
		echo '</div>';
	}
	
	public function change_html($reviews_title, $count, $product) {

		$average = $product->get_average_rating();

		$rating_5 = $product->get_rating_count(5);
		$rating_4 = $product->get_rating_count(4);
		$rating_3 = $product->get_rating_count(3);
		$rating_2 = $product->get_rating_count(2);
		$rating_1 = $product->get_rating_count(1);
		$total = $rating_1 + $rating_2 + $rating_3 + $rating_4 + $rating_5;
		$pct5 = $pct4 = $pct3 = $pct2 = $pct1 = 0;

		if ($total > 0) {
			$pct5 = ceil($rating_5 * 100 / $total);
			$pct4 = ceil($rating_4 * 100 / $total);
			$pct3 = ceil($rating_3 * 100 / $total);
			$pct2 = ceil($rating_2 * 100 / $total);
			$pct1 = ceil($rating_1 * 100 / $total);
		}

		$details = '<div class="wpr-individual-rating"><span>' . esc_html__('5 star', 'wpr-addons') . '</span> <span class="wpr-individual-rating-cont"><span style="width: ' . $pct5 . '%"> </span></span> <span>' . $pct5 . '%</span></div><br/> ';
		$details .= '<div class="wpr-individual-rating"><span>' . esc_html__('4 star', 'wpr-addons') . '</span> <span class="wpr-individual-rating-cont"><span style="width: ' . $pct4 . '%"> </span></span> <span>' . $pct4 . '%</span></div><br/> ';
		$details .= '<div class="wpr-individual-rating"><span>' . esc_html__('3 star', 'wpr-addons') . '</span> <span class="wpr-individual-rating-cont"><span style="width: ' . $pct3 . '%"> </span></span> <span>' . $pct3 . '%</span></div><br/> ';
		$details .= '<div class="wpr-individual-rating"><span>' . esc_html__('2 star', 'wpr-addons') . '</span> <span class="wpr-individual-rating-cont"><span style="width: ' . $pct2 . '%"> </span></span> <span>' . $pct2 . '%</span></div><br/> ';
		$details .= '<div class="wpr-individual-rating"><span>' . esc_html__('1 star', 'wpr-addons') . '</span> <span class="wpr-individual-rating-cont"><span style="width: ' . $pct1 . '%"> </span></span> <span>' . $pct1 . '%</span></div><br/> ';


		$htm = '</h2>';
		$htm .= '<div class="wpr-rating-container">';

		$htm .= wc_get_rating_html($average, $count);
		
		$htm .= '</div><h2 class="woocommerce-Reviews-title">';

		return $htm . $reviews_title;
	}

    protected function render() {
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

		add_filter('woocommerce_reviews_title', [$this, 'change_html'], 99, 3);

		$this->previous_tag();
		// add_action('woocommerce_before_single_product_summary',function(){
		// 	printf('<div class="wpr-woo-wrap">');
		// });

        wc_get_template( 'single-product/tabs/tabs.php' );
		
		$this->next_tag();
		// add_action('woocommerce_after_single_product_summary',function(){
		// 	printf('</div>');
		// });

		// $in_editor_mode = \wpr-addons\Core\Template_Cpt::TYPE == get_post_type();

		// if($in_editor_mode) {

		// 	global $product, $post;

		// 	$main_post = clone $post;

		// 	$product = \wpr-addons\Widgets\Products::instance()->get_product($post_type);
		// 	$post = get_post($product->get_id());

		// 	add_filter('the_content', [\wpr-addons\Widgets\Products::instance(), 'product_tab_content_preview']);

		// 	$product_tabs = woocommerce_default_product_tabs();
		// }

        // On render widget from Editor - trigger the init manually.
        if ( wp_doing_ajax() ) {
            ?>
            <script>
                jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
				console.log('works');
            </script>
            <?php
        }
    }
}