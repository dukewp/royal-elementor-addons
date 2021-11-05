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
				'label' => __( 'Enable Progress Bar Globally', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'label_on' => __( 'On', 'wpr-addons' ),
				'label_off' => __( 'Off', 'wpr-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'wpr_rpb_enable' => 'yes',
				],
			]
		);

		$element->add_control(
			'wpr_rpb_display_on',
			[
				'label' => __( 'Display Progress Bar On', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'page',
				'options' => [
					'page' => __( 'All Pages', 'wpr-addons' ),
					'post' => __( 'All Posts', 'wpr-addons' ),
					'global' => __( 'Globally', 'wpr-addons' ),
				],
				'separator' => 'after',
				'condition' => [
					'wpr_rpb_enable_globally' => 'yes',
				],
			]
		);

		$element->add_control(
			'wpr_height',
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

		$element->add_control(//wpr_rpb_position
			'progress_bar_position',
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
					'{{WRAPPER}} .wpr-progress-container' => '{{VALUE}}',
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
        // $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
		update_option('wpr_progress_bar_global_options', [
			'wpr_rpb_enable' => $settings['wpr_rpb_enable'],
			'wpr_rpb_enable_globally_option' => $settings['wpr_rpb_enable_globally'],
			'wpr_rpb_display_option' => $settings['wpr_rpb_display_on'],
			'wpr_height' => $settings['wpr_height'],
			'wpr_background_color' => $settings['wpr_background_color'],
			'wpr_fill_color' => $settings['wpr_fill_color'],
			'wpr_progress_bar_position' => $settings['progress_bar_position'],
		]);

	}

    public function html_to_footer() {
    	$settings = get_option('wpr_progress_bar_global_options');
		$rpb_position = 'top' === $settings['wpr_progress_bar_position'] ? 'top: 0px; bottom: auto;' : 'bottom: 0px; top: auto;';
		$rpb_background_color = $settings['wpr_background_color'];
		$rpb_height = 'height: '.$settings['wpr_height']['size'] . $settings['wpr_height']['unit'].';';
		$rpb_fill_color = 'background-color: ' . $settings['wpr_fill_color'] . '!important;';
    	if ( !empty($settings) ) {
    		if ( 'yes' === $settings['wpr_rpb_enable'] && 'yes' !== $settings['wpr_rpb_enable_globally_option']  ) {
				// $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
				echo '<div class="wpr-progress-container"><div class="wpr-progress-bar wpr-mybar" id="wpr-mybar"></div></div>';
			} else if ( 'yes' === $settings['wpr_rpb_enable_globally_option'] && get_post_type() === $settings['wpr_rpb_display_option'] ) {
				var_dump($settings['wpr_rpb_enable_globally_option']);
				// get_post_type() === $settings['wpr_rpb_display_on']
				echo '<div style="'. $rpb_position .'  background: '. $rpb_background_color .'; " class="wpr-progress-container"><div style="'.$rpb_fill_color . $rpb_height .'" class="wpr-progress-bar wpr-mybar" id="wpr-mybar"></div></div>';
				// if( is_page() ) {
				// 	// style="'. $rpb_position .'"
				// }
			} else if ( 'global' === $settings['wpr_rpb_display_option'] ) {
				echo '<div style="'. $rpb_position .'  background: '. $rpb_background_color .'; " class="wpr-progress-container"><div style="'.$rpb_fill_color . $rpb_height .'" class="wpr-progress-bar wpr-mybar" id="wpr-mybar"></div></div>';
			}
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