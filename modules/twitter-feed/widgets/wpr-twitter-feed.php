<?php
namespace WprAddons\Modules\TwitterFeed\Widgets;

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

class Wpr_Twitter_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-twitter-feed';
	}

	public function get_title() {
		return esc_html__( 'Twitter Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-twitter-feed';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'twitter feed' ];
	}

	// public function get_script_depends() {
	// 	return [ 'wpr-instafeed', 'wpr-lightgallery' ];
	// }

	// public function get_style_depends() {
	// 	return ['wpr-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css'];
	// }

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {
        $this->start_controls_section(
            'section_twitter_feeds_settings',
            [
                'label' => esc_html__('Account Settings', 'wpr-addons'),
            ]
        );

		$repeater = new Repeater();


        $repeater->add_control(
            'twitter_feed_account_name',
            [
                'label' => esc_html__('Account Name', 'wpr-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '@giorgi_karchava',
                'label_block' => false,
                'description' => esc_html__('Use @ sign with your account name.', 'wpr-addons'),
            ]
        );

		$this->add_control(
			'twitter_accounts',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'twitter_feed_account_name' => '@giorgi_karchava',
					],
					[
						'twitter_feed_account_name' => '@cufotheme',
					]
				],
				'title_field' => '<i class="{{{ twitter_feed_account_name }}}"></i> Account Name',
			]
		);

		if ( Utilities::is_new_free_user() ) {
			$this->add_control(
				'sharing_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Buttons are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-sharing-buttons-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

        $this->add_control(
            'twitter_feed_hashtag_name',
            [
                'label' => esc_html__('Hashtag Name', 'wpr-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'description' => esc_html__('Remove # sign from your hashtag name.', 'wpr-addons'),
            ]
        );

        $this->add_control(
            'twitter_feed_consumer_key',
            [
                'label' => esc_html__('Consumer Key', 'wpr-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => 'sOmRR0YaKwg0eyv4hIiEsu5pH',
                'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
            ]
        );

        $this->add_control(
            'twitter_feed_consumer_secret',
            [
                'label' => esc_html__('Consumer Secret', 'wpr-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => 'Gi4doRTttrEWUuGgjfWTPb0pkxRroBOijN6iNJHtNn2N8uEb47',
                'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
            ]
        );

        $this->add_control(
            'auto_clear_cache',
            [
                'label'        => esc_html__( 'Auto Cache Clear', 'wpr-addons' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wpr-addons' ),
                'label_off'    => __( 'No', 'wpr-addons' ),
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'twitter_feed_cache_limit',
            [
                'label'       => __( 'Data Cache Time', 'wpr-addons' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => 60,
                'description' => __( 'Cache expiration time (Minutes)', 'wpr-addons' ),
                'condition'   => [
                    'eael_auto_clear_cache' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'clear_cache_control',
            [
                'label'       => __( 'Clear Cache', 'wpr-addons' ),
                'type'        => Controls_Manager::BUTTON,
                'text'        => __( 'Clear', 'wpr-addons' ),
                'event'       => 'ea:cache:clear',
                'description' => esc_html__( 'Note: This will refresh your feed and fetch the latest data from your Twitter account', 'wpr-addons' ),
                'condition'   => [
                    'eael_auto_clear_cache' => ''
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($token)) {
            $credentials = base64_encode($settings['twitter_feed_consumer_key'] . ':' . $settings['twitter_feed_consumer_secret']);

            add_filter('https_ssl_verify', '__return_false');

            $response = wp_remote_post('https://api.twitter.com/oauth2/token', [
                'method' => 'POST',
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => 'Basic ' . $credentials,
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                ],
                'body' => ['grant_type' => 'client_credentials'],
            ]);

            $body = json_decode(wp_remote_retrieve_body($response));

            if ($body) {
                // update_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token', $body->access_token);
                $token = $body->access_token;
            }
        }

        add_filter('https_ssl_verify', '__return_false');

        $response = [];
        $items_array = [];

        foreach ($settings['twitter_accounts'] as $key=>$value) {
            $response[$key] = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $value['twitter_feed_account_name'] . '&count=999&tweet_mode=extended', [
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ]);

            if ( is_wp_error( $response[$key] ) ) {
                // return $html;
            }
    
            if ( ! empty( $response[$key]['response'] ) && $response[$key]['response']['code'] == 200 ) {
                $items_array[] = json_decode( wp_remote_retrieve_body( $response[$key] ), true );
                // set_transient( $cache_key, $items, $expiration );
                var_dump($items_array);
            }
        }

        echo '<div class="wpr-twitter-feed">';
        $banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
        foreach ( $items_array as $key=>$items ) :
        foreach ( $items as $item) :
        $banner = $item['user']['profile_banner_url'] ? $item['user']['profile_banner_url'] : $banner_placeholder;
        ?>
            <div class="wpr-tf-columns">
                <div class="wpr-tf-column"><div class="tweet">
                    <div class="box">
                        <img src="<?php echo $banner ?>" alt="">
                        <article class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                            <img src="<?php echo $item['user']['profile_image_url'] ?>" alt="Image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                            <p>
                                <strong><?php echo $item['user']['name'] ?></strong> <small><a href="<?php echo 'https://twitter.com/' . $item['user']['screen_name'] ?>">@<?php echo $item['user']['screen_name'] ?></a></small>
                                <br>
                                <p><?php echo $item['full_text'] ?></p>
                                <p><?php echo $item['created_at'] ?></p>
                                <p><?php echo strtotime($item['created_at']) ?></p>
                                <p><?php echo wp_date(get_option( 'date_format' ), strtotime($item['created_at'])) ?></p>
                            </p>
                            </div>
                            <div class="level-left">
                                <a class="level-item">
                                <span class="icon small"><i class="fas fa-heart"></i></span>
                                <span class="likes">
                                <?php echo $item['favorite_count'] ?></span>
                                </a>
                            </div>
                        </div>
                        </article>
                    </div>
                    </div> 
                </div>
            </div>
        <?php
        endforeach;
        endforeach;
        echo '</div>';
    }
}