<?php
namespace WprAddons\Modules\AdvancedAccordion\Widgets;

use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
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
use WprAddons\Includes\temporary\Widget_Area_Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Advanced_Accordion extends Widget_Base {
	
	public function get_name() {
		return 'wpr-advanced-accordion';
	}

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-toggle';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'blog', 'advanced accordion' ];
	}

	public function get_script_depends() {
		return [ '' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

    protected function register_controls() {
		
		$templates_select = [];

		// Get All Templates
		$templates = get_posts( [
			'post_type'   => array( 'elementor_library' ),
			'post_status' => array( 'publish' ),
			'meta_key' 	  => '_elementor_template_type',
			'meta_value'  => ['page', 'section'],
			'numberposts' => -1
		] );

		if ( ! empty( $templates ) ) {
			foreach ( $templates as $template ) {
				$templates_select[$template->ID] = $template->post_title;
			}
		}

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
        
        $repeater = new \Elementor\Repeater();
 
		$repeater->add_control(
			'accordion_content_type',
			[
				'label' => esc_html__( 'Content Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'template' => esc_html__( 'Widget', 'wpr-addons' )
				],
				'render_type' => 'template',
			]
		);

		$repeater->add_control(
			'accordion_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Acc Item Title' , 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'accordion_content', [
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Acc Item Content' , 'wpr-addons' ),
				'show_label' => false,
                'condition' => [
                    'accordion_content_type' => 'text'
                ]
			]
		);

		$repeater->add_control(
			'accordion_content_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $templates_select,
				'condition' => [
					'accordion_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'advanced_accordion',
			[
				'label' => esc_html__( 'Accordion Items', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'accordion_title' => esc_html__( 'Title #1', 'wpr-addons' ),
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #2', 'wpr-addons' ),
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #3', 'wpr-addons' ),
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					]
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

        $this->add_control(
            'accordion_type',
            [
                'label'       => esc_html__('Accordion Type', 'wpr-addons'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'label_block' => false,
                'options'     => [
                    'accordion' => esc_html__('Accordion', 'wpr-addons'),
                    'toggle'    => esc_html__('Toggle', 'wpr-addons'),
                ],
            ]
        );

		$this->end_controls_section();
    }


	public function wpr_accordion_template( $id ) {
		if ( empty( $id ) ) {
		return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id ) . $edit_link;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'accordion_attributes',
			[
				'class' => [ 'wpr-advanced-accordion' ],
				'data-accordion-type' => $settings['accordion_type'],
			]
		);

        ?>
            <div <?php echo $this->get_render_attribute_string( 'accordion_attributes' ); ?>>
                <?php foreach ($settings['advanced_accordion'] as $i=>$acc) : ?>
                    <button class="accordion">
						<span><?php echo $acc['accordion_title'] ?></span>
						<span><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
					</button>
                    <div class="panel">
					<?php if ('text' === $acc['accordion_content_type']) : ?>
                    	<p><?php echo $acc['accordion_content'] ?></p>
					<?php else: 
						echo $this->wpr_accordion_template( $acc['accordion_content_template'] );
						// echo Widget_Area_Utils::parse( '', $this->get_id(), $acc['_id'], '', $i+1 );
					endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php
    }
}