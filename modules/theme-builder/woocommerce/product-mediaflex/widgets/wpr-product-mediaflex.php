<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMediaflex\Widgets;

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

class Wpr_Product_Mediaflex extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-mediaflex';
	}

	public function get_title() {
		return esc_html__( 'Product MediaFlex', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-media', 'product', 'image', 'mediaflex' ];//tmp
	}

	public function get_script_depends() {
		return [ 'flexslider', 'zoom', 'wc-single-product'  ];
	}
	protected function _register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_image',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

	// 	$this->add_group_control(
	// 		Group_Control_Image_Size::get_type(),
	// 		[
	// 			'name' => 'product_media_image_crop',
	// 			'default' => 'full',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_media_zoom',
	// 		[
	// 			'label' => esc_html__( 'Enable Zoom Effect', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'yes',
	// 			'prefix_class' => 'wpr-gallery-zoom-',
	// 			'render_type' => 'template',
	// 			'separator' => 'before'
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_media_sale_badge',
	// 		[
	// 			'label' => esc_html__( 'Show Sale Badge', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'yes',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_media_lightbox',
	// 		[
	// 			'label' => esc_html__( 'Show Lightbox Icon', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'render_type' => 'template',
	// 			'return_value' => 'yes',
	// 			'prefix_class' => 'wpr-gallery-lightbox-',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_media_caption',
	// 		[
	// 			'label' => esc_html__( 'Product Image Caption', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'return_value' => 'yes',
	// 			'condition' => [
	// 				'product_media_zoom' => '',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_media_caption_hover',
	// 		[
	// 			'label' => esc_html__( 'Show Caption on Hover', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'return_value' => 'hover',
	// 			'prefix_class' => 'wpr-pd-image-caption-',
	// 			'condition' => [
	// 				'product_media_caption' => 'yes',
	// 				'product_media_zoom' => '',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	// Tab: Image Gallery ========
	// 	// Section: General ----------
	// 	$this->start_controls_section(
	// 		'wpr__section_product_media_gallery',
	// 		[
	// 			'label' => esc_html__( 'Image Gallery', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_CONTENT,
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_display_as',
	// 		[
	// 			'label' => esc_html__( 'Display As', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'slider' => esc_html__( 'Slideshow Gallery', 'wpr-addons' ),
	// 				'stacked' => esc_html__( 'Stacked Gallery', 'wpr-addons' ),
	// 			],
	// 			'default' => 'slider',
	// 			'prefix_class' => 'wpr-gallery-type-',
	// 			'render_type' => 'template'
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_stacked_gutter',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
	// 			'size_units' => [ 'px' ],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 50,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 10,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider .wpr-gallery-slide:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 			'condition' => [
	// 				'gallery_display_as' => 'stacked'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_heading',
	// 		[
	// 			'type' => Controls_Manager::HEADING,
	// 			'label' => esc_html__( 'Main Image', 'wpr-addons' ),
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_slider_nav',
	// 		[
	// 			'label' => esc_html__( 'Show Navigation Arrows', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'yes',
	// 			'selectors_dictionary' => [
	// 				'' => 'none',
	// 				'yes' => 'flex'
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'display:{{VALUE}} !important;',
	// 			],
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_hover',
	// 		[
	// 			'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'return_value' => 'fade',
	// 			'prefix_class' => 'wpr-gallery-slider-nav-',
	// 			'condition' => [
	// 				'gallery_slider_nav' => 'yes',
	// 				'gallery_display_as' => 'slider'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_icon',
	// 		[
	// 			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
	// 			'type' => 'wpr-arrow-icons',
	// 			'default' => 'fas fa-angle',
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'gallery_slider_nav' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_thumbs_nav_heading',
	// 		[
	// 			'type' => Controls_Manager::HEADING,
	// 			'label' => esc_html__( 'Gallery Thumbnails', 'wpr-addons' ),
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_slider_thumbs',
	// 		[
	// 			'label' => esc_html__( 'Show Thumbnail Images', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'render_type' => 'template',
	// 			'default' => 'yes',
	// 			'tablet_default' => 'yes',
	// 			'mobile_default' => 'yes',
	// 			'selectors_dictionary' => [
	// 				'' => 'none',
	// 				'yes' => 'inline-table'
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-dots' => 'display:{{VALUE}};',
	// 			],
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider'
	// 			]
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'thumbnail_slider_nav',
	// 		[
	// 			'label' => esc_html__( 'Show Navigation Arrows', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'render_type' => 'template',
	// 			'default' => 'yes',
	// 			'tablet_default' => 'yes',
	// 			'mobile_default' => 'yes',
	// 			'selectors_dictionary' => [
	// 				'' => 'none',
	// 				'yes' => 'flex'
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'display:{{VALUE}} !important;',
	// 			],
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'gallery_slider_thumbs' => 'yes',
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_hover',
	// 		[
	// 			'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'return_value' => 'fade',
	// 			'prefix_class' => 'wpr-thumbnail-slider-nav-',
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'thumbnail_slider_nav' => 'yes',
	// 				'gallery_slider_thumbs' => 'yes',
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_icon',
	// 		[
	// 			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
	// 			'type' => 'wpr-arrow-icons',
	// 			'default' => 'fas fa-angle',
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'gallery_slider_thumbs' => 'yes',
	// 				'thumbnail_slider_nav' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	// $this->add_responsive_control(
	// 	// 	'gallery_slider_thumb_cols',
	// 	// 	[
	// 	// 		'type' => Controls_Manager::SELECT,
	// 	// 		'label' => esc_html__( 'Thumbs to Show', 'wpr-addons' ),
	// 	// 		'default' => '4',
	// 	// 		'options' => [
	// 	// 			'1' => esc_html__( '1', 'wpr-addons' ),
	// 	// 			'2' => esc_html__( '2', 'wpr-addons' ),
	// 	// 			'3' => esc_html__( '3', 'wpr-addons' ),
	// 	// 			'4' => esc_html__( '4', 'wpr-addons' ),
	// 	// 			'5' => esc_html__( '5', 'wpr-addons' ),
	// 	// 			'6' => esc_html__( '6', 'wpr-addons' ),
	// 	// 		],
	// 	// 		'condition' => [
	// 	// 			'gallery_slider_thumbs' => 'yes'
	// 	// 		],
	// 	// 		'condition' => [
	// 	// 			'gallery_display_as' => 'slider'
	// 	// 		]
	// 	// 	]
	// 	// );

	// 	$this->add_control(
	// 		'gallery_slider_thumb_cols',
	// 		[
	// 			'label' => esc_html__( 'Thumbsnails to Show', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'min' => 2,
	// 			'default' => 4,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'gallery_slider_thumbs' => 'yes'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'slides_to_scroll',
	// 		[
	// 			'label' => esc_html__( 'Slides To Scroll', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'min' => 1,
	// 			'default' => 1,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 				'gallery_slider_thumbs' => 'yes',
	// 				'thumbnail_slider_nav' => 'yes',
	// 			],
	// 		]
	// 	);
		
	// 	$this->add_control(
	// 		'gallery_slider_effect',
	// 		[
	// 			'type' => Controls_Manager::SELECT,
	// 			'label' => esc_html__( 'Effect', 'wpr-addons' ),
	// 			'default' => 'slide',
	// 			'options' => [
	// 				'slide' => esc_html__( 'Slide', 'wpr-addons' ),
	// 				'fade' => esc_html__( 'Fade', 'wpr-addons' ),
	// 			],
	// 			'separator' => 'before',
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_effect_duration',
	// 		[
	// 			'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 0.7,
	// 			'min' => 0,
	// 			'max' => 5,
	// 			'step' => 0.1,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider'
	// 			]
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	// Tab: Content ==============
	// 	// Section: Lightbox Popup ---
	// 	$this->start_controls_section(
	// 		'section_lightbox_popup',
	// 		[
	// 			'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_CONTENT,
	// 			'condition' => [
	// 				'product_media_lightbox' => 'yes'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_extra_icon',
	// 		[
	// 			'label' => esc_html__( 'Lightbox Icon', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'choose_lightbox_extra_icon',
	// 		[
	// 			'label' => __( 'Icon', 'wpr-addons' ),
	// 			'type' => \Elementor\Controls_Manager::ICONS,
	// 			'skin' => 'inline',
	// 			'label_block' => false,
	// 			'default' => [
	// 				'value' => 'fas fa-search',
	// 				'library' => 'solid',
	// 			],
	// 			'condition' => [
	// 				'lightbox_extra_icon' => 'true',
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_autoplay',
	// 		[
	// 			'label' => esc_html__( 'Autoplay Slides', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_progressbar',
	// 		[
	// 			'label' => esc_html__( 'Show Progress Bar', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 			'condition' => [
	// 				'lightbox_popup_autoplay' => 'true'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_pause',
	// 		[
	// 			'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 5,
	// 			'min' => 1,
	// 			'max' => 10,
	// 			'step' => 1,
	// 			'condition' => [
	// 				'lightbox_popup_autoplay' => 'true',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_counter',
	// 		[
	// 			'label' => esc_html__( 'Show Counter', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_arrows',
	// 		[
	// 			'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_captions',
	// 		[
	// 			'label' => esc_html__( 'Show Captions', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_thumbnails',
	// 		[
	// 			'label' => esc_html__( 'Show Thumbnails', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_thumbnails_default',
	// 		[
	// 			'label' => esc_html__( 'Show Thumbs by Default', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 			'condition' => [
	// 				'lightbox_popup_thumbnails' => 'true'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_sharing',
	// 		[
	// 			'label' => esc_html__( 'Show Sharing Button', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_zoom',
	// 		[
	// 			'label' => esc_html__( 'Show Zoom Button', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_fullscreen',
	// 		[
	// 			'label' => esc_html__( 'Show Full Screen Button', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_popup_download',
	// 		[
	// 			'label' => esc_html__( 'Show Download Button', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'default' => 'true',
	// 			'return_value' => 'true',
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	// Styles ====================
	// 	// Section: Status -----------
	// 	$this->start_controls_section(
	// 		'section_style_product_sale',
	// 		[
	// 			'label' => esc_html__( 'Sale Badge', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'show_label' => false,
	// 			'condition' => [
	// 				'product_media_sale_badge' => 'yes'
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_color',
	// 		[
	// 			'label'  => esc_html__( 'On Sale Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#333333',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_bg_color',
	// 		[
	// 			'label'  => esc_html__( 'On Sale Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#ffffff',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'background-color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_border_color',
	// 		[
	// 			'label'  => esc_html__( 'On Sale Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'border-color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Typography::get_type(),
	// 		[
	// 			'name'     => 'product_saletypography',
	// 			'scheme' => Typography::TYPOGRAPHY_3,
	// 			'selector' => '{{WRAPPER}} .wpr-product-media-onsale'
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Box_Shadow::get_type(),
	// 		[
	// 			'name' => 'product_sale_shadow',
	// 			'selector' => '{{WRAPPER}} .wpr-product-media-onsale',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_border_type',
	// 		[
	// 			'label' => esc_html__( 'Border Type', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'none' => esc_html__( 'None', 'wpr-addons' ),
	// 				'solid' => esc_html__( 'Solid', 'wpr-addons' ),
	// 				'double' => esc_html__( 'Double', 'wpr-addons' ),
	// 				'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
	// 				'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
	// 				'groove' => esc_html__( 'Groove', 'wpr-addons' ),
	// 			],
	// 			'default' => 'none',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'border-style: {{VALUE}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_border_width',
	// 		[
	// 			'label' => esc_html__( 'Border Width', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 1,
	// 				'right' => 1,
	// 				'bottom' => 1,
	// 				'left' => 1,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'product_sale_border_type!' => 'none',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'product_sale_padding',
	// 		[
	// 			'label' => esc_html__( 'Padding', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'default' => [
	// 				'top' => 0,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'product_sale_margin',
	// 		[
	// 			'label' => esc_html__( 'Margin', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 10,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 10,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'product_sale_radius',
	// 		[
	// 			'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'default' => [
	// 				'top' => 0,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'after',
	// 		]
	// 	);

	// 	$this->end_controls_section();

	// 	$this->start_controls_section(
	// 		'section_style_lightbox_icon',
	// 		[
	// 			'label' => esc_html__( 'Lightbox Icon', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'show_label' => false,
	// 			'condition' => [
	// 				'product_media_lightbox' => 'yes'
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_color',
	// 		[
	// 			'label'  => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#ffffff',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox i' => 'color: {{VALUE}}',
	// 				'{{WRAPPER}} .wpr-product-media-lightbox svg' => 'fill: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_bg_color',
	// 		[
	// 			'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'background-color: {{VALUE}}',
	// 			]
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_border_color',
	// 		[
	// 			'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#E8E8E8',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Box_Shadow::get_type(),
	// 		[
	// 			'name' => 'product_lightbox_shadow',
	// 			'selector' => '{{WRAPPER}} .wpr-product-media-lightbox',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_tr_duration',
	// 		[
	// 			'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 0.1,
	// 			'min' => 0,
	// 			'max' => 5,
	// 			'step' => 0.1,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox i' => 'transition-duration: {{VALUE}}s',
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'transition-duration: {{VALUE}}s',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_border_type',
	// 		[
	// 			'label' => esc_html__( 'Border Type', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'none' => esc_html__( 'None', 'wpr-addons' ),
	// 				'solid' => esc_html__( 'Solid', 'wpr-addons' ),
	// 				'double' => esc_html__( 'Double', 'wpr-addons' ),
	// 				'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
	// 				'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
	// 				'groove' => esc_html__( 'Groove', 'wpr-addons' ),
	// 			],
	// 			'default' => 'none',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-style: {{VALUE}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_border_width',
	// 		[
	// 			'label' => esc_html__( 'Border Width', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 1,
	// 				'right' => 1,
	// 				'bottom' => 1,
	// 				'left' => 1,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'lightbox_border_type!' => 'none',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'lightbox_icon_size',
	// 		[
	// 			'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 50,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 20,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'font-size: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-product-media-lightbox svg' => 'width: {{SIZE}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'lightbox_icon_box_size',
	// 		[
	// 			'label' => esc_html__( 'Box Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 100,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 50,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'lightbox_margin',
	// 		[
	// 			'label' => esc_html__( 'Margin', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'default' => [
	// 				'top' => 10,
	// 				'right' => 10,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'lightbox_radius',
	// 		[
	// 			'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'default' => [
	// 				'top' => 2,
	// 				'right' => 2,
	// 				'bottom' => 2,
	// 				'left' => 2,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-lightbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->end_controls_section();

	// 	// Styles ====================
	// 	// Section: Navigation -------
	// 	$this->start_controls_section(
	// 		'wpr__section_style_gallery_arrows_nav',
	// 		[
	// 			'label' => esc_html__( 'Main Image Arrows', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 			],
	// 		]
	// 	);

	// 	$this->start_controls_tabs( 'tabs_gallery_slider_nav_style' );

	// 	$this->start_controls_tab(
	// 		'tab_gallery_slider_nav_normal',
	// 		[
	// 			'label' => esc_html__( 'Normal', 'wpr-addons' ),
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_color',
	// 		[
	// 			'label' => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => 'rgba(255,255,255,0.8)',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'color: {{VALUE}};',
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow svg' => 'fill: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_bg_color',
	// 		[
	// 			'label' => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'background-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_border_color',
	// 		[
	// 			'label' => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => 'rgba(255,255,255,0.8)',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->start_controls_tab(
	// 		'tab_gallery_slider_nav_hover',
	// 		[
	// 			'label' => esc_html__( 'Hover', 'wpr-addons' ),
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_hover_color',
	// 		[
	// 			'label' => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#ffffff',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_hover_bg_color',
	// 		[
	// 			'label' => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'background-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_hover_border_color',
	// 		[
	// 			'label' => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow:hover' => 'border-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->end_controls_tabs();

	// 	$this->add_control(
	// 		'gallery_slider_nav_transition_duration',
	// 		[
	// 			'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 0.1,
	// 			'min' => 0,
	// 			'max' => 5,
	// 			'step' => 0.1,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'transition-duration: {{VALUE}}s',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_slider_nav_font_size',
	// 		[
	// 			'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 50,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 25,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_slider_nav_size',
	// 		[
	// 			'label' => esc_html__( 'Box Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 100,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 50,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_slider_nav_position_horizontal',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
	// 			'size_units' => [ 'px' ],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 25,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 0,
	// 			],
	// 			'separator' => 'after',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-gallery-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_border_type',
	// 		[
	// 			'label' => esc_html__( 'Border Type', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'none' => esc_html__( 'None', 'wpr-addons' ),
	// 				'solid' => esc_html__( 'Solid', 'wpr-addons' ),
	// 				'double' => esc_html__( 'Double', 'wpr-addons' ),
	// 				'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
	// 				'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
	// 				'groove' => esc_html__( 'Groove', 'wpr-addons' ),
	// 			],
	// 			'default' => 'none',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-style: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_border_width',
	// 		[
	// 			'label' => esc_html__( 'Border Width', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 2,
	// 				'right' => 2,
	// 				'bottom' => 0,
	// 				'left' => 2,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'gallery_slider_nav_border_type!' => 'none',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_slider_nav_border_radius',
	// 		[
	// 			'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-gallery-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'after',
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	// Styles ====================
	// 	// Section: Navigation -------
	// 	$this->start_controls_section(
	// 		'wpr__section_style_gallery_thumb_nav',
	// 		[
	// 			'label' => esc_html__( 'Gallery Thumbnails', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_thumb_nav_width',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Width', 'wpr-addons' ),
	// 			'size_units' => [ '%', 'px' ],
	// 			'range' => [
	// 				'%' => [
	// 					'min' => 10,
	// 					'max' => 100,
	// 				],
	// 				'px' => [
	// 					'min' => 50,
	// 					'max' => 1000,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => '%',
	// 				'size' => 80,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-thumb-nav' => 'max-width: {{SIZE}}{{UNIT}};',
	// 			],
	// 			'render_type' => 'template'
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_thumb_nav_top_distance',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
	// 			'size_units' => [ 'px' ],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 25,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 1,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-thumb-nav' => 'margin-top: {{SIZE}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'gallery_thumb_nav_gutter',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Gutter', 'wpr-addons' ),
	// 			'size_units' => [ 'px' ],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 25,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 1,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-thumb-nav .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-product-thumb-nav .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
	// 			],
	// 			'render_type' => 'template'
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'gallery_thumb_nav_border_radius',
	// 		[
	// 			'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'default' => [
	// 				'top' => 0,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 				'unit' => '%',
	// 			],
	// 			'size_units' => [ 'px', '%' ],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-thumb-nav li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	$this->start_controls_section(
	// 		'wpr__section_style_thumbnail_arrows_nav',
	// 		[
	// 			'label' => esc_html__( 'Gallery Thumbnails Arrows', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'condition' => [
	// 				'gallery_display_as' => 'slider',
	// 			],
	// 		]
	// 	);

	// 	$this->start_controls_tabs( 'tabs_thumbnail_slider_nav_style' );

	// 	$this->start_controls_tab(
	// 		'tab_thumbnail_slider_nav_normal',
	// 		[
	// 			'label' => esc_html__( 'Normal', 'wpr-addons' ),
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_color',
	// 		[
	// 			'label' => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => 'rgba(255,255,255,0.8)',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'color: {{VALUE}};',
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow svg' => 'fill: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_bg_color',
	// 		[
	// 			'label' => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'background-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_border_color',
	// 		[
	// 			'label' => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => 'rgba(255,255,255,0.8)',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->start_controls_tab(
	// 		'tab_thumbnail_slider_nav_hover',
	// 		[
	// 			'label' => esc_html__( 'Hover', 'wpr-addons' ),
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_hover_color',
	// 		[
	// 			'label' => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#ffffff',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_hover_bg_color',
	// 		[
	// 			'label' => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'background-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_hover_border_color',
	// 		[
	// 			'label' => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow:hover' => 'border-color: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->end_controls_tabs();

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_transition_duration',
	// 		[
	// 			'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 0.1,
	// 			'min' => 0,
	// 			'max' => 5,
	// 			'step' => 0.1,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'transition-duration: {{VALUE}}s',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'thumbnail_slider_nav_font_size',
	// 		[
	// 			'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 50,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 25,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'thumbnail_slider_nav_size',
	// 		[
	// 			'label' => esc_html__( 'Box Size', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'size_units' => ['px'],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 10,
	// 					'max' => 100,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 50,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};', // remove line-height if not needed
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'thumbnail_slider_nav_position_horizontal',
	// 		[
	// 			'type' => Controls_Manager::SLIDER,
	// 			'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
	// 			'size_units' => [ 'px' ],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 25,
	// 				],
	// 			],
	// 			'default' => [
	// 				'unit' => 'px',
	// 				'size' => 0,
	// 			],
	// 			'separator' => 'after',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_border_type',
	// 		[
	// 			'label' => esc_html__( 'Border Type', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'none' => esc_html__( 'None', 'wpr-addons' ),
	// 				'solid' => esc_html__( 'Solid', 'wpr-addons' ),
	// 				'double' => esc_html__( 'Double', 'wpr-addons' ),
	// 				'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
	// 				'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
	// 				'groove' => esc_html__( 'Groove', 'wpr-addons' ),
	// 			],
	// 			'default' => 'none',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-style: {{VALUE}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_border_width',
	// 		[
	// 			'label' => esc_html__( 'Border Width', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 2,
	// 				'right' => 2,
	// 				'bottom' => 0,
	// 				'left' => 2,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'thumbnail_slider_nav_border_type!' => 'none',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'thumbnail_slider_nav_border_radius',
	// 		[
	// 			'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px', '%' ],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'after',
	// 		]
	// 	);

	// 	$this->end_controls_section(); // End Controls Section

	// 	// Styles ====================
	// 	// Section: Image Caption ----
	// 	$this->start_controls_section(
	// 		'section_style_image_caption',
	// 		[
	// 			'label' => esc_html__( 'Image Caption', 'wpr-addons' ),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 			'show_label' => false,
	// 			'condition' => [
	// 				'product_media_caption' => 'yes'
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_color',
	// 		[
	// 			'label'  => esc_html__( 'Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#333333',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_bg_color',
	// 		[
	// 			'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '#ffffff',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'background-color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_border_color',
	// 		[
	// 			'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
	// 			'type' => Controls_Manager::COLOR,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'border-color: {{VALUE}}',
	// 			],
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Box_Shadow::get_type(),
	// 		[
	// 			'name' => 'image_caption_shadow',
	// 			'selector' => '{{WRAPPER}} .wpr-product-media-caption span',
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Typography::get_type(),
	// 		[
	// 			'name'     => 'image_caption_typography',
	// 			'scheme' => Typography::TYPOGRAPHY_3,
	// 			'selector' => '{{WRAPPER}} .wpr-product-media-caption span'
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_transition_duration',
	// 		[
	// 			'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
	// 			'type' => Controls_Manager::NUMBER,
	// 			'default' => 0.1,
	// 			'min' => 0,
	// 			'max' => 5,
	// 			'step' => 0.1,
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption' => 'transition-duration: {{VALUE}}s',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_padding',
	// 		[
	// 			'label' => esc_html__( 'Padding', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 0,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_margin',
	// 		[
	// 			'label' => esc_html__( 'Margin', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 0,
	// 				'right' => 0,
	// 				'bottom' => 0,
	// 				'left' => 0,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_border_type',
	// 		[
	// 			'label' => esc_html__( 'Border Type', 'wpr-addons' ),
	// 			'type' => Controls_Manager::SELECT,
	// 			'options' => [
	// 				'none' => esc_html__( 'None', 'wpr-addons' ),
	// 				'solid' => esc_html__( 'Solid', 'wpr-addons' ),
	// 				'double' => esc_html__( 'Double', 'wpr-addons' ),
	// 				'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
	// 				'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
	// 				'groove' => esc_html__( 'Groove', 'wpr-addons' ),
	// 			],
	// 			'default' => 'none',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'border-style: {{VALUE}};',
	// 			],
	// 			'separator' => 'before',
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'image_caption_border_width',
	// 		[
	// 			'label' => esc_html__( 'Border Width', 'wpr-addons' ),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => [ 'px' ],
	// 			'default' => [
	// 				'top' => 1,
	// 				'right' => 1,
	// 				'bottom' => 1,
	// 				'left' => 1,
	// 			],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'image_caption_border_type!' => 'none',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
    //         'image_caption_align_vr',
    //         [
    //             'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
    //             'type' => Controls_Manager::CHOOSE,
    //             'label_block' => false,
    //             'default' => 'center',
	// 			'options' => [
	// 				'flex-start' => [
	// 					'title' => esc_html__( 'Top', 'wpr-addons' ),
	// 					'icon' => 'eicon-v-align-top',
	// 				],
	// 				'center' => [
	// 					'title' => esc_html__( 'Middle', 'wpr-addons' ),
	// 					'icon' => 'eicon-v-align-middle',
	// 				],
	// 				'flex-end' => [
	// 					'title' => esc_html__( 'Bottom', 'wpr-addons' ),
	// 					'icon' => 'eicon-v-align-bottom',
	// 				],
    //             ],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption' => 'align-items: {{VALUE}}',
	// 			],
	// 			'separator' => 'before',
    //         ]
    //     );

	// 	$this->add_control(
    //         'image_caption_align_hr',
    //         [
    //             'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
    //             'type' => Controls_Manager::CHOOSE,
    //             'label_block' => false,
    //             'default' => 'center',
    //             'options' => [
    //                 'flex-start' => [
    //                     'title' => esc_html__( 'Left', 'wpr-addons' ),
    //                     'icon' => 'eicon-h-align-left',
    //                 ],
    //                 'center' => [
    //                     'title' => esc_html__( 'Center', 'wpr-addons' ),
    //                     'icon' => 'eicon-h-align-center',
    //                 ],
    //                 'flex-end' => [
    //                     'title' => esc_html__( 'Right', 'wpr-addons' ),
    //                     'icon' => 'eicon-h-align-right',
    //                 ]
    //             ],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .wpr-product-media-caption' => 'justify-content: {{VALUE}}',
	// 			],
    //         ]
    //     );

		$this->end_controls_section();
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		// Get Product
		$product = wc_get_product();
		// $this->get_hoodies();

		if ( ! $product ) {
			return;
		}

		// Product ID
		$product_id = $product->get_id();
		$gallery_images = $product->get_gallery_image_ids();

		// Output
        wc_get_template('single-product/product-image.php');

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script>
				jQuery( '.woocommerce-product-gallery' ).each( function () {
					jQuery( this ).wc_product_gallery();
				} );
			</script>
			<?php
		}
	}
	
}