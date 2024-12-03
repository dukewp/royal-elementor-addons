<?php
namespace WprAddons\Modules\ImageScroll\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Image_Scroll extends Widget_Base {
		
	public function get_name() {
		return 'wpr-image-scroll';
	}

	public function get_title() {
		return esc_html__( 'Image Scroll', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'image scroll' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-image-scroll-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {
        // Image Section
        $this->start_controls_section(
            'section_image_scroll',
            [
                'label' => esc_html__('Settings', 'wpr-addons'),
            ]
        );

        $this->add_control(
            'update_preview',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview-button editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'wpr-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'full',
            ]
        );

        // $this->end_controls_section();

        // // Advanced Settings
        // $this->start_controls_section(
        //     'section_advanced_settings',
        //     [
        //         'label' => esc_html__('Advanced Settings', 'wpr-addons'),
        //     ]
        // );

        $this->add_control(
            'scroll_direction',
            [
                'label' => esc_html__('Direction', 'wpr-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical' => esc_html__('Vertical', 'wpr-addons'),
                    'horizontal' => esc_html__('Horizontal', 'wpr-addons'),
                ],
                'prefix_class' => 'wpr-scroll-',
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => esc_html__('Image Fit', 'wpr-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__('Cover', 'wpr-addons'),
                    'contain' => esc_html__('Contain', 'wpr-addons'),
                    'auto' => esc_html__('Auto', 'wpr-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'scroll_direction' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => esc_html__('Image Position', 'wpr-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'top left' => esc_html__('Top Left', 'wpr-addons'),
                    'top center' => esc_html__('Top Center', 'wpr-addons'),
                    'top right' => esc_html__('Top Right', 'wpr-addons'),
                    'center left' => esc_html__('Center Left', 'wpr-addons'),
                    'center center' => esc_html__('Center Center', 'wpr-addons'),
                    'center right' => esc_html__('Center Right', 'wpr-addons'),
                    'bottom left' => esc_html__('Bottom Left', 'wpr-addons'),
                    'bottom center' => esc_html__('Bottom Center', 'wpr-addons'),
                    'bottom right' => esc_html__('Bottom Right', 'wpr-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'scroll_direction' => 'horizontal',
                    'image_fit!' => 'auto',
                ],
            ]
        );

        $this->add_control(
            'reverse_direction',
            [
                'label' => esc_html__('Reverse Direction', 'wpr-addons'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'prefix_class' => 'wpr-direction-',
            ]
        );

        $this->add_control(
            'trigger_type',
            [
                'label' => esc_html__('Trigger', 'wpr-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => esc_html__('Hover', 'wpr-addons'),
                    'scroll' => esc_html__('Mouse Scroll', 'wpr-addons'),
                ],
                'prefix_class' => 'wpr-trigger-',
            ]
        );

        $this->add_control(
            'scroll_speed',
            [
                'label' => esc_html__('Animation Speed', 'wpr-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
            ]
        );

        $this->add_control(
            'enable_wrapper_link',
            [
                'label' => esc_html__('Enable Wrapper Link', 'wpr-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wrapper_link',
            [
                'label' => esc_html__('Link', 'wpr-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'wpr-addons'),
                'condition' => [
                    'enable_wrapper_link' => 'yes',
                ],
            ]
        );// Add this control after scroll_speed in register_controls()

        $this->add_control(
            'transition_duration',
            [
                'label' => esc_html__('Transition Duration', 'wpr-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0.1,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap' => 'transition-duration: {{VALUE}}s;'
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'transition_timing',
            [
                'label' => esc_html__('Transition Timing', 'wpr-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ease',
                'options' => [
                    'linear' => esc_html__('Linear', 'wpr-addons'),
                    'ease' => esc_html__('Ease', 'wpr-addons'),
                    'ease-in' => esc_html__('Ease In', 'wpr-addons'),
                    'ease-out' => esc_html__('Ease Out', 'wpr-addons'),
                    'ease-in-out' => esc_html__('Ease In Out', 'wpr-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap' => 'transition-timing-function: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Image Style', 'wpr-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Height', 'wpr-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Image Width', 'wpr-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 100,
                        'max' => 300,
                    ],
                    'vw' => [
                        'min' => 100,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-scroll-horizontal img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'scroll_direction' => 'horizontal',
                ],
            ]
        );

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' )
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .wpr-image-scroll-wrap'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wpr-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .wpr-image-scroll-wrap',
            ]
        );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' )
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}} .wpr-image-scroll-wrap:hover',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'wpr-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpr-image-scroll-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wpr-image-scroll-wrap:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['image']['url'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', [
            'wpr-image-scroll-wrap',
            'wpr-scroll-' . $settings['scroll_direction'],
            'wpr-trigger-' . $settings['trigger_type'],
            $settings['reverse_direction'] === 'yes' ? 'wpr-direction-reverse' : ''
        ]);

        $this->add_render_attribute('wrapper', 'data-speed', $settings['scroll_speed']['size']);

        // Add link wrapper if enabled
        if ('yes' === $settings['enable_wrapper_link'] && !empty($settings['wrapper_link']['url'])) {
            $this->add_link_attributes('link', $settings['wrapper_link']);
            $this->add_render_attribute('link', 'class', 'wpr-image-scroll-link');
        }
        ?>

        <?php if ('yes' === $settings['enable_wrapper_link'] && !empty($settings['wrapper_link']['url'])) : ?>
            <a <?php echo $this->get_render_attribute_string('link'); ?>>
        <?php endif; ?>

        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <?php echo Group_Control_Image_Size::get_attachment_image_html($settings); ?>
        </div>

        <?php if ('yes' === $settings['enable_wrapper_link'] && !empty($settings['wrapper_link']['url'])) : ?>
            </a>
        <?php endif;
    }

    protected function content_template() {
        ?>
        <# if ( settings.image.url ) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.image_size,
                dimension: settings.image_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );
            var reverseClass = settings.reverse_direction === 'yes' ? 'wpr-direction-reverse' : '';

            view.addRenderAttribute( 'wrapper', 'class', [
                'wpr-image-scroll-wrap',
                'wpr-scroll-' + settings.scroll_direction,
                'wpr-trigger-' + settings.trigger_type,
                reverseClass
            ]);

            view.addRenderAttribute( 'wrapper', 'data-speed', settings.scroll_speed.size );
        #>
            <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
                <img src="{{ image_url }}" />
            </div>
        <# } #>
        <?php
    }
}