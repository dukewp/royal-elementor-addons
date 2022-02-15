<?php
namespace WprAddons\Modules\ThemeBuilder\PostNavigation\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Post_Navigation extends Widget_Base {
	
	public function get_name() {
		return 'wpr-post-navigation';
	}

	public function get_title() {
		return esc_html__( 'Post Navigation', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-navigation';
	}

	public function get_categories() {
		return [ 'wpr-theme-builder-widgets' ];
	}

	public function get_keywords() {
		return [ 'navigation', 'arrows', 'pagination' ];
	}

	protected function _register_controls() {

		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_navigation',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_nav_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fixed',
				'options' => [
					'fixed-default' => esc_html__( 'Fixed Default', 'wpr-addons' ),
					'fixed' => esc_html__( 'Fixed Left/Right', 'wpr-addons' ),
					'static' => esc_html__( 'Static Left/Right', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
            'post_nav_fixed_default_align',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
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
				'selectors_dictionary' => [
					'left' => 'left: 0;',
					'center' => 'left: 50%;-webkit-transform: translateX(-50%);transform: translateX(-50%);',
					'right' => 'right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-fixed-default-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'post_nav_layout' => 'fixed-default',
				]
            ]
        );

		$this->add_responsive_control(
			'post_nav_fixed_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-navigation' => 'top: {{SIZE}}%;',
				],
				'condition' => [
					'post_nav_layout' => 'fixed',
				],
			]
		);

