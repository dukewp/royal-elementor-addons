<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Base\Module;
use Elementor\Core\Kits\Documents\Tabs\Settings_Layout;
use Elementor\Core\Responsive\Files\Frontend;
use Elementor\Plugin;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Breakpoints\Manager;
use Elementor\Core\Breakpoints;
use Elementor\Group_Control_Box_Shadow;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Sticky_Section {

    public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

        // FLEXBOX
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action('elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1);
    }

    public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

			$element->start_controls_section (
				'wpr_section_sticky_section',
				[
					'tab'   => Controls_Manager::TAB_ADVANCED,
					'label' =>  sprintf(esc_html__('Sticky Section - %s', 'wpr-addons'), Utilities::get_plugin_name()),
				]
			);

			$element->add_control(
				'wpr_sticky_apply_changes',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
					'separator' => 'after'
				]
			);

			$element->add_control (
				'enable_sticky_section',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Make This Section Sticky', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'prefix_class' => 'wpr-sticky-section-',
					'render_type' => 'template',
				]
			);

			$element->add_control(
				'enable_on_devices',
				[
					'label' => esc_html__( 'Enable on Devices', 'wpr-addons' ),
					'label_block' => true,
					'type' => Controls_Manager::SELECT2,
					'default' => ['desktop_sticky'],
					'options' => $this->breakpoints_manager(),
					'multiple' => true,
					'separator' => 'before',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],

				]
			);
            
			$element->add_control (
				'position_type',
				[
					'label' => __( 'Position Type', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Stick on Scroll', 'wpr-addons' ),
						'fixed' => __( 'Fixed by Default', 'wpr-addons' ),
					],
                    'selectors' => [
						'{{WRAPPER}}' => 'position: {{VALUE}};',
                    ],
					'render_type' => 'template',
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
            
			$element->add_control (
				'position_location',
				[
					'label' => __( 'Location', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'top',
					'render_type' => 'template',
					'options' => [
						'top' => __( 'Top', 'wpr-addons' ),
						'bottom'  => __( 'Bottom', 'wpr-addons' ),
					],
					// 'selectors_dictionary' => [
					// 	'top' => 'top: {{position_offset.VALUE}}px; bottom: auto;',
					// 	'bottom' => 'bottom: {{position_offset.VALUE}}px; top: auto;'
					// ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{VALUE}}: {{position_offset.VALUE}}px;',
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);
			
			$element->add_responsive_control(
				'position_offset',
				[
					'label' => __( 'Offset', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 500,
					'required' => true,
					'frontend_available' => true,
					'render_type' => 'template',
					'default' => 0,
					'widescreen_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: auto; bottom: auto; {{position_location.VALUE}}: {{VALUE}}px;',
                        '.wpr-hidden-header' => 'top: {{VALUE}}px;'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
                
            $element->add_control(
                'wpr_z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'wpr-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
					'max' => 99999,
					'step' => 1,
                    'default' => 10,
                    'selectors' => [
						'{{WRAPPER}}' => 'z-index: {{VALUE}};',
                        '.wpr-hidden-header' => 'z-index: {{VALUE}};'
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
                ]
            );

			$element->add_control(
				'custom_breakpoints',
				[
					'label' => __( 'Breakpoints', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => get_option('elementor_experiment-additional_custom_breakpoints'),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			$element->add_control(
				'active_breakpoints',
				[
					'label' => __( 'Active Breakpoints', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => $this->breakpoints_manager_active(),
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
				]
			);

			$element->add_control(
				'sticky_advanced_options_heading',
				[
					'label' => esc_html__( 'Advanced', 'wpr-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'position_location'	=> 'top'
					],
				]
			);

			// All pro
			$element->add_control (
				'sticky_advanced_options',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Enable Advanced Options', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'position_location'	=> 'top'
					]
				]
			);
			
			$element->add_responsive_control(
				'wpr_sticky_effects_offset',
				[
					'label' => __( 'Scroll Top Distance', 'wpr-addons' ), // SHOULD WORK AFTER STICKING
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'required' => true,
					'frontend_available' => true,
					'render_type' => 'template',
					'default' => 0,
					'widescreen_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top'
					]
				]
			);

			$element->add_control ( // NEXT HIDDEN SECTION
				'sticky_replace_header',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Replace with New Section', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top'
					],
					'separator' => 'before'
				]
			);

			$element->add_control (
				'sticky_background',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Enable Custom Colors', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			$element->add_control(
				'sticky_text_color',
				[
					'label' => esc_html__( 'Text Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-header *' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_background' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					]
				]
			);

			// $element->add_control(
			// 	'sticky_logo_color',
			// 	[
			// 		'label' => esc_html__( 'Logo Color', 'wpr-addons' ),
			// 		'type' => Controls_Manager::COLOR,
			// 		'selectors' => [
			// 			'{{WRAPPER}}.wpr-sticky-header .wpr-logo' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
			// 			'{{WRAPPER}}.wpr-sticky-header .wpr-logo *' => 'color: {{VALUE}} !important;'
			// 		],
			// 		'condition' => [
			// 			'sticky_background' => 'yes',
			// 			'sticky_advanced_options' => 'yes'
			// 		]
			// 	]
			// );

			$element->add_control(
				'sticky_background_color',
				[
					'label' => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-header' => 'background-color: {{VALUE}} !important; z-index: 9999 !important;',
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_background' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					]
				]
			);

			$element->add_control (
				'sticky_logo_scale',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Logo Scale', 'wpr-addons' ), // Show Number Input 0.7 default
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			$element->add_control(
				'sticky_logo_scale_size',
				[
					'label' => esc_html__( 'Logo Size', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.7,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-header .wpr-logo' => 'transform: scale({{VALUE}}); transform-origin: left;', // ADD CONTROL FOR ANIMATION TIMINGS
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_logo_scale' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					]
				]
			);

			$element->add_control (
				'sticky_hide',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Show on Scrolling Up', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top'
						// 'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			// $element->add_control(
			// 	'sticky_replace_header_id',
			// 	[
			// 		'label' => esc_html__( 'Section ID', 'wpr-addons' ),
			// 		'type' => Controls_Manager::TEXT,
			// 		'condition' => [
						// 'enable_sticky_section' => 'yes',
						// 'sticky_advanced_options' => 'yes',
			// 			'sticky_replace_header' => 'yes'
			// 		]
			// 	]
			// );

			$element->add_responsive_control( // TRANSITION
				'sticky_animation',
				[
					'label' => esc_html__( 'Select Animation', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none' => 'None',
						'fade' => 'Fade',
						'slide' => 'Slide'
					],
					'frontend_available' => true,
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top'
					],
					'separator' => 'before'
				]
			);

			$element->add_control(
				'sticky_animation_duration',
				[
					'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.3,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}}' => '--wpr-animation-duration: {{VALUE}}s', // ADD CONTROL FOR ANIMATION TIMINGS

					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_animation' => ['fade', 'slide']
					]
				]
			);

			$element->add_control(
				'sticky_trans_duration',
				[
					'label' => esc_html__( 'Transition', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.3,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-section-yes' => 'transition: all {{VALUE}}s ease-in-out !important;', // ADD CONTROL FOR ANIMATION TIMINGS
						'{{WRAPPER}} .wpr-logo' => 'transition: all {{VALUE}}s ease-in-out !important;', // ADD CONTROL FOR ANIMATION TIMINGS
						'{{WRAPPER}} *' => 'transition: all {{VALUE}}s ease-in-out !important;'
						// '{{WRAPPER}}' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;',
						// '{{WRAPPER}} *' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;'

					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			// $element->add_responsive_control(
			// 	'sticky_hide_distance',
			// 	[
			// 		'type' => Controls_Manager::SLIDER,
			// 		'label' => esc_html__( 'Scroll Distance', 'wpr-addons' ),
			// 		'frontend_available' => true,
			// 		'range' => [
			// 			'px' => [
			// 				'min' => 0,
			// 				'max' => 500,
			// 			]
			// 		],
			// 		'default' => [
			// 			'size' => 50,
			// 		],
			// 		'condition' => [
			// 			'sticky_hide' => 'yes'
			// 		]
			// 	]
			// );

			$element->add_control ( // NEXT HIDDEN SECTION
				'sticky_shrink_section',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Shrink Section', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			$element->add_responsive_control(
				'sticky_shrink_size',
				[
					'type' => Controls_Manager::SLIDER,
					'label' => esc_html__( 'Section Height', 'wpr-addons' ),
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
						]
					],
					'default' => [
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-header .elementor-container' => 'min-height: {{SIZE}}{{UNIT}} !important;',
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'position_location'	=> 'top',
						'sticky_advanced_options' => 'yes',
						'sticky_shrink_section' => 'yes',
						'sticky_replace_header!' => 'yes'
					]
				]
			);

			$element->add_control ( // NEXT HIDDEN SECTION
				'wpr_sticky_section_border',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Custom Border', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			$element->add_control(
				'wpr_sticky_section_border_type',
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
						'{{WRAPPER}}.wpr-sticky-header' => 'border-style: {{VALUE}};'
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes',
						'wpr_sticky_section_border' => 'yes'
					]
				]
			);

			$element->add_control(
				'wpr_sticky_section_border_color',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}}.wpr-sticky-header' => 'border-color: {{VALUE}}'
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes',
						'wpr_sticky_section_border' => 'yes',
						'wpr_sticky_section_border_type!' => 'none'
					]
				]
			);

			$element->add_control(
				'wpr_sticky_section_border_width',
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
						'{{WRAPPER}}.wpr-sticky-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes',
						'wpr_sticky_section_border' => 'yes',
						'wpr_sticky_section_border_type!' => 'none'
					]
				]
			);

			$element->add_control(
				'wpr_sticky_section_border_radius',
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
						'{{WRAPPER}}.wpr-sticky-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes',
						'wpr_sticky_section_border' => 'yes',
						'wpr_sticky_section_border_type!' => 'none'
					]
				]
			);

			$element->add_control ( // NEXT HIDDEN SECTION
				'wpr_sticky_section_bs',
				[
					'type' => Controls_Manager::SWITCHER,
					'label' => esc_html__( 'Custom Shadow', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes'
					],
					'separator' => 'before'
				]
			);

			$element->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'wpr_sticky_section_box_shadow',
					'selector' => '{{WRAPPER}}.wpr-sticky-header',
					'condition' => [
						'enable_sticky_section' => 'yes',
						'sticky_advanced_options' => 'yes',
						'position_location'	=> 'top',
						'sticky_replace_header!' => 'yes',
						'wpr_sticky_section_bs' => 'yes'
					]
				]
			);

            $element->end_controls_section();            
        }
    }

	public function breakpoints_manager() {
		$active_breakpoints = [];
		foreach ( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $value ) {
			$active_breakpoints[$key . '_sticky'] = esc_html__(ucwords(preg_replace('/_/i', ' ', $key)), 'wpr-addons');
		}

		$active_breakpoints['desktop_sticky'] = esc_html__('Desktop', 'wpr-addons');
		return $active_breakpoints;
	}

	public function breakpoints_manager_active() {
		$active_breakpoints = [];

		foreach ( $this->breakpoints_manager() as $key => $value ) {
			array_push($active_breakpoints, $key);
		}

		return $active_breakpoints;
	}
    
    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
            return;
        }
		
        $settings = $element->get_settings_for_display();

		if ($settings['enable_sticky_section'] !== 'yes') return;

		$wpr_sticky_effects_offset_widescreen = isset($settings['wpr_sticky_effects_offset_widescreen']) && !empty($settings['wpr_sticky_effects_offset_widescreen']) ? $settings['wpr_sticky_effects_offset_widescreen'] : $settings['wpr_sticky_effects_offset'];
		$wpr_sticky_effects_offset_desktop = isset($settings['wpr_sticky_effects_offset']) && !empty($settings['wpr_sticky_effects_offset']) ? $settings['wpr_sticky_effects_offset'] : $wpr_sticky_effects_offset_widescreen;
		$wpr_sticky_effects_offset_laptop =  isset($settings['wpr_sticky_effects_offset_laptop']) && !empty($settings['wpr_sticky_effects_offset_laptop']) ? $settings['wpr_sticky_effects_offset_laptop'] : $wpr_sticky_effects_offset_desktop;
		$wpr_sticky_effects_offset_tablet_extra =  isset($settings['wpr_sticky_effects_offset_tablet_extra']) && !empty($settings['wpr_sticky_effects_offset_tablet_extra']) ? $settings['wpr_sticky_effects_offset_tablet_extra'] : $wpr_sticky_effects_offset_laptop;
		$wpr_sticky_effects_offset_tablet =  isset($settings['wpr_sticky_effects_offset_tablet']) && !empty($settings['wpr_sticky_effects_offset_tablet']) ? $settings['wpr_sticky_effects_offset_tablet'] : $wpr_sticky_effects_offset_tablet_extra;
		$wpr_sticky_effects_offset_mobile_extra =  isset($settings['wpr_sticky_effects_offset_mobile_extra']) && !empty($settings['wpr_sticky_effects_offset_mobile_extra']) ? $settings['wpr_sticky_effects_offset_mobile_extra'] : $wpr_sticky_effects_offset_tablet;
		$wpr_sticky_effects_offset_mobile =  isset($settings['wpr_sticky_effects_offset_mobile']) && !empty($settings['wpr_sticky_effects_offset_mobile']) ? $settings['wpr_sticky_effects_offset_mobile'] : $wpr_sticky_effects_offset_mobile_extra;
		
        if ( $settings['enable_sticky_section'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', [
                'data-wpr-sticky-section' => $settings['enable_sticky_section'],
                'data-wpr-position-type' => $settings['position_type'],
                'data-wpr-position-offset' => $settings['position_offset'],
                'data-wpr-position-location' => $settings['position_location'],
				'data-wpr-sticky-devices' => $settings['enable_on_devices'],
				'data-wpr-custom-breakpoints' => $settings['custom_breakpoints'],
				'data-wpr-active-breakpoints' => $this->breakpoints_manager_active(),
				'data-wpr-z-index' => $settings['wpr_z_index'],
				'data-wpr-sticky-hide' => $settings['sticky_hide'],
				'data-wpr-replace-header' => $settings['sticky_replace_header'],
				'data-wpr-animation-duration' => $settings['sticky_animation_duration'],
				// 'data-wpr-offset-settings' => wp_json_encode([
				// 	'widescreen' => $wpr_sticky_effects_offset_widescreen,
				// 	'desktop' => $wpr_sticky_effects_offset_desktop,
				// 	'laptop' => $wpr_sticky_effects_offset_laptop,
				// 	'tablet_extra' => $wpr_sticky_effects_offset_tablet_extra,
				// 	'tablet' => $wpr_sticky_effects_offset_tablet,
				// 	'mobile_extra' => $wpr_sticky_effects_offset_mobile_extra,
				// 	'mobile' => $wpr_sticky_effects_offset_mobile
				// ])
            ] );
        }
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}

		ob_start();

		?>

		<# if ( 'yes' === settings.enable_sticky_section) { #>
			<div class="wpr-sticky-section-yes-editor" data-wpr-z-index={{{settings.wpr_z_index}}} data-wpr-sticky-section={{{settings.enable_sticky_section}}} data-wpr-position-type={{{settings.position_type}}} data-wpr-position-offset={{{settings.position_offset}}} data-wpr-position-location={{{settings.position_location}}} data-wpr-sticky-devices={{{settings.enable_on_devices}}} data-wpr-custom-breakpoints={{{settings.custom_breakpoints}}} data-wpr-active-breakpoints={{{settings.active_breakpoints}}} data-wpr-sticky-animation={{{settings.sticky_animation}}}  data-wpr-offset-settings={{{settings.wpr_sticky_effects_offset}}}></div>
		<# } #>   

		<?php
		
		// how to render attributes without creating new div using view.addRenderAttributes
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new Wpr_Sticky_Section();