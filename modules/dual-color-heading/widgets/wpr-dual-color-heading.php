<?php

namespace WprAddons\Modules\DualColorHeading\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes\Color;
use Elementor\Icons_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Dual_Color_Heading extends Widget_Base {

	public function get_name() {
		return 'wpr-dual-color-heading';
	}

	public function get_title() {
		return esc_html__('Dual Color Heading', 'wpr-addons');
	}
	public function get_icon() {
		return 'wpr-icon eicon-heading';
	}

	public function get_categories() {
		return ['wpr-widgets'];
	}

	public function get_keywords() {
		return ['royal', 'Dual Color Heading'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Settings', 'wpr-addons'),
			]
		);

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__('Select Layout', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => esc_html__('Default', 'wpr-addons'),
					'icon-top'  => esc_html__('Icon Top', 'wpr-addons'),
					'desc-top'  => esc_html__('Desccription Top', 'wpr-addons'),
					'icon-and-desc-top'  => esc_html__('Heading Bottom', 'wpr-addons'),
				],
				'prefix_class' => 'wpr-dual-heading-',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'wpr-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'wpr-addons'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'wpr-addons'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'wpr-addons'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-wrap' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading',
			[
				'label'   => __('Primary Heading', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Dual Color', 'wpr-addons'),
				'separator' => 'before',
				'label_block' => true
			]
		);

		$this->add_control(
			'secondary_heading',
			[
				'label'   => __('Secondary Heading', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Heading Widget', 'wpr-addons'),
				'label_block' => true
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __('Show Icon', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'wpr-addons'),
				'label_off' => __('Hide', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => __('Description', 'wpr-addons'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description text or Sub Heading', 'wpr-addons'),
				'condition' => [
					'show_description' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'wpr-addons'),
				'label_off' => __('Hide', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'feature_list_icon',
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
					'show_icon' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'primary_heading_styles',
			[
				'label' => esc_html__('Primary Heading', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_heading_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-dual-title .first'
			]
		);

		$this->add_control(
			'primary_heading_color',
			[
				'label' => __('Text Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'primary_heading_border_color',
			[
				'label' => __('Border Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'primary_heading_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} h1.wpr-dual-title .first',
			]
		);

		$this->add_responsive_control(
			'primary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'primary_heading_border_type',
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
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'primary_heading_border_width',
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
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'primary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'primary_heading_radius',
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
					'{{WRAPPER}} .wpr-dual-title .first' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title-wrap'  => 'margin-bottom: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'feature_list_title_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first'  => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'secondary_heading_styles',
			[
				'label' => esc_html__('Secondary Heading', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_heading_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-dual-title .second'
			]
		);

		$this->add_control(
			'secondary_heading_color',
			[
				'label' => __('Text Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'orange',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'secondary_heading_border_color',
			[
				'label' => __('Border Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_heading_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} h1.wpr-dual-title .second',
			]
		);

		$this->add_responsive_control(
			'secondary_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'secondary_heading_border_type',
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
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'secondary_heading_border_width',
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
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'secondary_heading_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'secondary_heading_radius',
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
					'{{WRAPPER}} .wpr-dual-title .second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'general_styles_description',
			[
				'label' => esc_html__('Description', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'gray',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-dual-heading-description',
			]
		);

		$this->add_responsive_control(
			'feature_list_description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-description'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_styles_icon',
			[
				'label' => esc_html__('Icon', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_style',
			[
				'label' => __('Icon', 'wpr-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Color', 'wpr-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__('Size', 'wpr-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'feature_list_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-icon-wrap'  => 'margin-bottom: {{SIZE}}px;',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
		$this->add_inline_editing_attributes('content', 'advanced');

        ?>
			<div class="wpr-dual-heading-wrap">
				<div class="wpr-dual-title-wrap">
					<h1 class="wpr-dual-title">
						<span class="first"><?php echo wp_kses($settings['primary_heading'], []); ?></span>&nbsp;
						<span class="second"><?php echo wp_kses($settings['secondary_heading'], []); ?></span>
					</h1>
				</div>
				
				<?php if ('yes' == $settings['show_description']) { ?>
					<div class="wpr-dual-heading-description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], []); ?></div>
				<?php } ?>

				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="wpr-dual-heading-icon-wrap">
						<?php \Elementor\Icons_Manager::render_icon($settings['feature_list_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>

			</div>
		<?php
	}
}
