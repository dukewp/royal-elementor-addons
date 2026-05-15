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
		add_action( 'wpr_ai_install_kit_job', [ __CLASS__, 'cron_run_install_kit' ] );

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

		if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
			add_filter( 'rest_pre_dispatch', [ __CLASS__, 'trace_pre_dispatch' ], 10, 3 );
			add_filter( 'rest_post_dispatch', [ __CLASS__, 'trace_post_dispatch' ], 10, 3 );
		}
	}

	/**
	 * Trace log file path. Written by REST sub-requests, read by admin page.
	 * Using a file avoids object-cache cross-process visibility surprises.
	 */
	public static function trace_log_path() {
		return defined( 'WP_CONTENT_DIR' ) ? rtrim( WP_CONTENT_DIR, '/\\' ) . '/wpr-mcp-trace.log' : '';
	}

	private static function trace_append( $stage, $extra = [] ) {
		if ( ! defined( 'WPR_AI_DEBUG' ) || ! WPR_AI_DEBUG ) {
			return;
		}
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
			if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
				set_transient( 'wpr_ai_mcp_bootstrap_diag', [
					'time'   => time(),
					'result' => 'McpAdapter class missing',
				], 600 );
			}
			return;
		}

		try {
			\WP\MCP\Core\McpAdapter::instance();
			if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
				set_transient( 'wpr_ai_mcp_bootstrap_diag', [
					'time'   => time(),
					'result' => 'instance() called',
				], 600 );
			}
		} catch ( \Throwable $e ) {
			if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
				set_transient( 'wpr_ai_mcp_bootstrap_diag', [
					'time'   => time(),
					'result' => 'exception',
					'error'  => $e->getMessage(),
				], 600 );
			}
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
			if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
				set_transient( 'wpr_ai_mcp_register_diag', $diag, 600 );
			}
			return;
		}

		$ability_names = [];
		foreach ( array_keys( self::abilities() ) as $full_name ) {
			$slug = self::ability_slug_from_full_name( $full_name );
			if ( '' !== $slug && self::is_mcp_tool_enabled( $slug ) ) {
				$ability_names[] = $full_name;
			}
		}

		self::trace_append( 'before_create_server', [
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

		if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
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
	}

	/**
	 * Build the public MCP endpoint URL for the REA server.
	 *
	 * Returns e.g. http://example.local/wp-json/wpr-addons-mcp/mcp
	 */
	public static function get_mcp_endpoint_url() {
		return rest_url( self::NS . '-mcp/mcp' );
	}

	/** Hostname allowed for remote kit XML (see download_kit_xml). */
	const KIT_XML_HOST = 'royal-elementor-addons.com';

	/**
	 * Slug segment after "wpr-addons/" for a full ability name.
	 *
	 * @param string $full_name e.g. wpr-addons/list-template-kits
	 * @return string e.g. list-template-kits or empty if unknown.
	 */
	public static function ability_slug_from_full_name( $full_name ) {
		$prefix = self::NS . '/';
		if ( ! is_string( $full_name ) || strpos( $full_name, $prefix ) !== 0 ) {
			return '';
		}
		return substr( $full_name, strlen( $prefix ) );
	}

	/**
	 * @return list<string>
	 */
	public static function all_ability_names() {
		$out = [];
		foreach ( array_keys( self::abilities() ) as $full ) {
			$s = self::ability_slug_from_full_name( $full );
			if ( '' !== $s ) {
				$out[] = $s;
			}
		}
		return $out;
	}

	/**
	 * Default MCP tool exposure: read-only on, writes off until enabled under Royal Addons → AI & MCP.
	 *
	 * @return array<string,bool>
	 */
	public static function default_mcp_tool_flags() {
		return [
			'list-template-kits'             => true,
			'search-template-kits'           => true,
			'list-widgets'                   => true,
			'list-extensions'                => true,
			'list-rea-widgets-on-page'       => true,
			'get-install-job-status'         => true,
			'import-template-kit'            => false,
			'finalize-kit-setup'             => false,
			'install-kit'                    => false,
			'patch-rea-widget-text-settings' => false,
			'toggle-extension'               => false,
		];
	}

	/**
	 * @return array<string,bool>
	 */
	public static function get_mcp_tool_flags() {
		$defaults = self::default_mcp_tool_flags();
		$stored   = get_option( 'wpr_ai_mcp_tools', [] );
		if ( ! is_array( $stored ) ) {
			$stored = [];
		}
		$flags = $defaults;
		foreach ( $defaults as $slug => $_on ) {
			if ( array_key_exists( $slug, $stored ) ) {
				$flags[ $slug ] = ! empty( $stored[ $slug ] );
			}
		}
		return $flags;
	}

	/**
	 * Whether an ability is exposed as an MCP tool.
	 */
	public static function is_mcp_tool_enabled( $slug ) {
		$flags = self::get_mcp_tool_flags();
		return ! empty( $flags[ $slug ] );
	}

	/**
	 * @return array<string,string> slug => admin label
	 */
	public static function ability_admin_labels() {
		$map = [];
		foreach ( self::abilities() as $full => $args ) {
			$slug = self::ability_slug_from_full_name( $full );
			if ( '' === $slug ) {
				continue;
			}
			$map[ $slug ] = isset( $args['label'] ) ? (string) $args['label'] : $slug;
		}
		return $map;
	}

	/**
	 * @param string $slug Ability slug (suffix).
	 * @return \WP_Error|null
	 */
	private static function gate_destructive_ability( $slug ) {
		if ( ! self::is_mcp_tool_enabled( $slug ) ) {
			return new \WP_Error(
				'wpr_ability_disabled',
				sprintf(
					/* translators: %s: machine-readable ability slug */
					__( 'This ability is disabled. Enable the "%s" tool under Royal Addons → AI & MCP.', 'wpr-addons' ),
					$slug
				)
			);
		}
		return self::enforce_rate_limit( $slug );
	}

	/**
	 * @param string $slug Ability slug.
	 * @return \WP_Error|null
	 */
	private static function enforce_rate_limit( $slug ) {
		$destructive = [ 'import-template-kit', 'finalize-kit-setup', 'install-kit', 'toggle-extension', 'patch-rea-widget-text-settings' ];
		if ( ! in_array( $slug, $destructive, true ) ) {
			return null;
		}
		$uid = (int) get_current_user_id();
		$key = 'wpr_ai_rl_' . $uid . '_' . md5( $slug );
		$n   = (int) get_transient( $key );
		$max = (int) apply_filters( 'wpr_ai_destructive_rate_limit_per_minute', 8, $slug );
		if ( $n >= $max ) {
			return new \WP_Error( 'wpr_rate_limited', __( 'Too many write operations; wait a minute and try again.', 'wpr-addons' ) );
		}
		set_transient( $key, $n + 1, 60 );
		return null;
	}

	/**
	 * @param string               $slug   Ability slug.
	 * @param mixed                $result Execution result.
	 */
	private static function maybe_audit( $slug, $result ) {
		if ( '1' !== get_option( 'wpr_ai_audit_log', '0' ) ) {
			return;
		}
		$destructive = [ 'import-template-kit', 'finalize-kit-setup', 'install-kit', 'toggle-extension', 'patch-rea-widget-text-settings' ];
		if ( ! in_array( $slug, $destructive, true ) ) {
			return;
		}
		if ( $result instanceof \WP_Error ) {
			$status = 'error:' . $result->get_error_code();
		} elseif ( is_array( $result ) && array_key_exists( 'success', $result ) ) {
			$status = ! empty( $result['success'] ) ? 'ok' : 'failed';
		} else {
			$status = 'ok';
		}
		if ( function_exists( 'error_log' ) ) {
			error_log( sprintf( 'WPR_AI_AUDIT user=%d ability=%s %s', (int) get_current_user_id(), $slug, $status ) );
		}
	}

	private static function install_job_transient_key( $job_id ) {
		return 'wpr_ai_inst_' . $job_id;
	}

	/**
	 * @param array $input Raw install-kit input.
	 * @return array
	 */
	private static function sanitize_install_kit_input_for_job( $input ) {
		if ( ! is_array( $input ) ) {
			return [];
		}
		unset( $input['async'], $input['_async_job'] );
		return $input;
	}

	/**
	 * WP-Cron worker for background install-kit jobs.
	 *
	 * @param string $job_id Job identifier.
	 */
	public static function cron_run_install_kit( $job_id ) {
		$job_id = is_string( $job_id ) ? sanitize_key( $job_id ) : '';
		if ( '' === $job_id ) {
			return;
		}
		$key   = self::install_job_transient_key( $job_id );
		$state = get_transient( $key );
		if ( ! is_array( $state ) || empty( $state['input'] ) || ! is_array( $state['input'] ) ) {
			return;
		}

		$state['status']  = 'running';
		$state['updated'] = time();
		set_transient( $key, $state, DAY_IN_SECONDS );

		$uid = isset( $state['user_id'] ) ? (int) $state['user_id'] : 0;
		wp_set_current_user( $uid );

		$result = self::run_install_kit_sync( $state['input'] );

		$state['updated'] = time();
		if ( is_wp_error( $result ) ) {
			$state['status'] = 'error';
			$state['error']  = [
				'code'    => $result->get_error_code(),
				'message' => $result->get_error_message(),
			];
			unset( $state['result'] );
		} elseif ( is_array( $result ) && array_key_exists( 'success', $result ) && empty( $result['success'] ) ) {
			$state['status']  = 'error';
			$state['error']   = [
				'code'    => 'wpr_install_failed',
				'message' => __( 'One or more install steps failed; inspect result.steps in the stored job payload.', 'wpr-addons' ),
			];
			$state['result'] = $result;
		} else {
			$state['status'] = 'complete';
			$state['result'] = $result;
			unset( $state['error'] );
		}
		set_transient( $key, $state, DAY_IN_SECONDS );
		self::maybe_audit( 'install-kit', $result );
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
						'async'            => [
							'type'        => 'boolean',
							'description' => 'If true (default), queue a background job and return job_id immediately so MCP clients can poll get-install-job-status. Set false to run synchronously (may time out remote clients).',
							'default'     => true,
						],
					],
				],
				'execute_callback'   => [ __CLASS__, 'execute_install_kit' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'               => $mcp_tool,
			],

			self::NS . '/get-install-job-status' => [
				'label'              => __( 'Get install-kit job status', 'wpr-addons' ),
				'description'        => __( 'Poll a background install-kit job created when install-kit runs with async true (default). Returns status queued|running|complete|error plus result or error details.', 'wpr-addons' ),
				'category'           => self::NS,
				'input_schema'       => [
					'type'       => 'object',
					'required'   => [ 'job_id' ],
					'properties' => [
						'job_id' => [
							'type'        => 'string',
							'description' => 'job_id returned by install-kit when async is true.',
						],
					],
				],
				'execute_callback'    => [ __CLASS__, 'execute_get_install_job_status' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
				'meta'                => $mcp_tool,
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

			self::NS . '/list-rea-widgets-on-page' => [
				'label'               => __( 'List Royal widgets on an Elementor page', 'wpr-addons' ),
				'description'         => __( 'Scan an Elementor-built page/post for Royal Elementor Addons widgets (widgetType starting with wpr-). Returns element_id, widget type, tree_path, patchable_keys (flat keys plus repeater paths like advanced_accordion.0.accordion_title), and a short text preview.', 'wpr-addons' ),
				'category'            => self::NS,
				'input_schema'        => [
					'type'       => 'object',
					'required'   => [ 'post_id' ],
					'properties' => [
						'post_id' => [
							'type'        => 'integer',
							'minimum'     => 1,
							'description' => 'WordPress post ID of a page or post built with Elementor.',
						],
					],
				],
				'execute_callback'    => [ __CLASS__, 'execute_list_rea_widgets_on_page' ],
				'permission_callback' => [ __CLASS__, 'can_elementor_page_ai' ],
				'meta'                => $mcp_tool,
			],

			self::NS . '/patch-rea-widget-text-settings' => [
				'label'               => __( 'Patch Royal widget text settings (Elementor)', 'wpr-addons' ),
				'description'         => __( 'Update allowlisted plain/rich-text control values on a single Royal Elementor widget (wpr-*) inside an Elementor document. Does not change colors, spacing, typography tokens, or arbitrary JSON. Uses Elementor document save (respects caps and kses). Enable in AI & MCP before use.', 'wpr-addons' ),
				'category'            => self::NS,
				'input_schema'        => [
					'type'       => 'object',
					'required'   => [ 'post_id', 'element_id', 'patches' ],
					'properties' => [
						'post_id'    => [
							'type'        => 'integer',
							'minimum'     => 1,
							'description' => 'Post ID of the Elementor page.',
						],
						'element_id' => [
							'type'        => 'string',
							'description' => 'Elementor element id from list-rea-widgets-on-page (e.g. a1b2c3d).',
						],
						'patches'    => [
							'type'                 => 'object',
							'additionalProperties' => true,
							'description'          => 'Map of setting key => string value. Flat keys (e.g. content_title) or repeater paths (e.g. advanced_accordion.0.accordion_title, testimonial_items.1.testimonial_author). Only allowlisted keys are applied.',
						],
					],
				],
				'execute_callback'    => [ __CLASS__, 'execute_patch_rea_widget_text_settings' ],
				'permission_callback' => [ __CLASS__, 'can_elementor_page_ai' ],
				'meta'                => $mcp_tool,
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

	/**
	 * Users who may use Elementor page introspection / patch abilities (per-post edit_post is still enforced in execute).
	 */
	public static function can_elementor_page_ai() {
		return current_user_can( 'edit_pages' ) || current_user_can( 'edit_posts' ) || current_user_can( 'manage_options' );
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
		$gate = self::gate_destructive_ability( 'toggle-extension' );
		if ( is_wp_error( $gate ) ) {
			self::maybe_audit( 'toggle-extension', $gate );
			return $gate;
		}

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
			$err = new \WP_Error( 'wpr_invalid_extension', sprintf( 'Unknown extension slug: %s', $slug ) );
			self::maybe_audit( 'toggle-extension', $err );
			return $err;
		}

		update_option( $slug, $enabled ? 'on' : 'off' );

		$out = [
			'slug'    => $slug,
			'enabled' => $enabled,
		];
		self::maybe_audit( 'toggle-extension', $out );
		return $out;
	}

	public static function execute_list_rea_widgets_on_page( $input = [] ) {
		$post_id = isset( $input['post_id'] ) ? absint( $input['post_id'] ) : 0;
		if ( $post_id < 1 || ! current_user_can( 'edit_post', $post_id ) ) {
			return new \WP_Error(
				'wpr_forbidden',
				__( 'You do not have permission to edit this content.', 'wpr-addons' )
			);
		}

		$document = self::rea_ai_get_elementor_document( $post_id );
		if ( is_wp_error( $document ) ) {
			return $document;
		}

		$elements = $document->get_elements_data();
		if ( ! is_array( $elements ) ) {
			$elements = [];
		}

		$items = self::rea_ai_flatten_rea_widgets( $elements );

		return [
			'post_id' => $post_id,
			'count'   => count( $items ),
			'items'   => $items,
			'usage'   => __( 'Pick element_id from items, then enable patch-rea-widget-text-settings in Royal Addons → AI & MCP and call it with the same post_id, element_id, and a small patches object (only keys listed under patchable_keys).', 'wpr-addons' ),
		];
	}

	public static function execute_patch_rea_widget_text_settings( $input = [] ) {
		$gate = self::gate_destructive_ability( 'patch-rea-widget-text-settings' );
		if ( is_wp_error( $gate ) ) {
			self::maybe_audit( 'patch-rea-widget-text-settings', $gate );
			return $gate;
		}

		$post_id    = isset( $input['post_id'] ) ? absint( $input['post_id'] ) : 0;
		$element_id = isset( $input['element_id'] ) ? sanitize_text_field( (string) $input['element_id'] ) : '';
		$patches    = isset( $input['patches'] ) && is_array( $input['patches'] ) ? $input['patches'] : [];

		if ( $post_id < 1 || '' === $element_id || [] === $patches ) {
			$err = new \WP_Error( 'wpr_bad_input', __( 'post_id, element_id, and a non-empty patches map are required.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		if ( ! preg_match( '/^[A-Za-z0-9]{6,32}$/', $element_id ) ) {
			$err = new \WP_Error( 'wpr_bad_element_id', __( 'element_id has an unexpected format.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			$err = new \WP_Error( 'wpr_forbidden', __( 'You do not have permission to edit this content.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		if ( strlen( wp_json_encode( $patches ) ) > 20000 ) {
			$err = new \WP_Error( 'wpr_payload_too_large', __( 'patches JSON is too large.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		if ( function_exists( 'wp_check_post_lock' ) && wp_check_post_lock( $post_id ) ) {
			$err = new \WP_Error( 'wpr_post_locked', __( 'Another user is editing this post; try again after they finish.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		$document = self::rea_ai_get_elementor_document( $post_id );
		if ( is_wp_error( $document ) ) {
			self::maybe_audit( 'patch-rea-widget-text-settings', $document );
			return $document;
		}

		if ( ! $document->is_editable_by_current_user() ) {
			$err = new \WP_Error( 'wpr_elementor_readonly', __( 'Elementor does not allow saving this document for the current user.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		$elements = $document->get_elements_data();
		if ( ! is_array( $elements ) ) {
			$elements = [];
		}

		$elements = json_decode( wp_json_encode( $elements ), true );
		if ( ! is_array( $elements ) ) {
			$elements = [];
		}

		$found_type = '';
		$applied    = self::rea_ai_patch_widget_settings_branch( $elements, $element_id, $patches, $found_type );
		if ( is_wp_error( $applied ) ) {
			self::maybe_audit( 'patch-rea-widget-text-settings', $applied );
			return $applied;
		}
		if ( false === $applied ) {
			$err = new \WP_Error( 'wpr_element_not_found', __( 'No matching Royal widget element_id was found on this page.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}
		if ( ! is_array( $applied ) || [] === $applied ) {
			$err = new \WP_Error( 'wpr_no_applied_patches', __( 'No allowlisted keys were updated. Check patchable_keys from list-rea-widgets-on-page.', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		$saved = $document->save( [ 'elements' => $elements ] );
		if ( ! $saved ) {
			$err = new \WP_Error( 'wpr_elementor_save_failed', __( 'Elementor refused to save (permissions or validation).', 'wpr-addons' ) );
			self::maybe_audit( 'patch-rea-widget-text-settings', $err );
			return $err;
		}

		$out = [
			'success'      => true,
			'post_id'      => $post_id,
			'element_id'   => $element_id,
			'widget_type'  => $found_type,
			'applied_keys' => $applied,
		];
		self::maybe_audit( 'patch-rea-widget-text-settings', $out );
		return $out;
	}

	public static function execute_finalize_kit_setup( $input = [] ) {
		$gate = self::gate_destructive_ability( 'finalize-kit-setup' );
		if ( is_wp_error( $gate ) ) {
			self::maybe_audit( 'finalize-kit-setup', $gate );
			return $gate;
		}

		self::ensure_kit_function_deps();

		if ( ! function_exists( 'wpr_final_settings_setup' ) ) {
			$err = new \WP_Error( 'wpr_function_missing', 'wpr_final_settings_setup is not available.' );
			self::maybe_audit( 'finalize-kit-setup', $err );
			return $err;
		}

		if ( ! empty( $input['kit_identifier'] ) ) {
			update_option( 'wpr-import-kit-id', sanitize_text_field( $input['kit_identifier'] ) );
		}

		$current = get_option( 'wpr-import-kit-id' );
		if ( empty( $current ) ) {
			$err = new \WP_Error( 'wpr_missing_kit_id', 'wpr-import-kit-id option is empty. Pass kit_identifier (e.g. "christmas-v1") or call import-template-kit first.' );
			self::maybe_audit( 'finalize-kit-setup', $err );
			return $err;
		}

		$step = self::call_wpr_admin_function( 'wpr_final_settings_setup' );

		$out = [
			'success'         => empty( $step['error'] ) && empty( $step['redirect_blocked'] ),
			'kit_identifier'  => $current,
			'step'            => $step,
		];
		self::maybe_audit( 'finalize-kit-setup', $out );
		return $out;
	}

	public static function execute_get_install_job_status( $input = [] ) {
		$job_id = isset( $input['job_id'] ) ? sanitize_key( $input['job_id'] ) : '';
		if ( '' === $job_id ) {
			return new \WP_Error( 'wpr_missing_job_id', 'job_id is required.' );
		}

		$key   = self::install_job_transient_key( $job_id );
		$state = get_transient( $key );
		if ( ! is_array( $state ) ) {
			return new \WP_Error( 'wpr_unknown_job', 'Unknown or expired job_id.' );
		}

		$uid = (int) get_current_user_id();
		if ( ! empty( $state['user_id'] ) && (int) $state['user_id'] !== $uid && ! ( is_multisite() && is_super_admin() ) ) {
			return new \WP_Error( 'wpr_job_forbidden', 'This job belongs to another user.' );
		}

		return [
			'job_id'    => $job_id,
			'status'    => isset( $state['status'] ) ? (string) $state['status'] : 'unknown',
			'queued_at' => isset( $state['queued_at'] ) ? (int) $state['queued_at'] : 0,
			'updated'   => isset( $state['updated'] ) ? (int) $state['updated'] : 0,
			'error'     => isset( $state['error'] ) ? $state['error'] : null,
			'result'    => isset( $state['result'] ) ? $state['result'] : null,
		];
	}

	/**
	 * Full synchronous kit install (used by MCP sync mode and by the cron worker).
	 *
	 * @param array $input Same shape as install-kit ability input (without async).
	 * @return array|\WP_Error
	 */
	private static function run_install_kit_sync( $input ) {
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

		$import = self::execute_import_template_kit(
			[
				'slug'                      => $slug,
				'version'                   => $version,
				'_internal_skip_kit_gate'   => true,
			]
		);
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

	public static function execute_install_kit( $input = [] ) {
		$gate = self::gate_destructive_ability( 'install-kit' );
		if ( is_wp_error( $gate ) ) {
			self::maybe_audit( 'install-kit', $gate );
			return $gate;
		}

		$async = ! isset( $input['async'] ) || ! empty( $input['async'] );

		if ( $async ) {
			$job_id = strtolower( wp_generate_password( 16, false, false ) );
			$key    = self::install_job_transient_key( $job_id );
			$state  = [
				'status'    => 'queued',
				'input'     => self::sanitize_install_kit_input_for_job( $input ),
				'queued_at' => time(),
				'updated'   => time(),
				'user_id'   => (int) get_current_user_id(),
			];
			set_transient( $key, $state, DAY_IN_SECONDS );

			if ( ! wp_next_scheduled( 'wpr_ai_install_kit_job', [ $job_id ] ) ) {
				wp_schedule_single_event( time() + 1, 'wpr_ai_install_kit_job', [ $job_id ] );
			}
			if ( function_exists( 'spawn_cron' ) ) {
				spawn_cron();
			}

			$queued = [
				'async'          => true,
				'job_id'         => $job_id,
				'status'         => 'queued',
				'success'        => true,
				'poll_ability'   => self::NS . '/get-install-job-status',
				'message'        => __( 'Queued background install. Poll get-install-job-status with job_id until status is complete or error.', 'wpr-addons' ),
			];
			self::maybe_audit( 'install-kit', $queued );
			return $queued;
		}

		$result = self::run_install_kit_sync( $input );
		self::maybe_audit( 'install-kit', $result );
		return $result;
	}

	public static function execute_import_template_kit( $input = [] ) {
		$skip_gate = ! empty( $input['_internal_skip_kit_gate'] );
		unset( $input['_internal_skip_kit_gate'] );

		if ( ! $skip_gate ) {
			$g = self::gate_destructive_ability( 'import-template-kit' );
			if ( is_wp_error( $g ) ) {
				self::maybe_audit( 'import-template-kit', $g );
				return $g;
			}
		}

		$slug    = isset( $input['slug'] ) ? sanitize_file_name( $input['slug'] ) : '';
		$version = isset( $input['version'] ) ? sanitize_key( $input['version'] ) : 'v1';

		$fail = static function ( $err ) use ( $skip_gate ) {
			if ( ! $skip_gate ) {
				self::maybe_audit( 'import-template-kit', $err );
			}
			return $err;
		};

		if ( '' === $slug ) {
			return $fail( new \WP_Error( 'wpr_missing_slug', 'A kit slug is required.' ) );
		}

		if ( ! class_exists( '\\WprAddons\\Admin\\Templates\\Library\\WPR_Templates_Data' ) ) {
			return $fail( new \WP_Error( 'wpr_data_missing', 'WPR_Templates_Data is not loaded; visit /wp-admin first.' ) );
		}

		$kits = WPR_Templates_Data::get_available_kits();
		if ( ! isset( $kits[ $slug ] ) ) {
			return $fail( new \WP_Error( 'wpr_unknown_kit', sprintf( 'Unknown kit slug: %s', $slug ) ) );
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
			return $fail( new \WP_Error( 'wpr_importer_missing', 'Bundled WordPress Importer class is unavailable.' ) );
		}

		$identifier = preg_match( '/-v\d+$/', $slug ) ? $slug : $slug . '-' . $version;
		update_option( 'wpr-import-kit-id', $identifier );

		if ( class_exists( '\\WprAddons\\Classes\\Utilities' ) ) {
			add_filter( 'intermediate_image_sizes_advanced', [ new Utilities(), 'disable_extra_image_sizes' ], 10, 3 );
		}

		set_time_limit( 0 );

		$local = self::download_kit_xml( $slug, $version );
		if ( is_wp_error( $local ) ) {
			return $fail( $local );
		}

		if ( function_exists( 'wpr_register_cpt_before_import' ) ) {
			wpr_register_cpt_before_import( $slug );
		}

		$wp_import = new \WP_Import( $local, [ 'fetch_attachments' => true ] );

		ob_start();
		$wp_import->run();
		$import_output = ob_get_clean();

		@unlink( $local );

		$ok = [
			'success' => true,
			'slug'    => $slug,
			'version' => $version,
			'notes'   => 'Kit imported. Run list-templates afterwards to inspect new content.',
			'log_excerpt' => is_string( $import_output ) ? substr( wp_strip_all_tags( $import_output ), 0, 2000 ) : '',
		];
		if ( ! $skip_gate ) {
			self::maybe_audit( 'import-template-kit', $ok );
		}
		return $ok;
	}

	/* -------------------------------------------------------------------------
	 * Internal helpers
	 * ---------------------------------------------------------------------- */

	/* -------------------------------------------------------------------------
	 * Elementor + Royal widget helpers (MCP)
	 * ---------------------------------------------------------------------- */

	/**
	 * Allowlisted Elementor setting keys we will write via MCP (plain or limited rich text only).
	 *
	 * @return list<string>
	 */
	private static function rea_widget_text_setting_allowlist() {
		$keys = [
			'title', 'sub_title', 'subtitle', 'heading', 'heading_text', 'secondary_heading',
			'description', 'description_text', 'desc_text', 'text', 'inner_text',
			'btn_text', 'button_text', 'read_more_text', 'link_text', 'prefix', 'suffix',
			'before_title', 'after_title', 'badge_text', 'number_prefix', 'number_suffix',
			'counter_prefix', 'counter_suffix', 'price_prefix', 'price_suffix',
			'filter_bar_title', 'search_placeholder', 'empty_query_text', 'editor',
			// Royal widget–specific (see modules/*/widgets).
			'content_title', 'content_description', 'content_btn_text',
			'prefix_text', 'suffix_text', 'animated_text', 'highlighted_text', 'anim_cursor_content',
			'gm_latitude', 'gm_longtitude', 'gm_location_title',
		];
		return apply_filters( 'wpr_ai_rea_widget_text_setting_keys', $keys );
	}

	/**
	 * Repeater fields where we allow dotted patch keys: repeater_key.index.inner_key
	 *
	 * @return array<string,list<string>>
	 */
	private static function rea_ai_repeater_inner_allowlist() {
		$map = [
			'advanced_accordion' => [ 'accordion_title', 'accordion_content' ],
			'testimonial_items'  => [ 'testimonial_title', 'testimonial_content', 'testimonial_author', 'testimonial_job', 'testimonial_date' ],
			'grid_elements'      => [ 'element_extra_text' ],
		];
		return apply_filters( 'wpr_ai_repeater_text_fields', $map );
	}

	/**
	 * @param array $settings Widget settings.
	 * @return list<string>
	 */
	private static function rea_ai_append_repeater_patchable_keys( $settings ) {
		if ( ! is_array( $settings ) ) {
			return [];
		}
		$out = [];
		foreach ( self::rea_ai_repeater_inner_allowlist() as $rep => $inner_keys ) {
			if ( empty( $settings[ $rep ] ) || ! is_array( $settings[ $rep ] ) ) {
				continue;
			}
			$max = min( count( $settings[ $rep ] ), 24 );
			for ( $i = 0; $i < $max; $i++ ) {
				if ( empty( $settings[ $rep ][ $i ] ) || ! is_array( $settings[ $rep ][ $i ] ) ) {
					continue;
				}
				$row = $settings[ $rep ][ $i ];
				foreach ( $inner_keys as $ik ) {
					if ( array_key_exists( $ik, $row ) && self::rea_ai_is_scalar_text_value( $row[ $ik ] ) ) {
						$out[] = $rep . '.' . $i . '.' . $ik;
					}
				}
			}
		}
		return $out;
	}

	/**
	 * Apply repeater.0.title style updates into $settings by reference.
	 *
	 * @param array  $settings Widget settings (by ref).
	 * @param string $dotted   e.g. advanced_accordion.0.accordion_title
	 * @param mixed  $value    New value.
	 */
	private static function rea_ai_apply_repeater_dot_path( &$settings, $dotted, $value ) {
		if ( ! is_string( $dotted ) || ! preg_match( '/^([a-z0-9_]{1,60})\.(\d{1,3})\.([a-z0-9_]{1,80})$/', $dotted, $m ) ) {
			return false;
		}
		$rep = $m[1];
		$idx = (int) $m[2];
		$sub = $m[3];
		$map = self::rea_ai_repeater_inner_allowlist();
		if ( ! isset( $map[ $rep ] ) || ! in_array( $sub, $map[ $rep ], true ) ) {
			return false;
		}
		if ( $idx < 0 || $idx > 99 ) {
			return false;
		}
		if ( ! isset( $settings[ $rep ] ) || ! is_array( $settings[ $rep ] ) ) {
			return false;
		}
		if ( ! isset( $settings[ $rep ][ $idx ] ) || ! is_array( $settings[ $rep ][ $idx ] ) ) {
			return false;
		}
		if ( ! self::rea_ai_is_scalar_text_value( $value ) ) {
			return false;
		}
		$settings[ $rep ][ $idx ][ $sub ] = self::rea_ai_sanitize_setting_value( $sub, $value );
		return true;
	}

	/**
	 * @return array<string,bool>
	 */
	private static function rea_ai_allowlisted_key_set() {
		static $set = null;
		if ( null === $set ) {
			$set = [];
			foreach ( self::rea_widget_text_setting_allowlist() as $k ) {
				if ( is_string( $k ) && preg_match( '/^[a-z0-9_]{1,80}$/', $k ) ) {
					$set[ $k ] = true;
				}
			}
		}
		return $set;
	}

	/**
	 * @param string $key Setting key.
	 */
	private static function rea_ai_is_allowlisted_setting_key( $key ) {
		$set = self::rea_ai_allowlisted_key_set();
		return isset( $set[ $key ] );
	}

	/**
	 * @param string $widget_type Elementor widgetType string.
	 */
	private static function rea_ai_is_rea_widget_type( $widget_type ) {
		if ( ! is_string( $widget_type ) || '' === $widget_type ) {
			return false;
		}
		if ( 0 === strpos( $widget_type, 'wpr-' ) ) {
			return true;
		}
		return (bool) apply_filters( 'wpr_ai_treat_as_rea_widget_type', false, $widget_type );
	}

	/**
	 * @param int $post_id Post ID.
	 * @return \Elementor\Core\Base\Document|\WP_Error
	 */
	private static function rea_ai_get_elementor_document( $post_id ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return new \WP_Error( 'wpr_elementor_inactive', __( 'Elementor is not active.', 'wpr-addons' ) );
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return new \WP_Error( 'wpr_unknown_post', __( 'Invalid post ID.', 'wpr-addons' ) );
		}

		if ( wp_is_post_revision( $post_id ) || 'trash' === $post->post_status ) {
			return new \WP_Error( 'wpr_bad_post_state', __( 'Invalid or trashed post.', 'wpr-addons' ) );
		}

		$types = apply_filters( 'wpr_ai_elementor_post_types', [ 'page', 'post', 'elementor_library' ] );
		if ( ! in_array( $post->post_type, (array) $types, true ) ) {
			return new \WP_Error( 'wpr_post_type', __( 'This post type is not allowed for Elementor edits.', 'wpr-addons' ) );
		}

		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		if ( ! $document ) {
			return new \WP_Error( 'wpr_no_document', __( 'Could not load Elementor document.', 'wpr-addons' ) );
		}

		if ( ! $document->is_built_with_elementor() ) {
			return new \WP_Error( 'wpr_not_elementor', __( 'This content is not built with Elementor.', 'wpr-addons' ) );
		}

		return $document;
	}

	/**
	 * @param mixed $value Setting value.
	 */
	private static function rea_ai_is_scalar_text_value( $value ) {
		if ( is_string( $value ) ) {
			return true;
		}
		if ( is_int( $value ) || is_float( $value ) ) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $key   Setting key.
	 * @param mixed  $value Raw value.
	 * @return string|int|float
	 */
	private static function rea_ai_sanitize_setting_value( $key, $value ) {
		if ( is_int( $value ) || is_float( $value ) ) {
			return $value;
		}
		if ( ! is_string( $value ) ) {
			return '';
		}
		if ( self::rea_ai_setting_uses_rich_text( $key ) ) {
			return wp_kses_post( $value );
		}
		return sanitize_text_field( $value );
	}

	/**
	 * @param string $key Setting key.
	 */
	private static function rea_ai_setting_uses_rich_text( $key ) {
		foreach ( [ 'editor', 'html', 'content', 'description' ] as $frag ) {
			if ( false !== strpos( $key, $frag ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param string $text Text.
	 * @param int    $max  Max length.
	 */
	private static function rea_ai_truncate( $text, $max = 160 ) {
		if ( ! is_string( $text ) ) {
			return '';
		}
		$t = trim( wp_strip_all_tags( $text ) );
		if ( function_exists( 'mb_substr' ) ) {
			return mb_strlen( $t ) > $max ? mb_substr( $t, 0, $max ) . '…' : $t;
		}
		return strlen( $t ) > $max ? substr( $t, 0, $max ) . '…' : $t;
	}

	/**
	 * @param array $settings Widget settings array.
	 * @return list<string>
	 */
	private static function rea_ai_list_patchable_keys_present( $settings ) {
		if ( ! is_array( $settings ) ) {
			return [];
		}
		$out = [];
		foreach ( self::rea_widget_text_setting_allowlist() as $key ) {
			if ( ! array_key_exists( $key, $settings ) ) {
				continue;
			}
			if ( ! self::rea_ai_is_scalar_text_value( $settings[ $key ] ) ) {
				continue;
			}
			$out[] = $key;
		}
		return array_values( array_unique( array_merge( $out, self::rea_ai_append_repeater_patchable_keys( $settings ) ) ) );
	}

	/**
	 * @param array $settings Widget settings.
	 */
	private static function rea_ai_first_text_preview( $settings ) {
		if ( ! is_array( $settings ) ) {
			return '';
		}
		foreach ( self::rea_widget_text_setting_allowlist() as $key ) {
			if ( empty( $settings[ $key ] ) || ! is_string( $settings[ $key ] ) ) {
				continue;
			}
			$t = trim( wp_strip_all_tags( $settings[ $key ] ) );
			if ( '' !== $t ) {
				return $t;
			}
		}
		return '';
	}

	/**
	 * @param array  $elements Elementor elements tree.
	 * @param string $path     Path prefix for debugging.
	 * @return list<array<string,mixed>>
	 */
	private static function rea_ai_flatten_rea_widgets( $elements, $path = '' ) {
		$rows = [];
		if ( ! is_array( $elements ) ) {
			return $rows;
		}
		foreach ( $elements as $idx => $el ) {
			if ( ! is_array( $el ) ) {
				continue;
			}
			$branch = '' === $path ? (string) (int) $idx : $path . '/' . (int) $idx;
			$el_type = isset( $el['elType'] ) ? (string) $el['elType'] : '';
			$wt      = isset( $el['widgetType'] ) ? (string) $el['widgetType'] : '';
			if ( 'widget' === $el_type && self::rea_ai_is_rea_widget_type( $wt ) ) {
				$settings = isset( $el['settings'] ) && is_array( $el['settings'] ) ? $el['settings'] : [];
				$rows[]   = [
					'element_id'     => isset( $el['id'] ) ? (string) $el['id'] : '',
					'widget_type'    => $wt,
					'tree_path'      => $branch,
					'patchable_keys' => self::rea_ai_list_patchable_keys_present( $settings ),
					'text_preview'   => self::rea_ai_truncate( self::rea_ai_first_text_preview( $settings ), 160 ),
				];
			}
			if ( ! empty( $el['elements'] ) && is_array( $el['elements'] ) ) {
				$rows = array_merge( $rows, self::rea_ai_flatten_rea_widgets( $el['elements'], $branch ) );
			}
		}
		return $rows;
	}

	/**
	 * @param array  $elements   By reference: full Elementor tree.
	 * @param string $target_id  Element id.
	 * @param array  $patches    key => value.
	 * @param string $found_type Output: widget type when found.
	 * @return array|false|\WP_Error Applied keys on success, false if not found, WP_Error if invalid target type.
	 */
	private static function rea_ai_patch_widget_settings_branch( &$elements, $target_id, $patches, &$found_type ) {
		foreach ( $elements as &$el ) {
			if ( ! is_array( $el ) ) {
				continue;
			}
			if ( isset( $el['id'], $el['widgetType'], $el['elType'] ) && 'widget' === $el['elType'] && $el['id'] === $target_id ) {
				if ( ! self::rea_ai_is_rea_widget_type( $el['widgetType'] ) ) {
					return new \WP_Error( 'wpr_not_rea_widget', __( 'The target element is not a Royal Elementor widget.', 'wpr-addons' ) );
				}
				$found_type = (string) $el['widgetType'];
				if ( ! isset( $el['settings'] ) || ! is_array( $el['settings'] ) ) {
					$el['settings'] = [];
				}
				$applied = [];
				foreach ( $patches as $raw_key => $value ) {
					$key = is_string( $raw_key ) ? $raw_key : '';
					if ( '' === $key ) {
						continue;
					}
					if ( false !== strpos( $key, '.' ) ) {
						if ( self::rea_ai_apply_repeater_dot_path( $el['settings'], $key, $value ) ) {
							$applied[] = $key;
						}
						continue;
					}
					if ( ! preg_match( '/^[a-z0-9_]{1,80}$/', $key ) ) {
						continue;
					}
					if ( ! self::rea_ai_is_allowlisted_setting_key( $key ) ) {
						continue;
					}
					if ( ! self::rea_ai_is_scalar_text_value( $value ) ) {
						continue;
					}
					$el['settings'][ $key ] = self::rea_ai_sanitize_setting_value( $key, $value );
					$applied[]              = $key;
				}
				return $applied;
			}
			if ( ! empty( $el['elements'] ) && is_array( $el['elements'] ) ) {
				$sub = self::rea_ai_patch_widget_settings_branch( $el['elements'], $target_id, $patches, $found_type );
				if ( is_wp_error( $sub ) ) {
					return $sub;
				}
				if ( is_array( $sub ) ) {
					return $sub;
				}
			}
		}
		unset( $el );
		return false;
	}

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
	 * "undefined function" fatals. Category import (e.g. WordPress Importer)
	 * needs wp-admin/includes/taxonomy.php for wp_insert_category().
	 */
	private static function ensure_kit_function_deps() {
		foreach ( [ 'file.php', 'media.php', 'image.php', 'post.php', 'taxonomy.php' ] as $admin_inc ) {
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

		$parsed = wp_parse_url( $url );
		$host   = isset( $parsed['host'] ) ? strtolower( (string) $parsed['host'] ) : '';
		if ( $host !== self::KIT_XML_HOST ) {
			return new \WP_Error( 'wpr_invalid_download_host', 'Kit XML download host is not allowed.' );
		}

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
