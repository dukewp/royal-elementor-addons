(function($) {
	"use strict";

	var handler = function($scope) {
			if ( 0 !== $scope.find('.wpr-search-form-input').length ) {
				$scope.find('.wpr-search-form-input').on( {
					focus: function() {
						$scope.addClass( 'wpr-search-form-input-focus' );
					},
					blur: function() {
						$scope.removeClass( 'wpr-search-form-input-focus' );
					}
				} );
			}
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-product-filters.default', handler );
	});

})(jQuery);
