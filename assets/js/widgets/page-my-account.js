(function($) {
	"use strict";

	var handler = function($scope) {

			if ( WprElements.editorCheck() ) {
			
				$scope.find(".woocommerce-MyAccount-content").each(function() {
					if ( $(this).index() !== 1 ) {
						$(this).css('display', 'none');
					}
				});

				$scope.find('.woocommerce-MyAccount-navigation-link').on('click', function() {
					var tabContent, tabLinks, pageName;

					tabContent = $scope.find(".woocommerce-MyAccount-content");
					tabContent.each(function() {
							$(this).css('display', 'none');
					});

					tabLinks = $scope.find(".woocommerce-MyAccount-navigation-link");
					tabLinks.each(function() {
						$(this).removeClass('is-active');
					});

					pageName = $(this).attr('class').slice($(this).attr('class').indexOf('--') + 2);
					$(this).addClass('is-active');

					$scope.find('[wpr-my-account-page="'+ pageName +'"]').css('display', 'block');

				});
			}
			
			if ( $scope.find('.wpr-wishlist-remove').length ) {
				$scope.find('.wpr-wishlist-remove').on('click', function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
                            action: 'remove_from_wishlist',
                            nonce: WprConfig.nonce,
							product_id: product_id,
						},
						success: function() {
							$scope.find('.wpr-wishlist-product[data-product-id="' + product_id + '"]').remove();
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_wishlist');
						}
					});
				});

				$(document).on('removed_from_wishlist', function() {
					$scope.find('.wpr-wishlist-product[data-product-id="' + actionTargetProductId + '"]').remove();
				});

			}
			
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-my-account-pro.default', handler );
	});

})(jQuery);
