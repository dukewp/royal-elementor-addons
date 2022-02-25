<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductSalesBadge\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_SalesBadge extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-sales-badge';
	}

	public function get_title() {
		return esc_html__( 'Product Sales Badge', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-info';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-sales-badge', 'product', 'sales-badge', 'sales', 'badge' ];//tmp
	}

	protected function _register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_sales_badge_styles',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
		// var_dump($settings);
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );;

        echo '<div class="wpr-product-sales-badge">';
        echo '</div>';
    }

}