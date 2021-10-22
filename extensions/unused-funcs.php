<#
            view.addRenderAttribute(
                'wrapper',
                    {
                        'data-wpr-sticky-header': 'settings.enable_sticky_header',
                        'data-wpr-position-type': settings.position_type,
                    }
            );
        #>

        /////
viewportWidth = $( 'body' ).prop( 'clientWidth' ),

        //////
for ( var i=0; i < prefixClass.length - 1; i++ ) {

if ( -1 !== prefixClass[i].search(/mobile\d/) ) {
    columnsMobile = prefixClass[i].slice(-1);
}

if ( -1 !== prefixClass[i].search(/mobile_extra\d/) ) {
    columnsMobileExtra = prefixClass[i].slice(-1);
}

if ( -1 !== prefixClass[i].search(/tablet\d/) ) {
    columnsTablet = prefixClass[i].slice(-1);
}

if ( -1 !== prefixClass[i].search(/tablet_extra\d/) ) {
    columnsTabletExtra = prefixClass[i].slice(-1);
}

if ( -1 !== prefixClass[i].search(/widescreen\d/) ) {
    columnsWideScreen = prefixClass[i].slice(-1);
}

if ( -1 !== prefixClass[i].search(/laptop\d/) ) {
    columnsLaptop = prefixClass[i].slice(-1);
}
}

/////
if ( 440 >= viewportWidth ) {
					columns = columnsMobile;
				// Mobile Extra
				} else if ( 768 >= viewportWidth ) {
					columns = (columnsMobileExtra) ? columnsMobileExtra : columnsTablet;

				// Tablet
				} else if ( 881 >= viewportWidth ) {
					columns = columnsTablet;
				// Tablet Extra
				} else if ( 1025 >= viewportWidth ) {
					columns = (columnsTabletExtra) ? columnsTabletExtra : columnsTablet;

				// Laptop
				} else if ( 1201 >= viewportWidth ) {
					columns = (columnsLaptop) ? columnsLaptop : columnsDesktop;

				// Desktop
				} else if ( 1920 >= viewportWidth ) {
					columns = columnsDesktop;

				// Larger Screens
				} else if ( 2300 >= viewportWidth ) {
					columns = columnsDesktop + 1;
				} else if ( 2650 >= viewportWidth ) {
					columns = (columnsWideScreen) ? columnsWideScreen : columnsDesktop + 2;
				} else if ( 3000 >= viewportWidth ) {
					columns = columnsDesktop + 3;
				} else {
					columns = columnsDesktop + 4;
				}
