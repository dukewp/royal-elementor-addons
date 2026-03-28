(function($) {
	"use strict";

	var handler = function($scope) {
			var ppcForm = $scope.find( '.wpr-ppc-form' );

			if ( ! ppcForm.length ) {
				return;
			}

			ppcForm.on( 'submit', function(e) {
				e.preventDefault();

				var $form = $(this),
					$container = $scope.find( '.wpr-ppc-container' ),
					$error = $form.find( '.wpr-ppc-error' ),
					$input = $form.find( '.wpr-ppc-password-input' );

				$error.hide();

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'wpr_ppc_verify_password',
						widget_id: $form.data( 'widget-id' ),
						post_id: $form.data( 'post-id' ),
						password: $input.val(),
						nonce: $form.data( 'nonce' )
					},
					success: function( response ) {
						if ( response.success ) {
							$container.hide().html( '<div class="wpr-ppc-content">' + response.data.content + '</div>' ).fadeIn( 600 );

							// Re-init Elementor widgets
							if ( window.elementorFrontend && elementorFrontend.elementsHandler ) {
								$container.find( '.elementor-element' ).each( function() {
									elementorFrontend.elementsHandler.runReadyTrigger( $( this ) );
								});
							}
						} else {
							$error.text( response.data.message ).show();
						}
					},
					error: function() {
						$error.text( 'Something went wrong. Please try again.' ).show();
					}
				});
			});
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-password-protected-content.default', handler );
	});

})(jQuery);
