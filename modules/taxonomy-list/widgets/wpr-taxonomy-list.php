<?php
namespace WprAddons\Modules\TaxonomyList\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;
use WprAddons\Classes\WPR_Post_Likes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Taxonomy_List extends Widget_Base {
	
	public function get_name() {
		return 'wpr-taxonomy-list';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy List', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-gallery-grid';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'taxonomy-list', 'taxonomy', 'category', 'categories', 'tag', 'list'];
	}

    protected function register_controls() {
        
    }
    protected function render() {

    }
}