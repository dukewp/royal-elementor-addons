jQuery( document ).ready( function() {
    
	jQuery( document ).on( 'click', '.my-dismiss-notice .notice-dismiss-2', function() {
        jQuery(document).find('.my-dismiss-notice').slideUp();
		var data = {
				action: 'my_dismiss_notice',
		};
		
		jQuery.post( notice_params.ajaxurl, data, function() {
		});
	})

    jQuery(document).on( 'click', '.my-dismiss-notice .maybe-later', function() {
        jQuery(document).find('.my-dismiss-notice').fadeOut();
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'maybe_later',
            }
        })
    
    })

    jQuery(document).on( 'click', '.my-dismiss-notice .already-rated', function() {
        jQuery(document).find('.my-dismiss-notice').slideUp();
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'already_rated',
            }
        })
    
    })
});