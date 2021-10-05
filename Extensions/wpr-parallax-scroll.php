<?php
namespace WprAddons\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;

class Wpr_Parallax_Scroll
{
    public function __construct()
    {
            add_action('elementor/element/section/section_layout/after_section_end', [$this, 'section_parallax'], 10);
            // add_action('elementor/element/section/section_layout/after_section_end', [$this, 'section_particles'], 10);
            // add_action('elementor/element/common/_section_style/after_section_end', [$this, 'content_protection'], 10);
            // add_action('elementor/element/common/_section_style/after_section_end', [$this, 'section_tooltip'], 10);
            add_action('elementor/frontend/column/before_render', [$this, '_before_render'], 10, 1);
            add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );
            
    }

    public static function add_attributes( Element_Base $element ) {
        // bail if any other element but section
        if ( $element->get_name() !== 'section' ) return;
        // bail if editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if($settings['wpr_enable_jarallax']) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'jarallax',
                'speed-data' => $settings['speed'],
            ] );
			$element->add_render_attribute( '_wrapper .elementor-element', [
                'class' => 'jarallax-img',  
            ] );
            self::get_jarallax_script_depends();
        }
    }

    public function _before_render( $element ) {
        // bail if any other element but section
        if ( $element->get_name() !== 'section' ) return;
        // bail if editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if($settings['wpr_enable_jarallax']) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'jarallax',
                'speed-data' => $settings['speed'],
            ] );
            $element->add_render_attribute( '_wrapper .elementor-element', [
                'class' => 'jarallax-img',
            ] );
            self::get_jarallax_script_depends();
            var_dump($settings['wpr_enable_jarallax']);
            // var_dump($element);
        }
    }

    // TODO:: remove this if not necessary
	public static function get_jarallax_script_depends() {    	
		wp_enqueue_script(
			'wpr-jarallax',
			WPR_ADDONS_URL . 'assets/js/lib/jarallax/jarallax.min.js',
			[
				'jquery',
			],
			'3.0.6',
			true
		);

		wp_enqueue_script(
			'wpr-jarallax-video',
			WPR_ADDONS_URL . 'assets/js/lib/jarallax/jarallax-video.min.js',
			[
				'jquery',
			],
			'3.0.6',
			true
		);
	}

    public function section_parallax($element)
    {
        $element->start_controls_section(
            'wpr_section_parallax_section',
            [
                'label' => __('<i class=""></i> WPR_Parallax', 'wpr-addons-elementor'),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'wpr_parallax_test',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => $this->teaser_template([
                    'title' => __('Meet WPR Parallax', 'wpr-addons'),
                    'messages' => __('Create stunning Parallax effects.', 'wpr-addons'),
                ]),
            ]
        );

        
      $element->add_control(
            'wpr_enable_jarallax',
            [
            'type'  => Controls_Manager::SWITCHER,
            'label' => __('Enable Parallax', 'wpr-addons'),
            'default' => '',
            'label_on' => __('Yes', 'wpr-addons'),
            'label_off' => __('No', 'wpr-addons'),
            'return_value' => 'yes',
            'render_type' => 'template',
            ]
        );
        $element->add_control(
            'speed',
            [
                'label' => __( 'Speed', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 100,
                'step' => 0.1,
                'default' => 1,
            ]
        );

        $element->add_control(
			'scroll_effect',
			[
				'label' => __( 'Scroll Effect', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'zoom',
				'options' => [
					'zoom'  => __( 'Zoom', 'wpr-addons' ),
					'scroll' => __( 'Scroll', 'wpr-addons' ),
					'fade' => __( 'Fade', 'wpr-addons' ),
					'shade' => __( 'Shade', 'wpr-addons' ),
					'motion' => __( 'Motion', 'wpr-addons' ),
					'multi-Layered' => __( 'Multi-Layered', 'wpr-addons' ),
				],
			]
		);
        

        $element->end_controls_section();
    }

    public function teaser_template($texts)
    {
        $html = '<div class="ea-nerd-box">
            <div class="ea-nerd-box-icon">
                <img src="' . WPR_ADDONS_ASSETS_URL . '/img/icon-128x128.png' . '">
            </div>
            <div class="ea-nerd-box-title">' . $texts['title'] . '</div>
            <div class="ea-nerd-box-message">' . $texts['messages'] . '</div>
        </div>';

        return $html;
    }

}

$promotion = new Wpr_Parallax_Scroll();