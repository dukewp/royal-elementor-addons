(function($) {
	"use strict";

	var handler = function($scope) {

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'check_product_in_wishlist',
						product_id: $scope.find('.wpr-wishlist-add').data('product-id')
					},
					success: function(response) {
						if ( true == response ) {
							if ( !$scope.find('.wpr-wishlist-add').hasClass('wpr-button-hidden') ) {
								$scope.find('.wpr-wishlist-add').addClass('wpr-button-hidden');
							}

							if ( $scope.find('.wpr-wishlist-remove').hasClass('wpr-button-hidden') ) {
								$scope.find('.wpr-wishlist-remove').removeClass('wpr-button-hidden');
							}
						}
					}
				});

				$scope.find('.wpr-wishlist-add').click(function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');

					$(this).fadeTo(500, 0);
	
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

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-wishlist-button-pro.default', handler );
	});

})(jQuery);
