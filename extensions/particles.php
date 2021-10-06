<?php
namespace WprAddons\extensions;

use Elementor\Controls_Manager;

class Particles {

	private static $_instance = null;

	public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'wpr_add_particles' ] );
		// add_action( 'elementor/editor/wp_head', [ $this, 'wpr_add_particles' ] );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );

		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );

		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );

	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' ) {
			return;
		}

		$settings = $element->get_settings();
		if ( $settings['wpr_enable_particles'] === 'yes' ) {
			$element->add_render_attribute( '_wrapper', 'data-wpr-particles', $settings['wpr_particle_json'] );
		}
	}

	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}

		$old_template = $template;
		ob_start();

		?>

		<div class="wpr-particle-wrapper" id="wpr-particle-{{ view.getID() }}" data-wpr-particles-editor=" {{ settings.wpr_particle_json }}"></div>

		<?php

		$slider_content = ob_get_contents();
		ob_end_clean();
		$template = $slider_content . $old_template;
		
		return $template;
	}


	public function register_controls( $element, $section_id, $args ) {

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) || ( 'column' === $element->get_name() && 'section_style' === $section_id ) ) {

			$element->start_controls_section(
				'wpr_particles',
				[
					'tab'   => Controls_Manager::TAB_STYLE,
					'label' => __( 'WPR - Particles', 'wpr-addons' ),
				]
			);
			$element->add_control(
				'wpr_enable_particles',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        => __( 'Enable Particle Background', 'wts-eae' ),
					'default'      => '',
					'label_on'     => __( 'Yes', 'wts-eae' ),
					'label_off'    => __( 'No', 'wts-eae' ),
					'return_value' => 'yes',
					'prefix_class' => 'wpr-particle-',
					'render_type'  => 'template',
				]
			);

			$element->add_control(
				'wpr_particle_json',
				[
					'type'        => Controls_Manager::CODE,
					'label'       => __( 'Add Particle Json', 'wts-eae' ),
					'default'     => '{"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
					'render_type' => 'template',
					'condition'   => [
						'wpr_enable_particles' => 'yes',
					],
				]
			);

            $element->end_controls_section();
        }
    }

    public function wpr_add_particles() {
		wp_enqueue_script( 'wpr-particles', WPR_ADDONS_URL . 'assets/js/lib/particles/particles.js', [ 'jquery' ], '3.0.6', true );
	}

}

$particles = new Particles();