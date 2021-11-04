<?php
namespace WprAddons\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;

class Wpr_Reading_Progress_Bar
{

    public function __construct() {
        add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
        // add_action( 'elementor/editor/after_save', [ $this, 'render_progress_bar' ], 10, 2 );
        add_action('wp_footer', [$this, 'render_progress_bar']);
    }

    public function register_controls($element) {

        $element->start_controls_section(
            'wpr_section_reading_progress_bar',
            [
                'label' => __('Reading Progress Bar - Royal Addons', 'wpr-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'wpr_enable_reading_progress',
            [
                'label' => __('Enable Reading Progress Bar', 'wpr-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wpr-addons'),
                'label_off' => __('No', 'wpr-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        // $element->add_control(
        //     'eael_ext_reading_progress_has_global',
        //     [
        //         'label' => __('Enabled Globally?', 'wpr-addons'),
        //         'type' => Controls_Manager::HIDDEN,
        //         'default' => (isset($global_settings['reading_progress']['enabled']) ? $global_settings['reading_progress']['enabled'] : false),
        //     ]
        // );

        // if (isset($global_settings['reading_progress']['enabled']) && ($global_settings['reading_progress']['enabled'] == true) && get_the_ID() != $global_settings['reading_progress']['post_id'] && get_post_status($global_settings['reading_progress']['post_id']) == 'publish') {
        //     $element->add_control(
        //         'eael_global_warning_text',
        //         [
        //             'type' => Controls_Manager::RAW_HTML,
        //             'raw' => __('You can modify the Global Reading Progress Bar by <strong><a href="' . get_bloginfo('url') . '/wp-admin/post.php?post=' . $global_settings['reading_progress']['post_id'] . '&action=elementor">Clicking Here</a></strong>', 'wpr-addons'),
        //             'content_classes' => 'eael-warning',
        //             'separator' => 'before',
        //             'condition' => [
        //                 'eael_ext_reading_progress' => 'yes',
        //             ],
        //         ]
        //     );
        // } else {
        //     $element->add_control(
        //         'eael_ext_reading_progress_global',
        //         [
        //             'label' => __('Enable Reading Progress Bar Globally', 'wpr-addons'),
        //             'description' => __('Enabling this option will effect on entire site.', 'wpr-addons'),
        //             'type' => Controls_Manager::SWITCHER,
        //             'default' => 'no',
        //             'label_on' => __('Yes', 'wpr-addons'),
        //             'label_off' => __('No', 'wpr-addons'),
        //             'return_value' => 'yes',
        //             'separator' => 'before',
        //             'condition' => [
        //                 'eael_ext_reading_progress' => 'yes',
        //             ],
        //         ]
        //     );

        //     $element->add_control(
        //         'eael_ext_reading_progress_global_display_condition',
        //         [
        //             'label' => __('Display On', 'wpr-addons'),
        //             'type' => Controls_Manager::SELECT,
        //             'default' => 'all',
        //             'options' => [
        //                 'posts' => __('All Posts', 'wpr-addons'),
        //                 'pages' => __('All Pages', 'wpr-addons'),
        //                 'all' => __('All Posts & Pages', 'wpr-addons'),
        //             ],
        //             'condition' => [
        //                 'eael_ext_reading_progress' => 'yes',
        //                 'eael_ext_reading_progress_global' => 'yes',
        //             ],
        //             'separator' => 'before',
        //         ]
        //     );
        // }

        // $element->add_control(
        //     'eael_ext_reading_progress_position',
        //     [
        //         'label' => esc_html__('Position', 'wpr-addons'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'top',
        //         'label_block' => false,
        //         'options' => [
        //             'top' => esc_html__('Top', 'wpr-addons'),
        //             'bottom' => esc_html__('Bottom', 'wpr-addons'),
        //         ],
        //         'separator' => 'before',
        //         'condition' => [
        //             'eael_ext_reading_progress' => 'yes',
        //         ],
        //     ]
        // );

        // $element->add_control(
        //     'eael_ext_reading_progress_height',
        //     [
        //         'label' => __('Height', 'wpr-addons'),
        //         'type' => Controls_Manager::SLIDER,
        //         'size_units' => ['px'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'step' => 1,
        //             ],
        //         ],
        //         'default' => [
        //             'unit' => 'px',
        //             'size' => 5,
        //         ],
        //         'selectors' => [
        //             '.eael-reading-progress-wrap .eael-reading-progress' => 'height: {{SIZE}}{{UNIT}} !important',
        //             '.eael-reading-progress-wrap .eael-reading-progress .eael-reading-progress-fill' => 'height: {{SIZE}}{{UNIT}} !important',
        //         ],
        //         'separator' => 'before',
        //         'condition' => [
        //             'eael_ext_reading_progress' => 'yes',
        //         ],
        //     ]
        // );

        // $element->add_control(
        //     'eael_ext_reading_progress_bg_color',
        //     [
        //         'label' => __('Background Color', 'wpr-addons'),
        //         'type' => Controls_Manager::COLOR,
        //         'default' => '',
        //         'selectors' => [
        //             '.eael-reading-progress' => 'background-color: {{VALUE}}',
        //         ],
        //         'separator' => 'before',
        //         'condition' => [
        //             'eael_ext_reading_progress' => 'yes',
        //         ],
        //     ]
        // );

        // $element->add_control(
        //     'eael_ext_reading_progress_fill_color',
        //     [
        //         'label' => __('Fill Color', 'wpr-addons'),
        //         'type' => Controls_Manager::COLOR,
        //         'default' => '#1fd18e',
        //         'selectors' => [
        //             '.eael-reading-progress-wrap .eael-reading-progress .eael-reading-progress-fill' => 'background-color: {{VALUE}} !important',
        //         ],
        //         'separator' => 'before',
        //         'condition' => [
        //             'eael_ext_reading_progress' => 'yes',
        //         ],
        //     ]
        // );

        $element->add_control(
            'wpr_reading_progress_bar_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview()">Apply Changes</button></div>',
            ]
        );

        $element->end_controls_section();
    }

    public function render_progress_bar() {
        $page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
        if( '' === $page_settings ) {
            return;
        } else if( 'yes' === $page_settings['wpr_enable_reading_progress'] ) {
            echo '<div class="wpr-progress-container">';
                echo '<div class="wpr-progress-bar" id="wpr-mybar"></div>';
            echo '</div>';
        } 
    }
}

new Wpr_Reading_Progress_Bar();


// from mighty-addons 


$rpbHeight = 'height: ' . $options['height']['size'] . $options['height']['unit'] . '; ';
$rpbBgColor = 'background-color: ' . $options['background_color'] . '; ';
$rpbFillColor = 'background-color: ' . $options['fill_color'] . '; ';

// question: why do we need variable styles instead of selectors here ?
// $html = '<div class="wpr-progress-container" style="' . $rpbBgColor . $rpbHeight . '"><div class="wpr-progress-bar" id="wpr-mybar" style="' . $rpbHeight . $rpbFillColor . '"></div></div></div>';

?>


<?php

// mighty addons version

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
			'wpr_section_reading_progress_bar',
			[
                'tab' => Controls_Manager::TAB_SETTINGS,
				'label' => __( 'Reading Progress Bar - Royal Addons', 'wpr-addons' ),
			]
        );

		$element->add_control(
			'wpr_enable_rpb',
			[
				'label' => __( 'Enable Progress Bar', 'wpr' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'wpr' ),
				'label_off' => __( 'Off', 'wpr' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'wpr_select_view',
			[
				'label' => __( 'Select View', 'wpr' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'view1',
				'options' => [
					'view1' => __( 'View 1', 'wpr' ),
					'view2' => __( 'View 2', 'wpr' ),
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
				'label' => __( 'Height', 'wpr' ),
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
				'label' => __( 'Background Color', 'wpr' ),
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
				'label' => __( 'Fill Color', 'wpr' ),
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
			// Global Settings
				$integrationOptions['reading-progress-bar'][$post_id] = self::createOption( $settings );

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

	public static function createOption( $settings ) {

		$rpbSetting = [];

		$rpbSetting['select_view'] = $settings['wpr_select_view'];

		if( $settings['wpr_select_view'] == 'view1' ) {
			// view 1
			$rpbSetting['height'] = $settings['wpr_height'];
			$rpbSetting['background_color'] = $settings['wpr_background_color'];
			$rpbSetting['fill_color'] = $settings['wpr_fill_color'];
		} 

		return $rpbSetting;
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
        $html = '<div class="wpr-progress-container"><div class="wpr-progress-bar" id="wpr-mybar"></div></div></div>';
    	return $html;
	}
}

Wpr_ReadingProgressBar::instance();