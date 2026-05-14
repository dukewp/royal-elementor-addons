<?php
/**
 * WordPress 7.0 Abilities API + MCP integration for Royal Elementor Addons.
 *
 * Exposes a small surface so AI agents (Claude / ChatGPT / Gemini through the
 * MCP Adapter, or any plugin using wp_ai_client_prompt()) can discover and
 * trigger Royal Elementor Addons features such as listing and importing
 * template kits, inspecting widgets/extensions, and toggling extensions.
 *
 * Loads silently on WordPress < 7.0 (no Abilities API available).
 *
 * @package WprAddons
 */

namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WprAddons\Admin\Templates\Library\WPR_Templates_Data;
use WprAddons\Classes\Utilities;

class WPR_AI_Abilities {

	/**
	 * Namespace used for all REA abilities and the MCP server slug.
	 */
	const NS = 'wpr-addons';

	/**
	 * Boot the integration.
	 */
	public static function init() {
		// Abilities API arrived in WP 6.9 / shipped broadly in 7.0.
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		add_action( 'wp_abilities_api_categories_init', [ __CLASS__, 'register_category' ] );
		add_action( 'wp_abilities_api_init', [ __CLASS__, 'register_abilities' ] );
		add_action( 'mcp_adapter_init', [ __CLASS__, 'register_mcp_server' ] );

		// The MCP Adapter plugin's bootstrap runs has_dependencies() at file-load
		// time. If mcp-adapter loads alphabetically before the plugin that
		// provides wp_register_ability() (Abilities API, or AI Experiments etc.)
		// it bails and never queues its rest_api_init priority-15 hook. Force
		// the singleton to instantiate now (when all plugins are guaranteed
		// loaded) so the hook gets queued and our server can register.
		add_action( 'plugins_loaded', [ __CLASS__, 'ensure_mcp_adapter_bootstrapped' ], 20 );

		add_filter( 'rest_pre_dispatch', [ __CLASS__, 'trace_pre_dispatch' ], 10, 3 );
		add_filter( 'rest_post_dispatch', [ __CLASS__, 'trace_post_dispatch' ], 10, 3 );
	}

	/**
	 * Trace log file path. Written by REST sub-requests, read by admin page.
	 * Using a file avoids object-cache cross-process visibility surprises.
	 */
	public static function trace_log_path() {
		return defined( 'WP_CONTENT_DIR' ) ? rtrim( WP_CONTENT_DIR, '/\\' ) . '/wpr-mcp-trace.log' : '';
	}

	private static function trace_append( $stage, $extra = [] ) {
		$file = self::trace_log_path();
		if ( '' === $file ) {
			return;
		}
		$line = wp_json_encode( array_merge( [
			'stage' => $stage,
			'time'  => microtime( true ),
			'pid'   => function_exists( 'getmypid' ) ? getmypid() : 0,
		], $extra ) );
		@file_put_contents( $file, $line . "\n", FILE_APPEND );
	}

	public static function trace_pre_dispatch( $result, $server, $request ) {
		$route = is_object( $request ) && method_exists( $request, 'get_route' ) ? (string) $request->get_route() : '';
		if ( false === strpos( $route, 'wpr-addons-mcp' ) && false === strpos( $route, '/mcp/' ) ) {
			return $result;
		}
		self::trace_append( 'pre_dispatch', [
			'route'   => $route,
			'method'  => is_object( $request ) ? $request->get_method() : '',
			'user_id' => function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0,
		] );
		return $result;
	}

	public static function trace_post_dispatch( $response, $server, $request ) {
		$route = is_object( $request ) && method_exists( $request, 'get_route' ) ? (string) $request->get_route() : '';
		if ( false === strpos( $route, 'wpr-addons-mcp' ) && false === strpos( $route, '/mcp/' ) ) {
			return $response;
		}
		$status = ( is_object( $response ) && method_exists( $response, 'get_status' ) ) ? (int) $response->get_status() : 0;
		self::trace_append( 'post_dispatch', [ 'route' => $route, 'status' => $status ] );
		return $response;
	}

