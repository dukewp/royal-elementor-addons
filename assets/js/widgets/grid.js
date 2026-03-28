(function($) {
	"use strict";

	var handler = function($scope) {
			var iGrid = $scope.find( '.wpr-grid' ),
				scopeId = $scope.attr('data-id'),
				loadedItems,
				experimentActionCount = $scope.hasClass('elementor-widget-wpr-woo-grid') ? 'wpr_get_filtered_count_products' : 
									 $scope.hasClass('elementor-widget-wpr-media-grid') ? 'wpr_get_media_filtered_count' : 'wpr_get_filtered_count_posts',
				experimentActionContent = $scope.hasClass('elementor-widget-wpr-woo-grid') ? 'wpr_woo_grid_filters_ajax' :
									   $scope.hasClass('elementor-widget-wpr-media-grid') ? 'wpr_filter_grid_media' : 'wpr_grid_filters_ajax';

			if ( ! iGrid.length ) {
				return;
			}
			
			if ( $scope.find(".wpr-grid-orderby form").length ) {
				var select = $scope.find(".wpr-grid-orderby form");
				$scope.find(".orderby").on("change", function () {
					select.trigger("submit");
				});
			}

			// Settings
			var settings = iGrid.attr( 'data-settings' );

			// Grid
			if ( typeof settings !== typeof undefined && settings !== false ) {
				settings = JSON.parse( iGrid.attr( 'data-settings' ) );

				$(window).on('load', function() {
					setTimeout(function() {
						if ($scope.find('.woocommerce-result-count').length) {
							var resultCountText = $scope.find('.woocommerce-result-count').text();
							
							var itemsPerPage = settings.query_posts_per_page ? 
								+settings.query_posts_per_page : 
								+WprConfig.woo_shop_ppp;

							if (WprConfig.is_product_category) {
								itemsPerPage = +WprConfig.woo_shop_cat_ppp;
							} else if (WprConfig.is_product_tag) {
								itemsPerPage = +WprConfig.woo_shop_tag_ppp;
							}
							
							if (isNaN(itemsPerPage) || itemsPerPage <= 0) {
								return;
							}
						
							var currentPage = 1;
							var currentPageElement = $scope.find('.wpr-grid-current-page');
							
							if (currentPageElement.length) {
								currentPage = parseInt(currentPageElement.text().trim()) || 1;
							}
						
							var totalItemsMatch = resultCountText.match(/(\d+)(?!.*\d+)/);
							var totalItems = totalItemsMatch ? parseInt(totalItemsMatch[1].trim()) : itemsPerPage;
						
							if (isNaN(totalItems) || totalItems <= 0) {
								return;
							}
						
							var startItem = (currentPage - 1) * itemsPerPage + 1;
							var endItem = Math.min(startItem + itemsPerPage - 1, totalItems);
						
							resultCountText = resultCountText.replace(/\d+\u2013\d+/, startItem + '\u2013' + endItem);
							$scope.find('.woocommerce-result-count').text(resultCountText);
							setTimeout(function() {
								$scope.find('.woocommerce-result-count').text(resultCountText);
							}, 800);
						}
					}, 200);
				});

				// Init Functions
				WprElements.isotopeLayout( settings, '', '', $scope );
				setTimeout(function() {
					WprElements.isotopeLayout( settings, '', '', $scope );
				}, 100 );

				if ( WprElements.editorCheck() ) {
					setTimeout(function() {
						WprElements.isotopeLayout( settings, '', '', $scope );
					}, 500 );
					setTimeout(function() {
						WprElements.isotopeLayout( settings, '', '', $scope );
					}, 1000 );
				}

				$( window ).on( 'load', function() {
					setTimeout(function() {
						WprElements.isotopeLayout( settings, '', '', $scope );
					}, 100 );
				});

				$(document).ready(function() {
					setTimeout(function() {
						WprElements.isotopeLayout( settings, '', '', $scope );
					}, 100 );
				});

				$(window).smartresize(function(){
					setTimeout(function() {
						WprElements.isotopeLayout( settings, '', '', $scope );
					}, 200 );
				});
				
				if ( settings.grid_settings ) {
					var pagesLoadedExperiment = 0;
					if ( $('body').find('.wpr-advanced-filters-wrap').length == 0 ) {
						loadMoreExperiment();
					}
					filtersExperiment();
				}

				isotopeFilters( settings );

				WprElements.changeInitialItems(0, scopeId);

				// Filtering Transitions
				iGrid.on( 'arrangeComplete', function( event, filteredItems ) {
					var deepLinkStager = 0,
						filterStager = 0,
						initStager = 0,
						duration = settings.animation_duration,
						filterDuration = settings.filters_animation_duration;

					if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
						initStager = 0;
					} else {
						iGrid.css( 'opacity', '1' );

						// Default Animation
						if ( 'default' === settings.animation && 'default' === settings.filters_animation ) {
							return;
						}
					}

					for ( var key in filteredItems ) {
						if ( WprElements.getInitialItems(scopeId) == 0 || key > WprElements.getInitialItems(scopeId) - 1 ) {
							initStager += settings.animation_delay;
							$scope.find( filteredItems[key]['element'] ).find( '.wpr-grid-item-inner' ).css({
								'opacity' : '1',
								'top' : '0',
								'transform' : 'scale(1)',
								'transition' : 'all '+ duration +'s ease-in '+ initStager +'s',
							});
						}

						filterStager += settings.filters_animation_delay;
						if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
							$scope.find( filteredItems[key]['element'] ).find( '.wpr-grid-item-inner' ).css({
								'transition' : 'all '+ filterDuration +'s ease-in '+ filterStager +'s',
							});
						}

						// DeepLinking
						var deepLink = window.location.hash;

						if ( deepLink.indexOf( '#filter:' ) >= 0 && deepLink.indexOf( '#filter:*' ) < 0 ) {
							deepLink = deepLink.replace( '#filter:', '' );

							$scope.find( filteredItems[key]['element'] ).filter(function() {
								if ( $(this).hasClass( deepLink ) ) {
									deepLinkStager += settings.filters_animation_delay;
									return $(this);
								}
							}).find( '.wpr-grid-item-inner' ).css({
								'transition-delay' : deepLinkStager +'s'
							});
						}
					}

					WprElements.changeInitialItems(filteredItems.length, scopeId);
				});

				// iGrid.imagesLoaded().progress( function( instance, image ) {
				// });

				// Grid Images Loaded
				iGrid.imagesLoaded(function() {
					if ( '1' !== iGrid.css( 'opacity' ) ) {
						iGrid.css( 'opacity', '1' );
					}
					
					setTimeout(function() {
						iGrid.addClass( 'grid-images-loaded' );
					}, 500 );

					// Equal Heights
					setEqualHeight(settings);
				});

				// Infinite Scroll / Load More
				if ( 'load-more' === settings.pagination_type || 'infinite-scroll' === settings.pagination_type ) {
					if ( $scope.find( '.wpr-grid-pagination' ).length && ! WprElements.editorCheck() ) {
						var pagination = $scope.find( '.wpr-grid-pagination' ),
							scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' );

						var navClass = false,
							threshold = false;

						if ( 'infinite-scroll' === settings.pagination_type ) {
							threshold = 300;
							navClass = scopeClass +' .wpr-load-more-btn';
						}

						// Advanced Filters + infinite-scroll: grid uses same AJAX as load more (handled in widgetAdvancedFilters)
						var skipPathInfiniteScroll = ( 'infinite-scroll' === settings.pagination_type && iGrid.attr( 'data-advanced-filters' ) === 'yes' );
						if ( ! skipPathInfiniteScroll ) {
						iGrid.infiniteScroll({
							path: scopeClass +' .wpr-grid-pagination a',
							hideNav: navClass,
							append: false,
							history: false,
							scrollThreshold: threshold,
							status: scopeClass +' .page-load-status',
							onInit: function() {
								this.on( 'load', function() {
									iGrid.removeClass( 'grid-images-loaded' );
								});
							}
						});
	
						// Request
						iGrid.on( 'request.infiniteScroll', function( event, path ) {
							pagination.find( '.wpr-load-more-btn' ).hide();
							pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
						});

						// Load
						var pagesLoaded = 0;

						iGrid.on( 'load.infiniteScroll', function( event, response ) {
							pagesLoaded++;

							// get posts from response
							var items = $( response ).find( scopeClass ).find( '.wpr-grid-item' );

							if ( $scope.find('.woocommerce-result-count').length ) {
								var resultCount = $scope.find('.woocommerce-result-count').text();
								var updatedResultCount = resultCount.replace( /\d\u2013\d+/, '1\u2013' + ( $scope.find('.wpr-grid-item').length + items.length ) );
								$scope.find('.woocommerce-result-count').text(updatedResultCount);
							}
							
							iGrid.infiniteScroll( 'appendItems', items );
							iGrid.isotopewpr( 'appended', items );

							items.imagesLoaded().progress( function( instance, image ) {
								WprElements.isotopeLayout( settings, '', '', $scope );

								// Fix Layout
								setTimeout(function() {
									WprElements.isotopeLayout( settings, '', '', $scope );
									isotopeFilters( settings );
								}, 10 );
					
								setTimeout(function() {
									iGrid.addClass( 'grid-images-loaded' );
								}, 500 );
							});

							// Loading
							pagination.find( '.wpr-pagination-loading' ).hide();

							if ( settings.pagination_max_pages - 1 !== pagesLoaded ) {
								if ( 'load-more' === settings.pagination_type ) {
									pagination.find( '.wpr-load-more-btn' ).fadeIn();

									if ( $scope.find('.wpr-grid-filters').length ) {
										if ( '*' !== $scope.find('.wpr-active-filter').attr('data-filter') ) {
											if ( 0 < $scope.find('.wpr-active-filter').length ) {
												let dataFilterClass = $scope.find('.wpr-active-filter').attr('data-filter').substring(1);
												items.each(function() {
													if ( !$(this).hasClass(dataFilterClass) ) {
														loadedItems = false;
													} else {
														loadedItems = true;
														return false;
													}
												});
					
												if ( !loadedItems ) {
													$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
												}
											}
										}
									}
								}
							} else {
								pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
								pagination.delay( 2000 ).fadeOut( 1000 );
								setTimeout(function() {
									pagination.find( '.wpr-pagination-loading' ).hide();
								}, 500 );
							}
							// Init Likes
							// No need for this anymore
							// setTimeout(function() {
							// 	postLikes( settings );
							// }, 300 );

							// Init Lightbox
							WprElements.lightboxPopup( settings, $scope, iGrid );

							// Fix Lightbox
							iGrid.data( 'lightGallery' ).destroy( true );
							iGrid.lightGallery( settings.lightbox );

							// Init Media Hover Link
							WprElements.mediaHoverLink($scope, iGrid);

							// Init Post Sharing
							postSharing();

							lazyLoadObserver();

							// Maybe there is some other way
							setTimeout(function() {
								setEqualHeight(settings);
								window.dispatchEvent(new Event('resize'));
							}, 500);
						});

						pagination.find( '.wpr-load-more-btn' ).on( 'click', function() {
							iGrid.infiniteScroll( 'loadNextPage' );
							return false;
						});
						}
					} else {
						$scope.find( '.wpr-load-more-btn' ).on( 'click', function() {
							alert( 'Load More is Disabled in the Editor! Please Preview this Page to see it in action' );
						});
					}
				}

			// Slider
			} else {
				iGrid.animate({ 'opacity': '1' }, 1000);

				settings = JSON.parse( iGrid.attr( 'data-slick' ) );

				var sliderClass = $scope.attr('class'),
					sliderColumnsDesktop = sliderClass.match(/wpr-grid-slider-columns-\d/) ? +sliderClass.match(/wpr-grid-slider-columns-\d/).join().slice(-1) : 2,
					sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? +sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
					sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? +sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
					sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? +sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
					sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? +sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
					sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? +sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
					sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? +sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
					sliderRows = settings.sliderRows,
					sliderSlidesToScroll = settings.sliderSlidesToScroll;

				// GOGA - add rows control and vertical gutter maybe
				iGrid.slick({
					appendDots : $scope.find( '.wpr-grid-slider-dots' ),
					rows: sliderRows,
					customPaging : function ( slider, i ) {
						var slideNumber = (i + 1),
							totalSlides = slider.slideCount;

						return '<span class="wpr-grid-slider-dot"></span>';
					},
					slidesToShow: sliderColumnsDesktop,
					responsive: [
						{
							breakpoint: 10000,
							settings: {
								slidesToShow: sliderColumnsWideScreen,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 2399,
							settings: {
								slidesToShow: sliderColumnsDesktop,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1221,
							settings: {
								slidesToShow: sliderColumnsLaptop,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1200,
							settings: {
								slidesToShow: sliderColumnsTabletExtra,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1024,
							settings: {
								slidesToShow: sliderColumnsTablet,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 880,
							settings: {
								slidesToShow: sliderColumnsMobileExtra,
							 	slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 768,
							settings: {
								slidesToShow: sliderColumnsMobile,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll
							}
						}
					],
				});

				var gridNavPrevArrow = $scope.find('.wpr-grid-slider-prev-arrow');
				var gridNavNextArrow = $scope.find('.wpr-grid-slider-next-arrow');

				if ( gridNavPrevArrow.length > 0 && gridNavNextArrow.length > 0 ) {
					var positionSum = gridNavPrevArrow.position().left * -2;
					if ( positionSum > 0 ) {
						$(window).on('load', function() {
							if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
								gridNavPrevArrow.addClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.addClass('wpr-adjust-slider-next-arrow');
							}
						});
		
						$(window).smartresize(function() {
							if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
								gridNavPrevArrow.addClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.addClass('wpr-adjust-slider-next-arrow');
							} else {
								gridNavPrevArrow.removeClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.removeClass('wpr-adjust-slider-next-arrow');
							}
						});
					}
				}

				// Adjust Horizontal Pagination
				if ( $scope.find( '.slick-dots' ).length && $scope.hasClass( 'wpr-grid-slider-dots-horizontal') ) {
					// Calculate Width
					var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

					// on Load
					if ( $scope.find( '.slick-dots' ).length ) {
						$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
					}


					$(window).smartresize(function() {
						setTimeout(function() {
							// Calculate Width
							var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

							// Set Width
							$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
						}, 300 );
					});
				}
			}
			
			checkWishlistAndCompare();
			addRemoveCompare();
			addRemoveWishlist();
	
			var mutationObserver = new MutationObserver(function(mutations) {
				// checkWishlistAndCompare();
				addRemoveCompare();
				addRemoveWishlist();
			});

			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			// Add To Cart AJAX
			if ( iGrid.find( '.wpr-grid-item-add-to-cart' ).length ) {
				var addCartIcon = iGrid.find( '.wpr-grid-item-add-to-cart' ).find( 'i' ),
					addCartIconClass = addCartIcon.attr( 'class' );

				if ( addCartIcon.length ) {
					addCartIconClass = addCartIconClass.substring( addCartIconClass.indexOf('fa-'), addCartIconClass.length );
				}

				$( 'body' ).on( 'adding_to_cart', function( ev, button, data ) {
					button.fadeTo( 'slow', 0 );
				});

				$( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
					var product_id = button.data('product_id');

					button.next().fadeTo( 700, 1 );

					button.css('display', 'none');

					if ( 'sidebar' === button.data('atc-popup') ) {
						if ( $('.wpr-mini-cart-toggle-wrap a').length ) {
							$('.wpr-mini-cart-toggle-wrap a').each(function() {
								if ( 'none' === $(this).closest('.wpr-mini-cart-inner').find('.wpr-mini-cart').css('display') ) {
									$(this).trigger('click');
								}
							});
						}
					} else if ( 'popup' === button.data('atc-popup') ) {
						var popupItem = button.closest('.wpr-grid-item'),
							popupText = popupItem.find('.wpr-grid-item-title').text(),
							popupLink = button.next().attr('href'),
							popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
							popupAnimation = button.data('atc-animation'),
							fadeOutIn = button.data('atc-fade-out-in'),
							animTime = button.data('atc-animation-time'),
							popupImage,
							animationClass = 'wpr-added-to-cart-default',
							removeAnimationClass;

						if ( 'slide-left' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-slide-in-left';
							removeAnimationClass = 'wpr-added-to-cart-slide-out-left';
						} else if ( 'scale-up' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-scale-up';
							removeAnimationClass = 'wpr-added-to-cart-scale-down';
						} else if ( 'skew' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-skew';
							removeAnimationClass = 'wpr-added-to-cart-skew-off';
						} else if ( 'fade' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-fade';
							removeAnimationClass = 'wpr-added-to-cart-fade-out';
						} else {
							removeAnimationClass = 'wpr-added-to-cart-popup-hide';
						}

						if ( '' !== popupImageSrc ) {
							popupImage = '<div class="wpr-added-tc-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
						} else {
							popupImage = '';
						}
						
						if ( !($scope.find('.wpr-grid').find('#wpr-added-to-cart-'+product_id).length > 0) ) {
							$scope.find('.wpr-grid').append('<div id="wpr-added-to-cart-'+product_id+'" class="wpr-added-to-cart-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tc-title"><p>'+ popupText + ' ' + WprConfig.addedToCartText +'</p><p><a href='+popupLink+'>'+ WprConfig.viewCart +'</a></p></div></div>');

							setTimeout(() => {
								$(this).find('#wpr-added-to-cart-'+product_id).addClass(removeAnimationClass);
								setTimeout(() => {
									$(this).find('#wpr-added-to-cart-'+product_id).remove();
								}, animTime * 1000);
							}, fadeOutIn * 1000);
						}
					}

					if ( addCartIcon.length ) {
						button.find( 'i' ).removeClass( addCartIconClass ).addClass( 'fa-check' );
						setTimeout(function() {
							button.find( 'i' ).removeClass( 'fa-check' ).addClass( addCartIconClass );
						}, 3500 );
					}
				});
			}

			// Init Post Sharing
			postSharing();

			// Post Sharing
			function postSharing() {
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
			}

			// Init Media Hover Link
			WprElements.mediaHoverLink($scope, iGrid);
				
			function isValidHttpUrl(string) {
				let url;
				try {
				  url = new URL(string);
				} catch (_) {
				  return false;
				}
				return url.protocol === "http:" || url.protocol === "https:";
			}

			// Init Lightbox
			if ( !$scope.hasClass('elementor-widget-wpr-woo-category-grid-pro') && !$scope.hasClass('elementor-widget-wpr-category-grid-pro') ) {
				WprElements.lightboxPopup( settings, $scope, iGrid );
			}

			// Init Likes
			postLikes( settings );

			// Likes
			function postLikes( settings ) {
				if ( ! $scope.find( '.wpr-post-like-button' ).length ) {
					return;
				}
				
				$scope.on('click', '.wpr-post-like-button', function(e) {
					e.preventDefault();

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

			// Set equal height to all grid-items
			function setEqualHeight( settings ) {
				let iGrid = $scope.find( '.wpr-grid' ),
					items = iGrid.children('article'),
					columns = Math.floor(iGrid.outerWidth() / items.outerWidth());

				if ( 'fitRows' === settings.layout && columns > 1 ) {
					let maxHeight = Math.max.apply(null, items.map(function(item) {
						return $(this).outerHeight();
					}));

					items.each(function() {
						$(this).css('height', maxHeight + 'px');
					});
                    
                    if ( 'yes' === settings.stick_last_element_to_bottom ) {
                        $scope.addClass('wpr-grid-last-element-yes');
                    }
				}
			}

			function lazyLoadObserver() {
				setTimeout(function() {
					let lazyLoadObserver = new IntersectionObserver((entries, observer) => {
						entries.forEach(entry => {
							if(entry.isIntersecting && entry.target.src.includes('icon-256x256')) {
								setTimeout(function() {
									entry.target.src = entry.target.parentElement.dataset.src;
									entry.target.classList.toggle('wpr-hidden-image');
									$(window).trigger('resize');
								}, 100);
							}
						});
					}, {});
					
					$scope.find('.wpr-grid-image-wrap img:first-of-type').each(function() {
						lazyLoadObserver.observe($(this)[0]);
					});
				}, 100);
			}

			lazyLoadObserver();

			// Isotope Filters
			function isotopeFilters( settings, event = 'load' ) { // GOGA: event listener parameter to determine load or other event

				// Count
				if ( 'yes' === settings.filters_count ) {
					$scope.find( '.wpr-grid-filters a, .wpr-grid-filters span' ).each(function() {
						let thisFilterHTML = $(this),
							numberOfPosts;

						if ( settings.grid_settings && 'load' == event  ) {

							var	thisTaxonomy = '*' !== $(this).attr( 'data-filter') ? $(this).data('ajax-filter')[0] : '*',
								thisFilter = '*' !== $(this).attr( 'data-filter') ? $(this).data('ajax-filter')[1] : '*';

								let data = {
									action: experimentActionCount,
									nonce: WprConfig.nonce,
									wpr_offset: 0,
									wpr_filter: thisFilter,
									wpr_taxonomy: thisTaxonomy,
									grid_settings: settings.grid_settings,
								};

							$.ajax({
								type: 'POST',
								url: WprConfig.ajaxurl,
								data: data,
								success: function(response) {
									numberOfPosts = response.data.query_found;
									thisFilterHTML.find( 'sup' ).text( response.data.query_found );
								},
								error: function(error) {
									console.log(error);
								}
							});
						} else {
							if ( '*' === $(this).attr( 'data-filter') ) {
								$(this).find( 'sup' ).text( $scope.find( '.wpr-grid-filters' ).next().find('article').length );
							} else {
								$(this).find( 'sup' ).text( $scope.find( $(this).attr( 'data-filter' ) ).length );
							}
						}
					});
				}

				// Return if Disabled
				if ( 'yes' === settings.filters_linkable ) {
					return;
				}

				// Deeplinking on Load
				if ( 'yes' === settings.deeplinking ) {
					var deepLink = window.location.hash.replace( '#filter:', '.' );

					if ( window.location.hash.match( '#filter:all' ) ) {
						deepLink = '*';
					}

					var activeFilter = $scope.find( '.wpr-grid-filters span[data-filter="'+ deepLink +'"]:not(.wpr-back-filter)' ),
						activeFilterWrap = activeFilter.parent();

					// Sub Filters
					if ( 'parent' === activeFilter.parent().attr( 'data-role' ) ) {
						if ( activeFilterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ deepLink +'"]').length ) {
							activeFilterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
							activeFilterWrap.siblings( 'ul[data-parent="'+ deepLink +'"]' ).css( 'display', 'block' );
						}
					} else if ( 'sub' === activeFilter.parent().attr( 'data-role' ) ) {
						activeFilterWrap.closest( '.wpr-grid-filters' ).children( 'li' ).css( 'display', 'none' );
						activeFilterWrap.parent( 'ul' ).css( 'display', 'inline-block' );
					}

					// Active Filter Class
					$scope.find( '.wpr-grid-filters span' ).removeClass( 'wpr-active-filter' );
					activeFilter.addClass( 'wpr-active-filter' );

					$scope.find( '.wpr-grid' ).isotopewpr({ filter: deepLink });

					// Fix Lightbox
					if ( '*' !== deepLink ) {
						settings.lightbox.selector = deepLink +' .wpr-grid-image-wrap';
					} else {
						settings.lightbox.selector = ' .wpr-grid-image-wrap';
					}

					WprElements.lightboxPopup( settings, $scope, iGrid );
				}

				// Hide Empty Filters
				if ( 'yes' === settings.filters_hide_empty ) {
					$scope.find( '.wpr-grid-filters span' ).each(function() {
						var searchClass = $(this).attr( 'data-filter' );

						if ( '*' !== searchClass ) {
							if ( 0 === iGrid.find(searchClass).length ) {
								$(this).parent( 'li' ).addClass( 'wpr-hidden-element' );
							} else {
								$(this).parent( 'li' ).removeClass( 'wpr-hidden-element' );
							}
						}
					});
				}

				// Set a Default Filter
				if ( !$scope.hasClass('elementor-widget-wpr-woo-category-grid-pro') && !$scope.hasClass('elementor-widget-wpr-category-grid-pro') ) {
					if ( '' !== settings.filters_default_filter ) {
						setTimeout(function() {
							$scope.find( '.wpr-grid-filters' ).find('span[data-filter*="-'+ settings.filters_default_filter +'"]')[0].click();
						}, 100)
					}
				}

				// Click Event
				if ( !settings.grid_settings ) {
					$scope.find( '.wpr-grid-filters span' ).on( 'click', function() {
					WprElements.changeInitialItems(0, scopeId);

					// Check why this was removed
					var filterClass = $(this).data( 'filter' ),
						filterWrap = $(this).parent( 'li' ),
						filterRole = filterWrap.attr( 'data-role' );
	
						// Active Filter Class
						$scope.find( '.wpr-grid-filters span' ).removeClass( 'wpr-active-filter' );
						$(this).addClass( 'wpr-active-filter' );
	
						// Sub Filters
						if ( 'parent' === filterRole ) {
							if ( filterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ filterClass +'"]' ).length ) {
								filterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
								filterWrap.siblings( 'ul[data-parent="'+ filterClass +'"]' ).css( 'display', 'block' );
							}
						} else if ( 'back' === filterRole ) {
							filterWrap.closest( '.wpr-grid-filters' ).children( 'li' ).css( 'display', 'inline-block' );
							filterWrap.parent().css( 'display', 'none' );
						}
	
						// Deeplinking
						if ( 'yes' === settings.deeplinking ) {
							var filterHash = '#filter:'+ filterClass.replace( '.', '' );
	
							if ( '*' === filterClass ) {
								filterHash = '#filter:all';
							}
	
							window.location.href = window.location.pathname + window.location.search + filterHash;
						}
	
						// Infinite Scroll
						if ( 'infinite-scroll' === settings.pagination_type ) {
							if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
								$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
							}
						}
	
						// Load More
						if ( 'load-more' === settings.pagination_type ) {
							if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
								$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
							}
						}
	
						// Filtering Animation
						if ( 'default' !== settings.filters_animation ) {
							$scope.find( '.wpr-grid-item-inner' ).css({
								'opacity' : '0',
								'transition' : 'none'
							});
						}
	
						if ( 'fade-slide' === settings.filters_animation ) {
							$scope.find( '.wpr-grid-item-inner' ).css( 'top', '20px' );
						} else if ( 'zoom' === settings.filters_animation ) {
							$scope.find( '.wpr-grid-item-inner' ).css( 'transform', 'scale(0.01)' );
						} else {
							$scope.find( '.wpr-grid-item-inner' ).css({
								'top' : '0',
								'transform' : 'scale(1)'
							});
						}
	
						// Filter Grid Items
						$scope.find( '.wpr-grid' ).isotopewpr({ filter: filterClass });
	
						// Fix Lightbox
						if ( '*' !== filterClass ) {
							settings.lightbox.selector = filterClass +' .wpr-grid-image-wrap';
						} else {
							settings.lightbox.selector = ' .wpr-grid-image-wrap';
						}
	
						// Destroy Lightbox
						iGrid.data('lightGallery').destroy( true );
						// Init Lightbox
						iGrid.lightGallery( settings.lightbox );
					});
				}
			}

			function checkWishlistAndCompare() {
				var wishlistArray;
				
				if ( iGrid.find('.wpr-wishlist-add').length ) {

					$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'check_product_in_wishlist_grid',
							},
							success: function(response) {
									wishlistArray = response;
							}
					});
					
					
					iGrid.find('.wpr-wishlist-add').each(function() {
						var wishlistBtn = $(this);
						
						if ( $.inArray(wishlistBtn.data('product-id'), wishlistArray) !== -1 ) {
							if ( !wishlistBtn.hasClass('wpr-button-hidden') ) {
								wishlistBtn.addClass('wpr-button-hidden');
							}

							if ( wishlistBtn.next().hasClass('wpr-button-hidden') ) {
								wishlistBtn.next().removeClass('wpr-button-hidden');
							}
						}
					});
				}

				if ( iGrid.find('.wpr-compare-add').length > 0 ) {
					var compareArray = [];
					
					$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'check_product_in_compare_grid',
							},
							success: function(response) {
								compareArray = response;
							},
							error: function(error) {
								console.log(error);
							}
					});
				
					
					iGrid.find('.wpr-compare-add').each(function() {
						var compareBtn = $(this);
						
						if ( $.inArray(compareBtn.data('product-id'), compareArray) !== -1 ) {
							if ( !compareBtn.hasClass('wpr-button-hidden') ) {
								compareBtn.addClass('wpr-button-hidden');
							}

							if ( compareBtn.next().hasClass('wpr-button-hidden') ) {
								compareBtn.next().removeClass('wpr-button-hidden');
							}
						}
					});
					
				}
			}

			function addRemoveCompare() {
				if ( iGrid.find('.wpr-compare-add').length ) {
					$scope.find('.wpr-compare-add').click(function(e) {
						e.preventDefault();
						var event_target = $(this);
						var product_id = $(this).data('product-id');

						event_target.fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
                                action: 'add_to_compare',
                                nonce: WprConfig.nonce,
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('added_to_compare');
	
								if ( 'sidebar' === event_target.data('atcompare-popup') ) {
									// GOGA - configure after adding compare dropdown functinality
									if ( $('.wpr-compare-toggle-btn').length ) {
										$('.wpr-compare-toggle-btn').each(function() {
											if ( 'none' === $(this).next('.wpr-compare').css('display') ) {
												$(this).trigger('click');
											}
										});
									}
								} else if ( 'popup' === event_target.data('atcompare-popup') ) {
									// Popup Link needs wishlist
									var popupItem = event_target.closest('.wpr-grid-item'),
										popupText = popupItem.find('.wpr-grid-item-title').text(),
										popupLink = WprConfig.comparePageURL,
										popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
										popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
										popupAnimation = event_target.data('atcompare-animation'),
										fadeOutIn = event_target.data('atcompare-fade-out-in'),
										animTime = event_target.data('atcompare-animation-time'),
										popupImage,
										animationClass = 'wpr-added-to-compare-default',
										removeAnimationClass;
			
									if ( 'slide-left' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-slide-in-left';
										removeAnimationClass = 'wpr-added-to-compare-slide-out-left';
									} else if ( 'scale-up' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-scale-up';
										removeAnimationClass = 'wpr-added-to-compare-scale-down';
									} else if ( 'skew' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-skew';
										removeAnimationClass = 'wpr-added-to-compare-skew-off';
									} else if ( 'fade' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-fade';
										removeAnimationClass = 'wpr-added-to-compare-fade-out';
									} else {
										removeAnimationClass = 'wpr-added-to-compare-popup-hide';
									}
			
									if ( '' !== popupImageSrc ) {
										popupImage = '<div class="wpr-added-tcomp-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
									} else {
										popupImage = '';
									}
									
									if ( !($scope.find('.wpr-grid').find('#wpr-added-to-comp-'+product_id).length > 0) ) {
										$scope.find('.wpr-grid').append('<div id="wpr-added-to-comp-'+product_id+'" class="wpr-added-to-compare-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tc-title"><p>'+ popupText +' was added to Compare</p><p><a target='+ popupTarget +' href='+popupLink+'>View Compare</a></p></div></div>');
			
										setTimeout(() => {
											$scope.find('#wpr-added-to-comp-'+product_id).addClass(removeAnimationClass);
											setTimeout(() => {
												$scope.find('#wpr-added-to-comp-'+product_id).remove();
											}, animTime * 1000);
										}, fadeOutIn * 1000);
									}
								}
							},
							error: function(response) {
								var error_message = response.responseJSON.message;
								// Display error message
								alert(error_message);
							}
						});
					});
	
					$scope.find('.wpr-compare-remove').click(function(e) {
						e.preventDefault();
						var product_id = $(this).data('product-id');
						$(this).fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
                                action: 'remove_from_compare',
                                nonce: WprConfig.nonce,
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('removed_from_compare');
							}
						});
					});
	
					$(document).on('removed_from_compare', function() {
						$scope.find('.wpr-compare-remove[data-product-id="' + actionTargetProductId + '"]').hide();
						$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').show();
						$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
					});
	
				}
			}

			function addRemoveWishlist() {
				let isPopupActive = false;
				if ( iGrid.find('.wpr-wishlist-add').length ) {
					$scope.find('.wpr-wishlist-add').click(function(e) {
						e.preventDefault();
						var event_target = $(this);
						var product_id = $(this).data('product-id');

						event_target.fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
                                action: 'add_to_wishlist',
                                nonce: WprConfig.nonce,
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('added_to_wishlist');
	
								if ( 'sidebar' === event_target.data('atw-popup') ) {
									// GOGA - configure after adding wishlist dropdown functinality
									if ( $('.wpr-wishlist-toggle-btn').length ) {
										$('.wpr-wishlist-toggle-btn').each(function() {
											if ( 'none' === $(this).next('.wpr-wishlist').css('display') ) {
												$(this).trigger('click');
											}
										});
									}
								} else if ( 'popup' === event_target.data('atw-popup') ) {
									// Popup Link needs wishlist
									var popupItem = event_target.closest('.wpr-grid-item'),
										popupText = popupItem.find('.wpr-grid-item-title').text(),
										popupLink = WprConfig.wishlistPageURL,
										popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
										popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
										popupAnimation = event_target.data('atw-animation'),
										fadeOutIn = event_target.data('atw-fade-out-in'),
										animTime = event_target.data('atw-animation-time'),
										popupImage,
										animationClass = 'wpr-added-to-wishlist-default',
										removeAnimationClass;
			
									if ( 'slide-left' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-slide-in-left';
										removeAnimationClass = 'wpr-added-to-wishlist-slide-out-left';
									} else if ( 'scale-up' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-scale-up';
										removeAnimationClass = 'wpr-added-to-wishlist-scale-down';
									} else if ( 'skew' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-skew';
										removeAnimationClass = 'wpr-added-to-wishlist-skew-off';
									} else if ( 'fade' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-fade';
										removeAnimationClass = 'wpr-added-to-wishlist-fade-out';
									} else {
										removeAnimationClass = 'wpr-added-to-wishlist-popup-hide';
									}
			
									if ( '' !== popupImageSrc ) {
										popupImage = '<div class="wpr-added-tw-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
									} else {
										popupImage = '';
									}
									if (!isPopupActive) {
										isPopupActive = true;
										
										if ( !($scope.find('.wpr-grid').find('#wpr-added-to-wish-'+product_id).length > 0) ) {
											$scope.find('.wpr-grid').append('<div id="wpr-added-to-wish-'+product_id+'" class="wpr-added-to-wishlist-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tw-title"><p>'+ popupText +' was added to Wishlist</p><p><a target="'+ popupTarget +'" href='+popupLink+'>View Wishlist</a></p></div></div>');
				
											setTimeout(() => {
												$scope.find('#wpr-added-to-wish-'+product_id).addClass(removeAnimationClass);
												setTimeout(() => {
													$scope.find('#wpr-added-to-wish-'+product_id).remove();
												}, animTime * 1000);
											}, fadeOutIn * 1000);
										}
									}
								}
							},
							error: function(response) {
								var error_message = response.responseJSON.message;
								// Display error message
								alert(error_message);
							}
						});
					});
	
					$scope.find('.wpr-wishlist-remove').on('click', function(e) {
						e.preventDefault();
						var product_id = $(this).data('product-id');

						$(this).fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
                                action: 'remove_from_wishlist',
                                nonce: WprConfig.nonce,
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('removed_from_wishlist');
							}
						});
					});
	
					$(document).on('removed_from_wishlist', function() {
						$scope.find('.wpr-wishlist-remove[data-product-id="' + actionTargetProductId + '"]').hide();
						$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').show();
						$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
					});
	
				}	
			}

			function filtersExperiment() {
				let currentRequest = null;
				$scope.find('.wpr-grid-filters').on('click', 'span', function(event) {
					if (currentRequest) {
						currentRequest.abort(); // Abort the previous request
					}

					pagesLoadedExperiment = 0;
					var thisTaxonomy,
						thisFilter,
                        loader = '<div class="wpr-grid-loader-wrap"><div class="wpr-ring"><div></div><div></div><div></div><div></div></div></div>';
						
					var filterClass = $(this).data( 'filter' ),
						filterWrap = $(this).parent( 'li' ),
						filterRole = filterWrap.attr( 'data-role' );
					
					if ( $(this).data('filter') !== '*' ) {
						thisTaxonomy = $(this).data('ajax-filter')[0];
						thisFilter = $(this).data('ajax-filter')[1];
					} else {
						thisTaxonomy = '*';
						thisFilter = '*';
					}
	
					// Sub Filters
					if ( 'parent' === filterRole ) {
						if ( filterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ filterClass +'"]').length ) {
							filterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
							filterWrap.siblings( 'ul[data-parent="'+ filterClass +'"]' ).css( 'display', 'block' );
						}
					} else if ( 'back' === filterRole ) {
						filterWrap.closest( '.wpr-grid-filters' ).children( 'li' ).css( 'display', 'inline-block' );
						filterWrap.parent().css( 'display', 'none' );
					}

					$scope.find('.wpr-grid-filters span').removeClass('wpr-active-filter');
					$(this).addClass('wpr-active-filter');

					event.preventDefault();
					event.stopPropagation();
					event.stopImmediatePropagation();

					iGrid.isotopewpr('remove', iGrid.children('.wpr-grid-item'));
					if ( iGrid.find('.wpr-grid-loader-wrap').length < 1 ) {
						iGrid.append(loader);
					}
					const start = performance.now();
					$.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: {
							action: experimentActionCount,
							nonce: WprConfig.nonce,
							// wpr_offset: +settings.grid_settings.query_offset + $scope.find('.wpr-grid-item').length,
							wpr_offset: +settings.grid_settings.query_offset,
							wpr_filter: thisFilter,
							wpr_taxonomy: thisTaxonomy,
							grid_settings: settings.grid_settings,
						},
						success: function(response) {
							var pageCount = response.data.page_count;
							var foundPosts = response.data.query_found;
							const end = performance.now();
							// console.log(`AJAX call took ${end - start} ms`);

							const start2 = performance.now();
							currentRequest = $.ajax({
								type: 'POST',
								url: WprConfig.ajaxurl,
								data: {
									action: experimentActionContent,
									nonce: WprConfig.nonce,
									wpr_item_length: +settings.grid_settings.query_offset + $scope.find('.wpr-grid-item').length,
									wpr_filter: thisFilter,
									wpr_taxonomy: thisTaxonomy,
									grid_settings: settings.grid_settings,
								},
								success: function( response ) {
									setTimeout(function() {
										// iGrid.addClass('wpr-zero-opacity');
										// $scope.find('.wpr-grid').html($(response));
										// WprElements.isotopeLayout( settings, '', '', $scope );
										const end2 = performance.now();
										// console.log(`AJAX call took ${end2 - start2} ms`);

										var newItems;

										if ( response.data && response.data.output ) {
											newItems = $(response.data.output);
										} else {
											newItems = $(response);
										}
										
										iGrid.find('.wpr-grid-loader-wrap').remove();
										iGrid.addClass('wpr-fix-grid-height');
										iGrid.append(newItems).isotopewpr('appended', newItems).isotopewpr('layout');
										setTimeout(function() {
											iGrid.removeClass('wpr-fix-grid-height');
										}, 500);

										if ( settings.pagination_type == 'load-more' && pagination ) { // needs check if more posts in tax
											pagination.find( '.wpr-pagination-finish' ).fadeOut( 100 );
											pagination.delay( 2000 ).fadeIn( 100 );
											
											if ( pageCount <= 1 && foundPosts <= newItems.length ) {
												// if ( items.length !== 0 ) {}
												if ( 'load-more' === settings.pagination_type ) {
													pagination.find( '.wpr-load-more-btn' ).fadeOut();
												}
											}
											
											if ( pageCount > 1 ) {
												// if ( items.length !== 0 ) {}
												if ( 'load-more' === settings.pagination_type ) {
													pagination.find( '.wpr-load-more-btn' ).fadeIn();
												}
											}
										}

										WprElements.isotopeLayout( settings, '', '', $scope );
										WprElements.lightboxPopup( settings, $scope, iGrid );
			
										// Fix Lightbox
										iGrid.data( 'lightGallery' ).destroy( true );
										iGrid.lightGallery( settings.lightbox );

                                        setTimeout(function() {
                                            WprElements.isotopeLayout( settings, '', '', $scope );

											window.dispatchEvent(new Event('resize'));
											window.dispatchEvent(new Event('scroll'));
                                        }, 500);
										
										WprElements.mediaHoverLink($scope, iGrid);
										lazyLoadObserver();
										// iGrid.removeClass('wpr-zero-opacity');
										WprElements.changeInitialItems(0, scopeId);
									}, 800);
								},
								error: function(error) {
									console.log(error);
								}
							});
						},
						error: function(error) {
							console.log(error);
						}
					});
				});
			}

			function loadMoreExperiment() {
				$scope.find('.wpr-load-more-btn').on('click', function(event) {
					event.preventDefault();
					event.stopPropagation();

					var thisTaxonomy,
						thisFilter;
						
					if ( $scope.find('.wpr-active-filter').length && $scope.find('.wpr-active-filter').data('filter') !== '*' ) {
						thisTaxonomy = $scope.find('.wpr-active-filter').data('ajax-filter')[0];
						thisFilter = $scope.find('.wpr-active-filter').data('ajax-filter')[1];
					} else {
						thisTaxonomy = '*';
						thisFilter = '*';
					}

					event.preventDefault();
					event.stopPropagation();
					event.stopImmediatePropagation();

					// Store the current scroll position
					var scrollTop = $(window).scrollTop();
					
					pagination.find( '.wpr-load-more-btn' ).hide();
					pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );

					$.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: {
							action: experimentActionCount,
							nonce: WprConfig.nonce,
							wpr_offset: +settings.grid_settings.query_offset + $scope.find('.wpr-grid-item').length,
							wpr_filter: thisFilter,
							wpr_taxonomy: thisTaxonomy,
							grid_settings: settings.grid_settings,
						},
						success: function(response) {
							var pageCount = response.data.page_count;

							$.ajax({
								type: 'POST',
								url: WprConfig.ajaxurl,
								data: {
									action: experimentActionContent,
									nonce: WprConfig.nonce,
									wpr_offset: +settings.grid_settings.query_offset + $scope.find('.wpr-grid-item').length,
									wpr_filter: thisFilter,
									wpr_taxonomy: thisTaxonomy,
									grid_settings: settings.grid_settings,
								},
								success: function( response ) {
									pagesLoadedExperiment++;
									var items;
									// iGrid.css('opacity', 0);
									if ( response.data && response.data.output ) {
										items = $(response.data.output);
									} else {
										items = $(response);
									}
		
									// $data.each(function() {
									// 	$(this).addClass('wpr-grid-hidden-item');
									// });

									iGrid.infiniteScroll( 'appendItems', items );
									iGrid.isotopewpr( 'appended', items );
									// isotopeFilters( settings ); // GOGA - if not images loaded
		
									items.imagesLoaded().progress( function( instance, image ) {
										WprElements.isotopeLayout( settings, '', '', $scope );
			
										// Fix Layout
										setTimeout(function() {
											WprElements.isotopeLayout( settings, '', '', $scope );
											// isotopeFilters( settings );
										}, 100 );
							
										setTimeout(function() {
											iGrid.addClass( 'grid-images-loaded' );
										}, 500 );
									});
			
									// Loading
									pagination.find( '.wpr-pagination-loading' ).hide();
									if ( pageCount > 1 ) {
									// if ( items.length !== 0 ) {
										if ( 'load-more' === settings.pagination_type ) {
											pagination.find( '.wpr-load-more-btn' ).fadeIn();
										}
									} else {
										pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
										pagination.delay( 2000 ).fadeOut( 1000 );
										setTimeout(function() {
											pagination.find( '.wpr-pagination-loading' ).hide();
										}, 500 );
									}
			
									// Init Likes
									// No need for this anymore
									// setTimeout(function() {
									// 	postLikes( settings );
									// }, 300 );
			
									// Init Lightbox
									WprElements.lightboxPopup( settings, $scope, iGrid );
			
									// Fix Lightbox
									iGrid.data( 'lightGallery' ).destroy( true );
									iGrid.lightGallery( settings.lightbox );
			
									// Init Media Hover Link
									WprElements.mediaHoverLink($scope, iGrid);
			
									// Init Post Sharing
									postSharing();
			
									lazyLoadObserver();

									// Maybe there is some other way
									window.dispatchEvent(new Event('resize'));
									window.dispatchEvent(new Event('scroll'));
									$(window).trigger('scroll');
								},
								error: function(error) {
									console.log(error);
								}
							});
						}
					});
				});
			}
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-grid.default', handler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-media-grid.default', handler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-woo-grid.default', handler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-woo-category-grid-pro.default', handler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-category-grid-pro.default', handler );
	});

})(jQuery);
