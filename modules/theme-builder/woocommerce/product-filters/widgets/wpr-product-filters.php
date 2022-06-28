<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductFilters\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Filters extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-filters';
	}

	public function get_title() {
		return esc_html__( 'Product Filters', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-filter';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'product-filters', 'product', 'filters' ];//tmp
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
        $this->start_controls_section(
            'section_product_filter',
            [
                'label' => esc_html__( 'General', 'wpr-addons' ),
            ]
        );

        $filter_by = [
            'active' => esc_html__( 'Active', 'wpr-addons' ),
            'search' => esc_html__( 'Search', 'wpr-addons' ),
            'price' => esc_html__( 'Price', 'wpr-addons' ),
            'rating' => esc_html__( 'Rating', 'wpr-addons' ),
        ];
            
		$this->add_control(
			'filter_type',
			[
				'label' => esc_html__( 'Filter Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $filter_by + Utilities::get_woo_taxonomies(),
				'separator' => 'before',
				'default' => 'search_form',
			]
		);

		$this->add_control(
			'slider_handlers_style',
			[
				'label' => esc_html__( 'Handlers', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'round' => esc_html__( 'Round', 'wpr-addons' ),
					'square' => esc_html__( 'Square', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-product-filter-slide-handlers-',
				'default' => 'round',
				'condition' => [
					'filter_type' => 'price'
				]
			]
		);
 
		$this->add_control(
			'filter_list_type',
			[
				'label' => esc_html__( 'Count', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-product-filter-label-',
				'separator' => 'before',
				'default' => 'right',
				'condition' => [
					'filter_type!' => ['active', 'search', 'price']
				]
			]
		);

		$this->add_control(
			'rating_style',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => 'Icon 1',
					'style-2' => 'Icon 2',
				],
				'default' => 'style-2',
				'condition' => [
					'filter_type' => 'rating',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'wpr-addons' ),
						'icon' => 'fas fa-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'wpr-addons' ),
						'icon' => 'far fa-star',
					],
				],
				'default' => 'solid',
				'condition' => [
					'filter_type' => 'rating',
				],
			]
		);

		$this->add_control(
			'enable_hierarchy',
			[
				'label' => esc_html__( 'Enable Hierarchy', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filter_type' => [ 'product_cat', 'product_tag' ],
				]
			]
		);

		$this->add_control(
			'tax_query_type',
			[
				'label' => esc_html__( 'Relation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'and' => esc_html__( 'AND', 'wpr-addons' ),
					'or' => esc_html__( 'OR', 'wpr-addons' ),
				],
				'default' => 'and',
				'condition' => [
					'filter_type!' => ['active', 'search', 'price', 'rating', 'product_cat', 'product_tag'],
				],
			]
		);

		$this->add_control(
			'show_count_brackets',
			[
				'label' => esc_html__( 'Show Count', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'filter_type!' => ['active', 'search', 'price'],
				]
			]
		);

		$this->add_control(
			'brackets_type',
			[
				'label' => esc_html__( 'Bracket Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'round' => esc_html__( 'Round', 'wpr-addons' ),
					'square' => esc_html__( 'Square', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'show_count_brackets' => 'yes',
					'filter_type!' => ['active', 'search', 'price'],
				],
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Title ------------
		$this->start_controls_section(
			'section_product_filter_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'filter_title_text',
			[
				'label' => esc_html__( 'Title Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Product Filter'
			]
		);

		$this->add_control(
			'filter_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
			]
		);

		$this->add_responsive_control(
            'filter_title_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-title' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section
		
		// Tab: Content
		// Section: Search -----------
		$this->start_controls_section(
			'section_search',
			[
				'label' => esc_html__( 'Search', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'filter_type' => 'search'
				]
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search...', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inner',
				'options' => [
					'inner' => esc_html__( 'Inner', 'wpr-addons' ),
					'outer' => esc_html__( 'Outer', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-search-form-style-',
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_disable_click',
			[
				'label' => esc_html__( 'Disable Button Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-search-form-disable-submit-btn-',
				'condition' => [
					'search_btn_style' => 'inner',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Go',
				'condition' => [
					'search_btn_type' => 'text',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'search_btn_type' => 'icon',
					'search_btn' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Title ------------
		$this->start_controls_section(
			'section_product_filter_title_styles',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-filter-title',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => 'bold'
					],
					'font_size'      => [
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Search ------------
		$this->start_controls_section(
			'section_style_search',
			[
				'label' => esc_html__( 'Search', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'search'
				]
			]
		);

		$this->add_control(
			'input_styles_heading',
			[
				'label' => esc_html__( 'Input', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus_colors',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'input_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-input',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'input_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					'{{WRAPPER}} .wpr-search-form-input' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'btn_styles_heading',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'btn_hv_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hv_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit:hover',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer .wpr-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-right .wpr-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-left .wpr-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-search-form-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'btn_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'price'
				]
			]
		);

		$this->add_control(
			'price_slider_styles_heading',
			[
				'label' => esc_html__( 'Range Slider', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'price_slider_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-slider' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_slider_range_bg_color',
			[
				'label' => esc_html__( 'Range Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-slider .ui-slider-range' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_slider_handlers_bg_color',
			[
				'label' => esc_html__( 'Handlers Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-slider .ui-slider-handle' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_slider_height',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-filter-price-slider .ui-slider-range' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-filter-price-slider .ui-slider-handle' => 'height: calc({{SIZE}}{{UNIT}} * 4); width: calc({{SIZE}}{{UNIT}} * 4); transform: translateY(calc({{SIZE}}{{UNIT}} * -1.5)) translateX(-50%);;'
				]
			]
		);

		$this->add_control(
			'price_label_styles_heading',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
		);

		$this->add_control(
			'price_label_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_label_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-filter-price-label',
			]
		);

		$this->add_control(
			'price_btn_styles_heading',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'price_tabs_btn_colors' );

		$this->start_controls_tab(
			'price_tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'price_btn_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_btn_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_btn_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-filter-price-amount .button'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'price_tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'price_btn_hv_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_btn_hv_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_btn_hv_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_btn_hv_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-filter-price-amount .button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'price_btn_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'price_btn_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
            'price_btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_btn_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-filter-price-amount .button',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'price_btn_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-price-amount .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Rating ------------
		$this->start_controls_section(
			'section_style_rating',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type' => 'rating'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_rating_styles' );

		$this->start_controls_tab(
			'tab_rating_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878C2',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_rating_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating:hover i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color_hover',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating:hover i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color_hover',
			[
				'label' => esc_html__( 'Score Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878C2',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating span:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_rating_color_active',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-active-product-filter.wpr-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color_active',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-active-product-filter.wpr-woo-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color_active',
			[
				'label' => esc_html__( 'Score Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-active-product-filter.wpr-woo-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-woo-rating span:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_rating_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Ditance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-woo-rating span'
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Taxonomy ------------
		$this->start_controls_section(
			'section_style_taxonomies',
			[
				'label' => esc_html__( 'Taxonomies', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_type!' => ['active', 'price', 'search', 'rating']
				]
			]
		);

		$this->start_controls_tabs( 'tax_style' );

		$this->start_controls_tab(
			'tax_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF00',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tax_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-filter-tax-wrap a',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tax_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tax_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tax1_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tax1_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tax_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tax_margin',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tax_border_type',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tax_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tax_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'tax_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'child_tax_indent',
			[
				'label' => esc_html__( 'Child Indent', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-child a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'enable_hierarchy' => 'yes'
				]
			]
		);

		$this->add_control(
			'tax_checkbox',
			[
				'label' => esc_html__( 'Checkbox', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'tax_checkbox_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ECECEC',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li a span:first-child' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tax_checkbox_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ECECEC',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li a span:first-child' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tax_checkbox_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li a span:first-child' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'tax_checkbox_border_width',
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
					'{{WRAPPER}} .wpr-product-filter-tax-wrap a span:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tax_checkbox_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tax_checkbox_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-active-product-filter:not(.wpr-woo-rating) span:first-child:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tax_checkbox_bg_size',
			[
				'label' => esc_html__( 'Background Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li a span:first-child' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'tax_checkbox_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-filter-tax-wrap li a span:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

	}

	public function get_shop_url( $settings ) {

		global $wp;

        if ( '' == get_option('permalink_structure' ) ) {
            $url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
        } else {
            $url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        }

		// WPR Filters
		$url = add_query_arg( 'wprfilters', '', $url );

		// Min/Max.
		if ( isset( $_GET['min_price'] ) ) {
			$url = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $url );
		}

		if ( isset( $_GET['max_price'] ) ) {
			$url = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $url );
		}

		// Search
		if ( isset( $_GET['psearch'] ) ) {
			$url = add_query_arg( 'psearch', wp_unslash( $_GET['psearch'] ), $url );
		}

		// Rating
		if ( isset( $_GET['filter_rating'] ) ) {
			$url = add_query_arg( 'filter_rating', wp_unslash( $_GET['filter_rating'] ), $url );
		}

		// Categories
		if ( isset( $_GET['filter_product_cat'] ) ) {
			$url = add_query_arg( 'filter_product_cat', wp_unslash( $_GET['filter_product_cat'] ), $url );
		}

		// Tags
		if ( isset( $_GET['filter_product_tag'] ) ) {
			$url = add_query_arg( 'filter_product_tag', wp_unslash( $_GET['filter_product_tag'] ), $url );
		}

		// All current filters.
		if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure, WordPress.CodeAnalysis.AssignmentInCondition.Found
			foreach ( $_chosen_attributes as $name => $data ) {
				$filter_name = wc_attribute_taxonomy_slug( $name );
				if ( ! empty( $data['terms'] ) ) {
					$url = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $url );
				}
				if ( 'or' === $settings['tax_query_type'] || isset($_GET['query_type_' . $filter_name]) ) {
					$url = add_query_arg( 'query_type_' . $filter_name, 'or', $url );
				}
			}
		}

		// Fix URL
		// $url = str_replace( '%2C', ',', $url );
		
		return $url;
	}

	public function get_price_range_from_wpdb() {
        global $wpdb;
        $min_query = "SELECT MIN( CAST( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
        $max_query = "SELECT MAX( CAST( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
        $value_min = $wpdb->get_var( $min_query );
        $value_max = $wpdb->get_var( $max_query );
        return [
            'min_price' => (int)$value_min,
            'max_price' => (int)$value_max,
        ];
    }

	public function get_rating_count( $rating ){
		global $wpdb;
		$review_ratings = $wpdb->get_results("
			SELECT meta_value
			FROM {$wpdb->prefix}commentmeta as commentmeta
			JOIN {$wpdb->prefix}comments as comments ON comments.comment_id = commentmeta.comment_id
			WHERE commentmeta.meta_key = 'rating' AND comments.comment_approved = 1
			ORDER BY commentmeta.meta_value
		", ARRAY_A);

		$new_array = [];
		foreach($review_ratings as $k=>$v) {
			$new_array[$k] = $v['meta_value'];
		}

		$vals = array_count_values($new_array);
		$val = isset($vals[$rating]) ? $vals[$rating] : 0;

		return $val;
	}

	public function get_taxonomy_data( $filter, $attribute, $shop_url ) {
		// Remove Prefix
        if ( 0 === strpos($filter, 'pa_') ) {
            $filter = 'filter_' . wc_attribute_taxonomy_slug( $filter );
        }

		// Replace Categories and Tags
		if ( 'product_cat' === $filter || 'product_tag' === $filter ) {
			$filter = 'filter_'. $filter;
		}

		// Get Selected Filters 
		$selected_filters = isset( $_GET[ $filter ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter ] ) ) ) : [];
        $is_filter_active = in_array( $attribute->slug, $selected_filters, true );

		// Get Attribute Link
		$selected_filters = array_map( 'sanitize_title', $selected_filters );
		if ( ! in_array( $attribute->slug, $selected_filters, true ) ) {
			$selected_filters[] = $attribute->slug;
		}
        $url = remove_query_arg( $filter, $shop_url );

		// Remove Already Selected Filters
		foreach ( $selected_filters as $key => $value ) {
            if ( $is_filter_active && $value === $attribute->slug ) {
                unset( $selected_filters[ $key ] );
            }
        }

		// Add New Filters
        if ( ! empty( $selected_filters ) ) {
            asort( $selected_filters );
			$url = add_query_arg( $filter, implode( ',', $selected_filters ), $url );
            $url = str_replace( '%2C', ',', $url );
		}

		return [
			'url' => $url,
			'class' => $is_filter_active ? 'wpr-active-product-filter' : ''
		];
	}

	public function get_filter_count( $count, $settings ) {
		if ( 'yes' === $settings['show_count_brackets'] ) {
			if ( 'round' === $settings['brackets_type'] ) {
				return '<span> ('. esc_html($count) .')</span>';
			} elseif ( 'square' === $settings['brackets_type'] ) {
				return '<span> ['. esc_html($count) .']</span>';
			} else {
				return '<span> '. esc_html($count) .'</span>';
			}
		}
	}

	public function render_product_taxonomies_filter( $settings ) {
		$filter_type = $settings['filter_type'];

		// Hierarchical
		if ( 'yes' === $settings['enable_hierarchy'] ) {
			$taxonomies = get_terms( $filter_type, [ 'parent' => 0, 'child_of' => 0 ] );
	
			echo '<ul class="wpr-product-filter-tax-wrap">';
	
			foreach ( $taxonomies as $taxonomy ) {
				$tax_data = $this->get_taxonomy_data( $filter_type, $taxonomy, $this->get_shop_url($settings) );
	
				echo '<li>';
					echo '<a href="'. esc_url($tax_data['url']) .'" class="'. esc_attr($tax_data['class']) .'">';
						echo '<span></span>';
						echo '<span class="wpr-product-filter-tax-name">'. esc_html($taxonomy->name) .'</span>';
						echo $this->get_filter_count($taxonomy->count, $settings);
					echo '</a>';

					// Children
					$children = get_terms( $filter_type, [ 'parent' => $taxonomy->term_id ] );
					if ( !empty( $children ) ) {
						foreach ( $children as $key => $child ) {
							$child_tax_data = $this->get_taxonomy_data( $filter_type, $child, $this->get_shop_url($settings) );

							echo '<li class="wpr-product-filter-tax-child">';
								echo '<a href="'. esc_url($child_tax_data['url']) .'" class="'. esc_attr($child_tax_data['class']) .'">';
									echo '<span></span>';
									echo '<span class="wpr-product-filter-tax-name">'. esc_html($child->name) .'</span>';
									echo $this->get_filter_count($child->count, $settings);
								echo '</a>';
							echo '</li>';
						}
					}
				echo '</li>';
			}
			
			echo '<ul>';

		// Non Hierarchical
		} else {
			$taxonomies = get_terms( $filter_type );
	
			echo '<ul class="wpr-product-filter-tax-wrap">';
	
			foreach ( $taxonomies as $taxonomy ) {
				$tax_data = $this->get_taxonomy_data( $filter_type, $taxonomy, $this->get_shop_url($settings) );
	
				echo '<li>';
					echo '<a href="'. esc_url($tax_data['url']) .'" class="'. esc_attr($tax_data['class']) .'">';
						echo '<span></span>';
						echo '<span class="wpr-product-filter-tax-name">'. esc_html($taxonomy->name) .'</span>';
						echo $this->get_filter_count($taxonomy->count, $settings);
					echo '</a>';
				echo '</li>';
			}
			
			echo '<ul>';

		}
	}

	public function render_product_price_slider_filter( $settings ) {
		wp_enqueue_script( 'wc-price-slider' );
			
		// Round values to nearest 10 by default.
		$step = 1;

		// Find min and max price in current result set.
		$prices = $this->get_price_range_from_wpdb();
		$min_price = $prices['min_price'];
		$max_price = $prices['max_price'];

		// Check to see if we should add taxes to the prices if store are excl tax but display incl.
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

		if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
			$tax_rates = WC_Tax::get_rates('');

			if ( $tax_rates ) {
				$min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
				$max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
			}
		}

		$min_price = floor( $min_price / $step ) * $step;
		$max_price = ceil( $max_price / $step ) * $step;

		// If both min and max are equal, we don't need a slider.
		if ( $min_price === $max_price ) {
			return;
		}

		// Get Current Prices
		$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
		$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

		$form_action = $this->get_shop_url($settings);

		?>

		<form method="get" action="<?php echo esc_url( $form_action ); ?>">
			<div class="wpr-product-filter-price price_slider_wrapper">
				<div class="wpr-product-filter-price-slider price_slider" style="display:none;"></div>
				<div class="wpr-product-filter-price-amount price_slider_amount" data-step="<?php echo esc_attr( $step ); ?>">
					<input type="text" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'wpr-addons' ); ?>" />
					<input type="text" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'wpr-addons' ); ?>" />
					<?php /* translators: Filter: verb "to filter" */ ?>
					<button type="submit" class="button"><?php echo esc_html__( 'Filter', 'wpr-addons' ); ?></button>
					<div class="wpr-product-filter-price-label price_label" style="display:none;">
						<?php echo esc_html__( 'Price:', 'wpr-addons' ); ?> <span class="from"></span> &mdash; <span class="to"></span>
					</div>
					<?php echo wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ); ?>
				</div>
			</div>
		</form>
		
		<?php
	}
	
	public function render_product_search_filter( $settings ) {
		$form_action = $this->get_shop_url($settings);
		$search_value = isset($_GET['psearch']) ? $_GET['psearch'] : '';

		// $this->add_render_attribute(
		// 	'input', [
		// 		'placeholder' => $settings['search_placeholder'],
		// 		'class' => 'wpr-search-form-input',
		// 		'type' => 'search',
		// 		'name' => 'psearch',
		// 		'title' => esc_html__( 'Search', 'wpr-addons' ),
		// 		'value' => get_search_query(),
		// 	]
		// );

		?>

		<form method="get" class="wpr-search-form" action="<?php echo esc_url( $form_action ); ?>">
			<div class="wpr-search-form-input-wrap elementor-clearfix">
				<input placeholder=<?php echo $settings['search_placeholder']; ?> class="wpr-search-form-input" type="search" name="psearch" title="Search" value="<?php echo esc_attr($search_value); ?>">
				<?php if ( 'yes' === $settings['search_btn'] ) : ?>
					<?php if ( $settings['search_btn_style'] === 'inner' ) : ?>
					<button class="wpr-search-form-submit" type="submit">
						<?php if ( 'icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value'] ) : ?>
							<i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>
						<?php elseif( 'text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text'] ) : ?>
							<?php echo esc_html( $settings['search_btn_text'] ); ?>
						<?php endif; ?>
					</button>
					<?php endif; ?>
				<?php endif ; ?>
			</div>
			
			<?php if ( $settings['search_btn_style'] === 'outer' ) : ?>
				<button class="wpr-search-form-submit" type="submit">
					<?php if ( 'icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value'] ) : ?>
						<i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>
					<?php elseif( 'text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text'] ) : ?>
						<?php echo esc_html( $settings['search_btn_text'] ); ?>
					<?php endif; ?>
				</button>
			<?php endif; ?>
		</form>
		
		<?php
	}

	public function render_product_rating_filter( $settings ) {
		$filter_rating = isset( $_GET['filter_rating'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['filter_rating'] ) ) ) ) : array(); // WPCS: input var ok, CSRF ok, sanitization ok.

		$wrapper_class = 'wpr-product-filter-rating';
		$rating_icon = '&#xE934;';

		if ( 'style-1' === $settings['rating_style'] ) {
			$wrapper_class .= ' wpr-woo-rating-style-1';
			if ( 'outline' === $settings['rating_unmarked_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style-2' === $settings['rating_style'] ) {
			$rating_icon = '&#9733;';
			$wrapper_class .= ' wpr-woo-rating-style-2';

			if ( 'outline' === $settings['rating_unmarked_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		echo '<ul class="'. esc_attr($wrapper_class) .'">';

		for ( $rating = 5; $rating >= 1; $rating-- ) {
			$url = $this->get_shop_url($settings);

			if ( in_array( $rating, $filter_rating, true ) ) {
				$rating_url = implode( ',', array_diff( $filter_rating, array( $rating ) ) );
			} else {
				$rating_url = implode( ',', array_merge( $filter_rating, array( $rating ) ) );
			}

			$class = in_array( $rating, $filter_rating, true ) ? 'wpr-active-product-filter wpr-woo-rating' : 'wpr-woo-rating';
			$url = $rating_url ? add_query_arg( 'filter_rating', $rating_url, $url ) : remove_query_arg( 'filter_rating' );

			echo '<li class="'. esc_attr($class) .'">';
				echo '<a href="'. esc_url( $url ) .'">';
					echo '<span>';
						for ( $i = 1; $i <= 5; $i++ ) {
							if ( $i <= $rating ) {
								echo '<i class="wpr-rating-icon-full">'. esc_html($rating_icon) .'</i>';
							} else {
								echo '<i class="wpr-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
							}
						}
					echo '</span>';
					echo $this->get_filter_count($this->get_rating_count($rating), $settings); // tmp sample number
				 echo '</a>';
			echo '</li>';
		}

		echo '</ul>';
	}

	public function render_filter_title( $settings ) {
		if ( '' !== $settings['filter_title_text'] ) {
			echo '<'. $settings['filter_title_tag'] .' class="wpr-product-filter-title">';
				echo esc_html($settings['filter_title_text']);
			echo '</'. $settings['filter_title_tag'] .'>';
		}
	}

	public function render_product_active_filters( $settings ) {
		$_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes();
		$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$filter_rating      = isset( $_GET['filter_rating'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['filter_rating'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
		$base_link          = $this->get_shop_url($settings);

		

		if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $filter_rating ) ) {
			
		echo '<ul class="wpr-product-active-filters">';

		// Attributes.
		if ( ! empty( $_chosen_attributes ) ) {
			foreach ( $_chosen_attributes as $taxonomy => $data ) {
				foreach ( $data['terms'] as $term_slug ) {
					$term = get_term_by( 'slug', $term_slug, $taxonomy );
					if ( ! $term ) {
						continue;
					}

					$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
					$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
					$current_filter = array_map( 'sanitize_title', $current_filter );
					$new_filter     = array_diff( $current_filter, array( $term_slug ) );

					$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );

					if ( count( $new_filter ) > 0 ) {
						$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
					}

					$filter_classes = array( 'chosen', 'chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) ), 'chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) . '-' . $term_slug ) );

					echo '<li class="' . esc_attr( implode( ' ', $filter_classes ) ) . '"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
				}
			}
		}

		if ( $min_price ) {
			$link = remove_query_arg( 'min_price', $base_link );
			/* translators: %s: minimum price */
			echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Min %s', 'woocommerce' ), wc_price( $min_price ) ) . '</a></li>'; // WPCS: XSS ok.
		}

		if ( $max_price ) {
			$link = remove_query_arg( 'max_price', $base_link );
			/* translators: %s: maximum price */
			echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Max %s', 'woocommerce' ), wc_price( $max_price ) ) . '</a></li>'; // WPCS: XSS ok.
		}

		if ( ! empty( $filter_rating ) ) {
			foreach ( $filter_rating as $rating ) {
				$link_ratings = implode( ',', array_diff( $filter_rating, array( $rating ) ) );
				$link         = $link_ratings ? add_query_arg( 'filter_rating', $link_ratings ) : remove_query_arg( 'filter_rating', $base_link );

				/* translators: %s: rating */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'woocommerce' ), esc_html( $rating ) ) . '</a></li>';
			}
		}
		
		echo '</ul>';

		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		echo '<div class="wpr-product-filters">';

		// Title
		$this->render_filter_title($settings);

		// Search
		if ( 'active' === $settings['filter_type'] ) {
			$this->render_product_active_filters($settings);

		// Search
		} elseif ( 'search' === $settings['filter_type'] ) {
			$this->render_product_search_filter($settings);

		// Rating
		} elseif ( 'rating' === $settings['filter_type'] ) {
			$this->render_product_rating_filter($settings);

		// Price
		} elseif ( 'price' === $settings['filter_type'] ) {
			$this->render_product_price_slider_filter($settings);

		// Taxonomies
		} else {
			$this->render_product_taxonomies_filter($settings);
		}

		echo '</div>';
	}
	
}