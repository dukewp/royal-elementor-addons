(function($) {
	"use strict";

	var handler = function($scope) {
			var settings = JSON.parse($scope.find( '.wpr-img-accordion-media-hover' ).attr( 'data-settings' ));

			// var MediaWrap = $scope.find( '.wpr-img-accordion-media-hover' );
			var MediaWrap = $scope.find( '.wpr-image-accordion' );
			// var	lightboxSettings = settings.lightbox;
			var lightboxSettings = $scope.find('.wpr-image-accordion').attr('lightbox') ? JSON.parse($scope.find('.wpr-image-accordion').attr('lightbox')) : '';

			var thisTargetHasClass = false;

			if ( $scope.find('.wpr-image-accordion-wrap').hasClass('wpr-acc-no-column') ) {
				if ( !$scope.hasClass('wpr-image-accordion-row') );
				$scope.removeClass('wpr-image-accordion-column').addClass('wpr-image-accordion-row');
				$scope.find('.wpr-image-accordion').css('flex-direction', 'row');
			}

			if ( '' !== lightboxSettings ) {
			
				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );
	
				// Fix LightGallery Thumbnails
				MediaWrap.on('onAfterOpen.lg',function() {
					if ( $('.lg-outer').find('.lg-thumb-item').length ) {
						$('.lg-outer').find('.lg-thumb-item').each(function() {
							var imgSrc = $(this).find('img').attr('src'),
								newImgSrc = imgSrc,
								extIndex = imgSrc.lastIndexOf('.'),
								imgExt = imgSrc.slice(extIndex),
								cropIndex = imgSrc.lastIndexOf('-'),
								cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;
							
							if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
								cropSize = '';
							}
	
							if ( cropSize !== '' ) {
								if ( false !== cropSize ) {
									newImgSrc = imgSrc.replace(cropSize, '-150x150');
								} else {
									newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
								}
							}
	
							// Change SRC
							$(this).find('img').attr('src', newImgSrc);
	
							if ( false == cropSize || '-450x450' === cropSize ) {
								$(this).find('img').attr('src', imgSrc);
							}
						});
					}
				});
	
				// Show/Hide Controls
				$scope.find( '.wpr-image-accordion' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );
	
					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}
	
					// Autoplay Button
					if ( '' === lightboxSettings.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});

			}

			MediaWrap.css('cursor', 'pointer');

			// Init Media Hover Link

			var accordionItem = $scope.find('.wpr-image-accordion-item');

			// Media Hover Link
			function mediaHoverLink() {
				if ( ! WprElements.editorCheck() ) {

					$scope.find('.wpr-img-accordion-media-hover').on( 'click', function( event ) {
						var thisSettings = event.target.className.includes('wpr-img-accordion-media-hover') ? JSON.parse($(this).attr('data-settings')) : JSON.parse($(this).closest('.wpr-img-accordion-media-hover').attr( 'data-settings' ));
						
						if ( !$(event.target).hasClass('wpr-img-accordion-item-lightbox') && 0 === $(event.target).closest('.wpr-img-accordion-item-lightbox').length ) {
							var itemUrl = thisSettings.activeItem.overlayLink;
							if (itemUrl != '') {
	
								if ( '_blank' === thisSettings.activeItem.overlayLinkTarget ) {
									window.open(itemUrl, '_blank').focus();
								} else {
									window.location.href = itemUrl;
								}
	
							}
						}
					});
				}				
			}

			if ( 'hover' === settings.activeItem.interaction) {

				mediaHoverLink();
				
				accordionItem.on('mouseenter', function() {
					accordionItem.removeClass('wpr-image-accordion-item-grow');
					accordionItem.find('.wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
					$(this).addClass('wpr-image-accordion-item-grow');
					$(this).find('.wpr-animation-wrap').addClass('wpr-animation-wrap-active');
				});

				accordionItem.on('mouseleave', function() {
					$(this).removeClass('wpr-image-accordion-item-grow');
					$(this).find('.wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
				});

			} else if ( 'click' === settings.activeItem.interaction ) {
				$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap');
				accordionItem.on('click', '.wpr-img-accordion-media-hover', function(event) {
					thisTargetHasClass = event.target.className.includes('wpr-img-accordion-media-hover') ? event.target.className.includes('wpr-animation-wrap-active') : $(this).closest('.wpr-img-accordion-media-hover').hasClass('wpr-animation-wrap-active');
					if (thisTargetHasClass && !WprElements.editorCheck()) {
						var thisSettings = event.target.className.includes('wpr-img-accordion-media-hover') ? JSON.parse($(this).attr('data-settings')) : JSON.parse($(this).closest('.wpr-img-accordion-media-hover').attr( 'data-settings' ));
						
						if ( !$(event.target).hasClass('wpr-img-accordion-item-lightbox') && 0 === $(event.target).closest('.wpr-img-accordion-item-lightbox').length ) {
							var itemUrl = thisSettings.activeItem.overlayLink;
							if (itemUrl != '') {
								if ( '_blank' === thisSettings.activeItem.overlayLinkTarget ) {
									window.open(itemUrl, '_blank').focus();
								} else {
									window.location.href = itemUrl;
								}
							}
						}
					} else {
						$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
						accordionItem.removeClass('wpr-image-accordion-item-grow');
						$(this).closest('.wpr-image-accordion-item').addClass('wpr-image-accordion-item-grow');
						$(this).closest('.wpr-img-accordion-media-hover').addClass('wpr-animation-wrap-active');
					}
				});
			} else {
				$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap');
			}

			accordionItem.each(function() {
				if ( $(this).index() === settings.activeItem.defaultActive - 1 ) {
					if ( 'click' === settings.activeItem.interaction) {
						setTimeout(() => {
							$(this).find('.wpr-img-accordion-media-hover').trigger('click');
						}, 400);
					} else {
						setTimeout(() => {
							$(this).find('.wpr-img-accordion-media-hover').trigger('mouseenter');
						}, 400);
					}
				}
			});
			
			$scope.find('.wpr-image-accordion-wrap').css('opacity', 1); 
        }

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-image-accordion.default', handler );
	});

})(jQuery);
