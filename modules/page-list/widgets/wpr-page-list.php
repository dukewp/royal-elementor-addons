<?php
namespace WprAddons\Modules\PageList\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Page_List extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-list';
	}

	public function get_title() {
		return esc_html__( 'Page List', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-editor-list-ul';
	}

	public function get_categories() {
		return [ 'wpr-widgets' ];
	}

	public function get_keywords() {
		return [ 'royal', 'page-list', 'list'];
	}

    protected function register_controls() {
    }

    public function render() {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        ); 

        $pages = get_pages($args); // get all pages based on supplied args
        
        foreach($pages as $page) { // $pages is array of object
            var_dump($page);
        }
    }
}