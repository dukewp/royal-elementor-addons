<?php

namespace WprAddons\Extensions\customCss;

use \Elementor\Controls_Manager;


if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly.

class Wpr_Custom_CSS {

	/*
	 * Instance of this class
	 */
	private static $instance = null;

	public function __construct()
	{
		// Add new controls to advanced tab globally
		add_action("elementor/element/after_section_end", array($this, 'wpr_add_section_custom_css_controls'), 25, 3);

		// Render the custom CSS
		if (!defined('ELEMENTOR_PRO_VERSION')) {
			add_action('elementor/element/parse_css', array($this, 'wpr_add_post_css'), 10, 2);
		}
	}

	public function wpr_add_section_custom_css_controls($widget, $section_id, $args)
	{
		if ('section_custom_css_pro' !== $section_id) {
			return;
		}
		if (!defined('ELEMENTOR_PRO_VERSION')) {

			$widget->start_controls_section(
				'wpr_custom_css_section',
				array(
					'label'     =>  __('WPR Custom CSS', 'wpr-addons'),
					'tab'       => Controls_Manager::TAB_LAYOUT
				)
			);

			$widget->add_control(
				'wpr_custom_css',
				array(
					'type'        => Controls_Manager::CODE,
					'label'       => __('Custom CSS', 'wpr-addons'),
					'label_block' => true,
					'language'    => 'css'
				)
			);

			$widget->add_control(
				'wpr_custom_css_description',
				array(
					'raw'             => __('Use "selector" keyword to target wrapper element.', 'wpr-addons'),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
					'separator'       => 'none'
				)
			);

			$widget->end_controls_section();
		}
	}



	public function wpr_add_post_css($post_css, $element)
	{
		if(get_option('wpr-custom-css-toggle') !== 'on') {
			return;
		}

		$element_settings = $element->get_settings();

		if (empty($element_settings['wpr_custom_css'])) {
			return;
		}

		$css = trim($element_settings['wpr_custom_css']);

		if (empty($css)) {
			return;
		}

		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}
}

new Wpr_Custom_CSS();

