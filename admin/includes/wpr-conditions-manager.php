<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Conditions_Manager setup
 *
 * @since 1.0
 */
class WPR_Conditions_Manager {

    /**
    ** Archive Templates Conditions
    */
    public static function archive_templates_conditions( $conditions ) {
    	$term_id = '';
    	$term_name = '';
    	$queried_object = get_queried_object();

    	// Get Terms
    	if ( ! is_null( $queried_object ) ) {
    		if ( isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
		        $term_id   = $queried_object->term_id;
		        $term_name = $queried_object->taxonomy;
    		}
    	}

        // Reset
        $template = null;

		// Archive Pages (includes search)
		if ( is_archive() || is_search() ) {
			if ( is_archive() && ! is_search() ) {
				// Author
				if ( is_author() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'archive/author' );
				// Date
				} elseif ( is_date() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'archive/date' );
				// Category
				} elseif ( is_category() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/categories', $term_id );
				// Tag
				} elseif ( is_tag() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/tags', $term_id );
				// Custom Taxonomies
				} elseif ( is_tax() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/'. $term_name, $term_id );
				// Products
				} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/products' );
		        }

			// Search Page
			} else {
	    		$template = Utilities::get_template_slug( $conditions, 'archive/search' );
	        }

	    // Posts Page
		} elseif ( Utilities::is_blog_archive() ) {
			$template = Utilities::get_template_slug( $conditions, 'archive/posts' );
		}

	    return $template;
    }

    /**
    ** Single Templates Conditions
    */
    public static function single_templates_conditions( $conditions, $pages ) {
        global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        // Reset
        $template = null;

		// Single Pages
		if ( is_single() || is_front_page() || is_page() || is_404() ) {

			if ( is_single() ) {
				// Blog Posts
				if ( 'post' == $post_type ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/posts', $post_id );
				// CPT
		        } else {
	    			$template = Utilities::get_template_slug( $conditions, 'single/'. $post_type, $post_id );
		        }
			} else {
				// Front page
				if ( $pages && is_front_page() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/front_page' );
				// Error 404 Page
				} elseif ( is_404() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/page_404' );
				// Single Page
				} elseif ( $pages && is_page() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/pages', $post_id );
		        }
			}

        }

	    return $template;
    }

    /**
    ** Canvas Page Before/After Content
    */
    public static function header_footer_display_conditions( $conditions ) {
    	// Template Type
    	$post_terms = wp_get_post_terms( get_the_ID(), 'wpr_template_type' );
        $template_type = ! empty($post_terms) ? $post_terms[0]->slug : '';

        // Global
        $template = Utilities::get_template_slug( $conditions, 'global' );

        // Custom
        if ( ! empty($conditions) && (sizeof( $conditions ) > 1 || sizeof( reset($conditions) ) > 1) ) {

			// Archive Pages (includes search)
			if ( ! is_null( WPR_Conditions_Manager::archive_templates_conditions( $conditions ) ) ) {
				$template = WPR_Conditions_Manager::archive_templates_conditions( $conditions );
			}

        	// Single Pages
			if ( ! is_null( WPR_Conditions_Manager::single_templates_conditions( $conditions, true ) ) ) {
				$template = WPR_Conditions_Manager::single_templates_conditions( $conditions, true );
			}

        }

	    if ( 'header' !== $template_type && 'footer' !== $template_type && 'popup' !== $template_type ) {
	    	return $template;
	    }
    }
    
}