(function($) {
	"use strict";

	var handler = function($scope) {
			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '1184287221975469',
				  xfbml      : true,
				  version    : 'v13.0'
				});
				FB.AppEvents.logPageView();
			  };
			
			  (function(d, s, id){
				 var js, fjs = d.getElementsByTagName(s)[0];
				 if (d.getElementById(id)) {return;}
				 js = d.createElement(s); js.id = id;
				 js.src = "https://connect.facebook.net/en_US/sdk.js";
				 fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
        }

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-facebook-feed.default', handler );
	});

})(jQuery);
