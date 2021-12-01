jQuery(document).ready(function( $ ) {
	"use strict";

	var WprTemplatesKit = {

		requiredPlugins: false,

		init: function() {

			$('.wpr-templates-kit-grid').find('.image-overlay').on('click', function(){
				WprTemplatesKit.showImportPage( $(this).closest('.grid-item') );
				WprTemplatesKit.renderImportPage( $(this).closest('.grid-item') );
			});

			$('.wpr-templates-kit-logo').find('.back-btn').on('click', function(){
				WprTemplatesKit.showTemplatesMainGrid();
			});

			$('.wpr-templates-kit-single').find('.import-kit').on('click', function(){
				var confirmImport = confirm('Are you sure you want to import this Template Kit?\n\nElementor Header, Footer, Pages, Media Files, Menus and some required plugins will be installed on your website.');
				
				if ( confirmImport ) {
					WprTemplatesKit.importTemplatesKit( $(this).attr('data-kit-id') );
					$('.wpr-import-kit-popup-wrap').fadeIn();
				}
			});

			$('.wpr-import-kit-popup-wrap .close-btn').on('click', function(){
				$('.wpr-import-kit-popup-wrap').fadeOut();
			});

		},

		installRequiredPlugins: function( kitID ) {
			var kit = $('.grid-item[data-kit-id="'+ kitID +'"]');
				WprTemplatesKit.requiredPlugins = kit.data('plugins') !== undefined ? kit.data('plugins') : false;

			// Install Plugins
			if ( WprTemplatesKit.requiredPlugins ) {
				if ( 'contact-form-7' in WprTemplatesKit.requiredPlugins ) {
					WprTemplatesKit.installPluginViaAjax('contact-form-7');
				}

				if ( 'ashe-extra' in WprTemplatesKit.requiredPlugins ) {
					WprTemplatesKit.installPluginViaAjax('ashe-extra');
				}
			}

		},

		installPluginViaAjax: function( slug ) {
            wp.updates.installPlugin({
                slug: slug,
                success: function() {
			        $.post(
			            ajaxurl,
			            {
			                action: 'wpr_install_reuired_plugins',
			                plugin: slug,
			            }
			        );
			        WprTemplatesKit.requiredPlugins[slug] = true;
                },
                error: function( xhr, ajaxOptions, thrownerror ) {
                    console.log(xhr.errorCode)
                    if ( 'folder_exists' === xhr.errorCode ) {
				        $.post(
				            ajaxurl,
				            {
				                action: 'wpr_install_reuired_plugins',
				                plugin: slug,
				            }
				        );
				        WprTemplatesKit.requiredPlugins[slug] = true;
                    }
                },
            });
		},

		importTemplatesKit: function( kitID ) {
			console.log('Installing Plugins...');
			WprTemplatesKit.importProgressBar('plugins');
			WprTemplatesKit.installRequiredPlugins( kitID );

	        var installPlugins = setInterval(function() {

	        	if ( Object.values(WprTemplatesKit.requiredPlugins).every(Boolean) ) {
					console.log('Importing Kit: '+ kitID +'...');
					WprTemplatesKit.importProgressBar('content');

					// Import Kit
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'wpr_import_templates_kit',
							wpr_templates_kit: kitID,
							wpr_templates_kit_single: false
						},
						success: function( response ) {
							console.log('Fixing Elementor Images...');
							WprTemplatesKit.importProgressBar('elementor');

							// Fix Elementor Images
							$.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {
									action: 'wpr_fix_elementor_images'
								},
								success: function( response ) {
									setTimeout(function(){
										console.log('Import Finished!');
										WprTemplatesKit.importProgressBar('finish');
									}, 1000 );
								}
							});
						}
					});

	        		// Clear
	        		clearInterval( installPlugins );
	        	}
	        }, 1000);
		},

		importProgressBar: function( step ) {
			if ( 'plugins' === step ) {
				$('.wpr-import-kit-popup .progress-wrap strong').text('Step 1: Installing/Activating Plugins...');
			} else if ( 'content' === step ) {
				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '33%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').text('Step 2: Importing Demo Content...');
			} else if ( 'elementor' === step ) {
				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '66%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').text('Step 3: Importing Settings...');
			} else if ( 'finish' === step ) {
				var href = window.location.href,
					index = href.indexOf('/wp-admin'),
					homeUrl = href.substring(0, index);

				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '100%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').html('Step 4: Import Finished - <a href="'+ homeUrl +'" target="_blank">Visit Site</a>');
				$('.wpr-import-kit-popup header h3').text('Import was Successfull!');
				$('.wpr-import-kit-popup-wrap .close-btn').show();
			}
		},

		showTemplatesMainGrid: function() {
			$(this).hide();
			$('.wpr-templates-kit-single').hide();
			$('.wpr-templates-kit-grid.main-grid').show();
			$('.wpr-templates-kit-filters').show();
			$('.wpr-templates-kit-logo').find('.back-btn').css('display', 'none');
		},

		showImportPage: function( kit ) {
			$('.wpr-templates-kit-grid.main-grid').hide();
			$('.wpr-templates-kit-filters').hide();
			$('.wpr-templates-kit-single .action-buttons-wrap').css('margin-left', $('#adminmenuwrap').outerWidth());
			$('.wpr-templates-kit-single').show();
			$('.wpr-templates-kit-logo').find('.back-btn').css('display', 'flex');
			$('.wpr-templates-kit-single .preview-demo').attr('href', 'https://staging-demosites.kinsta.cloud/'+ kit.data('kit-id'));
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
			var id = $('.wpr-templates-kit-grid.single-grid').find('.selected-template').data('page-id');

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