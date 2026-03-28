(function($) {
	"use strict";

	var handler = function($scope) {

			var $navMenu = $scope.find( '.wpr-nav-menu-container' ),
				$mobileNavMenu = $scope.find( '.wpr-mobile-nav-menu-container' );

			// Menu
			var subMenuFirst = $navMenu.find( '.wpr-nav-menu > li.menu-item-has-children' ),
				subMenuDeep = $navMenu.find( '.wpr-sub-menu li.menu-item-has-children' );

			if ( $scope.find('.wpr-mobile-toggle').length ) {
				$scope.find('a').on('click', function() { // GOGA - sub mega menu condition needs testing
                    if ( this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)  && !($(this).closest('.wpr-sub-mega-menu').length > 0) ) {
						$scope.find('.wpr-mobile-toggle').trigger('click');
					}
				});
			}

			// Click
			if ( $navMenu.attr('data-trigger') === 'click' ) {

				// First Sub
				subMenuFirst.children('a').on( 'click', function(e) {
					var currentItem = $(this).parent(),
						childrenSub = currentItem.children('.wpr-sub-menu, .wpr-sub-mega-menu');

					// Reset
					subMenuFirst.not(currentItem).removeClass('wpr-sub-open');
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') || ( $navMenu.hasClass('wpr-nav-menu-vertical') ) ) {
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );
					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Deep Subs
				subMenuDeep.on( 'click', function(e) {
					var currentItem = $(this),
						childrenSub = currentItem.children('.wpr-sub-menu');

					// Reset
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') ) {
						subMenuAnimation( subMenuDeep.find('.wpr-sub-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );

					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Reset Subs on Document click
				$( document ).mouseup(function (e) {
					if ( ! subMenuFirst.is(e.target) && subMenuFirst.has(e.target).length === 0 ) {
						subMenuFirst.not().removeClass('wpr-sub-open');
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
					}
					if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
						subMenuDeep.removeClass('wpr-sub-open');
						subMenuAnimation( subMenuDeep.children('.wpr-sub-menu'), false );
					}
				});
			
			// Hover
			} else {
				// Mouse Over
				subMenuFirst.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu, .wpr-sub-mega-menu'), true );
				});

				subMenuDeep.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});

				// Mouse Leave
				subMenuFirst.on( 'mouseleave', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
				});

				subMenuDeep.on( 'mouseleave', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
				});	
			}

			// Mobile Menu
			var mobileMenu = $mobileNavMenu.find( '.wpr-mobile-nav-menu' );

			// Toggle Button
			$mobileNavMenu.find( '.wpr-mobile-toggle' ).on( 'click', function(e) {

				if ( window.getComputedStyle($mobileNavMenu[0])['pointer-events'] === 'none' ) {
					return;
				}

				// Change Toggle Text
				if ( ! $(this).hasClass('wpr-mobile-toggle-open') ) {
					$(this).addClass('wpr-mobile-toggle-open');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(0).hide();
						$(this).children().eq(1).show();
					}
				} else {
					$(this).removeClass('wpr-mobile-toggle-open');
					$(this).trigger('focusout');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(1).hide();
						$(this).children().eq(0).show();
					}
				}

				// Show Menu
				if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
					$(this).closest('.elementor-top-section').addClass('wpr-section-full-height');
					$('body').css('overflow', 'hidden');
					$(this).parent().siblings('.wpr-mobile-mega-menu-wrap').toggleClass('wpr-mobile-mega-menu-open');
				} else {
					$(this).parent().siblings('.wpr-mobile-mega-menu-wrap').stop().slideToggle();
				}

				// Hide Off-Canvas Menu
				$scope.find('.mobile-mega-menu-close').on('click', function() {
					$(this).closest('.wpr-mobile-mega-menu-wrap').removeClass('wpr-mobile-mega-menu-open');
					$('body').css('overflow', 'visible');
					$(this).closest('.elementor-top-section').removeClass('wpr-section-full-height');
				});
				$scope.find('.wpr-mobile-mega-menu-overlay').on('click', function() {
					$(this).siblings('.wpr-mobile-mega-menu-wrap').removeClass('wpr-mobile-mega-menu-open');
					$('body').css('overflow', 'visible');
					$(this).closest('.elementor-top-section').removeClass('wpr-section-full-height');
				});

				// Fix Width
				fullWidthMobileDropdown();
			});

			// Sub Menu Class
			mobileMenu.find('.sub-menu').removeClass('wpr-sub-menu').addClass('wpr-mobile-sub-menu');

			// Add Submenu Icon
			let mobileSubIcon = mobileMenu.find('.wpr-mobile-sub-icon'),
				mobileSubIconClass = 'fas ';

			if ( $scope.hasClass('wpr-sub-icon-caret-down') ) {
				mobileSubIconClass += 'fa-caret-down';
			} else if ( $scope.hasClass('wpr-sub-icon-angle-down') ) {
				mobileSubIconClass += 'fa-angle-down';
			} else if ( $scope.hasClass('wpr-sub-icon-chevron-down') ) {
				mobileSubIconClass += 'fa-chevron-down';
			} else if ( $scope.hasClass('wpr-sub-icon-plus') ) {
				mobileSubIconClass += 'fa-plus';
			}

			mobileSubIcon.addClass(mobileSubIconClass);

			// Sub Menu Dropdown
			mobileMenu.find('.menu-item-has-children > a .wpr-mobile-sub-icon, .menu-item-has-children > a[href="#"]').on( 'click', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var parentItem = $(this).closest('li.menu-item');

				// Toggle
				if ( ! parentItem.hasClass('wpr-mobile-sub-open') ) {
					e.preventDefault();
					parentItem.addClass('wpr-mobile-sub-open');

					if ( ! $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
						$(window).trigger('resize');
						parentItem.children('.wpr-mobile-sub-menu').first().stop().slideDown();
					}

					// Mega Menu
					if ( parentItem.hasClass('wpr-mega-menu-true') ) {
						if ( parentItem.hasClass('wpr-mega-menu-ajax') && ! parentItem.find('.wpr-mobile-sub-mega-menu').find('.elementor').length  ) {
							let subIcon = parentItem.find('.wpr-mobile-sub-icon');

							$.ajax({
								type: 'GET',
								url: WprConfig.resturl + '/wprmegamenu/',
								data: {
									item_id: parentItem.data('id')
								},
								beforeSend:function() {
									subIcon.removeClass(mobileSubIconClass).addClass('fas fa-circle-notch fa-spin');
								},
								success: function( response ) {
									subIcon.removeClass('fas fa-circle-notch fa-spin').addClass(mobileSubIconClass);

									if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
										parentItem.find('.wpr-menu-offcanvas-back').after(response);
										offCanvasSubMenuAnimation( parentItem );
									} else {
										parentItem.find('.wpr-mobile-sub-mega-menu').html(response);
										parentItem.children('.wpr-mobile-sub-mega-menu').slideDown();
									}

									parentItem.find('.wpr-mobile-sub-mega-menu').find('.elementor-element').each(function() {
										elementorFrontend.elementsHandler.runReadyTrigger($(this));
									});
								}
							});
						} else {
							if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
								offCanvasSubMenuAnimation( parentItem );
							} else {
								parentItem.children('.wpr-mobile-sub-mega-menu').slideDown();
							}
						}
					} else {
						if (  $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
							offCanvasSubMenuAnimation( parentItem );
						}	
					}
				
				} else {
					// SlideUp
					parentItem.removeClass('wpr-mobile-sub-open');

					if ( ! $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
						parentItem.children('.wpr-mobile-sub-menu').slideUp();
						parentItem.children('.wpr-mobile-sub-mega-menu').slideUp();
					}
				}
			});

			// Off-Canvas Back Button
			$scope.find('.wpr-menu-offcanvas-back').on('click', function() {
				$(this).closest('.wpr-mobile-mega-menu').removeClass('wpr-mobile-sub-offcanvas-open');
				$(this).closest('.menu-item').removeClass('wpr-mobile-sub-open');
				$scope.find('.wpr-mobile-mega-menu-wrap').removeAttr('style');
                $scope.find('.wpr-mobile-sub-mega-menu').removeAttr('style');
			});

			// Run Functions
			MegaMenuCustomWidth();
			fullWidthMobileDropdown();

			// Run Functions on Resize
			$(window).smartresize(function() {
				MegaMenuCustomWidth();
				fullWidthMobileDropdown();
			});

			// Mega Menu Full or Custom Width
			function MegaMenuCustomWidth() {
				let megaItem = $scope.find('.wpr-mega-menu-true');

				megaItem.each(function() {
					let megaSubMenu = $(this).find('.wpr-sub-mega-menu')

					if ( $(this).hasClass('wpr-mega-menu-width-full') ) {
						megaSubMenu.css({
							'max-width' : $(window).width() +'px',
							'left' : - $scope.find('.wpr-nav-menu-container').offset().left +'px'
						});	// conditions for sticky replace needed
					} else if ( $(this).hasClass('wpr-mega-menu-width-stretch') ) {
						// Sections (Old)
						if ( 0 === $(this).closest('.e-con').length ) {
							var elContainer = $(this).closest('.elementor-section');
								elContainer = elContainer.hasClass('elementor-inner-section') ? elContainer : elContainer.children('.elementor-container');

							var elWidgetGap = !elContainer.hasClass('elementor-inner-section') ? elContainer.find('.elementor-element-populated').css('padding') : '0';
								elWidgetGap = parseInt(elWidgetGap.replace('px', ''), 10);

						// Container (New)
						} else {
							var elContainer = $(this).closest('.e-con-inner');

							var elWidgetGap = elContainer.find('.elementor-element.e-con').css('padding'),
								elWidgetGap = parseInt(elWidgetGap, 10);
						}
						
						if ( elContainer.length === 0 ) {
							return;
						}

						var elContainerWidth = elContainer.outerWidth() - (elWidgetGap * 2),
							offsetLeft = -($scope.offset().left - elContainer.offset().left) + elWidgetGap;

						megaSubMenu.css({
							'width' : elContainerWidth +'px',
							'left' : offsetLeft +'px'
						});
					} else if ( $(this).hasClass('wpr-mega-menu-width-custom') ) {
						megaSubMenu.css({
							'width' : $(this).data('custom-width') +'px',
						});
					} else if ( $(this).hasClass('wpr-mega-menu-width-default') && $(this).hasClass('wpr-mega-menu-pos-relative') ) {
						megaSubMenu.css({
							'width' : $(this).closest('.elementor-column').outerWidth() +'px',
						});
					}
				});
			}

			// Full Width Dropdown
			function fullWidthMobileDropdown() {
				if ( ! $scope.hasClass( 'wpr-mobile-menu-full-width' ) || (! $scope.closest('.elementor-column').length && ! $scope.closest('.e-con').length) ) {
					return;
				}
				
				var topSection = $scope.closest('.elementor-top-section').length ? $scope.closest('.elementor-top-section') : $scope.closest('.e-con-inner');

				var eColumn   =$scope.closest('.elementor-column').length ? $scope.closest('.elementor-column') : $scope.closest('.elementor-element'),
					mWidth 	  = topSection.outerWidth() - 2 * mobileMenu.offset().left,
					mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

				if ( topSection.hasClass('e-con-inner') ) {
					mPosition = eColumn.offset().left - 2 * parseInt(topSection.parent().css('padding-left'), 10);
					mWidth = topSection.outerWidth() - 2 * parseInt(topSection.parent().css('padding-left'), 10);
				}

				mobileMenu.parent('div').css({
					'width' : mWidth +'px',
					'left' : - mPosition +'px'
				});
			}

			// Sub Menu Animation
			function subMenuAnimation( selector, show ) {
				if ( show === true ) {
					selector.stop().addClass('wpr-animate-sub');
			} else {
					selector.stop().removeClass('wpr-animate-sub');
				}
			}

			// Off-Canvas Sub Menu Animation
			function offCanvasSubMenuAnimation( selector ) {
				let title = selector.children('a').clone().children().remove().end().text();

				selector.closest('.wpr-mobile-mega-menu').addClass('wpr-mobile-sub-offcanvas-open');
				
				if (selector.find('.wpr-menu-offcanvas-back').find('h3').length > 0) {
					selector.find('.wpr-menu-offcanvas-back').find('h3').text(title);
				} else {
					selector.find('.wpr-menu-offcanvas-back').append('<h3></h3>').find('h3').text(title);
				}

				let parentItem = $scope.find('.wpr-mobile-mega-menu').children('.wpr-mobile-sub-open'),
				    subSelector = parentItem.children('ul').length ? parentItem.children('ul') : parentItem.children('.wpr-mobile-sub-mega-menu'),
				    subHeight = subSelector.outerHeight();

				if ( subHeight > $(window).height() ) {
                    $scope.find('.wpr-mobile-sub-mega-menu').not(selector.find('.wpr-mobile-sub-mega-menu')).hide();
					$scope.find('.wpr-mobile-mega-menu-wrap').css('overflow-y', 'scroll');
				}
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-mega-menu.default', handler );
	});

})(jQuery);
