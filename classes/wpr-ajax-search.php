<?php
namespace WprAddons\Classes;

use Elementor\Utils;
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
                'post_type' => $_POST['query_type'] === 'all' || !wpr_fs()->can_use_premium_code() ? $all_post_types : array( sanitize_text_field($_POST['query_type']) ),
            ]
        );  

        echo '<span class="wpr-close-search"></span>';
        if( $the_query->have_posts() ) :
            echo '<ul>';
                while( $the_query->have_posts() ): $the_query->the_post(); 
                if ( ( !has_post_thumbnail() && 'yes' === sanitize_text_field($_POST['exclude_without_thumb'])) ) : 
                    continue;
                endif;
                ob_start();
                the_post_thumbnail('thumbnail');
                $post_thumb = ob_get_clean();
                ?>

                <li>
                        <?php if ( 'yes' === sanitize_text_field($_POST['wpr_show_ajax_thumbnail']) ) :
                            if ( has_post_thumbnail() ) :
                                echo '<a class="wpr-ajax-img-wrap" target="_blank" href="'. esc_url( get_the_permalink() ) .'">'.  $post_thumb .'</a>';
                            else :
                                echo '<a class="wpr-ajax-img-wrap" target="_blank" href='. esc_url( get_the_permalink() ) .'><img src='.Utils::get_placeholder_image_src().'></a>';
                            endif ;
                        endif ; ?>
                        <div class="wpr-ajax-search-content">
                            <a target="_blank" class="wpr-ajax-title" href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title();?></a>
                            <p class="wpr-ajax-desc"><a target="_blank" href="<?php echo esc_url( the_permalink() ); ?>"><?php echo wp_trim_words(get_the_content(), sanitize_text_field($_POST['number_of_words'])); ?></a></p>
                            <?php if ( sanitize_text_field($_POST['show_view_result_btn']) ) : ?>
                                <a class="wpr-view-result" href="<?php echo esc_url( the_permalink() ); ?>"><?php echo sanitize_text_field($_POST['wpr_view_result_text']) ?></a>
                            <?php endif; ?>
                        </div>
                </li>
                <?php endwhile;
            echo '</ul>';

            wp_reset_postdata();  
            
        else :

            echo '<p class="wpr-no-results">'.esc_html__('No Results Found', 'wpr-addons').'</p>';

        endif;
        
        die();
    }
 }

 new WPR_Ajax_Search;