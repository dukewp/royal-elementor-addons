<?php
namespace WprAddons\Modules\CircleMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Circle_Menu extends Widget_Base {

	public function get_name() {
		return 'wpr-circle-menu';
	}

	public function get_title() {
		return esc_html__( 'Circle Menu', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-global-colors';
	}

	public function get_categories() {
		return [ 'wpr-widgets' ];
	}

	public function get_keywords() {
		return [ 'royal', 'circle menu', 'floating menu', 'radial menu', 'circular navigation' ];
	}

	public function get_script_depends() {
		return [ 'wpr-circle-menu' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_custom_help_url() {
		if ( empty( get_option( 'wpr_wl_plugin_links' ) ) )
			return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
		return '';
	}

	protected function register_controls() {

		// Section: Items
		$this->start_controls_section(
			'section_circle_menu_items',
			[
				'label' => esc_html__( 'Items', 'wpr-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'cm_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'cm_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Home', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Type your title here', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'cm_link',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'cm_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'cm_title' => esc_html__( 'Home', 'wpr-addons' ),
						'cm_icon' => [
							'value' => 'fas fa-home',
							'library' => 'fa-solid',
						],
					],
					[
						'cm_title' => esc_html__( 'About', 'wpr-addons' ),
						'cm_icon' => [
							'value' => 'fas fa-info-circle',
							'library' => 'fa-solid',
						],
					],
					[
						'cm_title' => esc_html__( 'Services', 'wpr-addons' ),
						'cm_icon' => [
							'value' => 'fas fa-cogs',
							'library' => 'fa-solid',
						],
					],
					[
						'cm_title' => esc_html__( 'FAQ', 'wpr-addons' ),
						'cm_icon' => [
							'value' => 'fas fa-question-circle',
							'library' => 'fa-solid',
						],
					],
					[
						'cm_title' => esc_html__( 'Contact', 'wpr-addons' ),
						'cm_icon' => [
							'value' => 'fas fa-envelope',
							'library' => 'fa-solid',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		// Section: Layout
		$this->start_controls_section(
			'section_circle_menu_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cm_direction',
			[
				'label' => esc_html__( 'Menu Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom-right',
				'options' => [
					'top'          => esc_html__( 'Top', 'wpr-addons' ),
					'right'        => esc_html__( 'Right', 'wpr-addons' ),
					'bottom'       => esc_html__( 'Bottom', 'wpr-addons' ),
					'left'         => esc_html__( 'Left', 'wpr-addons' ),
					'full'         => esc_html__( 'Full', 'wpr-addons' ),
					'top-left'     => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-right'    => esc_html__( 'Top Right', 'wpr-addons' ),
					'top-half'     => esc_html__( 'Top Half', 'wpr-addons' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
					'bottom-half'  => esc_html__( 'Bottom Half', 'wpr-addons' ),
					'left-half'    => esc_html__( 'Left Half', 'wpr-addons' ),
					'right-half'   => esc_html__( 'Right Half', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'cm_distance',
			[
				'label' => esc_html__( 'Circle Menu Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 150,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'step' => 5,
						'max' => 500,
					],
				],
			]
		);

		$wrapper_selector = Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' ) ? '{{WRAPPER}}' : '{{WRAPPER}} .elementor-widget-container';

		$this->add_control(
			'cm_diameter',
			[
				'label' => esc_html__( 'Button Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'size' => 60,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'step' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					$wrapper_selector => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cm_show_titles',
			[
				'label' => esc_html__( 'Show Titles', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Section: Settings
		$this->start_controls_section(
			'section_circle_menu_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cm_speed',
			[
				'label' => esc_html__( 'Speed', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 600,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'step' => 10,
						'max' => 1000,
					],
				],
			]
		);

		$this->add_control(
			'cm_delay',
			[
				'label' => esc_html__( 'Delay', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'step' => 10,
						'max' => 2000,
					],
				],
			]
		);

		$this->add_control(
			'cm_step_out',
			[
				'label' => esc_html__( 'Step Out', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => -80,
				],
				'range' => [
					'px' => [
						'min' => -200,
						'step' => 5,
						'max' => 200,
					],
				],
			]
		);

		$this->add_control(
			'cm_step_in',
			[
				'label' => esc_html__( 'Step In', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => -105,
				],
				'range' => [
					'px' => [
						'min' => -200,
						'step' => 5,
						'max' => 200,
					],
				],
			]
		);

		$this->add_control(
			'cm_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
					'click' => esc_html__( 'Click', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'cm_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'ease'        => esc_html__( 'Ease', 'wpr-addons' ),
					'linear'      => esc_html__( 'Linear', 'wpr-addons' ),
					'ease-in'     => esc_html__( 'Ease-In', 'wpr-addons' ),
					'ease-out'    => esc_html__( 'Ease-Out', 'wpr-addons' ),
					'ease-in-out' => esc_html__( 'Ease-In-Out', 'wpr-addons' ),
				],
			]
		);

		$this->end_controls_section();

		// Style: Items
		$this->start_controls_section(
			'section_style_items',
			[
				'label' => esc_html__( 'Items', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_item_colors' );

		// Normal Tab
		$this->start_controls_tab(
			'tab_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-circle-menu-box li',
			]
		);

		$this->add_control(
			'item_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-item :is(i, svg)' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .wpr-circle-menu-item > svg > path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-box li' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-circle-menu-box li',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'fields_options' => [
					'typography' => [ 'default' => 'yes' ],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					],
					'line_height' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-circle-menu-item',
			]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'tab_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-circle-menu-box li:hover',
			]
		);

		$this->add_control(
			'item_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-box li:hover .wpr-circle-menu-item :is(i, svg)' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .wpr-circle-menu-box li:hover .wpr-circle-menu-item > svg > path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-box li:hover .wpr-circle-menu-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-box li:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-circle-menu-box li:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// After Tabs

		$this->add_control(
			'item_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-item :is(i, svg)' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-circle-menu-close :is(i, svg)' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_icon_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-item .wpr-circle-menu-icon-wrap :is(i, svg)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_border_type',
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
					'{{WRAPPER}} .wpr-circle-menu-box li' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-box li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'item_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-circle-menu-wrapper .wpr-circle-menu-box li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$circle_config = [
			'menuDirection'  => $settings['cm_direction'],
			'menuDiameter'   => $settings['cm_diameter'],
			'menuRadius'     => $settings['cm_distance'],
			'menuSpeed'      => $settings['cm_speed'],
			'menuDelay'      => $settings['cm_delay'],
			'menuStepOut'    => $settings['cm_step_out'],
			'menuStepIn'     => $settings['cm_step_in'],
			'menuTrigger'    => $settings['cm_trigger'],
			'menuTransition' => $settings['cm_transition'],
		];

		$this->add_render_attribute( 'circle-menu-wrapper', [
			'class' => 'wpr-circle-menu-wrapper wpr-circle-menu-trigger-' . $settings['cm_trigger'],
			'data-settings' => wp_json_encode( $circle_config ),
		] );

		$show_titles = 'yes' === $settings['cm_show_titles'];

		?>
		<div <?php $this->print_render_attribute_string( 'circle-menu-wrapper' ); ?>>
			<ul class="wpr-circle-menu-box">
				<?php foreach ( $settings['cm_items'] as $index => $item ) :
					if ( ! empty( $item['cm_link']['url'] ) ) {
						$this->add_link_attributes( 'cm_link_' . $item['_id'], $item['cm_link'] );
					}

					$li_class = ( $index === 0 ) ? 'wpr-circle-menu-item-trigger' : 'wpr-circle-menu-item-list';
				?>
					<li class="<?php echo esc_attr( $li_class ); ?>">
						<a class="wpr-circle-menu-item" <?php $this->print_render_attribute_string( 'cm_link_' . $item['_id'] ); ?>>
							<span class="wpr-circle-menu-icon-wrap">
								<?php if ( ! empty( $item['cm_icon']['value'] ) ) :
									Icons_Manager::render_icon( $item['cm_icon'], [ 'aria-hidden' => 'true' ] );
								endif; ?>

								<?php if ( $show_titles && ! empty( $item['cm_title'] ) ) : ?>
									<span class="wpr-circle-menu-item-title"><?php echo esc_html( $item['cm_title'] ); ?></span>
								<?php endif; ?>
							</span>

							<?php if ( $index === 0 && 'click' === $settings['cm_trigger'] ) : ?>
								<span class="wpr-circle-menu-close">
									<i aria-hidden="true" class="fas fa-times"></i>
								</span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}
