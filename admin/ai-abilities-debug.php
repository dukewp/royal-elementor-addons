<?php
/**
 * Royal Elementor Addons - AI Abilities Debug Page.
 *
 * A throwaway admin tool that lets you invoke every REA ability directly
 * from wp-admin so you can see the JSON output without needing an external
 * MCP client. Works on WordPress < 7.0 too by falling back to the static
 * execute_* methods on WPR_AI_Abilities.
 *
 * URL: wp-admin/admin.php?page=wpr-ai-abilities-debug
 *
 * @package WprAddons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WprAddons\Classes\WPR_AI_Abilities;

add_action( 'admin_menu', 'wpr_ai_abilities_debug_menu', 99 );
function wpr_ai_abilities_debug_menu() {
	add_submenu_page(
		'wpr-addons',
		'AI Abilities Debug',
		'AI Abilities',
		'manage_options',
		'wpr-ai-abilities-debug',
		'wpr_ai_abilities_debug_page'
	);
}

/**
 * Map of debug-page ability ids -> { ability name, static execute method, inputs[] }.
 */
function wpr_ai_abilities_debug_map() {
	return [
		'list-template-kits' => [
			'ability' => 'wpr-addons/list-template-kits',
			'method'  => 'execute_list_template_kits',
			'inputs'  => [
				'price' => [ 'label' => 'Price (free|premium|mixed)', 'type' => 'text' ],
				'tag'   => [ 'label' => 'Tag substring', 'type' => 'text' ],
				'limit' => [ 'label' => 'Limit (1-200)', 'type' => 'number', 'default' => 20 ],
			],
		],
		'search-template-kits' => [
			'ability' => 'wpr-addons/search-template-kits',
			'method'  => 'execute_search_template_kits',
			'inputs'  => [
				'query' => [ 'label' => 'Query (required, >= 2 chars)', 'type' => 'text' ],
				'limit' => [ 'label' => 'Limit (1-50)', 'type' => 'number', 'default' => 10 ],
			],
		],
		'import-template-kit' => [
			'ability' => 'wpr-addons/import-template-kit',
			'method'  => 'execute_import_template_kit',
			'danger'  => true,
			'inputs'  => [
				'slug'    => [ 'label' => 'Kit slug (required, e.g. logistic)', 'type' => 'text' ],
				'version' => [ 'label' => 'Version', 'type' => 'text', 'default' => 'v1' ],
			],
		],
		'finalize-kit-setup' => [
			'ability' => 'wpr-addons/finalize-kit-setup',
			'method'  => 'execute_finalize_kit_setup',
			'danger'  => true,
			'inputs'  => [
				'kit_identifier' => [ 'label' => 'Override wpr-import-kit-id (e.g. christmas-v1). Leave empty to reuse last import.', 'type' => 'text' ],
			],
		],
		'install-kit' => [
			'ability' => 'wpr-addons/install-kit',
			'method'  => 'execute_install_kit',
			'danger'  => true,
			'inputs'  => [
				'slug'              => [ 'label' => 'Kit slug (required, e.g. christmas)', 'type' => 'text' ],
				'version'           => [ 'label' => 'Version', 'type' => 'text', 'default' => 'v1' ],
				'reset'             => [ 'label' => 'Reset previous import (1/0)', 'type' => 'text', 'default' => '1' ],
				'switch_theme'      => [ 'label' => 'Switch to royal-elementor-kit theme (1/0)', 'type' => 'text', 'default' => '1' ],
				'fix_compatibility' => [ 'label' => 'Fix compatibility / deactivate extras (1/0)', 'type' => 'text', 'default' => '1' ],
				'plugins'           => [ 'label' => 'Activate plugins (CSV: woocommerce,contact-form-7,...)', 'type' => 'text', 'default' => '' ],
			],
		],
		'list-widgets' => [
			'ability' => 'wpr-addons/list-widgets',
			'method'  => 'execute_list_widgets',
			'inputs'  => [],
		],
		'list-extensions' => [
			'ability' => 'wpr-addons/list-extensions',
			'method'  => 'execute_list_extensions',
			'inputs'  => [],
		],
		'toggle-extension' => [
			'ability' => 'wpr-addons/toggle-extension',
			'method'  => 'execute_toggle_extension',
			'danger'  => true,
			'inputs'  => [
				'slug'    => [ 'label' => 'Extension slug (e.g. wpr-particles)', 'type' => 'text' ],
				'enabled' => [ 'label' => 'Enabled (1 / 0)', 'type' => 'text', 'default' => '1' ],
			],
		],
	];
}

