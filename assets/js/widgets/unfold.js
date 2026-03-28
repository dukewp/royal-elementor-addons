(function($) {
	"use strict";

	var handler = function($scope) {
			var $btn = $scope.find( '.wpr-unfold-btn' ),
				$wrapper = $scope.find( '.wpr-unfold-wrapper' ),
				$data = $scope.find( '.wpr-unfold-data' ),
				$dataInner = $scope.find( '.wpr-unfold-data-inner' ),
				$collapseIcon = $btn.find( '.wpr-unfold-collapse-icon' ),
				$expandIcon = $btn.find( '.wpr-unfold-expand-icon' ),
				config = $wrapper.data( 'config' ),
				duration = parseInt( config.transition_duration, 10 ) || 300,
				collapseHeight = parseInt( config.collapse_height, 10 ) || 79,
				hasIcons = $collapseIcon.length > 0 || $expandIcon.length > 0;

			function updateText( text ) {
				if ( hasIcons ) {
					var textNode = $btn.contents().filter(function() {
						return this.nodeType === 3;
					})[0];
					if ( textNode ) {
						textNode.nodeValue = text;
					}
				} else {
					$btn.html( text );
				}
			}

			function updateIcons( state ) {
				if ( hasIcons ) {
					if ( 'collapse' === state ) {
						$collapseIcon.show();
						$expandIcon.hide();
					} else {
						$expandIcon.show();
						$collapseIcon.hide();
					}
				}
			}

			// Initial state
			if ( collapseHeight >= $dataInner.outerHeight() ) {
				$btn.hide();
				$data.addClass( 'active' ).height( 'auto' );
			} else {
				$btn.show();
				$data.removeClass( 'active' ).height( collapseHeight );
			}

			$btn.off( 'click' ).on( 'click', function() {
				var isActive = $data.hasClass( 'active' );

				if ( isActive ) {
					$data.stop().animate( { height: collapseHeight }, duration );
					updateText( config.expand_text );
					updateIcons( 'expand' );
				} else {
					$data.stop().animate( { height: $dataInner.outerHeight() }, duration, function() {
						$data.height( 'auto' );
					});
					updateText( config.collapse_text );
					updateIcons( 'collapse' );
				}

				$data.toggleClass( 'active' );

				if ( isActive ) {
					requestAnimationFrame(function() {
						$data[0].scrollIntoView({ block: 'center', behavior: 'smooth' });
					});
				}
			});
		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-unfold.default', handler );
	});

})(jQuery);
