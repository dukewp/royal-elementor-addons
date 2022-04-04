<?php
namespace WprAddons\Modules\FeatureList\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
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

class Wpr_Feature_List extends Widget_Base {
	
	public function get_name() {
		return 'wpr-feature-list';
	}

	public function get_title() {
		return esc_html__( 'Feature List', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-editor-list-ul';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'features', 'feature list' ];
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
			'section_feature_list_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'list_alignment',
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
                'prefix_class' => 'wpr-feature-list-',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-item' => 'display-flex; justify-content: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

        $this->add_control(
            'feature_list_icon_shape',
            [
                'label'       => esc_html__( 'Icon Shape', 'wpr-addons' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'circle',
                'label_block' => false,
                'options'     => [
                    'circle'  => esc_html__( 'Circle', 'wpr-addons' ),
                    'square'  => esc_html__( 'Square', 'wpr-addons' ),
                    'rhombus' => esc_html__( 'Rhombus', 'wpr-addons' ),
                ],
				'prefix_class' => 'wpr-feature-list-'
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'feature_list_title_tag',
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
				'default' => 'h2'
			]
		);

		$this->add_control(
			'feature_list_connector',
			[
				'label' => esc_html__( 'Show Connector', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'list_alignment' => ['left', 'right'],
				],
				'prefix_class' => 'wpr-feature-list-connector-'
			]
		);

		$this->add_control(
			'list_item_spacing_v',
			[
				'label' => esc_html__( 'Vertical Spacing', 'wpr-addons' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_item_spacing_h',
			[
				'label' => esc_html__( 'Horizontal Spacing', 'wpr-addons' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-feature-list-left .wpr-feature-list-icon-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-feature-list-right .wpr-feature-list-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'list_alignment!' => 'center'
				]
			]
		);

        $this->end_controls_section();
        
		// Tab: Content ==============
		// Section: Content ----------
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs(
			'list_tabs'
		);

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => __( 'Content', 'wpr-addons' ),
			]
		);

        $repeater->add_control(
            'feature_list_media_type',
            [
                'label'       => esc_html__( 'Media Type', 'wpr-addons' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'wpr-addons' ),
                        'icon'  => 'fa fa-star',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'wpr-addons' ),
                        'icon'  => 'eicon-image-bold',
                    ],
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

		$repeater->add_control(
			'list_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'label_block' => false,
                'skin' => 'inline',
				'condition' => [
					'feature_list_media_type' => 'icon'
				]
			]
		);

		$repeater->add_control(
			'list_image',
			[
				'label' => esc_html__( 'Choose Image', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'skin' => 'inline',
				'condition' => [
					'feature_list_media_type' => 'image'
				]
			]
		);

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_title_url',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
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

		$repeater->add_control(
			'list_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => esc_html__( 'List Content', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Type your description here', 'wpr-addons' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'styles_tab',
			[
				'label' => __( 'Style', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'feature_list_custom_styles',
			[
				'label' => esc_html__( 'Custom Styles', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$repeater->add_control(
			'list_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Title #1', 'wpr-addons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
						'list_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'list_image' =>[
							'url' => Utils::get_placeholder_image_src(),	
							'id' => '',						
						],
					],
					[
						'list_title' => esc_html__( 'Title #2', 'wpr-addons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
						'list_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'list_image' =>[
							'url' => Utils::get_placeholder_image_src(),	
							'id' => '',						
						],
					],
					[
						'list_title' => esc_html__( 'Title #3', 'wpr-addons' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
						'list_icon' => [
							'value' => 'far fa-flag',
							'library' => 'solid'
						],
						'list_image' =>[
							'url' => Utils::get_placeholder_image_src(),	
							'id' => '',						
						],
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Icon ----------
		$this->start_controls_section(
			'section_feature_list_icon_styles',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_list_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A65FF',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A65FF',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-feature-list-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_wrapper_size',
			[
				'label' => esc_html__( 'Icon Wrapper Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
				],
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_margin',
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
					'{{WRAPPER}} .wpr-feature-list-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_type',
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
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_width',
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
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feature_list_icon_wrapper_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'feature_list_icon_wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				// 'default' => [
				// 	'top' => 1,
				// 	'right' => 1,
				// 	'bottom' => 1,
				// 	'left' => 1,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-inner-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Content ----------
		$this->start_controls_section(
			'section_feature_list_content_styles',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'feature_list_content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Title ----------
		$this->start_controls_section(
			'section_feature_list_title_styles',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_list_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-feature-list-title a.wpr-feature-list-url' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_list_title',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-feature-list-title'
			]
		);

		$this->add_responsive_control(
			'feature_list_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Description ----------
		$this->start_controls_section(
			'section_feature_list_description_styles',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feature_list_description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_list_description',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-feature-list-description'
			]
		);

		$this->add_responsive_control(
			'feature_list_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Tab: STYLE ==============
		// Section: Connector ----------
		$this->start_controls_section(
			'section_feature_list_connector_styles',
			[
				'label' => esc_html__( 'Connector', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'feature_list_connector' => 'yes'
				]
			]
		);

		$this->add_control(
			'feature_list_connector_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-wrap::before' => 'border-color: {{VALUE}}'
				],
			]
		);

        $this->add_control(
            'feature_list_connector_border_type',
            [
                'label'       => esc_html__( 'Connector Styles', 'wpr-addons' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'solid',
                'label_block' => false,
                'options'     => [
                    'solid'  => esc_html__( 'Solid', 'wpr-addons' ),
                    'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
                    'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
                ],
                'selectors'   => [
                    '{{WRAPPER}} .wpr-feature-list-icon-wrap::before' => 'border-style: {{VALUE}};',
                ],
				'separator' => 'before'
            ]
        );

		$this->add_control(
			'feature_list_connector_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-feature-list-icon-wrap::before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( $settings['list'] ) {
			$count_items = 0;
			echo '<div class="wpr-feature-list-wrap">';
                echo '<ul class="wpr-feature-list">';
                    foreach (  $settings['list'] as $item ) {
						$this->add_link_attributes( 'list_title_url' . $count_items, $item['list_title_url'] );
                        echo '<li class="wpr-feature-list-item">';
							echo '<div class="wpr-feature-list-icon-wrap">';
								echo '<div class="wpr-feature-list-icon-inner-wrap">';
									if ( 'icon' === $item['feature_list_media_type'] ) {
										\Elementor\Icons_Manager::render_icon( $item['list_icon'], [ 'aria-hidden' => 'true' ] );
									} else {
										$src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['list_image']['id'], 'thumbnail', $settings );
										echo '<img src="'. $src .'">';
									}
								echo '</div>';
                            echo '</div>';
                            echo '<div class="wpr-feature-list-content-wrap">';
								if ( empty($item['list_title_url']) ) {
									echo '<'. $settings['feature_list_title_tag'] .' class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . ' wpr-feature-list-title">' . $item['list_title'] . '</'. $settings['feature_list_title_tag'] .'>';
								} else {
									echo '<'. $settings['feature_list_title_tag'] .' class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . ' wpr-feature-list-title"><a class="wpr-feature-list-url" '. $this->get_render_attribute_string( 'list_title_url' . $count_items ) .'>' . $item['list_title'] . '</a></'. $settings['feature_list_title_tag'] .'>';
								}
                                echo '<p class="wpr-feature-list-description">' . $item['list_content'] . '</p>';
                            echo '</div>';
                        echo '</li>';
						$count_items++;
                    }
                echo '</ul>';
			echo '</div>';
		}
    }
}