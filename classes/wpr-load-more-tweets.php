<?php
namespace WprAddons\Classes;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Load_More_Tweets setup
 *
 * @since 3.4.6
 */

 class WPR_Load_More_Tweets {

    public function __construct() {
		add_action('wp_ajax_wpr_load_more_tweets', [$this, 'wpr_load_more_tweets_function']);
		add_action('wp_ajax_nopriv_wpr_load_more_tweets', [$this, 'wpr_load_more_tweets_function']);
    }

    public function wpr_load_more_tweets_function() {
        ?>
        <div>works</div>
        <?php
    }
}

new WPR_Load_More_Tweets();