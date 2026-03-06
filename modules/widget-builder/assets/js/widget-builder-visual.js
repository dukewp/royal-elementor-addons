/**
 * Widget Builder Visual UI
 * Chapter 1: Panel shell, tab switching, Monaco initialization
 */
(function ($) {
	'use strict';

	/* ------------------------------------------------------------------
	 * Global references
	 * ----------------------------------------------------------------*/
	var editors = {};          // Monaco editor instances { html, css, js }
	var monacoReady = false;

	/* ------------------------------------------------------------------
	 * Tab switching — Center panel (Content / Style / Advanced)
	 * ----------------------------------------------------------------*/
	function initCenterTabs() {
		$('.wpr-wb-center-tab').on('click', function () {
			var tab = $(this).data('tab');

			// Tabs
			$('.wpr-wb-center-tab').removeClass('active');
			$(this).addClass('active');

			// Panels
			$('.wpr-wb-center-panel').removeClass('active');
			$('.wpr-wb-center-panel[data-tab="' + tab + '"]').addClass('active');
		});
	}

	/* ------------------------------------------------------------------
	 * Tab switching — Right panel (HTML / CSS / JS / Includes)
	 * ----------------------------------------------------------------*/
	function initCodeTabs() {
		$('.wpr-wb-code-tab').on('click', function () {
			var editor = $(this).data('editor');

			// Tabs
			$('.wpr-wb-code-tab').removeClass('active');
			$(this).addClass('active');

			// Panels
			$('.wpr-wb-code-panel').removeClass('active');
			$('.wpr-wb-code-panel[data-editor="' + editor + '"]').addClass('active');

			// Re-layout the Monaco editor so it fills the container
			if (editors[editor]) {
				editors[editor].layout();
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Footer buttons — Settings / Preview / Save
	 * ----------------------------------------------------------------*/
	function initPanelCollapse() {
		var $panel = $('.wpr-wb-panel-left');
		var $btn = $('#wpr-wb-collapse-left');

		// Restore saved state
		if (localStorage.getItem('wpr_wb_panel_collapsed') === '1') {
			$panel.addClass('collapsed');
			$btn.find('i').removeClass('eicon-chevron-left').addClass('eicon-chevron-right');
		}

		$btn.on('click', function () {
			$panel.toggleClass('collapsed');
			$(this).find('i').toggleClass('eicon-chevron-left eicon-chevron-right');
			localStorage.setItem('wpr_wb_panel_collapsed', $panel.hasClass('collapsed') ? '1' : '0');
		});
	}

	function initFooterButtons() {
		// SETTINGS button — just marks active state
		$('#wpr-wb-btn-settings').on('click', function () {
			$('.wpr-wb-footer-btn').not('.wpr-wb-footer-btn-save').removeClass('active');
			$(this).addClass('active');
		});

		// PREVIEW button — open Elementor preview in new tab
		$('#wpr-wb-btn-preview').on('click', function () {
			$('.wpr-wb-footer-btn').not('.wpr-wb-footer-btn-save').removeClass('active');
			$(this).addClass('active');

			var url = wprWidgetBuilder.previewUrl;
			if (url) {
				window.open(url, '_blank');
			} else {
				showToast('Save the widget first to preview.', 'error');
			}
		});

		// SAVE button
		$('#wpr-wb-btn-save, #wpr-wb-collapsed-save').on('click', function () {
			saveWidget();
		});
	}

	/* ------------------------------------------------------------------
	 * Monaco Editor — load via AMD & initialise
	 * ----------------------------------------------------------------*/
	function initMonaco() {
		var basePath = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min';

		// Inject the AMD loader if not present
		if (typeof window.require === 'undefined' || typeof window.require.config === 'undefined') {
			var script = document.createElement('script');
			script.src = basePath + '/vs/loader.js';
			script.onload = function () {
				configureAndCreateEditors(basePath);
			};
			document.head.appendChild(script);
		} else {
			configureAndCreateEditors(basePath);
		}
	}

	function configureAndCreateEditors(basePath) {
		window.require.config({ paths: { vs: basePath + '/vs' } });

		window.require(['vs/editor/editor.main'], function () {
			monacoReady = true;

			// Use decorations to highlight {{...}} template tags in white
			function applyTemplateTagDecorations(editor) {
				var model = editor.getModel();
				if (!model) return;

				var decorations = [];
				var text = model.getValue();
				var regex = /\{\{[^}]*\}\}/g;
				var match;

				while ((match = regex.exec(text)) !== null) {
					var startPos = model.getPositionAt(match.index);
					var endPos = model.getPositionAt(match.index + match[0].length);
					decorations.push({
						range: new monaco.Range(startPos.lineNumber, startPos.column, endPos.lineNumber, endPos.column),
						options: { inlineClassName: 'wpr-template-tag' }
					});
				}

				editor._templateTagDecorations = editor.deltaDecorations(
					editor._templateTagDecorations || [],
					decorations
				);
			}

			editors.html = monaco.editor.create(document.getElementById('wpr-wb-editor-html'), {
				value: '',
				language: 'html',
				theme: 'vs-dark',
				lineNumbers: 'on',
				minimap: { enabled: false },
				wordWrap: 'on',
				fontSize: 14,
				automaticLayout: true,
				scrollBeyondLastLine: false,
				tabSize: 4,
			});

			editors.css = monaco.editor.create(document.getElementById('wpr-wb-editor-css'), {
				value: '',
				language: 'css',
				theme: 'vs-dark',
				lineNumbers: 'on',
				minimap: { enabled: false },
				wordWrap: 'on',
				fontSize: 14,
				automaticLayout: true,
				scrollBeyondLastLine: false,
				tabSize: 4,
			});

			editors.js = monaco.editor.create(document.getElementById('wpr-wb-editor-js'), {
				value: '',
				language: 'javascript',
				theme: 'vs-dark',
				lineNumbers: 'on',
				minimap: { enabled: false },
				wordWrap: 'on',
				fontSize: 14,
				automaticLayout: true,
				scrollBeyondLastLine: false,
				tabSize: 4,
			});

			// Apply template tag decorations to all editors
			['html', 'css', 'js'].forEach(function (key) {
				if (editors[key]) {
					applyTemplateTagDecorations(editors[key]);
					editors[key].onDidChangeModelContent(function () {
						applyTemplateTagDecorations(editors[key]);
					});
				}
			});

			// Load saved data into editors
			loadWidget();
		});
	}

	/* ------------------------------------------------------------------
	 * Toast notification
	 * ----------------------------------------------------------------*/
	function showToast(message, type) {
		var $toast = $('#wpr-wb-toast');
		$toast.text(message)
			.removeClass('success error visible')
			.addClass(type || '')
			.addClass('visible');

		clearTimeout($toast.data('timer'));
		$toast.data('timer', setTimeout(function () {
			$toast.removeClass('visible');
		}, 3000));
	}

	/* ------------------------------------------------------------------
	 * Save Widget via REST API
	 * ----------------------------------------------------------------*/
	function saveWidget() {
		if (!wprWidgetBuilder.postId) {
			// Create new widget first
			createWidget();
			return;
		}

		var data = collectData();

		$('#wpr-wb-btn-save').prop('disabled', true).text('SAVING...');

		$.ajax({
			url: wprWidgetBuilder.apiBase + 'save/' + wprWidgetBuilder.postId,
			method: 'POST',
			contentType: 'application/json',
			headers: { 'X-WP-Nonce': wprWidgetBuilder.nonce },
			data: JSON.stringify(data),
			success: function (res) {
				showToast('Widget saved successfully!', 'success');
				$('#wpr-wb-btn-save').prop('disabled', false).text('SAVE');
			},
			error: function (xhr) {
				var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Save failed.';
				showToast(msg, 'error');
				$('#wpr-wb-btn-save').prop('disabled', false).text('SAVE');
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Create new widget (when postId is 0)
	 * ----------------------------------------------------------------*/
	function createWidget() {
		var data = collectData();

		$('#wpr-wb-btn-save').prop('disabled', true).text('SAVING...');

		$.ajax({
			url: wprWidgetBuilder.apiBase + 'save',
			method: 'POST',
			contentType: 'application/json',
			headers: { 'X-WP-Nonce': wprWidgetBuilder.nonce },
			data: JSON.stringify(data),
			success: function (res) {
				if (res.post_id) {
					wprWidgetBuilder.postId = res.post_id;
					// Update URL without reload
					var newUrl = window.location.pathname + '?post=' + res.post_id + '&action=edit';
					window.history.replaceState(null, '', newUrl);
				}
				showToast('Widget created successfully!', 'success');
				$('#wpr-wb-btn-save').prop('disabled', false).text('SAVE');
			},
			error: function (xhr) {
				var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Create failed.';
				showToast(msg, 'error');
				$('#wpr-wb-btn-save').prop('disabled', false).text('SAVE');
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Collect all data from UI
	 * ----------------------------------------------------------------*/
	function collectData() {
		return {
			title: $('#wpr-wb-title').val() || 'New Widget',
			icon: $('#wpr-wb-icon').val() || 'eicon-cog',
			category: $('#wpr-wb-category').val() || 'wpr-widgets',
			tabs: {
				content: collectTabSections('content'),
				style: collectTabSections('style'),
				advanced: collectTabSections('advanced')
			},
			markup: editors.html ? editors.html.getValue() : '',
			css: editors.css ? editors.css.getValue() : '',
			js: editors.js ? editors.js.getValue() : '',
			css_includes: $('#wpr-wb-css-includes').val() ? $('#wpr-wb-css-includes').val().split('\n').filter(Boolean) : [],
			js_includes: $('#wpr-wb-js-includes').val() ? $('#wpr-wb-js-includes').val().split('\n').filter(Boolean) : [],
		};
	}

	/* ------------------------------------------------------------------
	 * Load Widget data via REST API
	 * ----------------------------------------------------------------*/
	function loadWidget() {
		if (!wprWidgetBuilder.postId) {
			return; // New widget, nothing to load
		}

		$('#wpr-wb-app').addClass('loading');

		$.ajax({
			url: wprWidgetBuilder.apiBase + 'load/' + wprWidgetBuilder.postId,
			method: 'GET',
			headers: { 'X-WP-Nonce': wprWidgetBuilder.nonce },
			success: function (res) {
				populateUI(res.data || res);
				$('#wpr-wb-app').removeClass('loading');
	
			},
			error: function () {
				showToast('Failed to load widget data.', 'error');
				$('#wpr-wb-app').removeClass('loading');
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Populate UI from loaded data
	 * ----------------------------------------------------------------*/
	function populateUI(data) {
		if (!data) return;

		// Left panel
		if (data.title) {
			$('#wpr-wb-title').val(data.title);
			$('#wpr-wb-header-title').text(data.title);
		}

		if (data.icon) {
			$('#wpr-wb-icon').val(data.icon);
			$('#wpr-wb-icon-preview-i').attr('class', data.icon);
		}

		// Category: API stores as categories array, UI uses single value
		var cat = data.category || (data.categories && data.categories[0]) || '';
		if (cat) {
			$('#wpr-wb-category').val(cat);
		}

		// Center panel — sections
		if (data.tabs) {
			populateSections('content', data.tabs.content);
			populateSections('style', data.tabs.style);
			populateSections('advanced', data.tabs.advanced);
		}

		// Right panel — Monaco editors
		if (editors.html && data.markup) {
			editors.html.setValue(data.markup);
		}
		if (editors.css && data.css) {
			editors.css.setValue(data.css);
		}
		if (editors.js && data.js) {
			editors.js.setValue(data.js);
		}

		// Includes
		if (data.css_includes && Array.isArray(data.css_includes)) {
			$('#wpr-wb-css-includes').val(data.css_includes.join('\n'));
		}
		if (data.js_includes && Array.isArray(data.js_includes)) {
			$('#wpr-wb-js-includes').val(data.js_includes.join('\n'));
		}
	}

	/* ------------------------------------------------------------------
	 * Update header title in real time
	 * ----------------------------------------------------------------*/
	function initTitleSync() {
		$('#wpr-wb-title').on('input', function () {
			var val = $(this).val() || 'New Widget';
			$('#wpr-wb-header-title').text(val);
		});
	}

	/* ------------------------------------------------------------------
	 * Keyboard shortcuts
	 * ----------------------------------------------------------------*/
	function initKeyboardShortcuts() {
		$(document).on('keydown', function (e) {
			// Ctrl+S / Cmd+S — Save
			if ((e.ctrlKey || e.metaKey) && e.key === 's') {
				e.preventDefault();
				saveWidget();
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Unsaved changes warning
	 * ----------------------------------------------------------------*/
	function initBeforeUnload() {
		var dirty = false;

		// Mark dirty on any input change
		$('#wpr-wb-app').on('input change', 'input, select, textarea', function () {
			dirty = true;
		});

		// Mark dirty when Monaco content changes
		var checkMonacoDirty = setInterval(function () {
			if (monacoReady) {
				clearInterval(checkMonacoDirty);
				['html', 'css', 'js'].forEach(function (key) {
					if (editors[key]) {
						editors[key].onDidChangeModelContent(function () {
							dirty = true;
						});
					}
				});
			}
		}, 500);

		// Clear dirty after save
		$(document).ajaxSuccess(function (e, xhr, settings) {
			if (settings.url && settings.url.indexOf('widget-builder/save') !== -1) {
				dirty = false;
			}
		});

		window.addEventListener('beforeunload', function (e) {
			if (dirty) {
				e.preventDefault();
				e.returnValue = '';
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Sections system — add / rename / collapse / delete / reorder
	 * ----------------------------------------------------------------*/
	var sectionCounter = 0;  // unique id counter

	function initSections() {
		// Add Section buttons
		$('.wpr-wb-add-section-btn').on('click', function () {
			var tab = $(this).data('tab');
			addSection(tab);
		});

		// Delegate: toggle collapse (with animation)
		$('.wpr-wb-center-body').on('click', '.wpr-wb-section-toggle', function (e) {
			e.stopPropagation();
			var $item = $(this).closest('.wpr-wb-section-item');
			var $body = $item.find('.wpr-wb-section-body');

			if ($item.hasClass('collapsed')) {
				$item.removeClass('collapsed');
				$body.hide().slideDown(150);
			} else {
				$body.slideUp(150, function () {
					$item.addClass('collapsed');
				});
			}
		});

		// Delegate: delete section
		$('.wpr-wb-center-body').on('click', '.wpr-wb-section-delete', function (e) {
			e.stopPropagation();
			var $section = $(this).closest('.wpr-wb-section-item');
			$section.slideUp(200, function () {
				$section.remove();
	
			});
		});

		// Delegate: toggle section settings panel
		$('.wpr-wb-center-body').on('click', '.wpr-wb-section-settings-btn', function (e) {
			e.stopPropagation();
			var $settings = $(this).closest('.wpr-wb-section-item').find('.wpr-wb-section-settings');
			$settings.slideToggle(150);
		});

		// Delegate: sync section key when edited in settings
		$('.wpr-wb-center-body').on('input', '.wpr-wb-section-key-input', function () {
			var $section = $(this).closest('.wpr-wb-section-item');
			$section.attr('data-section-key', $(this).val());
			$section.data('section-key', $(this).val());
		});

		// Init sortable on each sections list
		$('.wpr-wb-sections-list').sortable({
			handle: '.wpr-wb-section-drag',
			placeholder: 'wpr-wb-section-placeholder',
			tolerance: 'pointer',
			axis: 'y',
			cursor: 'grabbing',
			forcePlaceholderSize: true,
		});
	}

	/**
	 * Add a new section to a tab
	 */
	function addSection(tab, label, key, description) {
		sectionCounter++;
		var sKey = key || 'section_' + sectionCounter;
		var sLabel = label || 'Section ' + sectionCounter;
		var sDesc = description || '';

		var html = ''
			+ '<div class="wpr-wb-section-item" data-section-key="' + sKey + '">'
			+   '<div class="wpr-wb-section-header">'
			+     '<span class="wpr-wb-section-drag"><i class="eicon-handle"></i></span>'
			+     '<input type="text" class="wpr-wb-section-title-input" value="' + escAttr(sLabel) + '" data-field="label">'
			+     '<div class="wpr-wb-section-actions">'
			+       '<button type="button" class="wpr-wb-section-settings-btn" title="Section Settings"><i class="eicon-cog"></i></button>'
			+       '<button type="button" class="wpr-wb-section-toggle" title="Toggle"><i class="eicon-caret-down"></i></button>'
			+       '<button type="button" class="wpr-wb-section-delete" title="Delete"><i class="eicon-trash-o"></i></button>'
			+     '</div>'
			+   '</div>'
			+   '<div class="wpr-wb-section-settings" style="display:none;">'
			+     '<div class="wpr-wb-control-field">'
			+       '<label class="wpr-wb-control-field-label">Section Key</label>'
			+       '<input type="text" class="wpr-wb-control-field-input wpr-wb-section-key-input" value="' + escAttr(sKey) + '" data-field="section_key">'
			+     '</div>'
			+     '<div class="wpr-wb-control-field">'
			+       '<label class="wpr-wb-control-field-label">Description</label>'
			+       '<input type="text" class="wpr-wb-control-field-input" value="' + escAttr(sDesc) + '" data-field="description" placeholder="Optional section description">'
			+     '</div>'
			+   '</div>'
			+   '<div class="wpr-wb-section-body">'
			+     '<div class="wpr-wb-section-controls"></div>'
			+     '<button type="button" class="wpr-wb-add-control-btn"><i class="eicon-plus"></i> Add Control</button>'
			+   '</div>'
			+ '</div>';

		var $list = $('.wpr-wb-sections-list[data-tab="' + tab + '"]');
		var $section = $(html).hide();
		$list.append($section);
		$section.slideDown(200);

		// Refresh sortable
		$list.sortable('refresh');

		return $section;
	}

	/**
	 * Collect all sections & controls from a tab into an array
	 */
	function collectTabSections(tab) {
		var sections = [];
		$('.wpr-wb-sections-list[data-tab="' + tab + '"] .wpr-wb-section-item').each(function () {
			var $s = $(this);
			var sectionData = {
				key: $s.find('.wpr-wb-section-key-input').val() || $s.data('section-key'),
				label: $s.find('.wpr-wb-section-title-input').val(),
				description: $s.find('[data-field="description"]').val() || '',
				controls: []
			};

			// Controls will be collected here in Chapter 4
			$s.find('.wpr-wb-section-controls .wpr-wb-control-item').each(function () {
				var $c = $(this);
				sectionData.controls.push(collectControlData($c));
			});

			sections.push(sectionData);
		});
		return sections;
	}

	/**
	 * Populate sections from loaded data
	 */
	function populateSections(tab, sections) {
		if (!sections || !Array.isArray(sections)) return;

		for (var i = 0; i < sections.length; i++) {
			var s = sections[i];
			sectionCounter++;
			var $section = addSection(tab, s.label, s.key, s.description);

			if (s.controls && Array.isArray(s.controls)) {
				for (var j = 0; j < s.controls.length; j++) {
					var $ctrl = addControl($section, s.controls[j]);
					$ctrl.addClass('collapsed'); // collapse when loading saved data
				}
			}
		}
	}

	/**
	 * Simple HTML attribute escaping
	 */
	function escAttr(str) {
		return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}

	/* ------------------------------------------------------------------
	 * Control Types Registry
	 * ----------------------------------------------------------------*/
	var controlTypes = {
		// Basic
		text:               { label: 'Text',              icon: 'eicon-t-letter-bold', group: 'Basic' },
		number:             { label: 'Number',            icon: 'eicon-number-field',  group: 'Basic' },
		textarea:           { label: 'Textarea',          icon: 'eicon-text-area',     group: 'Basic' },
		wysiwyg:            { label: 'WYSIWYG',           icon: 'eicon-text',          group: 'Basic' },
		code:               { label: 'Code',              icon: 'eicon-code',          group: 'Basic' },
		// Choice
		select:             { label: 'Select',            icon: 'eicon-select',        group: 'Choice' },
		select2:            { label: 'Select2',           icon: 'eicon-select',        group: 'Choice' },
		switcher:           { label: 'Switcher',          icon: 'eicon-toggle',        group: 'Choice' },
		choose:             { label: 'Choose',            icon: 'eicon-text-align-left', group: 'Choice' },
		// Media
		media:              { label: 'Media',             icon: 'eicon-image',         group: 'Media' },
		icons:              { label: 'Icons',             icon: 'eicon-star',          group: 'Media' },
		// Design
		color:              { label: 'Color',             icon: 'eicon-paint-brush',   group: 'Design' },
		slider:             { label: 'Slider',            icon: 'eicon-h-align-stretch',   group: 'Design' },
		dimensions:         { label: 'Dimensions',        icon: 'eicon-frame-expand',  group: 'Design' },
		url:                { label: 'URL',               icon: 'eicon-url',           group: 'Basics' },
		// Group Controls
		typography:         { label: 'Typography',        icon: 'eicon-typography',    group: 'Group' },
		background:         { label: 'Background',        icon: 'eicon-background',    group: 'Group' },
		border:             { label: 'Border',            icon: 'eicon-square',        group: 'Group' },
		box_shadow:         { label: 'Box Shadow',        icon: 'eicon-lightbox',      group: 'Group' },
		text_shadow:        { label: 'Text Shadow',       icon: 'eicon-font',          group: 'Group' },
		// Other
		font:               { label: 'Font',              icon: 'eicon-font',          group: 'Other' },
		date_time:          { label: 'Date/Time',         icon: 'eicon-date',          group: 'Other' },
	};

	/** Group controls that need selector instead of options/default */
	var groupControlTypes = ['typography','background','border','box_shadow','text_shadow'];

	/** Controls that use options (key:value pairs) */
	var optionControlTypes = ['select','select2','choose'];

	var controlCounter = 0;

	/* ------------------------------------------------------------------
	 * Control Type Picker Modal
	 * ----------------------------------------------------------------*/
	var pendingSection = null; // which section the control will be added to

	function initControlPicker() {
		// Build the type grid once
		buildControlTypeGrid();

		// Close modal
		$('#wpr-wb-control-modal-close').on('click', closeControlModal);
		$('#wpr-wb-control-modal').on('click', function (e) {
			if (e.target === this) closeControlModal();
		});
		$(document).on('keydown', function (e) {
			if (e.key === 'Escape' && $('#wpr-wb-control-modal').hasClass('open')) {
				closeControlModal();
			}
		});

		// Search
		$('#wpr-wb-control-search').on('input', function () {
			filterControlTypes($(this).val().toLowerCase());
		});

		// Select a control type
		$('#wpr-wb-control-type-grid').on('click', '.wpr-wb-ct-item', function () {
			var type = $(this).data('type');
			if (pendingSection) {
				addControl(pendingSection, { type: type });
				pendingSection = null;
			}
			closeControlModal();
		});
	}

	function openControlModal($section) {
		pendingSection = $section;
		$('#wpr-wb-control-modal').addClass('open');
		$('#wpr-wb-control-search').val('').focus();
		filterControlTypes('');
	}

	function closeControlModal() {
		$('#wpr-wb-control-modal').removeClass('open');
		pendingSection = null;
	}

	function buildControlTypeGrid() {
		var groups = {};
		for (var type in controlTypes) {
			var ct = controlTypes[type];
			if (!groups[ct.group]) groups[ct.group] = [];
			groups[ct.group].push({ type: type, label: ct.label, icon: ct.icon });
		}

		var html = '';
		var groupOrder = ['Basic', 'Choice', 'Media', 'Design', 'Group', 'Other'];
		for (var g = 0; g < groupOrder.length; g++) {
			var gName = groupOrder[g];
			if (!groups[gName]) continue;
			html += '<div class="wpr-wb-ct-group" data-group="' + gName + '">';
			html += '<div class="wpr-wb-ct-group-title">' + gName + '</div>';
			html += '<div class="wpr-wb-ct-group-items">';
			for (var i = 0; i < groups[gName].length; i++) {
				var item = groups[gName][i];
				html += '<div class="wpr-wb-ct-item" data-type="' + item.type + '" data-label="' + item.label.toLowerCase() + '">';
				html += '<div class="wpr-wb-ct-item-icon"><i class="' + item.icon + '"></i></div>';
				html += '<span class="wpr-wb-ct-item-name">' + item.label + '</span>';
				html += '</div>';
			}
			html += '</div></div>';
		}

		$('#wpr-wb-control-type-grid').html(html);
	}

	function filterControlTypes(query) {
		if (!query) {
			$('#wpr-wb-control-type-grid .wpr-wb-ct-item').show();
			$('#wpr-wb-control-type-grid .wpr-wb-ct-group').show();
			return;
		}
		$('#wpr-wb-control-type-grid .wpr-wb-ct-item').each(function () {
			var match = $(this).data('label').indexOf(query) !== -1 || $(this).data('type').indexOf(query) !== -1;
			$(this).toggle(match);
		});
		// Hide empty groups
		$('#wpr-wb-control-type-grid .wpr-wb-ct-group').each(function () {
			var visible = $(this).find('.wpr-wb-ct-item:visible').length > 0;
			$(this).toggle(visible);
		});
	}

	/* ------------------------------------------------------------------
	 * Controls — add / delete / duplicate / collapse / reorder
	 * ----------------------------------------------------------------*/
	function addControl($section, conf) {
		controlCounter++;
		conf = conf || {};
		var type = conf.type || 'text';
		var ct = controlTypes[type] || controlTypes.text;
		var key = conf.key || type + '_' + controlCounter;
		var label = conf.label || ct.label;
		var isGroup = groupControlTypes.indexOf(type) !== -1;

		var html = ''
			+ '<div class="wpr-wb-control-item" data-type="' + type + '">'
			+   '<div class="wpr-wb-control-header">'
			+     '<span class="wpr-wb-control-drag"><i class="eicon-handle"></i></span>'
			+     '<span class="wpr-wb-control-type-icon"><i class="' + ct.icon + '"></i></span>'
			+     '<div class="wpr-wb-control-info">'
			+       '<span class="wpr-wb-control-label-text">' + escAttr(label) + '</span>'
			+       '<span class="wpr-wb-control-key-text">' + escAttr(key) + '</span>'
			+     '</div>'
			+     '<div class="wpr-wb-control-actions">'
			+       '<button type="button" class="wpr-wb-control-duplicate" title="Duplicate"><i class="eicon-clone"></i></button>'
			+       '<button type="button" class="wpr-wb-control-remove" title="Delete"><i class="eicon-trash-o"></i></button>'
			+       '<button type="button" class="wpr-wb-control-toggle-btn" title="Toggle"><i class="eicon-caret-down"></i></button>'
			+     '</div>'
			+   '</div>'
			+   '<div class="wpr-wb-control-body">'
			+     buildControlFields(type, key, label, conf, isGroup)
			+   '</div>'
			+ '</div>';

		var $controls = $section.find('.wpr-wb-section-controls');
		var $control = $(html);

		// Collapse all existing controls in this section
		$controls.find('.wpr-wb-control-item').addClass('collapsed');

		// Store initial data
		$control.data('control', $.extend({ type: type, key: key, label: label }, conf));

		$controls.append($control);
		initControlSortable($controls);

		return $control;
	}

	/**
	 * Build a single option row for the options repeater
	 */
	function buildOptionRow(key, title, icon) {
		var hasIcon = (icon !== null && icon !== undefined);
		var h = '<div class="wpr-wb-option-row">';
		h += '<span class="wpr-wb-option-drag"><i class="eicon-handle"></i></span>';
		h += '<input type="text" class="wpr-wb-option-key" value="' + escAttr(key || '') + '" placeholder="key">';
		h += '<input type="text" class="wpr-wb-option-title" value="' + escAttr(title || '') + '" placeholder="title">';
		if (hasIcon) {
			h += '<button type="button" class="wpr-wb-option-icon-btn" title="Choose Icon">';
			h += '<i class="' + (icon ? escAttr(icon) : 'eicon-star') + '"></i>';
			h += '</button>';
			h += '<input type="hidden" class="wpr-wb-option-icon" value="' + escAttr(icon || '') + '">';
		}
		h += '<button type="button" class="wpr-wb-option-remove" title="Remove"><i class="eicon-close"></i></button>';
		h += '</div>';
		return h;
	}

	/* ------------------------------------------------------------------
	 * CSS Selector Repeater Helpers
	 * ----------------------------------------------------------------*/
	var selectorPlaceholders = {
		color: 'color: {{VALUE}}',
		slider: 'width: {{SIZE}}{{UNIT}}',
		dimensions: 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}}',
		choose: 'text-align: {{VALUE}}',
		select: 'display: {{VALUE}}',
		number: 'opacity: {{VALUE}}',
		font: 'font-family: {{VALUE}}',
	};

	function buildSelectorRow(selector, declaration, controlType) {
		var placeholder = selectorPlaceholders[controlType] || 'color: {{VALUE}}';
		var h = '<div class="wpr-wb-selector-row">';
		h += '<div class="wpr-wb-selector-row-header">';
		h += '<span class="wpr-wb-selector-row-drag"><i class="eicon-handle"></i></span>';
		h += '<button type="button" class="wpr-wb-selector-row-remove"><i class="eicon-close"></i></button>';
		h += '</div>';
		h += '<input type="text" class="wpr-wb-selector-row-sel" value="' + escAttr(selector || '') + '" placeholder=".my-element">';
		h += '<input type="text" class="wpr-wb-selector-row-decl" value="' + escAttr(declaration || '') + '" placeholder="' + placeholder + '">';
		h += '</div>';
		return h;
	}

	/**
	 * Build the configuration fields HTML for a control based on its type
	 */
	function buildControlFields(type, key, label, conf, isGroup) {
		conf = conf || {};
		var h = '';

		// Label field (all controls)
		h += '<div class="wpr-wb-control-field">';
		h += '<label class="wpr-wb-control-field-label">Label</label>';
		h += '<input type="text" class="wpr-wb-control-field-input" data-field="label" value="' + escAttr(label) + '">';
		h += '</div>';

		// Key field (all controls)
		h += '<div class="wpr-wb-control-field">';
		h += '<label class="wpr-wb-control-field-label">Key</label>';
		h += '<div class="wpr-wb-key-input-wrap">';
		h += '<input type="text" class="wpr-wb-control-field-input" data-field="key" value="' + escAttr(key) + '">';
		h += '<button type="button" class="wpr-wb-key-copy-btn" title="Copy key"><i class="eicon-copy"></i></button>';
		h += '</div>';
		var hintTag = (type === 'icons') ? '{{icon(' + escAttr(key) + ')}}' : (type === 'media') ? '{{' + escAttr(key) + '.url}}' : '{{' + escAttr(key) + '}}';
		h += '<span class="wpr-wb-control-field-hint">Use in template: ' + hintTag + '</span>';
		h += '</div>';

		if (isGroup) {
			// Selector for group controls
			h += '<div class="wpr-wb-control-field">';
			h += '<label class="wpr-wb-control-field-label">CSS Selector</label>';
			h += '<input type="text" class="wpr-wb-control-field-input" data-field="selector" value="' + escAttr(conf.selector || '') + '" placeholder=".my-element">';
			h += '<span class="wpr-wb-control-field-hint">Applied to: {{WRAPPER}} .selector</span>';
			h += '</div>';
		} else {
			// Default value (non-group, skip icons)
			if (type !== 'icons') {
				h += '<div class="wpr-wb-control-field">';
				h += '<label class="wpr-wb-control-field-label">Default Value</label>';
				h += '<input type="text" class="wpr-wb-control-field-input" data-field="default" value="' + escAttr(conf.default || '') + '">';
				h += '</div>';

				// Slider min/max
				if (type === 'slider') {
					var sliderMin = (conf.slider_min !== undefined && conf.slider_min !== '') ? conf.slider_min : '0';
					var sliderMax = (conf.slider_max !== undefined && conf.slider_max !== '') ? conf.slider_max : '1000';
					var sliderStep = (conf.slider_step !== undefined && conf.slider_step !== '') ? conf.slider_step : '1';
					h += '<div class="wpr-wb-control-field">';
					h += '<label class="wpr-wb-control-field-label">Range</label>';
					h += '<div class="wpr-wb-slider-range-row">';
					h += '<div class="wpr-wb-slider-range-input"><span>Min</span><input type="number" step="any" class="wpr-wb-control-field-input" data-field="slider_min" value="' + escAttr(sliderMin) + '"></div>';
					h += '<div class="wpr-wb-slider-range-input"><span>Max</span><input type="number" step="any" class="wpr-wb-control-field-input" data-field="slider_max" value="' + escAttr(sliderMax) + '"></div>';
					h += '<div class="wpr-wb-slider-range-input"><span>Step</span><input type="number" step="any" class="wpr-wb-control-field-input" data-field="slider_step" value="' + escAttr(sliderStep) + '"></div>';
					h += '</div>';
					h += '</div>';
				}

				h += '<hr class="wpr-wb-control-separator">';
			}

			// Options repeater for select/select2/choose
			if (optionControlTypes.indexOf(type) !== -1) {
				var isChoose = (type === 'choose');
				h += '<div class="wpr-wb-control-field">';
				h += '<label class="wpr-wb-control-field-label">Options</label>';
				h += '<div class="wpr-wb-options-repeater" data-type="' + type + '">';

				// Render existing options
				if (conf.options && typeof conf.options === 'object') {
					for (var ok in conf.options) {
						var optVal = conf.options[ok];
						var optTitle = '';
						var optIcon = '';
						if (isChoose && typeof optVal === 'object') {
							optTitle = optVal.title || '';
							optIcon = optVal.icon || '';
						} else {
							optTitle = (typeof optVal === 'string') ? optVal : '';
						}
						h += buildOptionRow(ok, optTitle, isChoose ? optIcon : null);
					}
				}

				h += '</div>'; // end repeater
				h += '<button type="button" class="wpr-wb-option-add-btn"><i class="eicon-plus"></i> Add Option</button>';
				var optHint = (type === 'choose') ? 'Add options with key, label, and icon' : 'Add key-value pairs for dropdown options';
				h += '<span class="wpr-wb-control-field-hint">' + optHint + '</span>';
				h += '</div>';
				h += '<hr class="wpr-wb-control-separator">';
			}

			// CSS Styles (only for style-related controls)
			var selectorTypes = ['color', 'slider', 'dimensions', 'choose', 'select', 'number', 'font'];
			if (selectorTypes.indexOf(type) !== -1) {
				h += '<div class="wpr-wb-control-field wpr-wb-selectors-field" data-control-type="' + type + '">';
				h += '<label class="wpr-wb-control-field-label">CSS Styles</label>';
				h += '<div class="wpr-wb-selector-repeater">';
				if (conf.selectors) {
					for (var sk in conf.selectors) {
						// Split merged declarations back into separate rows
						var fullDecl = conf.selectors[sk];
						var parts = fullDecl.split(';').map(function (p) { return p.trim(); }).filter(Boolean);
						if (parts.length > 1) {
							for (var pi = 0; pi < parts.length; pi++) {
								h += buildSelectorRow(sk, parts[pi], type);
							}
						} else {
							h += buildSelectorRow(sk, fullDecl.replace(/;?\s*$/, ''), type);
						}
					}
				}
				h += '</div>';
				h += '<button type="button" class="wpr-wb-selector-add-btn"><i class="eicon-plus"></i> Add Selector</button>';
				var selectorHint = selectorPlaceholders[type] || 'color: {{VALUE}}';
				h += '<span class="wpr-wb-control-field-hint">e.g. ' + escAttr(selectorHint) + '</span>';
				h += '</div>';
				h += '<hr class="wpr-wb-control-separator">';
			}
		}

		// Condition (all controls)
		var condKey = (conf.condition && conf.condition.key) ? conf.condition.key : '';
		var condVal = (conf.condition && conf.condition.value) ? conf.condition.value : '';
		h += '<div class="wpr-wb-control-field wpr-wb-condition-field">';
		h += '<label class="wpr-wb-control-field-label">Condition</label>';
		h += '<div class="wpr-wb-condition-row">';
		h += '<input type="text" class="wpr-wb-control-field-input" data-field="condition_key" value="' + escAttr(condKey) + '" placeholder="control_key" style="width:48%">';
		h += '<span class="wpr-wb-condition-eq">=</span>';
		h += '<input type="text" class="wpr-wb-control-field-input" data-field="condition_value" value="' + escAttr(condVal) + '" placeholder="value" style="width:48%">';
		h += '</div>';
		h += '<span class="wpr-wb-control-field-hint">Show this control only when another control equals a value</span>';
		h += '</div>';

		// Separator (all controls)
		h += '<div class="wpr-wb-control-field">';
		h += '<label class="wpr-wb-control-field-label">Separator</label>';
		h += '<select class="wpr-wb-control-field-input" data-field="separator">';
		h += '<option value=""' + (!conf.separator ? ' selected' : '') + '>None</option>';
		h += '<option value="before"' + (conf.separator === 'before' ? ' selected' : '') + '>Before</option>';
		h += '<option value="after"' + (conf.separator === 'after' ? ' selected' : '') + '>After</option>';
		h += '</select>';
		h += '</div>';

		return h;
	}

	/**
	 * Collect data from a single control element
	 */
	function collectControlData($control) {
		var data = {
			type: $control.data('type'),
			key: $control.find('[data-field="key"]').val(),
			label: $control.find('[data-field="label"]').val(),
		};

		var isGroup = groupControlTypes.indexOf(data.type) !== -1;

		if (isGroup) {
			data.selector = $control.find('[data-field="selector"]').val() || '';
		} else {
			data.default = $control.find('[data-field="default"]').val() || '';

			// Collect options from repeater
			var $repeater = $control.find('.wpr-wb-options-repeater');
			if ($repeater.length) {
				var opts = {};
				var isChooseType = ($repeater.data('type') === 'choose');
				$repeater.find('.wpr-wb-option-row').each(function () {
					var optKey = $(this).find('.wpr-wb-option-key').val().trim();
					var optTitle = $(this).find('.wpr-wb-option-title').val().trim();
					if (!optKey) return;
					if (isChooseType) {
						var optIcon = $(this).find('.wpr-wb-option-icon').val() || '';
						opts[optKey] = { title: optTitle, icon: optIcon };
					} else {
						opts[optKey] = optTitle;
					}
				});
				if (Object.keys(opts).length > 0) {
					data.options = opts;
				}
			}

			// Parse selectors from repeater rows
			var $selectorsField = $control.find('.wpr-wb-selectors-field');
			if ($selectorsField.length) {
				var sels = {};
				$selectorsField.find('.wpr-wb-selector-row').each(function () {
					var sel = $(this).find('.wpr-wb-selector-row-sel').val().trim();
					var decl = $(this).find('.wpr-wb-selector-row-decl').val().trim();
					if (sel && decl) {
						// Ensure declaration ends with semicolon for merging
						var declClean = decl.replace(/;?\s*$/, '');
						if (sels[sel]) {
							sels[sel] = sels[sel].replace(/;?\s*$/, '') + '; ' + declClean + ';';
						} else {
							sels[sel] = declClean + ';';
						}
					}
				});
				if (Object.keys(sels).length > 0) {
					data.selectors = sels;
				}
			}
		}

		// Condition
		var condKey = $control.find('[data-field="condition_key"]').val();
		var condVal = $control.find('[data-field="condition_value"]').val();
		if (condKey) {
			data.condition = { key: condKey, value: condVal || '' };
		}

		// Slider min/max
		if (data.type === 'slider') {
			var sMin = $control.find('[data-field="slider_min"]').val();
			var sMax = $control.find('[data-field="slider_max"]').val();
			var sStep = $control.find('[data-field="slider_step"]').val();
			if (sMin !== '' && sMin !== undefined) data.slider_min = sMin;
			if (sMax !== '' && sMax !== undefined) data.slider_max = sMax;
			if (sStep !== '' && sStep !== undefined) data.slider_step = sStep;
		}

		var sep = $control.find('[data-field="separator"]').val();
		if (sep) data.separator = sep;

		return data;
	}

	/**
	 * Parse options textarea value into object
	 */
	function parseOptions(str, type) {
		var opts = {};
		var lines = str.trim().split('\n');
		for (var i = 0; i < lines.length; i++) {
			var parts = lines[i].split('|');
			if (parts.length < 2) continue;
			var key = parts[0].trim();
			if (type === 'choose' && parts.length >= 3) {
				opts[key] = { title: parts[1].trim(), icon: parts[2].trim() };
			} else {
				opts[key] = parts[1].trim();
			}
		}
		return opts;
	}

	/**
	 * Parse selectors textarea value into object
	 */
	function parseSelectors(str) {
		var sels = {};
		var lines = str.trim().split('\n');
		for (var i = 0; i < lines.length; i++) {
			var parts = lines[i].split('||');
			if (parts.length < 2) continue;
			sels[parts[0].trim()] = parts[1].trim();
		}
		return sels;
	}

	/**
	 * Init sortable on a controls list
	 */
	function initControlSortable($controls) {
		if ($controls.data('ui-sortable')) {
			$controls.sortable('refresh');
			return;
		}
		$controls.sortable({
			handle: '.wpr-wb-control-drag',
			placeholder: 'wpr-wb-control-placeholder',
			connectWith: '.wpr-wb-section-controls',
			tolerance: 'pointer',
			cursor: 'grabbing',
			forcePlaceholderSize: true,
		});
	}

	function initControlEvents() {
		var $body = $('.wpr-wb-center-body');

		// Open control picker (overrides the placeholder from Chapter 3)
		$body.off('click', '.wpr-wb-add-control-btn');
		$body.on('click', '.wpr-wb-add-control-btn', function () {
			var $section = $(this).closest('.wpr-wb-section-item');
			openControlModal($section);
		});

		// Toggle collapse — clicking header or toggle button
		$body.on('click', '.wpr-wb-control-header', function (e) {
			// Don't toggle if clicking drag handle, duplicate, or delete buttons
			if ($(e.target).closest('.wpr-wb-control-drag, .wpr-wb-control-duplicate, .wpr-wb-control-remove').length) {
				return;
			}
			e.stopPropagation();
			var $item = $(this).closest('.wpr-wb-control-item');
			var isCollapsed = $item.hasClass('collapsed');

			// Collapse all other controls in the same section
			$item.closest('.wpr-wb-section-controls').find('.wpr-wb-control-item').not($item).addClass('collapsed');

			$item.toggleClass('collapsed');

			// Init sortable on option repeaters and selector repeaters when expanding
			if (!$item.hasClass('collapsed')) {
				$item.find('.wpr-wb-options-repeater').each(function () {
					if (!$(this).data('ui-sortable')) {
						$(this).sortable({
							handle: '.wpr-wb-option-drag',
							placeholder: 'wpr-wb-option-placeholder',
							tolerance: 'pointer',
							axis: 'y',
							cursor: 'grabbing',
						});
					}
				});
				$item.find('.wpr-wb-selector-repeater').each(function () {
					if (!$(this).data('ui-sortable')) {
						$(this).sortable({
							handle: '.wpr-wb-selector-row-drag',
							placeholder: 'wpr-wb-selector-row-placeholder',
							tolerance: 'pointer',
							axis: 'y',
							cursor: 'grabbing',
						});
					}
				});
			}
		});

		// Delete
		$body.on('click', '.wpr-wb-control-remove', function (e) {
			e.stopPropagation();
			var $ctrl = $(this).closest('.wpr-wb-control-item');
			$ctrl.slideUp(150, function () { $ctrl.remove(); });
		});

		// Duplicate
		$body.on('click', '.wpr-wb-control-duplicate', function (e) {
			e.stopPropagation();
			var $ctrl = $(this).closest('.wpr-wb-control-item');
			var $section = $ctrl.closest('.wpr-wb-section-item');
			var data = collectControlData($ctrl);
			controlCounter++;
			data.key = data.type + '_' + controlCounter;
			addControl($section, data);
		});

		// Sync label display when label field changes
		$body.on('input', '.wpr-wb-control-body [data-field="label"]', function () {
			var $item = $(this).closest('.wpr-wb-control-item');
			$item.find('.wpr-wb-control-label-text').text($(this).val());
		});

		// Sync key display when key field changes
		$body.on('input', '.wpr-wb-control-body [data-field="key"]', function () {
			var $item = $(this).closest('.wpr-wb-control-item');
			$item.find('.wpr-wb-control-key-text').text($(this).val());
			// Update template hint
			var ctrlType = $item.data('type');
			var k = $(this).val();
			var hint = (ctrlType === 'icons') ? '{{icon(' + k + ')}}' : (ctrlType === 'media') ? '{{' + k + '.url}}' : '{{' + k + '}}';
			$item.find('.wpr-wb-control-field-hint').first().text('Use in template: ' + hint);
		});

		// Copy key as template tag
		$body.on('click', '.wpr-wb-key-copy-btn', function (e) {
			e.stopPropagation();
			e.preventDefault();
			var $item = $(this).closest('.wpr-wb-control-item');
			var $input = $(this).closest('.wpr-wb-key-input-wrap').find('[data-field="key"]');
			var keyVal = $input.val();
			var ctrlType = $item.data('type');
			if (ctrlType === 'icons') {
				var tag = '{{icon(' + keyVal + ')}}';
			} else if (ctrlType === 'media') {
				var tag = '{{' + keyVal + '.url}}';
			} else {
				var tag = '{{' + keyVal + '}}';
			}

			// Use temporary textarea for reliable copy
			var $temp = $('<textarea>');
			$temp.val(tag).css({ position: 'fixed', left: '-9999px' }).appendTo('body');
			$temp[0].select();
			document.execCommand('copy');
			$temp.remove();
			showToast('Copied ' + tag, 'success');
		});

		// Options repeater: Add option
		$body.on('click', '.wpr-wb-option-add-btn', function () {
			var $repeater = $(this).siblings('.wpr-wb-options-repeater');
			var isChoose = ($repeater.data('type') === 'choose');
			var newRow = buildOptionRow('', '', isChoose ? '' : null);
			$repeater.append(newRow);

			// Init sortable on the repeater if not already
			if (!$repeater.data('ui-sortable')) {
				$repeater.sortable({
					handle: '.wpr-wb-option-drag',
					placeholder: 'wpr-wb-option-placeholder',
					tolerance: 'pointer',
					axis: 'y',
					cursor: 'grabbing',
				});
			} else {
				$repeater.sortable('refresh');
			}
		});

		// Options repeater: Remove option
		$body.on('click', '.wpr-wb-option-remove', function () {
			$(this).closest('.wpr-wb-option-row').remove();
		});

		// Options repeater: Icon picker for choose options
		$body.on('click', '.wpr-wb-option-icon-btn', function () {
			var $btn = $(this);
			openOptionIconPicker($btn);
		});

		// CSS Selector repeater: Add row
		$body.on('click', '.wpr-wb-selector-add-btn', function () {
			var $repeater = $(this).siblings('.wpr-wb-selector-repeater');
			var controlType = $(this).closest('.wpr-wb-selectors-field').data('control-type') || 'color';
			$repeater.append(buildSelectorRow('', '', controlType));
			if (!$repeater.data('ui-sortable')) {
				$repeater.sortable({
					handle: '.wpr-wb-selector-row-drag',
					placeholder: 'wpr-wb-selector-row-placeholder',
					tolerance: 'pointer',
					axis: 'y',
					cursor: 'grabbing',
				});
			} else {
				$repeater.sortable('refresh');
			}
		});

		// CSS Selector repeater: Remove row
		$body.on('click', '.wpr-wb-selector-row-remove', function () {
			$(this).closest('.wpr-wb-selector-row').remove();
		});

	}

	/* ------------------------------------------------------------------
	 * Option Icon Picker (reuses icon list from main icon picker)
	 * ----------------------------------------------------------------*/
	var pendingOptionIconBtn = null;

	function openOptionIconPicker($btn) {
		pendingOptionIconBtn = $btn;
		var $modal = $('#wpr-wb-icon-modal');
		$modal.addClass('open').attr('data-mode', 'option-icon');
		$('#wpr-wb-icon-search').val('').focus();

		if (allIcons.length === 0) {
			loadIconList();
		} else {
			renderIcons(allIcons);
		}
	}

	/* ------------------------------------------------------------------
	 * Icon Picker
	 * ----------------------------------------------------------------*/
	var allIcons = [];    // cached list of eicon-* class names

	function initIconPicker() {
		// Open modal
		$('#wpr-wb-change-icon').on('click', function () {
			openIconModal();
		});

		// Close modal
		$('#wpr-wb-icon-modal-close').on('click', closeIconModal);
		$('#wpr-wb-icon-modal').on('click', function (e) {
			if (e.target === this) closeIconModal();
		});
		$(document).on('keydown', function (e) {
			if (e.key === 'Escape' && $('#wpr-wb-icon-modal').hasClass('open')) {
				closeIconModal();
			}
		});

		// Search
		$('#wpr-wb-icon-search').on('input', function () {
			var query = $(this).val().toLowerCase();
			filterIcons(query);
		});

		// Select icon (delegated)
		$('#wpr-wb-icon-grid').on('click', '.wpr-wb-icon-grid-item', function () {
			var iconClass = $(this).data('icon');
			var mode = $('#wpr-wb-icon-modal').attr('data-mode');

			if (mode === 'option-icon' && pendingOptionIconBtn) {
				// Update the option row icon
				pendingOptionIconBtn.find('i').attr('class', iconClass);
				pendingOptionIconBtn.siblings('.wpr-wb-option-icon').val(iconClass);
				pendingOptionIconBtn = null;
			} else {
				// Widget icon picker
				$('#wpr-wb-icon').val(iconClass);
				$('#wpr-wb-icon-preview-i').attr('class', iconClass);
			}

			$('.wpr-wb-icon-grid-item').removeClass('selected');
			$(this).addClass('selected');
			closeIconModal();
		});
	}

	function openIconModal() {
		var $modal = $('#wpr-wb-icon-modal');
		$modal.addClass('open').attr('data-mode', 'widget-icon');
		$('#wpr-wb-icon-search').val('').focus();

		if (allIcons.length === 0) {
			loadIconList();
		} else {
			renderIcons(allIcons);
		}
	}

	function closeIconModal() {
		$('#wpr-wb-icon-modal').removeClass('open').removeAttr('data-mode');
		pendingOptionIconBtn = null;
	}

	function loadIconList() {
		var $grid = $('#wpr-wb-icon-grid');
		$grid.html('<div class="wpr-wb-icon-grid-empty">Loading icons...</div>');

		$.getJSON(wprWidgetBuilder.eiconsUrl, function (data) {
			// Filter out advanced/pro icons that don't render in free version
			var excludePrefixes = [
				'eicon-ehp-', 'eicon-e-', 'eicon-atomic', 'eicon-library-',
				'eicon-kit-', 'eicon-upgrade', 'eicon-notification',
				'eicon-light-mode', 'eicon-dark-mode', 'eicon-off-canvas',
				'eicon-speakerphone', 'eicon-div-block', 'eicon-flexbox',
				'eicon-taxonomy-filter', 'eicon-tab-content', 'eicon-tab-menu',
				'eicon-elementor-circle', 'eicon-elementor',
				'eicon-editor-underline', 'eicon-contact', 'eicon-layout',
				'eicon-components', 'eicon-accessibility', 'eicon-lock-outline',
				'eicon-advanced'
			];
			allIcons = Object.keys(data).map(function (key) {
				return 'eicon-' + key;
			}).filter(function (icon) {
				for (var i = 0; i < excludePrefixes.length; i++) {
					if (icon === excludePrefixes[i] || icon.indexOf(excludePrefixes[i]) === 0) {
						return false;
					}
				}
				return true;
			});
			renderIcons(allIcons);
		}).fail(function () {
			// Fallback: use a small built-in list of common icons
			allIcons = [
				'eicon-cog','eicon-code','eicon-text','eicon-image','eicon-video-camera',
				'eicon-button','eicon-heading','eicon-divider','eicon-spacer','eicon-icon-box',
				'eicon-tabs','eicon-accordion','eicon-toggle','eicon-star','eicon-counter',
				'eicon-slider-push','eicon-carousel','eicon-gallery-grid','eicon-posts-grid',
				'eicon-form-horizontal','eicon-table','eicon-countdown','eicon-price-table',
				'eicon-social-icons','eicon-google-maps','eicon-menu-bar','eicon-search',
				'eicon-person','eicon-mail','eicon-cart','eicon-heart','eicon-play',
				'eicon-share','eicon-download-button','eicon-alert','eicon-info-circle'
			];
			renderIcons(allIcons);
		});
	}

	function renderIcons(icons) {
		var $grid = $('#wpr-wb-icon-grid');
		var currentIcon = $('#wpr-wb-icon').val();
		var html = '';

		if (icons.length === 0) {
			html = '<div class="wpr-wb-icon-grid-empty">No icons found.</div>';
		} else {
			for (var i = 0; i < icons.length; i++) {
				var sel = icons[i] === currentIcon ? ' selected' : '';
				html += '<div class="wpr-wb-icon-grid-item' + sel + '" data-icon="' + icons[i] + '" title="' + icons[i] + '">';
				html += '<i class="' + icons[i] + '"></i>';
				html += '</div>';
			}
		}

		$grid.html(html);
	}

	function filterIcons(query) {
		if (!query) {
			renderIcons(allIcons);
			return;
		}
		var filtered = allIcons.filter(function (icon) {
			return icon.indexOf(query) !== -1;
		});
		renderIcons(filtered);
	}


	/* ------------------------------------------------------------------
	 * Initialise everything on DOM ready
	 * ----------------------------------------------------------------*/
	$(function () {
		initPanelCollapse();
		initCenterTabs();
		initCodeTabs();
		initFooterButtons();
		initTitleSync();
		initSections();
		initControlPicker();
		initControlEvents();
		initIconPicker();
		initKeyboardShortcuts();
		initBeforeUnload();
		initMonaco();
	});

})(jQuery);
