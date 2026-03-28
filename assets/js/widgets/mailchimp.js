(function($) {
	"use strict";

	var handler = function($scope) {
			var mailchimpForm = $scope.find( 'form' );

			mailchimpForm.on( 'submit', function(e) {
				e.preventDefault();

				var buttonText = $(this).find('button').text();

				// Change Text
				$(this).find('button').text( $(this).find('button').data('loading') );

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'mailchimp_subscribe',
						fields: $(this).serialize(),
						listId: mailchimpForm.data( 'list-id' )
					},
					success: function(data) {
						if ( 'yes' == mailchimpForm.data('clear-fields') ) {
							mailchimpForm.find('input').each(function() {
								$(this).val('');
							});
						}

						mailchimpForm.find('button').text( buttonText );

						if ( 'subscribed' === data.status ) {
							$scope.find( '.wpr-mailchimp-success-message' ).show();
						} else {
							$scope.find( '.wpr-mailchimp-error-message' ).show();
						}
						
						$scope.find( '.wpr-mailchimp-message' ).fadeIn();
					}
				});

			});

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-mailchimp.default', handler );
	});

})(jQuery);
