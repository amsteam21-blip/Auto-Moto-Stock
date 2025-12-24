var SQUICK_TERM_META;
(function($) {
    "use strict";

    SQUICK_TERM_META = {
        init: function() {
            this.headerToggle();
        },
        headerToggle: function () {
            $(document).on('click','.squick-taxonomy-meta-header', function () {
                $(this).toggleClass('in');
                $(this).next().slideToggle();
            });
        }
    };
    $(document).ready(function() {
        SQUICK_TERM_META.init();
    });
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        try{
            var $respo = $.parseXML(xhr.responseText);
            //exit on error
            if ($($respo).find('wp_error').length) return;
            var $taxWrappe = $('.squick-term-meta-wrapper');
            if ($taxWrappe.length == 0) {
                return;
            }

            var taxonomy = $taxWrappe.data('taxonomy');
            $.ajax({
                type: "GET",
                url: SQUICK_META_DATA.ajaxUrl,
                data: {
                    action: 'squick_tax_meta_form',
                    taxonomy: taxonomy,
                    _wpnonce: $taxWrappe.data('nonce')
                },
                success : function(res) {
                    var $container = $(res);
                    SQUICK.fields.initFields($container);
                    $taxWrappe.html($container);
                    $container.find('.squick-field').trigger('squick_check_required');
                }
            });

        }catch(err) {}
    });
})(jQuery);