(function($) {
	"use strict";

	var handler = function($scope) {
			var iScrollTarget = $scope.find( '.wpr-timeline-centered' ).length > 0 ? $scope.find( '.wpr-timeline-centered' ) : '',
			    element = $scope.find('.wpr-timeline-centered').length > 0 ? $scope.find('.wpr-timeline-centered') : '',
				pagination = $scope.find( '.wpr-grid-pagination' ).length > 0 ? $scope.find( '.wpr-grid-pagination' ) : '',
				middleLine = $scope.find('.wpr-middle-line').length > 0 ? $scope.find('.wpr-middle-line') : '',
				timelineFill = $scope.find(".wpr-timeline-fill").length > 0 ? $scope.find(".wpr-timeline-fill") : '',
				lastIcon = $scope.find('.wpr-main-line-icon.wpr-icon:last').length > 0 ? $scope.find('.wpr-main-line-icon.wpr-icon:last') : '',
				firstIcon = $scope.find('.wpr-main-line-icon.wpr-icon').length > 0 ? $scope.find('.wpr-main-line-icon.wpr-icon').first() : '',
				scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' ),
				aosOffset = $scope.find('.wpr-story-info-vertical').attr('data-animation-offset') ? +$scope.find('.wpr-story-info-vertical').attr('data-animation-offset') : '',
				aosDuration = $scope.find('.wpr-story-info-vertical').attr('data-animation-duration') ? +$scope.find('.wpr-story-info-vertical').attr('data-animation-duration') : '';


			if ( $scope.find('.wpr-timeline-centered').length > 0 ) {
				
				$(window).resize(function() {
					removeLeftAlignedClass();
				});

				$(window).smartresize(function() {
					removeLeftAlignedClass();
				});

				setTimeout(function() {
					removeLeftAlignedClass();
					$(window).trigger('resize');
				}, 500);

				adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				
				setTimeout(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
					$(window).trigger('resize');
				}, 500);

				$(window).smartresize(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				});

				$(window).resize(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				});
	
				if ( 'load-more' !== iScrollTarget.attr('data-pagination') ) {
					$scope.find('.wpr-grid-pagination').css('visibility', 'hidden');
				}

				AOS.init({
					offset: parseInt(aosOffset),
					duration: aosDuration,
					once: true,
				});

				postsTimelineFill(lastIcon, firstIcon);

				$(window).on('scroll',  function() {
					postsTimelineFill(lastIcon, firstIcon);
				});

				// init Infinite Scroll
				if ( !$scope.find('.elementor-repeater-items').length && !WprElements.editorCheck() && ('load-more' === $scope.find('.wpr-timeline-centered').data('pagination') || 'infinite-scroll' === $scope.find('.wpr-timeline-centered').data('pagination')) ) {
					var threshold = iScrollTarget !== undefined && 'load-more' === iScrollTarget.attr('data-pagination') ? false : 10;
					// var navClass = scopeClass +' .wpr-load-more-btn';
					
					iScrollTarget.infiniteScroll({
						path: scopeClass +' .wpr-grid-pagination a',
						hideNav: false,
						append:  scopeClass +'.wpr-timeline-entry',
						history: false,
						scrollThreshold: threshold,
						status: scopeClass + ' .page-load-status',
					});
					// Request
					iScrollTarget.on( 'request.infiniteScroll', function( event, path ) {
						$scope.find( '.wpr-load-more-btn' ).hide();
						$scope.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					});
					
					var pagesLoaded = 0;

					iScrollTarget.on( 'load.infiniteScroll', function( event, response ) {
						pagesLoaded++;
						
						// get posts from response
						var items = $( response ).find(scopeClass).find( '.wpr-timeline-entry' );
						iScrollTarget.infiniteScroll( 'appendItems', items );

						if ( !$scope.find('.wpr-one-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline-left').length ) {
							$scope.find('.wpr-timeline-entry').each(function(index, value){
								$(this).removeClass('wpr-right-aligned wpr-left-aligned');
								if ( 0 == index % 2 ) {
									$(this).addClass('wpr-left-aligned');
									$(this).find('.wpr-story-info-vertical').attr('data-aos', $(this).find('.wpr-story-info-vertical').attr('data-aos-left'));
								} else {
									$(this).addClass('wpr-right-aligned');
									$(this).find('.wpr-story-info-vertical').attr('data-aos', $(this).find('.wpr-story-info-vertical').attr('data-aos-right'));
								}
							});
						}

						AOS.init({
							offset: parseInt(aosOffset),
							duration: aosDuration,
							once: true,
						});

						$(window).scroll();

						$scope.find( '.wpr-pagination-loading' ).hide();
						// $scope.find( '.wpr-load-more-btn' ).fadeIn();
						if ( iScrollTarget.data('max-pages') - 1 !== pagesLoaded ) { // $pagination_max_pages
							if ( 'load-more' === iScrollTarget.attr('data-pagination') ) {
								$scope.find( '.wpr-load-more-btn' ).fadeIn();
							}
						} else {
							$scope.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
							pagination.delay( 2000 ).fadeOut( 1000 );
						}

						middleLine = $scope.find('.wpr-middle-line');
						timelineFill = $scope.find(".wpr-timeline-fill");
						lastIcon = $scope.find('.wpr-main-line-icon.wpr-icon:last');
						firstIcon = $scope.find('.wpr-main-line-icon.wpr-icon').first();
						element = $scope.find('.wpr-timeline-centered');

						adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
						$(window).trigger('resize');
						postsTimelineFill(lastIcon, firstIcon);
					});

					if ( !WprElements.editorCheck() ) {
						$scope.find( '.wpr-load-more-btn' ).on( 'click', function() {
							iScrollTarget.infiniteScroll( 'loadNextPage' );
							return false;
						});

						if ( 'infinite-scroll' == iScrollTarget.attr('data-pagination') ) {
								iScrollTarget.infiniteScroll('loadNextPage');
						}
					}
				}
			}

			if ( $scope.find('.swiper-wrapper').length ) {

				var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
					// if ('undefined' === typeof Swiper) {
					// 	var asyncSwiper = elementorFrontend.utils.swiper;     
					// 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
					// 		return newSwiperInstance;
					// 	});
					//  } else {
					// 	return swiperPromise(swiperElement, swiperConfig);  
					// }

					// Check if swiperPromise is necessary
					var asyncSwiper = elementorFrontend.utils.swiper;     
					return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
						return newSwiperInstance;
					});
				};
				
				var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {    
					return new Promise(function (resolve, reject) {  
							var swiperInstance = new Swiper(swiperElement, swiperConfig); 
							resolve(swiperInstance); 
					}); 
				};
			
				var horizontal = $scope.find('.wpr-horizontal-bottom').length ? '.wpr-horizontal-bottom' : '.wpr-horizontal';
				var swiperSlider = $scope.find(horizontal +".swiper");
							
				var slidestoshow = swiperSlider.data("slidestoshow");

				swiperLoader(swiperSlider, {
					spaceBetween: +swiperSlider.data('swiper-space-between'),
					loop: swiperSlider.data('loop') === 'yes' ? true : false,
					autoplay: swiperSlider.data("autoplay") !== 'yes' ? false : {
						delay: +swiperSlider.attr('data-swiper-delay'),
						disableOnInteraction: false,
						pauseOnMouseEnter: swiperSlider.data('swiper-poh') === 'yes' ? true : false,
					},
					on: {
						init: function () {
							if ( $scope.find('.wpr-timeline-outer-container').length > 0 ) {
								$scope.find('.wpr-timeline-outer-container').css('opacity', 1);
							}
						},
					},
					speed: +swiperSlider.attr('data-swiper-speed'),
					slidesPerView: swiperSlider.data("slidestoshow"),
					direction: 'horizontal',
					pagination: {
					  el: '.wpr-swiper-pagination',
					  type: 'progressbar',
					},
					navigation: {
					  nextEl: '.wpr-button-next',
					  prevEl: '.wpr-button-prev',
					},
					// Responsive breakpoints
					breakpoints: {
					  // when window width is >= 320px
					  320: {
						slidesPerView: 1,
					  },
					  // when window width is >= 480px
					  480: {
						slidesPerView: 2,
					  },
					  // when window width is >= 640px
					  769: { // 640
						slidesPerView: slidestoshow,
					  }
					},
				  });

				//   swiperSlider.data('pause-on-hover') === 'yes' && swiperSlider.hover(function() {
				// 	  (this).swiper.autoplay.stop();
				//   }, function() {
				// 	  (this).swiper.autoplay.start();
				//   });

			} else {
				$(document).ready(function() {
					// Handler when all assets (including images) are loaded
					if ( $scope.find('.wpr-timeline-outer-container').length ) {
						$scope.find('.wpr-timeline-outer-container').css('opacity', 1);
					}
				});
			}

			function removeLeftAlignedClass() {
				if ( $scope.find('.wpr-centered').length ) {
					if ( window.innerWidth <= 767 ) {
						$scope.find('.wpr-wrapper .wpr-timeline-centered').removeClass('wpr-both-sided-timeline').addClass('wpr-one-sided-timeline').addClass('wpr-remove-one-sided-later');
						$scope.find('.wpr-wrapper .wpr-left-aligned').removeClass('wpr-left-aligned').addClass('wpr-right-aligned').addClass('wpr-remove-right-aligned-later');
					} else {
						$scope.find('.wpr-wrapper .wpr-timeline-centered.wpr-remove-one-sided-later').removeClass('wpr-one-sided-timeline').addClass('wpr-both-sided-timeline').removeClass('wpr-remove-one-sided-later');
						$scope.find('.wpr-wrapper .wpr-remove-right-aligned-later').removeClass('wpr-right-aligned').addClass('wpr-left-aligned').removeClass('wpr-remove-right-aligned-later');
					}
				}
			}

		  function postsTimelineFill(lastIcon, firstIcon) {
			if ( !$scope.find('.wpr-timeline-fill').length ) {
				return;
			}

			if ( $scope.find('.wpr-timeline-entry:eq(0)').prev('.wpr-year-wrap').length > 0 ) {
				firstIcon = $scope.find('.wpr-year-label').eq(0);
			}

			  if ( timelineFill.length ) {
				var fillHeight = timelineFill.css('height').slice(0, -2),
					docScrollTop = document.documentElement.scrollTop,
					clientHeight = document.documentElement.clientHeight/2;
				  
				if ( !((docScrollTop + clientHeight - (firstIcon.offset().top)) > lastIcon.offset().top - firstIcon.offset().top + parseInt(lastIcon.css('height').slice(0, -2))) ) {
					timelineFill.css('height', (docScrollTop  + clientHeight - (firstIcon.offset().top)) + 'px');
				}

				$scope.find('.wpr-main-line-icon.wpr-icon').each(function () {
					if ( $(this).offset().top < parseInt( firstIcon.offset().top + parseInt(fillHeight) ) ) {
						$(this).addClass('wpr-change-border-color');
					} else {
						$(this).removeClass('wpr-change-border-color');
					}
				});
			  }
		  }

		  function adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element) {
			  	element = $scope.find('.wpr-timeline-centered');
				if ( !$scope.find('.wpr-both-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline-left').length ) {
					return;
				}

				if ( $scope.find('.wpr-timeline-entry:eq(0)').prev('.wpr-year-wrap').length > 0 ) {
					firstIcon = $scope.find('.wpr-year-label').eq(0);
				}
				
				var firstIconOffset = firstIcon.offset().top;
				var lastIconOffset = lastIcon.offset().top;
				var middleLineTop = (firstIconOffset - element.offset().top) + 'px';
				// var middleLineHeight = (lastIconOffset - (lastIcon.css('height').slice(0, -2)/2 + (firstIconOffset - firstIcon.css('height').slice(0, -2)))) + 'px';
				var middleLineHeight = lastIconOffset - firstIconOffset + parseInt(lastIcon.css('height').slice(0, -2));
				var middleLineMaxHeight = firstIconOffset - lastIconOffset + 'px !important';

				middleLine.css('top', middleLineTop);
				middleLine.css('height', middleLineHeight);
				// middleLine.css('maxHeight', middleLineMaxHeight);
				timelineFill !== '' ? timelineFill.css('top', middleLineTop) : '';
		  }
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-posts-timeline.default', handler );
	});

})(jQuery);
