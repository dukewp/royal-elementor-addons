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

	public function add_option_element_select() {
		return [
			'username' => esc_html__( 'Username', 'wpr-addons' ),
			'profile_name' => esc_html__( 'Profile Name', 'wpr-addons' ),
			'profile_picture' => esc_html__( 'Profile Picture', 'wpr-addons' ),
			'twit' => esc_html__( 'Twit', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'comment' => esc_html__( 'Comment', 'wpr-addons' ),
			'likes' => esc_html__( 'Likes', 'wpr-addons' ),
			'retweets' => esc_html__( 'Retweets', 'wpr-addons' ),
			'media' => esc_html__( 'Media', 'wpr-addons' ),
		];
	}

	// Render User Name
	public function render_post_username( $settings, $class, $item ) {
		echo '<'. esc_attr($settings['element_username_tag']) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
                echo '<strong>'. $item['user']['name'] .'</strong>';
			echo '</div>';
		echo '</'. esc_attr($settings['element_username_tag']) .'>';
	}

	// Render User Account
	public function render_post_user_profile_name( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
            echo '<small>';
                echo '<a href="https://twitter.com/' . $item['user']['screen_name'] .'">@'. $item['user']['screen_name'] .'</a>';
            echo '</small>';
			echo '</div>';
		echo '</div>';
	}

	// Render User Account
	public function render_post_user_profile_picture( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
            ?>
            <figure class="image is-64x64">
                <img src="<?php echo $item['user']['profile_image_url'] ?>" alt="Image">
            </figure>
            <?php
			echo '</div>';
		echo '</div>';
	}

	public function render_post_caption($settings, $class, $item) {

		if ( !isset($item['full_text']) || '' === $item['full_text'] ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
                echo '<p>';
                    $string = preg_replace("~[[:alpha:]]+://[^<p>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $item['full_text']);
                    $new_string = preg_replace('/\#([a-z0-9]+)/i', '<a href="https://twitter.com/hashtag/$1?src=hashtag_click">#$1</a>', $string);
                    echo preg_replace('/\@([a-z0-9]+)/i', '<a href="https://twitter.com/$1">@$1</a>' ,$new_string);
                echo '</p>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $item) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}
                // echo wp_date(get_option( 'date_format' ), strtotime($item['created_at'])) 
                echo human_time_diff(strtotime($item['created_at'])) .' '. esc_html__('ago');

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_likes($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/like?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Likes">
                <span class="icon small"><i class="fas fa-heart"></i></span>
                <span class="likes">
                    <?php echo $item['favorite_count'] ?>
                </span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	public function render_post_comments($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Comments">
                <span class="icon small"><i class="fas fa-comment"></i></span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	public function render_post_retweets($settings, $class, $item) {

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 

            ?>
            <a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $item['id'] ?>&related=<?php echo $item['user']['screen_name'] ?>" target="_blank" title="Retweets">
                <span class="icon small"><i class="fa fa-retweet"></i></span>
                <span class="retweets">
                    <?php echo $item['retweet_count'] ?>
                </span>
            </a>
            <?php

			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="wpr-insta-feed-sep-style-1 '. esc_attr($class) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	public function render_post_media($settings, $class, $item) {

        if ( $item['extended_entities']['media'] ) {
            $media = $item['extended_entities']['media'];
        } else if ( isset( $item['retweeted_status']['entities']['media'] ) ) {
            $media = $item['retweeted_status']['entities']['media'];
        } else if ( isset( $item['quoted_status']['entities']['media'] ) ) {
            $media = $item['quoted_status']['entities']['media'];
        } else {
            $media = [];
        }

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">'; 
                // && $media[0]['type'] == 'photo'
                echo (isset( $media[0] )) ? '<img class="wpr-twit-image" src="' . $media[0]['media_url_https'] . '">' : '';
			echo '</div>';
		echo '</div>';
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $item ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
	

			case 'username':
				$this->render_post_username( $settings, $class, $item );
				break;

			case 'profile_name':
				$this->render_post_user_profile_name( $settings, $class, $item );
				break;

			case 'twit':
				$this->render_post_caption( $settings, $class, $item );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $item );
				break;

            case 'likes':
            	$this->render_post_likes( $settings, $class, $item );
            	break;

			case 'comments':
				$this->render_post_comments( $settings, $class, $item );
				break;

            case 'retweets':
            	$this->render_post_retweets( $settings, $class, $item );
            	break;

            case 'media':
            	$this->render_post_media( $settings, $class, $item );
            	break;
                
            case 'profile_picture':
            	$this->render_post_user_profile_picture( $settings, $class, $item );
            	break;

			// case 'read-more':
			// 	$this->render_post_read_more( $settings, $class );
			// 	break;

			// case 'lightbox':
			// 	$this->render_post_lightbox( $settings, $class, $item );
			// 	break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $item ) {
		$locations = [];

		foreach ( $settings['twitter_feed_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $thiss ) {

					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-insta-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'wpr-insta-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
							$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $item );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="wpr-insta-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'wpr-insta-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
						$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $item );
					}
				echo '</div>';
			}

		}
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
						'twitter_feed_account_name' => '@NASA',
                    ],
					[
						'twitter_feed_account_name' => '@EITCI',
                    ],
				],
				'title_field' => '{{{ twitter_feed_account_name }}}',
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
			'number_of_posts',
			[
				'label' => esc_html__( 'Items Per Page', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 0,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => esc_html__('Columns', 'wpr-addons'),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'options'            => [
					'1' => esc_html__('One', 'wpr-addons'),
					'2'    => esc_html__('Two', 'wpr-addons'),
					'3'    => esc_html__('Three', 'wpr-addons'),
					'4'    => esc_html__('Four', 'wpr-addons'),
					'5'    => esc_html__('Five', 'wpr-addons'),
					'6'    => esc_html__('Six', 'wpr-addons'),
					'7'    => esc_html__('Seven', 'wpr-addons'),
					'8'    => esc_html__('Eight', 'wpr-addons'),
					'9'    => esc_html__('Nine', 'wpr-addons'),
					'10'    => esc_html__('Ten', 'wpr-addons'),
					'11'    => esc_html__('Eleven', 'wpr-addons'),
					'12'    => esc_html__('Twelve', 'wpr-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-twitter-feed' => 'column-count: {{VALUE}}',
				],
				'frontend_available' => true,
				'render_type' => 'template'
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

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_feed_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this_select,
				'default' => 'caption',
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'twitter-feed', 'element_select', ['pro-lk', 'pro-shr'] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'wpr-addons' ),
					'over' => esc_html__( 'Over Media', 'wpr-addons' ),
					'below' => esc_html__( 'Below Media', 'wpr-addons' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Seperate Line', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Width', 'wpr-addons' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-twitter-feed-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'element_location' => 'over',
					],
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_username_tag',
			[
				'label' => esc_html__( 'Username HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'element_select' => 'username',
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'caption' ],
				]
			]
		);

		$repeater->add_control(
			'element_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'element_select' => [ 'username' ]
				]
			]
		);

		$repeater->add_control(
			'element_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 16,
				'min' => 8,
				'condition' => [
					'element_select' => [ 'username' ],
					'element_show_avatar' => 'yes'
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_tax_sep',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => ', ',
				'condition' => [
					'element_select!' => [
						'caption',
						'date',
						'username',
						'sharing',
						'lightbox',
						'separator',
					],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control(
		// 	'element_comments_text_1',
		// 	[
		// 		'label' => esc_html__( 'No Comments', 'wpr-addons' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => 'No Comments',
		// 		'condition' => [
		// 			'element_select' => [ 'comments' ],
		// 		]
		// 	]
		// );

		$repeater->add_control(
			'element_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Comment',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		// $repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		// $repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		// $repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		// $repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		// $repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		// $repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		// $repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		// $repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		// $repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		// $repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		// $repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		// $repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		// $repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_pfa_select',
			[
				'label' => esc_html__( 'Post Format Audio', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_pfv_select',
			[
				'label' => esc_html__( 'Post Format Video', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'meta' => esc_html__( 'Meta Value', 'wpr-addons' ),
				],
				'condition' => [
					'element_select' => 'lightbox',
				],
			]
		);

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'twitter-feed', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'twitter-feed', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'twitter_feed_elements',
			[
				'label' => esc_html__( 'Feed Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'profile_picture',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'left'
					],
					[
						'element_select' => 'username',
						'element_location' => 'above',
						'element_display' => 'inline',
						'element_align_hr' => 'right'
					],
					[
						'element_select' => 'profile_name',
						'element_location' => 'above',
						// 'element_display' => 'inline',
						// 'element_align_hr' => 'right'
					],
					[
						'element_select' => 'date',
						// 'element_display' => 'inline',
						// 'element_extra_text_pos' => 'after',
						// 'element_extra_text' => '/',
						'element_location' => 'above',
						// 'element_align_hr' => 'left'
					],
					[
						'element_select' => 'twit',
						'element_location' => 'above',
					],
					[
						'element_select' => 'comments',
						'element_location' => 'above',
					],
					[
						'element_select' => 'likes',
						'element_location' => 'above',
					],
					[
						'element_select' => 'retweets',
						'element_location' => 'above',
					],
					[
						'element_select' => 'media',
						'element_location' => 'above',
					],
					[
						'element_select' => 'separator',
						'element_location' => 'above',
					],
				],
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

        $this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'background-color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-twitter-follow-btn',
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
					'{{WRAPPER}} .wpr-twitter-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-twitter-follow-btn:hover' => 'background-color: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-twitter-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-twitter-follow-btn:hover',
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
				'selector' => '{{WRAPPER}} .wpr-twitter-follow-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_distance_from_feed',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-if-cfb-top .wpr-twitter-follow-btn-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-if-cfb-bottom .wpr-twitter-follow-btn-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-twitter-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'follow_button_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-twitter-follow-btn-wrap' => 'text-align: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

    public function round_down($number, $decimals) {
        $decimals = $decimals || 0;
        return (floor($number * pow(10, $decimals)) / pow(10, $decimals));
    }

    //Format numbers as per Twitter formatting.
    public function format_numbers($num) {
        if ($num >= 1000000000) {
            $num = $this->round_down(($num / 1000000000), 1) . 'G';
            return $num;
        }
        if ($num >= 1000000) {
            $num = $this->round_down(($num / 1000000), 1) . 'M';
            return $num;
        }
        if ($num >= 10000) {
            $num = $this->round_down(($num / 1000), 1) . 'K';
            return $num;
        }
        return $num;
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
            $response[$key] = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $value['twitter_feed_account_name'] . '&count='. $settings['number_of_posts'] .'&tweet_mode=extended', [
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
            }
        }

        $header_banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
        $header_banner = $items_array[0][0]['user']['profile_banner_url'] ? $items_array[0][0]['user']['profile_banner_url'] : $header_banner_placeholder;

        // var_dump($items_array[0][0]['user']);

        
        echo '<div class="wpr-twitter-feed-header">';
            echo '<img src="'. $header_banner .'" >'; ?>

            <div class="wpr-tf-statistics">
                <span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['statuses_count']) ?></span><span> Tweets</span></a></span>
                <span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>/following' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['friends_count']) ?></span><span> Following</span></a></span>
                <span class=""><a href='https://twitter.com/<?php echo $items_array[0][0]['user']['screen_name'] ?>/followers' target="_blank"><span><?php echo $this->format_numbers($items_array[0][0]['user']['followers_count']) ?></span> <span>Followers</span></a></span>
            </div>

            <span class="wpr-twitter-follow-btn-wrap">
                <a class="wpr-twitter-follow-btn" rel="nofollow" href="https://twitter.com/intent/follow?screen_name=<?php echo $items_array[0][0]['user']['screen_name'] ?>" target="_blank">
                    Follow
                </a>
            </span>

        <?php echo '</div>';

        echo '<div class="wpr-twitter-feed">';

        foreach ( $items_array as $key=>$items ) :
        $i = 0;
        foreach ( $items as $item) :

                
        $banner_placeholder = WPR_ADDONS_ASSETS_URL . 'img/placeholder.png';
        $banner = $item['user']['profile_banner_url'] ? $item['user']['profile_banner_url'] : $banner_placeholder;
        ?>
                <div class="wpr-tweet">
                        <img src="<?php echo $banner ?>" alt="">
                        <article class="media">

                            <?php 
                                // Content: Above Media
                                echo $this->get_elements_by_location( 'above', $settings, $item );
                            ?>
                            
                        </article>
                </div>
        <?php

        endforeach;
        endforeach;
        echo '</div>';
    }
}