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
					'label' => esc_html__( 'Enable Sticky section', 'wpr-addons' ),
					'default' => 'no',
					'return_value' => 'yes',
					'prefix_class' => 'wpr-sticky-section-',
					'render_type' => 'template',
				]
			);

			$element->add_control(
				'disable_sticky_devices',
				[
					'label' => esc_html__( 'Enable on Devices', 'wpr-addons' ),
					'label_block' => true,
					'type' => Controls_Manager::SELECT2,
					'options' => [
						'mobile_sticky' => esc_html__('Mobile', 'wpr-addons'),
						'mobile_extra_sticky' => esc_html__('Mobile_Extra', 'wpr-addons'),
						'tablet_sticky' => esc_html__('Tablet', 'wpr-addons'),
						'tablet_extra_sticky' => esc_html__('Tablet_Extra', 'wpr-addons'),
						'laptop_sticky' => esc_html__('Laptop', 'wpr-addons'),
						'desktop_sticky' => esc_html__('Desktop', 'wpr-addons'),
						'widescreen_sticky' => esc_html__('Widescreen', 'wpr-addons')
					],
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
						'sticky'  => __( 'Sticky', 'wpr-addons' ),
						'fixed' => __( 'Fixed', 'wpr-addons' ),
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
					'default' => 0,
					'widescreen_default' => 0,
					'laptop_default' => 0,
					'tablet_extra_default' => 0,
					'tablet_default' => 0,
					'mobile_extra_default' => 0,
					'mobile_default' => 0,
                    'selectors' => [
                        '{{WRAPPER}}.wpr-sticky-section-yes' => '{{position_location.VALUE}}: {{VALUE}}px;',
                    ],
					'condition' => [
						'enable_sticky_section' => 'yes'
					],
				]
			);
                
            $element->add_control(
                'z_index',
                [
                    'label' => esc_html__( 'Z-Index', 'elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99,
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

        if ( $settings['enable_sticky_section'] === 'yes' ) {
            $element->add_render_attribute( '_wrapper', [
                'data-wpr-sticky-section' => $settings['enable_sticky_section'],
                'data-wpr-position-type' => $settings['position_type'],
                'data-wpr-position-offset' => $settings['position_offset'],
                'data-wpr-position-location' => $settings['position_location'],
				'data-wpr-sticky-devices' => $settings['disable_sticky_devices']
            ] );
        }
    }

    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}

		ob_start();
        ?>
            <# if ( 'yes' == settings.enable_sticky_section) { #>
                <div class="wpr-sticky-section-yes-editor" data-wpr-sticky-section={{settings.enable_sticky_section}} data-wpr-position-type={{settings.position_type}} data-wpr-position-offset={{settings.position_offset}} data-wpr-position-location={{settings.position_location}} data-wpr-sticky-devices={{settings.disable_sticky_devices}}></div>
            <# } else { #>
                <div></div>
            <# } #>    
        <?php
		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}
}

new Wpr_Sticky_Section();
