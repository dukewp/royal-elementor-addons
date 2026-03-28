(function($) {
	"use strict";

	var handler = function($scope) {
			let animationDuration;

			if ( $scope.hasClass('wpr-offcanvas-entrance-animation-pro-sl') ) {
				$scope.removeClass('wpr-offcanvas-entrance-animation-pro-sl').addClass('wpr-offcanvas-entrance-animation-fade');
			} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-pro-gr') ) {
				$scope.removeClass('wpr-offcanvas-entrance-animation-pro-gr').addClass('wpr-offcanvas-entrance-animation-fade');
			}

			if ( $scope.hasClass('wpr-offcanvas-entrance-type-pro-ps') ) {
				$scope.removeClass('wpr-offcanvas-entrance-type-pro-ps').addClass('wpr-offcanvas-entrance-type-cover');
			}

			function openOffcanvas(offcanvasSelector) {
				if ( !$scope.hasClass('wpr-offcanvas-entrance-type-push') && !$scope.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-relative') ) {
					$('body').addClass('wpr-offcanvas-body-overflow');
				}
				animationDuration = +offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration').replace('s', '') * 1000;
				offcanvasSelector.fadeIn(animationDuration);
				offcanvasSelector.addClass('wpr-offcanvas-wrap-active');
				if ( $scope.hasClass('wpr-offcanvas-entrance-animation-slide') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-slide-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-in').addClass('wpr-offcanvas-slide-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-out').addClass('wpr-offcanvas-slide-in');
					}
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-grow') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-grow-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-in').addClass('wpr-offcanvas-grow-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-out').addClass('wpr-offcanvas-grow-in');
					}
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-fade') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-fade-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-in').addClass('wpr-offcanvas-fade-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-out').addClass('wpr-offcanvas-fade-in');
					}
				}

				$(window).trigger('resize');
			}

			function closeOffcanvas(offcanvasSelector) {
				if ( !$scope.hasClass('wpr-offcanvas-entrance-type-push') && !$scope.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-relative') ) {
					$('body').removeClass('wpr-offcanvas-body-overflow');
				}
				if ( $scope.hasClass('wpr-offcanvas-entrance-animation-slide') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-in').addClass('wpr-offcanvas-slide-out');
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-grow') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-in').addClass('wpr-offcanvas-grow-out');
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-fade') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-in').addClass('wpr-offcanvas-fade-out');
				}

				offcanvasSelector.fadeOut(animationDuration);
				offcanvasSelector.removeClass('wpr-offcanvas-wrap-active');
				// setTimeout(function() {
				// }, 600);
			}

			if ( $scope.hasClass('wpr-offcanvas-entrance-type-push') ) {	

				function growBodyWidth() {

					if ($('.wpr-offcanvas-body-inner-wrap-'+ $scope.data('id')).length < 1 ) {
						var offcanvasWrap = $('.wpr-offcanvas-wrap-'+ $scope.data('id')).clone();
						$('.wpr-offcanvas-wrap-'+ $scope.data('id')).remove();
	
						if ( !($('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')).length > 0) ) {
							$("body").wrapInner('<div class="wpr-offcanvas-body-inner-wrap-' + $scope.data('id') + '" />');
						}
	
						bodyInnerWrap = $('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id'));
		
						bodyInnerWrap.css('position', 'relative');
		
						if ( !(bodyInnerWrap.prev('.wpr-offcanvas-wrap').length > 0) ) {
							document.querySelector('body').insertBefore(offcanvasWrap[0], document.querySelector('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')));
						}

						offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));
					}

					openOffcanvas(offcanvasSelector);

					$('body').addClass('wpr-offcanvas-body-overflow');
	
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-left') ) {
						// bodyInnerWrap.animate({'margin-left': offcanvasSelector.find('.wpr-offcanvas-content').width() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'transform': 'translateX('+ offcanvasSelector.find('.wpr-offcanvas-content').outerWidth() +'px)',
						});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-right') ) {
						// bodyInnerWrap.animate({'margin-right': offcanvasSelector.find('.wpr-offcanvas-content').width() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'transform': 'translateX(-'+ offcanvasSelector.find('.wpr-offcanvas-content').outerWidth() +'px)',
						});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-top') ) {
						// bodyInnerWrap.animate({'margin-top': offcanvasSelector.find('.wpr-offcanvas-content').outerHeight() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'margin-top': offcanvasSelector.find('.wpr-offcanvas-content').outerHeight() + 'px',
						});
					}
				}
	
				function reduceBodyWidth() {
					
					if ( !bodyInnerWrap && !offcanvasSelector )  {
						bodyInnerWrap = $('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id'));
						offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));
					}

					closeOffcanvas(offcanvasSelector);

					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-left') ) {
						// bodyInnerWrap.animate({'margin-left': 0}, 'slow');
						bodyInnerWrap.css({'transform': 'translateX(0px)'});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-right') ) {
						// bodyInnerWrap.animate({'margin-right': 0}, 'slow');
						bodyInnerWrap.css({'transform': 'translateX(0px)'});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-top') ) {
						// bodyInnerWrap.animate({'margin-top': 0}, 'slow');
						bodyInnerWrap.css({'margin-top': 0});
					}

					$('body').removeClass('wpr-offcanvas-body-overflow');
					setTimeout(function() {
						var cnt = $('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')).contents();
						$('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')).replaceWith(cnt);
					}, 1000);
				}
	
				function closeTriggers() {
					offcanvasSelector.on('click', function(e){
						if ( !e.target.classList.value.includes('wpr-offcanvas-content') && !e.target.closest('.wpr-offcanvas-content') ) {
							reduceBodyWidth();
						}
					});
					
					$(document).on('keyup', function(event) {
						if (event.key == "Escape") {
							reduceBodyWidth();
						}
					});
		
					offcanvasSelector.find('.wpr-close-offcanvas').on('click', function() {
						reduceBodyWidth();
					});
				}

				if ( !($('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')).length > 0) ) {
					$("body").wrapInner('<div class="wpr-offcanvas-body-inner-wrap-' + $scope.data('id') + '" />');
				}

				var bodyInnerWrap = $('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id'));

				bodyInnerWrap.css('position', 'relative');

				if ( !(bodyInnerWrap.prev('.wpr-offcanvas-wrap').length > 0) ) {
					$scope.find('.wpr-offcanvas-wrap').addClass('wpr-offcanvas-wrap-'+ $scope.data('id'));

					document.querySelector('body').insertBefore($scope.find('.wpr-offcanvas-wrap')[0], document.querySelector('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')));
				}

				var offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));

				$scope.find('.wpr-offcanvas-trigger').on('click', function() {
					if ( $('.wpr-offcanvas-wrap-'+ $scope.data('id')).length > 0 && $scope.find('.wpr-offcanvas-wrap').length > 0 ) {
						$('.wpr-offcanvas-wrap-'+ $scope.data('id')).remove();
						$scope.find('.wpr-offcanvas-wrap').addClass('wpr-offcanvas-wrap-'+ $scope.data('id'));
						document.querySelector('body').insertBefore($scope.find('.wpr-offcanvas-wrap')[0], document.querySelector('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')));
						offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));
					}

					if (offcanvasSelector.hasClass('wpr-offcanvas-wrap-active')) {
						reduceBodyWidth();
					} else {
						growBodyWidth();
					}
				});
	
				if ( 'yes' === $scope.find('.wpr-offcanvas-container').data('offcanvas-open') ) {
					$scope.find('.wpr-offcanvas-trigger').trigger('click');
				}

				closeTriggers();
				
				$('body').on('click', function() {
					closeTriggers();
				});

				var mutationObserver = new MutationObserver(function(mutations) {
					closeTriggers();
				});

				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});
			} else {

				$scope.find('.wpr-offcanvas-trigger').on('click', function() {
					if ( !$scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-active') ) {
						openOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					} else if ( $scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-active') && $scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-relative') ) {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				$scope.find('.wpr-offcanvas-wrap').on('click', function(e){
					if ( !e.target.classList.value.includes('wpr-offcanvas-content') && !e.target.closest('.wpr-offcanvas-content') ) {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				if ( 'yes' === $scope.find('.wpr-offcanvas-container').data('offcanvas-open') ) {
					$scope.find('.wpr-offcanvas-trigger').trigger('click');
				}
				
				$(document).on('keyup', function(event) {
					if (event.key == "Escape") {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				$scope.find('.wpr-close-offcanvas').on('click', function() {
					closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
				});
				
			}

		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-offcanvas.default', handler );
	});

})(jQuery);
