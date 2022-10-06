<?php
namespace WprAddons\Classes;

use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Ajax_Search setup
 *
 * @since 3.4.6
 */

 class WPR_Ajax_Search {

    public function __construct() {
        add_action('wp_ajax_data_fetch' , [$this, 'data_fetch']);
        add_action('wp_ajax_nopriv_data_fetch',[$this, 'data_fetch']);
    }

    public function data_fetch(){
		$all_post_types = [];
        foreach(  Utilities::get_custom_types_of( 'post', false ) as $key=>$value ) {
            array_push($all_post_types, $key);
        }

        $the_query = new \WP_Query( 
            [
                'posts_per_page' => -1, 
                's' => sanitize_text_field( $_POST['keyword'] ), 
                'post_type' => $_POST['query_type'] === 'all' ? $all_post_types : array( sanitize_text_field($_POST['query_type']) ), 
            ]
        );

        if( $the_query->have_posts() ) :
            echo '<span class="wpr-close-search"></span>';
            echo '<ul>';
                while( $the_query->have_posts() ): $the_query->the_post(); ?>
            
                    <li>
                        <?php the_post_thumbnail('thumbnail'); ?>
                        <div class="wpr-ajax-search-content">
                            <a href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title();?></a>
                            <p><?php echo wp_trim_words(get_the_content(), sanitize_text_field($_POST['number_of_words'])); ?></p>
                        </div>
                    </li>
            
                <?php endwhile;
            echo '</ul>';

            wp_reset_postdata();  
        endif;
        
        die();
    }
 }

 new WPR_Ajax_Search;