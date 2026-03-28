(function($) {
	"use strict";

	var handler = function($scope) {
			// Settings
			var iGrid = $scope.find( '.wpr-magazine-grid-wrap' ),
				settings = iGrid.attr( 'data-slick' ),
				dataSlideEffect = iGrid.attr('data-slide-effect');

			// Slider
			if ( typeof settings !== typeof undefined && settings !== false ) {
				iGrid.slick({
					fade: 'fade' === dataSlideEffect ? true : false
				});
			}

			$(document).ready(function() {
				iGrid.css('opacity', 1);
			});

			var iGridLength = iGrid.find('.wpr-mgzn-grid-item').length;

			// $(window).smartresize(function() {
			// 	if (window.matchMedia("(max-width: 767px)").matches) { // If media query matches
			// 		iGrid.find('.wpr-magazine-grid.wpr-mgzn-grid-3-h')[0].style.gridTemplateRows = 'repeat('+ iGridLength +', 1fr)';
			// 	} else {
			// 		iGrid.find('.wpr-magazine-grid.wpr-mgzn-grid-3-h').removeAttr('style');
			// 	}
			// });

			// Media Hover Link
			if ( 'yes' === iGrid.find( '.wpr-grid-media-wrap' ).attr( 'data-overlay-link' ) && ! WprElements.editorCheck() ) {
				iGrid.find( '.wpr-grid-media-wrap' ).css('cursor', 'pointer');
				
				iGrid.find( '.wpr-grid-media-wrap' ).on( 'click', function( event ) {
					var targetClass = event.target.className;

					if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'wpr-cv-inner' ) || 
						 -1 !== targetClass.indexOf( 'wpr-grid-media-hover' ) ) {
						event.preventDefault();

						var itemUrl = $(this).find( '.wpr-grid-media-hover-bg' ).attr( 'data-url' );
						
						// GOGA - leave if necessary
						if (iGrid.find('.wpr-grid-item-title a').length) {
							// Extract the itemUrl
							if (itemUrl) {
								try {
									// Create a URL object to validate the URL
									var url = new URL(itemUrl);

									// Define a list of allowed protocols
									var allowedProtocols = ['http:', 'https:'];

									// Check if the URL's protocol is allowed
									if (allowedProtocols.includes(url.protocol)) {
										// Safe to use the URL
										var safeUrl = url.href;

										if ('_blank' === iGrid.find('.wpr-grid-item-title a').attr('target')) {
											window.open(safeUrl, '_blank').focus();
										} else {
											window.location.href = safeUrl;
										}
									} else {
										console.error('Invalid URL scheme:', url.protocol);
									}
								} catch (e) {
									console.error('Invalid URL:', itemUrl);
								}
							}
						}
					}
				});
			}

			// Sharing
			if ( $scope.find( '.wpr-sharing-trigger' ).length ) {
				var sharingTrigger = $scope.find( '.wpr-sharing-trigger' ),
					sharingInner = $scope.find( '.wpr-post-sharing-inner' ),
					sharingWidth = 5;

				// Calculate Width
				sharingInner.first().find( 'a' ).each(function() {
					sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
				});

				// Calculate Margin
				var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );

				// Set Positions
				if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
				} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
				} else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
					// Set Margins
					sharingInner.find( 'a' ).css({
						'margin-right' : '0',
						'margin-top' : sharingMargin +'px'
					});

					// Set Position
					sharingInner.css({
						'top' : -sharingMargin +'px',
						'left' : '50%',
						'-webkit-transform' : 'translate(-50%, -100%)',
						'transform' : 'translate(-50%, -100%)'
					});
				} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css({
						'left' : sharingMargin +'px',
						// 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
					});
				} else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
					// Set Margins
					sharingInner.find( 'a' ).css({
						'margin-right' : '0',
						'margin-bottom' : sharingMargin +'px'
					});

					// Set Position
					sharingInner.css({
						'bottom' : -sharingMargin +'px',
						'left' : '50%',
						'-webkit-transform' : 'translate(-50%, 100%)',
						'transform' : 'translate(-50%, 100%)'
					});
				}

				if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
					sharingTrigger.on( 'click', function() {
						var sharingInner = $(this).next();

						if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
							sharingInner.css( 'visibility', 'visible' );
							sharingInner.find( 'a' ).css({
								'opacity' : '1',
								'top' : '0'
							});

							setTimeout( function() {
								sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
							}, sharingInner.find( 'a' ).length * 100 );
						} else {
							sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

							sharingInner.find( 'a' ).css({
								'opacity' : '0',
								'top' : '-5px'
							});
							setTimeout( function() {
								sharingInner.css( 'visibility', 'hidden' );
							}, sharingInner.find( 'a' ).length * 100 );
						}
					});
				} else {
					sharingTrigger.on( 'mouseenter', function() {
						var sharingInner = $(this).next();

						sharingInner.css( 'visibility', 'visible' );
						sharingInner.find( 'a' ).css({
							'opacity' : '1',
							'top' : '0',
						});
						
						setTimeout( function() {
							sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
						}, sharingInner.find( 'a' ).length * 100 );
					});
					$scope.find( '.wpr-grid-item-sharing' ).on( 'mouseleave', function() {
						var sharingInner = $(this).find( '.wpr-post-sharing-inner' );

						sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

						sharingInner.find( 'a' ).css({
							'opacity' : '0',
							'top' : '-5px'
						});
						setTimeout( function() {
							sharingInner.css( 'visibility', 'hidden' );
						}, sharingInner.find( 'a' ).length * 100 );
					});
				}
			}

			// Likes
			if ( $scope.find( '.wpr-post-like-button' ).length ) {

				$scope.find( '.wpr-post-like-button' ).on( 'click', function() {
					var current = $(this);

					if ( '' !== current.attr( 'data-post-id' ) ) {

					$.ajax({
						type: 'POST',
						url: $('<div>').text(current.attr('data-ajax')).html(),
						data: {
							action : 'wpr_likes_init',
							post_id : current.attr( 'data-post-id' ),
							nonce : current.attr( 'data-nonce' )
						},
						beforeSend:function() {
							current.fadeTo( 500, 0.5 );
						},	
						success: function( response ) {
							// Get Icon
							var iconClass = $('<div>').text( current.attr('data-icon') ).html();

							// Get Count
							var countHTML = response.count;

							if ( '' === countHTML.replace(/<\/?[^>]+(>|$)/g, "") ) {
								countHTML = '<span class="wpr-post-like-count">' + $('<div>').text( current.attr('data-text') ).html() + '</span>';

								if ( ! current.hasClass( 'wpr-likes-zero' ) ) {
									current.addClass( 'wpr-likes-zero' );
								}
							} else {
								current.removeClass( 'wpr-likes-zero' );
							}

							// Update Icon
							if ( current.hasClass( 'wpr-already-liked' ) ) {
								current.prop( 'title', 'Like' );
								current.removeClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'fas', 'far' ) +'"></i>' + countHTML );
							} else {
								current.prop( 'title', 'Unlike' );
								current.addClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'far', 'fas' ) +'"></i>' + countHTML );
							}

							current.fadeTo( 500, 1 );
						}
					});

					}

					return false;
				});

			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-magazine-grid.default', handler );
	});

})(jQuery);
