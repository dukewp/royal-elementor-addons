<?php
namespace WprAddons\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;

class Wpr_Reading_Progress_Bar
{

    public function __construct()
    {
        // add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
        add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
        // add_action( 'elementor/editor/after_save', [ $this, 'render_progress_bar' ], 10, 2 );
        add_action('wp_footer', [$this, 'render_progress_bar']);
    }

    public function register_controls($element)
    {

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
