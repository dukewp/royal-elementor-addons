(function($) {
	"use strict";

	var handler = function($scope) {

			var $navMenu = $scope.find( '.wpr-nav-menu-container' ),
				$mobileNavMenu = $scope.find( '.wpr-mobile-nav-menu-container' );

			// Menu
			var subMenuFirst = $navMenu.find( '.wpr-nav-menu > li.menu-item-has-children' ),
				subMenuDeep = $navMenu.find( '.wpr-sub-menu li.menu-item-has-children' );

			if ( $scope.find('.wpr-mobile-toggle').length ) {
				$scope.find('a').on('click', function() {
					if (this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)) {
						$scope.find('.wpr-mobile-toggle').trigger('click');
					}
				});
			}

			if ( $navMenu.attr('data-trigger') === 'click' ) {
				// First Sub
				subMenuFirst.children('a').on( 'click', function(e) {
					var currentItem = $(this).parent(),
						childrenSub = currentItem.children('.wpr-sub-menu');

					// Reset
					subMenuFirst.not(currentItem).removeClass('wpr-sub-open');
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') || ( $navMenu.hasClass('wpr-nav-menu-vertical') && $scope.hasClass('wpr-sub-menu-position-absolute') ) ) {
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu'), false );
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
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu'), false );
					}
					if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
						subMenuDeep.removeClass('wpr-sub-open');
						subMenuAnimation( subMenuDeep.children('.wpr-sub-menu'), false );
					}
				});
			} else {
				// Mouse Over
				subMenuFirst.on( 'mouseenter', function() {
					if ( $navMenu.hasClass('wpr-nav-menu-vertical') && $scope.hasClass('wpr-sub-menu-position-absolute') ) {
						$navMenu.find('li').not(this).children('.wpr-sub-menu').hide();
						// BUGFIX: when menu is vertical and absolute positioned, lvl2 depth sub menus wont show properly on hover
					}

					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});

				// Deep Subs
				subMenuDeep.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});


				// Mouse Leave
				if ( $navMenu.hasClass('wpr-nav-menu-horizontal') ) {
					subMenuFirst.on( 'mouseleave', function() {
						subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
					});

					subMenuDeep.on( 'mouseleave', function() {
						subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
					});	
				} else {

					$navMenu.on( 'mouseleave', function() {
						subMenuAnimation( $(this).find('.wpr-sub-menu'), false );
					});
				}
			}


			// Mobile Menu
			var mobileMenu = $mobileNavMenu.find( '.wpr-mobile-nav-menu' );

			// Toggle Button
			$mobileNavMenu.find( '.wpr-mobile-toggle' ).on( 'click', function() {
				$(this).toggleClass('wpr-mobile-toggle-fx');

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
				$(this).parent().next().stop().slideToggle();

				// Fix Width
				fullWidthMobileDropdown();
			});

			// Sub Menu Class
			mobileMenu.find('.sub-menu').removeClass('wpr-sub-menu').addClass('wpr-mobile-sub-menu');

			// Sub Menu Dropdown
			mobileMenu.find('.menu-item-has-children').children('a').on( 'click', function(e) {
				var parentItem = $(this).closest('li');

				// Toggle
				if ( ! parentItem.hasClass('wpr-mobile-sub-open') ) {
					e.preventDefault();
					parentItem.addClass('wpr-mobile-sub-open');
					parentItem.children('.wpr-mobile-sub-menu').first().stop().slideDown();
				} else {
					parentItem.removeClass('wpr-mobile-sub-open');
					parentItem.children('.wpr-mobile-sub-menu').first().stop().slideUp();
				}
			});

			// Run Functions
			fullWidthMobileDropdown();

			// Run Functions on Resize
			$(window).smartresize(function() {
				fullWidthMobileDropdown();
			});

			// Full Width Dropdown
			function fullWidthMobileDropdown() {
				if ( ! $scope.hasClass( 'wpr-mobile-menu-full-width' ) || (! $scope.closest('.elementor-column').length && ! $scope.closest('.e-con').length) ) {
					return;
				}

                // GOGA: maybe in some cases elementor-element instead of e-con
                var topSection = $scope.closest('.elementor-top-section');

				var eColumn   = $scope.closest('.elementor-column').length ? $scope.closest('.elementor-column') : $scope.closest('.elementor-element'),
					mWidth 	  = topSection.length ? (topSection.outerWidth() - 2 * mobileMenu.offset().left) : ($(window).outerWidth() - 2 * mobileMenu.offset().left),
					mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

                // GOGA: don't need to calculate mWidth since it has tu be full
				mobileMenu.css({
					'width' : mWidth +'px',
					'left' : - mPosition +'px'
				});
			}

			// Sub Menu Animation
			function subMenuAnimation( selector, show ) {
				if ( show === true ) {
					selector.prev().attr('aria-expanded', 'true');
					if ( $scope.hasClass('wpr-sub-menu-fx-slide') ) {
						selector.stop().slideDown();
					} else {
						selector.stop().fadeIn();
					}
				} else {
					selector.prev().attr('aria-expanded', 'false');
					if ( $scope.hasClass('wpr-sub-menu-fx-slide') ) {
						selector.stop().slideUp();
					} else {
						selector.stop().fadeOut();
					}
				}
			}

			// Same-page anchor scroll (like onepage nav) – only when Consider Sticky Header setting is enabled
			if ( $navMenu.attr( 'data-consider-header' ) === 'yes' ) {
				var scrollSpeed = parseInt( $navMenu.attr( 'data-scroll-speed' ), 10 ) || 500;

				function getScrollTopForSection( sectionPos ) {
					if ( $( '[data-wpr-sticky-section="yes"]' ).first().length ) {
						return sectionPos - $( '[data-wpr-sticky-section="yes"]' ).first().height();
					}
					return sectionPos;
				}

				$scope.find( '.wpr-nav-menu a, .wpr-mobile-nav-menu a' ).on( 'click', function( e ) {
					var href = $( this ).attr( 'href' );
					if ( ! href || href === '#' ) { return; }
					var hashIndex = href.indexOf( '#' );
					if ( hashIndex === -1 ) { return; }
					var hash = href.slice( hashIndex );
					if ( hash === '#' ) { return; }
					var isSamePage = ( href.charAt( 0 ) === '#' ) || ( this.pathname === window.location.pathname && this.hostname === window.location.hostname );
					if ( ! isSamePage ) { return; }
					var $section = $( hash );
					if ( ! $section.length ) { return; }
					e.preventDefault();
					e.stopImmediatePropagation();
					var sectionPos = $section.offset().top;
					$( 'html, body' ).animate( {
						scrollTop: getScrollTopForSection( sectionPos )
					}, scrollSpeed );
				} );

				$( document ).ready( function() {
					var hash = window.location.hash;
					if ( ! hash || hash === '#' ) { return; }
					var $section = $( hash );
					if ( ! $section.length ) { return; }
					if ( ! $scope.find( 'a[href*="' + hash + '"]' ).length ) { return; }
					var sectionPos = $section.offset().top;
					$( 'html, body' ).animate( {
						scrollTop: getScrollTopForSection( sectionPos )
					}, scrollSpeed );
				} );
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-nav-menu.default', handler );
	});

})(jQuery);
