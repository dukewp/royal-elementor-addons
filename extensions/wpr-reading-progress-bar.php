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
			'wpr_enable_rpb',
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
			'wpr_select_view',
			[
				'label' => __( 'Select View', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'view1',
				'options' => [
					'view1' => __( 'View 1', 'wpr-addons' ),
					'view2' => __( 'View 2', 'wpr-addons' ),
				],
				'separator' => 'after',
				'condition' => [
					'wpr_enable_rpb' => 'yes',
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
					'wpr_enable_rpb' => 'yes',
					'wpr_select_view' => 'view1'
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
					'wpr_enable_rpb' => 'yes',
					'wpr_select_view' => 'view1'
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
					'wpr_enable_rpb' => 'yes',
					'wpr_select_view' => 'view1'
				],
				'selectors' => [
					'.wpr-progress-container .wpr-progress-bar' => 'background-color: {{VALUE}};'
				]
			]
		);

		$element->add_control(
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
					'.wpr-progress-container' => '{{VALUE}}',
				],
				'condition' => [
					'wpr_enable_rpb' => 'yes',
					'wpr_select_view' => 'view1'
				],
			]
		);

        $element->end_controls_section();
        
	}

	public function save_global_values( $post_id, $editor_data ) {

		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		$settings = $document->get_settings();
		$integrationOptions = get_option( 'wpr_addons_integration' );
		if ( $settings['wpr_enable_rpb'] == 'yes' ) {

				// Removing global values if disabled
				if( isset( get_option('wpr_addons_integration')['reading-progress-bar-globally'] ) && array_key_exists( $post_id, get_option('wpr_addons_integration')['reading-progress-bar-globally'] ) ) {
					unset( $integrationOptions['reading-progress-bar-globally'] );
				}

		} else {
				if( isset( get_option('wpr_addons_integration')['reading-progress-bar'] ) && array_key_exists( $post_id, get_option('wpr_addons_integration')['reading-progress-bar'] ) ) {
					// removing the disabled RPB
					unset( $integrationOptions['reading-progress-bar'][$post_id] );
				}
		}
		
		update_option( 'wpr_addons_integration', $integrationOptions );

	}
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    public function html_to_footer() {

		$postId = (string) get_the_ID();

		if( isset( get_option('wpr_addons_integration')['reading-progress-bar'] ) && array_key_exists( $postId, get_option('wpr_addons_integration')['reading-progress-bar'] )
		) {
			echo $this->getRpbHTML( get_option('wpr_addons_integration')['reading-progress-bar'][$postId] );
		} 		
	}

    public function getRpbHTML( $options ) {
		var_dump(get_option( 'wpr_addons_integration' ));
        $html = '<div class="wpr-progress-container"><div class="wpr-progress-bar" id="wpr-mybar"></div></div></div>';
    	return $html;
	}
}

Wpr_ReadingProgressBar::instance();
