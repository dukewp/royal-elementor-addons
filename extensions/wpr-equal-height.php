<?php
use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Equal_Height {
	public function __construct() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/section/print_template', array( $this, '_print_template' ), 10, 2 );
		add_action( 'elementor/frontend/section/before_render', array( $this, '_before_render' ), 10, 1 );

	}
    
    public function register_controls( $element ) {

		$element->start_controls_section(
			'wpr_section_equal_height',
			[
				'tab'   => Controls_Manager::TAB_ADVANCED,
                'label' =>  sprintf(esc_html__('Equal Height - %s', 'wpr-addons'), Utilities::get_plugin_name()),
            ]
		);

        $element->add_control(
            'wpr_section_equal_height_update',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );

		$element->add_control (
			'wpr_enable_equal_height',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Equal Height', 'wpr-addons' ),
				'default' => 'no',
				'return_value' => 'yes',
				'prefix_class' => 'wpr-equal-height-',
				'render_type' => 'template',
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

		$settings = $element->get_settings_for_display();

		if ( 'yes' === $settings['wpr_enable_equal_height'] ) {

			// $target_type = $settings['premium_eq_height_type'];

			// $target = ( 'custom' === $target_type ) ? explode( ',', $settings['premium_eq_height_custom_target'] ) : $settings['premium_eq_height_target'];

			// $addon_settings = array(
			// 	'targetType' => $target_type,
			// 	'target'     => $target,
			// 	'enableOn'   => $settings['premium_eq_height_enable_on'],
			// );

			// $element->add_render_attribute( '_wrapper', 'data-pa-eq-height', wp_json_encode( $addon_settings ) );
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