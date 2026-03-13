(function ($) {
	'use strict';

	// Section ID => control IDs that indicate "configured"
	var sectionControls = {
		wpr_dc_visitor_roles: ['wpr_dc_visitor_type', 'wpr_dc_specific_users', 'wpr_dc_capability'],
		wpr_dc_user_profile: ['wpr_dc_user_meta_key'],
		wpr_dc_page_content: ['wpr_dc_post_types', 'wpr_dc_specific_posts', 'wpr_dc_taxonomy', 'wpr_dc_meta_key', 'wpr_dc_post_hierarchy'],
		wpr_dc_archive: ['wpr_dc_archive_types', 'wpr_dc_archive_term_property'],
		wpr_dc_date_time: ['wpr_dc_date_from', 'wpr_dc_date_to', 'wpr_dc_weekdays', 'wpr_dc_time_from', 'wpr_dc_time_to', 'wpr_dc_recurring_from', 'wpr_dc_recurring_to'],
		wpr_dc_device_browser: ['wpr_dc_devices', 'wpr_dc_browsers'],
		wpr_dc_visitor_location: ['wpr_dc_ip_addresses', 'wpr_dc_referrer_domains', 'wpr_dc_geo_countries', 'wpr_dc_geo_cities'],
		wpr_dc_url_parameters: ['wpr_dc_param_name'],
		wpr_dc_woocommerce: ['wpr_dc_woo_cart', 'wpr_dc_woo_cart_products', 'wpr_dc_woo_cart_categories', 'wpr_dc_woo_product_type'],
		wpr_dc_language: ['wpr_dc_languages'],
		wpr_dc_custom_fields: ['wpr_dc_acf_field'],
		wpr_dc_dynamic_tags: ['wpr_dc_dynamic_tag'],
		wpr_dc_interaction: ['wpr_dc_interaction_type'],
		wpr_dc_random_limits: ['wpr_dc_random_enabled', 'wpr_dc_limit_enabled'],
		wpr_dc_fallback: ['wpr_dc_fallback_enabled']
	};

	function hasValue(val) {
		if (val === null || val === undefined || val === '' || val === 'no') return false;
		if (Array.isArray(val) && val.length === 0) return false;
		return true;
	}

	// Labels and descriptions that change based on show/hide mode.
	// Each entry: [controlClass, showLabel, hideLabel, showDesc (optional), hideDesc (optional)]
	var modeSensitiveControls = [
		// Visitor & Roles
		['wpr_dc_visitor_type', 'Visible to', 'Hidden from',
			'Choose who can see this element. Leave empty to skip this condition.',
			'Choose who this element is hidden from. Leave empty to skip this condition.'],
		['wpr_dc_ip_addresses', 'Allowed IP Addresses', 'Blocked IP Addresses'],
		// Device & Browser
		['wpr_dc_devices', 'Show on Devices', 'Hide on Devices'],
		['wpr_dc_browsers', 'Show on Browsers', 'Hide on Browsers'],
		// Date & Time
		['wpr_dc_date_from', 'Visible From', 'Hidden From'],
		['wpr_dc_date_to', 'Visible Until', 'Hidden Until'],
		['wpr_dc_weekdays', 'Show on Days', 'Hide on Days',
			'Leave empty to show every day.', 'Leave empty to hide every day.'],
		['wpr_dc_time_from', 'Show After Time', 'Hide After Time'],
		// Page & Content
		['wpr_dc_post_types', 'Show on Content Types', 'Hide on Content Types',
			'Show when viewing these post types.', 'Hide when viewing these post types.'],
		['wpr_dc_specific_posts', 'Show on Specific Pages', 'Hide on Specific Pages'],
		['wpr_dc_terms', null, null,
			'Comma-separated term slugs or IDs. Show when the current post has any of these.',
			'Comma-separated term slugs or IDs. Hide when the current post has any of these.'],
		// Archive
		['wpr_dc_archive_types', 'Show on Page Types', 'Hide on Page Types',
			'Show when the current page matches any of these types.',
			'Hide when the current page matches any of these types.'],
		// Language
		['wpr_dc_languages', 'Show for Languages', 'Hide for Languages',
			'Show this element only for selected languages.',
			'Hide this element for selected languages.'],
		// Visitor Location
		['wpr_dc_referrer_domains', null, null,
			'Show when visitor comes from these sites. One domain per line, e.g. google.com.',
			'Hide when visitor comes from these sites. One domain per line, e.g. google.com.'],
		// WooCommerce
		['wpr_dc_woo_cart_products', null, null,
			'Comma-separated product IDs. Show when any of these are in cart.',
			'Comma-separated product IDs. Hide when any of these are in cart.'],
		['wpr_dc_woo_cart_categories', null, null,
			'Comma-separated category slugs. Show when products from these categories are in cart.',
			'Comma-separated category slugs. Hide when products from these categories are in cart.'],
		['wpr_dc_woo_product_type', null, null,
			'Show on product pages of these types.',
			'Hide on product pages of these types.']
	];

	function updateModeLabels() {
		var currentElement = elementor.getCurrentElement();
		if (!currentElement) return;

		var settings = currentElement.model.get('settings');
		if (!settings) return;

		var mode = settings.get('wpr_dc_mode') || 'show';
		var isHide = mode === 'hide';

		for (var i = 0; i < modeSensitiveControls.length; i++) {
			var entry = modeSensitiveControls[i];
			var $control = $('.elementor-control-' + entry[0]);
			if (!$control.length) continue;

			// Update label if provided
			if (entry[1] && entry[2]) {
				$control.find('.elementor-control-title').text(isHide ? entry[2] : entry[1]);
			}

			// Update description if provided
			if (entry[3] && entry[4]) {
				$control.find('.elementor-control-field-description').text(isHide ? entry[4] : entry[3]);
			}
		}
	}

	function checkState() {
		var currentElement = elementor.getCurrentElement();
		if (!currentElement) return;

		var settings = currentElement.model.get('settings');
		if (!settings) return;

		updateModeLabels();

		$('#elementor-controls .elementor-control-type-section').each(function () {
			var $section = $(this);
			$section.removeClass('wpr-dc-status-active');

			$.each(sectionControls, function (sectionId, controls) {
				if ($section.hasClass('elementor-control-' + sectionId)) {
					for (var i = 0; i < controls.length; i++) {
						var val = settings.get(controls[i]);
						if (hasValue(val)) {
							$section.addClass('wpr-dc-status-active');
							return false; // break $.each
						}
					}
				}
			});
		});
	}

	$(window).on('elementor:init', function () {
		// Check on switcher clicks and section header clicks
		$(document).on(
			'click',
			'.elementor-control-type-switcher .elementor-switch, .elementor-panel-heading, .elementor-control-type-select2 .select2',
			function () {
				setTimeout(checkState, 100);
			}
		);

		// Check on input changes (text fields, selects)
		$(document).on(
			'change input',
			'#elementor-controls input, #elementor-controls select, #elementor-controls textarea',
			function () {
				setTimeout(checkState, 100);
			}
		);

		// Check when section is activated in panel
		elementor.channels.editor.on('section:activated', function () {
			setTimeout(checkState, 100);
		});

		// Update labels when mode dropdown changes
		$(document).on('change', '.elementor-control-wpr_dc_mode select', function () {
			setTimeout(updateModeLabels, 50);
		});

		// Check when element panel opens
		elementor.hooks.addAction('panel/open_editor/widget', function () {
			setTimeout(checkState, 200);
		});
		elementor.hooks.addAction('panel/open_editor/section', function () {
			setTimeout(checkState, 200);
		});
		elementor.hooks.addAction('panel/open_editor/column', function () {
			setTimeout(checkState, 200);
		});
		elementor.hooks.addAction('panel/open_editor/container', function () {
			setTimeout(checkState, 200);
		});
	});

})(jQuery);
