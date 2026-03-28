(function($) {
	"use strict";

	var handler = function($scope) {

			var $contentToggle = $( '.wpr-content-toggle', $scope ).first(),
				$switcherContainer = $( '.wpr-switcher-container', $contentToggle ).first(),
				$switcherWrap = $( '.wpr-switcher-wrap', $contentToggle ).first(),
				$contentWrap = $( '.wpr-switcher-content-wrap', $contentToggle ).first(),
				$switcherBg = $( '> .wpr-switcher-bg', $switcherWrap ),
				$switcherList = $( '> .wpr-switcher', $switcherWrap ),
				$contentList = $( '> .wpr-switcher-content', $contentWrap );

			// Active Tab
			var activeSwitcherIndex = parseInt( $switcherContainer.data('active-switcher') ) - 1;
			
			$switcherList.eq( activeSwitcherIndex ).addClass( 'wpr-switcher-active' );
			$contentList.eq( activeSwitcherIndex ).addClass( 'wpr-switcher-content-active wpr-animation-enter' );
	  
			function wprSwitcherBg( index ) {
				
				if ( ! $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
				
					var switcherWidth = 100 / $switcherList.length,
						switcherBgDistance = index * switcherWidth;

					$switcherBg.css({
						'width' : switcherWidth + '%',
						'left': switcherBgDistance + '%'
					});
				}
			  
			}

			wprSwitcherBg( activeSwitcherIndex );

			// Tab Switcher
			function wprTabsSwitcher( index ) {
				var activeSwitcher = $switcherList.eq( index ),
					activeContent = $contentList.eq( index ),
					activeContentHeight = 'auto';

				// Switcher
				wprSwitcherBg( index );

				if ( ! $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
					$switcherList.removeClass( 'wpr-switcher-active' );
					activeSwitcher.addClass( 'wpr-switcher-active' );

					if ( $scope.hasClass( 'wpr-switcher-style-dual' ) ) {
						$switcherContainer.attr( 'data-active-switcher', index + 1 );
					}
				}

				// Tabs
				$contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

				$contentList.removeClass( 'wpr-switcher-content-active wpr-animation-enter' );

				activeContentHeight = activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );

				activeContent.addClass( 'wpr-switcher-content-active wpr-animation-enter' );

				$contentWrap.css({ 'height': activeContentHeight });

				setTimeout( function() {  
					$contentWrap.css( { 'height': 'auto' } );
				}, 500 );

			}

			// Tab Click Event
			function wprTabsClick() {

				// Outer Labels
				if ( $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
					$switcherWrap.on( 'click', function() {
						var activeSwitcher = $switcherWrap.find( '.wpr-switcher-active' );

						if ( 1 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
							// Reset
							$switcherWrap.children( '.wpr-switcher' ).eq(0).removeClass( 'wpr-switcher-active' );

							// Set Active
							$switcherWrap.children( '.wpr-switcher' ).eq(1).addClass( 'wpr-switcher-active' );
							$switcherWrap.closest( '.wpr-switcher-container' ).attr( 'data-active-switcher', 2 );
							wprTabsSwitcher( 1 );

						} else if ( 2 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
							// Reset
							$switcherWrap.children( '.wpr-switcher' ).eq(1).removeClass( 'wpr-switcher-active' );

							// Set Active
							$switcherWrap.children( '.wpr-switcher' ).eq(0).addClass( 'wpr-switcher-active' );
							$switcherWrap.closest( '.wpr-switcher-container' ).attr( 'data-active-switcher', 1 );
							wprTabsSwitcher( 0 );
						}
					 
						// wprTabsSwitcher( switcherIndex );

					});

				// Inner Labels / Multi Labels
				} else {
					$switcherList.on( 'click', function() {

						var switcherIndex = $( this ).data( 'switcher' ) - 1;
					 
						wprTabsSwitcher( switcherIndex );

					});
				}
			}

			wprTabsClick();

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-content-toggle.default', handler );
	});

})(jQuery);
