(function($) {
	"use strict";

	var handler = function($scope) {

			var $tabs = $( '.wpr-tabs', $scope ).first(),
				$tabList = $( '.wpr-tabs-wrap', $tabs ).first(),
				$contentWrap = $( '.wpr-tabs-content-wrap', $tabs ).first(),
				$tabList = $( '> .wpr-tab', $tabList ),
				$contentList = $( '> .wpr-tab-content', $contentWrap ),
				tabsData = $tabs.data('options');

			// Active Tab
			var activeTabIndex = tabsData.activeTab - 1;

			// ?active_tab=tab-index#your-id
			var activeTabIndexFromLocation = window.location.href.indexOf("active_tab=");

			if (activeTabIndexFromLocation > -1) {
				activeTabIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_tab=", '') - 1;
			}

				$tabList.eq( activeTabIndex ).addClass( 'wpr-tab-active' );
				$contentList.eq( activeTabIndex ).addClass( 'wpr-tab-content-active wpr-animation-enter' );

			if ( tabsData.autoplay === 'yes' ) {
				
				var startIndex = activeTabIndex;

				var autoplayInterval = setInterval( function() {

					if ( startIndex < $tabList.length - 1 ) {
						startIndex++;
					} else {
						startIndex = 0;
					}

					wprTabsSwitcher( startIndex );

				}, tabsData.autoplaySpeed );
			}

			if ( 'hover' === tabsData.trigger ) {
				wprTabsHover();
			} else {
				wprTabsClick();
			}

			// Tab Switcher
			function wprTabsSwitcher( index ) {

				var activeTab = $tabList.eq( index ),
					activeContent = $contentList.eq( index ),
					activeContentHeight = 'auto';

				$contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

				$tabList.removeClass( 'wpr-tab-active' );
				activeTab.addClass( 'wpr-tab-active' );

				$contentList.removeClass( 'wpr-tab-content-active wpr-animation-enter' );

				activeContentHeight = activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );


				activeContent.addClass( 'wpr-tab-content-active wpr-animation-enter' );

				$contentWrap.css({ 'height': activeContentHeight });

				setTimeout( function() {  
					$contentWrap.css( { 'height': 'auto' } );
				}, 500 );

			}

			// Tab Click Event
			function wprTabsClick() {

				$tabList.on( 'click', function() {

					var tabIndex = $( this ).data( 'tab' ) - 1;
					
					clearInterval( autoplayInterval );
					wprTabsSwitcher( tabIndex );

				});

			}

			// Tab Hover Event
			function wprTabsHover() {
			   $tabList.hover( function () {

					var tabIndex = $( this ).data( 'tab' ) - 1;

					clearInterval( autoplayInterval );
					wprTabsSwitcher( tabIndex );
				  
				});
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-tabs.default', handler );
	});

})(jQuery);
