<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Render_Templates setup
 *
 * @since 1.0
 */
class WPR_Render_Templates {

	/**
	** Instance of Elemenntor Frontend class.
	*
	** @var \Elementor\Frontend()
	*/
	private static $elementor_instance;

	/**
	** Constructor
	*/
	public function __construct() {

		// Elementor Frontend
		self::$elementor_instance = \Elementor\Plugin::instance();

		// Custom Canvas Template
		// add_filter( 'template_include', [ $this, 'convert_to_canvas_template' ], 12 );

		// Canvas Page Header and Footer 
		add_action( 'get_header', [ $this, 'replace_header' ] );
		add_action( 'get_footer', [ $this, 'replace_footer' ] );

		// Canvas Page Content
		// add_action( 'elementor/page_templates/canvas/wpr_content', [ $this, 'canvas_page_content_display' ], 1 );

		// Scripts and Styles
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

	}


    /**
    ** Canvas Header
    */
    public function replace_header() {
    	$conditions = json_decode( get_option('wpr_header_conditions', '[]'), true );
    	$template = WPR_Conditions_Manager::header_footer_display_conditions( $conditions );
    	if ( ! empty( $conditions ) && ! is_null($template) ) {
			require __DIR__ . '/../templates/views/theme-header.php';

			$templates   = [];
			$templates[] = 'header.php';
			
			remove_all_actions( 'wp_head' ); // Avoid running wp_head hooks again.

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
    }

	/**
	** Canvas Footer
	*/
	public function replace_footer() {
    	$conditions = json_decode( get_option('wpr_footer_conditions', '[]'), true );
    	$template = WPR_Conditions_Manager::header_footer_display_conditions( $conditions );

    	if ( ! empty( $conditions ) && ! is_null($template) ) { 
			require __DIR__ . '/../templates/views/theme-footer.php';

			$templates   = [];
			$templates[] = 'footer.php';
			
			remove_all_actions( 'wp_footer' ); // Avoid running wp_footer hooks again.

			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
	}

	/**
	** Theme Builder Content Display
	*/
	public function canvas_page_content_display() {//TODO: Change and Adapt this function (maybe move to conditions manager)
		// Get Conditions
		$archives = json_decode( get_option( 'wpr_archive_conditions' ), true );
		$archives = is_null( $archives ) ? [] : $archives;
		$singles  = json_decode( get_option( 'wpr_single_conditions' ), true );
		$singles  = is_null( $singles ) ? [] : $singles;

		// Reset
		$template = '';

		// Archive Pages (includes search)
		if ( ! is_null( $this->archive_templates_conditions( $archives ) ) ) {
			$template = $this->archive_templates_conditions( $archives );
		}

    	// Single Pages
		if ( ! is_null( $this->single_templates_conditions( $singles, false ) ) ) {
			$template = $this->single_templates_conditions( $singles, false );
		}

		// Display Template
		Utilities::render_elementor_template( $template );
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		if ( class_exists( '\ElementorPro\Plugin' ) ) {
			$elementor_pro = \ElementorPro\Plugin::instance();
			$elementor_pro->enqueue_styles();
		}

		// Load Header Template CSS File
		$heder_conditions = json_decode( get_option('wpr_header_conditions', '[]'), true );
		$header_template_id = Utilities::get_template_id(WPR_Conditions_Manager::header_footer_display_conditions($heder_conditions));

		if ( false !== $header_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$header_css_file = new \Elementor\Core\Files\CSS\Post( $header_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$header_css_file = new \Elementor\Post_CSS_File( $header_template_id );
			}

			$header_css_file->enqueue();
		}

		// Load Footer Template CSS File
		$footer_conditions = json_decode( get_option('wpr_footer_conditions', '[]'), true );
		$footer_template_id = Utilities::get_template_id(WPR_Conditions_Manager::header_footer_display_conditions($footer_conditions));

		if ( false !== $footer_template_id ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$footer_css_file = new \Elementor\Core\Files\CSS\Post( $footer_template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$footer_css_file = new \Elementor\Post_CSS_File( $footer_template_id );
			}

			$footer_css_file->enqueue();
		}
	}

}