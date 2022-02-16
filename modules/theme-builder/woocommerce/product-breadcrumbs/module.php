<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductBreadcrumbs;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-product-breadcrumbs';
	}

	public function get_widgets() {
		return [
			'Wpr_Product_Breadcrumbs', // This should match the widget/element class.
		];
	}
	
}