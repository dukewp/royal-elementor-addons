<?php
namespace WprAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;

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
					'tab'   => Controls_Manager::TAB_STYLE,
					'label' => esc_html__( 'WPR - Parallax Hover', 'wpr-addons' ),
				]
			);


            $element->end_controls_section();
        }
    }


	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}
	
		$old_template = $template;
		ob_start();

		echo '<div class="wpr-particle-wrapper" id="wpr-particle-{{ view.getID() }}" data-wpr-particles-editor="{{ settings[settings.which_particle] }}"></div>';

		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' ) {
			return;
		}

		$settings = $element->get_settings();

		if ( $settings['wpr_enable_particles'] === 'yes' ) {
			$element->add_render_attribute( '_wrapper', 'data-wpr-particles', $settings[$settings['which_particle']] );
		}
	}

    public function enqueue_scripts() {
		wp_enqueue_script( 'wpr-parallax-hover', WPR_ADDONS_URL . 'assets/js/lib/parallax-hover/parallax.js', [ 'jquery' ], '3.0.6', true );
	}

}

$parallax_hover = new Wpr_Parallax_Hover();