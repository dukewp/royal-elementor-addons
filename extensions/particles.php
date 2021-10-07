<?php
namespace WprAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;

class Wpr_Particles {

	private static $_instance = null;

	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public $array_of_particles =  [
			'default' => '{"particles":{"number":{"value":83,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',

			'snow' => '{"particles":{"number":{"value":400,"density":{"enable":true,"value_area":800}},"color":{"value":"#fff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":10,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":500,"color":"#ffffff","opacity":0.4,"width":2},"move":{"enable":true,"speed":6,"direction":"bottom","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":0.5}},"bubble":{"distance":400,"size":4,"duration":0.3,"opacity":1,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',

			'bubble' => '{"particles":{"number":{"value":6,"density":{"enable":true,"value_area":800}},"color":{"value":"#1b1e34"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":160,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',

			'pentagon' => '{"particles":{"number":{"value":38,"density":{"enable":true,"value_area":800}},"color":{"value":"#5e73b9"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":39.459250432078804,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',

			'nasa' => '{"particles":{"number":{"value":160,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":2,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',

		];

	public function custom_json_particles($array, $element) {

		return $element->add_responsive_control(
			'wpr_particle_json_custom',
			[
				'type'        => Controls_Manager::CODE,
				'label'       => esc_html__( 'Add Particle Json', 'wpr-addons' ),
				'default'     => $array,
				'render_type' => 'template',
				'condition'   => [
						'which_particle' => 'wpr_particle_json_custom',
						'wpr_enable_particles' => 'yes'
				],
			]
		);
	}

	public function register_controls( $element, $section_id, $args ) {

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) ) {

			$element->start_controls_section(
				'wpr_particles',
				[
					'tab'   => Controls_Manager::TAB_STYLE,
					'label' => esc_html__( 'WPR - Particles', 'wpr-addons' ),
				]
			);
			$element->add_control(
				'wpr_enable_particles',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        => esc_html__( 'Enable Particle Background', 'wpr-addons' ),
					'default'      => '',
					'return_value' => 'yes',
					'prefix_class' => 'wpr-particle-',
					'render_type'  => 'template',
				]
			);

			$element->add_control(
				'which_particle',
				[
					'label' => __( 'Particles Selection', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'wpr_particle_json_custom'  => __( 'Custom JSON', 'plugin-domain' ),
						'wpr_particle_json' => __( 'Predefined Styles', 'plugin-domain' ),
					],
					'condition' => [
						'wpr_enable_particles' => 'yes'
					]
				]
			);

			$this->custom_json_particles($this->array_of_particles['snow'], $element);

			$element->add_responsive_control(
				'wpr_particle_json',
				[
					'label' => __( 'Particle Effect', 'plugin-domain' ),
					'type' => Controls_Manager::SELECT,
					'default' => $this->array_of_particles['default'],
					'widescreen_default' => $this->array_of_particles['snow'],
					'laptop_default' => $this->array_of_particles['snow'],
					'tablet_extra_default' => $this->array_of_particles['snow'],
					'tablet_default' => $this->array_of_particles['snow'],
					'mobile_extra_default' => $this->array_of_particles['pentagon'],
					'mobile_default' => $this->array_of_particles['pentagon'],
					'options' => [
						$this->array_of_particles['default']  => esc_html__( 'Default', 'plugin-domain' ),

						$this->array_of_particles['snow'] => esc_html__( 'Snow', 'plugin-domain' ),

						 $this->array_of_particles['bubble'] => esc_html__( 'Bubble', 'plugin-domain' ),

						 $this->array_of_particles['pentagon'] => esc_html__('Pentagon', 'wpr-addons'),

						 $this->array_of_particles['nasa'] => esc_html__('Nasa', 'wpr-addons'),
					],
					'condition'   => [
							'which_particle' => 'wpr_particle_json',
							'wpr_enable_particles' => 'yes'
					],
				]
			);


            $element->end_controls_section();
        }
    }


	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' ) {
			return $template;
		}
	
		$old_template = $template;
		ob_start();

		echo '<div class="wpr-particle-wrapper" id="wpr-particle-{{ view.getID() }}" data-wpr-particles-editor="{{ settings[settings.which_particle] }}"></div>';

		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' ) {
			return;
		}

		$settings = $element->get_settings();

		if ( $settings['wpr_enable_particles'] === 'yes' ) {
			$element->add_render_attribute( '_wrapper', 'data-wpr-particles', $settings[$settings['which_particle']] );
		}
	}

    public function enqueue_scripts() {
		wp_enqueue_script( 'wpr-particles', WPR_ADDONS_URL . 'assets/js/lib/particles/particles.js', [ 'jquery' ], '3.0.6', true );
	}

}

$particles = new Wpr_Particles();