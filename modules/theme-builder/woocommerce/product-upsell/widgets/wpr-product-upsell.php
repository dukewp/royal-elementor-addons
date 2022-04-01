<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductUpsell\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Upsell extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-upsell';
	}

	public function get_title() {
		return esc_html__( 'Product Upsell', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-upsell';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'product-upsell', 'product', 'upsell' ];//tmp
	}

	protected function register_controls() {
    }

    protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		// $meta_query = WC()->query->get_meta_query();

		// var_dump($meta_query);

		// $my_upsells = $product->get_upsell_ids();
		
		// $args = array(
		// 	'post_type'           => 'product',
		// 	'ignore_sticky_posts' => 1,
		// 	'no_found_rows'       => 1,
		// 	'posts_per_page'      => -1,
		// 	'orderby'             => 'post__in',
		// 	'order'               => 'asc',
		// 	'post__in'            => $my_upsells,
		// 	'meta_query'          => $meta_query
		// );
		
		// $products = new \WP_Query( $args );
		
		// echo '<div class="wpr-upsells-wrap">';
		// while( $products->have_posts() ) : $products->the_post();
		// 	echo get_the_title() . "<br>";
		// endwhile;
		// echo '</div>';

		echo '<div>';
        echo woocommerce_upsell_display();
		echo '</div>';
    }
}    