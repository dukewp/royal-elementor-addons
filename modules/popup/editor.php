<?php // A copy of Elementor Canvas page template

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Elementor Instance
$elementor_plugin = \Elementor\Plugin::$instance;

// Custom Template
$template_id = get_the_ID();
$template_terms = get_the_terms( $template_id, 'wpr_template_type' );
$template_type = is_object( $template_terms[0] ) ? $template_terms[0]->slug : false;

// Add Body Class
$elementor_plugin->frontend->add_body_class( 'elementor-template-canvas' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
	<?php
	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
</head>
<body <?php body_class(); ?>>

	<?php

	if ( $elementor_plugin->preview->is_preview_mode() ) {
    	echo '<div class="wpr-template-'. $template_type .'">';//tmp - removed this (popup styling issue) -> id="wpr-'. $template_type .'-id-'. $template_id .'"
		echo '<div class="wpr-template-'. $template_type .'-inner">';
	}

		// Popup Overlay & Close Button
		if ( 'popup' === $template_type ) {
			echo '<div class="wpr-popup-overlay"></div>';
			echo '<div class="wpr-popup-close-btn"><i class="eicon-close"></i></div>';
		}			

		// Template Container
		if ( $elementor_plugin->preview->is_preview_mode() ) {
			echo '<div class="wpr-'. $template_type .'-container">';
		}

		// Popup Image Overlay & Close Button
		if ( 'popup' === $template_type ) {
	    	echo '<div class="wpr-popup-image-overlay"></div>';
			echo '<div class="wpr-popup-close-btn"><i class="eicon-close"></i></div>';
		}

		/**
		 * Before canvas page template content.
		 *
		 * Fires before the content of Elementor canvas page template.
		 *
		 * @since 1.0.0
		 */
		do_action( 'elementor/page_templates/canvas/before_content' );

		// Elementor Editor
		if ( $elementor_plugin->preview->is_preview_mode() ) {
		     $elementor_plugin->modules_manager->get_modules( 'page-templates' )->print_content();

		// Client Side
		} else {
			/**
			 * Csutom canvas page template content.
			 *
			 * Fires instead of the Elementor canvas page template content.
			 */
			do_action( 'elementor/page_templates/canvas/wpr_content' );
		}


		/**
		 * After canvas page template content.
		 *
		 * Fires after the content of Elementor canvas page template.
		 *
		 * @since 1.0.0
		 */
		do_action( 'elementor/page_templates/canvas/after_content' );

		wp_footer();

	if ( $elementor_plugin->preview->is_preview_mode() ) {
		echo '</div">'; // end wpr-'. $template_type .'-container
		echo '</div">'; // wpr-template-'. $template_type .'-inner
		echo '</div">'; // wpr-template-'. $template_type .'
	}

	?>
</body>
</html>
