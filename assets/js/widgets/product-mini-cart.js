(function($) {
	"use strict";

	var handler = function($scope) {
            
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    updateMiniCart();
                }
            });

			$( document.body ).on( 'updated_wc_div', function() {
				updateMiniCart();
			});
			
			// function updateVH() {
			// 	let vh = window.innerHeight * 0.01;
			// 	document.documentElement.style.setProperty('--vh', `${vh}px`);
			// }
			
			// // Run the function initially
			// updateVH();
			
			// // Recalculate on window resize (for when the user rotates the device or resizes the viewport)
			// window.addEventListener('resize', updateVH);
			
            
            function updateMiniCart() {
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'woocommerce_get_refreshed_fragments'
                    },
                    success: function(response) {
                        if (response && response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
            
                            $(document.body).trigger('wc_fragments_refreshed');
                        }
                    }
                });
            }        

            $scope.find('.wpr-mini-cart').css({"display": "none"});

            var animationSpeed = $scope.find('.wpr-mini-cart-wrap').data('animation');

            $('body').on('click', function(e) {
                if ( !e.target.classList.value.includes('wpr-mini-cart') && !e.target.closest('.wpr-mini-cart') ) {	
                    $scope.find('.wpr-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('wpr-z-index');
                    if ( $scope.hasClass('wpr-mini-cart-slide') ) {
                        $scope.find('.wpr-mini-cart').slideUp(animationSpeed);
                    } else {
                        $scope.find('.wpr-mini-cart').fadeOut(animationSpeed);
                    }
                }

				if ( e.target.classList.value.includes('wpr-shopping-cart-wrap') ) {
					$scope.find('.wpr-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('wpr-z-index');
				}
            });

            if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
                if ( $('#wpadminbar').length ) {
                    $scope.find('.wpr-mini-cart').css({
                        // 'top': $('#wpadminbar').css('height'),
                        // 'height': $scope.find('.wpr-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
                        'z-index': 999999
                    });
                }

                closeSideBar();

                $scope.find('.wpr-shopping-cart-wrap').on('click', function(e) {
                    // if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
                    if ( !e.target.classList.value.includes('wpr-shopping-cart-inner-wrap') && !e.target.closest('.wpr-shopping-cart-inner-wrap') ) {
                        // $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
                        $scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-out');
                        $scope.find('.wpr-mini-cart-slide-out').css('animation-speed', animationSpeed);
                        $scope.find('.wpr-shopping-cart-wrap').fadeOut(animationSpeed);
                        $('body').removeClass('wpr-mini-cart-sidebar-body');
                        setTimeout(function() {
                            // $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
                            $scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-out');
                            $scope.find('.wpr-mini-cart').css({"display": "none"});
                        }, animationSpeed + 100);
                    }
                });
            }

            if ( $scope.find('.wpr-mini-cart').length ) {
                if ( $scope.hasClass('wpr-mini-cart-sidebar') || $scope.hasClass('wpr-mini-cart-dropdown') ) {
                    $scope.find('.wpr-mini-cart-toggle-btn').on('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        if ( 'none' === $scope.find('.wpr-mini-cart').css("display") ) {
                            $(this).closest('.elementor>.elementor-element').addClass('wpr-z-index');
                            if ( $scope.hasClass('wpr-mini-cart-slide') ) {
                                $scope.find('.wpr-mini-cart').slideDown(animationSpeed);
                            } else {
                                $scope.find('.wpr-mini-cart').fadeIn(animationSpeed);
                            }
                            if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
                                $scope.find('.wpr-shopping-cart-wrap').fadeIn(animationSpeed);
                                // $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-in');
                                $scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-in');
                                $scope.find('.wpr-mini-cart-slide-in').css('animation-speed', animationSpeed);
                                $('body').addClass('wpr-mini-cart-sidebar-body');
                            } else if ( $scope.hasClass('wpr-mini-cart-dropdown') ) {
								$scope.find('.wpr-shopping-cart-wrap').css('display', 'block');
							}
                            setTimeout(function() {
                                // $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-in');
                                $scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-in');
                                if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
                                    $scope.find('.wpr-woo-mini-cart').trigger('resize');
                                }
                            }, animationSpeed + 100);
                        } else {
                            $(this).closest('.elementor>.elementor-element').removeClass('wpr-z-index');
                            if ( $scope.hasClass('wpr-mini-cart-slide') ) {
                                $scope.find('.wpr-mini-cart').slideUp(animationSpeed);
                            } else {
                                $scope.find('.wpr-mini-cart').fadeOut(animationSpeed);
                            }
                        }
                    });
                }
            }

            var mutationObserver = new MutationObserver(function(mutations) {
                if (  $scope.hasClass('wpr-mini-cart-sidebar') ) {
                    closeSideBar();

                    // if ( $scope.find('.wpr-mini-cart').data('close-cart-heading') ) {
                    // 	$scope.find('.wpr-close-cart h2').text($scope.find('.wpr-mini-cart').data('close-cart-heading').replace(/-/g, ' '));
                    // }
                }
                
                $scope.find('.woocommerce-mini-cart-item').on('click', '.wpr-remove-item-from-mini-cart', function() {
                    $(this).closest('li').addClass('wpr-before-remove-from-mini-cart');
                });
            });

            // Listen to Mini Cart Changes
            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            function closeSideBar() {
                $scope.find('.wpr-close-cart span').on('click', function(e) {
                    // $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
                    $scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-out');
                    $scope.find('.wpr-mini-cart-slide-out').css('animation-speed', animationSpeed);
                    $scope.find('.wpr-shopping-cart-wrap').fadeOut(animationSpeed);
                    $('body').removeClass('wpr-mini-cart-sidebar-body');
                    setTimeout(function() {
                        // $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
                        $scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-out');
                        $scope.find('.wpr-mini-cart').css({"display": "none"});
                    }, animationSpeed + 100);
					$scope.find('.wpr-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('wpr-z-index');
                });
            }

		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-product-mini-cart.default', handler );
	});

})(jQuery);
