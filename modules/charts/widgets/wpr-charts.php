<?php
namespace WprAddons\Modules\Charts\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Charts extends Widget_Base {
	
	public function get_name() {
		return 'wpr-charts';
	}

	public function get_title() {
		return esc_html__( 'Charts', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-barcode';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'chart', 'charts' ];
	}

	public function get_script_depends() {
		return [ 'wpr-charts' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_chart_data',
			[
				'label' => esc_html__( 'Data', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

        $chartRepeater = new Repeater();

		$chartRepeater->add_control(
			'chart_label', [
				'label'       => esc_html__('Name', 'wpr-addons'),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__('January', 'wpr-addons'),
				'label_block' => true,
			]
		);

		$this->add_control(
			'charts_labels_data',
			[
				'label'   => esc_html__('Categories', 'wpr-addons'),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					['chart_label' => esc_html__('January', 'wpr-addons')],
					['chart_label' => esc_html__('February', 'wpr-addons')],
					['chart_label' => esc_html__('March', 'wpr-addons')],

				],

				'fields'      => $chartRepeater->get_controls(),
				'title_field' => '{{{ chart_label }}}',
				// 'condition'   => ['chart_type' => ['bar', 'horizontalBar', 'line', 'radar']],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_chart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

		// chart style
		$this->add_control(
			'chart_type',
			[
				'label'   => esc_html__('Chart Styles', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bar',
				'options' => [
					'line'          => esc_html__('Line', 'wpr-addons'),
					'bar'           => esc_html__('Bar', 'wpr-addons'),
					'radar'         => esc_html__('Radar', 'wpr-addons'),
					'doughnut'      => esc_html__('Doughnut', 'wpr-addons'),
					'pie'           => esc_html__('Pie', 'wpr-addons'),
					'polarArea'     => esc_html__('Polar Area', 'wpr-addons'),
					'scatter'     => esc_html__('Scatter', 'wpr-addons'),
				],

			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        extract($settings);

		$data_charts_array = [];

		if(is_array($charts_labels_data) && sizeof($charts_labels_data)):
			foreach($charts_labels_data AS $labels_data):
				$data_charts_array['labels'][] = $labels_data['chart_label'];
			endforeach;
		endif;

        var_dump($data_charts_array);

        $layout_settings = [
            'chart_type' => $settings['chart_type'],
            'chart_labels' => $data_charts_array['labels'],
        ];

		$this->add_render_attribute( 'chart-settings', [
            'class' => 'wpr-charts-container',
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
        
        echo '<div ' . $this->get_render_attribute_string( 'chart-settings') . '>';
            echo '<div>';
                echo '<canvas id="wpr-chart"></canvas>';
            echo '</div>';
        echo '</div>';
    }

}