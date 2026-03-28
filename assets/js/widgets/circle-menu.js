(function($) {
	"use strict";

	var handler = function($scope) {
			var $wrapper = $scope.find( '.wpr-circle-menu-wrapper' ),
				settings = $wrapper.data( 'settings' );

			if ( ! settings ) {
				return;
			}

			$wrapper.find( '.wpr-circle-menu-box' ).circleMenu({
				direction: settings.menuDirection,
				item_diameter: settings.menuDiameter.size,
				circle_radius: settings.menuRadius.size,
				speed: settings.menuSpeed.size,
				delay: settings.menuDelay.size,
				step_out: settings.menuStepOut.size,
				step_in: settings.menuStepIn.size,
				trigger: settings.menuTrigger,
				transition_function: settings.menuTransition
			});

			$wrapper.find( '.wpr-circle-menu-box li' ).css( 'visibility', 'visible' );
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-circle-menu.default', handler );
	});

})(jQuery);
