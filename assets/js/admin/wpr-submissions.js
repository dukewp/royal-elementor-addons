jQuery(document).ready(function($) {
    $('body').on('click', '.column-read_status', function() {
        const post_id = $(this).parent().attr('id').replace('post-', '');
        const read_status = $(this).text() === 'Read' ? '0' : '1';
        const nonce = WprSubmissions.nonce; // Replace 'your_custom_vars' with the variable name you use in the next step (step 8) to localize the script

        $.ajax({
            url: WprSubmissions.ajaxurl,
            type: 'POST',
            data: {
                action: 'wpr_submissions_update_read_status',
                post_id: post_id,
                read_status: read_status,
                nonce: nonce,
            },
            success: function(response) {
                if (response.success) {
                    // if (read_status === '1') {
                    //     $('#post-' + post_id + ' .column-read_status').text('Read');
                    // } else {
                    //     $('#post-' + post_id + ' .column-read_status').text('Unread');
                    // }
                } else {
                    alert('Error updating read status');
                }
            },
        });
    });

    $('.wpr-submission-unread').closest('tr').addClass('wpr-submission-unread-column');
});