/**
 * Run a single ability, preferring the WP 7.0 Abilities API if registered,
 * otherwise calling the static execute_* method directly.
 */
function wpr_ai_abilities_debug_run( $id, $input ) {
	$map = wpr_ai_abilities_debug_map();
	if ( ! isset( $map[ $id ] ) ) {
		return [ 'error' => 'unknown ability id' ];
	}

	$entry  = $map[ $id ];
	$source = 'static-fallback';
	$result = null;
	$error  = null;

	if ( function_exists( 'wp_get_ability' ) ) {
		$ability = wp_get_ability( $entry['ability'] );
		if ( $ability && method_exists( $ability, 'execute' ) ) {
			$source = 'wp_get_ability';
			try {
				$result = $ability->execute( $input );
			} catch ( \Throwable $e ) {
				$error = $e->getMessage();
			}
		}
	}

	if ( null === $result && null === $error ) {
		if ( ! class_exists( '\\WprAddons\\Classes\\WPR_AI_Abilities' ) ) {
			return [ 'error' => 'WPR_AI_Abilities class not loaded' ];
		}
		try {
			$result = call_user_func( [ '\\WprAddons\\Classes\\WPR_AI_Abilities', $entry['method'] ], $input );
		} catch ( \Throwable $e ) {
			$error = $e->getMessage();
		}
	}

	if ( $error ) {
		return [ 'source' => $source, 'error' => $error ];
	}

	if ( is_wp_error( $result ) ) {
		return [
			'source'  => $source,
			'wp_error' => [
				'code'    => $result->get_error_code(),
				'message' => $result->get_error_message(),
			],
		];
	}

	return [ 'source' => $source, 'result' => $result ];
}

function wpr_ai_abilities_debug_transient_key() {
	return 'wpr_ai_dbg_run_' . get_current_user_id();
}

/**
 * Live HTTP roundtrip against the local REST API to confirm whether
 * the MCP endpoints actually answer requests right now.
 *
 * The transient diagnostics only reflect past REST requests; this test
 * fires real wp_remote_* calls so we can see the live route state.
 */