		$this->add_control(
			'post_nav_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'post_nav_arrows_loc',
			[
				'label' => esc_html__( 'Arrows Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'separate',
				'options' => [
					'separate' => esc_html__( 'Separate', 'wpr-addons' ),
					'label' => esc_html__( 'Next to Label', 'wpr-addons' ),
					'title' => esc_html__( 'Next to Title', 'wpr-addons' ),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_arrows',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '!=',
							'value' => 'fixed',
						],
					],
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'post_nav_arrows' => 'yes',
				]
			]
		);

		$this->add_control(
			'post_nav_labels',
			[
				'label' => esc_html__( 'Show Labels', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_prev_text',
			[
				'label' => esc_html__( 'Previous Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Previous Posts',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_next_text',
			[
				'label' => esc_html__( 'Next Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Next Post',
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->add_control(
			'post_nav_image',
			[
				'label' => esc_html__( 'Show Post Thumbnail', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'post_nav_image_bg',
			[
				'label' => esc_html__( 'Set as Background Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_image',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '!=',
							'value' => 'fixed',
						],
					],
				],
			]
		);

		$this->add_control(
			'post_nav_image_hover',
			[
				'label' => esc_html__( 'Show Image on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_image',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '==',
							'value' => 'fixed',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_nav_image_width_crop',
				'default' => 'medium',
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_layout!' => 'fixed'
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_image_width',
			[
				'label' => __( 'Image Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 140,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation img' => 'width: {{SIZE}}px;',
				],
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_layout!' => 'fixed'
				],
			]
		);

		$this->add_control(
			'post_nav_back',
			[
				'label' => esc_html__( 'Show Back Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_back_link',
			[
				'label' => esc_html__( 'Back Button Link', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'post_nav_back' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_dividers',
			[
				'label' => esc_html__( 'Show Dividers', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static'
				],
			]
		);

		$post_taxonomies['all'] = esc_html__( 'All', 'wpr-addons' );

		$this->add_control(
			'post_nav_query',
			[
				'label' => esc_html__( 'Navigate Through', 'wpr-addons' ),
				'description' => esc_html__( 'If you select a taxonomy, Next and Previous posts will be in the same toxonomy term as the current post.', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_reverse($post_taxonomies),
				'default' => 'all',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->add_control(
			'post_nav_background',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_divider_color',
			[
				'label'  => esc_html__( 'Divider Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-nav-divider' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_popup_close_btn_style' );

		$this->start_controls_tab(
			'tab_post_nav_overlay_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_nav_background_filters',
				'selector' => '{{WRAPPER}} .wpr-post-nav-overlay',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_nav_overlay_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'post_nav_overlay_color_hover',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation:hover .wpr-post-nav-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_nav_background_filters_hover',
				'selector' => '{{WRAPPER}} .wpr-post-navigation:hover .wpr-post-nav-overlay',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-divider' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation-wrap' => 'border-width: {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}} 0;',
				],
				'separator' => 'before',
				'condition' => [
					'post_nav_layout' => 'static',
					'post_nav_dividers' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'post_nav_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-post-navigation-wrap > div' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'post_nav_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation a' => 'align-items: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap.wpr-post-nav-dividers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-bg-images .wpr-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Arrows -----------
		$this->start_controls_section(
			'section_style_post_nav_arrow',
			[
				'label' => esc_html__( 'Arrows', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_arrows' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_arrow_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-navigation svg path' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_arrow_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_arrow_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_arrow_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'transition: color {{VALUE}}s, background-color {{VALUE}}s, border-color {{VALUE}}s',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-hover img' => 'transition: all {{VALUE}}s ease',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation i' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-prev img' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-nav-next img' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-navigation i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-navigation img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_nav_arrow_border_type',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_nav_arrow_border_width',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-posts-navigation-svg-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_arrow_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_arrow_margin',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_arrow_radius',
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
					'{{WRAPPER}} .wpr-post-navigation i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Back Button ------
		$this->start_controls_section(
			'section_style_post_nav_back_btn',
			[
				'label' => esc_html__( 'Back Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_back' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_back_btn_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_back_btn_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_back_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_fill_color',
			[
				'label'  => esc_html__( 'Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_back_btn_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_back_btn_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_fill_color_ht',
			[
				'label'  => esc_html__( 'Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a:hover span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_back_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'transition: background-color {{VALUE}}s, color {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_back_btn_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-back span' => 'width: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px); height: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'post_nav_back_btn_border_width',
			[
				'label' => esc_html__( 'Box Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_gutter',
			[
				'label' => esc_html__( 'Box Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_post_nav_label',
			[
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_labels' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_label_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_label_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_label_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_label_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_label_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_label_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels span' => 'transition: color {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_nav_label_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-post-nav-labels span'
			]
		);

		$this->add_responsive_control(
			'post_nav_label_margin',
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
					'{{WRAPPER}} .wpr-post-nav-labels span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_post_nav_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_title' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_title_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_title_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_title_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5' => 'transition: color {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_nav_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-post-nav-labels h5'
			]
		);

		$this->add_responsive_control(
			'post_nav_title_margin',
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
					'{{WRAPPER}} .wpr-post-nav-labels h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

	}

	// Arrow Icon
	public function render_arrow_by_location( $settings, $location, $dir ) {
		if ( 'fixed' === $settings['post_nav_layout'] ) {
			$settings['post_nav_arrows_loc'] = 'separate';
		}

		if ( 'yes' === $settings['post_nav_arrows'] && $location === $settings['post_nav_arrows_loc'] && false !== strpos( $settings['post_nav_arrow_icon'], 'svg-' ) ) {
			echo  '<div class="wpr-posts-navigation-svg-wrapper">' . Utilities::get_wpr_icon( $settings['post_nav_arrow_icon'], $dir ) . '</div>';
		} else if ( 'yes' === $settings['post_nav_arrows'] && $location === $settings['post_nav_arrows_loc'] && false == strpos( $settings['post_nav_arrow_icon'], 'svg-' ) ) {
			echo  Utilities::get_wpr_icon( $settings['post_nav_arrow_icon'], $dir );
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Previous and Next Posts
		if ( 'all' === $settings['post_nav_query'] ) {
			$prev_post = get_adjacent_post( false, '', true );
			$next_post = get_adjacent_post( false, '', false );
		} else {
			$prev_post = get_adjacent_post( true, '', true, $settings['post_nav_query'] );
			$next_post = get_adjacent_post( true, '', false, $settings['post_nav_query'] );
		}

		// Layout Class
		$layout_class = 'wpr-post-navigation wpr-post-nav-'. $settings['post_nav_layout'];

		// Show Image on Hover
		if ( 'yes' === $settings['post_nav_image_hover'] ) {
			$layout_class .= ' wpr-post-nav-hover';
		}

		$prev_image_url = '';
		$next_image_url = '';
		$prev_post_bg = '';
		$next_post_bg = '';

		// Image URLs
		if ( ! empty($prev_post) ) {
			$prev_img_id = get_post_thumbnail_id( $prev_post->ID );
			$prev_image_url = Group_Control_Image_Size::get_attachment_image_src( $prev_img_id, 'post_nav_image_width_crop', $settings );
		}
		if ( ! empty($next_post) ) {
			$next_img_id = get_post_thumbnail_id( $next_post->ID );
			$next_image_url = Group_Control_Image_Size::get_attachment_image_src( $next_img_id, 'post_nav_image_width_crop', $settings );
		}

		// Background Images
		if ( 'yes' === $settings['post_nav_image'] && 'yes' === $settings['post_nav_image_bg'] ) {
			if ( 'fixed' !== $settings['post_nav_layout'] ) {
				if ( ! empty($prev_post) ) {
					$prev_post_bg = ' style="background-image: url('. $prev_image_url .')"';
				}

				if ( ! empty($next_post) ) {
					$next_post_bg = ' style="background-image: url('. $next_image_url .')"';
				}
			}
		}

		// Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			// Layout Class
			$wrapper_class = 'wpr-post-nav-'. $settings['post_nav_layout'] .'-wrap';

			// Dividers
			if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] ) {
				$wrapper_class .= ' wpr-post-nav-dividers';
			}

			// Background Images
			if ( 'yes' === $settings['post_nav_image_bg'] ) {
				$wrapper_class .= ' wpr-post-nav-bg-images';
			}

			echo '<div class="wpr-post-navigation-wrap elementor-clearfix '. $wrapper_class .'">';
		}

		// Previous Post
		echo '<div class="wpr-post-nav-prev '. $layout_class .'"'. $prev_post_bg .'>';
			if ( ! empty($prev_post) ) {
				echo '<a href="'. esc_url( get_permalink($prev_post->ID) ) .'" class="elementor-clearfix">';
					// Left Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'left' );

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $prev_image_url ) .'" alt="">';
						}
					}

					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="wpr-post-nav-labels">';
							// Prev Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									$this->render_arrow_by_location( $settings, 'label', 'left' );
									echo esc_html( $settings['post_nav_prev_text'] );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									$this->render_arrow_by_location( $settings, 'title', 'left' );
									echo esc_html( get_the_title($prev_post->ID) );
								echo '</h5>';
							}
						echo '</div>';
					}
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="wpr-post-nav-overlay"></div>';
				}
			}
		echo '</div>';

		// Back to Posts
		if ( 'fixed' !== $settings['post_nav_layout'] && 'yes' === $settings['post_nav_back'] ) {
			echo '<div class="wpr-post-nav-back">';
				echo '<a href="'. esc_url($settings['post_nav_back_link'] ) .'">';
					echo '<span></span>';
					echo '<span></span>';
					echo '<span></span>';
					echo '<span></span>';
				echo '</a>';
			echo '</div>';
		}

		// Middle Divider
		if ( 'static' === $settings['post_nav_layout'] && 'yes' === $settings['post_nav_dividers'] && '' === $settings['post_nav_back'] ) {
			echo '<div class="wpr-post-nav-divider"></div>';
		}

		// Next Post
		echo '<div class="wpr-post-nav-next '. $layout_class .'"'. $next_post_bg .'>';
			if ( ! empty($next_post) ) {
				echo '<a href="'. esc_url( get_permalink($next_post->ID) ) .'" class="elementor-clearfix">';
					// Label & Title
					if ( 'fixed' !== $settings['post_nav_layout'] ) {
						echo '<div class="wpr-post-nav-labels">';
							// Next Label
							if ( 'yes' === $settings['post_nav_labels'] ) {
								echo '<span>';
									echo esc_html( $settings['post_nav_next_text'] );
									$this->render_arrow_by_location( $settings, 'label', 'right' );
								echo '</span>';
							}

							// Post Title
							if ( 'yes' === $settings['post_nav_title'] ) {
								echo '<h5>';
									echo esc_html( get_the_title($next_post->ID) );
									$this->render_arrow_by_location( $settings, 'title', 'right' );
								echo '</h5>';
							}
						echo '</div>';
					}

					// Post Thumbnail
					if ( 'yes' === $settings['post_nav_image'] ) {
						if ( '' === $settings['post_nav_image_bg'] || 'fixed' === $settings['post_nav_layout'] ) {
							echo '<img src="'. esc_url( $next_image_url ) .'" alt="">';
						}
					}

					// Right Arrow
					$this->render_arrow_by_location( $settings, 'separate', 'right' );
				echo '</a>';

				// Image Overlay
				if ( 'yes' === $settings['post_nav_image_bg'] ) {
					echo '<div class="wpr-post-nav-overlay"></div>';
				}
			}
		echo '</div>';

		// End Navigation Wrapper
		if ( 'fixed' !== $settings['post_nav_layout'] ) {
			echo '</div>';
		}

	}
	
}