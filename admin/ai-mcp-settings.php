<?php
/**
 * Royal Elementor Addons — AI & MCP settings.
 *
 * @package WprAddons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WprAddons\Classes\WPR_AI_Abilities;

add_action( 'admin_menu', 'wpr_ai_mcp_settings_menu', 98 );
function wpr_ai_mcp_settings_menu() {
	add_submenu_page(
		'wpr-addons',
		__( 'AI & MCP', 'wpr-addons' ),
		__( 'AI & MCP', 'wpr-addons' ),
		'manage_options',
		'wpr-ai-mcp-settings',
		'wpr_ai_mcp_settings_page'
	);
}

add_action( 'admin_init', 'wpr_ai_mcp_settings_register' );
function wpr_ai_mcp_settings_register() {
	register_setting(
		'wpr_ai_mcp_settings_group',
		'wpr_ai_mcp_tools',
		[
			'type'              => 'array',
			'sanitize_callback' => 'wpr_ai_mcp_sanitize_tool_flags',
			'default'           => [],
		]
	);

	register_setting(
		'wpr_ai_mcp_settings_group',
		'wpr_ai_audit_log',
		[
			'type'              => 'string',
			'sanitize_callback' => 'wpr_ai_mcp_sanitize_audit_flag',
			'default'           => '0',
		]
	);
}

/**
 * @param mixed $value Raw POST value (checkbox sends "1", hidden field "0").
 * @return string '1' or '0'
 */
function wpr_ai_mcp_sanitize_audit_flag( $value ) {
	return ( '1' === $value || 1 === $value || true === $value ) ? '1' : '0';
}

/**
 * @param array $value Raw POSTed tool map.
 * @return array<string,bool>
 */
function wpr_ai_mcp_sanitize_tool_flags( $value ) {
	if ( ! is_array( $value ) ) {
		return [];
	}
	$out = [];
	foreach ( WPR_AI_Abilities::all_ability_names() as $name ) {
		$out[ $name ] = ! empty( $value[ $name ] );
	}
	return $out;
}

function wpr_ai_mcp_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Insufficient permissions.', 'wpr-addons' ) );
	}

	$endpoint = WPR_AI_Abilities::get_mcp_endpoint_url();
	$flags    = WPR_AI_Abilities::get_mcp_tool_flags();
	$audit    = get_option( 'wpr_ai_audit_log', '0' );

	if ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="updated notice is-dismissible"><p>' . esc_html__( 'Settings saved.', 'wpr-addons' ) . '</p></div>';
	}

	$app_pw_url = admin_url( 'profile.php#application-passwords-section' );
	if ( ! function_exists( 'wp_is_application_passwords_available' ) || ! wp_is_application_passwords_available() ) {
		$app_pw_url = admin_url( 'user-new.php' );
	}

	$labels = WPR_AI_Abilities::ability_admin_labels();
	$claude_snippet = wp_json_encode(
		[
			'mcpServers' => [
				'rea' => [
					'command' => 'npx.cmd',
					'args'    => [
						'-y',
						'mcp-remote',
						$endpoint,
						'--allow-http',
						'--header',
						'Authorization: Basic REPLACE_WITH_BASE64_OF_username_colon_application_password',
					],
				],
			],
		],
		JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
	);

	echo '<div class="wrap"><h1>' . esc_html__( 'Royal Elementor Addons — AI & MCP', 'wpr-addons' ) . '</h1>';
	echo '<p style="max-width:860px;">' . esc_html__( 'Expose selected abilities as MCP tools (WordPress 7.0 + MCP Adapter). Destructive tools stay off until you enable them here.', 'wpr-addons' ) . '</p>';

	echo '<form method="post" action="options.php">';
	settings_fields( 'wpr_ai_mcp_settings_group' );

	echo '<h2 class="title">' . esc_html__( 'MCP endpoint', 'wpr-addons' ) . '</h2>';
	echo '<table class="form-table"><tr><th scope="row">' . esc_html__( 'Streamable HTTP URL', 'wpr-addons' ) . '</th><td>';
	echo '<code style="font-size:13px;user-select:all;">' . esc_html( $endpoint ) . '</code>';
	echo '<p class="description">' . esc_html__( 'Use this URL in MCP Inspector, ChatGPT Connectors, or with mcp-remote in Claude Desktop (see snippet below). Authenticate with an Application Password (Administrator).', 'wpr-addons' ) . '</p>';
	echo '<p><a class="button" href="' . esc_url( $app_pw_url ) . '">' . esc_html__( 'Open Application Passwords', 'wpr-addons' ) . '</a></p>';
	echo '</td></tr></table>';

	echo '<h2 class="title">' . esc_html__( 'Claude Desktop (Windows)', 'wpr-addons' ) . '</h2>';
	echo '<p class="description" style="max-width:860px;">' . esc_html__( 'Paste into claude_desktop_config.json under mcpServers. Replace the Authorization header value: Base64-encode "USERNAME:APPLICATION_PASSWORD" (no spaces in the secret when encoding). For non-localhost HTTP hosts, keep --allow-http.', 'wpr-addons' ) . '</p>';
	echo '<textarea readonly rows="18" class="large-text code" style="font-size:12px;max-width:900px;">' . esc_textarea( $claude_snippet ) . '</textarea>';

	echo '<h2 class="title">' . esc_html__( 'Tools exposed via MCP', 'wpr-addons' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	foreach ( WPR_AI_Abilities::all_ability_names() as $name ) {
		$label = isset( $labels[ $name ] ) ? $labels[ $name ] : $name;
		$on    = ! empty( $flags[ $name ] );
		$id    = 'wpr_ai_mcp_' . md5( $name );
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
		echo '<label><input type="checkbox" name="wpr_ai_mcp_tools[' . esc_attr( $name ) . ']" id="' . esc_attr( $id ) . '" value="1" ' . checked( $on, true, false ) . '> ';
		echo '<code>' . esc_html( $name ) . '</code></label>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';

	echo '<h2 class="title">' . esc_html__( 'Audit logging', 'wpr-addons' ) . '</h2>';
	echo '<table class="form-table"><tr><th scope="row">' . esc_html__( 'Log ability runs', 'wpr-addons' ) . '</th><td>';
	echo '<input type="hidden" name="wpr_ai_audit_log" value="0" />';
	echo '<label><input type="checkbox" name="wpr_ai_audit_log" value="1" ' . checked( $audit, '1', false ) . '> ';
	echo esc_html__( 'Write one line per destructive ability execution to the PHP error log (user id, ability, outcome).', 'wpr-addons' ) . '</label>';
	echo '</td></tr></table>';

	submit_button();
	echo '</form>';

	if ( defined( 'WPR_AI_DEBUG' ) && WPR_AI_DEBUG ) {
		echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=wpr-ai-abilities-debug' ) ) . '">' . esc_html__( 'Open AI Abilities debug (WPR_AI_DEBUG)', 'wpr-addons' ) . '</a></p>';
	}

	echo '</div>';
}
