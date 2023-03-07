<?php
namespace WprAddons\Modules\FormBuilder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Form_Builder extends Widget_Base {
	
	public function get_name() {
		return 'wpr-forms';
	}

	public function get_title() {
		return esc_html__( 'Form Builder', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'cf7', 'contact form 7', 'caldera forms', 'ninja forms', 'wpforms', 'wp forms' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-forms-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_forms_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        echo 'Widget Works';
    }
}