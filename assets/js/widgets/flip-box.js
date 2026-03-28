(function($) {
	"use strict";

	var handler = function($scope) {
			
			var $flipBox = $scope.find('.wpr-flip-box'),
				flipBoxTrigger = $flipBox.data('trigger');
			
			// Listen for the pageshow event to prevent undesired cache
			window.addEventListener('pageshow', function(event) {
				if (event.persisted) {
					if ( $flipBox.hasClass('wpr-flip-box-active') ) {
						$flipBox.removeClass('wpr-flip-box-active');
					}
				}
			});

			// Listen for the popstate event same purpose
			window.addEventListener('popstate', function(event) {
				if ( $flipBox.hasClass('wpr-flip-box-active') ) {
					$flipBox.removeClass('wpr-flip-box-active');
				}
			});

			if ( 'box' === flipBoxTrigger ) {

				$flipBox.find('.wpr-flip-box-front').on( 'click', function() {
					$(this).closest('.wpr-flip-box').addClass('wpr-flip-box-active');
				});

				$(window).on( 'click', function () {
					if( $(event.target).closest('.wpr-flip-box').length === 0 ) {
						$flipBox.removeClass('wpr-flip-box-active');
					}
				});
		   
			} else if ( 'btn' == flipBoxTrigger ) {
		  
				$flipBox.find('.wpr-flip-box-btn').on( 'click', function() {
					$(this).closest('.wpr-flip-box').addClass('wpr-flip-box-active');		   
				});

				$(window).on( 'click', function (event) {
					if( $(event.target).closest('.wpr-flip-box').length === 0 ) {
						$flipBox.removeClass('wpr-flip-box-active');
					}
				});

			  
			} else if ( 'hover' == flipBoxTrigger ) {
		  
				$flipBox.hover(function () {
					$(this).toggleClass('wpr-flip-box-active');
				});

			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-flip-box.default', handler );
	});

})(jQuery);
