(function($) {
	"use strict";

	var handler = function($scope) {
			var countDownWrap = $scope.children( '.elementor-widget-container' ).children( '.wpr-countdown-wrap' ).length > 0 ? $scope.children( '.elementor-widget-container' ).children( '.wpr-countdown-wrap' ) : $scope.children( '.wpr-countdown-wrap' ),
				countDownInterval = null,
				dataInterval = countDownWrap.data( 'interval' ),
				dataShowAgain = countDownWrap.data( 'show-again' ),
				endTime = new Date( dataInterval * 1000);

			// Evergreen End Time
			if ( 'evergreen' === countDownWrap.data( 'type' ) ) {
				var evergreenDate = new Date(),
					widgetID = $scope.attr( 'data-id' ),
					settings = JSON.parse( localStorage.getItem( 'WprCountDownSettings') ) || {};

				// End Time
				if ( settings.hasOwnProperty( widgetID ) ) {
					if ( Object.keys(settings).length === 0 || dataInterval !== settings[widgetID].interval ) {
						endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
					} else {
						endTime = settings[widgetID].endTime;
					}
				} else {
					endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
				}

				if ( endTime + dataShowAgain < evergreenDate.setSeconds( evergreenDate.getSeconds() ) ) {
					endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
				}

				// Settings
				settings[widgetID] = {
					interval: dataInterval,
					endTime: endTime
				};

				// Save Settings in Browser
				localStorage.setItem( 'WprCountDownSettings', JSON.stringify( settings ) );
			}

			// Start CountDown
			if ( ! WprElements.editorCheck() ) { //tmp
			}
			// Init on Load
			initCountDown();

			// Start CountDown
			countDownInterval = setInterval( initCountDown, 1000 );

			function initCountDown() {
				var timeLeft = endTime - new Date();

				var numbers = {
					days: Math.floor(timeLeft / (1000 * 60 * 60 * 24)),
					hours: Math.floor(timeLeft / (1000 * 60 * 60) % 24),
					minutes: Math.floor(timeLeft / 1000 / 60 % 60),
					seconds: Math.floor(timeLeft / 1000 % 60)
				};

				if ( numbers.days < 0 || numbers.hours < 0 || numbers.minutes < 0 ) {
					numbers = {
						days: 0,
						hours: 0,
						minutes: 0,
						seconds: 0
					};
				}

				$scope.find( '.wpr-countdown-number' ).each(function() {
					var number = numbers[ $(this).attr( 'data-item' ) ];

					if ( 1 === number.toString().length ) {
						number = '0' + number;
					}

					$(this).text( number );

					// Labels
					var labels = $(this).next();

					if ( labels.length ) {
						if ( ! $(this).hasClass( 'wpr-countdown-seconds' ) ) {
							var labelText = labels.data( 'text' );

							if ( '01' == number ) {
								labels.text( labelText.singular );
							} else {
								labels.text( labelText.plural );
							}
						}
					}
				});

				// Stop Counting
				if ( timeLeft < 0 ) {
					clearInterval( countDownInterval );

					// Actions
					expiredActions();
				}
			}

			function expiredActions() {
				var dataActions = countDownWrap.data( 'actions' );

				if ( ! WprElements.editorCheck() ) {
					
					if ( dataActions.hasOwnProperty( 'hide-timer' ) ) {
						countDownWrap.hide();
					}
					
					if ( dataActions.hasOwnProperty( 'hide-element' ) ) {
						$( dataActions['hide-element'] ).hide();
					}
					
					if ( dataActions.hasOwnProperty( 'message' ) ) {
						if ( ! $scope.children( '.elementor-widget-container' ).children( '.wpr-countdown-message' ).length && ! $scope.children( '.wpr-countdown-message' ).length ) {
							// Sanitize message to prevent XSS
							var sanitizedMessage = WprElements.sanitizeHTMLContent(dataActions['message']);
							countDownWrap.after( '<div class="wpr-countdown-message">'+ sanitizedMessage +'</div>' );
						}
					}
					
					if ( dataActions.hasOwnProperty( 'redirect' ) ) {
						var url = new URL(dataActions['redirect']);
    
						// Define a list of allowed protocols
						var allowedProtocols = ['http:', 'https:'];

						// Check if the URL's protocol is allowed
						if (allowedProtocols.includes(url.protocol)) {
							window.location.href = url.href;
						}
					}

					if ( dataActions.hasOwnProperty( 'load-template' ) ) {
						// countDownWrap.parent().find( '.elementor-inner' ).parent().show();
						countDownWrap.next('.elementor').show();
					}

				}
				
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-countdown.default', handler );
	});

})(jQuery);
