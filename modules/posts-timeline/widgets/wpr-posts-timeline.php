<?php
namespace WprAddons\Modules\PostsTimeline\Widgets;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;

class Wpr_PostsTimeline extends Widget_Base {
	
	public function get_name() {
		return 'wpr-posts-timeline';
	}

	public function get_title() {
		return esc_html__( 'Posts Timeline', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-time-line';
	}

	public function get_categories() {
		return [ 'wpr-widgets' ];
	}

	public function get_keywords() {
		return ['Post Timeline', 'Post', 'Posts', 'Timeline', 'Posts Timeline', 'Story Timeline', 'Content Timeline'];
	}

	public function get_script_depends() {
		// TODO: separate infinite-scroll from isotope
		return [ 'swiper', 'wpr-aos-js', 'wpr-isotope' ];
	}

	public function get_style_depends() {
		return [ 'swiper', 'wpr-animations-css', 'wpr-loading-animations-css', 'wpr-aos-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    }

	public function wpr_aos_amination_array(){

		$animations = [
			"none" =>"None",
			  "fade" =>"Fade",
			  "fade-up" =>"Fade Up",
			  "fade-down" =>"Fade Down",
			  "fade-left" =>"Fade Left",
			  "fade-right" =>"Fade Right",
			  "fade-up-right" =>"Fade Up Right",
			  "fade-up-left" =>"Fade Up Left",
			  "fade-down-right" =>"Fade Down Right",
			  "fade-down-left" =>"Fade Down Left",
			  "flip-up" =>"Flip Up",
			  "flip-down" =>"Flip Down",
			  "flip-right" =>"Flip right",
			  "flip-left" =>"Flip Left",
			  "slide-up" =>"Slide Up",
			  "slide-left" =>"Slide Left",
			  "slide-right" =>"Slide Right",
			  "slide-down" =>"Slide Down",
			  "zoom-in" =>"Zoom In",
			  "zoom-out" =>"Zoom Out",
			  "zoom-in-up" =>"Zoom In Up",
			  "zoom-in-down" =>"Zoom In Down",
			  "zoom-in-left" =>"Zoom In Left",
			  "zoom-in-right" =>"Zoom In Right",
			  "zoom-out-up" =>"Zoom Out Up",
			  "zoom-out-down" =>"Zoom Out Down",
			  "zoom-out-left" =>"Zoom Out Left",
			  "zoom-out-right" =>"Zoom Out Right"
	   ];
	
		return $animations;
	
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'Layout', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
	
		$this->add_control(
			'timeline_content',
			[
				'label' => __( 'Timeline Content', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options'=>[
					'dynamic' => esc_html__('Dynamic', 'wpr-addons'),
					'custom' => esc_html__('Custom', 'wpr-addons'),
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'timeline_layout',
			[
				'label' => __( 'Layout', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'centered',
				'options'=>[
					'centered'=> esc_html__('Zig-Zag'),
					'one-sided'=> esc_html__('Line Left'),
					'one-sided-left'=> esc_html__('Line Right'),
					'horizontal-bottom'=> esc_html__('Line Top - Carousel'),
					'horizontal'=> esc_html__('Line Bottom - Carousel'),
				],
				'render_type' => 'template',
			]
		);
	
		$this->add_control(
			'content_layout',
			[
				'label' => __( 'Media Position', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'image-top',
				'options'=>[
					'image-top' => esc_html__('Top'),
					'image-bottom' => esc_html__('Bottom'),
					// 'background' => esc_html__('Background'),
				],
			]
		);
	
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'F j, Y',
				'options'=>[
					'F j, Y' => esc_html__(date('F j, Y')),
					'Y-m-d' => esc_html__(date('Y-m-d')),
					'Y, M, D' => esc_html__(date('Y, M, D')),
					'm/d/Y' => esc_html__(date('m/d/Y')),
					'd/m/Y' => esc_html__(date('d/m/Y')),
				],
				'condition' => [
					'timeline_content' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'timeline_fill',
			[
				'label' => esc_html__( 'Main Line Fill', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'repeater_show_year_label',
			[
				'label' => __( 'Main Line Label', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpr-addpns' ),
				'label_off' => __( 'Hide', 'wpr-addpns' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'timeline_content' => 'custom',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);
		
		$this->add_control(
			'posts_icon',
			[
				'label' => __( 'Main Line Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-amazon',
					'library' => 'solid',
				],
				'condition' => [
					'timeline_content' => 'dynamic'
				],
			]
		);
		
		$this->add_control(
			'show_extra_label',
			[
				'label' => esc_html__( 'Show Extra Label', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'separator' => 'before'
			]
		);

		// $this->add_responsive_control(
		// 	'label_alignment',
		// 	[
		// 		'label' => esc_html__( 'Extra Label Align', 'wpr-addons' ),
		// 		'type' => Controls_Manager::CHOOSE,
		// 		'label_block' => false,
		// 		'default' => 'center',
		// 		'options' => [
		// 			'left' => [
		// 				'title' => esc_html__( 'Start', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-left',
		// 				'icon' => 'eicon-text-align-left',
		// 			],
		// 			'center' => [
		// 				'title' => esc_html__( 'Center', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-center',
		// 				'icon' => 'eicon-text-align-center',
		// 			],
		// 			'right' => [
		// 				'title' => esc_html__( 'End', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-right',
		// 				'icon' => 'eicon-text-align-right',
		// 			],
		// 		],
        //         'selectors' => [
		// 			'{{WRAPPER}} .wpr-left-aligned .wpr-extra-label .wpr-label' => 'text-align: {{VALUE}};', //display: block;
		// 			'{{WRAPPER}} .wpr-left-aligned .wpr-extra-label .wpr-sub-label' => 'text-align: {{VALUE}};', //display: block;
		// 			'{{WRAPPER}} .swiper-wrapper .wpr-extra-label .wpr-label' => 'text-align: {{VALUE}};', //display: block;
		// 			'{{WRAPPER}} .swiper-wrapper .wpr-extra-label .wpr-sub-label' => 'text-align: {{VALUE}};', //display: block;
		// 		],
		// 		'condition' => [
		// 			// 'timeline_content' => 'custom',
		// 			'show_extra_label' => 'yes',
		// 			'timeline_layout' => ['centered', 'one-sided-left', 'horizontal', 'horizontal-bottom']
		// 		]
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'label_alignment_left',
		// 	[
		// 		'label' => esc_html__( 'Extra Label Align (Right)', 'wpr-addons' ),
		// 		'type' => Controls_Manager::CHOOSE,
		// 		'label_block' => false,
		// 		'default' => 'center',
		// 		'options' => [
		// 			'left' => [
		// 				'title' => esc_html__( 'Start', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-left',
		// 				'icon' => 'eicon-text-align-left',
		// 			],
		// 			'center' => [
		// 				'title' => esc_html__( 'Center', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-center',
		// 				'icon' => 'eicon-text-align-center',
		// 			],
		// 			'right' => [
		// 				'title' => esc_html__( 'End', 'wpr-addons' ),
		// 				// 'icon' => 'eicon-h-align-right',
		// 				'icon' => 'eicon-text-align-right',
		// 			],
		// 		],
        //         'selectors' => [
		// 			'{{WRAPPER}} .wpr-extra-label .wpr-label' => 'text-align: {{VALUE}};', //display: block;
		// 			'{{WRAPPER}} .wpr-extra-label .wpr-sub-label' => 'text-align: {{VALUE}};', //display: block;
		// 		],
		// 		'condition' => [
		// 			// 'timeline_content' => 'custom',
		// 			'show_extra_label' => 'yes',
		// 			'timeline_layout' => ['centered', 'one-sided']
		// 		]
		// 	]
		// );
				
		$this->add_control(
			'slides_to_show',
			[
				'label' => __( 'Slides To Show', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '3',
				'separator' => 'before',
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);
				
		$this->add_control(
			'story_info_gutter',
			[
				'label' => __( 'Gutter', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);

		$this->add_control(
			'slides_height',
			[
				'label' => esc_html__( 'Equal Height Slides', 'wpr-addons' ),
				'description' => __('Make all slides the same height based on the tallest slide','wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'auto-height',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout'   => [
					   'horizontal-bottom',
					],
				]
			]
		);

		$this->add_control(
			'slides_height_bottom_line',
			[
				'label' => esc_html__( 'Equal Height Slides', 'wpr-addons' ),
				'description' => __('Make all slides the same height','wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'condition' => [
					'timeline_layout'   => [
					   'horizontal',
					],
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'swiper_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'condition' => [
					'timeline_layout'   => [
						'horizontal-bottom',
						'horizontal'
					],
				],
				'render_type' => 'template',
			]
		);
				
		$this->add_control(
			'swiper_delay',
			[
				'label' => esc_html__( 'Autoplay Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'frontend_available' => true,
				'default' => 500,
				'condition' => [
					'swiper_autoplay' => 'yes',
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
				
		$this->add_control(
			'swiper_speed',
			[
				'label' => esc_html__( 'Carousel Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'frontend_available' => true,
				'default' => 500,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_control(
			'swiper_nav_icon',
			[
				'label' => esc_html__( 'Carousel Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom'],
				],
				// 'separator' => 'before',
			]
		);
		
		$this->add_control(
			'timeline_animation',
			[
				'label' => __( 'Entrance Animation', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'separator' => 'before',
				'options' => $this->wpr_aos_amination_array(),
				'condition'   => [
					'timeline_layout!'   => [
						'horizontal',
						'horizontal-bottom'
					 ],
				]
			]
		);

		// $this->add_responsive_control(
		// 	'timeline_animation',
		// 	[
		// 		'label' => esc_html__( 'Entrance Animation', 'elementor' ),
		// 		'type' => Controls_Manager::ANIMATION,
		// 		'frontend_available' => true,
		// 	]
		// );
		
		$this->add_control(
			'animation_offset',
			[
				'label' => esc_html__( 'Animation Offset', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'frontend_available' => true,
				'default' => 150,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'aos_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 2000,
				'frontend_available' => true,
				'default' => 600,
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'animation_loop_or_once',
			[
				'label' => esc_html__( 'Play Once', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'wpr-addons' ),
				'label_off' => esc_html__( 'No', 'wpr-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom'],
					'timeline_content' => 'dynamic'
				],
				'separator' => 'before'
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'repeater_content_section',
			[
				'label' => __( 'Timeline Items', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs(
			'story_tabs'
		);

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => __( 'Content', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'repeater_story_title',
			[
				'label' => __( 'Timeline Story Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Timeline Story',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'repeater_title_link',
			[
				'label' => esc_html__( 'Title URL', 'wpr-addons' ), // bottom issue
				'type' => Controls_Manager::TEXT,
				'default' => '#',
			]
		);

		$repeater->add_control(
			'repeater_date_label',
			[
				'label' => __( 'Primary Label', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '01 Jan 2020',
			]
		);

		$repeater->add_control(
			'repeater_extra_label',
			[
				'label' => __( 'Secondary Label', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Secondaty Label',
			]
		);

		$repeater->add_control(
			'repeater_media',
			[
				'label' => esc_html__( 'Display Media', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
					'video' => esc_html__( 'Video', 'wpr-addons' ),
				],
				'render_type' => 'template',
			]
		);
				
		$repeater->add_control(
			'repeater_youtube_video_url',
			[
				'label' => __( 'Youtube Video Link', 'twae1' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'condition'   => [
					'repeater_media' => 'video',
				]
			]
		);

		$repeater->add_control(
			'repeater_image',
			[
				'label' => __( 'Choose Image', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'description' => __('Image Size will not work with default image','wpr-addons'),
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'repeater_media' => 'image'
				]
			]
		);

		$repeater->add_control(
			'repeater_timeline_item_icon',
			[
				'label' => __( 'Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-address-book',
					'library' => 'solid',
				],
				'condition' => [
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'repeater_description',
			[
				'label' => __( 'Description', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Add Description Here',
			]
		);
	
		
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'repeater_advanced_tab',
			[
				'label' => __( 'Advanced', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'repeater_year',
			[
				'label' => __( 'Label Text', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '2020',
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name' => 'wpr_thumbnail',
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'repeater_image_size_notice',
			[
				'description' => __('Image Size will not work with default image','wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'none',				
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_color',
			[
				'label' => __( 'Inner Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}  .wpr-timeline-img i' => 'color: {{VALUE}}',
					// '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img i' => 'color: {{VALUE}}',
					// '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img i' => 'color: {{VALUE}}',
				],
				'condition' => [
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				],
				'default' => '#000',
			]
		);
		
		$repeater->add_control(
			'repeater_timeline_item_icon_bgcolor',
			[
				'label' => __( 'Inner Icon Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				],
				'default' => '#FFF',
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_size',
			[
				'label' => esc_html__( 'Inner Icon Size', 'wpr-addons' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img i' => 'font-size: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-horizontal-bottom .wpr-timeline-img svg' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-horizontal .wpr-timeline-img svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
				'condition' => [
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				]
			]
		);
		
		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					// 'show_overlay!' => 'yes',
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				],
			]
		);

		$repeater->add_responsive_control(
			'repeater_timeline_item_icon_alignment',
			[
				'label' => esc_html__( 'Inner Icon Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-timeline-img i' => 'display: block; text-align: {{VALUE}};',
				],
				'condition' => [
					// 'timeline_content' => 'custom',
					'repeater_media' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'repeater_story_icon',
			[
				'label' => __( 'Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-amazon',
					'library' => 'solid',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();
		
		$repeater->start_controls_tabs(
			'navigation_style_tabs'
		);

		$repeater->start_controls_tab(
			'repeater_styles_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);	

		$repeater->add_control(
			'item_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-story-info-vertical.wpr-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom-timeline {{CURRENT_ITEM}} .wpr-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal {{CURRENT_ITEM}} .wpr-story-info' => 'background-color: {{VALUE}}'
				]
			]
		);
		
		// $repeater->add_control(
		// 	'wpr_icon_bg_color',
		// 	[
		// 		'label'  => esc_html__( 'Icon Background Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '#605BE5',
		// 	]
		// );

		// $repeater->add_control(
		// 	'icon_color',
		// 	[
		// 		'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '#FFF',
		// 	]
		// );

		$repeater->add_control(
			'arrow_repeater_bgcolor',
			[
				'label' => __( 'Triangle Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [

					// TODO: background colors for repeater arrows
					// '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-story-info:before' => 'border-color: transparent transparent {{VALUE}} transparent !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-top .wpr-story-info:before' => 'border-bottom-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}}.swiper-slide-line-bottom .wpr-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-right-aligned .wpr-story-info-vertical:after' => 'border-right-color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-left-aligned .wpr-story-info-vertical:after' => 'border-left-color: {{VALUE}} !important',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-wrapper .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
				],
				// 'condition' => [
				// 	'timeline_layout' => 'horizontal-bottom'
				// ],
				'default' => '#605BE5',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'repeater_styles_normal_tab_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);	

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'timeline_repeater_list',
			[
				
				'label' => __( 'Content', 'wpr' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'repeater_story_title' => __( 'Amazon Founded', 'wpr' ),
						'repeater_description' => __('Placeholder for your text','wpr-addons'),
						'repeater_year'			=> __('1994','wpr-addons'),
						'repeater_date_label'   => __('Jul 1994','wpr-addons'),
						'repeater_extra_label'  => __('Amazon History','wpr-addons'),
						'repeater_image' =>[
							'url' => '',	
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
					],
					[
						'repeater_story_title' => __( 'Amazon Prime Services', 'wpr' ),
						'repeater_description' => __('Another placeholder for custom text','wpr'),
						'repeater_year'			=> __('2005','wpr-addons'),
						'repeater_date_label'   => __('Feb 2005','wpr-addons'),
						'repeater_extra_label'  => __('Amazon History','wpr-addons'),
						'repeater_image' =>[
							'url' => '',
							'id' => '',							
						],
						'repeater_youtube_video_url' => '',
						
					],
					[
						'repeater_story_title' => __( 'Amazon Announced Amazon Fresh Pickup', 'wpr' ),
						'repeater_description' => __('Create your own description','wpr'),
						'repeater_year'			=> __('2007','wpr-addons'),
						'repeater_date_label'   => __('Aug 2007','wpr-addons'),
						'repeater_extra_label'  => __('Amazon History','wpr-addons'),
						'repeater_image' =>[
							'url' => '',
							'id' => '',						
						],
						'repeater_youtube_video_url' => '',
					],
				],
				'title_field' => '{{{ repeater_story_title }}}',
			]
		);

		$this->end_controls_section();

		$post_types = Utilities::get_custom_types_of( 'post', false );
		unset( $post_types['product'] );
		// unset($post_types['page']);
		// unset($post_types['e-landing-page']);
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );
		$categories = [];
		foreach(get_categories() as $key=>$category) {
			array_push($categories, $key);
		}

        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', 'wpr-addons'),
				'condition' => [
					'timeline_content' => 'dynamic',
				]
            ]
        );

        $this->add_control(
			'timeline_post_types',
			[
				'label' => esc_html__( 'Post Type', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'label_block' => false,
				'options' => $post_types,
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'grid', 'query_source', ['pro-rl', 'pro-cr'] );

        $this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Selection', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'wpr-addons' ),
					'manual' => esc_html__( 'Manual', 'wpr-addons' ),
				],
				'condition' => [
					'timeline_post_types!' => [ 'current', 'related' ],
				],
			]
		);

		$this->add_control(
			'query_tax_selection',
			[
				'label' => esc_html__( 'Selection Taxonomy', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $post_taxonomies,
				'condition' => [
					'timeline_post_types' => 'related',
				],
			]
		);
		
		// Manual Selection
		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_manual_'. $slug,
				[
					'label' => esc_html__( 'Select ', 'wpr-addons' ) . $title,
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'label_block' => true,
					'options' => Utilities::get_posts_by_post_type( $slug ),
					'condition' => [
						'timeline_post_types' => $slug,
						'query_selection' => 'manual',
					],
					'separator' => 'before',
				]
			);
		}

        $this->add_control(
			'query_author',
			[
				'label' => esc_html__( 'Authors', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => Utilities::get_users(),
				'separator' => 'before',
				'condition' => [
					'timeline_post_types!' => [ 'current', 'related' ],
					'query_selection' => 'dynamic',
				],
			]
		);

		foreach ( $post_taxonomies as $slug => $title ) {
			global $wp_taxonomies;
			$post_type = '';
			if ( isset($wp_taxonomies[$slug]) && isset($wp_taxonomies[$slug]->object_type[0]) ) {
				$post_type = $wp_taxonomies[$slug]->object_type[0];
			}

			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => $title,
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'default' => 'post',
					'label_block' => true,
					'options' => Utilities::get_terms_by_taxonomy( $slug ),
					'condition' => [
						'timeline_post_types' => $post_type,
						'query_selection' => 'dynamic',
					],
				]
			);
		}

		foreach ( $post_types as $slug => $title ) {
			$this->add_control(
				'query_exclude_'. $slug,
				[
					'label' => esc_html__( 'Exclude ', 'wpr-addons' ) . $title,
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'label_block' => true,
					'options' => Utilities::get_posts_by_post_type( $slug ),
					'condition' => [
						'timeline_content' => 'dynamic',
						'timeline_post_types' => $slug,
						'timeline_post_types!' => [ 'current', 'related' ],
						'query_selection' => 'dynamic',
					],
				]
			);
		}

        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'wpr-addons'),
				'type' => Controls_Manager::NUMBER,
				'render_type' => 'template',
				'default' => 3,
                'min' => 0,
				'label_block' => false,
			]
		);

        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'title' => esc_html__( 'Title', 'wpr-addons'),
					'date' => esc_html__( 'Date', 'wpr-addons'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

        $this->add_control(
			'order_direction',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'label_block' => false,
				'options' => [
					'ASC' => esc_html__( 'Ascending', 'wpr-addons'),
					'DESC' => esc_html__( 'Descending', 'wpr-addons'),
				],
				'condition' => [
					'query_selection' => 'dynamic',
				]
			]
		);

		$this->add_control(
			'query_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'No Posts Found!',
				'condition' => [
					// 'query_selection' => 'dynamic',
					// 'query_source!' => 'related',
				]
			]
		);

		$this->add_control(
			'query_exclude_no_images',
			[
				'label' => esc_html__( 'Exclude Items w/o Thumbnail', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'wpr-addons'),
				'condition' => [
					// 'timeline_content' => 'dynamic',
				]
            ]
        );
		
		$this->add_responsive_control(
			'content_alignment_left',
			[
				'label' => esc_html__( 'Content Align', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-left-aligned .wpr-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-left-aligned .wpr-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-left-aligned .wpr-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-left-aligned .wpr-timeline-img' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-timeline-img' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .wpr-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .wpr-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .swiper-wrapper .wpr-inner-date-label' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided-left', 'horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Align (Right)', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .wpr-right-aligned .wpr-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-right-aligned .wpr-description' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-right-aligned .wpr-inner-date-label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-right-aligned .wpr-timeline-img' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->add_control(
			'show_overlay',
			[
				'label' => esc_html__( 'Show Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'separator' => 'before',
				'render_type' => 'template',
				'condition' => [
					'content_layout' => 'image-top'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-img img' => 'display: block; width: 100%!important;'
				]
			]
		);

		$this->add_control(
			'show_on_hover',
			[
				'label' => esc_html__( 'Show Items on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide:hover .wpr-story-info' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				]
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_overlay',
			[
				'label' => esc_html__( 'Title Over Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'show_title' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_title_on_hover',
			[
				'label' => esc_html__( 'Show Title on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-title' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-title' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-timeline-entry:hover .wpr-title' => 'opacity: 1; transform: translateY(0%);',
					'{{WRAPPER}} .wpr-story-info:hover .wpr-title' => 'opacity: 1; transform: translateY(0%);',
				],
				'condition' => [
					'show_title' => 'yes',
					'title_overlay' => 'yes'
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_overlay',
			[
				'label' => esc_html__( 'Date Over Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				// 'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'timeline_content' => 'dynamic',
					'show_date' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_date_on_hover',
			[
				'label' => esc_html__( 'Show Date on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-story-info:hover .wpr-inner-date-label' => 'opacity: 1; transform: translateY(0%);',
					'{{WRAPPER}} .wpr-timeline-entry:hover .wpr-inner-date-label' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'show_date' => ['yes'],
					'date_overlay' => 'yes',
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					// 'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'description_overlay',
			[
				'label' => esc_html__( 'Description Over Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'condition' => [
					'show_overlay' => 'yes',
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_description_on_hover',
			[
				'label' => esc_html__( 'Show Description on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-description' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-story-info:hover .wpr-description' => 'opacity: 1; transform: translateY(0%);',
					'{{WRAPPER}} .wpr-timeline-entry:hover .wpr-description' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'show_description' => ['yes'],
					'description_overlay' => 'yes'
				]
			]
		);
		
		$this->add_control( //TODO: remove from custom if not needed
			'excerpt_count',
			[
				'label' => esc_html__( 'Excerpt Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 200,
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 10,
				'condition' => [
					'timeline_content' => 'dynamic',
					'show_description' => 'yes'
				]
			]
		);

		// $this->add_control( //TODO: where does it work
		// 	'show_image',
		// 	[
		// 		'label' => esc_html__( 'Show Image', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'default' => 'yes',
		// 		'label_block' => false,
		// 		'render_type' => 'template',
		// 	]
		// );

		$this->add_control(
			'show_readmore',
			[
				'label' => esc_html__( 'Show Read More', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_control(
			'readmore_overlay',
			[
				'label' => esc_html__( 'Read More Over Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'show_overlay' => 'yes',
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->add_control(
			'show_readmore_on_hover',
			[
				'label' => esc_html__( 'Show Read More on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'selectors_dictionary' => [
					'yes' => 'opacity: 0; transition: all 0.5s ease',
					'no' => 'visibility: visible;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-read-more-button' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-story-info:hover .wpr-read-more-button' => 'opacity: 1; transform: translateY(0%);',
					'{{WRAPPER}} .wpr-timeline-entry:hover .wpr-read-more-button' => 'opacity: 1; transform: translateY(0%);'
				],
				'condition' => [
					'show_readmore' => ['yes'],
					'readmore_overlay' => 'yes'
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment_left',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .wpr-left-aligned .wpr-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-left-aligned .wpr-read-more-button' => 'text-align: center;',
					'{{WRAPPER}} .swiper-wrapper .wpr-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout!' => 'one-sided',
				]
			]
		);

		$this->add_responsive_control (
			'readmore_content_alignment',
			[
				'label' => esc_html__( 'Align (Right)', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .wpr-right-aligned .wpr-read-more-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-right-aligned .wpr-read-more-button' => 'text-align: center;',
				],
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => esc_html__( 'Read More', 'wpr-addons' ), // bottom issue
				'type' => Controls_Manager::TEXT,
				'default' => 'Read More',
				'condition' => [
					'show_readmore' => 'yes',
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'overlay_section',
			[
				'label' => __( 'Overlay', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_overlay' => 'yes'
				]
			]
		);
		
		$this->add_control(
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
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Height', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					// '{{WRAPPER}} .wpr-timeline-story-overlay[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'overlay_content_alignment_vertical',
			[
				'label' => esc_html__( 'Overlay Content Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_overlay' => 'yes',
					'content_layout' => 'image-top'
				]
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				// 'condition' => [
				// 	'repeater_media' => 'icon' 
				// ],
			]
		);

		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'posts-timeline', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
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
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-timeline-story-overlay' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

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

		$this->end_controls_section();

		$this->start_controls_section(
			'pagination_section',
			[
				'label' => __( 'Pagination', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left'],
					'show_pagination' => 'yes'
				]
			]
		);
	
		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination Type', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'load-more',
				'options'=>[
					'load-more' => __('Load More'),
					'infinite-scroll' => __('Infinite Scroll')
				],
				'condition' => [
					'show_pagination' => 'yes',
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Load More', 'wpr-addons'),
				'condition' => [
					'pagination_type' => 'load-more',
				]
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'End of Content.',
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
				]
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'loader-1' => esc_html__( 'Loader 1', 'wpr-addons' ),
					'loader-2' => esc_html__( 'Loader 2', 'wpr-addons' ),
					'loader-3' => esc_html__( 'Loader 3', 'wpr-addons' ),
					'loader-4' => esc_html__( 'Loader 4', 'wpr-addons' ),
					'loader-5' => esc_html__( 'Loader 5', 'wpr-addons' ),
					'loader-6' => esc_html__( 'Loader 6', 'wpr-addons' ),
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ],
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);
		
		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
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
					'{{WRAPPER}} .wpr-grid-pagination' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-pagination-loading' => 'text-align: {{VALUE}};',
					// '{{WRAPPER}} .wpr-pagination-loading div' => 'margin: auto;',
				],
				'condition' => [
					'timeline_content' => ['dynamic'],
					'timeline_layout' => ['centered', 'one-sided']
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_styles_section',
			[
				'label' => __( 'Items', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'story_bgcolor',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-data-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal .wpr-story-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-story-info' => 'background-color: {{VALUE}}',
				],
				'default' => '#FFF',
			]
		);

		$this->add_control(
			'story_border_color',
			[
				'label' => __( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .wpr-story-info-vertical' => 'border-color: {{VALUE}} !important',
				],
				'condition' => [
					'timeline_layout!' => 'centered'
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'story_border_color_left',
			[
				'label' => __( 'Border Color (Left-aligned)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-left-aligned .wpr-story-info-vertical' => 'border-color: {{VALUE}} !important',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'story_border_color_right',
			[
				'label' => __( 'Border Color (Right-aligned)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-right-aligned .wpr-story-info-vertical' => 'border-color: {{VALUE}} !important',
				],
				'condition' => [
					'timeline_layout' => 'centered',
				],
				'default' => '#605BE5',
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow',
				'selector' => '{{WRAPPER}} .wpr-story-info',
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'timeline_item_shadow_vertical',
				'selector' => '{{WRAPPER}} .wpr-story-info-vertical',
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);

		$this->add_responsive_control(
			'timeline_container_height',
			[
				'label' => esc_html__( 'Container Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				// 'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 450,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-horizontal-wrapper' => 'height: calc(100% + {{timeline_item_position.SIZE}}) !important;',
					// '{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide' => 'height: calc(100% + {{timeline_item_position.SIZE}}) !important;',
					
					// '{{WRAPPER}} .wpr-horizontal-wrapper' => 'height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position.SIZE}}) !important;',
					// '{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide' => 'height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position.SIZE}} ) !important;',
					// '{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide .wpr-story-info' => 'height: {{SIZE}}{{UNIT}} !important; min-height: 100% !important;',

					// '{{WRAPPER}} .wpr-horizontal-wrapper' => 'min-height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position.SIZE}}) !important;',
					// '{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide-line-bottom' => 'min-height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position.SIZE}}{{UNIT}} ) !important;', 

					'{{WRAPPER}} .wpr-horizontal-wrapper' => 'min-height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position_equal_heights.SIZE}}) !important;',
					'{{WRAPPER}} .wpr-horizontal-timeline .swiper-slide-line-bottom' => 'min-height: calc( {{SIZE}}{{UNIT}} + {{timeline_item_position_equal_heights.SIZE}}{{UNIT}} ) !important;', 
				],
				'condition' => [
					'timeline_layout' => ['horizontal'],
				],
			]
		);

		$this->add_responsive_control(
			'vertical_timeline_container_height',
			[
				'label' => esc_html__( 'Container Height', 'wpr-addons' ),
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
						'max' => 5000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered .wpr-story-info-vertical' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-timeline-centered' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'content_layout' => ['background'],
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);

		$this->add_responsive_control(
			'timeline_img_width',
			[
				'label' => esc_html__( 'Image Size', 'wpr-addons' ),
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
						'max' => 5000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal'], // horizontal-bottom
					// 'slides_height' => ['auto-height']
				],
			]
		);

		$this->add_responsive_control(
			'timeline_item_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'position: absolute; bottom: {{SIZE}}{{UNIT}} !important;',
					// '{{WRAPPER}} .wpr-story-info' => 'position: relative; bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal'],
					'slides_height_bottom_line!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'timeline_item_position_equal_heights',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Item Bottom Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'margin-bottom: {{SIZE}}{{UNIT}} !important; height: {{timeline_container_height.SIZE}}px',
				],
				'condition' => [
					'timeline_layout' => ['horizontal'],
					'slides_height_bottom_line' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'story_info_margin_top',
			[
				'label' => esc_html__( 'Item Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 170,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 5000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-horizontal-bottom-timeline .swiper-slide.auto-height .wpr-story-info' => 'max-height: calc(100% - {{SIZE}}{{UNIT}}) !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom'],
				],
			]
		);

		// $this->add_responsive_control(
		// 	'story_info_position_top',
		// 	[
		// 		'label' => esc_html__( 'Item Top', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ '%', 'px' ],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 170,
		// 		],
		// 		'range' => [
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 5000,
		// 			],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-story-info' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
		// 			'{{WRAPPER}} .wpr-horizontal-bottom-timeline .swiper-slide.auto-height .wpr-story-info' => 'max-height: calc(100% - {{SIZE}}{{UNIT}}) !important;',
		// 		],
		// 		'condition' => [
		// 			'timeline_layout' => ['horizontal-bottom'],
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'story_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-timeline-story-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-data-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'show_overlay!' => 'yes'
				],
			]
		);

		$this->add_control( //TOdO: add pagination icon
			'item_distance',
			[
				'label' => esc_html__( 'Item Distance', 'wpr-addons' ),
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
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered.wpr-one-sided-timeline-left .wpr-timeline-entry.wpr-left-aligned .wpr-timeline-entry-inner .wpr-data-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-timeline-centered.wpr-one-sided-timeline .wpr-timeline-entry.wpr-right-aligned .wpr-timeline-entry-inner .wpr-data-wrap' => 'margin-left: calc(27% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .wpr-centered .wpr-left-aligned .wpr-timeline-entry-inner .wpr-data-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-centered .wpr-right-aligned .wpr-timeline-entry-inner .wpr-data-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// $this->add_responsive_control( //TODO: decide need or not
		// 	'wpr_story_cont_padding',
		// 	[
		// 		'label' => esc_html__( 'Container Padding', 'wpr-addons' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px' ],
		// 		'default' => [
		// 			'top' => 2,
		// 			'right' => 2,
		// 			'bottom' => 2,
		// 			'left' => 2,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-horizontal-timeline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 		// TODO: creates problem with slider container
		// 	]
		// );

		$this->add_control(
			'timeline_item_border_type',
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
					'{{WRAPPER}} .wpr-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-story-info' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-story-info-vertical' => 'border-style: {{VALUE}} !important;',
				],
				'separator' => 'before',
			]
		);

		
		$this->add_control(
			'timeline_item_border_width',
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
					'{{WRAPPER}} .wpr-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-horizontal-timeline .wpr-story-info:before' => 'top: calc( 100% + {{BOTTOM}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .wpr-horizontal-bottom-timeline .wpr-story-info:before' => 'bottom: calc( 100% + {{TOP}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .wpr-right-aligned .wpr-story-info-vertical.wpr-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'{{WRAPPER}} .wpr-left-aligned .wpr-story-info-vertical.wpr-data-wrap:after' => 'left: calc( 100% + {{LEFT}}{{UNIT}} ) !important;'
				],
				'condition' => [
					'timeline_layout!' => 'centered',
					'timeline_item_border_type!' => 'none'
				],
			]
		);

		$this->add_control(
			'timeline_item_border_width_left',
			[
				'label' => esc_html__( 'Border Width (Left-aligned)', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-story-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'left: calc( 100% + {{RIGHT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'right: calc( 103% + {{LEFT}}{{UNIT}} ) !important; left: auto !important',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'timeline_item_border_width_right',
			[
				'label' => esc_html__( 'Border Width (Right-aligned)', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-right-aligned .wpr-story-info-vertical' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-right-aligned .wpr-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-right-aligned .wpr-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-right-aligned .wpr-data-wrap:after' => 'right: calc( 100% + {{LEFT}}{{UNIT}} ) !important;',
				],
				'condition' => [
					'timeline_layout' => 'centered',
					'timeline_item_border_type!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'story_border_radius',
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
					'{{WRAPPER}} .wpr-story-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-story-info img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // TODO: img radius issue on paddings
					'{{WRAPPER}} .wpr-story-info-vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				// 'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'overlay_style_section',
			[
				'label' => __( 'Overlay', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_overlay' => ['yes']
				],
			]
		);

		$this->add_control(
			'overlay_bgcolor',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-timeline-story-overlay' => 'background-color: {{VALUE}}',
				],
				'default' => '#0000005E',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => esc_html__( 'Background', 'plugin-name' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .wpr-timeline-story-overlay',
			]
		);

		$this->add_responsive_control(
			'timeline_overlay_padding',
			[
				'label' => esc_html__( 'Overlay Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-story-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' =>[
					'show_overlay' => 'yes'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_styles_section',
			[
				'label' => __( 'Title', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
		);

		$this->start_controls_tabs('title_style_tabs');
		
		$this->start_controls_tab(
			'title_style_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);
		
		/*---- Story Title ----*/
		$this->add_control(
			'story_title_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-data-wrap span.wpr-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal span.wpr-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom span.wpr-title' => 'color: {{VALUE}}',
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'story_title_bg_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-data-wrap span.wpr-title' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal span.wpr-title' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom span.wpr-title' => 'background-color: {{VALUE}}',
				],
				'default' => 'transparent',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-wrapper span.wpr-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'title_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
				
		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-title-wrap:hover .wpr-title' => 'cursor: pointer; color: {{VAlUE}}',
				],
				'default' => '#ffA',
			]
		);
		
		$this->add_control(
			'title_bg_color_hover',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-title-wrap:hover .wpr-title' => 'background-color: {{VAlUE}}',
				],
				'default' => '#433BD5',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic'
				]
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => 'border-color: {{VALUE}}',
				]
			]
		);

		// $this->add_control( //TODO: add inner text
		// 	'date_extra_text_color',
		// 	[
		// 		'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '#9C9C9C',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-item-date .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'date_extra_icon_color',
		// 	[
		// 		'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'default' => '#9C9C9C',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-item-date .inner-block i[class*="wpr-grid-extra-icon"]' => 'color: {{VALUE}}',
		// 		],
		// 		'separator' => 'after',
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-inner-date-label'
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .wpr-inner-date-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
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
					'{{WRAPPER}} .wpr-inner-date-label' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		// $this->add_control(
		// 	'date_icon_spacing',
		// 	[
		// 		'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px'],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 25,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 5,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .wpr-grid-item-date .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'date_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 17,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-inner-date-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
					
		$this->start_controls_section(
			'description_styles_section',
			[
				'label' => __( 'Description', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-description' => 'color: {{VALUE}}',
				],
				'default' => '#333333',
			]
		);

		$this->add_control(
			'description_bg_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-description' => 'background-color: {{VALUE}}',
				],
				'default' => 'transparent',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_description',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-wrapper .wpr-description',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
			
		$this->start_controls_section(
			'readmore_styles_section',
			[
				'label' => __( 'Read More', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => ['dynamic']
				]
			]
		);

		$this->start_controls_tabs(
			'readmore_style_tabs'
		);

		$this->start_controls_tab(
			'readmore_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button' => 'color: {{VAlUE}}',
				],
				'default' => '#fff',
			]
		);
		
		$this->add_control(
			'readmore_bg_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button' => 'background-color: {{VAlUE}}',
				],
				'default' => '#443DD7',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-wrapper .wpr-read-more-button',
			]
		);

		$this->add_responsive_control(
			'readmore_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 130,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button' => 'display: inline-block; width: {{SIZE}}{{UNIT}} !important; height: auto;',
					// '{{WRAPPER}} .wpr-wrapper .wpr-read-more-wrap' => 'width: {{SIZE}}{{UNIT}} !important; height: auto;',

				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'readmore_border_radius',
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
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
				
		$this->add_control(
			'readmore_color_hover',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button:hover' => 'color: {{VAlUE}}',
				],
				'default' => '#ffA',
			]
		);
		
		$this->add_control(
			'readmore_bg_color_hover',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-read-more-button:hover' => 'background-color: {{VAlUE}}',
				],
				'default' => '#433BD5',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
		$this->start_controls_section(
			'middle_line_styles_section',
			[
				'label' => __( 'Main Line', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( //TODO: change slider control to number
			'middle_line_width',
			[
				'label' => esc_html__( 'Line Width', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-line::before' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .wpr-wrapper .wpr-middle-line' => 'transform: scaleX({{SIZE}}) !important;',
					'{{WRAPPER}} .wpr-wrapper #wpr-timeline-fill' => 'transform: scaleX({{SIZE}}) !important;',
					/// add container class for more specificity
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __( 'Line Color', 'wpr' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-line::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper .wpr-middle-line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper .wpr-timeline-centered .wpr-year' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal .wpr-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-pagination.swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-next' => 'color: {{VALUE}}',
				],
				'default' => '#D6D6D6',
			]
		); //Todo: find out why so many colors together

		$this->add_control(
			'timeline_fill_color',
			[
				'label'  => esc_html__( 'Line Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-fill' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-change-border-color' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-vertical:before' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-vertical:after' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		//todo: add progressbar height
		$this->add_responsive_control(
			'swiper_pagination_progressbar_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Progressbar Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 109,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-pagination.swiper-pagination-progressbar' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);
		
		$this->add_control(
			'swiper_progressbar_color',
			[
				'label' => __( 'Progressfill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				],
				'default' => '#605BE5',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'year_label_section',
			[
				'label' => __( 'Main Line Label', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'year_label_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-year' => 'color: {{VALUE}}',
				],
				'default' => '#fff',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_bgcolor',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-year' => 'background-color: {{VALUE}}', //GOBACK
				],
				'default' => '#54595F',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_control(
			'year_label_border_color',
			[
				'label' => __( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-year' => 'border-color: {{VALUE}}', //GOBACK
				],
				'default' => '#54595F',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'year_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-wrapper .wpr-year',
				'condition' => [
					'timeline_content' => ['custom'],
				]
			]
		);
		
		$this->add_responsive_control(
			'year-label-padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 15,
					'bottom' => 10,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-year-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'year_label_border_type',
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
					'{{WRAPPER}} .wpr-year-label' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'year_label_border_size',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-year-label' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'year_label_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'year_label_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-year-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_styles_section',
			[
				'label' => __( 'Main Line Icon', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_bgcolor',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'background-color: {{VALUE}}',
				],
				'default' => '#605BE5',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => \Elementor\Core\Schemes\Color::get_type(),
				// 	'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'color: {{VALUE}}; border-color: {{icon_border_color}};',
				],
				'condition' => [
					// 'timeline_content' => ['dynamic']
				],
				'default' => '#FFF',
			]
		);

		//TODO: override javascript
		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					// '{{WRAPPER}} .wpr-story-info-vertical .wpr-icon' => 'border-color: {{VALUE}}',
					// '{{WRAPPER}} .wpr-story-info .wpr-icon' => 'border-color: {{VALUE}}',
					// '{{WRAPPER}} .wpr-horizontal-timeline .wpr-icon' => 'border-color: {{VALUE}}',
					// '{{WRAPPER}} .wpr-horizontal-bottom-timeline .wpr-icon' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_bottom_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 110,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-icon' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		// $this->add_responsive_control( // TODO: determine why used top previously
		// 	'icon_top_position',
		// 	[
		// 		'type' => Controls_Manager::SLIDER,
		// 		'label' => esc_html__( 'Icon position', 'wpr-addons' ),
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => -500,
		// 				'max' => 500,
		// 			]
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 80,
		// 		],			
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-horizontal-bottom .wpr-icon' => 'top: {{SIZE}}{{UNIT}} !important;',
		// 		],
		// 		'condition' => ['timeline_layout' => ['horizontal-bottom']],
		// 	]
		// );

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'wpr' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'icon_bg_size',
			[
				'label' => esc_html__( 'Icon Background Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 38,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-icon i' => 'display: block;',
					'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; display: flex !important; justify-content: center !important; align-items: center !important;',

				],
				'separator' => 'after',
			]
		);

		// $this->add_control( // TODO: needs centering from css	
		// 	'wpr_icon_margin_left_onesided',
		// 	[
		// 		'label' => __( 'Margin Left', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 58,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-icon' => 'margin-left: calc(24%); transform: translate(-50%);', // .wpr-story-info-vertical
		// 		],
		// 		'condition' => [
		// 			'timeline_layout' => ['one-sided'],
		// 		]
		// 	]
		// );
		
		$this->add_control(
			'icon_top',
			[
				'label' => __( 'Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 113,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'top: {{SIZE}}{{UNIT}}; transform: translate(-50%, -50%);',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom'],
				]
			]
		);
		
		// $this->add_control(
		// 	'wpr_icon_margin_left_horizontal',
		// 	[
		// 		'label' => __( 'Position Left', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 500,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 94,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'left: {{SIZE}}{{UNIT}}',
		// 			// '{{WRAPPER}} .wpr-wrapper .wpr-icon' => 'margin-bottom: {{SIZE}}{{UNIT}}', // creates marker shape
		// 		],
		// 		'condition' => [
		// 			'timeline_layout' => ['horizontal', 'horizontal-bottom'],
		// 		]
		// 	]
		// );
		
		$this->add_control(
			'icon_border_type',
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
					'{{WRAPPER}} .wpr-icon' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_radius',
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
					'{{WRAPPER}} .wpr-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
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
					'{{WRAPPER}} .wpr-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();	
			
		$this->start_controls_section(
			'label_styles_section',
			[
				'label' => __( 'Extra Label', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_extra_label' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'date_label_bg_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-extra-label' => 'background-color: {{VALUE}}',
				],
				'default' => 'transparent',
			] 
		);

		$this->add_responsive_control(
			'label_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-extra-label' => 'width: {{SIZE}}{{UNIT}}; height: auto;',

				],
				// 'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'label_right',
			[
				'label' => __( 'Right Label Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%','px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered.wpr-both-sided-timeline .wpr-timeline-entry.wpr-left-aligned .wpr-timeline-entry-inner .wpr-extra-label' => 'left: calc(100% + {{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'timeline_layout' => ['centered'],
				]
			]
		);

		$this->add_responsive_control(
			'label_left',
			[
				'label' => __( 'Left Label Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered.wpr-both-sided-timeline .wpr-timeline-entry.wpr-right-aligned .wpr-timeline-entry-inner .wpr-extra-label' => 'right: calc(100% + {{SIZE}}{{UNIT}})',
				],
				'condition' => [
					'timeline_layout' => ['centered'],
				]
			]
		);

		$this->add_responsive_control(
			'label_one_sided',
			[
				'label' => __( 'Left Label Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 130,
					],
					'%' => [
						'min' => 0,
						'max' => 12,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered.wpr-one-sided-timeline .wpr-extra-label' => 'left: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'timeline_layout' => ['one-sided'],
				]
			]
		);

		$this->add_responsive_control(
			'label_one_sided_left',
			[
				'label' => __( 'Right Label Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered.wpr-one-sided-timeline-left .wpr-extra-label' => 'left: calc(100% + {{SIZE}}{{UNIT}}) !important',
				],
				'condition' => [
					'timeline_layout' => ['one-sided-left'],
				]
			]
		);

		$this->add_control(
			'label_top_position',
			[
				'label' => __( 'Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-extra-label' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom'],
				]
			]
		);

		$this->add_responsive_control(
			'label_bottom_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Label Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-extra-label' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-extra-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_border_radius',
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
					'{{WRAPPER}} .wpr-wrapper .wpr-extra-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					/// add container class for more specificity
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_section',
			[
				'label' => __( 'Primary Label', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_label_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper span.wpr-label' => 'color: {{VALUE}}',
				],
				'default' => '#605BE5',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper span.wpr-label',
					'{{WRAPPER}} .wpr-extra-label span.wpr-label',
				],
			]
		);

		/*---- Secondary Label ----*/
		$this->add_control(
			'secondary_label_section',
			[
				'label' => __( 'Secondary Label', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'timeline_content' => 'custom'
				]
			]
		);

		$this->add_control(
			'secondary_label_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper span.wpr-sub-label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_content' => 'custom'
				],
				'default' => '#7A7A7A',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_label_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-wrapper span.wpr-sub-label',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'triangle_styles',
			[
				'label' => esc_html__( 'Triangle', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'triangle_bgcolor',
			[
				'label' => __( 'Triangle Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wrapper .wpr-one-sided-timeline .wpr-data-wrap:after' => 'border-right-color: {{icon_bgcolor}}',
					'{{WRAPPER}} .wpr-wrapper .wpr-one-sided-timeline-left .wpr-data-wrap:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wrapper .wpr-right-aligned .wpr-data-wrap:after' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-horizontal .wpr-story-info:before' => 'border-top-color: {{VALUE}} !important',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-story-info:before' => 'border-bottom-color: {{VALUE}} !important',
					'{{WRAPPER}} .wpr-wrapper .wpr-left-aligned .wpr-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-wrapper .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'border-right-color: {{VALUE}} !important; border-left-color: transparent !important;',
					'{{WRAPPER}} .wpr-wrapper .wpr-one-sided-timeline-left .wpr-left-aligned .wpr-data-wrap:after' => 'border-left-color: {{VALUE}} !important',
				],
				'condition' => [
					'timeline_content' => 'dynamic'
				],
				'default' => '#605BE5',
			]
		);
		
		$this->add_responsive_control(
			'story_triangle_size',
			[
				'label' => esc_html__( 'Triangle Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-story-info:before' => 'border-width: {{size}}{{UNIT}}; top: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-story-info:before' => 'border-width: {{size}}{{UNIT}}; bottom: 100%; left: 50%; transform: translate(-50%);',
					'{{WRAPPER}} .wpr-one-sided-timeline .wpr-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .wpr-one-sided-timeline-left .wpr-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{triangle_onesided_position_top.SIZE}}%; transform: translateY(-50%);',
					'{{WRAPPER}} .wpr-both-sided-timeline .wpr-right-aligned .wpr-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.SIZE}}; transform: translateY(-50%);',
					'{{WRAPPER}} .wpr-both-sided-timeline .wpr-left-aligned .wpr-data-wrap:after' => 'border-width: {{size}}{{UNIT}}; top: {{arrow_bothsided_position_top.SIZE}}{{arrow_bothsided_position_top.SIZE}}; transform: translateY(-50%);',
				],
			]
		);

		$this->add_control(
			'triangle_horizontal-bottom_position_top',
			[
				'label' => __( 'Triangle Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-story-info:before' => 'top: {{size}}{{UNIT}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'triangle_horizontal-bottom_position_left',
			[
				'label' => __( 'Triangle Position Left', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-story-info:before' => 'left: {{size}}{{UNIT}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal-bottom']
				],
			]
		);

		$this->add_control(
			'triangle_horizontal_position_top',
			[
				'label' => __( 'Triangle Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-story-info:before' => 'top: {{size}}{{UNIT}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		$this->add_control(
			'triangle_horizontal_position_left',
			[
				'label' => __( 'Triangle Position Left', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal .wpr-story-info:before' => 'left: {{size}}{{UNIT}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal']
				],
			]
		);

		$this->add_control(
			'triangle_onesided_position_top',
			[
				'label' => __( 'Triangle Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'selectors' => [

					'{{WRAPPER}} .wpr-one-sided-timeline .wpr-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .wpr-one-sided-timeline-left .wpr-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					// '{{WRAPPER}} .wpr-timeline-centered.wpr-both-sided-timeline .wpr-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate (-50%, -50%) !important;',
					'{{WRAPPER}} .wpr-one-sided-timeline .wpr-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate (-50%, -50%) !important;',
					'{{WRAPPER}} .wpr-one-sided-timeline .wpr-extra-label' => 'top: {{size}}{{UNIT}};  transform: translateY(-50%) !important;',
					'{{WRAPPER}} .wpr-one-sided-timeline-left .wpr-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%,-50%) !important;',
					'{{WRAPPER}} .wpr-one-sided-timeline-left .wpr-extra-label' => 'top: {{size}}{{UNIT}};  transform: translateY(-50%) !important;',
				],
				'condition' => [
					'timeline_layout' => ['one-sided', 'one-sided-left']
				],
			]
		);

		$this->add_control(
			'arrow_bothsided_position_top',
			[
				'label' => __( 'Triangle Position Top', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-timeline-centered .wpr-data-wrap:after' => 'top: {{size}}{{UNIT}}; transform: translateY(-50%) !important;',
					'{{WRAPPER}} .wpr-timeline-centered  .wpr-right-aligned .wpr-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(50%, -50%) !important;',
					'{{WRAPPER}} .wpr-timeline-centered  .wpr-left-aligned .wpr-icon' => 'position: absolute; top: {{size}}{{UNIT}}; transform: translate(-50%, -50%) !important;',
					'{{WRAPPER}} .wpr-timeline-centered .wpr-extra-label' => 'top: {{size}}{{UNIT}};  transform: translateY(-50%) !important;', 
				],
				'condition' => [
					'timeline_layout' => ['centered']
				],
				'render_type' => 'template'
			]
		);

		// $this->add_control(
		// 	'arrow_onesided_position_left',
		// 	[
		// 		'label' => __( 'Triangle Position Left', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['%', 'px'],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => -1000,
		// 				'max' => 1000,
		// 			],
		// 			'%' => [
		// 				'min' => -100,
		// 				'max' => 150,
		// 			],
		// 		],
		// 		'default' => [
		// 			'size' => 100,
		// 			'unit' => '%',
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-data-wrap:after' => 'right: {{SIZE}}{{UNIT}};',
		// 		],
		// 		'condition' => [
		// 			'timeline_layout' => ['one-sided']
		// 		],
		// 	]
		// );

		$this->end_controls_section();

			$this->start_controls_section(
				'navigation_button_styles',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);
		
		$this->start_controls_tabs(
			'navigation_style_tabs'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'navigation_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-next' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-next i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_responsive_control(
			'navigation_icon_size',
			[
				'label' => esc_html__( 'Button Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					
				],
			]
		);

		$this->add_responsive_control(
			'navigation_icon_bg_size',
			[
				'label' => esc_html__( 'Button Background Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .wpr-horizontal-bottom .wpr-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-next i' => 'width: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .wpr-horizontal .wpr-button-prev i' => 'width: {{SIZE}}{{UNIT}}; text-align: center;',
				],
			]
		);

		$this->add_responsive_control(
			'swiper_navigation_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 71,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-next' => 'top: auto; bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'timeline_layout' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'swiper_navigation_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 114,
				],			
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev' => 'bottom: auto; top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-next' => 'bottom: auto; top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'timeline_layout' => 'horizontal-bottom',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		); //hovertab
		
		$this->add_control(
			'navigation_button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} .wpr-button-next:hover' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'navigation_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE1',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-prev:hover i' => 'color: {{VALUE}}; cursor: pointer;',
					'{{WRAPPER}} .wpr-button-next:hover i' => 'color: {{VALUE}}; cursor: pointer;',
				],
				'condition' => [
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'timeline_content' => 'dynamic',
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);	

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'color: {{VALUE}}',
				],
				'condition' => [
					'timeline_layout' => ['centered', 'one-sided', 'one-sided-left']
				]
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination a, {{WRAPPER}} .wpr-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-double-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wave .wpr-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-chasing-dots .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-three-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-fading-circle .wpr-circle:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_type' => [ 'load-more', 'infinite-scroll' ]
				]
			]
		);

		// $this->add_control(
		// 	'pagination_wrapper_color',
		// 	[
		// 		'label'  => esc_html__( 'Wrapper Color', 'wpr-addons' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-pagination' => 'background-color: {{VALUE}}',
		// 		],
		// 		'separator' => 'after',
		// 	]
		// );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination a:hover, {{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		;$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'loadmore_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-load-more-btn',
			]
		);

		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name'     => 'pagination_typography',
		// 		'scheme' => Typography::TYPOGRAPHY_3, 
		// 		'selector' => '{{WRAPPER}} .wpr-grid-pagination'
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'pagination_icon_size',
		// 	[
		// 		'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => ['px'],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 5,
		// 				'max' => 30,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 15,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .wpr-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		$this->add_control(
			'pagination_border_type',
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
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
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Timeline', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}
	
	public function get_tax_query_args() {
		$settings = $this->get_settings();
		$tax_query = [];

		if ( 'related' === $settings[ 'timeline_post_types' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'timeline_post_types' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
			}
		}

		return $tax_query;
	}

	// for frontend
	public function get_main_query_args() {
		$settings = $this->get_settings();
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		// Dynamic
		$args = [
			'post_type' => $settings[ 'timeline_post_types' ],
			// 'tax_query' => $this->get_tax_query_args(),
			'post__not_in' => !empty($settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'timeline_post_types' ] ] : '',
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings[ 'order_posts' ],
			'order' => $settings['order_direction'],
			'author' => $author,
			'paged' => $paged,
		];

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}
		
		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'timeline_post_types' ] ];
			}

			$args = [
				'post_type' => $settings[ 'timeline_post_types' ],
				'post__in' => $post_ids,
				'posts_per_page' => $settings['posts_per_page'],
				'orderby' => '',  //  $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		// Get Post Type
		// if ( 'current' === $settings[ 'query_source' ] ) {
		// 	global $wp_query;

		// 	$args = $wp_query->query_vars;
		// 	$args['posts_per_page'] = $settings['query_posts_per_page'];
		// 	$args['orderby'] = $settings['query_randomize'];
		// }

		// Related
		// if ( 'related' === $settings[ 'query_source' ] ) {
		// 	$args = [
		// 		'post_type' => get_post_type( get_the_ID() ),
		// 		'tax_query' => $this->get_tax_query_args(),
		// 		'post__not_in' => [ get_the_ID() ],
		// 		'ignore_sticky_posts' => 1,
		// 		'posts_per_page' => $settings['query_posts_per_page'],
		// 		'orderby' => $settings[ 'query_randomize' ],
		// 		'offset' => $offset,
		// 	];
		// }

		return $args;
	}

	public function get_max_num_pages( $settings ) {
		$query = new \WP_Query( $this->get_main_query_args() );
		$max_num_pages = intval( ceil( $query->max_num_pages ) );

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}	
	
	public $content_alignment = '';

	public function content_and_animation_alignment($layout, $countItem, $settings) {

		if ( $layout != 'one-sided-left' ) {
			$this->content_alignment = "wpr-right-aligned";
		}

		if ( $layout === 'one-sided-left' ) {
			$this->content_alignment = "wpr-left-aligned"; 
		}
		
		if ( $layout == 'centered' ) {

			if ( $countItem % 2 == 0 ) { 
				$this->content_alignment = "wpr-left-aligned";
			}	
			
			if ( preg_match('/right/i', $settings['timeline_animation']) ) {
				if ( 'wpr-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				} else if ( 'wpr-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
			if ( preg_match('/left/i', $settings['timeline_animation']) ) {
				if ( 'wpr-left-aligned' === $this->content_alignment ) {
					$this->animation = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
				} else if ( 'wpr-right-aligned' === $this->content_alignment  ) {
					$this->animation = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
				}
			}
		}

		if ( preg_match('/right/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
		} else if ( preg_match('/left/i', $settings['timeline_animation']) ) {
			$this->animation_loadmore_left = preg_match('/left/i', $settings['timeline_animation']) ? str_replace('left', 'right', $settings['timeline_animation']) : $settings['timeline_animation'];
			$this->animation_loadmore_right = preg_match('/right/i', $settings['timeline_animation']) ? str_replace('right', 'left', $settings['timeline_animation']) : $settings['timeline_animation'];
		}
	}

    public function add_custom_horizontal_timeline_attributes($content, $settings, $index) {

			$this->timeline_description = $content['repeater_description'];
			$this->timeline_year = esc_html($content['repeater_year']);
			$this->story_date_label = esc_html($content['repeater_date_label']);
			$this->story_extra_label = esc_html($content['repeater_extra_label']);
			$this->timeline_story_title = esc_html($content['repeater_story_title']);
			$this->thumbnail_size = $content['wpr_thumbnail_size'];
			$this->thumbnail_custom_dimension = $content['wpr_thumbnail_custom_dimension'];
		              
			$this->show_year_label = esc_html($settings['repeater_show_year_label']);
			$this->timeline_year = esc_html($content['repeater_year']);

			$this->title_key = $this->get_repeater_setting_key( 'repeater_story_title', 'timeline_repeater_list', $index );
			$this->year_key = $this->get_repeater_setting_key( 'repeater_year', 'timeline_repeater_list', $index );
			$this->date_label_key = $this->get_repeater_setting_key( 'repeater_date_label', 'timeline_repeater_list', $index );
			$this->extra_label_key = $this->get_repeater_setting_key( 'repeater_extra_label', 'timeline_repeater_list', $index );
			$this->description_key = $this->get_repeater_setting_key( 'repeater_description', 'timeline_repeater_list', $index );

			$this->background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
			$this->background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
			
			$this->add_inline_editing_attributes( $this->title_key, 'none' );
			$this->add_inline_editing_attributes( $this->year_key, 'none' );
			$this->add_inline_editing_attributes( $this->date_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->extra_label_key, 'none' );
			$this->add_inline_editing_attributes( $this->description_key, 'advanced' );

			$this->add_render_attribute( $this->title_key, ['class'=> 'wpr-title']);
			$this->add_render_attribute( $this->year_key, ['class'=> 'wpr-year-label wpr-year']);
			$this->add_render_attribute( $this->date_label_key, ['class'=> 'wpr-label']);
			$this->add_render_attribute( $this->extra_label_key, ['class'=> 'wpr-sub-label']);
			$this->add_render_attribute( $this->description_key, ['class'=> 'wpr-description']);
                        
    }

	public function render_image_or_icon($content) {					
        if( ( isset($content['repeater_image']['id']) && $content['repeater_image']['id'] != "" ) ) {
            if($this->thumbnail_size == 'custom'){
                $custom_size =   [$this->thumbnail_custom_dimension['width'], $this->thumbnail_custom_dimension['height'] ];
                $this->image= wp_get_attachment_image($content['repeater_image']['id'], $custom_size , true);	
                
            }
            else{
                $this->image= wp_get_attachment_image($content['repeater_image']['id'], $this->thumbnail_size, true);                
            }
        } else if (isset($content['repeater_image']['url']) && $content['repeater_image']['url'] != "") {
            $this->image = '<img src="'.$content['repeater_image']['url'].'">';
        } else if ($content['repeater_timeline_item_icon'] != '') {
            ob_start();
            \Elementor\Icons_Manager::render_icon( $content['repeater_timeline_item_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_image = ob_get_clean();
            $this->image = $icon_image;
        }  else {
            $this->image ='';
        }
	}
	
	public function wpr_render_swiper_pagination($settings) {
		echo '</div>
			<!-- Add Pagination -->        
			<div class="wpr-pagination"></div>
			<!-- Add Arrows -->
			<div class="wpr-button-prev wpr-slider-prev-arrow wpr-slider-prev-'. $this->get_id() .'">
				'. \WprAddons\Classes\Utilities::get_wpr_icon( $settings['swiper_nav_icon'], '' ) .'
			</div>
			<div class="wpr-button-next wpr-slider-next-arrow wpr-slider-next-'. $this->get_id() .'">
				'. \WprAddons\Classes\Utilities::get_wpr_icon( $settings['swiper_nav_icon'], '' ) .'
			</div>
		</div>';
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

	public static function youtube_url ( $story_settings ) {
		if ( $story_settings['repeater_youtube_video_url'] != '' ) {
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $story_settings['repeater_youtube_video_url'], $matches);
              
                if ( isset($matches[1]) ) {
                    $id = $matches[1];
                    $media = '<iframe width="100%" height="auto"
                    src="https://www.youtube.com/embed/' . $id . '" 
                    frameborder="0" allowfullscreen></iframe>';
                }
            } else if ( empty($story_settings['repeater_youtube_video_url']) ) {
				$media = '';
			} else {
                $media = __("Wrong URL","wpr-addons");
            }
			return $media;
	}

	public function render_custom_vertical_timeline($layout, $settings, $data, $countItem ) {
		echo '
		<div class="wpr-wrapper wpr-vertical '. $this->timeline_layout_wrapper .'">
			<div class="wpr-timeline-centered wpr-timeline-sm wpr-line '.$this->timeline_layout.'">';
			echo '<div class="wpr-middle-line"></div>';
			echo 'yes' === $this->timeline_fill ? '<div id="wpr-timeline-fill" class="wpr-timeline-fill" data-layout="'. $layout .'"></div>' : '';
			
			foreach ( $data as $index=>$content ) {
				
				$this->content_and_animation_alignment($layout, $countItem, $settings);
				
				$this->thumbnail_size = $content['wpr_thumbnail_size'];
				$this->thumbnail_custom_dimension = $content['wpr_thumbnail_custom_dimension'];

				$this->show_year_label = esc_html($settings['repeater_show_year_label']);
				$this->timeline_year = esc_html($content['repeater_year']);

				$this->render_image_or_icon($content);

				$background_image = $settings['content_layout'] === 'background' ? $content['repeater_image']['url'] : '';
				$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

				if ( $settings['repeater_show_year_label'] == 'yes' ) {
					echo '<span class="wpr-year-wrap">
					<span class="wpr-year-label wpr-year">'.$content['repeater_year'].'</span>
					</span>';
				}
				
				echo '<article class="wpr-timeline-entry '. $this->content_alignment .' elementor-repeater-item-'. $content['_id'] .'" data-item-id="elementor-repeater-item-' . $content['_id'] . '">
					<div class="wpr-timeline-entry-inner">';
					if ( 'yes' === $settings['show_extra_label'] ) {
						echo '<time class="wpr-extra-label" data-animation-offset="'. $settings['animation_offset'] .'" data-aos="'.$this->animation.'" data-aos-left="'.$this->animation_loadmore_left .'" data-aos-right="'. $this->animation_loadmore_right .'" data-animation-offset="'. $settings['animation_offset'] .'" data-animation-duration="'. $settings['aos_animation_duration'] .'">';
							echo '<span class="wpr-label">'. $content['repeater_date_label'] .'</span>';
							echo '<span class="wpr-sub-label">' . $content['repeater_extra_label'] .'</span>';
						echo '</time>';
					}

						echo '<div class="wpr-icon-border-color wpr-icon" data-bullet-border-color="'. $this->bullet_border_color .'" data-bullet-bg-color="'. $settings['icon_bgcolor'] .'">';
							\Elementor\Icons_Manager::render_icon( $content['repeater_story_icon'], [ 'aria-hidden' => 'true' ] );
						echo '</div>';

						echo '<div class="wpr-story-info-vertical wpr-data-wrap '. $background_class .'"  data-aos="'.$this->animation.'" data-aos-left="'.$this->animation_loadmore_left .'" data-aos-right="'. $this->animation_loadmore_right .'" data-animation-offset="'. $settings['animation_offset'] .'" data-animation-duration="'. $settings['aos_animation_duration'] .'"  style="background-image: url('. $background_image .')">';

							echo $settings['content_layout'] === 'image-top' || $settings['show_overlay'] === 'yes' ? '<div class="wpr-animation-wrap wpr-timeline-img" style="position: relative;">' .$this->image : '';

							echo !empty($content['repeater_youtube_video_url'] ) && $settings['content_layout'] === 'image-top' ? '<div> '. $this->youtube_url($content) .' </div>' : '';

								echo $settings['show_overlay'] === 'yes' && !empty($this->image) ? '<div class="wpr-timeline-story-overlay '. $this->animation_class .'">' : '';

									echo 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. esc_url($content['repeater_title_link']) .'" target="_blank"><span class="wpr-title '. $this->animation_class .'">'. $content['repeater_story_title'] .'</span></a>' : '';
									echo 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="wpr-description">'. $content['repeater_description'] .'</div>' : '';

								echo $settings['show_overlay'] === 'yes' && !empty($content['repeater_image']['url']) ? '</div>' : '';

							echo $settings['content_layout'] === 'image-top' || $settings['show_overlay'] === 'yes' ? '</div>' : '';

							echo 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. esc_url($content['repeater_title_link']) .'" target="_blank"><span class="wpr-title">'. $content['repeater_story_title'] .'</span></a>' : '';

							echo 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ? '<div class="wpr-description">'. $content['repeater_description'] .'</div>' : '';

							echo !empty($content['repeater_youtube_video_url'] ) && $settings['content_layout'] !== 'image-top' ? '<div> '. $this->youtube_url($content) .' </div>' : '';

							echo $settings['content_layout'] === 'image-bottom' ? $this->image : '';

						echo '</div>
						</div>
				</article>';
						
				$countItem = $countItem +1;
				
			}
			echo'</div>    
			</div>';

	} // end of render_custom_vertical_timeline

	public function render_dynamic_vertical_timeline($settings, $my_query, $arrow_bgcolor, $layout, $countItem, $paged ) {
				$layout_settings = [
					'pagination_type' => $settings['pagination_type'],
				];

				$this->add_render_attribute( 'grid-settings', [
					'data-settings' => wp_json_encode( $layout_settings ),
				] );

				wp_reset_postdata();
				
				if(!$my_query->have_posts()) {
					echo '<div>'. $settings['query_not_found_text'] .'</div>';
				}

				if ( $my_query->have_posts() ) { 
					echo '<div class="wpr-wrapper wpr-vertical '. $this->timeline_layout_wrapper .'">';
					echo '<div class="wpr-timeline-centered wpr-timeline-sm wpr-line '. $this->timeline_layout .'"  data-pagination="'. $this->pagination_type .'" data-max-pages="'. $this->pagination_max_pages .'" data-arrow-bgcolor="'. $arrow_bgcolor .'">';
					echo '<div class="wpr-middle-line"></div>';
					echo 'yes' === $this->timeline_fill ? '<div id="wpr-timeline-fill" class="wpr-timeline-fill" data-layout="'. $layout .'"></div>' : '';

				while ( $my_query->have_posts() ) {
					global $wp_query;
					$counter = $wp_query->current_post++;
					$my_query->the_post();
					ob_start();
					the_post_thumbnail();
					$this->image = ob_get_clean();

					$this->content_and_animation_alignment($layout, $countItem, $settings);
				
					$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
					$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';

					echo '<article class="wpr-timeline-entry '. $this->content_alignment .'" data-counter="'. $countItem .'">
						<div class="wpr-timeline-entry-inner">';
						if ( 'yes' === $settings['show_extra_label'] ) {
							echo '<time class="wpr-extra-label" data-aos="'. $this->animation.'" data-animation-offset="'. $settings['animation_offset'] .'">
								<span class="wpr-label">'. get_the_date($settings['date_format']) .'</span>
							</time>';
						}

							echo '<div class="wpr-icon-border-color wpr-icon" data-bullet-border-color="'. $this->bullet_border_color .'" data-bullet-bg-color="'. $settings['icon_bgcolor'] .'">';
							\Elementor\Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
							echo '<div class="wpr-story-info-vertical wpr-data-wrap animated '. $background_class .'" data-aos="'. $this->animation.'" data-aos-left="'. $this->animation_loadmore_left .'" data-aos-right="'. $this->animation_loadmore_right .'" data-animation-offset="'. $settings['animation_offset'] .'" data-animation-duration="'. $settings['aos_animation_duration'] .'" data-animation-once="'. $settings['animation_loop_or_once'] .'" style="background-image: url('.$background_image .')">';

							echo 'image-top' === $settings['content_layout'] || 'yes' === $settings['show_overlay'] ? '<div class="wpr-animation-wrap wpr-timeline-img" style="position: relative;">'.$this->image.'' : '';

							
								echo $settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url()) ? '<div class="wpr-timeline-story-overlay '. $this->animation_class .'">' : '';

									echo  'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. get_the_permalink() .'" target="_blank"><span class="wpr-title">'. get_the_title() .'</span></a>' : '';

									echo 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ? '<div class="wpr-inner-date-label">
										'. get_the_date($settings['date_format']) .'
									</div>' : '';
									
									echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="wpr-description">'.wp_trim_words(get_the_content(), $settings['excerpt_count']).'</div>' : '';
									
									echo 'yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay'] ? '<div class="wpr-read-more-wrap"><a class="wpr-read-more-button" href="'. get_the_permalink() .'">'. $settings['read_more_text'] .'</a></div>' : '';

								echo $settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url()) ? '</div>' : '';
									
							echo $settings['content_layout'] === 'image-top' || $settings['show_overlay'] === 'yes' ? '</div>' : '';

									echo  'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. get_the_permalink() .'" target="_blank"><span class="wpr-title">'. get_the_title() .'</span></a>' : '';

									echo 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ? '<div class="wpr-inner-date-label">
										'. get_the_date($settings['date_format']) .'
									</div>' : '';

									echo !empty(get_the_content()) && 'yes' === $settings['show_description']  && 'yes' !== $settings['description_overlay'] ? '<div class="wpr-description">'.wp_trim_words(get_the_content(), $settings['excerpt_count']).'</div>' : '';

									echo 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '<div class="wpr-read-more-wrap"><a class="wpr-read-more-button" href="'. get_the_permalink() .'">'. $settings['read_more_text'] .'</a></div>' : '';

								echo $settings['content_layout'] === 'image-bottom' ? '<div class="wpr-animation-wrap wpr-timeline-img"><img src="'.the_post_thumbnail().'"</div>' : '';

							echo '</div>';
					echo '</div>';
					echo '</article>';	

					$countItem++;
			}
			
			echo'</div>';  
			echo '</div>';

			if ( 'yes' === $settings['show_pagination'] ) {

			echo '<div>';
			echo '<div class="wpr-grid-pagination wpr-pagination-load-more">';

			echo '<div class="wpr-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="wpr-double-bounce">';
							echo '<div class="wpr-child wpr-double-bounce1"></div>';
							echo '<div class="wpr-child wpr-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="wpr-wave">';
							echo '<div class="wpr-rect wpr-rect1"></div>';
							echo '<div class="wpr-rect wpr-rect2"></div>';
							echo '<div class="wpr-rect wpr-rect3"></div>';
							echo '<div class="wpr-rect wpr-rect4"></div>';
							echo '<div class="wpr-rect wpr-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="wpr-spinner wpr-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="wpr-chasing-dots">';
							echo '<div class="wpr-child wpr-dot1"></div>';
							echo '<div class="wpr-child wpr-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="wpr-three-bounce">';
							echo '<div class="wpr-child wpr-bounce1"></div>';
							echo '<div class="wpr-child wpr-bounce2"></div>';
							echo '<div class="wpr-child wpr-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="wpr-fading-circle">';
							echo '<div class="wpr-circle wpr-circle1"></div>';
							echo '<div class="wpr-circle wpr-circle2"></div>';
							echo '<div class="wpr-circle wpr-circle3"></div>';
							echo '<div class="wpr-circle wpr-circle4"></div>';
							echo '<div class="wpr-circle wpr-circle5"></div>';
							echo '<div class="wpr-circle wpr-circle6"></div>';
							echo '<div class="wpr-circle wpr-circle7"></div>';
							echo '<div class="wpr-circle wpr-circle8"></div>';
							echo '<div class="wpr-circle wpr-circle9"></div>';
							echo '<div class="wpr-circle wpr-circle10"></div>';
							echo '<div class="wpr-circle wpr-circle11"></div>';
							echo '<div class="wpr-circle wpr-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="wpr-pagination-finish">'. $settings['pagination_finish_text'] .'</p>';
				echo '<a href="'. get_pagenum_link( $paged + 1, true ) .'" class="wpr-load-more-btn button">';
				echo $settings['pagination_load_more_text'];
				echo '</a>';
			echo '</div>';
			echo '</div>';

			}
		}
	} // end rendern_dynamic_vertical_timeline

	public function render_custom_horizontal_timeline( $settings, $autoplay, $dir, $data, $slidesHeight, $swiper_speed, $swiper_delay ) {
		$sidesToShow = isset($settings['slides_to_show'])
		&& !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : 2;

		if ( $settings['timeline_layout'] == 'horizontal' ) {
			$horizontal_class = 'wpr-horizontal-wrapper';
		} else if ( $settings['timeline_layout'] == 'horizontal-bottom' ) {
			$horizontal_class = 'wpr-horizontal-bottom-wrapper';
		}

        $horizontal_inner_class = $horizontal_class == 'wpr-horizontal-wrapper' ? 'wpr-horizontal' : 'wpr-horizontal-bottom';

        $horizontal_timeline_class = $horizontal_inner_class == 'wpr-horizontal' ? 'wpr-horizontal-timeline' : 'wpr-horizontal-bottom-timeline';

        $swiper_class = $horizontal_timeline_class === 'wpr-horizontal-timeline' ? 'swiper-slide-line-bottom' : 'swiper-slide-line-top';

		echo '<div id="'.$horizontal_class.'" class="wpr-wrapper swiper-container '.$horizontal_inner_class.'" dir="'. $dir .'" data-slidestoshow = "'.esc_attr($sidesToShow).'" data-autoplay="'.esc_attr($autoplay).'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-space-between="'. esc_attr($settings['story_info_gutter']) .'">';

		echo '<div class="swiper-wrapper '. $horizontal_timeline_class .'">';
			if ( is_array($data) ) {
					foreach($data as $index=>$content) {

						$this->add_custom_horizontal_timeline_attributes($content, $settings, $index);
				
						$this->thumbnail_custom_dimension = $content['wpr_thumbnail_custom_dimension'];

						$this->render_image_or_icon($content);

						echo '<div class="swiper-slide '. $swiper_class .' '.esc_attr($slidesHeight).' elementor-repeater-item-'. $content['_id'] .'">';

							if ( 'yes' === $settings['show_extra_label'] ) {
								echo '<div class="wpr-extra-label" >
									<span '.$this->get_render_attribute_string( $this->date_label_key ).' >'. $this->story_date_label .'</span> 
									<span '.$this->get_render_attribute_string( $this->extra_label_key ).' >'. $this->story_extra_label .'</span>
								</div>';
							}

							echo '<div class="wpr-icon-border-color wpr-icon">';
							\Elementor\Icons_Manager::render_icon( $content['repeater_story_icon'], [ 'aria-hidden' => 'true' ] );
							echo'</div>'; 
							echo '<div class="wpr-story-info '. $this->background_class .'" style="background-image: url('.$this->background_image .')">';
								echo '<div class="wpr-animation-wrap wpr-timeline-img" style="position: relative;">';

									echo 'image-top' === $settings['content_layout'] ? $this->image : '';

									echo !empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] == 'image-top' ? '<div> '. $this->youtube_url($content) .' </div>' : ''; 

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) ? '<div class="wpr-timeline-story-overlay '. $this->animation_class .'">' : '';

										echo 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. esc_url($content['repeater_title_link']) .'" target="_blank"><span '.$this->get_render_attribute_string( $this->title_key ) .'>'. $this->timeline_story_title .'</span></a>' : '';

										echo 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div '.$this->get_render_attribute_string( $this->description_key ).'>'. $this->timeline_description .'</div>' : '';

									echo 'yes' === $settings['show_overlay'] && !empty($this->image) ? '</div>' : '';
								
								echo '</div>'; 
									
									echo 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. esc_url($content['repeater_title_link']) .'" target="_blank"><span '.$this->get_render_attribute_string( $this->title_key ).'>'.$this->timeline_story_title.'</span></a>' : '';

									echo 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ?'<div '.$this->get_render_attribute_string( $this->description_key ) .'>'. $this->timeline_description .'</div>' : ''; 

									echo !empty( $content['repeater_youtube_video_url'] ) && $settings['content_layout'] == 'image-bottom' ? '<div> '. $this->youtube_url($content) .' </div>' : ''; 

									echo 'image-bottom' === $settings['content_layout'] ? $this->image : '';              
							echo '</div>';
						echo '</div>';
							
						// if ( $settings['repeater_show_year_label'] == 'yes' ) {
						// 	echo '<span class="wpr-year-wrap">
						// 	<span class="wpr-year-label wpr-year">'.$content['repeater_year'].'</span>
						// 	</span>';
						// }
					}
				} 
				
				$this->wpr_render_swiper_pagination($settings);
	}
	
	public function render_dynamic_horizontal_timeline ( $settings, $my_query, $dir, $autoplay, $slidesHeight, $swiper_speed, $swiper_delay ) {
		wp_reset_postdata();
		$sidesToShow = isset($settings['slides_to_show'])
		&& !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : 2;
	
		if ( $settings['timeline_layout'] == 'horizontal' ) {
			$horizontal_class = 'wpr-horizontal-wrapper';
		} else if ( $settings['timeline_layout'] == 'horizontal-bottom' ) {
			$horizontal_class = 'wpr-horizontal-bottom-wrapper';
		}
	
		$horizontal_inner_class = $horizontal_class == 'wpr-horizontal-wrapper' ? 'wpr-horizontal' : 'wpr-horizontal-bottom';
	
		$horizontal_timeline_class = $horizontal_inner_class == 'wpr-horizontal' ? 'wpr-horizontal-timeline' : 'wpr-horizontal-bottom-timeline';
	
		$swiper_class = $horizontal_timeline_class === 'wpr-horizontal-timeline' ? 'swiper-slide-line-bottom' : 'swiper-slide-line-top';
	
		if(!$my_query->have_posts()) {
			echo '<div>'. $settings['query_not_found_text'] .'</div>';
		}
	
		if( $my_query->have_posts() ) { 
				echo '<div id="'.$horizontal_class.'" class="wpr-wrapper swiper-container '. $horizontal_inner_class .'" dir="'. $dir .'" data-slidestoshow = "'.esc_attr($sidesToShow).'" data-autoplay="'.esc_attr($autoplay).'" data-swiper-speed="'. esc_attr($swiper_speed) .'" data-swiper-delay="'. esc_attr($swiper_delay) .'" data-swiper-space-between="'.$settings['story_info_gutter'].'">
					<div class="'.$horizontal_timeline_class.' swiper-wrapper">';
					while( $my_query->have_posts() ) {
						$my_query->the_post();
						
						$background_image = $settings['content_layout'] === 'background' ? get_the_post_thumbnail_url() : '';
						$background_class = $settings['content_layout'] === 'background' ? 'story-with-background' : '';
						
					echo '<div id="" class="swiper-slide  '.$swiper_class.'  '. esc_attr($slidesHeight) .'">';
						// TODO: apply animation class to other layouts as well
						echo '<div class="wpr-story-info '. $background_class .'" style="background-image: url('. $background_image .')">';
						echo $settings['content_layout'] === 'image-top' || $settings['show_overlay'] === 'yes' ? '<div class="wpr-animation-wrap wpr-timeline-img" style="position: relative;"><img src="'. get_the_post_thumbnail_url() .'">' : '';
	
						echo $settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url()) ? '<div class="wpr-timeline-story-overlay '. $this->animation_class .'">' : '';
	
							echo 'yes' === $settings['show_title'] && 'yes' === $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. get_the_permalink() .'" target="_blank"><span class="wpr-title">'. get_the_title() .'</span></a>' : '';
	
							echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' === $settings['description_overlay'] ? '<div class="wpr-description">'.wp_trim_words(get_the_content(), $settings['excerpt_count']).'</div>' : '';
	
							echo 'yes' === $settings['show_date'] && 'yes' === $settings['date_overlay'] ? '<div class="wpr-inner-date-label">
							'. get_the_date($settings['date_format']) .'
							</div>' : '';
							
							echo 'yes' === $this->show_readmore && 'yes' === $settings['readmore_overlay'] ? '<div class="wpr-read-more-wrap"><a class="wpr-read-more-button" href="'. get_the_permalink() .'">'. $settings['read_more_text'] .'</a></div>' : '';
	
						echo $settings['show_overlay'] === 'yes' && !empty(get_the_post_thumbnail_url()) ? '</div>' : '';
						echo $settings['content_layout'] === 'image-top' || $settings['show_overlay'] === 'yes' ? '</div>' : '';
	
						echo 'yes' === $settings['show_title'] && 'yes' !== $settings['title_overlay'] ? '<a class="wpr-title-wrap" href="'. get_the_permalink() .'" target="_blank"><span class="wpr-title">'. get_the_title() .'</span></a>' : '';
	
						echo !empty(get_the_content()) && 'yes' === $settings['show_description'] && 'yes' !== $settings['description_overlay'] ? '<div class="wpr-description">'.wp_trim_words(get_the_content(), $settings['excerpt_count']).'</div>' : '';
	
						echo 'yes' === $settings['show_date'] && 'yes' !== $settings['date_overlay'] ? '<div class="wpr-inner-date-label">
						'. get_the_date($settings['date_format']) .'
						</div>' : '';
	
						echo 'yes' === $this->show_readmore && 'yes' !== $settings['readmore_overlay'] ? '<div class="wpr-read-more-wrap"><a class="wpr-read-more-button" href="'. get_the_permalink() .'">'. $settings['read_more_text'] .'</a></div>' : '';
	
						echo $settings['content_layout'] === 'image-bottom' ? '<div class="wpr-animation-wrap wpr-timeline-img"><img src="'.the_post_thumbnail().'"></div>' : '';
						echo '</div>';
	
						if ( 'yes' === $settings['show_extra_label'] ) {	
							echo '<div class="wpr-extra-label">
								<span class="wpr-label">
								'. get_the_date($settings['date_format']) .'
								</span>
							</div>';
						}
	
						echo '<div class="wpr-icon-border-color wpr-icon">';
							\Elementor\Icons_Manager::render_icon( $settings['posts_icon'], [ 'aria-hidden' => 'true' ] );
						echo'</div>'; 
					echo '</div>';
				}
	
				$this->wpr_render_swiper_pagination($settings);
			}
	
			
			// <span class="wpr-label">'. get_the_date('Y') .'</span> 
			// <span class="wpr-sub-label">'. get_the_date('M ') .'</span>
	
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		global $paged;
		$paged = 1;
		$my_query = 'dynamic' === $settings['timeline_content'] ? new \WP_Query ($this->get_main_query_args()) : '';
		
		$layout = $settings['timeline_layout'];
		$this->animation= $settings['timeline_animation'];
		$this->animation_loadmore_left = '';
		$this->animation_loadmore_right = '';
		$this->timeline_fill = $settings['timeline_fill'];
		$this->show_readmore = !empty($settings['show_readmore']) ? $settings['show_readmore'] : '';
		$data = $settings['timeline_repeater_list'];
		$autoplay = $settings['swiper_autoplay'];
		$swiper_speed = $settings['swiper_speed'];
		$swiper_delay = $settings['swiper_delay'];
		$slidesHeight = $settings['slides_height'];
		$this->pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : '';
		$this->pagination_max_pages = !empty($this->get_max_num_pages( $settings )) ? $this->get_max_num_pages( $settings ) : '';
		$this->bullet_border_color = $settings['icon_border_color'];
		$arrow_bgcolor = $settings['triangle_bgcolor'];

		$animation_settings = [	
			'overlay_animation' => $settings['overlay_animation'], 
			'overlay_animation_size' => $settings['overlay_animation_size'],
			'overlay_animation_timing' => $settings['overlay_animation_timing'],
			'overlay_animation_tr' => $settings['overlay_animation_tr'],
		];

		$this->animation_class = $this->get_animation_class( $animation_settings, 'overlay' );
		
		$isRTL = is_rtl();
		$dir = '';
		if($isRTL){
			$dir = 'rtl';
		}

			if ( 'one-sided' === $layout ){
				$this->timeline_layout = "wpr-one-sided-timeline";
				$this->timeline_layout_wrapper = "wpr-one-sided-wrapper";
			} else if ( 'centered' === $layout) {
				$this->timeline_layout = 'wpr-both-sided-timeline';
				$this->timeline_layout_wrapper = 'wpr-centered';
			} else if ( 'one-sided-left' === $layout ) {
				$this->timeline_layout = "wpr-one-sided-timeline-left";
				$this->timeline_layout_wrapper = "wpr-one-sided-wrapper-left";
			} else if ( 'horizontal' === $layout ) {
				$this->timeline_layout = "wpr-horizontal-timeline";
				$this->timeline_layout_wrapper = "wpr-horizontal-wrapper";
			}

			$countItem = !empty($countItem) ? $countItem : 0;

			if ( 'dynamic' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_dynamic_horizontal_timeline ( $settings, $my_query, $dir, $autoplay, $slidesHeight, $swiper_speed, $swiper_delay );


			} else if ( 'custom' === $settings['timeline_content'] && ('horizontal' === $layout || 'horizontal-bottom' === $layout) ) {

					$this->render_custom_horizontal_timeline( $settings, $autoplay, $dir, $data, $slidesHeight,  $swiper_speed, $swiper_delay );

			} else {
				if( 'dynamic' === $settings['timeline_content'] ) {

					$this->render_dynamic_vertical_timeline($settings, $my_query, $arrow_bgcolor, $layout, $countItem, $paged );

				} else {

					$this->render_custom_vertical_timeline($layout, $settings, $data, $countItem );

				}
			}
	}

	//todo: can content_timeline be used on dynamic query content?
}

new Wpr_PostsTimeline();