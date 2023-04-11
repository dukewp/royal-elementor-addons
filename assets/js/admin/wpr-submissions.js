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

      // Check if the post type is wpr_submissions
      if ($('#postbox-container-1').find('#submitdiv').length > 0) {
        
        // $.ajax({
        //     url: WprSubmissions.ajaxurl,
        //     type: 'POST',
        //     data: {
        //         action: 'wpr_submissions_meta',
        //         nonce: WprSubmissions.nonce,
        //     },
        //     success: function(response) {
        //         console.log(response);
        //         if (response.success) {
        //             console.log(response);
        //             // Add a new field to the submit box
        //             $('<div class="misc-pub-section">Your Custom Field: <span id="your-custom-field">Your Custom Value</span></div>').insertBefore('#major-publishing-actions');
        //         }
        //     },
        // });

        
        // Access the variables
        var form_name = WprSubmissions.form_name;
        var form_id = WprSubmissions.form_id;
        var form_page = WprSubmissions.form_page;
        var form_page_id = WprSubmissions.form_page_id;
        var agent_ip = WprSubmissions.agent_ip
        var user_agent = WprSubmissions.form_agent

        // Use the variables as needed
        console.log(form_name, form_id, form_page, form_page_id);
        $('<div class="misc-pub-section">Form: <a id="wpr-form-meta">'+ form_name + ' (' + form_id + ')' +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Page: <a id="wpr-form-meta">'+ form_page +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User IP: <span id="wpr-form-meta">'+ agent_ip +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User Agent: <span id="wpr-form-meta">'+ user_agent +'</sp></div>').insertBefore('#major-publishing-actions');
    }
});