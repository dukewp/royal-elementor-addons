!( function( $, elementor_front ) {
    "use strict";
	var AdvAccordionContentHandler = {
		WprElementorEditor: function () {
			console.log(WprConfig.ajaxurl);
			console.log(WprAccordionTemplate.resturl);
		  elementor_front.config.environmentMode.edit &&
			(window.parent.jQuery("#elementor-editor-dark-mode-css").length > 0 &&
			  window.parent.jQuery("body").addClass("elementor-editor-dark-mode"),
			elementor_front.hooks.addAction("frontend/element_ready/global", function (elementor_front) {
			  (elementor_front.find(".widgetarea_warper_edit").on("click", function () {
                  console.log(' click event works ');
				var t = window.parent.jQuery(".widgetarea_iframe_modal"),
				  d = t.find("#widgetarea-control-iframe"),
				  i = t.find(".dialog-lightbox-loading"),
				  o = t.find(".dialog-type-lightbox"),
				  a = $(this).parent().attr("data-elementskit-widgetarea-key"),
				  r = $(this).parent().attr("data-elementskit-widgetarea-index"),
				  l =
                  WprAccordionTemplate.resturl +
					"dynamic-content/content_editor/widget/" +
					a +
					"-" +
					r;
                console.log(t);
				window.parent.jQuery("body").attr("data-elementskit-widgetarea-key", a),
				  window.parent.jQuery("body").attr("data-elementskit-widgetarea-load", "false"),
				  o.show(),
				  t.show(),
				  i.show(),
				  d.contents().find("#elementor-loading").show(),
				  d.css("z-index", "-1"),
				  d.attr("src", l),
				  d.on("load", function () {
					i.hide(),
					  d.show(),
					  d.contents().find("#elementor-loading").hide(),
					  d.css("z-index", "1");
				  });
			  }),
			  "undefined" != typeof window.parent.jQuery) &&
				window.parent
				  .jQuery(".widgetarea_iframe_modal")
				  .find(".eicon-close")
				  .on("click", function () {
					window.parent.jQuery("body").attr("data-elementskit-widgetarea-load", "true");
				  });
			}));
		}
	}

	$( window ).on( 'elementor/frontend/init', AdvAccordionContentHandler.WprElementorEditor );

}( jQuery, window.elementorFrontend ) );