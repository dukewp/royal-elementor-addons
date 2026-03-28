(function($) {
	"use strict";

	var handler = function($scope) {

			var $progressBar = $scope.find( '.wpr-progress-bar' ),
				prBarCircle = $scope.find( '.wpr-prbar-circle' ),
				$prBarCircleSvg = prBarCircle.find('.wpr-prbar-circle-svg'),
				$prBarCircleLine =  $prBarCircleSvg.find('.wpr-prbar-circle-line'),
				$prBarCirclePrline = $scope.find( '.wpr-prbar-circle-prline' ),
				prBarHrLine = $progressBar.find('.wpr-prbar-hr-line-inner'),
				prBarVrLine = $progressBar.find('.wpr-prbar-vr-line-inner'),
				prBarOptions = $progressBar.data('options'),
				prBarCircleOptions = prBarCircle.data('circle-options'),
				prBarCounter = $progressBar.find('.wpr-prbar-counter-value'),
				prBarCounterValue = prBarOptions.counterValue,
				prBarCounterValuePersent = prBarOptions.counterValuePersent,
				prBarAnimDuration = prBarOptions.animDuration,
				prBarAnimDelay = prBarOptions.animDelay,
				prBarLoopDelay = +prBarOptions.loopDelay,
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				numeratorData = {
					toValue: prBarCounterValue,
					duration: prBarAnimDuration,
				};

			if ( 'yes' === prBarOptions.counterSeparator ) {
				numeratorData.delimiter = ',';
			}


			function isInViewport( $selector ) {
				if ( $selector.length ) {
					var elementTop = $selector.offset().top,
					elementBottom = elementTop + $selector.outerHeight(),
					viewportTop = $(window).scrollTop(),
					viewportBottom = viewportTop + $(window).height();

					if ( elementTop > $(window).height() ) {
						elementTop += 50;
					}

					return elementBottom > viewportTop && elementTop < viewportBottom;
				}
			};

			function progressBar() {

				if ( isInViewport( prBarVrLine ) ) {
					prBarVrLine.css({
						'height': prBarCounterValuePersent + '%'
					});
				}

				if ( isInViewport( prBarHrLine ) ) {
					prBarHrLine.css({
						'width': prBarCounterValuePersent + '%'
					});
				}

				if ( isInViewport( prBarCircle ) ) {
					var circleDashOffset = prBarCircleOptions.circleOffset;
					
					$prBarCirclePrline.css({
						'stroke-dashoffset': circleDashOffset
					});
				}

				// Set Delay
				if ( isInViewport( prBarVrLine ) || isInViewport( prBarHrLine ) || isInViewport( prBarCircle ) ) {
					setTimeout(function() {
						prBarCounter.numerator( numeratorData );
					}, prBarAnimDelay );
				}
			
			}

			progressBar();

			if (prBarOptions.loop === 'yes') {
				setInterval(function() {

					if ( isInViewport( prBarVrLine ) ) {
						prBarVrLine.css({
							'height': 0 + '%'
						});
					}
	
					if ( isInViewport( prBarHrLine ) ) {
						prBarHrLine.css({
							'width': 0 + '%'
						});
					}
	
					if ( isInViewport( prBarCircle ) ) {
						var circleDashOffset = prBarCircleOptions.circleOffset;
						
						$prBarCirclePrline.css({
							'stroke-dashoffset': $prBarCirclePrline.css('stroke-dasharray')
						});
					}

					// Set Delay
					if ( isInViewport( prBarVrLine ) || isInViewport( prBarHrLine ) || isInViewport( prBarCircle ) ) {
						setTimeout(function() {
							prBarCounter.numerator( {
								toValue: 0,
								duration: prBarAnimDuration,
							} );
						}, prBarAnimDelay);
					}

					setTimeout(function() {
						progressBar();
					}, prBarAnimDuration + prBarAnimDelay);
				}, (prBarAnimDuration + prBarAnimDelay) * prBarLoopDelay);
			}

			 $(window).on('scroll', function() {
				progressBar();
			});
				  
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-progress-bar.default', handler );
	});

})(jQuery);
