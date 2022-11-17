jQuery(window).on("elementor:init", (function() {
    "use strict";
    
    var wprAjaxSelect2 = elementor.modules.controls.BaseData.extend({
        onReady: function() {
            var $this = this,
                $select = $this.ui.select,
                restUrl = $select.attr("data-rest-url"),
                nonce = window.wpApiSettings.nonce;
            $select.select2({
                ajax: {
                    url: restUrl,
                    dataType: "json",
                    headers: {
                        "X-WP-Nonce": nonce
                    },
                    data: function(response) {
                        return {
                            s: response.term
                        }
                    }
                },
                cache: !0
            });

            var controlValue = void 0 !== $this.getControlValue() ? $this.getControlValue() : "";
            console.log(controlValue);
            controlValue.isArray && (controlValue = $this.getControlValue().join()), jQuery.ajax({
                url: restUrl,
                dataType: "json",
                headers: {
                    "X-WP-Nonce": nonce
                },
                data: {
                    ids: String(controlValue)
                }
            }).then((function(response) {
                console.log(response);

                null !== response && response.results.length > 0 && (jQuery.each(response.results, (function(index, element) {
                    var option = new Option(element.text, element.id, !0, !0);
                    $select.append(option).trigger("change")
                })), $select.trigger({
                    type: "select2:select",
                    params: {
                        data: response
                    }
                }))
            }))
        },

        onBeforeDestroy: function() {
            this.ui.select.data("select2") && this.ui.select.select2("destroy"), this.el.remove()
        }
    });

    elementor.addControlView("wpr-ajax-select2", wprAjaxSelect2)
}));