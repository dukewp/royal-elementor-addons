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

	public function get_style_depends() {
		return ['wpr-loading-animations-css'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_chart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

		$this->add_control(
			'data_source',
			[
				'label'              => __( 'Data Source', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'custom',
				'options'            => [
					'custom' => __( 'Custom', 'wpr-addons' ),
					'csv'    => 'CSV' . __( ' File', 'wpr-addons' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'csv_source',
			[
				'label'              => __( 'Data Source', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'url',
				'options'            => [
					'url' => __( 'Remote URL', 'wpr-addons' ),
					'file' => __( ' File', 'wpr-addons' ),
				],
				'condition'   => [
					'data_source' => 'csv',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'data_csv_separator',
			[
				'label'       => __( 'Remote URL', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'default' => ',',
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
				],
			]
		);

		$this->add_control(
			'data_source_csv_url',
			[
				'label'       => __( 'Remote URL', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
					'csv_source' => 'url',
				],
			]
		);

		$this->add_control(
			'data_source_csv_file',
			[
				'label'       => __( 'Upload CSV File', 'wpr-addons' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'dynamic'     => ['active' => true],
				'media_type'  => [],
				'label_block' => true,
				'condition'   => [
					'data_source' => 'csv',
					'csv_source' => 'file',
				],
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
					'bar'           => esc_html__('Bar', 'wpr-addons'),
					'bar_horizontal' => esc_html__('Bar (Horizontal)', 'wpr-addons'),
					'line'          => esc_html__('Line', 'wpr-addons'),
					'radar'         => esc_html__('Radar', 'wpr-addons'),
					'doughnut'      => esc_html__('Doughnut', 'wpr-addons'),
					'pie'           => esc_html__('Pie', 'wpr-addons'),
					'polarArea'     => esc_html__('Polar Area', 'wpr-addons'),
					'scatter'     => esc_html__('Scatter', 'wpr-addons'),
				],

			]
		);

		$this->add_control(
			'chart_interaction_mode',
			[
				'label'   => esc_html__('Interaction Mode', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'nearest',
				'options' => [
					'nearest' => esc_html__('Nearest', 'wpr-addons'),
					'point' => esc_html__('Point', 'wpr-addons'),
					'index' => esc_html__('Index', 'wpr-addons'),
					'dataset' => esc_html__('Dataset', 'wpr-addons'),
				],
			]
		);

		$this->add_control(
			'trigger_tooltip_on',
			[
				'label'   => esc_html__('Show Tooltip On', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mousemove',
				'options' => [
					'mousemove' => esc_html__('Hover', 'wpr-addons'),
					'click' => esc_html__('Click', 'wpr-addons'),
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'labels_rotation_x_axis',
			array(
				'label'              => __( 'Labels Rotation ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 360,
				'default'            => 0,
				'separator'			 => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'column_width_x',
			[
				'label' => esc_html__( 'Column Width', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'step' => 0.1,
				'min' => 0,
				'max' => 1,
				'frontend_available' => true,
				'condition' => [
					'chart_type' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_control(
			'charts_legend_position',
			[
				'label'   => esc_html__('Legend Position', 'wpr-addons'),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'top'    => [
						'title' => esc_html__('Top', 'wpr-addons'),
						'icon'  => 'eicon-v-align-top',
					],
					'right'  => [
						'title' => esc_html__('Right', 'wpr-addons'),
						'icon'  => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'wpr-addons'),
						'icon'  => 'eicon-v-align-bottom',
					],
					'left'   => [
						'title' => esc_html__('Left', 'wpr-addons'),
						'icon'  => 'eicon-h-align-left',
					],
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'chart_animation',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_animation_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'chart_animation' => 'yes',
				]
			]
		);

		$this->add_control(
			'chart_animation_duration', 
			[
				'label'       => esc_html__('Animation Duration', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1000,
				'condition' => [
					'chart_animation' => 'yes',
				]
			]
		);

		$this->add_control(
			'animation_transition_type',
			[
				'label'   => esc_html__('Animation Transition Style', 'elementskit'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'linear',
				'options' => [
					'linear' => 'linear',
					'easeInQuad' => 'easeInQuad',
					'easeOutQuad' => 'easeOutQuad',
					'easeInOutQuad' => 'easeInOutQuad',
					'easeInCubic' => 'easeInCubic',
					'easeOutCubic' => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInQuart' => 'easeInQuart',
					'easeOutQuart' => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInQuint' => 'easeInQuint',
					'easeOutQuint' => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInSine' => 'easeInSine',
					'easeOutSine' => 'easeOutSine',
					'easeInOutSine' => 'easeInOutSine',
					'easeInExpo' => 'easeInExpo',
					'easeOutExpo' => 'easeOutExpo',
					'easeInOutExpo' => 'easeInOutExpo',
					'easeInCirc' => 'easeInCirc',
					'easeOutCirc' => 'easeOutCirc',
					'easeInOutCirc' => 'easeInOutCirc',
					'easeInElastic' => 'easeInElastic',
					'easeOutElastic' => 'easeOutElastic',
					'easeInOutElastic' => 'easeInOutElastic',
					'easeInBack' => 'easeInBack',
					'easeOutBack' => 'easeOutBack',
					'easeInOutBack' => 'easeInOutBack',
					'easeInBounce' => 'easeInBounce',
					'easeOutBounce' => 'easeOutBounce',
					'easeInOutBounce' => 'easeInOutBounce',
				],
				'condition' => [
					'chart_animation' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_chart_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'To Be Applied', 'wpr-addons' ),
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_lines',
			[
				'label' => esc_html__( 'Show Lines', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_control(
			'line_dots',
			[
				'label' => esc_html__( 'Line Dots', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_responsive_control(
			'line_dots_radius',
			[
				'label' => esc_html__( 'Line Dots Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'condition' => [
					'chart_type!' => ['bar', 'bar_horizontal'],
					'line_dots' => 'yes',
				]
			]
		);

		$this->add_control(
			'enable_min_max',
			[
				'label' => esc_html__( 'Min-Max Values', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					// 'chart_type!' => ['bar', 'bar_horizontal'],
				]
			]
		);

		$this->add_control(
			'min_value', 
			[
				'label'       => esc_html__('Min. Value', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => -100,
				'condition' => [
					'enable_min_max' => 'yes',
				]
			]
		);

		$this->add_control(
			'max_value', 
			[
				'label'       => esc_html__('Max. Value', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 100,
				'condition' => [
					'enable_min_max' => 'yes',
				]
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_data',
			[
				'label' => esc_html__( 'Data', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

        $chart_repeater = new \Elementor\Repeater();

		$chart_repeater->add_control(
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

				'fields'      => $chart_repeater->get_controls(),
				'title_field' => '{{{ chart_label }}}',
				'condition'   => [
					'data_source' => 'custom',
					'chart_type' => ['bar', 'bar_horizontal', 'line', 'radar', 'scatter'] //  'horizontalBar',
				],
			]
		);

		// repeter for data fields
		$chart_repeater_labels = new Repeater();

		$chart_repeater_labels->add_control(
			'chart_data_label', [
				'label'       => esc_html__('Label', 'wpr-addons'),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__('Label #1', 'wpr-addons'),
				'label_block' => true,
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_set', [
				'label'       => esc_html__('Data', 'wpr-addons'),
				'type'        => Controls_Manager::TEXT,
				'default'     => '10,23,15',
				'label_block' => true,
				'description' => esc_html__('Enter data values by "," separated(1). Example: 2,4,8,16,32 etc', 'wpr-addons'),
			]
		);

		// start tabs section
		$chart_repeater_labels->start_controls_tabs(
			'chart_data_bar_background_tab'
		);
		// start normal sections
		$chart_repeater_labels->start_controls_tab(
			'chart_data_bar_background_normal',
			[
				'label' => esc_html__('Normal', 'wpr-addons'),
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_background_color', [
				'label'       => esc_html__('Background Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(23, 187, 87, 0.48)',
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_border_color', [
				'label'       => esc_html__('Border Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(87, 187, 23, 0.48)',
			]
		);

		$chart_repeater_labels->end_controls_tab();

		// end normal sections
		// start hover sections
		$chart_repeater_labels->start_controls_tab(
			'chart_data_bar_background_hover',
			[
				'label' => esc_html__('Hover', 'wpr-addons'),
			]
		);
		$chart_repeater_labels->add_control(
			'chart_data_background_color_hover', [
				'label'       => esc_html__('Background Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
				'default' => 'rgba(23, 23, 23, 0.5)'
			]
		);

		$chart_repeater_labels->add_control(
			'chart_data_border_color_hover', [
				'label'       => esc_html__('Border Color', 'wpr-addons'),
				'type'        => Controls_Manager::COLOR,
			]
		);
		$chart_repeater_labels->end_controls_tab();
		// end hover sections
		$chart_repeater_labels->end_controls_tabs();
		// end tabs section

		$chart_repeater_labels->add_control(
			'chart_data_border_width', [
				'label'       => esc_html__('Border Width', 'wpr-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '1',
			]
		);

		$this->add_control(
			'charts_data_set',
			[
				'label'   => esc_html__('Set Data', 'wpr-addons'),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					[
						'chart_data_label'            => esc_html__('Label #1', 'wpr-addons'),
						'chart_data_set'              => '13,20,15',
						'chart_data_background_color'   => 'rgba(242,41,91,0.48)',
						'chart_data_border_color' => 'rgba(242,41,91,0.48)',
						'chart_data_border_width' => 1,
					],
					[
						'chart_data_label'            => esc_html__('Label #2', 'wpr-addons'),
						'chart_data_set'              => '20,10,33',
						'chart_data_background_color'   => 'rgba(69,53,244,0.48)',
						'chart_data_border_color' => 'rgba(69,53,244,0.48)',
						'chart_data_border_width' => 1,
					],
					[
						'chart_data_label'            => esc_html__('Label #3', 'wpr-addons'),
						'chart_data_set'              => '10,3,23',
						'chart_data_background_color'   => 'rgba(239,239,40,0.57)',
						'chart_data_border_color' => 'rgba(239,239,40,0.57)',
						'chart_data_border_width' => 1,
					],

				],

				'fields'      => $chart_repeater_labels->get_controls(),
				'title_field' => '{{{ chart_data_label }}}',
				// 'condition'   => ['chart_style' => ['bar', 'bar_horizontal', 'horizontalBar', 'line', 'radar']],
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_general_styles',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_responsive_control(
			'chart_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
            'section_chart_legend_styles',
			[
				'label' => esc_html__( 'Legend', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_responsive_control(
			'legend_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'legend_font_style',
			[
				'label'   => esc_html__('Font Style', 'elementskit'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => 'Normal',
					'italic' => 'Italic',
					'oblique' => 'Oblique',
				],

			]
		);

		// $this->add_control(
		// 	'legend_font_family',
		// 	[
		// 		'label'   => esc_html__('Font Style', 'elementskit'),
		// 		'type'    => Controls_Manager::SELECT,
		// 		'default' => '',
		// 		'options' => [
		// 		],

		// 	]
		// );

		$this->add_control(
			'legend_font_weight',
			array(
				'label'              => __( 'Font Weight ', 'wpr-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'default'            => 600,
				'frontend_available' => true,
			)
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        extract($settings);

		$data_charts_array = [];

		if ( in_array($chart_type, array('bar', 'bar_horizontal', 'line', 'radar', 'scatter'))) {
			if(is_array($charts_labels_data) && sizeof($charts_labels_data)):
				foreach($charts_labels_data AS $labels_data):
					$data_charts_array['labels'][] = $labels_data['chart_label'];
				endforeach;
			endif;

			if(is_array($charts_data_set) && sizeof($charts_data_set)) {
				foreach($charts_data_set as $chart_data) {
					$data_charts_array['datasets'][] = [
						'label' => $chart_data['chart_data_label'],
						'data' => array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ','))),
						'backgroundColor' => $chart_data['chart_data_background_color'],
						'hoverBackgroundColor' => $chart_data['chart_data_background_color_hover'],
						'borderColor' => $chart_data['chart_data_border_color'],
						'hoverBorderColor' => $chart_data['chart_data_border_color_hover'],
						'borderWidth' => $chart_data['chart_data_border_width'],
						'barPercentage' => $settings['column_width_x'],
					];
				}
			}
		} else {
			if(is_array($charts_data_set) && sizeof($charts_data_set)) {
				$chart_data_number_values = [];
				$chart_background_colors = [];
				$chart_background_hover_colors = [];
				$chart_data_border_colors = [];
				$chart_data_border_hover_colors = [];
				$chart_data_border_width = [];
				$chart_data_bar_percentage = [];
				
				foreach($charts_data_set AS $labels_data):
					$data_charts_array['labels'][] = $labels_data['chart_data_label'];
				endforeach;
				
				foreach($charts_data_set as $chart_data) {
					array_push($chart_data_number_values, array_map('floatval', explode(',', trim($chart_data['chart_data_set'], ',')))[0]);
					array_push($chart_background_colors, trim($chart_data['chart_data_background_color']));
					array_push($chart_background_hover_colors, trim($chart_data['chart_data_background_color_hover']));
					array_push($chart_data_border_colors, trim($chart_data['chart_data_border_color']));
					array_push($chart_data_border_hover_colors, trim($chart_data['chart_data_border_color_hover']));
					array_push($chart_data_border_width, trim($chart_data['chart_data_border_width']));
					array_push($chart_data_bar_percentage, trim($chart_data['column_width_x']));
				}

					$data_charts_array['datasets'][] = [
						'label' => 'Label Placeholder', // test with fixed value 
						'data' => $chart_data_number_values,
						'backgroundColor' => $chart_background_colors,
						'hoverBackgroundColor' => $chart_background_hover_colors,
						'borderColor' => $chart_data_border_colors,
						'hoverBorderColor' => $chart_data_border_hover_colors,
						'borderWidth' => $chart_data_border_width,
						'barPercentage' => $chart_data_bar_percentage,
					];
			}
		}

		if ( !empty($data_source_csv_url) ) {
			$data_url = $data_source_csv_url;
		} else if ( !empty($data_source_csv_file['url']) ) {
			$data_url = $data_source_csv_file['url'];
		} else {
			$data_url = '';
		}

        $layout_settings = [
			'data_source' => $data_source,
            'chart_type' => $settings['chart_type'],
			'chart_interaction_mode' => $chart_interaction_mode,
			'trigger_tooltip_on' => $trigger_tooltip_on,
            'chart_labels' => !empty($data_charts_array['labels']) ? $data_charts_array['labels'] : '',
			'chart_datasets' => wp_json_encode($data_charts_array['datasets']),
			'legend_position' => $settings['charts_legend_position'],
			'chart_animation' => $chart_animation,
			'chart_animation_loop' => $chart_animation_loop,
			'chart_animation_duration' => $chart_animation_duration,
			'animation_transition_type' => $animation_transition_type,
			'show_chart_title' => $settings['show_chart_title'],
			'chart_title' => !empty($settings['chart_title']) ? $settings['chart_title'] : '',
			'show_lines' => isset($show_lines) ? $show_lines : '',
			'line_dots' => isset($line_dots) ? $line_dots : '',
			'line_dots_radius' => isset($line_dots_radius) ? $line_dots_radius['size'] : '',
			'rotation' => $labels_rotation_x_axis,
			'min_value' => $min_value,
			'max_value' => $max_value,
			'chart_padding' => $chart_padding['size'],
			'url' => $data_url,
			'separator' => $data_csv_separator,
			'legend_font_size' => $legend_font_size['size'],
			'legend_font_style' => $legend_font_style,
			'legend_font_weight' => $legend_font_weight,
        ];

		$this->add_render_attribute( 'chart-settings', [
            'class' => 'wpr-charts-container',
			'data-settings' => wp_json_encode( $layout_settings ),
		] );
        
        echo '<div ' . $this->get_render_attribute_string( 'chart-settings') . '>';

			if ($data_source === 'csv') {
				echo '<span class="wpr-rotating-plane" style="width: 25px; height: 25px; background: red; border-radius: 50%; position: absolute; top: 50%; left: 50%; z-index: 999; transform: translate(-50%, -50%);"></span>';
			}

            echo '<div class="wpr-charts-wrapper">';
                echo '<canvas class="wpr-chart"></canvas>';
            echo '</div>';
        echo '</div>';
    }

}