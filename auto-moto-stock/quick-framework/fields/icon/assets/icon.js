/**
 * icon field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_IconClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_IconClass.prototype = {
		init: function() {
			var self = this,
				$iconField = this.$container.find('[data-field-control]'),
				$iconInfo = self.$container.find('.squick-field-icon-item-info');

			/**
			 * Show icon popup when click icon info
			 */
			$iconInfo.on('click', function(event) {
				if ($(event.target).closest('.squick-field-icon-remove').length > 0) {
					if ($iconField.val() !== '') {
                        $iconField.val('');
                        $iconInfo.find('> span ').html('').attr('class', '');
                        $iconField.trigger('change');
					}
				} else {
                    SQUICK_ICON_POPUP.open(self.getValue(), function(iconValue) {
                        $iconField.val(iconValue);
                        $iconInfo.find('> span ').html('').attr('class', iconValue);
                        $iconField.trigger('change');
                    });
				}
			});
		},
		getValue: function () {
			return this.$container.find('[data-field-control]').val();
		}
	};
})(jQuery);