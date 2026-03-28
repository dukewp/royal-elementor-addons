(function($) {
	"use strict";

	var handler = function($scope) {

			var $imgHotspots = $scope.find( '.wpr-image-hotspots' ),
				hotspotsOptions = $imgHotspots.data('options'),
				$hotspotItem = $imgHotspots.find('.wpr-hotspot-item'),
				tooltipTrigger = hotspotsOptions.tooltipTrigger;

			if ( 'click' === tooltipTrigger ) {
				$hotspotItem.on( 'click', function() {
					if ( $(this).hasClass('wpr-tooltip-active') ) {
						$(this).removeClass('wpr-tooltip-active');
					} else {
						$hotspotItem.removeClass('wpr-tooltip-active');
						$(this).addClass('wpr-tooltip-active');
					}
					 event.stopPropagation();
				});

				$(window).on( 'click', function () {
					$hotspotItem.removeClass('wpr-tooltip-active');
				});
		   
			} else if ( 'hover' === tooltipTrigger ) {
				$hotspotItem.on( 'mouseenter', function () {
					$(this).addClass('wpr-tooltip-active');
				});
				
				$hotspotItem.on( 'mouseleave', function () {
					$(this).removeClass('wpr-tooltip-active');
				});

			} else {
				$hotspotItem.addClass('wpr-tooltip-active');
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-image-hotspots.default', handler );
	});

})(jQuery);
