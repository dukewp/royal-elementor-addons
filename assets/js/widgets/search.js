(function($) {
	"use strict";

	var handler = function($scope) {

			var isFound = false;

			$scope.find('.wpr-search-form-input').on( {
				focus: function() {
					$scope.addClass( 'wpr-search-form-input-focus' );
				},
				blur: function() {
					$scope.removeClass( 'wpr-search-form-input-focus' );
				}
			} );
            
            if ( $scope.find('.wpr-category-select').length > 0 ) {
                // Set the selected value on page load
                $(document).ready(function() {
                    var wprSelectedCategory = localStorage.getItem('wprSelectedCategory');
                    if (wprSelectedCategory) {
						$scope.find('.wpr-category-select option').each(function() {
							if ($(this).val() === wprSelectedCategory) {
								isFound = true;
								$scope.find('.wpr-category-select').val(wprSelectedCategory);
								return false; // Breaks out of the .each() loop
							} else {
								$scope.find('.wpr-category-select').val(0);
							}
						});
                    }
                });

                $scope.find('.wpr-category-select').on('change', function(e) {
                    
                    var selectedValue = $(this).val();
                    localStorage.setItem('wprSelectedCategory', selectedValue);

			        if ($scope.find('.wpr-search-form-input').attr('ajax-search') === 'yes') {
                        postsOffset = 0;
                        $scope.find('.wpr-data-fetch').hide();
                        $scope.find('.wpr-data-fetch ul').html('');
                        ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
                    }
                });
            }

			// if ( $scope.find('.wpr-search-input-hidden') ) {
			// 	$scope.find('.wpr-search-form-submit').on('click', function(e) {
			// 		e.preventDefault();
			// 		if ($scope.find('input').hasClass('wpr-search-input-hidden')) {
			// 			$scope.find('input').removeClass('wpr-search-input-hidden');
			// 		} else {
			// 			$scope.find('input').addClass('wpr-search-input-hidden');
			// 			$scope.find('.wpr-search-form-input').val('');
			// 			$scope.find('.wpr-data-fetch').slideUp(200);
			// 			setTimeout(function() {
			// 				$scope.find('.wpr-data-fetch ul').html('');
			// 				$scope.find('.wpr-no-results').remove();
			// 			}, 400);
			// 			postsOffset = 0;
			// 		}
			// 	});
			// }

			var prevData;
			var searchTimeout = null;

			function ajaxSearchCall(thisObject, postsOffset, e) {
				if ( e.which === 13 ) {
					return false;
				}

				if (searchTimeout != null) {
					clearTimeout(searchTimeout);
				}
				var optionPostType = ($scope.find('.wpr-category-select').length > 0 && $scope.find('.wpr-category-select').find('option:selected').data('post-type'));
				var wprTaxonomyType = $scope.find('.wpr-search-form-input').attr('wpr-taxonomy-type');

				if ( $scope.find('.wpr-category-select').length > 0) {
					if (!wprTaxonomyType) {
						if ($scope.find('.wpr-search-form-input').attr('wpr-query-type') == 'product') {
							wprTaxonomyType = 'product_cat';
						} else {
							wprTaxonomyType = 'category';
						}
					}
				}

				searchTimeout = setTimeout(() => {
					var thisValue = thisObject.val();

					$.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: { 
							action: 'wpr_data_fetch',
							nonce: WprConfig.nonce,
							wpr_keyword: $scope.find('.wpr-search-form-input').val(),
							wpr_meta_query: $scope.find('.wpr-search-form-input').attr('meta-query'),
							wpr_query_type: $scope.find('.wpr-search-form-input').attr('wpr-query-type'),
							wpr_option_post_type: optionPostType ? $scope.find('.wpr-category-select').find('option:selected').data('post-type') : '',
							wpr_taxonomy_type: wprTaxonomyType,
							wpr_category: $scope.find('.wpr-category-select').length > 0 ? $scope.find('.wpr-category-select').val() : '',
							wpr_number_of_results: $scope.find('.wpr-search-form-input').attr('number-of-results'),
							wpr_search_results_offset: postsOffset,
							wpr_show_description: $scope.find('.wpr-search-form-input').attr('show-description'),
							wpr_number_of_words: $scope.find('.wpr-search-form-input').attr('number-of-words'),
							wpr_show_ajax_thumbnail: $scope.find('.wpr-search-form-input').attr('show-ajax-thumbnails'),
							wpr_show_product_price: $scope.find('.wpr-search-form-input').attr('show-product-price'),
							wpr_show_view_result_btn: $scope.find('.wpr-search-form-input').attr('show-view-result-btn'),
							wpr_view_result_text: $scope.find('.wpr-search-form-input').attr('view-result-text'),
							wpr_no_results: $scope.find('.wpr-search-form-input').attr('no-results'),
							wpr_exclude_without_thumb: $scope.find('.wpr-search-form-input').attr('exclude-without-thumb'),
							wpr_ajax_search_link_target: $scope.find('.wpr-search-form-input').attr('link-target'),
							wpr_show_ps_pt: $scope.find('.wpr-search-form-input').attr('password-protected'),
							wpr_show_attachments: $scope.find('.wpr-search-form-input').attr('attachments'),
							// wpr_ajax_search_img_size: $scope.find('.wpr-search-form-input').attr('ajax-search-img-size')
						},
						success: function(data) {
							$scope.closest('section').addClass('wpr-section-z-index');
							if ( $scope.find('.wpr-data-fetch ul').html() === '' ) {
								$scope.find( '.wpr-pagination-loading' ).hide();
								$scope.find('.wpr-data-fetch ul').html( data );
								$scope.find('.wpr-no-more-results').fadeOut(100);
								setTimeout(function() {
									if (!data.includes('wpr-no-results')) {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
										if ( $scope.find('.wpr-data-fetch ul').find('li').length < $scope.find('.wpr-search-form-input').attr('number-of-results') ||
											$scope.find('.wpr-data-fetch ul').find('li').length == $scope.find('.wpr-data-fetch ul').find('li').data('number-of-results')) {
											$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
											$scope.find('.wpr-load-more-results').fadeOut(100);
										} else {
											$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
											$scope.find('.wpr-load-more-results').fadeIn(100);
										}
									} else {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
									}
								}, 100);
								prevData = data;
							} else {
								if ( data != prevData ) {
									prevData = data;
									if (data.includes('wpr-no-results')) {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
										$scope.find('.wpr-data-fetch ul').html('');
										$scope.closest('section').removeClass('wpr-section-z-index');
									} else {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
									}

									$scope.find('.wpr-data-fetch ul').append( data );

									if (data == '') {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find( '.wpr-pagination-loading' ).hide();
										$scope.find('.wpr-load-more-results').show();
									}

									if ($scope.find('.wpr-data-fetch ul').find('li').length < $scope.find('.wpr-search-form-input').attr('number-of-results')) {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find('.wpr-load-more-results').show();
									}

									if ( $scope.find('.wpr-data-fetch ul').find('li').length == $scope.find('.wpr-data-fetch ul').find('li').data('number-of-results') ) {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find('.wpr-load-more-results').show();
									}
									// $scope.find( '.wpr-pagination-loading' ).hide();
								}
							}

							if (data.includes('wpr-no-results')) {
								$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
								$scope.find('.wpr-load-more-results').fadeOut();
							} else {
								$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
							}
							
							if (thisValue.length > 2) {
								$scope.find('.wpr-data-fetch').slideDown(200);
								$scope.find('.wpr-data-fetch ul').fadeTo(200, 1);
							} else {
								$scope.find('.wpr-data-fetch').slideUp(200);
								$scope.find('.wpr-data-fetch ul').fadeTo(200, 0);
								setTimeout(function() {
									$scope.find('.wpr-data-fetch ul').html('');
									$scope.find('.wpr-no-results').remove();
									$scope.closest('section').removeClass('wpr-section-z-index');
								}, 600);
								postsOffset = 0;
							}
						},
						error: function(error) {
							console.log(error);
						}
					});
				}, 400);
			}

			if ($scope.find('.wpr-search-form-input').attr('ajax-search') === 'yes') {

				$scope.find('.wpr-search-form').attr('autocomplete', 'off');
				
				var postsOffset = 0;
				// $scope.find('.wpr-data-fetch ul').on('scroll', function(e) { 
				// 	if ( $(this).scrollTop() + $(this).innerHeight() >=  $(this)[0].scrollHeight ) {
				// 		postsOffset += +$scope.find('.wpr-search-form-input').attr('number-of-results');
				// 		ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
				// 	}
				// });

				$scope.find('.wpr-load-more-results').on('click', function(e) {
					postsOffset += +$scope.find('.wpr-search-form-input').attr('number-of-results');
					$scope.find('.wpr-load-more-results').hide();
					$scope.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
				});

				$scope.find('.wpr-search-form-input').on('keyup', function(e) {
					postsOffset = 0;
					$scope.find('.wpr-data-fetch').hide();
					$scope.find('.wpr-data-fetch ul').html('');
					ajaxSearchCall($(this), postsOffset, e);
				});
	
				$scope.find('.wpr-data-fetch').on('click', '.wpr-close-search', function() {
					$scope.find('.wpr-search-form-input').val('');
					$scope.find('.wpr-data-fetch').slideUp(200);
					setTimeout(function() {
						$scope.find('.wpr-data-fetch ul').html('');
						$scope.find('.wpr-no-results').remove();
						$scope.closest('section').removeClass('wpr-section-z-index');
					}, 400);
					postsOffset = 0;
				});

				$('body').on('click', function(e) {
					if ( !e.target.classList.value.includes('wpr-data-fetch') && !e.target.closest('.wpr-data-fetch') ) {
						if ( !e.target.classList.value.includes('wpr-search-form') && !e.target.closest('.wpr-search-form') ) {
							$scope.find('.wpr-search-form-input').val('');
							$scope.find('.wpr-data-fetch').slideUp(200);
							setTimeout(function() {
								$scope.find('.wpr-data-fetch ul').html('');
								$scope.find('.wpr-no-results').remove();
								$scope.closest('section').removeClass('wpr-section-z-index');
							}, 400);
							postsOffset = 0;
						}
					}
				});

				var mutationObserver = new MutationObserver(function(mutations) {
					$scope.find('.wpr-data-fetch li').on('click', function() {
						var itemUrl = $(this).find('a').attr('href');
						var itemUrlTarget = $(this).find('a').attr('target');
						window.open(itemUrl, itemUrlTarget).focus();
					});
				});

				// Listen to Mini Cart Changes
				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-search.default', handler );
	});

})(jQuery);
