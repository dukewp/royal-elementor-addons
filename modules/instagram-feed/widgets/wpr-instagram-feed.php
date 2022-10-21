<?php
namespace WprAddons\Modules\InstagramFeed\Widgets;

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
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Instagram_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-instagram-feed';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-instagram-post';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'instagram feed' ];
	}

	public function get_script_depends() {
		return [ 'wpr-instafeed', 'wpr-lightgallery' ];
	}

	public function get_style_depends() {
		return ['wpr-animations-css', 'wpr-lightgallery-css'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }
	
	public function add_control_overlay_animation_divider() {
		$this->add_control(
			'overlay_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
	}
	
	public function add_control_overlay_image() {
		$this->add_control(
			'overlay_image',
			[
				'label' => esc_html__( 'Upload GIF', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}
	
	public function add_control_overlay_image_width() {
		$this->add_control(
			'overlay_image_width',
			[
				'label' => esc_html__( 'GIF Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 70,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg img' => 'max-width: {{SIZE}}px;',
				],
			]
		);
	}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'zoom-in' => esc_html__( 'Zoom In', 'wpr-addons' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'wpr-addons' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'wpr-addons' ),
					'grayscale-out' => esc_html__( 'Grayscale Out', 'wpr-addons' ),
					'blur-in' => esc_html__( 'Blur In', 'wpr-addons' ),
					'blur-out' => esc_html__( 'Blur Out', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_overlay_color() {
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
				'selector' => '{{WRAPPER}} .wpr-insta-feed-media-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-insta-feed-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_overlay_border_color() {
		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_border_type() {
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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);
	}
	
	public function add_control_overlay_border_width() {
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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);
	}

	public function add_option_element_select() {
		return [
			'caption' => esc_html__( 'Caption', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'icon' => esc_html__( 'Icon', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'pro-shr' => esc_html__( 'Sharing (Pro)', 'wpr-addons' )
		];
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}
	
	public function add_control_stack_insta_feed_slider_nav_position() {}
	
	public function add_control_insta_feed_slider_dots_hr() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ------------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
		
		if ( '' == get_option('wpr_instagram_access_token') ) {
			$this->add_control(
				'instagram_access_token_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Please enter <strong>Instagram Access Token</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'show_instagram_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'center_instagram_follow_button',
			[
				'label' => esc_html__( 'Center Follow Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'wpr-if-cfb-',
				'label_block' => false,
				'condition' => [
					'show_instagram_follow_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'instagram_follow_text',
			[
				'label' => esc_html__( 'Follow Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Follow on Instagram',
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_link',
			[
				'label' => esc_html__( 'Follow Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'wpr_insta_feed_layout',
			[
				'label'              => esc_html__('Select Layout', 'wpr-addons'),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'default'            => 'layout-masonry',
				'prefix_class' => 'wpr-insta-feed-'	,
				'render_type' => 'template',
				'separator' => 'before',
				'options'            => [
					'layout-grid'     => esc_html__('Grid', 'wpr-addons'),
					'layout-masonry' => esc_html__('Masonry', 'wpr-addons'),
					'layout-list'     => esc_html__('List Style', 'wpr-addons'),
					'layout-carousel' => esc_html__('Slider\Carousel', 'wpr-addons'),
				],
			]
		);

		$this->add_control(
			'wpr_insta_feed_column_grid',
			[
				'label'              => esc_html__('Columns Grid', 'wpr-addons'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'wpr-insta-col-4',
				'options'            => [
					'wpr-insta-col-auto' => esc_html__('1 Columns', 'wpr-addons'),
					'wpr-insta-col-6'    => esc_html__('2 Columns', 'wpr-addons'),
					'wpr-insta-col-4'    => esc_html__('3 Columns', 'wpr-addons'),
					'wpr-insta-col-3'    => esc_html__('4 Columns', 'wpr-addons'),
					'wpr-insta-col-2-point-4'    => esc_html__('5 Columns', 'wpr-addons'),
					'wpr-insta-col-2'    => esc_html__('6 Columns', 'wpr-addons')
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-masonry'
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '33.3%',
				'options' => [
					'100%' => esc_html__( 'One', 'wpr-addons' ),
					'50%' => esc_html__( 'Two', 'wpr-addons' ),
					'33.3%' => esc_html__( 'Three', 'wpr-addons' ),
					'25%' => esc_html__( 'Four', 'wpr-addons' ),
					'20%' => esc_html__( 'Five', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'width: {{VALUE}}',
					'{{WRAPPER}} .wpr-layout-list .wpr-insta-feed-content-wrap' => 'width: {{VALUE}}'
				],
				'condition' => [
					'wpr_insta_feed_layout' => ['layout-grid', 'layout-list']
				],
			]
		);

		$this->add_responsive_control(
			'gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-masonry' => 'column-gap: {{SIZE}}px;',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=widescreen] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_widescreen.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=widescreen] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_widescreen.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=laptop] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_laptop.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=laptop] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_laptop.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=tablet_extra] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_tablet_extra.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=tablet_extra] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_tablet_extra.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_tablet.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_tablet.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=mobile_extra] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_mobile_extra.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=mobile_extra] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_mobile_extra.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_mobile.VALUE}} - (2 * {{SIZE}}px));',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list .wpr-insta-feed-content-wrap' => 'margin: {{SIZE}}px; width: calc({{columns_mobile.VALUE}} - (2 * {{SIZE}}px));',
				]
			]
		);

		$this->add_control(
			'distance_bottom',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.wpr-if-cfb-yes) .wpr-instagram-feed' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'margin-bottom: {{SIZE}}px',
				]
			]
		);

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'zigzag' => esc_html__( 'ZigZag', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-insta-feed-list-',
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-list'
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-layout-list .wpr-insta-feed-media-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance.SIZE}}{{layout_list_media_distance.UNIT}});',
					'body[data-elementor-device-mode=widescreen] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_widescreen.SIZE}}{{layout_list_media_distance_widescreen.UNIT}});',
					'body[data-elementor-device-mode=laptop] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_laptop.SIZE}}{{layout_list_media_distance_laptop.UNIT}});',
					'body[data-elementor-device-mode=tablet_extra] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet_extra.SIZE}}{{layout_list_media_distance_tablet_extra.UNIT}});',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet.SIZE}}{{layout_list_media_distance_tablet.UNIT}});',
					'body[data-elementor-device-mode=mobile_extra] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile_extra.SIZE}}{{layout_list_media_distance_mobile_extra.UNIT}});',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile.SIZE}}{{layout_list_media_distance_mobile.UNIT}});'
				],
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-list-left .wpr-layout-list .wpr-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-right .wpr-layout-list .wpr-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-zigzag .wpr-layout-list .wpr-insta-feed-content-wrap:nth-child(odd) .wpr-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-zigzag .wpr-layout-list .wpr-insta-feed-content-wrap:nth-child(even) .wpr-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-list',
				]
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_insta_feed_slider',
			[
                'label' => esc_html__('Slider Temporary', 'wpr-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'wpr_insta_feed_layout' => 'layout-carousel',
				]
            ]
		);

        $this->add_control(
            'section_insta_feed_slider_update',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );

		$this->add_control (
			'force_square_images',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Square Images', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'prefix_class' => 'wpr-if-square-images-'
			]
		);

		$this->add_control (
			'enable_cs_nav',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wpr_cs_nav_arrows',
			[
				'label' => esc_html__( 'Navigation Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
				],
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'wpr_cs_nav_tabs',
			[
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->start_controls_tab(
			'wpr_cs_nav_tab_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'wpr_cs_nav_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => 'fill: {{VALUE}}'
				],
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control(
			'wpr_cs_nav_icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'wpr_cs_nav_icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev, {{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next',
			]
		);

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'wpr_cs_nav_tab_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'wpr_cs_nav_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'wpr_cs_nav_icon_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'wpr_cs_nav_icon_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover' => 'border-color: {{VALUE}}',
				]
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
		
		$this->add_responsive_control(
			'wpr_cs_nav_icon_size',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);
		
		$this->add_responsive_control(
			'wpr_cs_nav_icon_bg_size',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control(
			'wpr_cs_nav_border',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'wpr_cs_nav_border_width',
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
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_cs_nav' => 'yes',
					'wpr_cs_nav_border!' => 'none'
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control (
			'wpr_enable_cs_pag',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wpr_cs_pag_type',
			[
				'label' => esc_html__( 'Pagination Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'wpr-addons' ),
					'fraction' => esc_html__( 'Fraction', 'wpr-addons' ),
					'progressbar' => esc_html__( 'Progressbar', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
				]
			]
		);

		$this->add_control(
			'wpr_cs_pag_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-fraction' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
				]
			]
		);

		$this->add_control(
			'wpr_cs_pag_bg_color',
			[
				'label'  => esc_html__( 'Bar Background', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000040',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
					'wpr_cs_pag_type' => 'progressbar'
				]
			]
		);
		
		$this->add_responsive_control(
			'wpr_cs_pag_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
					'wpr_cs_pag_type' => 'bullets'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wpr_cs_pag_fraction_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-fraction',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
					'wpr_cs_pag_type' => 'fraction'
				]
			]
		);

		$this->add_responsive_control(
			'wpr_cs_pag_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 6,
					'bottom' => 0,
					'left' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wpr_enable_cs_pag' => 'yes',
					'wpr_cs_pag_type' => 'bullets'
				]
			]
		);

		$this->add_control (
			'wpr_enable_insta_feed_slider_autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
				
		$this->add_control(
			'wpr_insta_feed_delay',
			[
				'label' => __( 'Delay', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'wpr_enable_insta_feed_slider_autoplay' => 'yes'
				]
			]
		);

		$this->add_control (
			'wpr_enable_insta_feed_slider_loop',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wpr_insta_feed_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1
			]
		);
				
		$this->add_responsive_control(
			'wpr_insta_feed_space_between',
			[
				'label' => __( 'Gutter', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5
			]
		);
				
		$this->add_control(
			'wpr_insta_feed_speed',
			[
				'label' => __( 'Speed', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500
			]
		);
	
		$this->add_control_stack_insta_feed_slider_nav_position();
		
		$this->add_control_insta_feed_slider_dots_hr();

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_feed_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this_select,
				'default' => 'caption',
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_select', ['pro-lk', 'pro-shr'] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'wpr-addons' ),
					'over' => esc_html__( 'Over Media', 'wpr-addons' ),
					'below' => esc_html__( 'Below Media', 'wpr-addons' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Seperate Line', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Width', 'wpr-addons' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'wpr-addons' ),
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
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-instagram-feed-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'element_location' => 'over',
					],
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
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
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_title_tag',
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
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'element_select' => 'title',
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'caption' ],
				]
			]
		);

		$repeater->add_control(
			'element_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'author' ]
				]
			]
		);

		$repeater->add_control(
			'element_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 16,
				'min' => 8,
				'condition' => [
					'element_select' => [ 'author' ],
					'element_show_avatar' => 'yes'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'date',
						'time',
						'author',
						'comments',
						'read-more',
						'likes',
						'sharing',
						'lightbox',
						'custom-field',
						'separator',
						'post_format',
					],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control(
		// 	'element_comments_text_1',
		// 	[
		// 		'label' => esc_html__( 'No Comments', 'wpr-addons' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => 'No Comments',
		// 		'condition' => [
		// 			'element_select' => [ 'comments' ],
		// 		]
		// 	]
		// );

		$repeater->add_control(
			'element_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Comment',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		// $repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		// $repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control(
			'element_separator_style',
			[
				'label' => esc_html__( 'Select Styling', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wpr-insta-feed-sep-style-1',
				'options' => [
					'wpr-insta-feed-sep-style-1' => esc_html__( 'Separator Style 1', 'wpr-addons' ),
					'wpr-insta-feed-sep-style-2' => esc_html__( 'Separator Style 2', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'separator',
				]
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'element_animation_size',
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
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'insta_feed_elements',
			[
				'label' => esc_html__( 'Feed Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'date',
						'element_display' => 'inline',
						'element_extra_text_pos' => 'after',
						'element_extra_text' => '/',
						'element_location' => 'above',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'icon',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'right'
					],
					[
						'element_select' => 'caption',
						'element_location' => 'below',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Single Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-insta-feed-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'overlay_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

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

		$this->add_control_overlay_animation_divider();

		$this->add_control_overlay_image();

		$this->add_control_overlay_image_width();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_image_effects();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'image_effects_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control_lightbox_popup_thumbnails();

		$this->add_control_lightbox_popup_thumbnails_default();

		$this->add_control_lightbox_popup_sharing();

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_description',
			[
				'raw' => sprintf(__( 'You can change Lightbox Popup styling options globaly. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'wpr-addons' ), Utilities::get_plugin_name()),
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Styles ===============
		// Section: Feed -----------
		$this->start_controls_section(
			'section_style_feed',
			[
				'label' => esc_html__( 'Feed', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feed_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'feed_item_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-content-wrap',
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
                                'blur' => 3,
                                'spread' => 0,
                                'color' => '#22222266'
                            ]
                    ]
				]
			]
		);

		$this->add_control(
			'feed_item_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feed_item_border_width',
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
					'{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feed_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'feed_item_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feed_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template'
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
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Caption ----------
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_dropcap_color',
			[
				'label'  => esc_html__( 'DropCap Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3a3a3a',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption.wpr-enable-dropcap p:first-child:first-letter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-caption'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_dropcap_typography',
				'label' => esc_html__( 'Drop Cap Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-caption.wpr-enable-dropcap p:first-child:first-letter'
			]
		);

		$this->add_responsive_control(
			'caption_justify',
			[
				'label' => esc_html__( 'Justify Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'caption_width',
			[
				'label' => esc_html__( 'Caption Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
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
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_width',
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
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'caption_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
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
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
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
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block span[class*="wpr-insta-feed-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block i[class*="wpr-insta-feed-extra-icon"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-date'
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
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
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
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'date_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

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
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
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
					'top' => 0,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Icon ---------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_icon_style' );

		$this->start_controls_tab(
			'tab_insta_feed_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-icon i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-icon'
			]
		);

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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'icon_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-icon .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_padding',
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_margin',
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
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_radius',
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Lightbox ---------
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_lightbox_style' );

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lightbox_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'lightbox_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-lightbox'
			]
		);

		$this->add_control(
			'lightbox_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'lightbox_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .wpr-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .wpr-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
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
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'background-color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'background-color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn:hover',
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
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public static function call_instagram_api($access_token) {

		$url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url,permalink,username,caption,timestamp&access_token=' . $access_token;
		$response = wp_remote_get($url);
		$body = json_decode($response['body']);
		if(!isset($body)) {
			return $response['body'];
		}
		return $body->data;	
	}

	public function refresh_access_token($access_token) {
		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$access_token.'';
		$response = wp_remote_get($url);
		if(!isset($body)) {
			$body = json_decode($response['body']);

			update_option('wpr_instagram_access_token', $body->access_token);
			update_option('wpr_instagram_access_token_expires_in', $body->expires_in);
			update_option('wpr_instagram_access_token_generation_date', date('Y-m-d'));
		}
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

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' wpr-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' wpr-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' wpr-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}


	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo '<div class="wpr-insta-feed-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_url( get_the_permalink( get_the_ID() ) ) .'">';

			if ( wpr_fs()->can_use_premium_code() ) {
				if ( '' !== $settings['overlay_image']['url'] ) {
					echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
				}
			}

		echo '</div>';
	}

	public function render_post_caption($settings, $class, $result) {

		if ( '' === $result->caption ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<figcaption class="wpr-insta-feed-caption"><span>'. esc_html(wp_trim_words($result->caption, $settings['element_word_count'])) .'</span></figcaption>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $result) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Date
				echo date(get_option( 'date_format' ), strtotime($result->timestamp));

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_icon($settings, $class, $result) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			   echo '<span>';
				echo '<i class="fab fa-instagram"></i>';
			   echo '</span>';
			echo '</div>';
		echo '</div>';
	}
	
	public function render_post_lightbox( $settings, $class, $result ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $result->media_url;

				if ( 'VIDEO' === $result->media_type ) {
					$lightbox_source = $result->thumbnail_url;
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="wpr-insta-feed-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public function render_post_sharing_icons( $settings, $class, $result ) {}

	// Get Elements
	public function get_elements( $type, $settings, $class, $result ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {

			case 'caption':
				$this->render_post_caption( $settings, $class, $result );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $result );
				break;

			case 'icon':
				$this->render_post_icon( $settings, $class, $result );
				break;
	

			// case 'author':
			// 	$this->render_post_author( $settings, $class );
			// 	break;

			// case 'comments':
			// 	$this->render_post_comments( $settings, $class );
			// 	break;

			// case 'read-more':
			// 	$this->render_post_read_more( $settings, $class );
			// 	break;

			// case 'likes':
			// 	$this->render_post_likes( $settings, $class, $post_id );
			// 	break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class, $result );
				break;

			case 'lightbox':
				$this->render_post_lightbox( $settings, $class, $result );
				break;

			// case 'separator':
			// 	$this->render_post_element_separator( $settings, $class );
			// 	break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $result ) {
		$locations = [];

		foreach ( $settings['insta_feed_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $thiss ) {

					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-insta-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'wpr-insta-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
							$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $result );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="wpr-insta-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'wpr-insta-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
						$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $result );
					}
				echo '</div>';
			}

		}
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		$instagram_settings = [
			'data-col' => 4
		];

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$instagram_settings['lightbox'] = [
			'selector' => '.wpr-insta-feed-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];
		
		if ( 'layout-carousel' === $settings['wpr_insta_feed_layout'] ) {
			
			$navigation = $settings['enable_cs_nav'];
			$pagination = $settings['wpr_enable_cs_pag'];
			$pagination_type = isset($settings['wpr_cs_pag_type']) ? $settings['wpr_cs_pag_type'] : '';
			$autoplay = $settings['wpr_enable_insta_feed_slider_autoplay'];
			$loop = $settings['wpr_enable_insta_feed_slider_loop'];
			$slides_to_show = $settings['wpr_insta_feed_slides_to_show'];
			$slides_to_show_widescreen = isset($settings['wpr_insta_feed_slides_to_show_widescreen']) ? $settings['wpr_insta_feed_slides_to_show_widescreen'] : $slides_to_show;
			$slides_to_show_laptop = isset($settings['wpr_insta_feed_slides_to_show_laptop']) ? $settings['wpr_insta_feed_slides_to_show_laptop'] : $settings['wpr_insta_feed_slides_to_show'];
			$slides_to_show_tablet_extra = isset($settings['wpr_insta_feed_slides_to_show_tablet_extra']) ? $settings['wpr_insta_feed_slides_to_show_tablet_extra'] : $slides_to_show_laptop;
			$slides_to_show_tablet = isset($settings['wpr_insta_feed_slides_to_show_tablet']) ? $settings['wpr_insta_feed_slides_to_show_tablet'] : $slides_to_show_tablet_extra;
			$slides_to_show_mobile_extra = isset($settings['wpr_insta_feed_slides_to_show_mobile_extra']) ? $settings['wpr_insta_feed_slides_to_show_mobile_extra'] : $slides_to_show_tablet;
			$slides_to_show_mobile = isset($settings['wpr_insta_feed_slides_to_show_mobile']) ? $settings['wpr_insta_feed_slides_to_show_mobile'] : $slides_to_show_mobile_extra;
			$space_between = $settings['wpr_insta_feed_space_between'];
			$space_between_widescreen = isset($settings['wpr_insta_feed_space_between_widescreen']) ? $settings['wpr_insta_feed_space_between_widescreen'] : $space_between;
			$space_between_laptop = isset($settings['wpr_insta_feed_space_between_laptop']) ? $settings['wpr_insta_feed_space_between_laptop'] : $space_between;
			$space_between_tablet_extra = isset($settings['wpr_insta_feed_space_between_tablet_extra']) ? $settings['wpr_insta_feed_space_between_tablet_extra'] : $space_between_laptop;
			$space_between_tablet = isset($settings['wpr_insta_feed_space_between_tablet']) ? $settings['wpr_insta_feed_space_between_tablet'] : $space_between_tablet_extra;
			$space_between_mobile_extra = isset($settings['wpr_insta_feed_space_between_mobile_extra']) ? $settings['wpr_insta_feed_space_between_mobile_extra'] : $space_between_tablet;
			$space_between_mobile = isset($settings['wpr_insta_feed_space_between_mobile']) ? $settings['wpr_insta_feed_space_between_mobile'] : $space_between_mobile_extra;
			$delay = isset($settings['wpr_insta_feed_delay']) ? $settings['wpr_insta_feed_delay'] : '';
			$speed = $settings['wpr_insta_feed_speed'];

			$instagram_settings['carousel'] = array(
				'wpr_cs_navigation' => $navigation,
				'wpr_cs_pagination' => $pagination,
				'wpr_cs_pagination_type' => $pagination_type,
				'wpr_cs_autoplay' => $autoplay,
				'wpr_cs_loop' => $loop,
				'wpr_cs_slides_to_show' => $slides_to_show,
				'wpr_cs_slides_to_show_widescreen' => $slides_to_show_widescreen,
				'wpr_cs_slides_to_show_laptop' => $slides_to_show_laptop,
				'wpr_cs_slides_to_show_tablet_extra' => $slides_to_show_tablet_extra,
				'wpr_cs_slides_to_show_tablet' => $slides_to_show_tablet,
				'wpr_cs_slides_to_show_mobile_extra' => $slides_to_show_mobile_extra,
				'wpr_cs_slides_to_show_mobile' => $slides_to_show_mobile,
				'wpr_cs_space_between' => $space_between,
				'wpr_cs_space_between_widescreen' => $space_between_widescreen,
				'wpr_cs_space_between_laptop' => $space_between_laptop,
				'wpr_cs_space_between_tablet_extra' => $space_between_tablet_extra,
				'wpr_cs_space_between_tablet' => $space_between_tablet,
				'wpr_cs_space_between_tablet' => $space_between_mobile_extra,
				'wpr_cs_space_between_tablet' => $space_between_mobile,
				'wpr_cs_delay' => $delay,
				'wpr_cs_speed' => $speed,
				// 'enable_on'   => $settings['wpr_enable_equal_height_on'],
			);
		}

		$this->add_render_attribute(
			'instagram',
			[
				'class'         => ['wpr-instagram-feed', 'wpr-'. $settings['wpr_insta_feed_layout'], $settings['wpr_insta_feed_column_grid']],
				'data-settings' => wp_json_encode( $instagram_settings ),
			]
		);

		if ( ! empty( $settings['instagram_follow_link']['url'] ) ) {
			$this->add_link_attributes( 'instagram_follow_link', $settings['instagram_follow_link'] );
		}

		$token_expires_in = get_option('wpr_instagram_access_token_expires_in');

		$compare_date = strtotime('-'.get_option('wpr_instagram_access_token_expires_in').' seconds');

		$token_generation_date = strtotime(get_option('wpr_instagram_access_token_generation_date'));

		$access_token = get_option('wpr_instagram_access_token');

		if ( $token_generation_date <= $compare_date  ) {
			
			$this->refresh_access_token($access_token);
		}

		if ( '' === $access_token ) {
			echo '<p>Please provide instagram access token!</p>';
			return;
		}

		?>

			<div class="wpr-instagram-feed-cont">
				<?php
					if ( 'yes' === $settings['enable_cs_nav'] ) {
						echo '<div class="wpr-swiper-nav-wrap">';
							echo '<button class="wpr-swiper-button wpr-swiper-button-prev">';
								echo Utilities::get_wpr_icon( $settings['wpr_cs_nav_arrows'], 'left' );
							echo '</button>';
							echo '<button class="wpr-swiper-button wpr-swiper-button-next">';
								echo Utilities::get_wpr_icon( $settings['wpr_cs_nav_arrows'], 'right' );
							echo '</button>';
						echo '</div>';
					}
					

					if ( 'yes' === $settings['wpr_enable_cs_pag'] ) {
						echo '<div class="swiper-pagination"></div>';
					}
				?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'instagram' ) ); ?>>

					<?php foreach($this->call_instagram_api($access_token) as $result) : ?>

						<div class="wpr-insta-feed-content-wrap wpr-insta-col-12">
							<figure>
								<?php
									// Content: Below Media
									echo $this->get_elements_by_location( 'above', $settings, $result );
								 ?>
								<div class="wpr-insta-feed-media-wrap <?php echo esc_attr($this->get_image_effect_class( $settings )) ?>">
								<?php if ($result->media_type == 'IMAGE') : ?>
									<div class="wpr-insta-feed-image-wrap" data-src=<?php echo $result->media_url ?>>
										<img src=<?php echo $result->media_url  ?> alt="">
									</div>
								<?php else : ?>
									<div class="wpr-insta-feed-image-wrap" data-src=<?php echo $result->thumbnail_url ?>>
										<img class="wpr-insta-feed-thumb" src=<?php echo $result->thumbnail_url ?> alt="">
									</div>
								<?php endif ; ?>
									<div class="wpr-insta-feed-media-hover wpr-animation-wrap">
										<?php
											// Media Overlay
											$this->render_media_overlay( $settings );

											// Content: Over Media
											$this->get_elements_by_location( 'over', $settings, $result );
										?>
									</div>
								</div>
								<?php
									// Content: Below Media
									echo $this->get_elements_by_location( 'below', $settings, $result );
								 ?>
							</figure>
						</div>

					<?php endforeach; ?>
					
				</div>
				
				<?php if ( 'yes' === $settings['show_instagram_follow_button'] ) : ?>
					<div>
						<a class="wpr-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>><?php echo $settings['instagram_follow_text'] ?></a>
					</div>
				<?php endif; ?>
			</div>

		<?php
    }
}