<?php
namespace WprAddons\Modules\customCss\Widgets;

use Elementor\Core\Schemes\Color;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Repeater;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Core\Files\CSS\Post;
// use ElementorPro\Plugin;



class Wpr_Custom_Css {

	private static $_instance = null;

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/element/parse_css', [ $this, 'add_post_css' ], 10, 2 );
		// add_action( 'elementor/css-file/post/parse', [ $this, 'add_page_settings_css' ] );
	}

	public function add_post_css( $post_css, $element ) {
        if ( $post_css instanceof Dynamic_CSS ) {
            return;
		}
        
		$element_settings = $element->get_settings();
        
		if ( empty( $element_settings['wpr_custom_css'] ) ) {
            return;
		}
        
		$css = trim( $element_settings['wpr_custom_css'] );
        
		if ( empty( $css ) ) {
            return;
		}

		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );
        
		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';
        var_dump($css);

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

	/**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {
		$document = Plugin::elementor()->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'wpr_custom_css' );

		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	public function register_controls( $element, $section_id, $args ) {
        // $old_section = Plugin::elementor()->controls_manager->get_control_from_stack( $controls_stack->get_unique_name(), 'section_custom_css_pro' );

		// Plugin::elementor()->controls_manager->remove_control_from_stack( $controls_stack->get_unique_name(), [ 'section_custom_css_pro', 'custom_css_pro' ] );

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) ) {
		// for custom css

			$element->start_controls_section(
				'wpr_elementor_section_custom_css',
				[
					'label' => __( 'WPR Custom CSS', 'plugin-name' ),
					'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
				]
			);
	
			$element->add_control(
				'wpr_custom_css',
				[
					'label' => __( 'Custom CSS', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::CODE,
					'language' => 'css',
					'render_type' => 'ui',
					'rows' => 20,
				]
			);
	
			$element->end_controls_section();
        }
    }

}

// $wpr_custom_css = new Wpr_Custom_Css();

