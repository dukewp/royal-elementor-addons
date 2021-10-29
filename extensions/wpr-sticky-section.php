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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Sticky_Section {

    public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );
    }

    public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() ) ) {

			$element->start_controls_section (
				'wpr_section_sticky_section',
				[
					'tab'   => Controls_Manager::TAB_ADVANCED,
					'label' => esc_html__( 'Sticky section - Royal Addons', 'wpr-addons' ),
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
            
			$element->add_control (
				'position_type',
				[
					'label' => __( 'Position', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'sticky',
					'options' => [
						'sticky'  => __( 'Stick on Scroll', 'wpr-addons' ),
						'fixed' => __( 'Fixed by Default', 'wpr-addons' ),
					],
                    'selectors' => [
						'{{WRAPPER}}' => 'position: {{VALUE}};',
                    ],
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
					'options' => [
						'top' => __( 'Top', 'wpr-addons' ),
						'bottom'  => __( 'Bottom', 'wpr-addons' ),
					],
                    'selectors' => [
                        '{{WRAPPER}}' => '{{VALUE}}: {{position_offset.VALUE}};',
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
					'default' => 0,
					'min' => 0,
					'max' => 500,
					'required' => true,
					'render_type' => 'template',
					'frontend_available' => true,
					'widescreen_default' => 0,
					'desktop_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
					'prefix_class' => 'wpr-offset-%s',
                    'selectors' => [
                        '{{WRAPPER}}' => '{{position_location.VALUE}}: {{VALUE}}px;', // add to wrapper .wpr-sticky-section-yes
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
                
            $element->add_control(
                'wpr_z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
					'max' => 99999,
					'step' => 1,
                    'default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					]
                ]
            );

            $element->end_controls_section();            
        }
    }
    
    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' ) {
            return;
        }

        $settings = $element->get_settings_for_display();
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}

		ob_start();
		
		// how to render attributes without creating new div using view.addRenderAttributes
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new Wpr_Sticky_Section();
