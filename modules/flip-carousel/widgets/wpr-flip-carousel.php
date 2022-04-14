<?php
namespace WprAddons\Modules\FlipCarousel\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Flip_Carousel extends Widget_Base {
	
	public function get_name() {
		return 'wpr-flip-carousel';
	}

	public function get_title() {
		return esc_html__( 'Flip Carousel', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'flip carousel', 'flip', 'carousel', 'flip slider' ];
	}

	public function get_script_depends() {
		return [ 'wpr-flipster' ];
	}

	public function get_style_depends() {
		return [ 'wpr-flipster-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-advanced-slider-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {

		$this->start_controls_section(
			'section_flip_carousel',
			[
				'label' => esc_html__( 'Slides', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

        $repeater = new Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'slide_text',
			[
				'label' => esc_html__( 'Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Image Caption',
				'description' => 'Show/Hide Image Caption from Settings tab.'
				// 'condition' => [
				// 	'enable_figcaption' => 'yes'
				// ]
			]
		);
		
		$repeater->add_control(
			'enable_slide_link',
			[
				'label' => __( 'Enable Slide Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'wpr-addons' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'enable_slide_link' => 'yes'
				]
			]
		);
        
		$this->add_control(
			'carousel_elements',
			[
				'label' => esc_html__( 'Carousel Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => esc_html__('title'),
					],
					[
						'element_select' => esc_html__('title'),
					],
					[
						'element_select' => esc_html__('title'),
					],
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_flip_carousel_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'flip_carousel_image_size',
				'default' => 'medium_large',
				'exclude' => ['custom']
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-items-wrapper' => 'height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'spacing',
			[
				'label' => __( 'Gutter', 'wpr-addons' ),
				'description' => esc_html__('Change Image Size if gutter corrupts carousel layout', 'wpr-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => -0.6,
				'min' => -1,
				'max' => 1,
				'step' => 0.1,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'carousel_type',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'coverflow',
				'separator' => 'before',
				'options' => [
					'coverflow' => esc_html__( 'Cover Flow', 'wpr-addons' ),
					'carousel' => esc_html__( 'Carousel', 'wpr-addons' ),
					'flat' => esc_html__( 'Flat', 'wpr-addons' ),
					'wheel' => esc_html__( 'Wheel', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'starts_from_center',
			[
				'label' => __( 'Item Starts From Center', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay_milliseconds',
			[
				'label' => __( 'Autoplay Interval', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 500,
				'default' => 3000,
				'step' => 20,
				'condition' => [
					'autoplay' => 'yes'
				]
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_click',
			[
				'label' => __( 'Slide on Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_scroll',
			[
				'label' => __( 'Play on Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
		$this->add_control(
			'enable_navigation',
			[
				'label' => __( 'Show Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'prev_next_navigation',
			[
				'label' => esc_html__( 'Navigation Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
					'default' => esc_html__( 'Default', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-flip-navigation-',
				'condition' => [
					'enable_navigation' => 'yes'
				]
			]
		);

		$this->add_control(
			'flip_carousel_nav_icon',
			[
				'label' => esc_html__( 'Navigation Icon', 'wpr-addons' ),
				'type' => 'wpr-arrow-icons',
				'default' => 'fas fa-angle',
				'condition' => [
					'enable_navigation' => 'yes',
					'prev_next_navigation' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'pagination',
			[
				'label' => __( 'Show Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'default' => 'yes'
			]
		);
		
		$this->add_control(
			'pagination_position',
			[
				'label' => esc_html__( 'Pagination Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'wpr-addons' ),
					'after' => esc_html__( 'Below Image', 'wpr-addons' )
				],
				'render_type' => 'template',
				'prefix_class' => 'wpr-flip-pagination-',
				'condition' => [
					'pagination' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'enable_figcaption',
			[
				'label' => __( 'Show Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'flipcaption_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'wpr-addons' ),
					'after' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->end_controls_section();
		
        $this->start_controls_section(
			'section_flip_carousel_navigation_styles',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_navigation' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_navigation'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#40CBB5',
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-flip-navigation-custom .flipster__button svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-flip-navigation-default .flipster__button svg' => 'stroke: {{VALUE}}'
				]
			]
		);
		
		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#40CBB5',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'border-color: {{VALUE}}',
				]
			]
		);


		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__button',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'navigation_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__button:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-flip-navigation-default .flipster__button:hover svg' => 'stroke: {{VALUE}}'
				],
			]
		);
		
		$this->add_control(
			'navigation_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'navigation_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0AA79A',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation_hover',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .flipster__button i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__button svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'prev_next_navigation' => ['default', 'custom']
				]
			]
		);
		
		$this->add_responsive_control(
			'icon_bg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',	
				],
				'condition' => [
					'prev_next_navigation' => ['default', 'custom']
				],
			]
		);

		$this->add_control(
			'border',
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
					'{{WRAPPER}} button.flipster__button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'icon_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'border!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'prev_next_navigation' => ['default', 'custom']
				]
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_pagination_styles',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_pagination'
		);

		$this->start_controls_tab(
			'pagination_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0AA79A',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item',
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'pagination_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'pagination_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination_hover',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .flipster__nav__link',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'pagination_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);
		
		$this->add_responsive_control(
			'pagination_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__nav__link::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'pagination' => ['yes']
				]
			]
		);

		$this->add_responsive_control(
			'pagination_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-carousel .flipster__nav__item' => 'margin: 0 {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-flip-pagination-after .wpr-flip-carousel .flipster__nav' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-flip-pagination-before .wpr-flip-carousel .flipster__nav' => 'margin-bottom	: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'pagination_border',
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
					'{{WRAPPER}} .flipster__nav__item' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		 
		$this->add_control(
			'pagination_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'pagination_border!' => 'none'
				],
			]
		);
		
		$this->add_control(
			'pagination_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'prev_next_navigation' => ['default', 'custom']
				],
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_caption_styles',
			[
				'label' => esc_html__( 'Caption', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'caption_style_tabs' );

		$this->start_controls_tab(
			'caption_style_tabs_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
			
		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'color: {{VALUE}}',
				],
			]
		);
			
		$this->add_control(
			'caption_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'caption_style_tabs_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
			
		$this->add_control(
			'caption_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipcaption:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr_caption',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography_caption',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .flipcaption',
			]
		);

		$this->add_control(
			'caption_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => '-webkit-transition: all {{VALUE}}s ease !important; transition: all {{VALUE}}s ease !important;',
				]
			]
		);

		$this->add_responsive_control(
			'flipcaption_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'size_units' => [ 'px', 'vw', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'width: {{SIZE}}{{UNIT}}; margin: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'flipcaption_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .flipcaption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'flipcaption_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'separator' => 'before',
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
					'{{WRAPPER}} .flipcaption' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();
    }

	public function flip_carousel_attributes($settings) {

		$icon_prev = '<span class="wpr-flip-carousel-navigation">'. Utilities::get_wpr_icon( $settings['flip_carousel_nav_icon'], 'left' ) .'</span>';

		$icon_next = '<span class="wpr-flip-carousel-navigation">'. Utilities::get_wpr_icon( $settings['flip_carousel_nav_icon'], 'right' ) .'</span>';

		$attributes = [
			'starts_from_center' => $settings['starts_from_center'],
			'carousel_type' => $settings['carousel_type'],
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			'autoplay_milliseconds' => $settings['autoplay_milliseconds'],
			'pause_on_hover' => $settings['pause_on_hover'],
			'play_on_click' => $settings['play_on_click'],
			'play_on_scroll' => $settings['play_on_scroll'],
			'pagination_position' => $settings['pagination_position'],
			'spacing' => $settings['spacing'],
			'enable_navigation' => $settings['enable_navigation'],
			'prev_next_navigation' => $settings['prev_next_navigation'],
			'button_prev' => $icon_prev,
			'button_next' => $icon_next,
			'pagination_bg_color_hover' => $settings['pagination_bg_color_hover']
		];

		return json_encode($attributes);
	}

    protected function render() {
		$settings = $this->get_settings_for_display();
		
        if ( $settings['carousel_elements'] ) {
			$i = 0;
			echo '<div class="wpr-flip-carousel-wrapper">';
            echo '<div class="wpr-flip-carousel" data-settings="'. esc_attr($this->flip_carousel_attributes($settings)) .'">';
            echo '<ul class="wpr-flip-items-wrapper">';
            foreach ( $settings['carousel_elements'] as $element ) {
				if ( ! empty( $element['slide_link']['url'] ) ) {
					$this->add_link_attributes( 'slide_link'.$i, $element['slide_link'] );
				}
				// $flip_slide_image = Group_Control_Image_Size::get_attachment_image_src( $element['image']['id'], 'flip_carousel_image_size', $settings );
				$flip_slide_image = Utils::get_placeholder_image_src() === $element['image']['url'] ? '<img src='. Utils::get_placeholder_image_src() .' />' : '<img src="'.  Group_Control_Image_Size::get_attachment_image_src( $element['image']['id'], 'flip_carousel_image_size', $settings ) .'" />';

				$figcaption = 'yes' === $settings['enable_figcaption'] ? '<figcaption class="flipcaption"><span style="width: 100%;">'. $element['slide_text'] .'</span></figcaption>' : '';

				$inner_figure = 'after' === $settings['flipcaption_position']
						? ''. $flip_slide_image . $figcaption .''
						: ''. $figcaption . $flip_slide_image .'';

				$figure = 'yes' === $element['enable_slide_link']
						? '<a '. $this->get_render_attribute_string( 'slide_link'.$i ) .'>' . $inner_figure . '</a>'
						: $inner_figure;

                echo '<li class="wpr-flip-item" data-flip-title="">';
					echo '<figure>';
						echo $figure;
					echo '</figure>';
				echo '</li>';
				$i++;
            }
            echo '</ul>';
            echo '</div>';
			echo '</div>';
        }
    }
}