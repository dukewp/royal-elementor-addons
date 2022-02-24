<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMeta\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Meta extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-meta';
	}

	public function get_title() {
		return esc_html__( 'Product Meta', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-info';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-meta', 'product', 'meta' ];//tmp
	}

	protected function _register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_excerpt',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'post_info_layout',
			[
				'label' => esc_html__( 'List Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'options' => [
					'block' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'flex' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
                'prefix_class' => 'wpr-product-rating-',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-meta .product_meta' => 'display: {{VALUE}}; align-items: center;'
                ],
				'default' => 'flex',
				'label_block' => false,
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

        echo '<div class="wpr-product-meta">';
            woocommerce_template_single_meta();
        echo '</div>';
    }
}