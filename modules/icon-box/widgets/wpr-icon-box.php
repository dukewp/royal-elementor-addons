<?php
namespace WprAddons\Modules\IconBox\Widgets;

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
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Icon_Box extends Widget_Base {

    public function get_name() {
        return 'wpr-icon-box';
    }

    public function get_title() {
        return esc_html__( 'Icon Box', 'wpr-addons' );
    }

    public function get_icon() {
        return 'wpr-icon eicon-icon-box';
    }

    public function get_categories() {
        return [ 'wpr-widgets'];
    }

    public function get_keywords() {
        return [ 'royal', 'icon box' ];
    }

    public function get_custom_help_url() {
        if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
            return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {

        // Tab: Content ==============
        // Section: General ----------
        $this->start_controls_section(
            'section_general_settings',
            [
                'label' => esc_html__( 'General', 'wpr-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

        $this->add_control(
            'icon_box_media_type',
            [
                'label'       => esc_html__( 'Media Type', 'wpr-addons' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'icon' => esc_html__( 'Icon', 'wpr-addons' ),
                    'image' => esc_html__( 'Image', 'wpr-addons' )
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

		$this->add_control(
			'icon_box_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'label_block' => false,
                'skin' => 'inline',
				'condition' => [
					'icon_box_media_type' => 'icon'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
                'condition' => [
                    'icon_box_media_type' => 'image'
                ]
			]
		);

		$this->add_control(
			'icon_box_image',
			[
				'label' => esc_html__( 'Choose Image', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'skin' => 'inline',
				'condition' => [
					'icon_box_media_type' => 'image'
				]
			]
		);

		$this->add_control(
			'icon_box_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h2',
                'separator' => 'before'
			]
		);

		$this->add_control(
			'icon_box_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title' , 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_box_title_url',
			[
				'label' => esc_html__( 'Title Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon_box_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => esc_html__( 'Content', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Type your description here', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_box_link_type',
			[
				'label' => esc_html__( 'Link Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'title' => esc_html__( 'Title', 'wpr-addons' ),
					'btn' => esc_html__( 'Button', 'wpr-addons' ),
					'box' => esc_html__( 'Box', 'wpr-addons' ),
				],
				'default' => 'btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_box_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Click here',
				'separator' => 'before',
				'condition' => [
					'icon_box_link_type' => ['btn'],
				],
			]
		);

		$this->add_control(
			'icon_box_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon_box_link_type' => ['btn'],
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_alignment',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
                'prefix_class' => 'wpr-icon-box-',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_box_alignment_v',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box ' => 'align-items: {{VALUE}}',
				],
				'separator' => 'before',
                'condition' => [
                    'icon_box_alignment!' => 'center'
                ]
			]
		);

		$this->add_responsive_control(
			'icon_box_text_align',
			[
				'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-content-wrap' => 'text-align: {{VALUE}}',
					'{{WRAPPER}}.wpr-icon-box-center .wpr-icon-box-media-wrap' => 'width: 100%; text-align: {{VALUE}}',
				]
			]
		);

        $this->add_responsive_control(
			'icon_box_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-icon-box-left .wpr-icon-box-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-icon-box-right .wpr-icon-box-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-icon-box-center .wpr-icon-box-media-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				]
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
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
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'icon-box', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-icon-box-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'icon-box', 'overlay_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// $this->add_control_overlay_animation_divider();

		// $this->add_control_overlay_image();

		// $this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

        // Tab: Content ==============
        // Section: Badge ----------
        $this->start_controls_section(
			'section_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'badge_style',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'default' => 'corner',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'corner' => esc_html__( 'Corner Badge', 'wpr-addons' ),
					'cyrcle' => esc_html__( 'Cyrcle Badge', 'wpr-addons' ),
					'flag' => esc_html__( 'Flag Badge', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'badge_title',
			[
				'label' => esc_html__( ' Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Hot',
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'badge_title_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_size',
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
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-badge-cyrcle .wpr-icon-box-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-badge-corner .wpr-icon-box-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
					'{{WRAPPER}} .wpr-icon-box-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style!' => [ 'none', 'cyrcle' ],
				],	
			
			]
		);

		$this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'separator' => 'before',
                'condition' => [
					'badge_style!' => 'none',
				],
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Tab: STYLE ==============
		// Section: Icon ----------
		$this->start_controls_section(
			'section_icon_box_icon_styles',
			[
				'label' => esc_html__( 'Media', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_box_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_box_icon_wrapper_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A65FF',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_box_icon_wrapper_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-media-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-icon-box-media-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_box_icon_wrapper_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 75,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'icon_box_media_type' => 'icon'
                ]
			]
		);

		$this->add_control(
			'icon_box_icon_wrapper_border_type',
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
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_box_icon_wrapper_border_width',
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
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_box_icon_wrapper_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'icon_box_icon_wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => 'rgba(0, 0, 0, 0.25)',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-icon-box-media-hover-bg'
			]
		);

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpr-addons' ),
					'multiply' => esc_html__( 'Multiply', 'wpr-addons' ),
					'screen' => esc_html__( 'Screen', 'wpr-addons' ),
					'overlay' => esc_html__( 'Overlay', 'wpr-addons' ),
					'darken' => esc_html__( 'Darken', 'wpr-addons' ),
					'lighten' => esc_html__( 'Lighten', 'wpr-addons' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'wpr-addons' ),
					'color-burn' => esc_html__( 'Color-burn', 'wpr-addons' ),
					'hard-light' => esc_html__( 'Hard-light', 'wpr-addons' ),
					'soft-light' => esc_html__( 'Soft-light', 'wpr-addons' ),
					'difference' => esc_html__( 'Difference', 'wpr-addons' ),
					'exclusion' => esc_html__( 'Exclusion', 'wpr-addons' ),
					'hue' => esc_html__( 'Hue', 'wpr-addons' ),
					'saturation' => esc_html__( 'Saturation', 'wpr-addons' ),
					'color' => esc_html__( 'Color', 'wpr-addons' ),
					'luminosity' => esc_html__( 'luminosity', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'overlay_border_type',
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
					'{{WRAPPER}} .wpr-icon-box-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_border_width',
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
					'{{WRAPPER}} .wpr-icon-box-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_radius',
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
					'{{WRAPPER}} .wpr-icon-box-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Title & Description ----------
		$this->start_controls_section(
			'section_icon_box_title_&_description_styles',
			[
				'label' => esc_html__( 'Title & Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'icon_box_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-icon-box-title a.wpr-icon-box-url' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_box_title',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-icon-box-title',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '20',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'icon_box_description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6E6B6B',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_box_description',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-icon-box-description',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->end_controls_section();

        // Tab: Style ==============
        // Section: Button ----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon_box_link_type' => [ 'btn' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-icon-box-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-icon-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-icon-box:hover .wpr-icon-box-btn',
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box:hover .wpr-icon-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box:hover .wpr-icon-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-icon-box:hover .wpr-icon-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-icon-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 17,
					'bottom' => 8,
					'left' => 17,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-icon-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}}  .wpr-icon-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
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
					'{{WRAPPER}} .wpr-icon-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

        // Tab: Style ==============
        // Section: Badge ----------
        $this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-badge-inner' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#e83d17',
				'selectors' => [
					'{{WRAPPER}} .wpr-icon-box-badge-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-icon-box-badge-flag:before' => ' border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-icon-box-badge-inner'
			]
		);

		$this->add_control(
			'badge_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-icon-box-badge-inner'
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
				'{{WRAPPER}} .wpr-icon-box-badge .wpr-icon-box-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public function render_element_badge() {
		$settings = $this->get_settings();

		if ( $settings['badge_style'] !== 'none' && ! empty( $settings['badge_title'] ) ) :

			$this->add_render_attribute( 'wpr-icon-box-badge-attr', 'class', 'wpr-icon-box-badge wpr-icon-box-badge-'. $settings[ 'badge_style'] );
			if ( ! empty( $settings['badge_hr_position'] ) ) :
				$this->add_render_attribute( 'wpr-icon-box-badge-attr', 'class', 'wpr-icon-box-badge-'. $settings['badge_hr_position'] );
			endif; ?>

			<div <?php echo $this->get_render_attribute_string( 'wpr-icon-box-badge-attr' ); ?>>
				<div class="wpr-icon-box-badge-inner"><?php echo $settings['badge_title']; ?></div>
			</div>
		<?php endif;
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' wpr-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' wpr-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' wpr-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' wpr-anim-transparency';
			}
		}

		return $class;
	}

	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo '<div class="wpr-icon-box-media-hover-bg '. $this->get_animation_class( $settings, 'overlay' ) .'" data-url="'. esc_url($settings['icon_box_title_url']['url']) .'">';

			// if ( wpr_fs()->can_use_premium_code() ) {
			// 	if ( '' !== $settings['overlay_image']['url'] ) {
			// 		echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
			// 	}
			// }

		echo '</div>';
	}

    protected function render() {
        // Get Settings
        $settings = $this->get_settings_for_display();

        $this->add_link_attributes( 'icon_box_url', $settings['icon_box_title_url'] );

		$this->add_render_attribute( 'btn_attribute', 'class', 'wpr-icon-box-btn-wrap' );
		// if ( 'none' !== $settings['btn_animation'] ) {
		// 	$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' wpr-anim-transparency' : '';
		// 	$this->add_render_attribute( 'btn_attribute', 'class', 'wpr-anim-transparency wpr-anim-size-medium wpr-element-'. $settings['btn_animation'] .' wpr-anim-timing-'. $settings['btn_animation_timing'] .' wpr-anim-size-'. $settings['btn_animation_size']. $anim_transparency );	
		// }

            echo '<div class="wpr-icon-box-wrap">';
                echo '<div class="wpr-icon-box">';

                    echo '<div class="wpr-icon-box-media-wrap">';
                        echo '<div class="wpr-icon-box-icon-inner-wrap">';

                            if ( 'icon' === $settings['icon_box_media_type'] ) {
                                \Elementor\Icons_Manager::render_icon( $settings['icon_box_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                $src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['icon_box_image']['id'], 'thumbnail', $settings );
                                echo '<img src="'. $src .'">';
                            }

                        echo '</div>';
                    echo '</div>';

                    echo '<div class="wpr-icon-box-content-wrap">';

                        if (!empty($settings['icon_box_title'])) {
                            if ( empty($settings['icon_box_title_url']) ) {
                                echo '<'. esc_html($settings['icon_box_title_tag']) .' class="wpr-icon-box-title">' . wp_kses_post($settings['icon_box_title']) . '</'. esc_html($settings['icon_box_title_tag']) .'>';
                            } else {
                                echo '<'. esc_html($settings['icon_box_title_tag']) .' class="wpr-icon-box-title"><a class="wpr-icon-box-url" '. $this->get_render_attribute_string( 'icon_box_url' ) .'>' . $settings['icon_box_title'] . '</a></'. esc_html($settings['icon_box_title_tag']) .'>';
                            }
                        }

                        if (!empty($settings['icon_box_content'])) {
                            echo '<p class="wpr-icon-box-description">' . wp_kses_post($settings['icon_box_content']) . '</p>';
                        }

                        if ( 'btn' === $settings['icon_box_link_type'] ) :
                            echo '<div '. $this->get_render_attribute_string( 'btn_attribute' ) . '>';
                                echo '<a class="wpr-icon-box-btn"'. $this->get_render_attribute_string( 'icon_box_url' ) .'>';
        
                                    if ( '' !== $settings['icon_box_btn_text'] ) :
                                    echo '<span class="wpr-icon-box-btn-text">'. $settings['icon_box_btn_text'] .'</span>';		
                                    endif;
        
                                    if ( '' !== $settings['icon_box_btn_icon']['value'] ) :
                                    echo '<span class="wpr-icon-box-btn-icon">';
                                        echo '<i class="'. esc_attr( $settings['icon_box_btn_icon']['value'] ) .'"></i>';
                                    echo '</span>';
                                    endif;
                                echo '</a>';
                            echo '</div>';
                        endif;
                        
                    echo '</div>';
					
                
				// Media Hover
				echo '<div class="wpr-icon-box-media-hover wpr-animation-wrap">';
					$this->render_media_overlay($settings);
                echo '</div>';

			echo '</div>';
                $this->render_element_badge();
            echo '</div>';
    }
}