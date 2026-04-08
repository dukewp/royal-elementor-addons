<?php
namespace WprAddons\Admin\AiBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_AI_Builder {

	const VERSION = '1.0.0';

	public function __construct() {
		$this->load_includes();
		$this->init_hooks();
	}

	private function load_includes() {
		$dir = __DIR__ . '/includes/';
		require_once $dir . 'class-ai-client.php';
		require_once $dir . 'class-prompt-builder.php';
		require_once $dir . 'class-elementor-builder.php';
		require_once $dir . 'class-page-creator.php';
		require_once $dir . 'class-ajax-handler.php';
	}

	private function init_hooks() {
		add_action( 'admin_menu', [ $this, 'register_menu' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		new Includes\WPR_AI_Ajax_Handler();
	}

	public function register_menu() {
		add_submenu_page(
			'wpr-addons',
			'AI Builder',
			'AI Builder',
			'manage_options',
			'wpr-ai-builder',
			[ $this, 'render_page' ]
		);
	}

	public function enqueue_assets( $hook ) {
		if ( false === strpos( $hook, 'wpr-ai-builder' ) ) {
			return;
		}

		wp_enqueue_style(
			'wpr-ai-builder',
			WPR_ADDONS_URL . 'admin/ai-builder/assets/css/ai-builder.css',
			[],
			self::VERSION
		);

		wp_enqueue_script(
			'wpr-ai-builder',
			WPR_ADDONS_URL . 'admin/ai-builder/assets/js/ai-builder.js',
			[ 'jquery' ],
			self::VERSION,
			true
		);

		wp_localize_script( 'wpr-ai-builder', 'wprAiBuilder', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wpr-ai-builder' ),
			'catalog' => $this->get_catalog(),
		] );
	}

	public function render_page() {
		include __DIR__ . '/ai-builder-page.php';
	}

	public function get_catalog() {
		$catalog_file = __DIR__ . '/catalog.json';
		if ( ! file_exists( $catalog_file ) ) {
			return [];
		}
		$data = json_decode( file_get_contents( $catalog_file ), true );
		return is_array( $data ) ? $data : [];
	}

	public static function get_ai_provider() {
		return get_option( 'wpr_ai_provider', 'claude' );
	}

	public static function get_ai_api_key() {
		$provider = self::get_ai_provider();
		$key = get_option( 'wpr_ai_' . $provider . '_key', '' );
		if ( empty( $key ) ) {
			return '';
		}
		return self::decrypt_key( $key );
	}

	public static function get_ai_model() {
		return get_option( 'wpr_ai_model', 'claude-sonnet-4-6' );
	}

	public static function encrypt_key( $key ) {
		if ( empty( $key ) ) return '';
		$salt = wp_salt( 'auth' );
		$iv = substr( md5( $salt ), 0, 16 );
		return base64_encode( openssl_encrypt( $key, 'aes-256-cbc', $salt, 0, $iv ) );
	}

	public static function decrypt_key( $encrypted ) {
		if ( empty( $encrypted ) ) return '';
		$salt = wp_salt( 'auth' );
		$iv = substr( md5( $salt ), 0, 16 );
		return openssl_decrypt( base64_decode( $encrypted ), 'aes-256-cbc', $salt, 0, $iv );
	}
}