function wpr_ai_abilities_debug_selftest() {
	$site = home_url();
	$out  = [ 'site' => $site ];

	$index = wp_remote_get( rest_url(), [ 'timeout' => 5, 'redirection' => 0 ] );
	if ( is_wp_error( $index ) ) {
		$out['index_error'] = $index->get_error_message();
	} else {
		$body = wp_remote_retrieve_body( $index );
		$json = json_decode( $body, true );
		$namespaces = [];
		if ( is_array( $json ) && isset( $json['namespaces'] ) && is_array( $json['namespaces'] ) ) {
			$namespaces = $json['namespaces'];
		}
		$wpr_or_mcp = array_values( array_filter( $namespaces, function ( $ns ) {
			return false !== strpos( (string) $ns, 'wpr-addons' ) || false !== strpos( (string) $ns, 'mcp' );
		} ) );
		$out['index_status']            = (int) wp_remote_retrieve_response_code( $index );
		$out['index_namespaces_total']  = count( $namespaces );
		$out['index_namespaces_mcp']    = $wpr_or_mcp;
		$out['index_has_wpr_addons_mcp'] = in_array( 'wpr-addons-mcp', $namespaces, true );
		$out['index_has_default_mcp']    = in_array( 'mcp', $namespaces, true );
	}

	$payload = wp_json_encode( [
		'jsonrpc' => '2.0',
		'id'      => 1,
		'method'  => 'initialize',
		'params'  => [
			'protocolVersion' => '2024-11-05',
			'capabilities'    => new stdClass(),
			'clientInfo'      => [ 'name' => 'wpr-debug-selftest', 'version' => '1.0' ],
		],
	] );

	$auth_user = isset( $_GET['wpr_selftest_user'] ) ? sanitize_user( wp_unslash( $_GET['wpr_selftest_user'] ) ) : '';
	$auth_pw   = isset( $_GET['wpr_selftest_pw'] )   ? wp_unslash( $_GET['wpr_selftest_pw'] )                : '';
	$auth_hdr  = ( '' !== $auth_user && '' !== $auth_pw ) ? 'Basic ' . base64_encode( $auth_user . ':' . $auth_pw ) : '';
	$no_tools  = ! empty( $_GET['wpr_mcp_no_tools'] );

	$trace_log = function_exists( '\\WprAddons\\Classes\\WPR_AI_Abilities::trace_log_path' )
		? \WprAddons\Classes\WPR_AI_Abilities::trace_log_path()
		: ( defined( 'WP_CONTENT_DIR' ) ? rtrim( WP_CONTENT_DIR, '/\\' ) . '/wpr-mcp-trace.log' : '' );

	$read_trace = static function () use ( $trace_log ) {
		if ( '' === $trace_log || ! file_exists( $trace_log ) ) {
			return [];
		}
		$lines = file( $trace_log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		$out   = [];
		foreach ( (array) $lines as $line ) {
			$decoded = json_decode( $line, true );
			$out[]   = is_array( $decoded ) ? $decoded : [ 'raw' => $line ];
		}
		return $out;
	};

	$probe = static function ( $url ) use ( $payload, $auth_hdr, $no_tools, $trace_log, $read_trace ) {
		if ( '' !== $trace_log ) {
			@file_put_contents( $trace_log, '' );
		}

		$headers = [
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json, text/event-stream',
		];
		if ( '' !== $auth_hdr ) {
			$headers['Authorization'] = $auth_hdr;
		}

		$probe_url = $no_tools ? add_query_arg( 'wpr_mcp_no_tools', '1', $url ) : $url;

		$t0   = microtime( true );
		$resp = wp_remote_post( $probe_url, [
			'timeout'     => 8,
			'redirection' => 0,
			'headers'     => $headers,
			'body'        => $payload,
		] );
		$elapsed = round( ( microtime( true ) - $t0 ) * 1000 );

		$trace = $read_trace();

		if ( is_wp_error( $resp ) ) {
			return [ 'error' => $resp->get_error_message(), 'url' => $probe_url, 'elapsed_ms' => $elapsed, 'trace' => $trace ];
		}
		$body = wp_remote_retrieve_body( $resp );
		$json = json_decode( $body, true );
		return [
			'status'     => (int) wp_remote_retrieve_response_code( $resp ),
			'url'        => $probe_url,
			'elapsed_ms' => $elapsed,
			'body'       => is_array( $json ) ? $json : ( is_string( $body ) ? mb_substr( $body, 0, 400 ) : null ),
			'trace'      => $trace,
		];
	};

	$out['auth_in_use']         = '' !== $auth_hdr ? ( 'Basic ' . $auth_user . ':***' ) : 'none';
	$out['no_tools_override']   = $no_tools;
	$out['trace_log']           = $trace_log;
	$out['post_wpr_addons_mcp'] = $probe( rest_url( 'wpr-addons-mcp/mcp' ) );
	$out['post_default_mcp']    = $probe( rest_url( 'mcp/mcp-adapter-default-server' ) );

	return $out;
}

function wpr_ai_abilities_debug_handle_post() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}
	if ( ! isset( $_POST['_wpnonce'], $_POST['wpr_ability_id'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wpr-ai-abilities-debug' ) ) {
		return;
	}

	$map    = wpr_ai_abilities_debug_map();
	$run_id = sanitize_key( wp_unslash( $_POST['wpr_ability_id'] ) );
	if ( ! isset( $map[ $run_id ] ) ) {
		return;
	}

	$tkey = wpr_ai_abilities_debug_transient_key();

	set_transient( $tkey, [
		'id'    => $run_id,
		'phase' => 'started',
		'time'  => time(),
	], 600 );

	register_shutdown_function( function () use ( $tkey, $run_id ) {
		$err = error_get_last();
		if ( ! $err || ! in_array( $err['type'], [ E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR ], true ) ) {
			return;
		}
		set_transient( $tkey, [
			'id'    => $run_id,
			'phase' => 'fatal',
			'fatal' => $err,
			'time'  => time(),
		], 600 );
	} );

	$input = [];
	foreach ( $map[ $run_id ]['inputs'] as $field => $meta ) {
		$key = 'in_' . $run_id . '_' . $field;
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$value = wp_unslash( $_POST[ $key ] );
		if ( '' === $value ) {
			continue;
		}
		if ( 'number' === ( $meta['type'] ?? 'text' ) ) {
			$input[ $field ] = (int) $value;
		} elseif ( in_array( $field, [ 'enabled', 'reset', 'switch_theme', 'fix_compatibility' ], true ) ) {
			$input[ $field ] = in_array( strtolower( $value ), [ '1', 'true', 'yes', 'on' ], true );
		} elseif ( 'plugins' === $field ) {
			$arr = array_filter( array_map( 'trim', explode( ',', $value ) ) );
			if ( ! empty( $arr ) ) {
				$input[ $field ] = array_values( array_map( 'sanitize_key', $arr ) );
			}
		} else {
			$input[ $field ] = sanitize_text_field( $value );
		}
	}

	$run_out = wpr_ai_abilities_debug_run( $run_id, $input );

	set_transient( $tkey, [
		'id'    => $run_id,
		'phase' => 'completed',
		'out'   => $run_out,
		'input' => $input,
		'time'  => time(),
	], 600 );

	wp_safe_redirect( admin_url( 'admin.php?page=wpr-ai-abilities-debug&ran=' . rawurlencode( $run_id ) ) );
	exit;
}
add_action( 'admin_init', 'wpr_ai_abilities_debug_handle_post' );

function wpr_ai_abilities_debug_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions.' );
	}

	$map     = wpr_ai_abilities_debug_map();
	$tkey    = wpr_ai_abilities_debug_transient_key();
	$last    = get_transient( $tkey );
	$run_id  = null;
	$run_out = null;
	$phase   = null;

	if ( is_array( $last ) ) {
		$run_id  = isset( $last['id'] ) ? (string) $last['id'] : null;
		$run_out = $last['out'] ?? null;
		$phase   = $last['phase'] ?? null;

		if ( 'fatal' === $phase ) {
			$run_out = [ 'phase' => 'fatal', 'fatal' => $last['fatal'] ?? null ];
		} elseif ( 'started' === $phase ) {
			$run_out = [ 'phase' => 'started', 'note' => 'Request started but never completed (likely PHP timeout, redirect, or fatal). Check wp-content/debug.log.' ];
		}

		delete_transient( $tkey );
	}

	$wp_700_ready = function_exists( 'wp_register_ability' );

	echo '<div class="wrap"><h1>Royal Elementor Addons - AI Abilities Debug</h1>';

	$mcp_diag = [
		'wp_register_ability_fn' => function_exists( 'wp_register_ability' ),
		'wp_get_abilities_fn'    => function_exists( 'wp_get_abilities' ),
		'McpAdapter_class'       => class_exists( '\\WP\\MCP\\Core\\McpAdapter' ),
		'HttpTransport_class'    => class_exists( '\\WP\\MCP\\Transport\\HttpTransport' ),
		'mcp_adapter_init_did'   => did_action( 'mcp_adapter_init' ),
		'rest_api_init_did'      => did_action( 'rest_api_init' ),
		'plugins_loaded_did'     => did_action( 'plugins_loaded' ),
		'rea_bootstrap_kick'     => get_transient( 'wpr_ai_mcp_bootstrap_diag' ),
	];

	if ( class_exists( '\\WP\\MCP\\Core\\McpAdapter' ) ) {
		try {
			$adapter_inst = \WP\MCP\Core\McpAdapter::instance();
			if ( method_exists( $adapter_inst, 'get_servers' ) ) {
				$servers = $adapter_inst->get_servers();
				$mcp_diag['servers_count'] = is_array( $servers ) ? count( $servers ) : 'n/a';
				$mcp_diag['server_ids']    = is_array( $servers ) ? array_keys( $servers ) : 'n/a';
			}
		} catch ( \Throwable $e ) {
			$mcp_diag['adapter_error'] = $e->getMessage();
		}
	}

	$mcp_register_diag = get_transient( 'wpr_ai_mcp_register_diag' );

	echo '<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:14px 18px;margin:0 0 16px;max-width:880px;">';
	echo '<h2 style="margin:0 0 8px;font-size:14px;">MCP / Abilities runtime state (current admin request)</h2>';
	echo '<pre style="background:#1d2327;color:#e6e7e9;padding:10px 12px;border-radius:4px;font-size:12px;overflow:auto;">';
	echo esc_html( wp_json_encode( $mcp_diag, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
	echo '</pre>';

	echo '<h2 style="margin:14px 0 8px;font-size:14px;">Last REST-time server registration attempt</h2>';
	if ( $mcp_register_diag ) {
		echo '<pre style="background:#1d2327;color:#e6e7e9;padding:10px 12px;border-radius:4px;font-size:12px;overflow:auto;">';
		echo esc_html( wp_json_encode( $mcp_register_diag, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
		echo '</pre>';
	} else {
		echo '<p style="margin:6px 0 0;color:#646970;font-size:12px;">No data yet. Open <code>http://rea.local/wp-json/</code> in a new tab to trigger <code>mcp_adapter_init</code>, then refresh this page.</p>';
	}

	$mcp_route_check = get_transient( 'wpr_ai_mcp_route_check' );
	echo '<h2 style="margin:14px 0 8px;font-size:14px;">REST routes containing "wpr-addons" or "mcp" at priority 200</h2>';
	if ( $mcp_route_check ) {
		echo '<pre style="background:#1d2327;color:#e6e7e9;padding:10px 12px;border-radius:4px;font-size:12px;overflow:auto;">';
		echo esc_html( wp_json_encode( $mcp_route_check, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
		echo '</pre>';
	} else {
		echo '<p style="margin:6px 0 0;color:#646970;font-size:12px;">No data yet.</p>';
	}

	echo '<h2 style="margin:14px 0 8px;font-size:14px;">Live REST self-test (real HTTP roundtrip from PHP)</h2>';
	$selftest = wpr_ai_abilities_debug_selftest();
	echo '<pre style="background:#1d2327;color:#e6e7e9;padding:10px 12px;border-radius:4px;font-size:12px;overflow:auto;">';
	echo esc_html( wp_json_encode( $selftest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
	echo '</pre>';
	echo '<p style="margin:6px 0 0;color:#646970;font-size:12px;">If <code>index_status</code> is 200 but the MCP endpoints return 404 here, the adapter is not registering routes. If they return 401, the route exists but auth blocks anonymous access (expected — the real client uses an Application Password). Each probe also records a <code>trace</code> log (per-request entries written to <code>wp-content/wpr-mcp-trace.log</code>) so we can see how far the request got. Add <code>&amp;wpr_mcp_no_tools=1</code> to the page URL to register the REA MCP server with zero tools, which isolates ability-to-tool conversion as a suspect.</p>';

	$self_url = admin_url( 'admin.php?page=wpr-ai-abilities-debug' );
	echo '<p style="margin:8px 0 0;font-size:12px;">To probe with auth, append <code>&amp;wpr_selftest_user=ADMIN&amp;wpr_selftest_pw=APP+PASSWORD</code> to the page URL (encode spaces as <code>+</code>). Example with no-tools mode on: <code>' . esc_html( $self_url . '&wpr_selftest_user=admin&wpr_selftest_pw=wqef+jgBp+wMCu+agpz+bWlb+gC9F&wpr_mcp_no_tools=1' ) . '</code></p>';

	echo '</div>';

	echo '<p style="max-width:780px;">';
	echo 'Invoke each ability directly to see its JSON output. ';
	if ( $wp_700_ready ) {
		echo '<strong>WordPress 7.0 Abilities API detected</strong> - calls go through <code>wp_get_ability()-&gt;execute()</code>.';
	} else {
		echo '<strong>Abilities API not detected</strong> - calls fall back to <code>WPR_AI_Abilities::execute_*</code> static methods so you can still test the logic.';
	}
	echo '</p>';

	foreach ( $map as $id => $entry ) {
		$is_danger = ! empty( $entry['danger'] );
		echo '<div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:14px 18px;margin:16px 0;max-width:880px;">';
		echo '<h2 style="margin:0 0 4px;font-size:16px;">';
		echo esc_html( $entry['ability'] );
		if ( $is_danger ) {
			echo ' <span style="font-size:11px;color:#fff;background:#d63638;padding:2px 6px;border-radius:3px;">write</span>';
		}
		echo '</h2>';

		echo '<form method="post" style="margin-top:8px;">';
		wp_nonce_field( 'wpr-ai-abilities-debug' );
		echo '<input type="hidden" name="wpr_ability_id" value="' . esc_attr( $id ) . '">';

		if ( empty( $entry['inputs'] ) ) {
			echo '<p style="margin:4px 0 8px;color:#646970;">No inputs.</p>';
		} else {
			echo '<table class="form-table" style="margin:0;"><tbody>';
			foreach ( $entry['inputs'] as $field => $meta ) {
				$key = 'in_' . $id . '_' . $field;
				$val = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ( $meta['default'] ?? '' );
				echo '<tr><th style="width:240px;padding:6px 10px 6px 0;font-weight:500;">' . esc_html( $meta['label'] ) . '</th><td style="padding:4px 0;">';
				echo '<input name="' . esc_attr( $key ) . '" type="' . esc_attr( $meta['type'] ?? 'text' ) . '" value="' . esc_attr( $val ) . '" class="regular-text">';
				echo '</td></tr>';
			}
			echo '</tbody></table>';
		}

		submit_button( $is_danger ? 'Run (write!)' : 'Run', $is_danger ? 'delete' : 'primary', 'submit', false );
		echo '</form>';

		if ( $run_id === $id && null !== $run_out ) {
			echo '<h3 style="margin:14px 0 6px;font-size:13px;color:#1d2327;">Output</h3>';
			echo '<pre style="background:#1d2327;color:#e6e7e9;padding:12px 14px;border-radius:4px;max-height:480px;overflow:auto;font-size:12px;">';
			echo esc_html( wp_json_encode( $run_out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
			echo '</pre>';
		}

		echo '</div>';
	}

	echo '</div>';
}