	/**
	 * Force the MCP Adapter singleton to instantiate even if its own bootstrap
	 * bailed due to plugin load order. Safe no-op when the adapter already
	 * bootstrapped itself (singleton just returns the existing instance).
	 */
	public static function ensure_mcp_adapter_bootstrapped() {
		if ( ! class_exists( '\\WP\\MCP\\Core\\McpAdapter' ) ) {
			set_transient( 'wpr_ai_mcp_bootstrap_diag', [
				'time'   => time(),
				'result' => 'McpAdapter class missing',
			], 600 );
			return;
		}

		try {
			\WP\MCP\Core\McpAdapter::instance();
			set_transient( 'wpr_ai_mcp_bootstrap_diag', [
				'time'   => time(),
				'result' => 'instance() called',
			], 600 );
		} catch ( \Throwable $e ) {
			set_transient( 'wpr_ai_mcp_bootstrap_diag', [
				'time'   => time(),
				'result' => 'exception',
				'error'  => $e->getMessage(),
			], 600 );
		}
	}

	/**
	 * Register the REA ability category.
	 */
	public static function register_category() {
		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			self::NS,
			[
				'label'       => __( 'Royal Elementor Addons', 'wpr-addons' ),
				'description' => __( 'Discover, import, and configure Royal Elementor Addons template kits, widgets and extensions.', 'wpr-addons' ),
			]
		);
	}

	/**
	 * Register all REA abilities.
	 */
	public static function register_abilities() {
		foreach ( self::abilities() as $name => $args ) {
			wp_register_ability( $name, $args );
		}
	}

	/**
	 * Register the MCP server exposing the REA abilities.
	 *
	 * @param mixed $adapter MCP adapter instance provided by the action.
	 */
	public static function register_mcp_server( $adapter ) {
		$diag = [
			'time'                 => time(),
			'adapter_is_object'    => is_object( $adapter ),
			'adapter_class'        => is_object( $adapter ) ? get_class( $adapter ) : null,
			'has_create_server'    => is_object( $adapter ) && method_exists( $adapter, 'create_server' ),
		];

		if ( ! is_object( $adapter ) || ! method_exists( $adapter, 'create_server' ) ) {
			set_transient( 'wpr_ai_mcp_register_diag', $diag, 600 );
			return;
		}

		$ability_names = array_keys( self::abilities() );

		// Allow stripping tools entirely so we can confirm whether ability-as-tool
		// conversion is what makes our endpoint hang. Triggered by either a
		// query var (?wpr_mcp_no_tools=1) or a defined PHP constant. The query
		// var only takes effect for the request that carries it (e.g. the
		// loopback POST issued by the self-test panel).
		$strip_tools = ( defined( 'WPR_MCP_NO_TOOLS' ) && WPR_MCP_NO_TOOLS )
			|| ! empty( $_GET['wpr_mcp_no_tools'] )
			|| ! empty( $_REQUEST['wpr_mcp_no_tools'] );

		if ( $strip_tools ) {
			$ability_names = [];
		}

		self::trace_append( 'before_create_server', [
			'strip_tools'   => (bool) $strip_tools,
			'ability_count' => count( $ability_names ),
		] );

		$transports     = [];
		$error_handler  = null;
		$transport_cls  = '\\WP\\MCP\\Transport\\HttpTransport';
		$err_handler_cls = '\\WP\\MCP\\Infrastructure\\ErrorHandling\\ErrorLogMcpErrorHandler';

		if ( class_exists( $transport_cls ) ) {
			$transports[] = $transport_cls;
		}
		if ( class_exists( $err_handler_cls ) ) {
			$error_handler = $err_handler_cls;
		}

		$diag['transports']     = $transports;
		$diag['error_handler']  = $error_handler;
		$diag['ability_count']  = count( $ability_names );

		$result = $adapter->create_server(
			self::NS . '-mcp',
			self::NS . '-mcp',
			'mcp',
			__( 'Royal Elementor Addons', 'wpr-addons' ),
			__( 'Browse and import REA template kits, inspect widgets, and toggle extensions through MCP.', 'wpr-addons' ),
			defined( 'WPR_ADDONS_VERSION' ) ? WPR_ADDONS_VERSION : '1.0.0',
			$transports,
			$error_handler,
			null,
			$ability_names
		);

		if ( is_wp_error( $result ) ) {
			$diag['result']   = 'WP_Error';
			$diag['code']     = $result->get_error_code();
			$diag['message']  = $result->get_error_message();
		} else {
			$diag['result']   = 'ok';
		}

		self::trace_append( 'after_create_server', [
			'result' => $diag['result'],
		] );

		set_transient( 'wpr_ai_mcp_register_diag', $diag, 600 );

		add_action( 'rest_api_init', static function () {
			$routes  = function_exists( 'rest_get_server' ) ? rest_get_server()->get_routes() : [];
			$matched = [];
			foreach ( $routes as $path => $_ ) {
				if ( false !== strpos( $path, 'wpr-addons' ) || false !== strpos( $path, 'mcp' ) ) {
					$matched[] = $path;
				}
			}
			set_transient( 'wpr_ai_mcp_route_check', [
				'time'    => time(),
				'matched' => $matched,
				'total'   => count( $routes ),
			], 600 );
		}, 200 );
	}

	/**
	 * Build the public MCP endpoint URL for the REA server.
	 *
	 * Returns e.g. http://example.local/wp-json/wpr-addons-mcp/mcp
	 */
	public static function get_mcp_endpoint_url() {
		return rest_url( self::NS . '-mcp/mcp' );
	}

	/**
	 * Definition of all REA abilities.
	 *
	 * @return array<string,array>
	 */
	private static function abilities() {
		$mcp_tool = [ 'mcp' => [ 'public' => true, 'type' => 'tool' ] ];

		return [
			self::NS . '/list-template-kits'   => [
				'label'              => __( 'List template kits', 'wpr-addons' ),
				'description'        => __( 'List Royal Elementor Addons template kits with their slug, name, tags, price, and supported builders. Optionally filter by price (free|premium|mixed) or a tag substring.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'properties' => [
						'price' => [
							'type'        => 'string',
							'enum'        => [ 'mixed', 'free', 'premium' ],
							'description' => 'Filter kits by price.',
						],
						'tag'   => [
							'type'        => 'string',
							'description' => 'Case-insensitive substring matched against the kit tags field.',
						],
						'limit' => [
							'type'        => 'integer',
							'minimum'     => 1,
							'maximum'     => 200,
							'default'     => 50,
							'description' => 'Maximum number of kits to return.',
						],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_list_template_kits' ],
				'permission_callback' => [ __CLASS__, 'can_view' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/search-template-kits' => [
				'label'              => __( 'Search template kits', 'wpr-addons' ),
				'description'        => __( 'Free-text search across REA template kit slug, name, and tags. Returns the best matches.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'required'   => [ 'query' ],
					'properties' => [
						'query' => [ 'type' => 'string', 'minLength' => 2 ],
						'limit' => [ 'type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10 ],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_search_template_kits' ],
				'permission_callback' => [ __CLASS__, 'can_view' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/import-template-kit'  => [
				'label'              => __( 'Import a template kit (content only)', 'wpr-addons' ),
				'description'        => __( 'Download and import the XML content of a Royal Elementor Addons template kit by slug. This step alone does NOT switch theme, activate dependent plugins, or set the home page. Use install-kit for the full pipeline.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'required'   => [ 'slug' ],
					'properties' => [
						'slug'    => [
							'type'        => 'string',
							'description' => 'Kit slug from list-template-kits (e.g. "christmas", "newyear", "logistic"). The combined form ("christmas-v1") is also accepted.',
						],
						'version' => [
							'type'        => 'string',
							'description' => 'Version key like "v1". Concatenated with slug as "{slug}-{version}" to locate the remote XML. Defaults to v1.',
							'default'     => 'v1',
						],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_import_template_kit' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/finalize-kit-setup'  => [
				'label'              => __( 'Finalize an imported kit', 'wpr-addons' ),
				'description'        => __( 'Run wpr_final_settings_setup standalone after a content-only import-template-kit call. Assigns header/footer/archive/single conditions, sets the home page, imports Elementor site settings, deletes the Hello World post, and regenerates image sizes. Requires that wpr-import-kit-id is set (import-template-kit sets it for you).', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'properties' => [
						'kit_identifier' => [
							'type'        => 'string',
							'description' => 'Override the wpr-import-kit-id option, e.g. "christmas-v1". Optional; if omitted, uses whatever the last import-template-kit call stored.',
						],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_finalize_kit_setup' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/install-kit'         => [
				'label'              => __( 'Install a template kit (full pipeline)', 'wpr-addons' ),
				'description'        => __( 'Run the full Royal Elementor Addons kit install pipeline equivalent to clicking "Import" in wp-admin: optionally reset previous import, switch to royal-elementor-kit theme, activate required plugins, fix Royal compatibility, import the kit XML, then run final settings (home page, header/footer, site settings). DESTRUCTIVE — switches theme and deactivates unrelated plugins by default. Always use on a fresh or throwaway site first.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'required'   => [ 'slug' ],
					'properties' => [
						'slug'             => [
							'type'        => 'string',
							'description' => 'Kit slug from list-template-kits, e.g. "christmas". The combined form "christmas-v1" is also accepted.',
						],
						'version'          => [
							'type'        => 'string',
							'description' => 'Version, e.g. "v1". Concatenated with slug.',
							'default'     => 'v1',
						],
						'reset'            => [
							'type'        => 'boolean',
							'description' => 'Delete any previously imported items first (posts/pages/menus marked with _wpr_demo_import_item).',
							'default'     => true,
						],
						'switch_theme'     => [
							'type'        => 'boolean',
							'description' => 'Switch to royal-elementor-kit theme if no Royal premium theme is active.',
							'default'     => true,
						],
						'fix_compatibility' => [
							'type'        => 'boolean',
							'description' => 'Deactivate plugins not on the REA known-good list. Destructive!',
							'default'     => true,
						],
						'plugins'          => [
							'type'        => 'array',
							'items'       => [ 'type' => 'string' ],
							'description' => 'Plugins to activate before import. Allowed values: "woocommerce", "contact-form-7", "media-library-assistant", "royal-backup-reset".',
							'default'     => [],
						],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_install_kit' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/list-widgets'         => [
				'label'              => __( 'List REA widgets', 'wpr-addons' ),
				'description'        => __( 'Return the list of Royal Elementor Addons widgets with their slug, display name, and whether they are currently enabled.', 'wpr-addons' ),
				'category'           => self::NS,
				'execute_callback'   => [ __CLASS__, 'execute_list_widgets' ],
				'permission_callback' => [ __CLASS__, 'can_view' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/list-extensions'      => [
				'label'              => __( 'List REA extensions', 'wpr-addons' ),
				'description'        => __( 'Return the list of Royal Elementor Addons extensions (Particles, Parallax, Sticky Section, etc.) and whether each is enabled.', 'wpr-addons' ),
				'category'           => self::NS,
				'execute_callback'   => [ __CLASS__, 'execute_list_extensions' ],
				'permission_callback' => [ __CLASS__, 'can_view' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/toggle-extension'     => [
				'label'              => __( 'Toggle a REA extension', 'wpr-addons' ),
				'description'        => __( 'Enable or disable a Royal Elementor Addons extension by slug.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'required'   => [ 'slug', 'enabled' ],
					'properties' => [
						'slug'    => [
							'type'        => 'string',
							'description' => 'Extension slug, e.g. "wpr-particles" or "wpr-sticky-section".',
						],
						'enabled' => [ 'type' => 'boolean' ],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_toggle_extension' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'               => $mcp_tool,
			],
		];
	}

	/* -------------------------------------------------------------------------
	 * Permission helpers
	 * ---------------------------------------------------------------------- */

	public static function can_view() {
		return current_user_can( 'edit_posts' ) || current_user_can( 'manage_options' );
	}

	public static function can_manage() {
		return current_user_can( 'manage_options' );
	}

	/* -------------------------------------------------------------------------
	 * Execute callbacks
	 * ---------------------------------------------------------------------- */

	public static function execute_list_template_kits( $input = [] ) {
		$price = isset( $input['price'] ) ? sanitize_key( $input['price'] ) : '';
		$tag   = isset( $input['tag'] ) ? strtolower( sanitize_text_field( $input['tag'] ) ) : '';
		$limit = isset( $input['limit'] ) ? max( 1, min( 200, (int) $input['limit'] ) ) : 50;

		$kits  = self::flatten_kits();
		$items = [];

		foreach ( $kits as $row ) {
			if ( $price && $row['price'] !== $price ) {
				continue;
			}
			if ( $tag && false === strpos( strtolower( $row['tags'] ), $tag ) ) {
				continue;
			}

			$items[] = $row;

			if ( count( $items ) >= $limit ) {
				break;
			}
		}

		return [ 'count' => count( $items ), 'items' => $items ];
	}

	public static function execute_search_template_kits( $input = [] ) {
		$query = isset( $input['query'] ) ? strtolower( sanitize_text_field( $input['query'] ) ) : '';
		$limit = isset( $input['limit'] ) ? max( 1, min( 50, (int) $input['limit'] ) ) : 10;

		if ( '' === $query ) {
			return [ 'query' => '', 'count' => 0, 'items' => [] ];
		}

		$matches = [];

		foreach ( self::flatten_kits() as $row ) {
			$haystack = strtolower( $row['slug'] . ' ' . $row['name'] . ' ' . $row['tags'] );
			if ( false !== strpos( $haystack, $query ) ) {
				$matches[] = $row;
				if ( count( $matches ) >= $limit ) {
					break;
				}
			}
		}

		return [ 'query' => $query, 'count' => count( $matches ), 'items' => $matches ];
	}

	public static function execute_list_widgets() {
		if ( ! class_exists( '\\WprAddons\\Classes\\Utilities' ) ) {
			return [ 'count' => 0, 'items' => [] ];
		}

		$modules = Utilities::get_registered_modules();
		$items   = [];

		foreach ( $modules as $name => $info ) {
			$slug = isset( $info[0] ) ? (string) $info[0] : '';
			if ( '' === $slug ) {
				continue;
			}

			$items[] = [
				'name'    => (string) $name,
				'slug'    => $slug,
				'enabled' => 'on' === get_option( 'wpr-widget-' . $slug, 'on' ),
				'badge'   => isset( $info[3] ) ? (string) $info[3] : '',
				'docs'    => isset( $info[1] ) ? (string) $info[1] : '',
			];
		}

		return [ 'count' => count( $items ), 'items' => $items ];
	}

	public static function execute_list_extensions() {
		$extensions = [
			'wpr-particles'           => __( 'Particles', 'wpr-addons' ),
			'wpr-parallax-background' => __( 'Parallax Background', 'wpr-addons' ),
			'wpr-parallax-multi-layer'=> __( 'Parallax Multi-Layer', 'wpr-addons' ),
			'wpr-sticky-section'      => __( 'Sticky Section', 'wpr-addons' ),
			'wpr-custom-css'          => __( 'Custom CSS', 'wpr-addons' ),
			'wpr-display-conditions'  => __( 'Display Conditions', 'wpr-addons' ),
			'wpr-column-slider'       => __( 'Column Slider', 'wpr-addons' ),
			'wpr-equal-height'        => __( 'Equal Height', 'wpr-addons' ),
		];

		$items = [];
		foreach ( $extensions as $slug => $label ) {
			$items[] = [
				'slug'    => $slug,
				'label'   => $label,
				'enabled' => 'on' === get_option( $slug, 'on' ),
			];
		}

		return [ 'count' => count( $items ), 'items' => $items ];
	}

	public static function execute_toggle_extension( $input = [] ) {
		$slug    = isset( $input['slug'] ) ? sanitize_key( $input['slug'] ) : '';
		$enabled = ! empty( $input['enabled'] );

		$allowed = [
			'wpr-particles',
			'wpr-parallax-background',
			'wpr-parallax-multi-layer',
			'wpr-sticky-section',
			'wpr-custom-css',
			'wpr-display-conditions',
			'wpr-column-slider',
			'wpr-equal-height',
		];

		if ( ! in_array( $slug, $allowed, true ) ) {
			return new \WP_Error( 'wpr_invalid_extension', sprintf( 'Unknown extension slug: %s', $slug ) );
		}

		update_option( $slug, $enabled ? 'on' : 'off' );

		return [
			'slug'    => $slug,
			'enabled' => $enabled,
		];
	}

	public static function execute_finalize_kit_setup( $input = [] ) {
		self::ensure_kit_function_deps();

		if ( ! function_exists( 'wpr_final_settings_setup' ) ) {
			return new \WP_Error( 'wpr_function_missing', 'wpr_final_settings_setup is not available.' );
		}

		if ( ! empty( $input['kit_identifier'] ) ) {
			update_option( 'wpr-import-kit-id', sanitize_text_field( $input['kit_identifier'] ) );
		}

		$current = get_option( 'wpr-import-kit-id' );
		if ( empty( $current ) ) {
			return new \WP_Error( 'wpr_missing_kit_id', 'wpr-import-kit-id option is empty. Pass kit_identifier (e.g. "christmas-v1") or call import-template-kit first.' );
		}

		$step = self::call_wpr_admin_function( 'wpr_final_settings_setup' );

		return [
			'success'         => empty( $step['error'] ) && empty( $step['redirect_blocked'] ),
			'kit_identifier'  => $current,
			'step'            => $step,
		];
	}

	public static function execute_install_kit( $input = [] ) {
		$slug    = isset( $input['slug'] ) ? sanitize_file_name( $input['slug'] ) : '';
		$version = isset( $input['version'] ) ? sanitize_key( $input['version'] ) : 'v1';
		$reset             = ! isset( $input['reset'] ) || ! empty( $input['reset'] );
		$switch_theme      = ! isset( $input['switch_theme'] ) || ! empty( $input['switch_theme'] );
		$fix_compatibility = ! isset( $input['fix_compatibility'] ) || ! empty( $input['fix_compatibility'] );

		$allowed_plugins = [ 'woocommerce', 'contact-form-7', 'media-library-assistant', 'royal-backup-reset' ];
		$plugins         = [];
		if ( isset( $input['plugins'] ) && is_array( $input['plugins'] ) ) {
			foreach ( $input['plugins'] as $p ) {
				$p = sanitize_key( $p );
				if ( in_array( $p, $allowed_plugins, true ) ) {
					$plugins[] = $p;
				}
			}
		}

		if ( '' === $slug ) {
			return new \WP_Error( 'wpr_missing_slug', 'A kit slug is required.' );
		}

		self::ensure_kit_function_deps();

		$steps = [];

		if ( $reset && function_exists( 'wpr_reset_previous_import' ) ) {
			$steps['reset'] = self::call_wpr_admin_function( 'wpr_reset_previous_import' );
		}

		if ( $switch_theme && function_exists( 'wpr_activate_required_theme' ) ) {
			$steps['switch_theme'] = self::call_wpr_admin_function( 'wpr_activate_required_theme' );
		}

		if ( ! empty( $plugins ) && function_exists( 'wpr_activate_required_plugins' ) ) {
			$steps['plugins'] = [];
			foreach ( $plugins as $plugin ) {
				$steps['plugins'][ $plugin ] = self::call_wpr_admin_function( 'wpr_activate_required_plugins', [ 'plugin' => $plugin ] );
			}
		}

		if ( $fix_compatibility && function_exists( 'wpr_fix_royal_compatibility' ) ) {
			$steps['fix_compatibility'] = self::call_wpr_admin_function( 'wpr_fix_royal_compatibility' );
		}

		$import = self::execute_import_template_kit( [ 'slug' => $slug, 'version' => $version ] );
		$steps['import'] = is_wp_error( $import )
			? [ 'wp_error' => [ 'code' => $import->get_error_code(), 'message' => $import->get_error_message() ] ]
			: $import;

		if ( is_wp_error( $import ) ) {
			return [ 'success' => false, 'slug' => $slug, 'version' => $version, 'steps' => $steps ];
		}

		if ( function_exists( 'wpr_final_settings_setup' ) ) {
			$steps['final'] = self::call_wpr_admin_function( 'wpr_final_settings_setup' );
		}

		return [
			'success' => true,
			'slug'    => $slug,
			'version' => $version,
			'steps'   => $steps,
		];
	}

	public static function execute_import_template_kit( $input = [] ) {
		$slug    = isset( $input['slug'] ) ? sanitize_file_name( $input['slug'] ) : '';
		$version = isset( $input['version'] ) ? sanitize_key( $input['version'] ) : 'v1';

		if ( '' === $slug ) {
			return new \WP_Error( 'wpr_missing_slug', 'A kit slug is required.' );
		}

		if ( ! class_exists( '\\WprAddons\\Admin\\Templates\\Library\\WPR_Templates_Data' ) ) {
			return new \WP_Error( 'wpr_data_missing', 'WPR_Templates_Data is not loaded; visit /wp-admin first.' );
		}

		$kits = WPR_Templates_Data::get_available_kits();
		if ( ! isset( $kits[ $slug ] ) ) {
			return new \WP_Error( 'wpr_unknown_kit', sprintf( 'Unknown kit slug: %s', $slug ) );
		}

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		self::ensure_kit_function_deps();

		if ( ! class_exists( 'WP_Import' ) ) {
			$importer = WPR_ADDONS_PATH . 'admin/import/class-wordpress-importer.php';
			if ( file_exists( $importer ) ) {
				require_once $importer;
			}
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			return new \WP_Error( 'wpr_importer_missing', 'Bundled WordPress Importer class is unavailable.' );
		}

		$identifier = preg_match( '/-v\d+$/', $slug ) ? $slug : $slug . '-' . $version;
		update_option( 'wpr-import-kit-id', $identifier );

		if ( class_exists( '\\WprAddons\\Classes\\Utilities' ) ) {
			add_filter( 'intermediate_image_sizes_advanced', [ new Utilities(), 'disable_extra_image_sizes' ], 10, 3 );
		}

		set_time_limit( 0 );

		$local = self::download_kit_xml( $slug, $version );
		if ( is_wp_error( $local ) ) {
			return $local;
		}

		if ( function_exists( 'wpr_register_cpt_before_import' ) ) {
			wpr_register_cpt_before_import( $slug );
		}

		$wp_import = new \WP_Import( $local, [ 'fetch_attachments' => true ] );

		ob_start();
		$wp_import->run();
		$import_output = ob_get_clean();

		@unlink( $local );

		return [
			'success' => true,
			'slug'    => $slug,
			'version' => $version,
			'notes'   => 'Kit imported. Run list-templates afterwards to inspect new content.',
			'log_excerpt' => is_string( $import_output ) ? substr( wp_strip_all_tags( $import_output ), 0, 2000 ) : '',
		];
	}

	/* -------------------------------------------------------------------------
	 * Internal helpers
	 * ---------------------------------------------------------------------- */

	/**
	 * Flatten the nested kit data structure into a simple list of rows.
	 */
	private static function flatten_kits() {
		if ( ! class_exists( '\\WprAddons\\Admin\\Templates\\Library\\WPR_Templates_Data' ) ) {
			return [];
		}

		$kits = WPR_Templates_Data::get_available_kits();
		$rows = [];

		foreach ( $kits as $slug => $versions ) {
			if ( ! is_array( $versions ) ) {
				continue;
			}

			foreach ( $versions as $ver => $data ) {
				$rows[] = [
					'slug'    => (string) $slug,
					'version' => (string) $ver,
					'name'    => isset( $data['name'] ) ? (string) $data['name'] : '',
					'tags'    => isset( $data['tags'] ) ? (string) $data['tags'] : '',
					'price'   => isset( $data['price'] ) ? (string) $data['price'] : '',
					'pages'   => isset( $data['pages'] ) ? (string) $data['pages'] : '',
				];
			}
		}

		return $rows;
	}

	/**
	 * Make sure the admin-side kit helpers (wpr_reset_previous_import etc.) and
	 * their underlying WP admin APIs (wp_handle_upload, media_sideload_image,
	 * wp_generate_attachment_metadata, file_exists wrappers, ...) are loaded.
	 *
	 * These normally only get included on regular wp-admin requests; when we
	 * hit them from an MCP / REST request we need to require the files
	 * explicitly or attachment-related helpers blow up with
	 * "undefined function" fatals.
	 */
	private static function ensure_kit_function_deps() {
		foreach ( [ 'file.php', 'media.php', 'image.php', 'post.php' ] as $admin_inc ) {
			$path = ABSPATH . 'wp-admin/includes/' . $admin_inc;
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}

		if ( ! function_exists( 'wpr_import_templates_kit' ) ) {
			$file = WPR_ADDONS_PATH . 'admin/templates-kit.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}

	/**
	 * Call one of the existing admin kit functions (wpr_reset_previous_import,
	 * wpr_activate_required_theme, etc.) without letting their internal
	 * wp_send_json_*() / wp_die() calls terminate the request.
	 *
	 * Sets a valid REA kit nonce in $_POST, merges any extra fields, swaps the
	 * wp_die handlers for an exception-throwing handler, captures the JSON
	 * payload via output buffering, then restores everything.
	 *
	 * @param callable $fn   Function name to invoke.
	 * @param array    $post Extra $_POST fields to set for the duration of the call.
	 * @return array         Diagnostic info: { output, parsed, died, error }.
	 */
	private static function call_wpr_admin_function( $fn, $post = [] ) {
		if ( ! is_callable( $fn ) ) {
			return [ 'error' => 'function not callable', 'fn' => $fn ];
		}

		$original_post  = $_POST;
		$_POST          = array_merge( $_POST, $post );
		$_POST['nonce'] = wp_create_nonce( 'wpr-templates-kit-js' );

		$thrower = static function () {
			return static function () {
				throw new \RuntimeException( 'wpr_wp_die' );
			};
		};

		$redirect_catcher = static function ( $location ) {
			throw new \RuntimeException( 'wpr_redirect:' . (string) $location );
		};

		add_filter( 'wp_die_handler', $thrower, 9999 );
		add_filter( 'wp_die_ajax_handler', $thrower, 9999 );
		add_filter( 'wp_die_json_handler', $thrower, 9999 );
		add_filter( 'wp_redirect', $redirect_catcher, 9999, 1 );

		$threw    = false;
		$redirect = null;
		$err      = null;

		ob_start();
		try {
			call_user_func( $fn );
		} catch ( \Throwable $e ) {
			$msg = $e->getMessage();
			if ( 'wpr_wp_die' === $msg ) {
				$threw = true;
			} elseif ( 0 === strpos( $msg, 'wpr_redirect:' ) ) {
				$redirect = substr( $msg, strlen( 'wpr_redirect:' ) );
			} else {
				$err = $msg;
			}
		}
		$output = ob_get_clean();

		remove_filter( 'wp_die_handler', $thrower, 9999 );
		remove_filter( 'wp_die_ajax_handler', $thrower, 9999 );
		remove_filter( 'wp_die_json_handler', $thrower, 9999 );
		remove_filter( 'wp_redirect', $redirect_catcher, 9999 );

		$_POST = $original_post;

		$parsed = null;
		if ( is_string( $output ) && '' !== trim( $output ) ) {
			$maybe = json_decode( trim( $output ), true );
			if ( null !== $maybe ) {
				$parsed = $maybe;
			}
		}

		$result = [
			'fn'   => is_string( $fn ) ? $fn : 'callable',
			'died' => $threw,
		];

		if ( null !== $redirect ) {
			$result['redirect_blocked'] = $redirect;
		}

		if ( $parsed !== null ) {
			$result['parsed'] = $parsed;
		} elseif ( is_string( $output ) && '' !== $output ) {
			$result['output'] = mb_substr( wp_strip_all_tags( $output ), 0, 500 );
		}

		if ( $err !== null ) {
			$result['error'] = $err;
		}

		return $result;
	}

	/**
	 * Download a kit XML to a local sideloaded file without using helpers that
	 * call wp_die() / wp_send_json_error() — those would terminate the MCP
	 * request and leave the caller without a structured error.
	 *
	 * The upstream library serves kits under "{slug}-{version}/main.xml" (e.g.
	 * "christmas-v1/main.xml"). If a caller already passes the combined form
	 * we use it as-is, otherwise we append "-{version}".
	 *
	 * @param string $slug    Kit slug (e.g. "christmas" or "christmas-v1").
	 * @param string $version Version key (defaults to "v1").
	 * @return string|\WP_Error Local file path on success.
	 */
	private static function download_kit_xml( $slug, $version = 'v1' ) {
		$identifier = preg_match( '/-v\d+$/', $slug ) ? $slug : $slug . '-' . $version;

		$random = substr( str_shuffle( '0123456789abcdefghijklmnopqrstuvwxyz' ), 0, 7 );
		$url    = 'https://royal-elementor-addons.com/library/templates-kit/' . rawurlencode( $identifier ) . '/main.xml?=' . $random;

		if ( ! function_exists( 'download_url' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$tmp = download_url( $url );
		if ( is_wp_error( $tmp ) ) {
			return new \WP_Error( 'wpr_download_failed', $tmp->get_error_message() );
		}

		if ( ! is_string( $tmp ) || ! file_exists( $tmp ) || filesize( $tmp ) <= 0 ) {
			if ( is_string( $tmp ) ) {
				@unlink( $tmp );
			}
			return new \WP_Error( 'wpr_download_empty', 'Downloaded kit XML is empty or missing.' );
		}

		// WP_Import only needs a readable file path; we deliberately skip
		// wp_handle_sideload() so its strict mime/upload validation cannot
		// reject the kit XML (e.g. when XML uploads are disabled site-wide).
		// The temporary file is removed by the caller after import.
		return (string) $tmp;
	}
}

WPR_AI_Abilities::init();
