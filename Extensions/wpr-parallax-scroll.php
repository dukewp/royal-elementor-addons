<?php
namespace WprAddons\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Controls_Stack;
use Elementor\Element_Base;

class Wpr_Parallax_Scroll {
    public function __construct()
    {
            add_action('elementor/element/section/section_layout/after_section_end', [$this, 'section_parallax'], 10);
            add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
            add_action('elementor/frontend/section/before_render', [$this, '_before_render'], 10, 1);
            add_action( 'wp_enqueue_scripts', [ $this, 'get_jarallax_script_depends' ] );
            
    }

    public function _before_render( $element ) {
        // bail if any other element but section
        if ( $element->get_name() !== 'section' ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if($settings['wpr_enable_jarallax']) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'jarallax',
                'speed-data' => $settings['speed'],
                'bg-image' => $settings['bg_image'],
                'scroll-effect' => $settings['scroll_effect'],
            ] );
            // $element->add_render_attribute( '_wrapper .elementor-element', [
            //     'class' => 'jarallax-img',
            // ] );
            // var_dump($settings['wpr_enable_jarallax']);
            // var_dump($element);
        }
    }

    public function _print_template( $template, $widget ) {
		ob_start();
        ?>
        <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: -100;">
        <!-- <div style="background-position: 50% 50%; background-size: cover; background-repeat: no-repeat; background-image: url(&quot;https://source.unsplash.com/random&quot;); position: fixed; top: 0px; left: 0px; width: 1205.6px; height: 625.4px; overflow: hidden; pointer-events: none; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity; margin-top: 48.3px; transform: translate3d(0px, -19.26px, 0px);"></div> -->
        </div>
        <?php
		$parallax_content = ob_get_contents();
		ob_end_clean();
		$template = $template . $parallax_content;
		return $template;
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
            'prefix_class' => 'jarallax-'
            ]
        );
        $element->add_control(
            'speed',
            [
                'label' => __( 'Speed', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 2 - 3,
                'max' => 2,
                'step' => 0.1,
                'default' => 1,
                'render_type' => 'template',
            ],
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
            'render_type' => 'template',

			]
		);

        $element->add_control(
			'bg_image',
			[
				'label' => __( 'Choose Image', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
                'render_type' => 'template',
			]
		);
        

        $element->end_controls_section();
    }

    public function teaser_template($texts)
    {
        $html = '<div class="">
            <div class="">
                <img src="' . WPR_ADDONS_ASSETS_URL . '/img/icon-128x128.png' . '">
            </div>
            <div class="">' . $texts['title'] . '</div>
            <div class="">' . $texts['messages'] . '</div>
        </div>';

        return $html;
    }

}

$parallax = new Wpr_Parallax_Scroll();