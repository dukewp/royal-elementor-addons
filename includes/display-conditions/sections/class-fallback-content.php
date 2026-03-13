<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Fallback_Content extends WPR_DC_Section_Base {

	public function get_id() {
		return 'wpr_dc_fallback';
	}

	public function get_label() {
		return esc_html__( 'Fallback Content', 'wpr-addons' );
	}

	public function get_order() {
		return 160;
	}

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_fallback_enabled',
			[
				'label'        => esc_html__( 'Show Fallback Content', 'wpr-addons' ),
				'description'  => esc_html__( 'Display alternative content when this element is hidden by visibility conditions.', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type'  => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_type',
			[
				'label'     => esc_html__( 'Fallback Type', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'text',
				'options'   => [
					'text'     => esc_html__( 'Custom Text', 'wpr-addons' ),
					'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_dc_fallback_enabled' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_text',
			[
				'label'     => esc_html__( 'Fallback Text', 'wpr-addons' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => '',
				'condition' => [
					'wpr_dc_fallback_enabled' => 'yes',
					'wpr_dc_fallback_type'    => 'text',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_template',
			[
				'label'       => esc_html__( 'Choose Template', 'wpr-addons' ),
				'type'        => 'wpr-ajax-select2',
				'options'     => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition'   => [
					'wpr_dc_fallback_enabled' => 'yes',
					'wpr_dc_fallback_type'    => 'template',
				],
				'render_type' => 'none',
			]
		);
	}

	/**
	 * Fallback doesn't evaluate — it's used by the render handler.
	 */
	public function evaluate( $settings ) {
		return null;
	}
}
