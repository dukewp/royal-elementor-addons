<?php
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Parallax_Scroll {

    public function __construct() {
        add_action( 'elementor/element/section/section_background/after_section_end', [$this, 'register_controls'], 10);
        add_action( 'elementor/frontend/section/before_render', [$this, '_before_render'], 10, 1);
        add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function register_controls( $element ) {
        $element->start_controls_section(
            'wpr_section_parallax',
            [
                'tab' => Controls_Manager::TAB_STYLE,
                'label' => __('Parallax - Royal Addons', 'wpr-addons-elementor'),
            ]
        );
        
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {

        $element->add_control(
            'wpr_enable_jarallax',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Background Parallax', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'wpr-jarallax-'
            ]
        );

        $element->add_control(
            'speed',
            [
                'label' => __( 'Animation Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1.0,
                'max' => 2.0,
                'step' => 0.1,
                'default' => 1.4,
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
            ]
        );

        $element->add_control(
			'scroll_effect',
			[
				'label' => __( 'Scrolling Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'scale',
				'options' => [
					'scale'  => esc_html__( 'Zoom', 'wpr-addons' ),
					'scroll' => esc_html__( 'Scroll', 'wpr-addons' ),
					'opacity' => esc_html__( 'Opacity', 'wpr-addons' ),
                    'scale-opacity' => esc_html__('Scale Opacity', 'wpr-addons'),
					'scroll-opacity' => esc_html__( 'Scroll Opacity', 'wpr-addons' )
				],
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
			]
		);

        $element->add_control(
			'bg_image',
			[
				'label' => __( 'Choose Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
			]
		);

        $element->add_control(
            'wpr_parallax',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply Changes</button></div>',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
            ]
        );

        } // end if ( 'on' === get_option('wpr-parallax-background', 'on') ) {

        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {

        $element->add_control(
            'wpr_enable_parallax_hover',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Enable Multi Layer Parallax', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'separator' => 'before',
                'prefix_class' => 'wpr-parallax-'
            ]
        );

        $element->add_control(
            'invert_direction',
            [
                'type'  => Controls_Manager::SWITCHER,
                'label' => __('Invert Animation Direction', 'wpr-addons'),
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'scalar_speed',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Animation Speed', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0.0,
                        'max' => 100.0,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10.0,
                ],
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'repeater_bg_image',
            [
                'label' => __( 'Choose Image', 'wpr-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'render_type' => 'template',
            ]
        );

        $repeater->add_control(
            'layer_width',
            [
                'label' => esc_html__( 'Image Width', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 1000,
                'step' => 10,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'width: {{SIZE}}px !important;',
                ],		
            ]
        );

        $repeater->add_control(
            'layer_position_hr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Horizontal Position (%)', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'left: {{SIZE}}{{UNIT}} !important;',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'layer_position_vr',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Vertical Position (%)', 'wpr-addons' ),
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wpr-parallax-ml-children' => 'top: {{SIZE}}{{UNIT}}!important;',
                ],
            ]
        );

        $element->add_control(
            'hover_parallax',
            [
                'label' => __( 'Repeater List', 'wpr-addons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
						'layer_position_vr' => [
							'unit' => '%',
							'size' => 30,
						],
						'layer_position_hr' => [
							'unit' => '%',
							'size' => 40,
						],
                    ],
                    [
						'layer_position_vr' => [
							'unit' => '%',
							'size' => 60,
						],
						'layer_position_hr' => [
							'unit' => '%',
							'size' => 20,
						],
                    ],
                ],
                'condition' => [
                    'wpr_enable_parallax_hover' => 'yes'
                ]
            ]
        );

        } // end if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {

        $element->end_controls_section();
    }

    public function _before_render( $element ) {
        // bail if any other element but section
        if ( $element->get_name() !== 'section' ) return;

		$settings = $element->get_settings_for_display();

        // Parallax Background
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            if ( 'yes' === $settings['wpr_enable_jarallax'] ) { 
    			$element->add_render_attribute( '_wrapper', [
                    'class' => 'wpr-jarallax',
                    'speed-data' => $settings['speed'],
                    'bg-image' => $settings['bg_image']['url'],
                    'scroll-effect' => $settings['scroll_effect'],
                ] );
            }
        }

        // Parallax Multi Layer
        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
            if ( $settings['wpr_enable_parallax_hover'] == 'yes' ) {
    			 if ( $settings['hover_parallax'] ) {

    				echo '<div class="wpr-parallax-multi-layer" scalar-speed="'. $settings['scalar_speed']['size'] .'" direction="'. $settings['invert_direction'] .'" style="overflow: hidden;">';

    			 	foreach (  $settings['hover_parallax'] as $item ) {
    					echo '<div data-depth="0.2" style-top="'. $item['layer_position_vr']['size'] .'%" style-left="'. $item['layer_position_hr']['size'] .'%" class="wpr-parallax-ml-children elementor-repeater-item-' . $item['_id'] . '">
    			 			<img src="' . $item['repeater_bg_image']['url'] . '">
    			 		</div>';
    			 	}
                     
    				echo '</div>';
    			 }
    		}
        }

    }

    public function _print_template( $template, $widget ) {
		ob_start();

        // Background
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            echo '<div class="wpr-jarallax" speed-data-editor="{{settings.speed}}" scroll-effect-editor="{{settings.scroll_effect}}" bg-image-editor="{{settings.bg_image.url}}"></div>';
        }

        // Multi Layer
        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
        ?>

            <# if ( settings.hover_parallax.length && settings.wpr_enable_parallax_hover == 'yes') { #>
				<div class="wpr-parallax-multi-layer" direction="{{settings.invert_direction}}" scalar-speed="{{settings.scalar_speed.size}}" data-relative-input="true" style="overflow: hidden;">
				<# _.each( settings.hover_parallax, function( item ) { #>
					<div data-depth="0.2" class="wpr-parallax-ml-children elementor-repeater-item-{{ item._id }}">	
						<img src="{{item.repeater_bg_image.url}}">
					</div>
				<# }); #>
				</div>
			<# } #>

        <?php
        }

		$parallax_content = ob_get_contents();

		ob_end_clean();

		return $template . $parallax_content;
	}

    public static function enqueue_scripts() {
        if ( 'on' === get_option('wpr-parallax-background', 'on') ) {
            wp_enqueue_script( 'wpr-jarallax', WPR_ADDONS_URL . 'assets/js/lib/jarallax/jarallax.min.js', ['jquery'], '1.12.7', true );
        }

        if ( 'on' === get_option('wpr-parallax-multi-layer', 'on') ) {
            wp_enqueue_script( 'wpr-parallax-hover', WPR_ADDONS_URL . 'assets/js/lib/parallax/parallax.min.js', ['jquery'], '1.0', true );
        }
    }

}

new Wpr_Parallax_Scroll();
