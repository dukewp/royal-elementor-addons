(function($) {
	"use strict";

	var handler = function($scope) {
			$scope.find('.wpr-sharing-print').on('click', function(e) {
				e.preventDefault();
				window.print();
			});
        }

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-sharing-buttons.default', handler );
	});

})(jQuery);
