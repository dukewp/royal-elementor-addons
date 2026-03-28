(function($) {
	"use strict";

	var handler = function($scope) {
            var acc = $scope.find('.wpr-acc-button');
			var accPanels = $scope.find('.wpr-acc-panel');
            var accItemWrap = $scope.find('.wpr-accordion-item-wrap');
			var accordionType = $scope.find('.wpr-advanced-accordion').data('accordion-type');
			var activeIndex = +$scope.find('.wpr-advanced-accordion').data('active-index') - 1;
			var accordionTrigger = $scope.find('.wpr-advanced-accordion').data('accordion-trigger');
			var interactionSpeed = +$scope.find('.wpr-advanced-accordion').data('interaction-speed') * 1000;
			var scopeID = $scope.attr('data-id');

			// ?active_panel=panel-index#your-id
			var activeTabIndexFromLocation = window.location.href.indexOf("active_panel=");

			if (activeTabIndexFromLocation > -1) {
				activeIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_panel=", '') - 1;
			}

			if ('click' === accordionTrigger) {

				if ( accordionType == 'accordion' ) {
					acc.off('click').on("click", function(e) {
						e.preventDefault();
						e.stopPropagation();
						var thisIndex = acc.index(this);
						acc.each(function(index){
							(index != thisIndex && $(this).closest('.elementor-widget-wpr-advanced-accordion').attr('data-id') == scopeID) ? $(this).removeClass('wpr-acc-active') : '';
						});
						$scope.find('.wpr-acc-panel').each(function(index) {
							(index != thisIndex && $(this).closest('.elementor-widget-wpr-advanced-accordion').attr('data-id') == scopeID) ? $(this).removeClass('wpr-acc-panel-active') && $(this).slideUp(interactionSpeed) : '';
						});
						$(this).toggleClass("wpr-acc-active");
						var panel = $(this).next();
						if ( !panel.hasClass('wpr-acc-panel-active') ) {
							panel.slideDown(interactionSpeed);
							panel.addClass('wpr-acc-panel-active');
						} else {
							panel.slideUp(interactionSpeed);
							panel.removeClass('wpr-acc-panel-active');
						}
					});
				} else {
					acc.each(function() {
						$(this).on("click", function() {
							$(this).toggleClass("wpr-acc-active");
							var panel = $(this).next();
							if ( !panel.hasClass('wpr-acc-panel-active') ) {
								panel.slideDown(interactionSpeed);
								panel.addClass('wpr-acc-panel-active');
							} else {
								panel.slideUp(interactionSpeed);
								panel.removeClass('wpr-acc-panel-active');
							}
						});
					});
				}

				acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
			} else if ( accordionTrigger == 'hover' ) {
				accItemWrap.on("mouseenter", function() {
						var thisIndex = accItemWrap.index(this);

						$(this).find('.wpr-acc-button').addClass("wpr-acc-active");

						var panel = $(this).find('.wpr-acc-panel');
							panel.slideDown(interactionSpeed);
							panel.addClass('wpr-acc-panel-active');

						accItemWrap.each(function(index) {
							if (index != thisIndex) {
								$(this).find('.wpr-acc-button').removeClass("wpr-acc-active");
								var panel = $(this).find('.wpr-acc-panel');
								panel.slideUp(interactionSpeed);
								panel.removeClass('wpr-acc-panel-active');
							}
						});
				});
				
				accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
			}

			$scope.find('.wpr-acc-search-input').on( {
				focus: function() {
					$scope.addClass( 'wpr-acc-search-input-focus' );
				},
				blur: function() {
					$scope.removeClass( 'wpr-search-form-input-focus' );
				}
			} );
			
			let allInAcc = $scope.find('.wpr-advanced-accordion > *');

			$scope.find('i.fa-times').on('click', function() {
				$scope.find('.wpr-acc-search-input').val('');
				$scope.find('.wpr-acc-search-input').trigger('keyup');
			});

			var iconBox = $scope.find('.wpr-acc-icon-box');

			iconBox.each(function() {
				$(this).find('.wpr-acc-icon-box-after').css({
					'border-top': $(this).height()/2 + 'px solid transparent', 
					'border-bottom': $(this).height()/2 + 'px solid transparent'
				});	
			});

			$(window).resize(function() {
				iconBox.each(function() {
					$(this).find('.wpr-acc-icon-box-after').css({
						'border-top': $(this).height()/2 + 'px solid transparent', 
						'border-bottom': $(this).height()/2 + 'px solid transparent'
					});	
				});
			});

			$scope.find('.wpr-acc-search-input').on('keyup', function() {
				setTimeout( () => {
					let thisValue = $(this).val();
					if ( thisValue.length > 0 ) {
						$scope.find('.wpr-acc-search-input-wrap').find('i.fa-times').css('display', 'inline-block');
						allInAcc.each(function() {
							if ( $(this).hasClass('wpr-accordion-item-wrap') ) {
								var itemWrap = $(this);
								if ( itemWrap.text().toUpperCase().indexOf(thisValue.toUpperCase()) == -1 ) {
									itemWrap.hide();
									if ( itemWrap.find('.wpr-acc-button').hasClass('wpr-acc-active') && itemWrap.find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
										itemWrap.find('.wpr-acc-button').removeClass('wpr-acc-active');
										itemWrap.find('.wpr-acc-panel').removeClass('wpr-acc-panel-active');
									}
								} else {
									itemWrap.show();
									if ( !itemWrap.find('.wpr-acc-button').hasClass('wpr-acc-active') && !itemWrap.find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
										itemWrap.find('.wpr-acc-button').addClass('wpr-acc-active');
										itemWrap.find('.wpr-acc-panel').addClass('wpr-acc-panel-active');
										itemWrap.find('.wpr-acc-panel').slideDown(interactionSpeed);
									}
								}
							}
						});
					} else {
						$scope.find('.wpr-acc-search-input-wrap').find('i.fa-times').css('display', 'none');
						allInAcc.each(function() {
							if ( $(this).hasClass('wpr-accordion-item-wrap') ) {
								$(this).show();
								if ( $(this).find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
									$(this).find('.wpr-acc-panel').removeClass('wpr-acc-panel-active');
								}
								if ( $(this).find('.wpr-acc-button').hasClass('wpr-acc-active') ) {
									$(this).find('.wpr-acc-button').removeClass('wpr-acc-active')
								}
								$(this).find('.wpr-acc-panel').slideUp(interactionSpeed);
							}
						});
						// if ('click' === accordionTrigger) {
						// 	acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
						// } else if ( 'hover' === accordionTrigger ) {
						// 	accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
						// }
					}
				}, 1000);
			});
			
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-advanced-accordion.default', handler );
	});

})(jQuery);
