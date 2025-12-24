var SQUICK_META_BOX;
(function($) {
    "use strict";

    SQUICK_META_BOX = {
        init: function() {
            var $firstMeta = $('.squick-sections','.postbox').first().closest('.postbox');
            $('.squick-sections','.postbox').each(function (index,el) {
                if ((index > 0)) {
                    var $wrap = $(el).closest('.postbox');
                    $(el).find('li').removeClass('active').appendTo($firstMeta.find('.squick-sections ul'));
                    $wrap.find('.squick-meta-box-fields > div').hide().appendTo($firstMeta.find('.squick-meta-box-fields'));
                    $wrap.hide();
                }
            });


        }
    };
    $(document).on('squick_before_init_fields',function() {
        SQUICK_META_BOX.init();
    });
})(jQuery);