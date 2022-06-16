<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductFilters\Widgets;

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

class Wpr_Product_Filters extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-filters';
	}

	public function get_title() {
		return esc_html__( 'Product Filters', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-filter';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'product-filters', 'product', 'filters' ];//tmp
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Filter', 'wpr-addons' ),
            ]
        );

        $filter_by = [
            'search_form' => esc_html__( 'Search Form', 'wpr-addons' ),
            'price_by' => esc_html__( 'Price', 'wpr-addons' ),
            'sort_by' => esc_html__( 'Sort By', 'wpr-addons' ),
            'order_by' => esc_html__( 'Order By', 'wpr-addons' )
        ];
            
		$this->add_control(
			'filter_type',
			[
				'label' => esc_html__( 'Filter Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $filter_by + Utilities::get_woo_taxonomies(),
				'separator' => 'before',
				'label_block' => true,
				'default' => 'search_form',
			]
		);

        $this->end_controls_section();

	}

	public function get_shop_url() {
		global $wp;

        if ( '' == get_option('permalink_structure' ) ) {
            $url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
        } else {
            $url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        }

		return $url;
	}

	public function get_attribute_data( $filter, $attribute, $shop_url ) {
		// Remove Prefix
        if ( 0 === strpos($filter, 'pa_') ) {
            $filter = 'filter_' . wc_attribute_taxonomy_slug( $filter );
        }

		// Get Selected Filters 
		$selected_filters = isset( $_GET[ $filter ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter ] ) ) ) : [];
        $is_filter_active = in_array( $attribute->slug, $selected_filters, true );

		// Get Attribute Link
		$selected_filters = array_map( 'sanitize_title', $selected_filters );
		if ( ! in_array( $attribute->slug, $selected_filters, true ) ) {
			$selected_filters[] = $attribute->slug;
		}
        $url = remove_query_arg( $filter, $shop_url );

		// Remove Already Selected Filters
		foreach ( $selected_filters as $key => $value ) {
            if ( $is_filter_active && $value === $attribute->slug ) {
                unset( $selected_filters[ $key ] );
            }
        }

		// Add New Filters
        if ( ! empty( $selected_filters ) ) {
            asort( $selected_filters );
            $url = add_query_arg( 'wprfilters', '', $url );
			$url = add_query_arg( $filter, implode( ',', $selected_filters ), $url );
            $url = str_replace( '%2C', ',', $url );
		}

		
		// $qv = $_SERVER['QUERY_STRING' ];
		// var_dump(parse_str($qv, $params));

		// $url = add_query_arg( $filter, implode( ',', $selected_filters ), $url );


		return [
			'url' => $url,
			'class' => $is_filter_active ? 'wpr-active-product-filter' : ''
		];
	}

	public function render_product_attributes( $settings ) {
		$attributes = get_terms( $settings['filter_type'] );

		echo '<ul>';

		foreach ( $attributes as $attribute ) {
			$attr_data = $this->get_attribute_data( $settings['filter_type'], $attribute, $this->get_shop_url() );

			echo '<li>';
				echo '<a href="'. esc_url($attr_data['url']) .'" class="'. esc_attr($attr_data['class']) .'">'. esc_html($attribute->name);
					echo '<span> ('. esc_html($attribute->count) .')</span>';
				echo '</a>';
			echo '</li>';
		}
		
		echo '<ul>';
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		echo '<div class="wpr-product-filters">';

		if ( false ) {

		// Attributes
		} else {
			$this->render_product_attributes($settings);
		}

		echo '</div>';
	}
	
}