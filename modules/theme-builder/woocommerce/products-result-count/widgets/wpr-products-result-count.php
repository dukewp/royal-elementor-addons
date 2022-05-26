<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductsResultCount\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Products_Result_Count extends Widget_Base {
	
	public function get_name() {
		return 'wpr-products-result-count';
	}

	public function get_title() {
		return esc_html__( 'Product Result Count', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-counter';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'products-result-count', 'product', 'result', 'count' ];//tmp
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_result_count',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
    }

    protected function render() {
        // Get Settings
        $settings = $this->get_settings_for_display();
        // global $product;

        // $product = wc_get_product();

        // if ( empty( $product ) ) {
        //     return;
        // }

        echo '<div class="wpr-products-result-count">';
		    woocommerce_result_count();;
        echo '</div>';
    }
} 