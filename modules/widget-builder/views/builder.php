<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$widget_id    = isset( $_GET['post'] ) ? intval( wp_unslash( $_GET['post'] ) ) : 0;
$widget_title = $widget_id ? get_the_title( $widget_id ) : esc_html__( 'New Widget', 'wpr-addons' );
$back_url     = admin_url( 'edit.php?post_type=wpr_custom_widget' );
$preview_url  = '';

if ( $widget_id ) {
	$preview_url = str_replace(
		[ '&amp;', 'action=edit' ],
		[ '&', 'action=elementor' ],
		get_edit_post_link( $widget_id )
	);
}
?>
<script>
	var wprWidgetBuilder = {
		apiBase: '<?php echo esc_url( rest_url( 'wpr-addons/v1/widget-builder/' ) ); ?>',
		postId: <?php echo intval( $widget_id ); ?>,
		nonce: '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>',
		previewUrl: '<?php echo esc_url( $preview_url ); ?>',
		backUrl: '<?php echo esc_url( $back_url ); ?>',
		eiconsUrl: '<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'lib/eicons/eicons.json' ); ?>'
	};
</script>

<div id="wpr-wb-app">

	<!-- LEFT PANEL: Settings -->
	<div class="wpr-wb-panel wpr-wb-panel-left">
		<!-- Panel Header -->
		<div class="wpr-wb-panel-header">
			<span class="wpr-wb-panel-header-title" id="wpr-wb-header-title"><?php echo esc_html( $widget_title ); ?></span>
			<button type="button" class="wpr-wb-panel-collapse-btn" id="wpr-wb-collapse-left" title="<?php esc_attr_e( 'Collapse Panel', 'wpr-addons' ); ?>">
				<i class="eicon-chevron-left"></i>
			</button>
		</div>

		<!-- Settings Content -->
		<div class="wpr-wb-panel-body" id="wpr-wb-settings-body">
			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Title', 'wpr-addons' ); ?></label>
				<input type="text" id="wpr-wb-title" class="wpr-wb-setting-input" value="<?php echo esc_attr( $widget_title ); ?>" placeholder="<?php esc_attr_e( 'New Widget', 'wpr-addons' ); ?>">
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'The Widget Title will show on the widgets list.', 'wpr-addons' ); ?></span>
			</div>

			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Icon', 'wpr-addons' ); ?></label>
				<div class="wpr-wb-icon-preview-wrap">
					<div class="wpr-wb-icon-preview" id="wpr-wb-icon-preview">
						<i class="eicon-cog" id="wpr-wb-icon-preview-i"></i>
					</div>
					<button type="button" class="wpr-wb-btn-outline" id="wpr-wb-change-icon"><?php esc_html_e( 'Change Icon', 'wpr-addons' ); ?></button>
				</div>
				<input type="hidden" id="wpr-wb-icon" value="eicon-cog">
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'It lets you set the widget icon. You can use any of the eicon or font-awesome icons, simply return the class name as a string.', 'wpr-addons' ); ?></span>
			</div>

			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Category', 'wpr-addons' ); ?></label>
				<select id="wpr-wb-category" class="wpr-wb-setting-input">
					<option value="basic"><?php esc_html_e( 'Basic', 'wpr-addons' ); ?></option>
					<option value="general"><?php esc_html_e( 'General', 'wpr-addons' ); ?></option>
					<option value="wpr-widgets" selected><?php esc_html_e( 'Royal Addons', 'wpr-addons' ); ?></option>
				</select>
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'Widget categories in Elementor are used to organize the widgets into groups.', 'wpr-addons' ); ?></span>
			</div>
		</div>

		<!-- Bottom Action Bar -->
		<div class="wpr-wb-panel-footer">
			<button type="button" class="wpr-wb-footer-btn" id="wpr-wb-btn-preview" title="<?php esc_attr_e( 'Preview in Elementor', 'wpr-addons' ); ?>">
				<?php esc_html_e( 'PREVIEW', 'wpr-addons' ); ?>
			</button>
			<button type="button" class="wpr-wb-footer-btn wpr-wb-footer-btn-save" id="wpr-wb-btn-save" title="<?php esc_attr_e( 'Save Widget', 'wpr-addons' ); ?>">
				<?php esc_html_e( 'SAVE', 'wpr-addons' ); ?>
			</button>
		</div>
		<button type="button" class="wpr-wb-collapsed-save-btn" id="wpr-wb-collapsed-save" title="<?php esc_attr_e( 'Save Widget', 'wpr-addons' ); ?>">
			<i class="eicon-save"></i>
		</button>
	</div>

	<!-- CENTER PANEL: Controls Builder -->
	<div class="wpr-wb-panel wpr-wb-panel-center">
		<!-- Tabs -->
		<div class="wpr-wb-center-tabs">
			<button type="button" class="wpr-wb-center-tab active" data-tab="content">
				<i class="eicon-edit"></i>
				<span><?php esc_html_e( 'Content', 'wpr-addons' ); ?></span>
			</button>
			<button type="button" class="wpr-wb-center-tab" data-tab="style">
				<i class="eicon-paint-brush"></i>
				<span><?php esc_html_e( 'Style', 'wpr-addons' ); ?></span>
			</button>
			<button type="button" class="wpr-wb-center-tab" data-tab="advanced">
				<i class="eicon-cog"></i>
				<span><?php esc_html_e( 'Advanced', 'wpr-addons' ); ?></span>
			</button>
		</div>

		<!-- Tab Content Areas -->
		<div class="wpr-wb-center-body">
			<div class="wpr-wb-center-panel active" data-tab="content">
				<div class="wpr-wb-sections-list" data-tab="content"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="content">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
			<div class="wpr-wb-center-panel" data-tab="style">
				<div class="wpr-wb-sections-list" data-tab="style"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="style">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
			<div class="wpr-wb-center-panel" data-tab="advanced">
				<div class="wpr-wb-sections-list" data-tab="advanced"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="advanced">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- RIGHT PANEL: Code Editor -->
	<div class="wpr-wb-panel wpr-wb-panel-right">
		<!-- Code Tabs -->
		<div class="wpr-wb-code-tabs">
			<button type="button" class="wpr-wb-code-tab active" data-editor="html"><?php esc_html_e( 'HTML', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="css"><?php esc_html_e( 'CSS', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="js"><?php esc_html_e( 'JavaScript', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="includes"><?php esc_html_e( 'CSS/JS Includes', 'wpr-addons' ); ?></button>
		</div>

		<!-- Editor Areas -->
		<div class="wpr-wb-code-body">
			<div class="wpr-wb-code-panel active" data-editor="html">
				<div id="wpr-wb-editor-html" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="css">
				<div id="wpr-wb-editor-css" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="js">
				<div id="wpr-wb-editor-js" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="includes">
				<div class="wpr-wb-includes-panel">
					<div class="wpr-wb-includes-group">
						<label class="wpr-wb-includes-label"><?php esc_html_e( 'External CSS URLs (one per line)', 'wpr-addons' ); ?></label>
						<textarea id="wpr-wb-css-includes" class="wpr-wb-includes-textarea" rows="5" placeholder="https://example.com/style.css"></textarea>
					</div>
					<div class="wpr-wb-includes-group">
						<label class="wpr-wb-includes-label"><?php esc_html_e( 'External JS URLs (one per line)', 'wpr-addons' ); ?></label>
						<textarea id="wpr-wb-js-includes" class="wpr-wb-includes-textarea" rows="5" placeholder="https://example.com/script.js"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- Status Toast -->
<div id="wpr-wb-toast" class="wpr-wb-toast"></div>

<!-- Control Type Picker Modal -->
<div id="wpr-wb-control-modal" class="wpr-wb-modal-overlay">
	<div class="wpr-wb-modal wpr-wb-modal-lg">
		<div class="wpr-wb-modal-header">
			<span class="wpr-wb-modal-title"><?php esc_html_e( 'Add Control', 'wpr-addons' ); ?></span>
			<button type="button" class="wpr-wb-modal-close" id="wpr-wb-control-modal-close">&times;</button>
		</div>
		<div class="wpr-wb-modal-search">
			<input type="text" id="wpr-wb-control-search" class="wpr-wb-setting-input" placeholder="<?php esc_attr_e( 'Search control types...', 'wpr-addons' ); ?>">
		</div>
		<div class="wpr-wb-control-type-grid" id="wpr-wb-control-type-grid">
			<!-- Populated via JS -->
		</div>
	</div>
</div>

<!-- Icon Picker Modal -->
<div id="wpr-wb-icon-modal" class="wpr-wb-modal-overlay">
	<div class="wpr-wb-modal">
		<div class="wpr-wb-modal-header">
			<span class="wpr-wb-modal-title"><?php esc_html_e( 'Choose Icon', 'wpr-addons' ); ?></span>
			<button type="button" class="wpr-wb-modal-close" id="wpr-wb-icon-modal-close">&times;</button>
		</div>
		<div class="wpr-wb-modal-search">
			<input type="text" id="wpr-wb-icon-search" class="wpr-wb-setting-input" placeholder="<?php esc_attr_e( 'Search icons...', 'wpr-addons' ); ?>">
		</div>
		<div class="wpr-wb-icon-grid" id="wpr-wb-icon-grid">
			<!-- Icons populated via JS -->
		</div>
	</div>
</div>
