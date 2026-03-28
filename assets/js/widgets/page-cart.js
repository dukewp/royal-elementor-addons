(function($) {
	"use strict";

	var handler = function($scope) {
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-page-cart.default', handler );
	});

})(jQuery);
