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

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
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

		$this->add_control(
			'show_instagram_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'instagram_follow_text',
			[
				'label' => esc_html__( 'Follow Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Follow on Instagram',
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_link',
			[
				'label' => esc_html__( 'Follow Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'100%' => esc_html__( 'One', 'wpr-addons' ),
					'50%' => esc_html__( 'Two', 'wpr-addons' ),
					'33.3%' => esc_html__( 'Three', 'wpr-addons' ),
					'25%' => esc_html__( 'Four', 'wpr-addons' ),
					'20%' => esc_html__( 'Five', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-feed figure' => 'width: {{VALUE}}',
					'{{WRAPPER}} .wpr-instagram-feed video' => 'width: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'distance_bottom',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-feed' => 'margin-bottom: {{SIZE}}px',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Styles ===============
		// Section: Feed -----------
		$this->start_controls_section(
			'section_style_feed',
			[
				'label' => esc_html__( 'Feed', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public static function call_instagram_api($access_token) {

		$url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,username,caption,timestamp&access_token=' . $access_token;
		$response = wp_remote_get($url);
		$body = json_decode($response['body']);
		if(!isset($body)) {
			return $response['body'];
		}
		return $body->data;	
	}

	public function refresh_access_token($access_token) {
		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$access_token.'';
		$response = wp_remote_get($url);
		$body = json_decode($response['body']);
		if(!isset($body)) {
			var_dump($response['body']);
			return $response['body'];
		}
		var_dump($body->data);
		// update_option('wpr_instagram_access_token')
		return $body->data;	
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		$access_token = get_option('wpr_instagram_access_token');

		$instagram_settings = [
			'data-col' => 4
		];

		$this->add_render_attribute(
			'instagram',
			[
				'class'         => 'wpr-instagram-feed',
				'data-settings' => wp_json_encode( $instagram_settings ),
			]
		);
		if ( ! empty( $settings['instagram_follow_link']['url'] ) ) {
			$this->add_link_attributes( 'instagram_follow_link', $settings['instagram_follow_link'] );
		}

		// var_dump($this->call_instagram_api($access_token));

		$token_expires_in = get_option('wpr_instagram_access_token_expires_in');

		$compare_date = strtotime('-'.get_option('wpr_instagram_access_token_expires_in').' seconds');

		$token_generation_date = strtotime(get_option('wpr_instagram_access_token_generation_date'));


		if ( $token_generation_date <= $compare_date  ) {
			var_dump($compare_date);
			// $this->refresh_access_token($access_token);
		}

		?>

			<div class="wpr-instagram-feed-cont">
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'instagram' ) ); ?>>
				
					<?php foreach($this->call_instagram_api($access_token) as $result) : ?>
				
					<?php if ( $result->media_type === 'IMAGE') : ?>
					<figure>
						<img src=<?php echo $result->media_url  ?> alt="">
						<figcaption><?php echo $result->caption ?></figcaption>
					</figure>
					<?php else : ?>
						<video controls>
						<source src=<?php echo $result->media_url ?> type="video/mp4">
						</video>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
				
				<?php if ( 'yes' === $settings['show_instagram_follow_button'] ) : ?>
					<div>
						<a class="wpr-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>><?php echo $settings['instagram_follow_text'] ?></a>
					</div>
				<?php endif; ?>
			</div>

		<?php
    }
}