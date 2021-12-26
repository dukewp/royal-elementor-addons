<?php

namespace WprAddons\Modules\AdvancedTable\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
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



// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class Wpr_AdvancedTable extends Widget_Base {
    public function get_name() {
		return 'wpr-advanced-table';
	}

	public function get_title() {
		return esc_html__('Advanced Table', 'wpr-addons');
	}
	public function get_icon() {
		return 'wpr-icon eicon-table';
	}

	public function get_categories() {
		return ['wpr-widgets'];
	}

	public function get_keywords() {
		return ['Advanced Table', 'Advanced', 'Table', 'Data table', 'Data'];
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [];
	}

	public function get_script_depends() {
		return ['wpr-table-to-excel-js'];
	}

	public function get_custom_help_url() {
        return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-back-to-top-help-btn';
    }

    public function _register_controls() {

		$this->start_controls_section(
			'section_preview',
			[
				'label' => __('General', 'wpr-addons'),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'choose_table_type',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
					'csv' => esc_html__( 'upload CSV', 'wpr-addons' ),
				],
			]
		);

        $this->add_control(
            'table_upload_csv',
            [
                'label'     => esc_html__('Upload CSV File', 'wpr-addons'),
                'type'      => Controls_Manager::MEDIA,
                'media_type'=> array(),
                'condition' => [
                    'choose_table_type'   => 'csv',
                ]
            ]);
         

		$this->add_control(
			'enable_table_sorting',
			[
				'label' => __('Table Sorting', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'enable_table_live_search',
			[
				'label' => __('Enable Live Search', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

        // Search bar placeholder
        $this->add_control(
            'search_placeholder',
            [
                'label'                 => esc_html__( 'Search Placeholder', 'wpr-addons' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => 'Type Here To Search...',
                'condition'             => [
                    'enable_table_live_search'   => 'yes',
                ],
                // 'frontend_available'    => true,
            ]
        );

		$this->add_control(
			'enable_row_pagination', [
				'label' => __('Table Row Pagination', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'enable_custom_pagination', [
				'label' => __('Custom Pagination', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'table_items_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 10,
				'condition' => [
					'enable_custom_pagination' => 'yes'
				]
			]
		);

		$this->add_control(
			'pagination_nav_icons',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'enable_custom_pagination' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'enable_table_export',
			[
				'label' => __('Enable Table Export', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

        $this->add_control(
            'table_export_csv_button',
            [
                'label' => __('Export table as CSV file', 'wpr-addons'),
                'type'  => Controls_Manager::BUTTON,
                'text'  => __('Export', 'wpr-addons'),
                'event' => 'my-table-export',
				'condition' => [
					'enable_table_export' => 'yes'
				]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header',
			[
				'label' => __('Header', 'wpr-addons'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_th', [
				'label' => __( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Table Title' , 'wpr-addons' ),
				'dynamic'   => ['active' => true],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'header_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Default: 1 (optional).'),
				'default' 		=> 1,
				'min'     		=> 1,
			]
		);
		
		$repeater->add_responsive_control(
			'header_icon',
			[
				'label' => __('Header Icon', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        $repeater->add_control(
            'header_icon_type',
            [
                'label'    => esc_html__('Header Icon Type', 'wpr-addons'),
                'type'    => Controls_Manager::CHOOSE,
                'options'               => [
                    'none'        => [
                        'title'   => esc_html__('None', 'wpr-addons'),
                        'icon'    => 'fa fa-ban',
                    ],
                    'icon'        => [
                        'title'   => esc_html__('Icon', 'wpr-addons'),
                        'icon'    => 'fa fa-star',
                    ],
                    'image'       => [
                        'title'   => esc_html__('Image', 'wpr-addons'),
                        'icon'    => 'eicon-image-bold',
                    ],
                ],
                'default' => 'icon',
                'condition' => [
                    'header_icon' => 'yes'
                ]
            ]
        );

		$repeater->add_responsive_control(
			'header_icon_position',
			[
				'label' => esc_html__('Header Icon Position'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => __('left', 'wpr-addons'),
					'right' => __('right', 'wpr-addons')
				],
				'condition' => [
					'header_icon' => 'yes'
				]
			]
		);

		$repeater->add_responsive_control(
			'choose_header_col_icon',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'header_icon' => 'yes',
					'header_icon_type' => 'icon',
				]

			]
		);

		$repeater->add_control(
			'header_col_img',
			[
				'label' => esc_html__( 'Image', 'wpr-addons'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_control(
			'header_col_img_size',
			[
				'label' => esc_html__( 'Image Size(px)', 'wpr-addons'),
				'default' => '25',
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'condition' => [
					'header_icon_type'	=> 'image'
				]
			]
		);
		
		$repeater->add_control(
			'header_icon_color',
			[
				'label' => __( 'Icon Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}'
				],
				'condition' => [
					'header_icon_type'	=> 'icon'
				]
			]
		);

		$repeater->add_control(
			'header_th_background_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#7A74FF',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'table_header',
			[
				'label' => __( 'Repeater Table Header', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'table_th' => __( 'TABLE HEADER', 'wpr-addons' ),
					],
					[
						'table_th' => __( 'TABLE HEADER', 'wpr-addons' ),
					],
					[
						'table_th' => __( 'TABLE HEADER', 'wpr-addons' ),
					],
					[
						'table_th' => __( 'TABLE HEADER', 'wpr-addons' ),
					],
				],
				'title_field' => '{{{ table_th }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'wpr-addons'),
				'condition' => [
					'choose_table_type' => 'custom'
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'table_content_row_type',
			[
				'label' => esc_html__( 'Row Type', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'label_block' => false,
				'options' => [
					'row' => esc_html__( 'Row', 'wpr-addons'),
					'col' => esc_html__( 'Column', 'wpr-addons'),
				]
			]
		);

		$repeater->start_controls_tabs(
			'repeater_style_tabs',
			[
				'condition' => [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->start_controls_tab(
			'repeater_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'td_background_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important'
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'repeater_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'td_background_color_hover',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$repeater->add_control(
			'table_content_row_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Default: 1 (optional).'),
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> true,
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_content_row_rowspan',
			[
				'label'			=> esc_html__( 'Row Span', 'wpr-addons'),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Default: 1 (optional).'),
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> true,
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'table_td', [
				'label' => __( 'Content', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Content' , 'wpr-addons' ),
				'show_label' => false,
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);

		$repeater->add_responsive_control(
			'td_icon',
			[
				'label' => __('Content Icon', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'wpr-addons'),
				'label_off' => __('No', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

        $repeater->add_control(
            'td_icon_type',
            [
                'label'    => esc_html__('Icon Type', 'wpr-addons'),
                'type'    => Controls_Manager::CHOOSE,
                'options'               => [
                    'none'        => [
                        'title'   => esc_html__('None', 'wpr-addons'),
                        'icon'    => 'fa fa-ban',
                    ],
                    'icon'        => [
                        'title'   => esc_html__('Icon', 'wpr-addons'),
                        'icon'    => 'fa fa-star',
                    ],
                    'image'       => [
                        'title'   => esc_html__('Image', 'wpr-addons'),
                        'icon'    => 'eicon-image-bold',
                    ],
                ],
                'default' => 'icon',
                'condition' => [
                    'td_icon' => 'yes'
                ]
            ]
        );

		$repeater->add_responsive_control(
			'td_icon_position',
			[
				'label' => esc_html__('Content Icon Position'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => __('left', 'wpr-addons'),
					'right' => __('right', 'wpr-addons')
				],
				'condition' => [
					'td_icon' => 'yes'
				]
			]
		);

		$repeater->add_responsive_control(
			'choose_td_icon',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'td_icon' => 'yes',
					'td_icon_type' => 'icon'
				]

			]
		);

		$repeater->add_control(
			'td_col_img',
			[
				'label' => esc_html__( 'Image', 'wpr-addons'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'td_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_control(
			'td_col_img_size',
			[
				'label' => esc_html__( 'Image Size(px)', 'wpr-addons'),
				'default' => '25',
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'condition' => [
					'td_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_control(
			'tr_bg_color',
			[
				'label' => __( 'Row Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'render_type' => 'template',
				// 'selectors' => [
				// 	'{{WRAPPER}} {{CURRENT_ITEM}} td.wpr-table-td' => 'background-color: {{VALUE}} !important'
				// ],
				'condition' => [
					'table_content_row_type' => 'row'
				]
			]
		);

		$repeater->add_control(
			'td_icon_color',
			[
				'label' => __( 'Icon Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}'
				],
				'condition' 	=> [
					'table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'cell_link',
			[
				'label' => __( 'Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'wpr-addons' ),
				'show_external' => true,
				'default' => [
					'url' => 'https://wp-royal.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'table_content_row_type' => 'col',
				]
			]
		);
	
		$this->add_control(
			'table_content_rows',
			[
				'label' => __( 'Repeater Table Rows', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'table_content_row_type' => 'row' ],
					[ 'table_content_row_type' => 'col'	],
					[ 'table_content_row_type' => 'col' ],
					[ 'table_content_row_type' => 'col' ],
					[ 'table_content_row_type' => 'col' ],
				],
				'title_field' => '{{table_content_row_type}}::{{table_td}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __('General', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_width',
			[
				'label' => __( 'Table Width', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'render_type' => 'template',
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 2000
					]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 100,
					'unit' => '%'
				],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-container .wpr-advanced-table' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-export-search-inner-cont' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-table-custom-pagination' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'table_alignment',
            [
                'label'        => __('Alignment', 'wpr-addons'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
                'options'      => [
                    'flex-start'   => [
                        'title' => __('Left', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'  => [
                        'title' => __('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-container .wpr-table-inner-container' => 'display: flex; justify-content: {{VALUE}}',
					'{{WRAPPER}} .wpr-export-search-cont' => 'display: flex; justify-content: {{VALUE}}',
					'{{WRAPPER}} .wpr-table-pagination-cont' => 'display: flex; justify-content: {{VALUE}}',
				]
            ]
        );

		$this->add_control(
			'all_border_type',
			[
				'label' => esc_html__('Global Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} th.wpr-table-th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.wpr-table-td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'all_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} th.wpr-table-th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.wpr-table-td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'all_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'all_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} th.wpr-table-th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} th.wpr-table-th-pag' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.wpr-table-td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} td.wpr-table-td-pag' => 'border-color: {{VALUE}}',
					// '{{WRAPPER}} li' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
				'condition' => [
					'all_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-th' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-th-pag' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-th i' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-td' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-td-pag' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-td i' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .wpr-table-text' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'header_style',
			[
				'label' => esc_html__('Header', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_control(
			'th_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'th_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#34495E',
				'selectors' => [
					'{{WRAPPER}} th' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);

		$this->add_control(
			'th_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} th:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'th_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#43495E',
				'selectors' => [
					'{{WRAPPER}} th:hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'th_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} th'
			]
		);

		$this->add_control(
			'header_border_radius',
			[
				'label' => esc_html__( 'Header Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} th:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} th:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => __('Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-advanced-table thead i' => 'font-size: {{SIZE}}{{UNIT}};',
                    // '{{WRAPPER}} .eael-data-table thead tr th .data-table-header-svg-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'header_sorting_icon_size',
            [
                'label'      => __('Sorting Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-advanced-table thead .wpr-sorting-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
				'condition' => [
					'enable_table_sorting' => 'yes'
				]
            ]
        );

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'header_img_space',
            [
                'label'      => __('Image Padding', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-advanced-table th img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label'      => __('Icon Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-advanced-table th i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'th_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_styles',
			[
				'label' => esc_html__('Content', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs(
			'cells_style_tabs'
		);

		$this->start_controls_tab(
			'cells_style_normal_tab',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_control(
			'odd_cell_styles',
			[
				'label' => __('Odd Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'odd_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'gray',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-odd td.wpr-table-text' => 'color: {{VALUE}}',
					// '{{WRAPPER}} tr:nth-child(odd) td a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tr.wpr-odd td a' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-odd td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
					// '{{WRAPPER}} tr:nth-child(odd) td' => 'background-color: {{VALUE}}', // TODO: decide tr or td
				],
			]
		);

		$this->add_control(
			'even_cell_styles',
			[
				'label' => __('Even Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-even td a .wpr-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tr.wpr-even td.wpr-table-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffbbee',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-even td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->add_control(
			'active_td_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0E0D0D45',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-active-td-bg-color' => 'background: {{VALUE}} !important;',
				],
			]
		);

		$this->start_controls_tab(
			'cells_style_hover_tab',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);

		$this->add_control(
			'odd_cell_hover_styles',
			[
				'label' => __('Odd Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'odd_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'gray',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-odd td:hover a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tr.wpr-odd td:hover.wpr-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tr.wpr-odd td:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'odd_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-odd:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'even_cell_hover_styles',
			[
				'label' => __('Even Rows', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'even_row_td_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'lightgray',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-even td:hover.wpr-table-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} tr.wpr-even td:hover a .wpr-table-text' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} tr.wpr-even td:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'even_row_td_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#544E4E',
				'selectors' => [
					'{{WRAPPER}} tr.wpr-even:hover td' => 'background-color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'td_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} td',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
            'tbody_icon_size',
            [
                'label'      => __('Icon Size', 'wpr-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
				'separator' => 'before',
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpr-advanced-table tbody i' => 'font-size: {{SIZE}}{{UNIT}};',
                    // '{{WRAPPER}} .eael-data-table thead tr th .data-table-header-svg-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'td_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'td_img_space',
            [
                'label'      => __('Image Padding', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-advanced-table td img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

        $this->add_responsive_control(
            'td_icon_space',
            [
                'label'      => __('Icon Margin', 'wpr-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    // 'px' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
                    // '%' => [
                    //     'min' => 1,
                    //     'max' => 100,
                    // ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
				],
                'selectors'             => [
					'{{WRAPPER}} .wpr-advanced-table td i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
            ]
		);

		$this->add_responsive_control(
			'td_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => ' eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => ' eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => ' eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'export_buttons_styles_section',
			[
				'label' => __( 'Export Buttons', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_table_export' => 'yes'
                ]
			]
		);
        
        $this->start_controls_tabs(
			'export_button_style_tabs'
		);

		$this->start_controls_tab(
			'export_buttons_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'export_buttons_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'export_buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#34495E',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'export_buttons_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'separator' => 'before',
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'export_buttons_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'export_buttons_border_type!' => 'none',
				],
			]
		);
		
		$this->add_responsive_control(
			'export_buttons_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'export_buttons_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDD',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'export_buttons_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'export_buttons_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'export_buttons_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#43495E',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
		
		$this->add_control(
			'export_buttons_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#BBB',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'search_style_section',
			[
				'label' => __('Search', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_table_live_search' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
            'table_search_input_tabs'
        );

            $this->start_controls_tab(
                'table_search_input_normal_tab',
                [
                    'label'     => esc_html__( 'Normal', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input' => 'background-color: {{VALUE}};',
                    ],
                ]
            );


            $this->end_controls_tab();


            $this->start_controls_tab(
                'table_search_input_hover_tab',
                [
                    'label'     => esc_html__( 'Hover', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_hover_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_hover_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );


            $this->end_controls_tab();

            $this->start_controls_tab(
                'table_search_input_focus_tab',
                [
                    'label'     => esc_html__( 'Focus', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_focus_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_focus_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'table_search_input_border_shadow',
			  'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input',
			  'separator' => 'before'
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_text_typography',
                'label' =>esc_html__( 'Typography', 'wpr-addons' ),
                'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input',
				'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_heading',
            [
                'label'     => esc_html__( 'Input Placeholder:', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_color',
            [
                'label'     => esc_html__( 'Color', 'wpr-addons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpr-table-live-search-cont input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_placeholder_typo',
                'label' =>esc_html__( 'Typography', 'wpr-addons' ),
                'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input::placeholder',
            ]
        );

        $this->add_control(
            'table_search_icon_heading',
            [
                'label'     => esc_html__( 'Icon:', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'wpr-addons' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cacaca',
                'selectors' => [
                    '{{WRAPPER}} i.wpr-search-input-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'table_search_icon_font_size',
            [
                'label'      => esc_html__( 'Font Size', 'wpr-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
                'selectors'  => [
                    '{{WRAPPER}} i.wpr-search-input-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
        );

        $this->add_control(
            'table_search_input_heading',
            [
                'label'     => esc_html__( 'Input:', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
			'table_search_input_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 325,
                    'unit'  => 'px'
                ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-live-search-cont' => 'width: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

        $this->add_responsive_control(
            'table_search_input_padding',
            [
                'label'      => esc_html__( 'Padding', 'wpr-addons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'    => 10,
                    'bottom' => 10,
                    'left'   => 10,
                    'right'  => 10,
                    'unit'   => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-table-live-search-cont input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpr-search-input-icon' => 'right: {{RIGHT}}{{UNIT}} !important',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'table_search_input_border',
				'label'       => esc_html__( 'Border', 'wpr-addons' ),
				'selector'    => '{{WRAPPER}} .wpr-table-live-search-cont input',
			]
        );

        $this->add_responsive_control(
			'table_search_input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpr-table-live-search-cont input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
        );

		$this->end_controls_section();
	
		$this->start_controls_section(
			'pagination_style_section',
			[
				'label' => __('Pagination', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_custom_pagination' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'pagination_normal_style_tabs'
		);

		$this->start_controls_tab(
			'pagination_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);
				
		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'color: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_bg_color',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#34495E',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'background-color: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_color_active',
			[
				'label' => __( 'Background Color (Active)', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list.wpr-active-pagination-item' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'table_pagination_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-table-custom-pagination-list',
			]
		);

		$this->add_responsive_control(
			'pagination_horizontal_gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'pagination_vertical_gutter',
			[
				'label' => __( 'Vertical Gutter', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 7,
					'right' => 13,
					'bottom' => 7,
					'left' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'separator' => 'before',
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hover',
			[
				'label' => __( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffb',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:hover' => 'color: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label' => __( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				// 'default' => '#7b2ccc',
				'default' => '#43495E',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		
		$this->add_control(
            'pagination_alignment',
            [
                'label'        => __('Alignment', 'wpr-addons'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
				'separator' => 'before',
                'options'      => [
                    'flex-start'   => [
                        'title' => __('Left', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'  => [
                        'title' => __('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination' => 'display: flex; justify-content: {{VALUE}}',
				]
            ]
        );

		$this->end_controls_section();
    }

	protected function render_csv_data($url, $custom_pagination, $sorting_icon, $settings) {

		$url_ext = pathinfo($url, PATHINFO_EXTENSION);

		ob_start();
		if($url_ext === 'csv'){ 
			echo $this->wpr_parse_csv_to_table($url, true, $custom_pagination, $sorting_icon, $settings);
		} else {
			echo '<p class="wpr-no-csv-file-found">'. esc_html__('Please provide an csv file', 'wpr-addons') .'</p>';
		}
		return \ob_get_clean();

	}

	protected function wpr_parse_csv_to_table($filename, $header=true, $custom_pagination, $sorting_icon, $settings) {

		$handle = fopen($filename, "r");
		//display header row if true
		echo '<table class="wpr-append-to-scope wpr-advanced-table" id="wpr-advanced-table">';
		if ($header) {
			$csvcontents = fgetcsv($handle);
			echo '<thead><tr class="wpr-table-head-row wpr-table-row">';
			foreach ($csvcontents as $headercolumn) {
				echo "<th class='wpr-table-th wpr-table-text'>$headercolumn  $sorting_icon</th>";
			}
			echo '</tr></thead>';
		}
		echo '<tbody>';

		// displaying contents
		$countRows = 0;
		$oddEven = '';
		while ($csvcontents = fgetcsv($handle)) {
			if($countRows < 100) {	// TODO: remove if statement later
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'wpr-even' : 'wpr-odd';
				echo '<tr class="wpr-table-row  '. $oddEven .'">';
				foreach ($csvcontents as $column) {
					echo '<td class="wpr-table-td wpr-table-text">'. $column .'</td>';
				}
				echo '</tr>';
			}
		}
		echo '</tbody></table>';
		echo '</div>';

		if ( 'yes' == $settings['enable_custom_pagination'] ) { ?>
			<div class="wpr-table-pagination-cont">
			<ul class="wpr-table-custom-pagination">
				<div class="wpr-table-custom-pagination-inner-cont">
				<li class='wpr-table-custom-pagination-prev wpr-table-prev-next wpr-table-custom-pagination-list wpr-table-next-arrow wpr-table-arrow'><?php echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], '' ); ?></li>

					<?php 
					$item_index = 0;
		
					$exact_number_of_pages = $countRows/$settings['table_items_per_page'];
					$total_pages = ceil($countRows/$settings['table_items_per_page']);
					
					for (  $i = 1; $i <= $total_pages; $i++ ) {	?>
		
							<li class="wpr-table-custom-pagination-list wpr-table-custom-pagination-list-item <?php echo $i === 1 ? 'wpr-active-pagination-item' : ''; ?>">
								<span><?php echo $i; ?></span>
							</li>
		
						<?php } ?>
					
				<li class='wpr-table-custom-pagination-next wpr-table-prev-next wpr-table-custom-pagination-list wpr-table-prev-arrow wpr-table-arrow'><?php echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], '' ); ?></li>
				</div>
			</ul>
			</div>
	
			<?php } 

		echo '</div>';
		fclose($handle);
		
	}

    public function render() {
		$settings = $this->get_settings_for_display(); 

		$table_tr = [];
		$table_td = [];
		?>

		<?php 

		if ( 'yes' === $settings['enable_table_live_search'] || 'yes' === $settings['enable_table_export'] ) {

			echo '<div class="wpr-export-search-cont">';
			echo '<div class="wpr-export-search-inner-cont">';
	
			if ( 'yes' === $settings['enable_table_export'] ) {
				echo '<div class="wpr-table-export-button-cont">';
					echo '<button class="wpr-button wpr-xls">XLS</button>';
					echo '<button class="wpr-button wpr-csv">CSV</button>';
				echo '</div>';
			}

			if ( 'yes' === $settings['enable_table_live_search'] ) {
				echo '<div class="wpr-table-live-search-cont">';
					echo '<input type="search" id="wpr-table-live-search" placeholder="'. $settings['search_placeholder'] .'">';
					echo '<i class="fas fa-search wpr-search-input-icon"></i>';
				echo '</div>';
			}

			echo '</div>';
			echo '</div>';

		}
		
		$x = 0;
		
		$sorting_icon = 'yes' === $settings['enable_table_sorting'] ? '<span class="wpr-sorting-icon"><i class="fas fa-sort"></i></span>' : ''; ?>

		
		<div class="wpr-table-container">
		<div class="wpr-table-inner-container" style="width: 100%;" data-table-sorting="<?php echo $settings['enable_table_sorting']; ?>" data-custom-pagination="<?php echo $settings['enable_custom_pagination'] ?>" data-row-pagination="<?php echo $settings['enable_row_pagination'] ?>" data-rows-per-page="<?php echo isset($settings['table_items_per_page']) ? $settings['table_items_per_page'] : ''; ?>">

		<?php if ( 'csv' === $settings['choose_table_type'] ) {

			echo $this->render_csv_data($settings['table_upload_csv']['url'], $settings['enable_custom_pagination'], $sorting_icon, $settings);

		} else {

			// Storing Data table content values
			$countRows = 0;
			foreach( $settings['table_content_rows'] as $content_row ) {
				$countRows++;
				$oddEven = $countRows % 2 == 0 ? 'wpr-even' : 'wpr-odd';
				$row_id = uniqid();
				if( $content_row['table_content_row_type'] == 'row' ) {
					$table_tr[] = [
						'id' => $row_id,
						'type' => $content_row['table_content_row_type'],
					'tr_bg_color' => $content_row['tr_bg_color'],
					'class' => ['wpr-table-body-row', 'wpr-table-row', 'elementor-repeater-item-'.$content_row['_id'], $oddEven]
					];
					
				}
			if( $content_row['table_content_row_type'] == 'col' ) {

					$table_tr_keys = array_keys( $table_tr );
				$last_key = end( $table_tr_keys );

					$table_td[] = [
						'row_id'		=> $table_tr[$last_key]['id'],
						'type'			=> $content_row['table_content_row_type'],
						'content'		=> $content_row['table_td'],
						'colspan'		=> $content_row['table_content_row_colspan'],
						'rowspan'		=> $content_row['table_content_row_rowspan'],
						'link'   		=> $content_row['cell_link'],
						'icon_type' => $content_row['td_icon_type'],
						'icon'			=> $content_row['td_icon'],
						'icon_position' => $content_row['td_icon_position'],
						'icon_item' 	=> $content_row['choose_td_icon'],
						'col_img' => $content_row['td_col_img'],
						'col_img_size' => $content_row['td_col_img_size'],
						'class' => ['elementor-repeater-item-'.$content_row['_id'], 'wpr-table-td'],
					];
				}
			}
		
		?>
			  <table class="wpr-advanced-table" id="wpr-advanced-table">
				<?php if ( $settings['table_header'] ) { ?>
				<thead>
					<tr class="wpr-table-head-row wpr-table-row">
						<?php $i = 0; foreach ($settings['table_header'] as $item) { 
							$this->add_render_attribute('th_class'.$i, [
								'class' => 'wpr-table-th',
								'colspan' => $item['header_colspan'],
							]); ?>
								<?php if($item['header_icon'] == 'yes' && $item['header_icon_position'] == 'left') {
								if( $item['header_icon_type'] == 'image' ) :
									$this->add_render_attribute('wpr_table_th_img'.$i, [
										'src'	=> esc_url( $item['header_col_img']['url'] ),
										'class'	=> 'wpr-data-table-th-img',
										'style'	=> "width:{$item['header_col_img_size']}px;",
										'alt'	=> esc_attr(get_post_meta($item['header_col_img']['id'], '_wp_attachment_image_alt', true))
									]);
									?>
								<th <?php echo $this->get_render_attribute_string('th_class'.$i); ?> class="elementor-repeater-item-<?php echo $item['_id']; ?>">
										<img <?php echo $this->get_render_attribute_string('wpr_table_th_img'.$i); ?>>
										<span class="wpr-table-text"><?php echo $item['table_th']; ?></span>
										<?php echo $sorting_icon; ?>
								</th>
								<?php else : ?>
									<th <?php echo $this->get_render_attribute_string('th_class'.$i); ?> class="elementor-repeater-item-<?php echo $item['_id']; ?>">
										<?php \Elementor\Icons_Manager::render_icon($item['choose_header_col_icon'], ['aria-hidden' => 'true']);?>&nbsp;&nbsp;
										<span class="wpr-table-text"><?php echo $item['table_th']; ?></span>
										<?php echo $sorting_icon; ?>
									</th>
								<?php endif;
							?>

							<?php  } elseif ($item['header_icon'] == 'yes' && $item['header_icon_position'] == 'right') {
								if( $item['header_icon_type'] == 'image' ) :
									$this->add_render_attribute('wpr_table_th_img'.$i, [
										'src'	=> esc_url( $item['header_col_img']['url'] ),
										'class'	=> 'wpr-data-table-th-img',
										'style'	=> "width:{$item['header_col_img_size']}px;",
										'alt'	=> esc_attr(get_post_meta($item['header_col_img']['id'], '_wp_attachment_image_alt', true))
									]);
									?>
								<th <?php echo $this->get_render_attribute_string('th_class'.$i); ?> class="elementor-repeater-item-<?php echo $item['_id']; ?>">
										<span class="wpr-table-text"><?php echo $item['table_th']; ?></span>
										<img <?php echo $this->get_render_attribute_string('wpr_table_th_img'.$i); ?>>
										<?php echo $sorting_icon; ?>
								</th>
								<?php else : ?>
									<th <?php echo $this->get_render_attribute_string('th_class'.$i); ?> class="elementor-repeater-item-<?php echo $item['_id']; ?>">
										<span class="wpr-table-text"><?php echo $item['table_th']; ?></span>&nbsp;&nbsp;
										<?php \Elementor\Icons_Manager::render_icon($item['choose_header_col_icon'], ['aria-hidden' => 'true']);?>
										<?php echo $sorting_icon; ?>
									</th>
								<?php endif; ?>
							<?php } else { ?>
							<th <?php echo $this->get_render_attribute_string('th_class'.$i); ?> class="elementor-repeater-item-<?php echo $item['_id']; ?>">
								<span class="wpr-table-text"><?php echo $item['table_th']; ?></span>
								<?php echo $sorting_icon; ?>
							</th>
						<?php } $i++; } ?>
					</tr>
				</thead>
				<tbody>
				<?php for( $i = 0 + $x; $i < count( $table_tr ) + $x; $i++ ) :

						$this->add_render_attribute('table_row_attributes'.$i, [
							'class' => $table_tr[$i]['class'],
						]);

						?>
					<tr <?php echo $this->get_render_attribute_string('table_row_attributes'.$i) ?>>
						<?php
							for( $j = 0; $j < count( $table_td ); $j++ ) {
								if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {
									$this->add_render_attribute('table_inside_td'.$i.$j, [
										'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
										'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
										'class' => $table_td[$j]['class']
									]);
									if($table_td[$j]['icon'] == 'yes' && $table_td[$j]['icon_position'] == 'left') { 
										if( $table_td[$j]['icon_type'] == 'image' ) {   
											$this->add_render_attribute('wpr_table_td_img'.$j, [
												'src'	=> esc_url( $table_td[$j]['col_img']['url'] ),
												'class'	=> 'wpr-data-table-th-img',
												'style'	=> "width:{$table_td[$j]['col_img_size']}px;",
												'alt'	=> esc_attr(get_post_meta($table_td[$j]['col_img']['id'], '_wp_attachment_image_alt', true))
											]); ?>
									
										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?> style="background-color: <?php echo $table_tr[$i]['tr_bg_color']; ?>">
											<div class="wpr-td-content-wrapper">
													<img <?php echo $this->get_render_attribute_string('wpr_table_td_img'.$j); ?>>
													<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="_blank">
														<span class="wpr-table-text"><?php echo $table_td[$j]['content']; ?></span>
													</a>
											</div>
										</td>

										<?php } else { ?>
									
										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?> style="background-color: <?php echo $table_tr[$i]['tr_bg_color']; ?>">
											<div class="wpr-td-content-wrapper">
												<div <?php echo $this->get_render_attribute_string('td_content'); ?>>
													<?php \Elementor\Icons_Manager::render_icon($table_td[$j]['icon_item'], ['aria-hidden' => 'true']);?>&nbsp;&nbsp;
													<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="_blank">
														<span class="wpr-table-text"><?php echo $table_td[$j]['content']; ?></span>
													</a>
												</div>
											</div>
										</td>
										
									<?php }
									} elseif( $table_td[$j]['icon'] == 'yes' && $table_td[$j]['icon_position'] == 'right' ) { 
										if( $table_td[$j]['icon_type'] == 'image' ) {   
											$this->add_render_attribute('wpr_table_td_img'.$j, [
												'src'	=> esc_url( $table_td[$j]['col_img']['url'] ),
												'class'	=> 'wpr-data-table-th-img',
												'style'	=> "width:{$table_td[$j]['col_img_size']}px;",
												'alt'	=> esc_attr(get_post_meta($table_td[$j]['col_img']['id'], '_wp_attachment_image_alt', true))
											]); ?>
									
										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?> style="background-color: <?php echo $table_tr[$i]['tr_bg_color']; ?>">
											<div class="wpr-td-content-wrapper">
												<div <?php echo $this->get_render_attribute_string('td_content'); ?>>
													<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="_blank">
														<span class="wpr-table-text"><?php echo $table_td[$j]['content']; ?></span>
													</a>
													<img <?php echo $this->get_render_attribute_string('wpr_table_td_img'.$j); ?>>
												</div>
											</div>
										</td>

										<?php } else { ?>

										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?> style="background-color: <?php echo $table_tr[$i]['tr_bg_color']; ?>">
											<div class="wpr-td-content-wrapper">
												<div <?php echo $this->get_render_attribute_string('td_content'); ?>><a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="_blank"><span class="wpr-table-text"><?php echo $table_td[$j]['content']; ?></span></a>&nbsp;&nbsp;<?php \Elementor\Icons_Manager::render_icon($table_td[$j]['icon_item'], ['aria-hidden' => 'true']);?></div>
											</div>
										</td>
									<?php }
								 } else { ?>

										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?> style="background-color: <?php echo $table_tr[$i]['tr_bg_color']; ?>">
											<div class="wpr-td-content-wrapper">
												<div <?php echo $this->get_render_attribute_string('td_content'); ?>>
												<a href="<?php echo esc_url($table_td[$j]['link']['url']) ?>" target="_blank"><span class="wpr-table-text"><?php echo $table_td[$j]['content']; ?></span></a>
											</div>
											</div>
										</td>

								<?php }
								}
							}
						?>
					</tr>
			        <?php endfor; ?>
				</tbody>
			</table>
		  </div>
		  <?php
		  
			if ( 'yes' == $settings['enable_custom_pagination'] ) { ?>
				<div class="wpr-table-pagination-cont">
				<ul class="wpr-table-custom-pagination">
					<div class="wpr-table-custom-pagination-inner-cont">
					<li class='wpr-table-custom-pagination-prev wpr-table-prev-next wpr-table-custom-pagination-list wpr-slider-prev-arrow wpr-slider-arrow'><?php echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], '' ); ?></i></li>

						<?php 
						$total_rows = 0;
						$item_index = 0;
			
						foreach ( $settings['table_content_rows'] as $item ) {
							if ( 'row' === $item['table_content_row_type'] ) {
								$total_rows++;
							}
						}
			
						$exact_number_of_pages = $total_rows/$settings['table_items_per_page'];
						$total_pages = ceil($total_rows/$settings['table_items_per_page']);
						
						for (  $i = 1; $i <= $total_pages; $i++ ) {	?>
			
								<li class="wpr-table-custom-pagination-list wpr-table-custom-pagination-list-item <?php echo $i === 1 ? 'wpr-active-pagination-item' : ''; ?>">
									<span><?php echo $i; ?></span>
								</li>
			
							<?php } ?>
						
					<li class='wpr-table-custom-pagination-next wpr-table-prev-next wpr-table-custom-pagination-list wpr-slider-prev-arrow wpr-slider-arrow'><?php echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], '' ); ?></i></li>
					</div>
				</ul>
				</div>
		
				<?php } 

		  ?>
		</div>
    <?php }
	  }
  }
}