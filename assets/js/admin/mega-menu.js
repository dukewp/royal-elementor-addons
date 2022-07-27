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

                WprMegaMenuSettings.createMenuTemplate(id, depth);
            });
        },

		createMenuTemplate: function(id, depth) {
            console.log(id)
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_create_mega_menu_template',
                    item_id: id,
				},
				success: function( response ) {
					console.log(response);
				}
			});
		},

        initColorPickers: function() {
            $('.wpr-mm-setting-color').find('input').wpColorPicker();

            // Fix Color Picker
            if ( $('.wpr-mm-setting-color').length ) {
                $('.wpr-mm-setting-color').find('.wp-color-result-text').text('Select Color');
                $('.wpr-mm-setting-color').find('.wp-picker-clear').text('Clear');
            }
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