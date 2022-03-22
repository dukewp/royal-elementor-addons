<?php
namespace WprAddons\Modules\ThemeBuilder\PostTitle;

use WprAddons\Base\Module_Base;
use wpraddons\Classes\Utilities;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-title';
	}

	public function get_widgets() {
		return Utilities::show_theme_buider_widget_on('single') ? ['Wpr_Post_Title'] : []; // This should match the widget/element class.
	}
	
}