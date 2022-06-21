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
            'price' => esc_html__( 'Price', 'wpr-addons' ),
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

		// WPR Filters
		$url = add_query_arg( 'wprfilters', '', $url );

		// Min/Max.
		if ( isset( $_GET['min_price'] ) ) {
			$url = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $url );
		}

		if ( isset( $_GET['max_price'] ) ) {
			$url = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $url );
		}

		// All current filters.
		if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure, WordPress.CodeAnalysis.AssignmentInCondition.Found
			foreach ( $_chosen_attributes as $name => $data ) {
				$filter_name = wc_attribute_taxonomy_slug( $name );
				if ( ! empty( $data['terms'] ) ) {
					$url = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $url );
				}
				if ( 'or' === $data['query_type'] ) {
					$url = add_query_arg( 'query_type_' . $filter_name, 'or', $url );
				}
			}
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
			$url = add_query_arg( $filter, implode( ',', $selected_filters ), $url );
            $url = str_replace( '%2C', ',', $url );
		}

		return [
			'url' => $url,
			'class' => $is_filter_active ? 'wpr-active-product-filter' : ''
		];
	}

	public function render_product_attributes( $settings ) {
		$attributes = get_terms( $settings['filter_type'] );

		echo '<ul class="wpr-product-filter-attrs">';

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

	public function get_price_range_from_wpdb() {
        global $wpdb;
        $min_query = "SELECT MIN( CAST( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
        $max_query = "SELECT MAX( CAST( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key = '_price'";
        $value_min = $wpdb->get_var( $min_query );
        $value_max = $wpdb->get_var( $max_query );
        return [
            'min_price' => (int)$value_min,
            'max_price' => (int)$value_max,
        ];
    }

	public function render_product_price_slider() {
		wp_enqueue_script( 'wc-price-slider' );
			
		// Round values to nearest 10 by default.
		$step = 1;

		// Find min and max price in current result set.
		$prices = $this->get_price_range_from_wpdb();
		$min_price = $prices['min_price'];
		$max_price = $prices['max_price'];

		// Check to see if we should add taxes to the prices if store are excl tax but display incl.
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

		if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
			$tax_rates = WC_Tax::get_rates('');

			if ( $tax_rates ) {
				$min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
				$max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
			}
		}

		$min_price = floor( $min_price / $step ) * $step;
		$max_price = ceil( $max_price / $step ) * $step;

		// If both min and max are equal, we don't need a slider.
		if ( $min_price === $max_price ) {
			return;
		}

		// Get Current Prices
		$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
		$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

		$form_action = $this->get_shop_url();

		?>

		<form method="get" action="<?php echo esc_url( $form_action ); ?>">
			<div class="wpr-product-filter-price price_slider_wrapper">
				<div class="wpr-product-filter-price-slider price_slider" style="display:none;"></div>
				<div class="wpr-product-filter-price-amount price_slider_amount" data-step="<?php echo esc_attr( $step ); ?>">
					<input type="text" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'wpr-addons' ); ?>" />
					<input type="text" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'wpr-addons' ); ?>" />
					<?php /* translators: Filter: verb "to filter" */ ?>
					<button type="submit" class="button"><?php echo esc_html__( 'Filter', 'wpr-addons' ); ?></button>
					<div class="wpr-product-filter-price-label price_label" style="display:none;">
						<?php echo esc_html__( 'Price:', 'wpr-addons' ); ?> <span class="from"></span> &mdash; <span class="to"></span>
					</div>
					<?php echo wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ); ?>
				</div>
			</div>
		</form>
		
		<?php
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

		// Price
		} elseif ( 'price' === $settings['filter_type'] ) {
			$this->render_product_price_slider();

		// Attributes
		} else {
			$this->render_product_attributes($settings);
		}

		echo '</div>';
	}
	
}