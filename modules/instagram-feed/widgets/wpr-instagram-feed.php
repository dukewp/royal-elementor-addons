<?php
namespace WprAddons\Modules\InstagramFeed\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Instagram_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-instagram-feed';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-instagram-post';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'instagram feed' ];
	}

	// public function get_script_depends() {
	// 	return [ 'wpr-isotope', 'wpr-slick', 'wpr-lightgallery' ];
	// }

	// public function get_style_depends() {
	// 	return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	// }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ------------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );


		$this->add_control(
			'instagram_login',
			[
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<form class="wpr-facebook-login elementor-button" data-type="reviews"><input type="submit" value="Log in with Facebook" class="" style="background-color: #3b5998; color: #fff;"></form>',
				'label_block' => true,
				// 'condition'   => [
				// 	'api_version' => 'new',
                // ],
            ]
		);

        $this->end_controls_section();
    }

    protected function render() {

    }

    // onsubmit="connectInstagramInit(this);"
    //  action="javascript:void(0);"
}