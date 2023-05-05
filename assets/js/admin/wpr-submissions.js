jQuery(document).ready(function($) {
    $('.page-title-action').remove();
    
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
    
    $('<input>').attr({
        type: 'hidden',
        id: 'wpr_submission_changes',
        name: 'wpr_submission_changes',
    }).appendTo('#post');

    let changes = {};

    $('.wpr-submissions-wrap').each(function() {
        $(this).children().eq(1).attr('readonly', true);
      
        $(this).find('input, textarea').on('change', function () {
            let key = $(this).attr('id');
            let value = $(this).val();

            changes[key] = value; // Store the changes in the changes object
            $('#wpr_submission_changes').val(JSON.stringify(changes));
        });
    });

    $('.wpr-edit-submissions').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#wpr_submission_changes').val('');
        let thisButton = $(this);
        $('.wpr-submissions-wrap').each(function() {
            if ( $(this).children().eq(1).attr('readonly') ) {
                $(this).children().eq(1).attr('readonly', false);
                thisButton.text('Cancel');
            } else {
                $(this).children().eq(1).attr('readonly', true);
                thisButton.text('Edit');
            }
        });
    })

    $('.wpr-submission-unread').closest('tr').addClass('wpr-submission-unread-column');

    // Check if the post type is wpr_submissions
    if ($('#postbox-container-1').find('#submitdiv').length > 0) {

        // Access the variables
        var form_name = WprSubmissions.form_name;
        var form_id = WprSubmissions.form_id;
        var form_page = WprSubmissions.form_page;
        var form_page_id = WprSubmissions.form_page_id;
        var agent_ip = WprSubmissions.agent_ip
        var user_agent = WprSubmissions.form_agent

        // Use the variables as needed
        console.log(form_name, form_id, form_page, form_page_id);
        $('#minor-publishing').remove();
        $('#submitdiv .postbox-header').find('h2').text('Extra Info');
        $('<div class="misc-pub-section">Form: <a href="'+ WprSubmissions.form_page_editor +'" target="_blank">'+ form_name + ' (' + form_id + ')' +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Page: <a href="'+ WprSubmissions.form_page_url +'" target="_blank">'+ form_page +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Created at: <span class="wpr-submissions-meta">'+ WprSubmissions.post_created +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Updated at: <span class="wpr-submissions-meta">'+ WprSubmissions.post_updated +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User IP: <span class="wpr-submissions-meta">'+ agent_ip +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User Agent: <span class="wpr-submissions-meta">'+ user_agent +'</sp></div>').insertBefore('#major-publishing-actions');

        $('#postbox-container-1').css('opacity', 1);
        $('#postbox-container-2').css('opacity', 1);
    }
});