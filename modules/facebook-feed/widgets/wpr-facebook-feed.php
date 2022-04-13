<?php
namespace WprAddons\Modules\FacebookFeed\Widgets;

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

class Wpr_Facebook_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-facebook-feed';
	}

	public function get_title() {
		return esc_html__( 'Facebook Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-facebook';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'facebook feed' ];
	}

	public function get_script_depends() {
		return [ 'wpr-instafeed' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

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
				'raw'         => '<form onsubmit="connectInstagramInit(this);" action="javascript:void(0); class="wpr-facebook-login elementor-button" data-type="reviews"><input type="submit" value="Log in with Facebook" style="background-color: #3b5998; color: #fff;"></form>',
				'label_block' => true,
            ]
		);
    }

    protected function render() {
		$settings = $this->get_settings_for_display();
        
        // need authorization
        // https://graph.facebook.com/v13.0/oauth/access_token?client_id=1184287221975469&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php&client_secret=6b25829937b4859194b0b47ab43241d7&code=<AUTHORIZATION_CODE>

        // needs page id
        // https://graph.facebook.com/oauth/EAAQ1Glstia0BAETXWF5GktlwTHVAKQdEiNpo4XXZAk2Qh778YYFZCjCHV3hxvcmV2HplIxweUK5jfJPHNXpMY2VoUHoZBzFMqgOO8sTazrViC88e4Hej7F3kklWorWYE2fO0L0H8sBW3nu98pWDEpjU0WVJ9BelfT0PTm7Y0gvTVNfjLm0sLne6xBPtfX7Dcr6DFZCV4IAZDZD?client_id=1184287221975469&client_secret=6b25829937b4859194b0b47ab43241d7&grant_type=client_credentials

        // retrieves metadata
        // https://graph.facebook.com/me?
        // metadata=1&access_token=EAAQ1Glstia0BAETXWF5GktlwTHVAKQdEiNpo4XXZAk2Qh778YYFZCjCHV3hxvcmV2HplIxweUK5jfJPHNXpMY2VoUHoZBzFMqgOO8sTazrViC88e4Hej7F3kklWorWYE2fO0L0H8sBW3nu98pWDEpjU0WVJ9BelfT0PTm7Y0gvTVNfjLm0sLne6xBPtfX7Dcr6DFZCV4IAZDZD

        echo '<div class="wpr-facebook-feed">';
        // $response_profile = wp_remote_get('https://graph.facebook.com/me?fields=id,name,email,picture&access_token=EAAQ1Glstia0BAETXWF5GktlwTHVAKQdEiNpo4XXZAk2Qh778YYFZCjCHV3hxvcmV2HplIxweUK5jfJPHNXpMY2VoUHoZBzFMqgOO8sTazrViC88e4Hej7F3kklWorWYE2fO0L0H8sBW3nu98pWDEpjU0WVJ9BelfT0PTm7Y0gvTVNfjLm0sLne6xBPtfX7Dcr6DFZCV4IAZDZD');

        // $response_photos = wp_remote_get("https://graph.facebook.com/v13.0/me/photos?access_token=EAAQ1Glstia0BAETXWF5GktlwTHVAKQdEiNpo4XXZAk2Qh778YYFZCjCHV3hxvcmV2HplIxweUK5jfJPHNXpMY2VoUHoZBzFMqgOO8sTazrViC88e4Hej7F3kklWorWYE2fO0L0H8sBW3nu98pWDEpjU0WVJ9BelfT0PTm7Y0gvTVNfjLm0sLne6xBPtfX7Dcr6DFZCV4IAZDZD");
        
        echo '<a target="_blank" class="wpr-login-to-facebook" href="https://www.facebook.com/v13.0/dialog/oauth?client_id=1184287221975469&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php?state={st=state123abc,ds=123456789}&state={st=state123abc,ds=123456789}">Log In</a>';
        
        // $response_body = json_decode(wp_remote_retrieve_body( $response_photos ));
        // var_dump($response_body);
        echo '<div>';

    }
}