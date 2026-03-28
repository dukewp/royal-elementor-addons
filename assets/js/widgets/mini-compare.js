(function($) {
	"use strict";

	var handler = function($scope) {
			// $scope.find('.wpr-compare-text').click(function(e) {
			// 	e.preventDefault();
			// 	alert(WprConfig.comparePageID);
			// 	$scope.find('.wpr-compare-popup').removeClass('wpr-compare-popup-hidden');
			// 	$.ajax({
			// 		// url: WprConfig.ajaxurl,
			// 		url: '/royal-wp/wp-json/wpraddons/v1/page-content/' + WprConfig.comparePageID,
			// 		type: 'GET',
			// 		// data: {
			// 		// 	action: 'wpr_get_page_content',
			// 		// 	wpr_compare_page_id: WprConfig.comparePageID // Replace with the ID of the page you want to retrieve
			// 		// },
			// 		// success: function(response) {
			// 		// 	// $scope.find('.wpr-compare-popup').append(response.data.content);
			// 		// 	$scope.find('.wpr-compare-popup').append(response);
			// 		// },
			// 		dataType: 'json',
			// 		success: function(response) {
			// 				$scope.find('.wpr-compare-popup').append(response);
			// 				elementorFrontend.init();
			// 		},
			// 		error: function(xhr, status, error) {
			// 		}
			// 	});
			// });
			
			if ( !($scope.find('.wpr-compare-count').length > 0 && 0 == $scope.find('.wpr-compare-count').text()) ) {
				$scope.find('.wpr-compare-count').css('display', 'inline-flex');
			}

			// WITH AJAX
			if ( $scope.hasClass('wpr-compare-style-popup') ) {
				$scope.find('.wpr-compare-toggle-btn').on('click', function(e) {
					e.preventDefault();

					$('body').addClass('wpr-body-overflow-hidden');

					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-popup-hidden');
					$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-out').addClass('wpr-compare-fade-in');
					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-out').addClass('wpr-compare-fade-in');

					$scope.find('.wpr-compare-popup-inner-wrap').html('<div class="wpr-compare-loader-wrap"><div class="wpr-double-bounce"><div class="wpr-child wpr-double-bounce1"></div><div class="wpr-child wpr-double-bounce2"></div></div></div>');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
                            action: 'wpr_get_page_content',
							nonce: WprConfig.nonce,
							wpr_compare_page_id: WprConfig.comparePageID
						},
						success: function(response) {
							$scope.find('.wpr-compare-popup-inner-wrap').html(response.data.content);
							WprElements.widgetCompare($scope);
							
							$scope.find('.wpr-compare-remove').click(function(e) {
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
										if ( !($scope.find('.wpr-compare-popup-inner-wrap').find('.wpr-compare-remove').length > 0) ) {
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
						},
						error: function(xhr, status, error) {
							console.log(xhr.responseText);
						}
					});
				});
	
				$scope.find('.wpr-close-compare').click(function(e) {
					$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
					setTimeout(function() {
						$scope.find('.wpr-compare-bg').addClass('wpr-compare-popup-hidden');
						$('body').removeClass('wpr-body-overflow-hidden');
					}, 600)
				});
	
				$scope.find('.wpr-compare-bg').click(function(e) {
					if ( !e.target.classList.value.includes('wpr-compare-popup') && !e.target.closest('.wpr-compare-popup') ) {
						var thisTarget = $(this);
						$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
						$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
						setTimeout(function() {
							thisTarget.addClass('wpr-compare-popup-hidden');
							$('body').removeClass('wpr-body-overflow-hidden');
						}, 600);
					}
				});

			}

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_compare_items',
					nonce: WprConfig.nonce,
				},
				success: function(response) {
					let compare_count = response.compare_count;
					if ( $scope.find('.wpr-compare-count').css('display') == 'none' && 0 < compare_count ) {
						$scope.find('.wpr-compare-count').text(compare_count);
						$scope.find('.wpr-compare-count').css('display', 'inline-flex');
					} else if ( 0 == compare_count ) {
						$scope.find('.wpr-compare-count').css('display', 'none');
						$scope.find('.wpr-compare-count').text(compare_count);
					} else {
						$scope.find('.wpr-compare-count').text(compare_count);
					}
				},
				error: function(error) {
					console.log(error);
				}
			});

			$(document).on('removed_from_compare', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_compare',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-compare-count').text(response.compare_count);
						
						if ( response.compare_count == 0 ) {
							$scope.find('.wpr-compare-count').css('display', 'none');
						} else {
							$scope.find('.wpr-compare-count').css('display', 'inline-flex');
						}
					}
				});
			});

			$(document).on('added_to_compare', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_compare',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-compare-count').text(response.compare_count);
						$scope.find('.wpr-compare-count').css('display', 'inline-flex');
					}
				});
			});
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-mini-compare-pro.default', handler );
	});

})(jQuery);
