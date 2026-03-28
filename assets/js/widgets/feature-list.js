(function($) {
	"use strict";

	var handler = function($scope) {
            const target = document.querySelector('.e-n-tabs-content>div');
            
            if (target) {
                const observer = new MutationObserver(function(mutationsList) {
                    for (let mutation of mutationsList) {
                        if (mutation.type === 'attributes' || mutation.type === 'childList') {
                            featureList();
                        }
                    }
                });
        
                observer.observe(target, {
                    attributes: true,
                    childList: true,
                    subtree: true
                });
            }

            featureList();

            function featureList() { 
                $scope.find('.wpr-feature-list-item:not(:last-of-type)').find('.wpr-feature-list-icon-wrap').each(function(index) {
                    var offsetTop = $scope.find('.wpr-feature-list-item').eq(index + 1).find('.wpr-feature-list-icon-wrap').offset().top;
                    
                    $(this).find('.wpr-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
                });

                $(window).resize(function() {
                    $scope.find('.wpr-feature-list-item:not(:last-of-type)').find('.wpr-feature-list-icon-wrap').each(function(index) {
                        var offsetTop = $scope.find('.wpr-feature-list-item').eq(index + 1).find('.wpr-feature-list-icon-wrap').offset().top;
                        
                        $(this).find('.wpr-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
                    });
                });
            }
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-feature-list.default', handler );
	});

})(jQuery);
