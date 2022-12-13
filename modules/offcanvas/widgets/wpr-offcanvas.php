<?php
namespace WprAddons\Modules\Offcanvas\Widgets;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Offcanvas extends Widget_Base {

	protected $nav_menu_index = 1;
	
	public function get_name() {
		return 'wpr-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Offcanvas', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-sidebar';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'offcanvas', 'off canvas', 'sidebar' ];
	}

	public function get_style_depends() {
		return [ 'wpr-link-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-nav-menu-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function wpr_offcanvas_template( $id ) {
		if ( empty( $id ) ) {
		return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'offcanvas_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'offcanvas_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				// 'condition' => [
				// 	'offcanvas_content_type' => 'template',
				// ],
			]
		);

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display(); ?>

        <button class="wpr-offcanvas-trigger">Click Here</button>

        <div class="wpr-offcanvas-cont">
            <div class="wpr-offcanvas">
                <?php
                    echo $this->wpr_offcanvas_template($settings['offcanvas_template']);
                ?>
            </div>
        </div>
        
    <?php }
}