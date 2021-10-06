<?php
namespace WprAddons\extensions;

use Elementor\Controls_Manager;

class Particles {

	private static $_instance = null;

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
        add_action( 'wp_enqueue_scripts', [ $this, 'wpr_add_particles' ] );
		add_action( 'elementor/editor/wp_head', [ $this, 'wpr_add_particles' ] );

	}

    // public function wpr_add_particles() {
	// 	wp_enqueue_script( 'wpr-particles' );
	// }

    public function wpr_add_particles() {
		wp_enqueue_script( 'wpr-particles', WPR_ADDONS_URL . 'assets/js/lib/particles/particles.js', [ 'jquery' ], '3.0.6', true );
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

            $element->end_controls_section();
        }
    }

}

$particles = new Particles();