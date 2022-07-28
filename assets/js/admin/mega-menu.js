jQuery(document).ready(function( $ ) {
	"use strict";
console.log(WprMegaMenuSettingsData)
    var WprMegaMenuSettings = {

        init: function() {
            WprMegaMenuSettings.initSettingsButtons();
        },

        initSettingsButtons: function() {
            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $(this),
                    id = WprMegaMenuSettings.getNavItemId($this),
                    depth = WprMegaMenuSettings.getNavItemDepth($this);
                    
                // Settings Button
                $this.append('<div class="wpr-mm-settings-btn" data-id="'+ id +'" data-depth="'+ depth +'"><span>R</span>Mega Menu</div>');

                // Active Label
            });
            
            // Open Popup
            $('.wpr-mm-settings-btn').on( 'click', WprMegaMenuSettings.openSettingsPopup );
        },

        openSettingsPopup: function() {
            // Show Popup
            $('.wpr-mm-settings-popup-wrap').fadeIn();

            // Edit Menu Button
            WprMegaMenuSettings.initEditMenuButton( $(this) );

            // Menu Width
            WprMegaMenuSettings.initMenuWidthToggle();

            // Color Pickers
            WprMegaMenuSettings.initColorPickers();

            // Icon Picker
            WprMegaMenuSettings.initIconPicker();

            // Save Settings
            WprMegaMenuSettings.saveSettings( $(this) );
            
            // Close Popup
            WprMegaMenuSettings.closeSettingsPopup();
        },

        closeSettingsPopup: function() {
            $('.wpr-mm-settings-close-popup-btn').on('click', function() {
                $('.wpr-mm-settings-popup-wrap').fadeOut();
            });


            $('.wpr-mm-settings-popup-wrap').on('click', function(e) {
                if(e.target !== e.currentTarget) return;
                $(this).fadeOut();
            });
        },

        initEditMenuButton: function( selector ) {
            $('.wpr-edit-mega-menu-btn').on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth');

                WprMegaMenuSettings.createOrEditMenuTemplate(id, depth);
            });
        },

		createOrEditMenuTemplate: function(id, depth) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_create_mega_menu_template',
                    item_id: id,
                    item_depth: depth
				},
				success: function( response ) {
					console.log(response.data['edit_link']);
                    WprMegaMenuSettings.openTemplateEditorPopup(response.data['edit_link']);
				}
			});
		},

        openTemplateEditorPopup: function( editorLink ) {
            $('.wpr-mm-editor-popup-wrap').fadeIn();
            $('.wpr-mm-editor-popup-iframe').append('<iframe src="'+ editorLink +'" width="100%" height="100%"></iframe>');
        },

        initColorPickers: function() {
            $('.wpr-mm-setting-color').find('input').wpColorPicker();

            // Fix Color Picker
            if ( $('.wpr-mm-setting-color').length ) {
                $('.wpr-mm-setting-color').find('.wp-color-result-text').text('Select Color');
                $('.wpr-mm-setting-color').find('.wp-picker-clear').val('Clear');
            }
        },

        initIconPicker: function() {
            $('#wpr_mm_icon_picker').iconpicker();

            // Bind iconpicker events to the element
            $('#wpr_mm_icon_picker').on('iconpickerSelected', function(event) {
                $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                $('.wpr-mm-setting-icon div span:last-child').addClass('wpr-mm-active-icon');
                $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                $('.wpr-mm-setting-icon div span:last-child i').addClass(event.iconpickerValue);
            });

            // Bind iconpicker events to the element
            $('#wpr_mm_icon_picker').on('iconpickerHide', function(event){
                setTimeout(function() {
                    if ( 'wpr-mm-active-icon' == $('.wpr-mm-setting-icon div span:first-child').attr('class') ) {
                        $('#wpr_mm_icon_picker').val('')
                    }

                    $('.wpr-mm-settings-wrap').removeAttr('style');
                },100);
            });

            $('.wpr-mm-setting-icon div span:first-child').on('click', function() {
                $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                $(this).addClass('wpr-mm-active-icon');
            });

            $('.wpr-mm-setting-icon div span:last-child').on('click', function() {
                $('#wpr_mm_icon_picker').focus();
                $('.wpr-mm-settings-wrap').css('overflow', 'hidden');
            });
        },

        saveSettings: function( selector ) {
            var $saveButton = $('.wpr-save-mega-menu-btn');

            $saveButton.on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth'),
                    settings = WprMegaMenuSettings.getSettings();

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'wpr_save_mega_menu_settings',
                        item_id: id,
                        item_depth: depth,
                        item_settings: settings
                    },
                    success: function( response ) {
                        $saveButton.text('Saved');
                        $saveButton.append('<span class="dashicons dashicons-yes"></span>');
                        console.log('Settings Saved!');

                        setTimeout(function() {
                            $('.wpr-mm-settings-popup-wrap').fadeOut();
                        }, 1000);
                    }
                });
            });
        },

        getSettings: function() {
            var settings = {};

            $('.wpr-mm-setting').each(function() {
                var $this = $(this),
                    checkbox = $this.find('input[type="checkbox"]'),
                    select = $this.find('select'),
                    number = $this.find('input[type="number"]'),
                    text = $this.find('input[type="text"]');

                // Checkbox
                if ( checkbox.length ) {
                    let id = checkbox.attr('id');
                    settings[id] = checkbox.prop('checked') ? 'true' : 'false';
                }

                // Select
                if ( select.length ) {
                    let id = select.attr('id');
                    settings[id] = select.val();
                }
                
                // Multi Value
                if ( $this.hasClass('wpr-mm-setting-radius') ) {
                    let multiValue = [],
                        id = $this.find('input').attr('id');

                    $this.find('input').each(function() {
                        multiValue.push($(this).val());
                    });

                    settings[id] = multiValue;
                
                // Number
                } else {
                    if ( number.length ) {
                        let id = number.attr('id');
                        settings[id] = number.val();
                    }
                }

                // Text
                if ( text.length ) {
                    let id = text.attr('id');
                    settings[id] = text.val();
                }

                
            });

            return settings;
        },

		getNavItemId: function( item ) {
			var id = item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},

		getNavItemDepth: function( item ) {
			var depthClass = item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			} else {
                return depthClass[0].replace( 'menu-item-depth-', '' );
            }
		},

        initMenuWidthToggle: function() {
            var select = $('#wpr_mm_width'),
                option = $('#wpr_mm_custom_width').closest('.wpr-mm-setting');
            
            if ( 'custom' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'custom' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        }

    }

    // Init
    WprMegaMenuSettings.init();

});