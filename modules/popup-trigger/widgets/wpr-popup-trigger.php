<?php
namespace WprAddons\Modules\PopupTrigger\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Popup_Trigger extends Widget_Base {
	
	public function get_name() {
		return 'wpr-popup-trigger';
	}

	public function get_title() {
		return esc_html__( 'Popup Trigger', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-button';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'popup', 'trigger', 'button', 'action' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ---------
		$this->start_controls_section(
			'section_popup_trigger',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'popup_trigger_type',
			[
				'label'   => esc_html__( 'Button Action', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'close',
				'options' => [
					'close' => esc_html__( 'Close Popup', 'wpr-addons' ),
					'close-permanently' => esc_html__( 'Close Permanently', 'wpr-addons' ),
					'back' => esc_html__( 'Go Back to Referrer', 'wpr-addons' ),
					'scroll' => esc_html__( 'Scroll to Top', 'wpr-addons' ),
				]
			]
		);

		$this->add_control(
			'popup_trigger_show_again_delay',
			[
				'label'   => esc_html__( 'Show Again Delay', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'No Delay', 'wpr-addons' ),
					'10000' => esc_html__( '10 Sec', 'wpr-addons' ),//tmp
					'15000' => esc_html__( '15 Sec', 'wpr-addons' ),//tmp
					'20000' => esc_html__( '20 Sec', 'wpr-addons' ),//tmp
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
				'condition' => [
					'popup_trigger_type' => 'close'
				]
			]
		);

		$this->add_control(
			'popup_trigger_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Close Popup',
				'separator' => 'before'
			]
		);


		$this->add_control(
			'popup_trigger_extra_icon_pos',
			[
				'label' => esc_html__( 'Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'popup_trigger_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICON,
				'default' => '',
				'condition' => [
					'popup_trigger_extra_icon_pos!' => 'none'
				]
			]
		);

		$this->add_control(
            'popup_trigger_align',
            [
                'label' => esc_html__( 'Button Align', 'wpr-addons' ),
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Tab: Styles ===============
		// Section: General ----------
		$this->start_controls_section(
			'section_popup_trigger_styles',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_popup_trigger_style' );

		$this->start_controls_tab(
			'tab_popup_trigger_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'popup_trigger_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_trigger_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'popup_trigger_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_trigger_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-popup-trigger-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_popup_trigger_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'popup_trigger_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'popup_trigger_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'popup_trigger_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_trigger_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-popup-trigger-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'popup_trigger_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		$this->add_control(
			'popup_trigger_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popup_trigger_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-popup-trigger-button'
			]
		);

		$this->add_control(
			'popup_trigger_border_type',
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
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_trigger_border_width',
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
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'popup_trigger_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'popup_trigger_icon_spacing',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button .wpr-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-popup-trigger-button .wpr-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'popup_trigger_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'popup_trigger_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'popup_trigger_radius',
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
					'{{WRAPPER}} .wpr-popup-trigger-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$popup_show_delay = $settings['popup_trigger_show_again_delay'];

		if ( 'close-permanently' === $settings['popup_trigger_type'] ) {
			$popup_show_delay = 10000000000000;
		}

		echo '<div class="wpr-popup-trigger-button" data-trigger="'. $settings['popup_trigger_type'] .'" data-show-delay="'. $popup_show_delay .'">';

			// Icon: Before
			if ( 'before' === $settings['popup_trigger_extra_icon_pos'] ) {
				echo '<i class="wpr-extra-icon-left '. esc_attr( $settings['popup_trigger_extra_icon'] ) .'"></i>';
			}

			echo '<span>'. $settings['popup_trigger_text'] .'</span>';

			// Icon: After
			if ( 'after' === $settings['popup_trigger_extra_icon_pos'] ) {
				echo '<i class="wpr-extra-icon-right '. esc_attr( $settings['popup_trigger_extra_icon'] ) .'"></i>';
			}
		echo '</div>';

	}
	
}