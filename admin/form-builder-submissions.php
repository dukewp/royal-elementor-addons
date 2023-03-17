<?php

// SUBMISSIONS CPT
add_action('init', 'wpr_submissions');

// function wpr_submissions() {
// 	$args = array(
// 		'show_in_menu' => 'wpr-addons',
// 		'label'        => 'Submissions',
// 		'public'       => true
// 	);
// 	register_post_type( 'wpr_submissions', $args );
// }

// Define the callback function to register the meta box
function my_custom_meta_box() {
	$args = new WP_Query([
		'post_type' => 'wpr_submissions'
	]);
	foreach ( $args as $arg ) {

        add_meta_box(
            'wpr_submission_fields',
            'Royal Submissions',
            'my_meta_box_callback',
            'wpr_submissions',
            'normal',
            'default'
        );
	}
}

// Define the callback function to display the meta box contents
function my_meta_box_callback( $post, $metabox ) {
    foreach (get_post_meta($post->ID) as $key => $value ) {
        
        $prefix = "form_field-";
        $key_title = ucfirst(str_replace($prefix, "", $key));
        
        echo '<label for="my_meta_field">'.  $key_title .': </label>';
        echo '<input style="display: block;" type="text" id="'. $key .'" name="submitter_email" value="'. $value[0] .'">';
    }

    // Display the form field for the custom meta field
}

function wpr_submissions() {
	$args = [
		'labels' => [
			'name' => __( 'Submissions' ),
			'singular_name' => __( 'Submission' )
		],
		// 'show_in_menu' => 'wpr-addons',
		'public' => false,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author' ),
		'register_meta_box_cb' => 'my_custom_meta_box'
	];
	
    register_post_type( 'wpr_submissions', $args);	
}