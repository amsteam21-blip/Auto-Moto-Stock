var SQUICK_WIDGET;
(function($) {
	"use strict";

	SQUICK_WIDGET = {
		init: function() {
			this.widgetUpdate();
			this.saveGroupState();
			$(document).on('widget-added widget-updated', SQUICK_WIDGET.widgetUpdate);

		},
		widgetUpdate: function (event, $widget) {
			if ($widget == null) {
				return;
			}
			SQUICK.fields.initFields($widget.find('.squick-meta-box-wrap'));
			$widget.find('.squick-field').trigger('squick_check_required');
			$widget.find('.squick-field').trigger('squick_check_preset');
		},
		saveGroupState: function () {
            $( document).on( 'click', '#widgets-right .squick-field-group-title', function() {
                var $this   = $(this),
                    $_group = $this.closest('.squick-field-group'),
                    groupID = $_group.attr('id'),
                    isOpen  = !$_group.hasClass('in');

                var $form    = $this.closest('form'),
                    inoutVal = isOpen ? 'open' : 'close',
                    $input   = $('input[name="squick_group_status[' + groupID + ']"]', $form);

                if ($input.length) {
                    $input.val(inoutVal);
                } else {
                    $('<input />', {
                        type: 'hidden',
                        name: 'squick_group_status[' + groupID + ']'
                    }).val(inoutVal).appendTo($form);
                }
			});
        }
	};
	$(document).ready(function() {
		SQUICK_WIDGET.init();
	});
})(jQuery);