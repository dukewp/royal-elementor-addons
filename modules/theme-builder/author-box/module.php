<?php
namespace WprAddons\Modules\ThemeBuilder\AuthorBox;

use WprAddons\Base\Module_Base;
use wpraddons\Classes\Utilities;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-author-box';
	}

	public function get_widgets() {
		return Utilities::show_theme_buider_widget_on('single') || Utilities::show_theme_buider_widget_on('archive') ? ['Wpr_Author_Box'] : []; // This should match the widget/element class.
	}
	
}