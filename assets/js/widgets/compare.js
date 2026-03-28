(function($) {
	"use strict";

	var handler = function($scope) {
			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_compare_items',
					nonce: WprConfig.nonce,
					remove_text: $scope.find('.wpr-compare-table-wrap').attr('remove_from_compare_text'),
					compare_empty_text: $scope.find('.wpr-compare-table-wrap').attr('compare_empty_text'),
					element_addcart_simple_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_simple_txt'),
					element_addcart_grouped_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_grouped_txt'),
					element_addcart_variable_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_variable_txt')
				},
				success: function(response) {
					if ( true ) {
						$scope.find('.wpr-compare-table-wrap').html(response.compare_table);
					}
				},
				error: function(error) {
					console.log(error);
				}
			});

			$scope.on('click', '.wpr-compare-remove', function(e) {
				e.preventDefault();
				var productID = $(this).data('product-id');
			
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
                        action: 'remove_from_compare',
                        nonce: WprConfig.nonce,
						product_id: productID
					},
					success: function() {
						WprElements.changeActionTargetProductId(productID);
						$scope.find('[data-product-id="' + productID + '"]').remove();
						if ( !($scope.find('.wpr-compare-remove').length > 0) ) {
							$scope.find('.wpr-compare-products').addClass('wpr-hidden-element');
							$scope.find('.wpr-compare-empty').removeClass('wpr-hidden-element');
						} else {
							$scope.find('.wpr-compare-empty').addClass('wpr-hidden-element');
							$scope.find('.wpr-compare-products').removeClass('wpr-hidden-element');
						}
						$(document).trigger('removed_from_compare');
					}
				});
			});
			
			$( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
				button.next().fadeTo( 700, 1 );

				button.css('display', 'none');
			});
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-compare-pro.default', handler );
	});

})(jQuery);
