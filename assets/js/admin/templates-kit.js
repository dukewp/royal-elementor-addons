jQuery(document).ready(function( $ ) {
	"use strict";

	var WprTemplatesKit = {

		init: function() {

			$('.wpr-templates-kit-grid').find('.image-overlay').on('click', function(){
				WprTemplatesKit.showImportPage();
				WprTemplatesKit.renderImportPage( $(this).closest('.grid-item') );
			});

			$('.wpr-templates-kit-logo').find('.back-btn').on('click', function(){
				WprTemplatesKit.showTemplatesMainGrid();
			});

			$('.wpr-templates-kit-single').find('.import-kit').on('click', function(){
				WprTemplatesKit.importTemplatesKit( $(this).attr('data-kit-id') );
			});
			
		},

		importTemplatesKit: function( kit ) {
			// AJAX Data
			var data = {
				action: 'wpr_import_templates_kit',
				wpr_templates_kit: kit,
				wpr_templates_kit_single: false
			};

			console.log(kit)
			console.log('import started')

			// Update via AJAX
			$.post(ajaxurl, data, function(response) {
				console.log(response);
			});
		},

		showTemplatesMainGrid: function() {
			$(this).hide();
			$('.wpr-templates-kit-single').hide();
			$('.wpr-templates-kit-grid.main-grid').show();
			$('.wpr-templates-kit-filters').show();
		},

		showImportPage: function() {
			$('.wpr-templates-kit-grid.main-grid').hide();
			$('.wpr-templates-kit-filters').hide();
			$('.wpr-templates-kit-single .action-buttons-wrap').css('margin-left', $('#adminmenuwrap').outerWidth());
			$('.wpr-templates-kit-single').show();
			$('.wpr-templates-kit-logo').find('.back-btn').css('display', 'flex');
		},

		renderImportPage: function( kit ) {
			var kitID = kit.data('kit-id'),
				pagesAttr = kit.data('pages') !== undefined ? kit.data('pages') : false,
				pagesArray = pagesAttr ? pagesAttr.split(',') : false,
				singleGrid = $('.wpr-templates-kit-grid.single-grid');

			// Reset
			singleGrid.html('');

			// Render
			if ( pagesArray ) {
				for (var i = 0; i < pagesArray.length - 1; i++ ) {
					singleGrid.append('\
				        <div class="grid-item" data-page-id="'+ pagesArray[i] +'">\
				            <div class="image-wrap">\
				                <img src="http://192.168.100.6/spacy/wp-content/plugins/royal-elementor-addons/assets/img/tmp/kit/'+ pagesArray[i] +'.jpg">\
				            </div>\
				            <footer><h3>'+ pagesArray[i] +'</h3></footer>\
				        </div>\
					');
				};
			} else {

			}

			// Set Kit ID
			$('.wpr-templates-kit-single').find('.import-kit').attr('data-kit-id', kit.data('kit-id'));

			// Set Active Template ID by Default
			WprTemplatesKit.setActiveTemplateID(singleGrid.children().first());

			singleGrid.find('.grid-item').on('click', function(){
				WprTemplatesKit.setActiveTemplateID( $(this) );
			});
		},

		setActiveTemplateID: function( template ) {
			// Reset
			$('.wpr-templates-kit-grid.single-grid').find('.grid-item').removeClass('selected-template');
			
			// Set
			template.addClass('selected-template');
			var id =  $('.wpr-templates-kit-grid.single-grid').find('.selected-template').data('page-id');

			$('.wpr-templates-kit-single').find('.import-template').attr('data-template-id', id);
			$('.wpr-templates-kit-single').find('.import-template strong').text(id);
		},

	}

	WprTemplatesKit.init();

	/*
	** Import Template -------------------------
	*/
	function importTemplate() {
		$( '.wpr-import' ).on( 'click', function() {
			// Buttons
			var importButton = $(this),
				editButton 	 = importButton.parent().find('.wpr-edit-template'),
				resetButton  = importButton.parent().find('.wpr-delete-template');

			$('.wrap').children('h1').text('Importing Template, Please be patient...');	
			
			// AJAX Data
			var data = {
				action: 'wpr_import_template',
				wpr_template: $(this).attr('data-slug'),
			};

			// Update via AJAX
			$.post(ajaxurl, data, function(response) {
				$('.wrap').children('h1').text('Howdy Nick! Template has been successfully imported :)');

				// Change Buttons
				importButton.removeClass('wpr-import').addClass('wpr-template-conditions').text('Activate').unbind('click');
				editButton.removeClass('hidden');
				resetButton.removeClass('hidden');

				// Open Conditions
				changeTemplateConditions();

				// Edit Template Link
				response = response.split(';');
				editButton.attr( 'href', 'post.php?post='+ response[30].replace('i:', '') +'&action=elementor' );
			});		
		});		
	}

	importTemplate();
}); // end dom ready