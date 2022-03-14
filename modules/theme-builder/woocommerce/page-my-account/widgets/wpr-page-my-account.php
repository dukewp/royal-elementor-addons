<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageMyAccount\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_My_Account extends Widget_Base {
	
	public function get_name() {
		return 'wpr-my-account';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-my-account';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'account', 'product', 'page', 'account page', 'page checkout', 'My Account' ];
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center;"><button class="elementor-update-preview-button elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply Changes</button></div>',
            ]
        );

		$this->end_controls_section();
    }
	
	private function render_html_front_end() {
		$current_endpoint = $this->get_current_endpoint();
		?>
		<div class="e-my-account-tab e-my-account-tab__<?php echo sanitize_html_class( $current_endpoint ); ?>">
			<span class="elementor-hidden">[[woocommerce_my_account]]</span>
			<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
		</div>
		<?php
	}

    protected function render() {

		// Add actions & filters before displaying our Widget.
		add_action( 'woocommerce_account_navigation', [ $this, 'woocommerce_account_navigation' ], 1 );
		add_filter( 'woocommerce_account_menu_items', [ $this, 'modify_menu_items' ], 10, 2 );
		add_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 2 );
		add_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		add_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		add_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );

		// Display our Widget.
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_html_front_end();
		} else {
			$this->render_html_editor();
		}

		// Remove actions & filters after displaying our Widget.
		remove_action( 'woocommerce_account_navigation', [ $this, 'woocommerce_account_navigation' ], 2 );
		remove_action( 'woocommerce_account_menu_items', [ $this, 'modify_menu_items' ], 10 );
		remove_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 5 );
		remove_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		remove_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		remove_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );
    }
}