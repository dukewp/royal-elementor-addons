(function($) {
	"use strict";

	var handler = function($scope) {
			var gallery = $scope.find( '.wpr-gallery-slider' ),
				gallerySettings = gallery.attr( 'data-slick' );
			
			gallery.animate({ 'opacity' : '1' }, 1000 );

			if ( '[]' !== gallerySettings ) {
				gallery.slick({
					appendDots : $scope.find( '.wpr-gallery-slider-dots' ),
					customPaging : function ( slider, i ) {
						var slideNumber = (i + 1),
							totalSlides = slider.slideCount;

						return '<span class="wpr-gallery-slider-dot"></span>';
					}
				});
			}

			// Lightbox
			var lightboxSettings = $( '.wpr-featured-media-image' ).attr( 'data-lightbox' );

			if ( typeof lightboxSettings !== typeof undefined && lightboxSettings !== false && ! WprElements.editorCheck() ) {
				var MediaWrap = $scope.find( '.wpr-featured-media-wrap' );
					lightboxSettings = JSON.parse( lightboxSettings );

				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );

				// Show/Hide Controls
				MediaWrap.on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
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
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-featured-media.default', handler );
	});

})(jQuery);
