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

	public function get_script_depends() {
		return [ 'wpr-instafeed' ];
	}

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
				'raw'         => '<form onsubmit="connectInstagramInit(this);" action="javascript:void(0); class="wpr-facebook-login elementor-button" data-type="reviews"><input type="submit" value="Log in with Facebook" class="" style="background-color: #3b5998; color: #fff;"></form>',
				'label_block' => true,
            ]
		);

		$this->add_control(
			'instagram_feed_client_access_token',
			[
				'label'       => __( 'Access Token', 'premium-addons-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => ['active' => true ],
				'default'     => get_option('wpr_instagram_access_token'),
				'description' => 'Get your access token from <a href="#" target="_blank">here</a>',
				'label_block' => true
			]
		);
		
		if ( '' == get_option('wpr_instagram_access_token') ) {
			$this->add_control(
				'instagram_access_token_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Please enter <strong>Instagram Access Token</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

        $this->end_controls_section();
    }

    protected function render() {
		$settings = $this->get_settings_for_display();

		$access_token = $settings['instagram_feed_client_access_token'];

		$instagram_settings = array(
			'accesstok'   => $access_token,

		);

		$this->add_render_attribute(
			'instagram',
			[
				'class'         => 'wpr-instagram-feed-cont',
				'data-settings' => wp_json_encode( $instagram_settings ),
			]
		);

		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'instagram' ) ); ?>>

		<div id="instafeed" style="min-height: 1px;"></div>

		<?php
    }
}