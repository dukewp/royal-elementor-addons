(function($) {
	"use strict";

	var handler = function($scope) {
			
			if ( !($scope.find('.wpr-wishlist-count').length > 0 && 0 == $scope.find('.wpr-wishlist-count').text()) ) {
				$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
			} else {

			}
			
			function wishlistRemoveHandler() {
				$scope.find('.wpr-wishlist-remove').on('click', function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
                            action: 'remove_from_wishlist',
                            nonce: WprConfig.nonce,
							product_id: product_id
						},
						success: function() {
							   $scope.find('.wpr-wishlist-product[data-product-id="' + product_id + '"]').remove();
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_wishlist');
						}
					});
				});
			}

			wishlistRemoveHandler();
	
			var mutationObserver = new MutationObserver(function(mutations) {
				wishlistRemoveHandler();
			});

			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_wishlist_items',
					nonce: WprConfig.nonce,
				},
				success: function(response) {
					if ( $scope.find('.wpr-wishlist-count').css('display') == 'none' && 0 < response.wishlist_count ) {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
					} else if ( 0 == response.wishlist_count ) {
						$scope.find('.wpr-wishlist-count').css('display', 'none');
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
					} else {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
					}

					if ( true ) {
						// Get all elements with the class 'wpr-wishlist-product' and their product IDs
						var productElements = $scope.find('.wpr-wishlist-product');
						var productIds = productElements.map(function() {
						  return $(this).data('product-id');
						}).get();
						
						// Filter out the items in the response that match the product IDs
						var newWishlistItems = response.wishlist_items.filter(function(item) {
						  return !productIds.includes(item.product_id);
						});

						// Convert the wishlist_items to an array of product_ids for easier searching
						var wishlistProductIds = response.wishlist_items.map(function(item) {
							return item.product_id;
						});

						productElements.each(function() {
						  var productId = $(this).data('product-id');
						
						  // If the product ID is not in the wishlistProductIds array, remove the element
						  if (!wishlistProductIds.includes(productId)) {
							$(this).remove();
						  }
						});

						newWishlistItems.forEach(function(item) {
							$scope.find('.wpr-wishlist-products').append('<li class="wpr-wishlist-product" data-product-id="'+ item.product_id +'"><a class="wpr-wishlist-product-img" href="'+ item.product_url +'">'+ item.product_image +'</a><div><a href="'+ item.product_url +'">'+ item.product_title +'</a><div class="wpr-wishlist-product-price">'+ item.product_price +'</div></div><span class="wpr-wishlist-remove" data-product-id="'+ item.product_id +'"></span></li>');
						});
					}
				}
			});

			$(document).on('added_to_wishlist', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_wishlist',
						nonce: WprConfig.nonce,
						product_id: actionTargetProductId,
					},
					success: function(response) {
						if ( $scope.find('.wpr-wishlist-products').find('li[data-product-id='+ response.product_id +']').length == 0 ) {
							$scope.find('.wpr-wishlist-products').append('<li class="wpr-wishlist-product" data-product-id="'+ response.product_id +'"><a class="wpr-wishlist-product-img" href="'+ response.product_url +'">'+ response.product_image +'</a><div><a href="'+ response.product_url +'">'+ response.product_title +'</a><div class="wpr-wishlist-product-price">'+ response.product_price +'</div></div><span class="wpr-wishlist-remove" data-product-id="'+ response.product_id +'"></span></li>');
						}

						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
					}
				});
			});

			$(document).on('removed_from_wishlist', function() {
				$scope.find('.wpr-wishlist-product[data-product-id="' + actionTargetProductId + '"]').remove();
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_wishlist',
						nonce: WprConfig.nonce,
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						
						if ( response.wishlist_count == 0 ) {
							$scope.find('.wpr-wishlist-count').css('display', 'none');
						} else {
							$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
						}
					}
				});
			});
			$scope.find('.wpr-wishlist').css({"display": "none"});

			var animationSpeed = $scope.find('.wpr-wishlist-wrap').data('animation');

			$('body').on('click', function(e) {
				if ( !e.target.classList.value.includes('wpr-wishlist-wrap') && !e.target.closest('.wpr-wishlist-wrap') ) {
					if ( $scope.hasClass('wpr-wishlist-slide') ) {
						$scope.find('.wpr-wishlist').slideUp(animationSpeed);
					} else {
						$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
					}
				}
			});

			if ( 0 !== $scope.hasClass('wpr-wishlist-sidebar').length ) {
				if ( $('#wpadminbar').length ) {
					$scope.find('.wpr-wishlist').css({
						// 'top': $('#wpadminbar').css('height'),
						// 'height': $scope.find('.wpr-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
						'z-index': 999999
					});
				}

				closeSideBar();

				$scope.find('.wpr-wishlist').on('click', function(e) {
					// if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
					if ( !e.target.classList.value.includes('wpr-wishlist-inner-wrap') && !e.target.closest('.wpr-wishlist-inner-wrap') ) {
						// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-out');
						$scope.find('.wpr-wishlist-slide-out').css('animation-speed', animationSpeed);
						$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
						$('body').removeClass('wpr-wishlist-sidebar-body');
						setTimeout(function() {
							// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-wishlist-inner-wrap').removeClass('wpr-wishlist-slide-out');
							$scope.find('.wpr-wishlist').css({"display": "none"});
						}, animationSpeed + 100);
					}
				});
			}

			if ( $scope.find('.wpr-wishlist').length ) {
				if ( $scope.hasClass('wpr-wishlist-sidebar') || $scope.hasClass('wpr-wishlist-dropdown') ) {
					$scope.find('.wpr-wishlist-toggle-btn').on('click', function(e) {
						e.stopPropagation();
						e.preventDefault();
						if ( 'none' === $scope.find('.wpr-wishlist').css("display") ) {
							if ( $scope.hasClass('wpr-wishlist-slide') ) {
								$scope.find('.wpr-wishlist').slideDown(animationSpeed);
							} else {
								$scope.find('.wpr-wishlist').fadeIn(animationSpeed);
							}
							if ( $scope.hasClass('wpr-wishlist-sidebar') ) {
								$scope.find('.wpr-wishlist').fadeIn(animationSpeed);
								$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-in');
								$scope.find('.wpr-wishlist-slide-in').css('animation-speed', animationSpeed);
								$('body').addClass('wpr-wishlist-sidebar-body');
							}
							setTimeout(function() {
								// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-in');
								$scope.find('.wpr-wishlist').removeClass('wpr-wishlist-slide-in');
								if ( $scope.hasClass('wpr-wishlist-sidebar') ) {
									$scope.find('.wpr-wishlist').trigger('resize');
								}
							}, animationSpeed + 100);
						} else {
							if ( $scope.hasClass('wpr-wishlist-slide') ) {
								$scope.find('.wpr-wishlist').slideUp(animationSpeed);
							} else {
								$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
							}
						}
					});
				}
			}

			var mutationObserver = new MutationObserver(function(mutations) {
				if (  0 !== $scope.hasClass('wpr-wishlist-sidebar').length ) {
					closeSideBar();
				}
				
				$scope.find('.wpr-wishlist-product').on('click', '.wpr-wishlist-remove', function() {
					$(this).closest('li').addClass('wpr-before-remove-from-wishlist');
				});

				if ( $scope.find('.wpr-wishlist-product').length !== 0 ) {
					$scope.find('.wpr-wishlist-empty').addClass('wpr-wishlist-empty-hidden');
					$scope.find('.wpr-view-wishlist').removeClass('wpr-hidden-element');
				} else {
					$scope.find('.wpr-wishlist-empty').removeClass('wpr-wishlist-empty-hidden');
					$scope.find('.wpr-view-wishlist').addClass('wpr-hidden-element');
				}
			});

			// Listen to Mini Cart Changes
			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			function closeSideBar() {
				$scope.find('.wpr-close-wishlist span').on('click', function(e) {
					// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
					$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-out');
					$scope.find('.wpr-wishlist-slide-out').css('animation-speed', animationSpeed);
					$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
					$('body').removeClass('wpr-wishlist-sidebar-body');
					setTimeout(function() {
						// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-wishlist-inner-wrap').removeClass('wpr-wishlist-slide-out');
						$scope.find('.wpr-wishlist').css({"display": "none"});
					}, animationSpeed + 100);
				});
			}
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-mini-wishlist-pro.default', handler );
	});

})(jQuery);
