<?php

namespace WprAddons\Modules\DualColorHeading\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
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

class Wpr_Dual_Color_Heading extends Widget_Base
{

	public function get_name()
	{
		return 'wpr-dual-color-heading';
	}

	public function get_title()
	{
		return esc_html__('Dual Color Heading', 'wpr-addons');
	}
	public function get_icon()
	{
		return 'wpr-icon eicon-heading';
	}

	public function get_categories()
	{
		return ['wpr-widgets'];
	}

	public function get_keywords()
	{
		return ['Dual', 'Color', 'heading'];
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends()
	{
		return array('');
	}


	public function try_my_paddings($title, $name, $selector)
	{
		$this->add_responsive_control(
			$title,
			[
				'label' => __($name, 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}}' . $selector  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
	public function try_my_margins($title, $name, $selector)
	{
		$this->add_responsive_control(
			$title,
			[
				'label' => __($name, 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}}' . $selector  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __('Content', 'wpr-addons'),
			)
		);

		$this->add_control(
			'content_style',
			[
				'label' => __('Content Style', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'Default',
				'options' => [
					'Default'  => __('default', 'wpr-addons'),
					'Icon on top'  => __('icon on top', 'wpr-addons'),
					'Icon & sub-text on top'  => __('icon & sub-text on top', 'wpr-addons'),
					'Sub-text on top'  => __('sub-text on top', 'wpr-addons'),
				],
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'wpr-addons'),
				'label_off' => __('Hide', 'wpr-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'icon_1',
			[
				'label' => esc_html__('Select Icon', 'wpr-addons'),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],

			]
		);

		$this->add_control(
			'title_first',
			array(
				'label'   => __('Title (second part)', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('ROYAL  ADDONS  IS', 'wpr-addons'),
			)
		);

		$this->add_control(
			'title_second',
			array(
				'label'   => __('Title (second part)', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('THE  BEST  OF  ALL', 'wpr-addons'),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __('Description', 'wpr-addons'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description', 'wpr-addons'),
			)
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'wpr-addons'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'wpr-addons'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'wpr-addons'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
			]
		);
		$this->add_control(
			'hover_animation',
			[
				'label' => __('Hover Animation', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
				'prefix_class' => 'elementor-animation-',
			]
		);
		$this->add_control(
			'openpage_pro_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<span style="color:#2a2a2a;">BlaBlaBla</span> option is fully supported<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-advanced-slider-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
				'content_classes' => 'wpr-pro-notice',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __('Style Section', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background-color',
			[
				'label' => __('Background Color', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .widgetcont' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->try_my_paddings('container_padding', 'Container Padding', ' .widgetcont');
		$this->try_my_margins('container_margin', 'Container Margin', ' .widgetcont');


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __('Border', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .widgetcont',
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border radius', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .widgetcont' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __('Box Shadow', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .widgetcont',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			array(
				'label' => __('Icon Style', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .iconcont' => 'font-size: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .iconcont' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'color_and_typography',
			[
				'label' => esc_html__('Color & Typography', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading-style',
			[
				'label' => __('Title Style', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'first_text_color',
			[
				'label' => __('First half', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .title .first' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'second_text_color',
			[
				'label' => __('Second half', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'orange',
				'selectors' => [
					'{{WRAPPER}} .title .second' => 'color: {{VALUE}}',
				],
			]
		);

		$this->try_my_margins('title-margin', 'Title Margin', '.titlecont');

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __('Title Typography', 'wpr-addons'),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selectors' => [
					'{{WRAPPER}} h1.first',
					'{{WRAPPER}} h1.second'
				],
			]
		);


		$this->add_control(
			'description-style',
			[
				'label' => __('Description Style', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Description Color', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'gray',
				'selectors' => [
					'{{WRAPPER}} .description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Description Typography', 'wpr-addons'),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .description',
			]
		);


		$this->end_controls_section();



	}
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
		$this->add_inline_editing_attributes('content', 'advanced');
?>
		<?php if ($settings['content_style'] == 'Default') { ?>
			<div class="widgetcont" style="text-align: <?php echo $settings['text_align'] ?>;">
				<div class="titlecont">
					<h1 class="title"><span class="first"><?php echo wp_kses($settings['title_first'], $settings['hover_animation'], array()); ?></span>&nbsp;&nbsp;<span class="second"><?php echo wp_kses($settings['title_second'], $settings['hover_animation'], array()); ?></span></h1>
				</div>
				<div class="description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], array()); ?></div>
				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="iconcont">
						<?php \Elementor\Icons_Manager::render_icon($settings['icon_1'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if ($settings['content_style'] == 'Icon on top') { ?>
			<div class="widgetcont" style="text-align: <?php echo $settings['text_align'] ?>;">
				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="iconcont">
						<?php \Elementor\Icons_Manager::render_icon($settings['icon_1'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>
				<div>
					<h1 class="title"><span class="first"><?php echo wp_kses($settings['title_first'], $settings['hover_animation'], array()); ?></span>&nbsp;&nbsp;<span class="second"><?php echo wp_kses($settings['title_second'], $settings['hover_animation'], array()); ?></span></h1>
				</div>
				<div class="description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], array()); ?></div>
			</div>
		<?php } ?>

		<?php if ($settings['content_style'] == 'Icon & sub-text on top') { ?>
			<div class="widgetcont" style="text-align: <?php echo $settings['text_align'] ?>;">
				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="iconcont">
						<?php \Elementor\Icons_Manager::render_icon($settings['icon_1'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>
				<div class="description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], array()); ?></div>
				<div>
					<h1 class="title"><span class="first"><?php echo wp_kses($settings['title_first'], $settings['hover_animation'], array()); ?></span>&nbsp;&nbsp;<span class="second"><?php echo wp_kses($settings['title_second'], $settings['hover_animation'], array()); ?></span></h1>
				</div>
			</div>
		<?php } ?>

		<?php if ($settings['content_style'] == 'Sub-text on top') { ?>
			<div class="widgetcont" style="text-align: <?php echo $settings['text_align'] ?>;">
				<div class="description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], array()); ?></div>
				<div>
					<h1 class="title"><span class="first"><?php echo wp_kses($settings['title_first'], $settings['hover_animation'], array()); ?></span>&nbsp;&nbsp;<span class="second"><?php echo wp_kses($settings['title_second'], $settings['hover_animation'], array()); ?></span></h1>
				</div>
				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="iconcont">
						<?php \Elementor\Icons_Manager::render_icon($settings['icon_1'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

<?php
	}
}
