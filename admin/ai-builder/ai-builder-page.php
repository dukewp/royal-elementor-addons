<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$provider = get_option( 'wpr_ai_provider', 'claude' );
$model = get_option( 'wpr_ai_model', 'claude-sonnet-4-6' );
$has_key = ! empty( \WprAddons\Admin\AiBuilder\WPR_AI_Builder::get_ai_api_key() );
?>

<div class="wrap wpr-ai-builder-wrap">
	<div class="wpr-ai-builder-header">
		<h1>AI Website Builder</h1>
		<p>Describe your website and AI will build it using pre-designed sections. <a href="#" id="wpr-ai-toggle-settings">Settings</a></p>
	</div>

	<!-- Settings Panel -->
	<div class="wpr-ai-panel wpr-ai-settings-panel" id="wpr-ai-settings"<?php echo $has_key ? ' style="display:none"' : ''; ?>>
		<h2>API Settings</h2>
		<div class="wpr-ai-settings-form">
			<div class="wpr-ai-field">
				<label>AI Provider</label>
				<select id="wpr-ai-provider">
					<option value="claude" <?php selected( $provider, 'claude' ); ?>>Claude (Anthropic)</option>
					<option value="openai" <?php selected( $provider, 'openai' ); ?>>OpenAI</option>
				</select>
			</div>
			<div class="wpr-ai-field">
				<label>Model</label>
				<select id="wpr-ai-model">
					<optgroup label="Claude" class="wpr-ai-models-claude">
						<option value="claude-sonnet-4-6" <?php selected( $model, 'claude-sonnet-4-6' ); ?>>Claude Sonnet 4.6 (Recommended)</option>
						<option value="claude-opus-4-6" <?php selected( $model, 'claude-opus-4-6' ); ?>>Claude Opus 4.6 (Highest Quality)</option>
						<option value="claude-haiku-4-5-20251001" <?php selected( $model, 'claude-haiku-4-5-20251001' ); ?>>Claude Haiku 4.5 (Fastest)</option>
					</optgroup>
					<optgroup label="OpenAI" class="wpr-ai-models-openai">
						<option value="gpt-4o" <?php selected( $model, 'gpt-4o' ); ?>>GPT-4o (Recommended)</option>
						<option value="gpt-4o-mini" <?php selected( $model, 'gpt-4o-mini' ); ?>>GPT-4o Mini (Cheaper)</option>
					</optgroup>
				</select>
			</div>
			<div class="wpr-ai-field">
				<label>API Key</label>
				<input type="password" id="wpr-ai-api-key" placeholder="Enter your API key...">
			</div>
			<div class="wpr-ai-field-actions">
				<button type="button" class="button button-secondary" id="wpr-ai-test-btn">Test Connection</button>
				<button type="button" class="button button-primary" id="wpr-ai-save-btn">Save Settings</button>
				<span class="wpr-ai-status" id="wpr-ai-settings-status"></span>
			</div>
		</div>
	</div>

	<!-- Builder Panel -->
	<div class="wpr-ai-panel wpr-ai-builder-panel" id="wpr-ai-builder-form">
		<div class="wpr-ai-step">
			<h2>1. Describe Your Page</h2>
			<div class="wpr-ai-field">
				<label>Page Type</label>
				<select id="wpr-ai-page-type">
					<option value="landing">Landing Page</option>
					<option value="homepage">Homepage</option>
					<option value="about">About Us</option>
					<option value="services">Services</option>
					<option value="portfolio">Portfolio</option>
					<option value="contact">Contact</option>
					<option value="pricing">Pricing</option>
					<option value="custom">Custom</option>
				</select>
			</div>
			<div class="wpr-ai-field">
				<label>Business / Site Name</label>
				<input type="text" id="wpr-ai-business-name" placeholder="e.g., TechStart Solutions">
			</div>
			<div class="wpr-ai-field">
				<label>Describe your page</label>
				<textarea id="wpr-ai-prompt" rows="5" placeholder="I need a landing page for my digital marketing agency. Include a hero section with a bold headline, list our services (SEO, PPC, Social Media, Web Design), add client testimonials, an FAQ section, and a call-to-action..."></textarea>
			</div>
		</div>

		<div class="wpr-ai-step">
			<h2>2. Style Preferences</h2>
			<div class="wpr-ai-field">
				<label>Style</label>
				<div class="wpr-ai-style-options">
					<label class="wpr-ai-style-option active" data-style="modern"><span>Modern</span></label>
					<label class="wpr-ai-style-option" data-style="classic"><span>Classic</span></label>
					<label class="wpr-ai-style-option" data-style="minimal"><span>Minimal</span></label>
					<label class="wpr-ai-style-option" data-style="bold"><span>Bold</span></label>
				</div>
			</div>
			<div class="wpr-ai-field">
				<label>Color Preference (optional)</label>
				<input type="text" id="wpr-ai-color" placeholder="e.g., Blue and white, Dark theme, Warm colors...">
			</div>
		</div>

		<div class="wpr-ai-actions">
			<button type="button" class="button button-primary button-hero" id="wpr-ai-generate-btn" <?php echo ! $has_key ? 'disabled' : ''; ?>>
				Generate Page
			</button>
		</div>
	</div>

	<!-- Progress Panel -->
	<div class="wpr-ai-panel wpr-ai-progress-panel" id="wpr-ai-progress" style="display:none;">
		<h2>Generating Your Page...</h2>
		<div class="wpr-ai-progress-steps">
			<div class="wpr-ai-progress-step" data-step="analyze">
				<span class="wpr-ai-step-icon">&#9634;</span>
				<span>Analyzing your requirements...</span>
			</div>
			<div class="wpr-ai-progress-step" data-step="ai">
				<span class="wpr-ai-step-icon">&#9634;</span>
				<span>AI is selecting sections and generating content...</span>
			</div>
			<div class="wpr-ai-progress-step" data-step="build">
				<span class="wpr-ai-step-icon">&#9634;</span>
				<span>Building page structure...</span>
			</div>
			<div class="wpr-ai-progress-step" data-step="create">
				<span class="wpr-ai-step-icon">&#9634;</span>
				<span>Creating WordPress page...</span>
			</div>
		</div>
		<div class="wpr-ai-progress-bar">
			<div class="wpr-ai-progress-fill" style="width:0%"></div>
		</div>
	</div>

	<!-- Result Panel -->
	<div class="wpr-ai-panel wpr-ai-result-panel" id="wpr-ai-result" style="display:none;">
		<div class="wpr-ai-result-success">
			<h2>Page Created Successfully!</h2>
			<p class="wpr-ai-result-title"></p>
			<p class="wpr-ai-result-info"></p>
			<div class="wpr-ai-result-actions">
				<a href="#" class="button button-primary button-hero wpr-ai-edit-btn" target="_blank">Edit with Elementor</a>
				<a href="#" class="button button-secondary wpr-ai-view-btn" target="_blank">View Page</a>
				<a href="#" class="button button-secondary wpr-ai-original-btn" target="_blank">View Original</a>
				<button type="button" class="button button-secondary wpr-ai-new-btn">Generate Another</button>
			</div>
		</div>
	</div>

	<!-- Error Panel -->
	<div class="wpr-ai-panel wpr-ai-error-panel" id="wpr-ai-error" style="display:none;">
		<h2>Something went wrong</h2>
		<p class="wpr-ai-error-message"></p>
		<button type="button" class="button button-secondary wpr-ai-retry-btn">Try Again</button>
	</div>
</div>
