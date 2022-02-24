<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductRating\Widgets;

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

class Wpr_Product_Rating extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-rating';
	}

	public function get_categories() {
		return [ 'wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-rating', 'product', 'rating' ];//tmp
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_rating',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'element_rating_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'flex' => esc_html__('Horizontal'),
					'block' => esc_html__('Vertical'),
				],
                'prefix_class' => 'wpr-product-rating-',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-rating .inner-block' => 'display: {{VALUE}}; align-items: center;'
                ],
				'default' => 'flex',
			]
		);

		$this->add_control(
			'product_rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D2CDCD',
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_score_color',
			[
				'label' => esc_html__( 'Text Color Hover', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} a.woocommerce-review-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_rating_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-woo-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-woo-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_rating_spacing',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Label Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-product-rating-flex .wpr-product-rating a.woocommerce-review-link' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-rating-block .wpr-product-rating a.woocommerce-review-link' => 'margin-top: {{SIZE}}{{UNIT}}; display: block;',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_rating_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-rating .woocommerce-review-link'
			]
		);

		$this->add_responsive_control(
			'shopengine_rating_alignment',
			[
				'label'        => esc_html__('Alignment', 'shopengine'),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => esc_html__('Left', 'shopengine'),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__('Center', 'shopengine'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__('Right', 'shopengine'),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'prefix_class' => 'wpr-product-rating-',
				'default'      => 'left',
                'selectors' => [
                    '{{WRAPPER}}.wpr-product-rating-block .wpr-woo-rating' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}}.wpr-product-rating-block .woocommerce-review-link' => 'text-align: {{VALUE}};'
                ],
				'separator'    => 'before',
			]
		);

        $this->end_controls_section();
    }
    
    public function render_product_rating( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $rating_count = $product->get_rating_count();
		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

        // $rating_icon = '&#9733;';
        $style_class = ' wpr-woo-rating-style-2';
        $rating_icon = '&#9734;';

		echo '<div class="'. esc_attr($class . $style_class) .'">';
			echo '<div class="inner-block">';

				echo '<div class="wpr-woo-rating">';

					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= $rating_amount ) {
							echo '<i class="wpr-rating-icon-full">'. $rating_icon .'</i>';
						} elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
							echo '<i class="wpr-rating-icon-'. ( $rating_amount - $round_rating ) * 10 .'">'. $rating_icon .'</i>';
						} else {
							echo '<i class="wpr-rating-icon-empty">'. $rating_icon .'</i>';
						}
			     	}

				echo '</div>'; ?>

                <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                    (<?php printf( _n( '%s customer review', '%s customer reviews', 10, 'shopengine' ), '<span class="count">' . esc_html( $rating_count ) . '</span>' ); ?>)
                </a>

			<?php echo '</div>';
		echo '</div>';
	}

    protected function render() {
        // \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $class = '';
		// var_dump($settings);
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        echo '<div class="wpr-product-rating">';
            $this->render_product_rating($settings, '');
        echo '</div>';
    }
}