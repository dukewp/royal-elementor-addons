(function($) {
	"use strict";

	var handler = function($scope) {
			var taxList = $scope.find('.wpr-taxonomy-list');

			if ( taxList.data('show-on-click') == 'yes' ) {

				// $scope.find('.wpr-tax-dropdown').css('margin-left', -($scope.find('.wpr-tax-dropdown').width()));

				taxList.find('.wpr-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').addClass('wpr-sub-hidden');

						taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).closest('li').data('term-id') +'"]').each(function() {
							if ( !$(this).hasClass('wpr-sub-hidden') ) {
								taxList.find('.wpr-sub-taxonomy[data-id="'+ $(this).data('parent-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');
								// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
								$(this).addClass('wpr-sub-hidden');
							}
						});

						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').each(function() {
							if ( !$(this).hasClass('wpr-sub-hidden') ) {
								taxList.find('.wpr-sub-taxonomy[data-id="'+ $(this).data('parent-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');
								// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
								$(this).addClass('wpr-sub-hidden');
							}
						});

						// if (!taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).parent('li').data('term-id') +'"]').hasClass('wpr-sub-hidden')) {
						// 	taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).parent('li').data('term-id') +'"] i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// 	taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).parent('li').data('term-id') +'"]').addClass('wpr-sub-hidden');
						// }
					}

					taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).closest('li').data('term-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');

					if ( !taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').hasClass('wpr-sub-hidden') ) {
						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').addClass('wpr-sub-hidden');
					}
				});

				taxList.find('.wpr-sub-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// taxList.find('.wpr-sub-taxonomy i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').addClass('wpr-sub-hidden');
					}

					taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');

					if ( !taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id').replace('child-', '') +'"]').hasClass('wpr-sub-hidden') ) {
						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id').replace('child-', '') +'"]').addClass('wpr-sub-hidden');
					}
				});

				taxList.find('.wpr-inner-sub-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// taxList.find('.wpr-sub-taxonomy i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').addClass('wpr-sub-hidden');
					}
				});
			}
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-taxonomy-list.default', handler );
	});

})(jQuery);
