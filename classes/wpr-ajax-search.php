<?php
namespace WprAddons\Classes;


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
        $the_query = new \WP_Query( 
            [
                'posts_per_page' => -1, 
                's' => esc_attr( $_POST['keyword'] ), 
                'post_type' => array('product') 
            ]
        );

        if( $the_query->have_posts() ) :
            echo '<ul>';
                while( $the_query->have_posts() ): $the_query->the_post(); ?>
            
                    <li><a href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title();?></a></li>
            
                <?php endwhile;
            echo '</ul>';

            wp_reset_postdata();  
        endif;
        
        die();
    }
 }

 new WPR_Ajax_Search;