(function($) {
	"use strict";

	var handler = function($scope) {

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'check_product_in_compare',
					product_id: $scope.find('.wpr-compare-add').data('product-id')
				},
				success: function(response) {
					if ( true == response ) {
						if ( !$scope.find('.wpr-compare-add').hasClass('wpr-button-hidden') ) {
							$scope.find('.wpr-compare-add').addClass('wpr-button-hidden');
						}

						if ( $scope.find('.wpr-compare-remove').hasClass('wpr-button-hidden') ) {
							$scope.find('.wpr-compare-remove').removeClass('wpr-button-hidden');
						}
					}
				}
			});

			// $(document).ready(function() {
				$scope.find('.wpr-compare-add').click(function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');

					$(this).fadeTo(500, 0);

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

			// });

		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-compare-button-pro.default', handler );
	});

})(jQuery);
