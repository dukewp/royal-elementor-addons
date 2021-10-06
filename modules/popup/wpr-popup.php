<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Popup extends Elementor\Core\Base\Document {
	
	public function get_name() {
		return 'wpr-popups';
	}

	public static function get_type() {
		return 'wpr-popups';
	}
	
	public static function get_title() {
		return esc_html__( 'WPR Popup', 'wpr-addons' );
	}

	public function get_css_wrapper_selector() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return '.wpr-template-popup';
		} else {
			return '#wpr-popup-id-' . $this->get_main_id();
		}
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'popup_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'popup_trigger',
			[
				'label'   => esc_html__( 'Open Popup', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'load',
				'options' => [
					'load' => esc_html__( 'On Page Load', 'wpr-addons' ),
					'scroll' => esc_html__( 'On Page Scroll', 'wpr-addons' ),
					'element-scroll' => esc_html__( 'On Scroll to Element', 'wpr-addons' ),
					'date' => esc_html__( 'After Specific Date', 'wpr-addons' ),
					'inactivity'  => esc_html__( 'After User Inactivity', 'wpr-addons' ),
					'exit' => esc_html__( 'After User Exit Intent', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Trigger (Selector)', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'popup_load_delay',
			[
				'label' => esc_html__( 'Delay after Page Load (sec)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'condition' => [
					'popup_trigger' => 'load',
				]
			]
		);

		$this->add_control(
			'popup_scroll_progress',
			[
				'label' => esc_html__( 'Scroll Progress (in %)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'popup_trigger' => 'scroll',
				]
			]
		);

		$this->add_control(
			'popup_element_scroll',
			[
				'label' => esc_html__( 'Element Selector', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'popup_trigger' => 'element-scroll',
				]
			]
		);

		$this->add_control(
			'popup_specific_date',
			[
				'label' => esc_html__( 'Select Date', 'wpr-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'wpr-addons' ), Elementor\Utils::get_timezone_string() ),
				'condition' => [
					'popup_trigger' => 'date',
				],
			]
		);

		$this->add_control(
			'popup_custom_trigger',
			[
				'label' => esc_html__( 'Element Selector', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'popup_trigger' => 'custom',
				]
			]
		);

		$this->add_control(
			'popup_inactivity_time',
			[
				'label' => esc_html__( 'Inactivity Time (sec)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 15,
				'min' => 1,
				'condition' => [
					'popup_trigger' => 'inactivity',
				]
			]
		);

		$this->add_control(
			'popup_show_again_delay',
			[
				'label'   => esc_html__( 'Show Again Delay', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'No Delay', 'wpr-addons' ),
					'60000' => esc_html__( '1 Minute', 'wpr-addons' ),
					'180000' => esc_html__( '3 Minute', 'wpr-addons' ),
					'300000' => esc_html__( '5 Minute', 'wpr-addons' ),
					'600000' => esc_html__( '10 Minute', 'wpr-addons' ),
					'1800000' => esc_html__( '30 Minute', 'wpr-addons' ),
					'3600000' => esc_html__( '1 Hour', 'wpr-addons' ),
					'10800000' => esc_html__( '3 Hour', 'wpr-addons' ),
					'21600000' => esc_html__( '6 Hour', 'wpr-addons' ),
					'43200000' => esc_html__( '12 Hour', 'wpr-addons' ),
					'86400000' => esc_html__( '1 Day', 'wpr-addons' ),
					'259200000' => esc_html__( '3 Days', 'wpr-addons' ),
					'604800000' => esc_html__( '7 Days', 'wpr-addons' ),
					'2628000000' => esc_html__( 'Month', 'wpr-addons' ),
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_stop_after_date',
			[
				'label' => esc_html__( 'Stop Showing After Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_stop_after_date_select',
			[
				'label' => esc_html__( 'Select Date', 'wpr-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'wpr-addons' ), Elementor\Utils::get_timezone_string() ),
				'condition' => [
					'popup_stop_after_date!' => '',
				],
			]
		);

		$this->add_control(
			'popup_automatic_close_delay',
			[
				'label' => esc_html__( 'Automatic Closing Delay (sec)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_disable_esc_key',
			[
				'label' => esc_html__( 'Prevent Closing on "ESC" Key', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_show_for_roles',
			[
				'label' => esc_html__( 'Show For Roles', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => Utilities::get_user_roles(),
				'multiple' => 'true',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_show_via_referral',
			[
				'label' => esc_html__( 'Show according to URL Keyword', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_referral_keyword',
			[
				'label' => esc_html__( 'Enter Keyword', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => 'Popup will show up if the URL contains this Keyword.',
				'condition' => [
					'popup_show_via_referral' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'popup_show_on_device',
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
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'popup_display_as',
			[
				'label'   => esc_html__( 'Display As', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'modal',
				'options' => [
					'modal' => esc_html__( 'Modal Popup', 'wpr-addons' ),
					'notification' => esc_html__( 'Notification', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'popup_display_as_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_responsive_control(
			'popup_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-container' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'popup_display_as!' => 'notification',
				]
			]
		);

		$this->add_control(
			'popup_height',
			[
				'label'   => esc_html__( 'Height', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'=> esc_html__( 'Auto', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'auto' => 'height: auto; z-index: 13;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-container' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'popup_custom_height',
			[
				'label' => esc_html__( 'Custom Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-container' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'popup_height' => 'custom'
				]
			]
		);

		$this->add_responsive_control(
            'popup_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-template-popup-inner' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'popup_display_as!' => 'notification',
				]
            ]
        );

		$this->add_responsive_control(
            'popup_align_vr',
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
					'{{WRAPPER}} .wpr-template-popup-inner' => 'align-items: {{VALUE}}',
				],
				'condition' => [
					'popup_display_as!' => 'notification',
				]
            ]
        );

		$this->add_responsive_control(
            'popup_content_align',
            [
                'label' => esc_html__( 'Content Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-start',
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
					'{{WRAPPER}} .wpr-popup-container' => 'align-items: {{VALUE}}',
				],
				'condition' => [
					'popup_display_as!' => 'notification',
				]
            ]
        );

		$this->add_control(
			'popup_animation',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::ANIMATION,
				'label_block' => false,
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-container' => 'animation-duration: {{SIZE}}s;',
				],
				'condition' => [
					'popup_animation!' => '',
				]
			]
		);

		$this->add_control(
			'popup_zindex',
			[
				'label' => esc_html__( 'Z Index', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 9999,
				'min' => 1,
				'selectors' => [
					'{{WRAPPER}}' => 'z-index: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_disable_page_scroll',
			[
				'label' => esc_html__( 'Disable Page Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => true,
				'return_value' => true,
				'separator' => 'before',
				'condition' => [
					'popup_display_as!' => 'notification',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_overlay',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
				'condition' => [
					'popup_display_as!' => 'notification',
				]
			]
		);

		$this->add_control(
			'popup_overlay_display',
			[
				'label' => esc_html__( 'Show Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'display: none !important;',
					'yes' => 'display: block;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-overlay' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_overlay_disable_close',
			[
				'label' => esc_html__( 'Prevent Closing on Overlay Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'popup_overlay_display' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_close_button',
			[
				'label' => esc_html__( 'Close Button', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'popup_close_button_display',
			[
				'label' => esc_html__( 'Show Close Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'display: none;',
					'yes' => 'display: inherit;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_close_button_display_delay',
			[
				'label' => esc_html__( 'Show Up Delay (sec)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'condition' => [
					'popup_close_button_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'popup_close_button_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'wpr-addons' ),
					'outside' => esc_html__( 'Outside', 'wpr-addons' ),
				],
				'separator' => 'before',
				'condition' => [
					'popup_close_button_display' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'popup_close_button_position_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'popup_close_button_display' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'popup_close_button_position_hr',
			[
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'popup_close_button_display' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Default Document Settings
		parent::_register_controls();

		$this->start_controls_section(
			'popup_container_styles',
			[
				'label' => esc_html__( 'Popup', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'popup_container_bg',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#ffffff',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-popup-image-overlay'
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'popup_container_bg_overlay',
				'selector' => '{{WRAPPER}} .wpr-popup-image-overlay',
			]
		);

		$this->add_control(
			'popup_scrollbar_color',
			[
				'label'  => esc_html__( 'ScrollBar Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ps-container > .ps-scrollbar-y-rail > .ps-scrollbar-y' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'popup_container_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} div[data-elementor-type="wpr-popup"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'popup_container_margin',
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
					'{{WRAPPER}} .wpr-template-popup-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_container_radius',
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
					'{{WRAPPER}} .wpr-popup-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'popup_container_border',
				'label' => esc_html__( 'Border', 'jet-popup' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .wpr-popup-container',
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_container_shadow',
				'selector' => '{{WRAPPER}} .wpr-popup-container'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_overlay_styles',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'popup_overlay_bg',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#777777',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-popup-overlay'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_close_btn_styles',
			[
				'label' => esc_html__( 'Close Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_popup_close_btn_style' );

		$this->start_controls_tab(
			'tab_popup_close_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'popup_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_close_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'popup_close_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_close_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-popup-close-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_popup_close_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'popup_close_btn_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_close_btn_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'popup_close_btn_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'popup_close_btn_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-close-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_close_btn_border_type',
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
					'{{WRAPPER}} .wpr-popup-close-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_close_btn_border_width',
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
					'{{WRAPPER}} .wpr-popup-close-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'popup_close_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'popup_close_btn_padding',
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
					'{{WRAPPER}} .wpr-popup-close-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_close_btn_radius',
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
					'{{WRAPPER}} .wpr-popup-close-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}
	
}