<?php
use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Equal_Height {
	public function __construct() {

		// Enqueue the required JS file.
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Create Premium Equal Height tab at the end of section layout tab.
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10 );

		add_action( 'elementor/section/print_template', array( $this, '_print_template' ), 10, 2 );

		// Insert data before section rendering.
		add_action( 'elementor/frontend/section/before_render', array( $this, '_before_render' ), 10, 1 );

		// Check if scripts should be loaded.
		add_action( 'elementor/frontend/section/before_render', array( $this, 'check_script_enqueue' ) );

	}
    
    public function register_controls( $element ) {

		$element->start_controls_section(
			'wpr_section_equal_height',
			[
				'tab'   => Controls_Manager::TAB_ADVANCED,
                'label' =>  sprintf(esc_html__('Equal Height - %s', 'wpr-addons'), Utilities::get_plugin_name()),
            ]
		);

        $element->end_controls_section();

    }

	public function enqueue_scripts() {

		// if ( ! wp_script_is( 'pa-eq-height', 'enqueued' ) ) {
		// 	wp_enqueue_script( 'pa-eq-height' );
		// }

	}

    public function _before_render( $element ) {
        if ( $element->get_name() !== 'section' ) {
            return;
        }
    }
    
    public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}

		ob_start();

		// how to render attributes without creating new div using view.addRenderAttributes
		$equal_height_content = ob_get_contents();

		ob_end_clean();

		return $template . $equal_height_content;
    }

}

new Wpr_Equal_Height();