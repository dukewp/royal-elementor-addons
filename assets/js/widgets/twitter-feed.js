(function($) {
	"use strict";

	var handler = function($scope) {

			if ($scope.find('.wpr-twitter-feed').attr( 'data-settings' )) {
				var settings = JSON.parse( $scope.find('.wpr-twitter-feed').attr( 'data-settings' ) );
			} else {
				return;
			}

			let twitterFeed = $scope.find('.wpr-twitter-feed');
			
			var settings = JSON.parse( twitterFeed.attr( 'data-settings' ) );
			var loadMoreSettings = settings.twitter_load_more_settings;

			var nextPostsIndex = loadMoreSettings.number_of_posts;
			var pagination = $scope.find( '.wpr-grid-pagination' );

			if ( $scope.hasClass('wpr-twitter-feed-masonry') ) {
				// Init Functions
				isotopeLayout( settings );
				setTimeout(function() {
					isotopeLayout( settings );
				}, 100 );

				if ( WprElements.editorCheck() ) {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 500 );
					setTimeout(function() {
						isotopeLayout( settings );
					}, 1000 );
				}

				$( window ).on( 'load', function() {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 100 );
				});

				$(window).smartresize(function(){
					setTimeout(function() {
						isotopeLayout( settings );
					}, 200 );
				});
			}

			function isotopeLayout( settings ) {
				var twitterFeed = $scope.find( '.wpr-twitter-feed' ),
					item = twitterFeed.find( '.wpr-tweet' ),
					layout = settings.layout_select,
					columns = 3,
					gutterHr = settings.gutter_hr,
					gutterVr = settings.gutter_vr,
					contWidth = twitterFeed.width() + gutterHr - 0.3,
					viewportWidth = $(window).outerWidth(),
					transDuration = 400;

					var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
					var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
					var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
					var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
					var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
					var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

				// Mobile
				if (MobileResp >= viewportWidth ) {
					columns = (settings.columns_mobile) ? (settings.columns_mobile) : 1;
				// Mobile Extra
				} else if ( MobileExtraResp >= viewportWidth ) {
					columns = (settings.columns_mobile_extra) ? settings.columns_mobile_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;
				// Tablet
				} else if ( TabletResp >= viewportWidth ) {
					columns = (settings.columns_tablet) ? settings.columns_tablet : 2;
				// Tablet Extra
				} else if ( TabletExtraResp >= viewportWidth ) {
					columns = (settings.columns_tablet_extra) ? settings.columns_tablet_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;

				// Laptop
				} else if (  LaptopResp >= viewportWidth ) {
					columns = (settings.columns_laptop) ? settings.columns_laptop : settings.columns;

				// Desktop
				} else if ( wideScreenResp - 1 >= viewportWidth ) {
					columns = settings.columns;

				// Larger Screens
				} else if ( wideScreenResp <= viewportWidth ) {
					columns = (settings.columns_widescreen) ? settings.columns_widescreen : settings.columns;
				} else {
					columns = settings.columns
				}

				// Limit Columns for Higher Screens
				if ( columns > 8 ) {
					columns = 8;
				}

				columns = parseInt(columns);

				if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
					columns = 3;
				}

				// Calculate Item Width
				item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

				// Set Vertical Gutter
				item.css( 'margin-bottom', gutterVr +'px' );

				// Reset Vertical Gutter for 1 Column Layout
				if ( 1 === columns ) {
					item.last().css( 'margin-bottom', '0' );
				}

				// add last row & make all post equal height
				var maxTop = -1;

				// Run Isotope
				var twitterFeedMasonry = twitterFeed.isotopewpr({
					layoutMode: layout,
					masonry: {
						comlumnWidth: contWidth / columns,
						gutter: gutterHr
					},
					transitionDuration: transDuration,
  					percentPosition: true
				});

				if ( '1' !== twitterFeed.css( 'opacity' ) ) {
					twitterFeed.css( 'opacity', '1' );
				}

				// return instagramFeed;//tmp
			}

			if ( !WprElements.editorCheck() ) {
				$scope.find('.wpr-load-more-twitter-posts').on('click', function() {
					pagination.find( '.wpr-load-more-btn' ).hide();
					pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					// pagination.find( '.wpr-pagination-finish' ).fadeIn(  );
					// pagination.delay( 2000 ).fadeOut( 1000 );
					// setTimeout(function() {
						$.ajax({
							type: 'POST',
							url: WprConfig.ajaxurl,
							data: { 
								action: 'wpr_load_more_tweets',
								nonce: WprConfig.nonce,
								wpr_load_more_settings: loadMoreSettings,
								next_post_index: nextPostsIndex,
							},
							success: function(data) {
								var $data = $(data);

								$data.each(function() {
									$(this).addClass('wpr-twitter-hidden-item');
								});

								
								$scope.find('.wpr-twitter-feed').append( $data );

								setTimeout(function() {
					
									if ( $scope.hasClass('wpr-twitter-feed-masonry') ) {
										twitterFeed.isotopewpr( 'appended', $data );
								
										twitterFeed.isotopewpr( 'reloadItems' ); // https://isotope.metafizzy.co/methods.html#reloaditems
										
										twitterFeed.isotopewpr('layout'); // https://isotope.metafizzy.co/methods.html#layout
						
										$(window).trigger('resize');
									}

									$data.each(function(index) {
										var item = $(this);
										setTimeout(function() {
											item.removeClass('wpr-twitter-hidden-item');
										}, 300);
									});
		
									// Loading
									pagination.find( '.wpr-pagination-loading' ).hide();
	
									if (data.includes('wpr-tweet')) { // replaceclassname
										pagination.find( '.wpr-load-more-btn' ).fadeIn();
									} else {
										pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
									}
								}, 400);

								nextPostsIndex =  nextPostsIndex + loadMoreSettings.number_of_posts;
							},
							error: function(error) {
								console.log(error);
							}
						});

					// }, 1000);
				});
			}

			twitterFeedCarousel();
				
			$scope.find('.wpr-grid').css('opacity', 1);

			function twitterFeedCarousel() {
				if ( $scope.hasClass('wpr-twitter-feed-carousel') ) {
					var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
						// if ('undefined' === typeof Swiper) {
						// 	var asyncSwiper = elementorFrontend.utils.swiper;     
						// 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {     
						// 		return newSwiperInstance;
						// 	});  
						// } else {     
						// 	return swiperPromise(swiperElement, swiperConfig);  
						// }

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

					$scope.find('.wpr-twitter-feed').css('flexWrap', 'nowrap');

					var sliderSettings = settings.carousel;
					
					$scope.find('.wpr-twitter-feed-cont').addClass('swiper');
					$scope.find('.wpr-twitter-feed').addClass('swiper-wrapper');
					$scope.find('.wpr-tweet').addClass('swiper-slide');
					$scope.find('.wpr-twitter-feed-cont').css('overflow', 'hidden');
					// $scope.find('.elementor-container').css('margin', '0');
					var swiperSlider = $scope.find('.wpr-twitter-feed-cont');

					var aboveMobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value + 1;
					var aboveMobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value + 1;
					var aboveTabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value + 1;
					var aboveTabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value + 1;
					var aboveLaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value + 1;
					var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

					swiperLoader(swiperSlider, {
						autoplay: sliderSettings.wpr_cs_autoplay === 'yes' ? {
							delay: +sliderSettings.wpr_cs_delay,
						} : false,
						loop: sliderSettings.wpr_cs_loop === 'yes' ? true : false,
						slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
						spaceBetween: +sliderSettings.wpr_cs_space_between,
						speed: +sliderSettings.wpr_cs_speed,
						pagination: sliderSettings.wpr_cs_pagination === 'yes' ? {
						el: '.swiper-pagination',
						type: sliderSettings.wpr_cs_pagination_type,
						} : false,
						navigation: {
						prevEl: '.wpr-swiper-button-prev',
						nextEl: '.wpr-swiper-button-next',
						},
						// Responsive breakpoints - direction min
						breakpoints: {
						320: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile,
							// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile,
						},
						[aboveMobileResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile_extra,
							// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile_extra,
						},
						[aboveMobileExtraResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet,
							spaceBetween: +sliderSettings.wpr_cs_space_between_tablet,
						},
						[aboveTabletResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet_extra,
							spaceBetween: +sliderSettings.wpr_cs_space_between_tablet_extra,
						},
						[aboveTabletExtraResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_laptop,
							spaceBetween: +sliderSettings.wpr_cs_space_between_laptop,
						},
						[aboveLaptopResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
							spaceBetween: +sliderSettings.wpr_cs_space_between,
						},
						[wideScreenResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_widescreen,
							spaceBetween: +sliderSettings.wpr_cs_space_between_widescreen,
						}
						},
					
					});

					$scope.css('opacity', 1);
				}
			}

		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-twitter-feed.default', handler );
	});

})(jQuery);
