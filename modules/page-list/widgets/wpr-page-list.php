<?php
namespace WprAddons\Modules\PageList\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Page_List extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-list';
	}

	public function get_title() {
		return esc_html__( 'Page List', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-editor-list-ul';
	}

	public function get_categories() {
		return [ 'wpr-widgets' ];
	}

	public function get_keywords() {
		return [ 'royal', 'page-list', 'list'];
	}

    public function add_repeater_args_page_list() {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        ); 

        $pages = get_pages($args); // get all pages based on supplied args

        $pages_array = [];
        
        foreach($pages as $page) { // $pages is array of object
            $pages_array[$page->ID] = $page->post_title;
        }

        return $pages_array;

    }

	public function add_control_title_pointer_color_hr() {
		$this->add_control(
			'title_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_title_pointer() {
		$this->add_control(
			'title_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_title_pointer_height() {
		$this->add_control(
			'title_pointer_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_title_pointer_animation() {
		$this->add_control(
			'title_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
    
    protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_page_list_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'taxonomy_list_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
                'prefix_class' => 'wpr-page-list-',
				'label_block' => false,
			]
		);

		$this->add_control(
			'open_in_new_page',
			[
				'label' => esc_html__( 'Open In New Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'default' => 'yes',
			]
		);

        $this->add_control_title_pointer_color_hr();

        $this->add_control_title_pointer();

        $this->add_control_title_pointer_height();

        $this->add_control_title_pointer_animation();

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Content ----------
		$this->start_controls_section(
			'section_page_list_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'page_list_item_type',
            [
                'label'       => esc_html__( 'Page Type', 'wpr-addons' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'square',
                'label_block' => false,
                'default' => 'custom',
                'options'     => [
                    'dynamic'  => esc_html__( 'Dynamic', 'wpr-addons' ),
                    'custom' => esc_html__( 'Custom', 'wpr-addons' )
                ]
            ]
        );

		$repeater->add_control(
			'query_page_selection',
			[
				'label' => esc_html__( 'Select Page', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT2,
				'options' => $this->add_repeater_args_page_list(),
				'label_block' => true,
                'condition' => [
                    'page_list_item_type' => 'dynamic'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'New Page' , 'wpr-addons' ),
				'separator' => 'before',
				'label_block' => true,
                'condition' => [
                    'page_list_item_type' => 'custom'
                ]
			]
		);

		$repeater->add_control(
			'page_list_item_sub_title', [
				'label' => esc_html__( 'Sub Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'New Page Sub-title' , 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'page_list_item_title_url',
			[
				'label' => esc_html__( 'Title Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
                'condition' => [
                    'page_list_item_type' => 'custom'
                ],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'show_page_list_item_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => 'yes',
                'separator' => 'before'
			]
		);

		$repeater->add_control(
			'page_list_item_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => 'svg',
				'condition' => [
					'show_page_list_item_icon' => 'yes'
				]
			]
		);

		$repeater->add_control(
			'show_page_list_item_badge',
			[
				'label' => esc_html__( 'Show Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'default' => '',
                'separator' => 'before'
			]
		);

		$repeater->add_control(
			'page_list_item_badge_text', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Badge' , 'wpr-addons' ),
				'label_block' => true,
                'condition' => [
                    'show_page_list_item_badge' => 'yes'
                ]
			]
		);

		$this->add_control(
			'page_list',
			[
				'label' => esc_html__( 'Repeater List', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'page_list_item_title' => esc_html__( 'New Page 1', 'wpr-addons' ),
						'page_list_item_sub_title' => esc_html__( 'First Sub Title', 'wpr-addons' ),
						// 'page_list_icon' => [
						// 	'value' => 'fas fa-rocket',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
					[
						'page_list_item_title' => esc_html__( 'New Page 2', 'wpr-addons' ),
						'page_list_item_sub_title' => esc_html__( 'Second Sub Title', 'wpr-addons' ),
						// 'page_list_icon' => [
						// 	'value' => 'far fa-flag',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
					[
						'page_list_item_title' => esc_html__( 'New Page 3', 'wpr-addons' ),
						'page_list_item_sub_title' => esc_html__( 'Third Sub Title', 'wpr-addons' ),
						// 'page_list_icon' => [
						// 	'value' => 'fas fa-grip-lines-vertical',
						// 	'library' => 'solid'
						// ],
						// 'page_list_label' =>[
						// ],
					],
				],
				'title_field' => '{{{ page_list_item_title }}}',
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: List Item Style ---
		$this->start_controls_section(
			'section_style_page_list_item',
			[
				'label' => esc_html__( 'List Item Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'list_item_style' );

		$this->start_controls_tab(
			'page_list_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'page_list_item_title_color',
			[
				'label'  => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_list_item_sub_title_color',
			[
				'label'  => esc_html__( 'Sub-title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_list_item_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'page_list_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_list_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_list_item_title_typo',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-page-list-item-wrap a',
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_list_item_sub_title_typo',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-page-list-item-wrap p',
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
			'page_list_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'page_list_item_title_color_hr',
			[
				'label'  => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'page_list_item_background_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'page_list_item_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'page_list_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'page_list_item_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 8,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'page_list_item_border_type',
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
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'page_list_item_border_width',
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
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'page_list_item_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'page_list_item_radius',
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
					'{{WRAPPER}} .wpr-page-list-item-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Content --------
		$this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'badge_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'badge_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_border_padding',
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
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'badge_border_radius',
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
					'{{WRAPPER}} .wpr-page-list-item-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Content --------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-page-list .wpr-page-list-item-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $class = '';

		// Pointer Class
		$page_title_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $settings['title_pointer'];
		$page_title_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $settings['title_pointer_animation'];

		$class .= ' wpr-pointer-'. $page_title_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $page_title_pointer_animation;

        echo '<div class="wpr-page-list-wrap">';
            echo '<ul class="wpr-page-list">';

            if ( 'yes' === $settings['open_in_new_page'] ) {
                $target = '_blank';
            } else {
                $target = '_self';
            }
            
                foreach ( $settings['page_list'] as $key=>$item ) {
                    if ( 'yes' === $item['show_page_list_item_icon'] ) {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon( $item['page_list_item_icon'], [ 'aria-hidden' => 'true' ] );
                        $icon = ob_get_clean();
                    }
                    if ( 'dynamic' === $item['page_list_item_type'] && isset($item['query_page_selection']) ) {
                        echo '<div class="wpr-page-list-item-wrap '. $class .'">';
                            echo 'yes' === $item['show_page_list_item_icon'] ? '<span class="wpr-page-list-item-icon">'. $icon .'</span>' : '';
                            echo '<li>';
                                echo '<a class="wpr-pointer-item" href='. get_the_permalink($item['query_page_selection']) .' target='. $target .'>';
                                    echo get_the_title($item['query_page_selection']);
                                echo '</a>';
                                if ( isset($item['page_list_item_sub_title']) ) {
                                    echo '<p>'. $item['page_list_item_sub_title'] .'</p>';
                                }
                            echo '</li>';
                            echo 'yes' === $item['show_page_list_item_badge'] ? '<span class="wpr-page-list-item-badge">'. $item['page_list_item_badge_text'] .'</span>' : '';
                        echo '</div>';
                    } else if ( 'custom' === $item['page_list_item_type'] && isset($item['page_list_item_title']) ) {
                        if ( ! empty( $item['page_list_item_title_url']['url'] ) ) {
                            $this->add_link_attributes( 'title_link_'. $key, $item['page_list_item_title_url'] );
                        }
                        echo '<div class="wpr-page-list-item-wrap '. $class .'">';
                            echo 'yes' === $item['show_page_list_item_icon'] ? '<span class="wpr-page-list-item-icon">'. $icon .'</span>' : '';
                            echo '<li>';
                                echo '<a  '. $this->get_render_attribute_string( 'title_link_'. $key ) .' class="wpr-pointer-item">';
                                    echo $item['page_list_item_title'];
                                echo '</a>';
                                if ( isset($item['page_list_item_sub_title']) ) {
                                    echo '<p>'. $item['page_list_item_sub_title'] .'</p>';
                                }
                            echo '</li>';
                            echo 'yes' === $item['show_page_list_item_badge'] ? '<span class="wpr-page-list-item-badge">'. $item['page_list_item_badge_text'] .'</span>' : '';
                        echo '</div>';
                    }
                }

            echo '</ul>';
        echo '</div>';
    }
}