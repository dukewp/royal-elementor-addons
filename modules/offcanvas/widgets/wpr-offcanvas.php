<?php
namespace WprAddons\Modules\Offcanvas\Widgets;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Offcanvas extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'wpr-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Offcanvas', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-sidebar';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'offcanvas', 'off canvas', 'sidebar' ];
	}

	public function get_style_depends() {
		return [ 'wpr-link-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-nav-menu-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_offcanvas_position() {
		$this->add_control(
            'offcanvas_position',
            [
                'label'        => esc_html__('Position', 'wpr-addons'), 
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'right',
				'render_type' => 'template',
                'options'      => [
                    'right' => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'pro-lf'  => [
                        'title' => esc_html__('Left (Pro)', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'pro-tp'   => [
                        'title' => esc_html__('Top (Pro)', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'pro-btm'  => [
                        'title' => esc_html__('Bottom (Pro)', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'pro-mdl'  => [
                        'title' => esc_html__('Middle (Pro)', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
				]
            ]
        );
	}

	public function add_responsive_control_offcanvas_box_width() {
		$this->add_responsive_control(
			'offcanvas_box_width',
			[
				'label' => sprintf( __( 'Width %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => 'wpr-pro-control',
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_position' => ['left', 'right', 'middle']
				]
			]
		);
	}

	public function add_responsive_control_offcanvas_box_height() {
		$this->add_responsive_control(
			'offcanvas_box_height',
			[
				'label' => sprintf( __( 'Height %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SLIDER,
				'classes' => 'wpr-pro-control',
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'vh',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_position' => ['top', 'bottom', 'middle']
				]
			]
		);
	}

	public function add_control_offcanvas_entrance_animation() {
		$this->add_control(
			'offcanvas_entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'pro-sl' => esc_html__( 'Slide (Pro)', 'wpr-addons' ),
					'pro-gr' => esc_html__( 'Grow (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-offcanvas-entrance-animation-'
			]
		);
	}

	public function add_control_offcanvas_entrance_type() {
		$this->add_control(
			'offcanvas_entrance_type',
			[
				'label' => esc_html__( 'Entrance Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'options' => [
					'cover' => esc_html__( 'Cover', 'wpr-addons' ),
					'pro-ps' => esc_html__( 'Push (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-offcanvas-entrance-type-',
				'default' => 'cover',
				'condition' => [
					'offcanvas_position' => ['top', 'left', 'right'],
					// 'offcanvas_entrance_animation' => ['slide', 'grow']
				]
			]
		);
	}

	public function add_control_offcanvas_open_by_default() {
		$this->add_control(
			'offcanvas_open_by_default',
			[
				'label' => sprintf( __( 'Open by Default %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance',
				'render_type' => 'template'
				// 'separator' => 'before',
			]
		);
	}

	public function add_control_offcanvas_reverse_header () {
		$this->add_control(
			'offcanvas_reverse_header',
			[
				'label' => sprintf( __( 'Reverse Header %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'classes' => 'wpr-pro-control',
			]
		);
	}

	public function add_control_offcanvas_button_icon() {
		$this->add_control(
			'offcanvas_button_icon',
			[
				'label' => sprintf( __( 'Select Icon %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::ICONS,
				'classes' => 'wpr-pro-control',
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-bars',
					'library' => 'fa-solid',
				]
			]
		);
	}

	public function wpr_offcanvas_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_offcanvas_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'offcanvas_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				// 'condition' => [
				// 	'offcanvas_content_type' => 'template',
				// ],
			]
		);

		$this->add_control(
			'offcanvas_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Offcanvas', 'wpr-addons' ),
			]
		);

		$this->add_control_offcanvas_position();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_position', ['pro-lf', 'pro-tp', 'pro-btm', 'pro-mdl'] );

		$this->add_responsive_control_offcanvas_box_width();

		$this->add_responsive_control_offcanvas_box_height();

		$this->add_control_offcanvas_entrance_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_animation', ['pro-sl', 'pro-gr'] );

		$this->add_control_offcanvas_entrance_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'offcanvas', 'offcanvas_entrance_type', ['pro-ps'] );

		$this->add_control_offcanvas_open_by_default();

		$this->add_control(
			'offcanvas_button_heading',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_button_title', 
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Click Here', 'wpr-addons' ),
			]
		);

		$this->add_control_offcanvas_button_icon();

		$this->add_responsive_control(
			'offcanvas_button_icon_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger i' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
            'offcanvas_button_alignment',
            [
                'label'        => esc_html__('Align', 'wpr-addons'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
				// 'separator' => 'before',
                'options'      => [
                    'left' => [
                        'title' => esc_html__('left', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-container' => 'text-align: {{VALUE}}',
				]
            ]
        );

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_offcanvas_button',
			[
				'label' => esc_html__( 'Offcanvas Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-offcanvas-trigger',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-offcanvas-trigger:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-offcanvas-trigger',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_padding',
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
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
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
					'{{WRAPPER}} .wpr-offcanvas-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();
		
		// Tab: Style ==============
		// Section: Header ------------
		$this->start_controls_section(
			'section_style_offcanvas_header',
			[
				'label' => esc_html__( 'Offcanvas Header', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control_offcanvas_reverse_header();

		$this->add_control(
			'offcanvas_close_icon_heading',
			[
				'label' => esc_html__( 'Close Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_close_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-offcanvas' => 'color: {{VALUE}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-close-offcanvas' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_close_icon_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-close-offcanvas i' => 'font-size: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'offcanvas_title_heading',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'offcanvas_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-title' => 'color: {{VALUE}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-title' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'offcanvas_title',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-offcanvas-title, .wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-title',
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Box ------------
		$this->start_controls_section(
			'section_style_offcanvas_box',
			[
				'label' => esc_html__( 'Offcanvas Wrapper', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'offcanvas_overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'offcanvas_overlay_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-wrap' => 'background-color: {{VALUE}};',
					'.wpr-offcanvas-wrap-{{ID}}' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_box_style',
			[
				'label' => esc_html__( 'Wrapper', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'offcanvas_box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'background-color: {{VALUE}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'offcanvas_box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'border-color: {{VALUE}}',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'offcanvas_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-offcanvas-content, .wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content',
			]
		);

		$this->add_control(
			'offcanvas_box_border_style',
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
					'{{WRAPPER}} .wpr-offcanvas-content' => 'border-style: {{VALUE}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'border-style: {{VALUE}};'
				]
			]
		);
	
		$this->add_responsive_control(
			'offcanvas_box_border_width',
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
					'{{WRAPPER}} .wpr-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' =>[
					'offcanvas_box_border_style!' => 'none',
				],
			]
		);
	
		$this->add_responsive_control(
				'offcanvas_box_border_radius',
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
						'{{WRAPPER}} .wpr-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'separator' => 'after',
				]
		);

		$this->add_responsive_control(
			'offcanvas_box_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['offcanvas_position'] = 'right';
			$settings['offcanvas_entrance_animation'] = 'fade';
		}

		$this->add_render_attribute(
			'offcanvas-wrapper',
			[
				'class' => [ 'wpr-offcanvas-container' ],
				'data-offcanvas-open' => $settings['offcanvas_open_by_default'],
			]
		);

		?>

		<div <?php echo $this->get_render_attribute_string( 'offcanvas-wrapper' ); ?>>
			<button class="wpr-offcanvas-trigger">
				<?php if ( !empty($settings['offcanvas_button_icon']) && wpr_fs()->can_use_premium_code() ) : 
					\Elementor\Icons_Manager::render_icon( $settings['offcanvas_button_icon'] );
				elseif ( ! wpr_fs()->can_use_premium_code() ) : ?>
					<i class="fas fa-bars"></i>
				<?php endif; ?>
				<?php if ( !empty($settings['offcanvas_button_title']) ) : ?>
					<span><?php echo esc_html($settings['offcanvas_button_title']) ?></span>
				<?php endif; ?>
			</button>

			<div class="wpr-offcanvas-wrap">
				<div class="wpr-offcanvas-content wpr-offcanvas-content-<?php echo $settings['offcanvas_position'] ?>">
					<div class="wpr-offcanvas-header">
						<span class="wpr-close-offcanvas">
							<i class="fa fa-times" aria-hidden="true"></i>
						</span>
						<?php if ( !empty($settings['offcanvas_title']) ) : ?>
							<span class="wpr-offcanvas-title"><?php echo esc_html($settings['offcanvas_title']) ?></span>
						<?php endif; ?>
					</div>
					<?php
						if ( !empty($settings['offcanvas_template']) ) {
							echo $this->wpr_offcanvas_template($settings['offcanvas_template']);
						} else {
							echo '<p>Please select a template!</p>';
						}
					?>
				</div>
			</div>
		</div>
        
    <?php }
}