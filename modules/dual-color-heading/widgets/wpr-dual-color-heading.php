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
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;



// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class Wpr_Dual_Color_Heading extends Widget_Base
{

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

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__('Content Style', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => esc_html__('Default', 'wpr-addons'),
					'icon-top'  => esc_html__('Icon on Top', 'wpr-addons'),
					'icon-and-desc-top'  => esc_html__('Icon & Description on top', 'wpr-addons'),
					'desc-top'  => esc_html__('Description on top', 'wpr-addons'),
				],
				'prefix_class' => 'wpr-dual-heading-',
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label' => __('Show Icon', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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

		$this->add_control(
			'title_first',
			[
				'label'   => __('Title (first part)', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('ROYAL  ADDONS  IS', 'wpr-addons'),
				'separator' => 'before',
				'label_block' => true
			]
		);

		$this->add_control(
			'title_second',
			[
				'label'   => __('Title (second part)', 'wpr-addons'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('THE  BEST  OF  ALL', 'wpr-addons'),
				'label_block' => true
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => __('Description', 'wpr-addons'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description', 'wpr-addons'),
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => __('Alignment', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
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
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-wrap' => 'text-align: {{VALUE}}',
				]
				// 'toggle' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_styles',
			[
				'label' => esc_html__('Content', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_style',
			[
				'label' => __('Icon', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
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

		$this->add_control(
			'heading_style',
			[
				'label' => __('Title', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'first_text_color',
			[
				'label' => __('First half', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'black',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .first' => 'color: {{VALUE}}',
				]
			]
		);


		$this->add_control(
			'second_text_color',
			[
				'label' => __('Second half', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'orange',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-title .second' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __('Typography', 'wpr-addons'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} h1.wpr-dual-title',
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
				]
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'description_style',
			[
				'label' => __('Description', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'gray',
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-heading-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
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
						<span class="first"><?php echo wp_kses($settings['title_first'], []); ?></span>&nbsp;
						<span class="second"><?php echo wp_kses($settings['title_second'], []); ?></span>
					</h1>
				</div>
				<div class="wpr-dual-heading-description" <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], []); ?></div>
				<?php if ('yes' == $settings['show_icon']) { ?>
					<div class="wpr-dual-heading-icon-wrap">
						<?php \Elementor\Icons_Manager::render_icon($settings['feature_list_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php } ?>
			</div>
		<?php
	}
}
