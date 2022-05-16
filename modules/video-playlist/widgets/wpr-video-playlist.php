<?php
namespace WprAddons\Modules\VideoPlaylist\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Wpr_Video_Playlist extends Widget_Base {
    public function get_name() {
        return 'wpr-video-playlist';
    }

    public function get_title() {
        return esc_html__( 'Video Playlist', 'wpr-addons' );
    }

    public function get_icon() {
        return 'wpr-icon eicon-video-playlist';
    }

    public function get_categories() {
        return [ 'wpr-widgets'];
    }

    public function get_keywords() {
        return [ 'royal', 'video playlist', 'video gallery' ];
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
            'section_playlist_general',
            [
                'label' => esc_html__( 'General', 'wpr-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

        $repeater = new Repeater();

		$repeater->add_control(
			'playlist_item_title',
			[
				'label'  	=> esc_html__( 'Title', 'wpr-addons' ),
				'type'   	=> Controls_Manager::TEXT,
				'default' => 'Playlist Title',
			]
		);

		$repeater->add_control( 
            'playlist_item_link_type',
            [
                'label' => esc_html__( 'Link Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'custom' => esc_html__( 'Custom URL', 'wpr-addons' ),
                    'video-youtube'  => esc_html__( 'Youtube', 'wpr-addons' ),
                    'video-vimeo'  => esc_html__( 'Vimeo', 'wpr-addons' ),
                ],
                'separator' => 'before'
            ]
        );

		$repeater->add_control(
			'playlist_item_video_src',
			[
				'label' => esc_html__( 'Video URL', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'playlist_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'playlist_item_video_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'playlist_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'playlist_item_video_mute',
			[
				'label' => esc_html__( 'Mute', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'playlist_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'playlist_item_video_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'playlist_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);
        
        $this->add_control(
			'playlist_items',
			[
				'label' => esc_html__( 'Repeater List', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'playlist_item_title' => esc_html__( 'Title #1', 'wpr-addons' ),
					],
					[
						'playlist_item_title' => esc_html__( 'Title #2', 'wpr-addons' ),
					],
				],
				'title_field' => '{{{ playlist_item_title }}}',
			]
		);

        $this->end_controls_section();

    }

    public function get_string_between($string , $start , $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    protected function render() { // Get Settings
        $settings = $this->get_settings_for_display(); 
		$item_count = 0;

		if ( empty( $settings['playlist_items'] ) ) {
			return;
		} ?>

        
        <div class="wpr-video-playlist-wrap">
            <div class="wpr-video-playlist-item-active"></div>
            <ul class="wpr-video-playlist-items">
		
            <?php foreach ( $settings['playlist_items'] as $key => $item ) {
                $item_type = $item['playlist_item_link_type'];
                $item_video_src = $item['playlist_item_video_src'];
                $active_class = $item_count == 0 ? 'wpr-video-active' : '';
                
                $fullstring = $item_video_src;

                if ('video-youtube' === $item_type) {
                    $youtube_video_id = $this->get_string_between($fullstring, 'v=', '&');
                    $item_thumbnail = 'https://img.youtube.com/vi/'. $youtube_video_id .'/default.jpg';
                } elseif ('video-vimeo' === $item_type) {
                    $item_thumbnail = '';
                }
                ?>
                <li class="wpr-video-playlist-item <?php echo esc_attr($active_class)?>"><img src="<?php echo esc_attr($item_thumbnail) ?>"><a href="<?php echo esc_url($item_video_src) ?>" class="gallery-item-link"><?php echo esc_html($item['playlist_item_title']) ?></a></li>
            <?php $item_count++;
            }

        ?>
            </ul>
        </div>
        <?php
    }

}