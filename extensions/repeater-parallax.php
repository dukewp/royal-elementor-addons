<?php
namespace WprAddons\Extensions;

use Elementor\Core\Schemes\Color;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Core\Files\CSS\Post;
// use ElementorPro\Plugin;


class Wpr_Parallax_Hover {

	private static $_instance = null;

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function register_controls( $element, $section_id, $args ) {

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) ) {

			$element->start_controls_section (
				'wpr_parallax_hover',
				[
					'tab'   => Controls_Manager::TAB_LAYOUT,
					'label' => esc_html__( 'WPR - Parallax Hover', 'wpr-addons' ),
				]
			);

			$element->add_control(
				'wpr_enable_parallax_hover',
				[
					'type'  => Controls_Manager::SWITCHER,
					'label' => __('Enable Parallax Hover', 'wpr-addons'),
					'default' => 'no',
					'label_on' => __('Yes', 'wpr-addons'),
					'label_off' => __('No', 'wpr-addons'),
					'return_value' => 'yes',
					'render_type' => 'template',
					'prefix_class' => 'parallax-'
				]
			);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'hover_repeater', [
					'label' => __( 'Hover Repeater', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Repeater Item' , 'wpr-addons' ),
					'label_block' => true,
				]
			);

			$repeater->add_control(
				'repeater_bg_image',
				[
					'label' => __( 'Choose Image', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					],
					'render_type' => 'template',
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
						'{{WRAPPER}} {{CURRENT_ITEM}}.children' => 'left: {{SIZE}}{{UNIT}}!important;',
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
						'{{WRAPPER}} {{CURRENT_ITEM}}.children' => 'top: {{SIZE}}{{UNIT}}!important;',
					],
					'separator' => 'before',
				]
			);

			$repeater->add_control(
				'layer_width',
				[
					'label' => esc_html__( 'width', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 100,
					'min' => 0,
					'max' => 1000,
					'step' => 10,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.children img' => 'width: {{SIZE}}px!important;',
					],		
				]
			);

			$element->add_control(
				'hover_parallax',
				[
					'label' => __( 'Repeater List', 'wpr-addons' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'hover_repeater' => __( 'Title #1', 'wpr-addons' ),
						],
						[
							'hover_repeater' => __( 'Title #2', 'wpr-addons' ),
						],
					],
					'title_field' => '{{{ hover_repeater }}}',
					'condition' => [
						'wpr_enable_parallax_hover' => 'yes'
					]
				]
			);

			$element->add_control(
				'scalar_x',
				[
					'label' => esc_html__( 'Scalar Speed X', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 100.0,
					'min' => 0.0,
					'max' => 1000.0,
					'step' => 10.0,		
				]
			);

			$element->add_control(
				'scalar_y',
				[
					'label' => esc_html__( 'Scalar Speed Y', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 100.0,
					'min' => 0.0,
					'max' => 1000.0,
					'step' => 10.0,		
				]
			);

			$element->end_controls_section();
			// for custom css

		}
    }


	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}
	
		$old_template = $template;
		ob_start();
		?>

			<# if ( settings.hover_parallax.length && settings.wpr_enable_parallax_hover == 'yes') { #>
				<div class="scene" scalar-x="{{settings.scalar_x}}" scalar-y="{{settings.scalar_y}}" data-relative-input="true" style="overflow: hidden;">
				<# _.each( settings.hover_parallax, function( item ) { #>
					<div data-depth="0.2" class="children elementor-repeater-item-{{ item._id }}">	
						<img src="{{item.repeater_bg_image.url}}">
					</div>
				<# }); #>
				</div>
			<# } #>

		<?php

		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' ) {
			return;
		}

		$settings = $element->get_settings();
		if ( $settings['wpr_enable_parallax_hover'] == 'yes' ) {
			$element->add_render_attribute( '_wrapper', [
            ] );
			 if ( $settings['hover_parallax'] ) {
				echo '<div class="scene" scalar-y="'. $settings['scalar_y'] .'" scalar-x="'. $settings['scalar_x'] .'" style="overflow: hidden;">';
			 	foreach (  $settings['hover_parallax'] as $item ) {
					echo '<div data-depth="0.2" style-top="'. $item['layer_position_vr']['size'] .'%" style-left="'. $item['layer_position_hr']['size'] .'%" class="children elementor-repeater-item-' . $item['_id'] . '">
						<span>' . $item['layer_position_hr']['size'] . ' ' . $item['layer_position_vr']['size'] . '</span>
			 			<img src="' . $item['repeater_bg_image']['url'] . '">
			 		</div>';
			 	}
				echo '</div>';
			 }
		}
	}

    public function enqueue_scripts() {
		wp_enqueue_script( 'wpr-parallax-hover', WPR_ADDONS_URL . 'assets/js/lib/parallax/parallax.min.js', [ 'jquery' ], '3.0.6', true );
	}

}

$parallax_hover = new Wpr_Parallax_Hover();