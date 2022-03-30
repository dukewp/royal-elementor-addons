<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMenuCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Menu_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-menu-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Menu Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-menu-cart', 'product', 'menu', 'cart' ];//tmp
	}

	// public function get_script_depends() {
	// 	return [ 'flexslider', 'zoom', 'wc-single-product'  ];
	// }

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_menu_cart_button',
			[
				'label' => esc_html__( 'Cart Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'menu_cart_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'menu_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a46497',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'menu_cart_bg_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a46497',
				'selectors' => [
					'{{WRAPPER}} .wpr-menu-cart-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
            'apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply Changes</button></div>',
            ]
        );
    } 

	public function render_menu_cart_toggle() {

		if ( null === WC()->cart ) {
			return;
		}

		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		?>

		<div class="wpr-menu-cart-toggle-wrap">
			<button id="wpr-menu-cart-toggle-btn" href="#" class="wpr-menu-cart-toggle-btn" aria-expanded="false">
				<span class="wpr-menu-cart-btn-text"><?php echo $sub_total; ?></span>
				<span class="wpr-menu-cart-btn-icon" <?php echo $counter_attr; ?>>
					<i class="eicon">
                        <span class="wpr-menu-cart-icon-count"><?php echo $product_count ?></span>
                    </i>
					<span class=""><?php esc_html_e( 'Cart', 'wpr-addons' ); ?></span>
				</span>
			</button>
		</div>
		<?php
	}

	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		$is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		?>
		<div class="wpr-menu-cart">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
				<div class="">
					<div class="" aria-hidden="true">
						<div class="" aria-hidden="true">
							<div class=""></div>
							<div class="widget_shopping_cart_content">
								<?php if ( $is_edit_mode ) {
									woocommerce_mini_cart();
								} ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
    
    protected function render() {
        echo '<div class="wpr-menu-cart-wrap">';
            $this->render_menu_cart_toggle();
            $this->render_menu_cart();
        echo '</div>';
    }    
}        
