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

		$this->add_control(
			'wpr_orderby_type',
			[
				'label'   => esc_html__('Order By Filter Type', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'dropdown' => esc_html__('Dropdown', 'wpr-addons'),
					'list'     => esc_html__('List', 'wpr-addons'),
				],
				'default' => 'dropdown',
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
		$catalog_orderby_options = [
			'menu_order' => esc_html__('Default sorting', 'wpr-addons'),
			'popularity' => esc_html__('Sort by popularity', 'wpr-addons'),
			'rating'     => esc_html__('Sort by average rating', 'wpr-addons'),
			'date'       => esc_html__('Sort by latest', 'wpr-addons'),
			'price'      => esc_html__('Sort by price: low to high', 'wpr-addons'),
			'price-desc' => esc_html__('Sort by price: high to low', 'wpr-addons'),
			'title'      => esc_html__('Sort by title: a to z', 'wpr-addons'),
			'title-desc' => esc_html__('Sort by title: z to a', 'wpr-addons'),
		];
		
		$orderby = get_query_var('orderby');

        echo '<div class="wpr-products-result-count">';
		    echo woocommerce_result_count();
        echo '</div>'; ?>

		<div class="wpr-filter-orderby-wrap">
			<form action="#" method="get" class="wpr-filter wpr-filter-orderby-<?php echo esc_html__($settings['wpr_orderby_type'], 'wpr-addons') ?>">
				<?php if('dropdown' === $settings['wpr_orderby_type']) : ?>
					<!-- DROPDOWN STYLE -->
					<!-- <i class="wpr-filter-orderby-icon eicon-angle-right"></i> -->
					<select name="orderby" class="orderby" aria-label="<?php echo esc_attr__('Shop order', 'wpr-addons'); ?>">
						<?php foreach($catalog_orderby_options as $id => $name) : ?>
							<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<!-- LIST SELECT STYLE -->
					<?php foreach($catalog_orderby_options as $id => $name) : ?>
						<div class="orderby-input-group">
							<input name="orderby" class="orderby" type="radio"
								id="orderby-<?php echo esc_attr($id); ?>"
								aria-label="<?php echo esc_attr__('Shop order', 'wpr-addons'); ?>"
								<?php checked($orderby, $id); ?>
								value="<?php echo esc_attr($id); ?>"/>
							<label for="orderby-<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</form>
		</div>

   <?php 
    } 
} 