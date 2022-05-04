<?php
namespace WprAddons\Modules\AdvancedAccordion\Widgets;

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

		// Tab: Content ==============
		// Section: Query ------------
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
					'widgets' => esc_html__( 'Widget', 'wpr-addons' )
				],
				'render_type' => 'template',
			]
		);

		$repeater->add_control(
			'accordion_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Acc Item Title' , 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'accordion_content', [
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Acc Item Content' , 'wpr-addons' ),
				'show_label' => false,
                'condition' => [
                    'accordion_content_type' => 'text'
                ]
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
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
            <div class="wpr-advanced-accordion">
                <?php foreach ($settings['advanced_accordion'] as $i=>$acc) : ?>
                    <button class="accordion"><?php echo $acc['accordion_title'] ?></button>
                    <div class="panel">
                    <p><?php echo $acc['accordion_content'] ?></p>
                    </div>
                    
                    <?php echo Widget_Area_Utils::parse( '', $this->get_id(), $acc['_id'], '', $i+1 ); ?>
                <?php endforeach; ?>
            </div>
        <?php
    }
}