<?php

namespace WprAddons\Extensions\Wpr_ReadingProgressBar;

// Elementor classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_ReadingProgressBar {

    private static $_instance = null;

    public final function __construct() {
		// Register controls on Post/Page Settings
		add_action( 'elementor/documents/register_controls', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/editor/after_save', [ $this, 'save_global_values' ], 10, 2 );
        add_action( 'wp_footer', [ $this, 'html_to_footer' ] );
	}
    
    public function register_controls( $element ) {
        
		$element->start_controls_section(
			'wpr_reading_progress_bar',
			[
                'tab' => Controls_Manager::TAB_SETTINGS,
				'label' => __( 'Reading Progress Bar - Royal Addons', 'wpr-addons' ),
			]
        );

		$element->add_control(
			'wpr_rpb_enable',
			[
				'label' => __( 'Enable Progress Bar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'label_on' => __( 'On', 'wpr-addons' ),
				'label_off' => __( 'Off', 'wpr-addons' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'wpr_rpb_enable_globally',
			[
				'label' => __( 'Enable Progress Bar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'label_on' => __( 'On', 'wpr-addons' ),
				'label_off' => __( 'Off', 'wpr-addons' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'wpr_rpb_display_on',
			[
				'label' => __( 'Display Progress Bar On', 'wpr' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'page',
				'options' => [
					'page' => __( 'All Pages', 'wpr' ),
					'post' => __( 'All Posts', 'wpr' ),
					'global' => __( 'Globally', 'wpr' ),
				],
				'separator' => 'after',
				'condition' => [
					'wpr_rpb_enable_globally' => 'yes',
				],
			]
		);

		$element->add_control(
			'wpr_rpb_height',
			[
				'label' => __( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'condition' => [
					'wpr_rpb_enable' => 'yes',
				],
				'selectors' => [
					'.wpr-progress-container' => 'height: {{SIZE}}{{UNIT}} !important',
					'.wpr-progress-container .wpr-progress-bar' => 'height: {{SIZE}}{{UNIT}} !important',
				],
			]
		);

		$element->add_control(
			'wpr_background_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#C5C5C6',
				'condition' => [
					'wpr_rpb_enable' => 'yes',
				],
				'selectors' => [
					'.wpr-progress-container' => 'background-color: {{VALUE}};'
				]
			]
		);

		$element->add_control(
			'wpr_fill_color',
			[
				'label' => __( 'Fill Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#6A63DA',
				'condition' => [
					'wpr_rpb_enable' => 'yes',
				],
				'selectors' => [
					'.wpr-progress-container .wpr-progress-bar' => 'background-color: {{VALUE}};'
				]
			]
		);

		$element->add_control(
			'wpr_rpb_position',
			[
				'label' => __( 'Position', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'top',
				'render_type' => 'template',
				'options' => [
					'top' => __( 'Top', 'wpr-addons' ),
					'bottom' => __( 'Bottom', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'top' => 'top: 0px; bottom: auto;',
					'bottom' => 'bottom: 0px; top: auto;',
				],
				'selectors' => [
					'.wpr-progress-container' => '{{VALUE}} !important',
					'{{WRAPPER}}' => '{{VALUE}} !important',
				],
				'condition' => [
					'wpr_rpb_enable' => 'yes',
				],
			]
		);

        $element->end_controls_section();
        
	}

	public function save_global_values( $post_id, $editor_data ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		$settings = $document->get_settings();
		update_option('wpr_rpb_global_options', [
			'wpr_rpb_enable' => $settings['wpr_rpb_enable'],
			'wpr_rpb_enable_globally' => $settings['wpr_rpb_enable_globally'],
			'wpr_rpb_height' => $settings['wpr_rpb_height'],
			'wpr_background_color' => $settings['wpr_background_color'],
			'wpr_fill_color' => $settings['wpr_fill_color'],
			'wpr_rpb_position' => $settings['wpr_rpb_position'],
			'wpr_rpb_display_on' => $settings['wpr_rpb_display_on'],
		]);

	}

    public function html_to_footer() {
    	$settings = get_option('wpr_rpb_global_options');
		$page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );

		$rpb_position = 'top' === $settings['wpr_rpb_position'] ? 'top: 0px; bottom: auto' : 'bottom: 0px; top: auto';
    	if ( !empty($settings) ) {
    		if ( 'yes' === $settings['wpr_rpb_enable'] ) {
				// style="'. $rpb_position .'"
				echo '<div data-style="'. $rpb_position .'" class="wpr-progress-container"><div class="wpr-progress-bar" id="wpr-mybar"></div></div></div>';
			}

			// if ( 'yes' === $settings['wpr_rpb_enable_globally'] ) {
			// 	if( get_post_type() === $settings['wpr_rpb_display_on'] ) {
			// 		// style="'. $rpb_position .'"
			// 		echo '<div class="wpr-progress-container"><div class="wpr-progress-bar" id="wpr-mybar"></div></div></div>';
			// 	}
			// }
    	}
	}

    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Wpr_ReadingProgressBar::instance();