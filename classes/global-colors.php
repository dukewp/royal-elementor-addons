<?php
namespace WprAddons\Classes;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_Global_Colors extends Tab_Base {
	public function get_id() {
		return 'wpr-global-colors';
	}

	public function get_title() {
		return esc_html__( 'Royal Global Colors', 'wpr-addons' );
	}

	public function get_group() {
		return 'global';
	}

	public function get_icon() {
		return 'eicon-global-colors';
	}

	public function get_help_url() {
		return 'https://go.elementor.com/global-colors';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'section_wpr_global_colors',
			[
				'label' => esc_html__( 'Global Colors', 'wpr-addons' ),
				'tab' => $this->get_id(),
			]
		);

		$this->add_control(
			'title',
			[
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'required' => true,
			]
		);

		// Color Value
		$this->add_control(
			'color',
			[
				'type' => Controls_Manager::COLOR,
				'label_block' => true,
				'dynamic' => [],
				'selectors' => [
					'{{WRAPPER}}' => 'wpr-global-colors: {{VALUE}}',
				],
				'global' => [
					'active' => false,
				],
			]
		);

		$this->end_controls_section();
	}
}


if ( 'Royal Addons' === Utilities::get_plugin_name() ) {
    new Wpr_Global_Colors('argument');
}