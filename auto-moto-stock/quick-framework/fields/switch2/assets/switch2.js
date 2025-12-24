/**
 * radio field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_Switch2Class = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_Switch2Class.prototype = {
		init: function() {
			var self = this;
			this.$container.find('input[type="checkbox"]').on('change', function () {
				var $switch_button = self.$container.find('.squick-field-switch-button'),
					value = '';
				if ($(this).prop('checked')) {
					value = $switch_button.data('switch-on');
				}
				else {
					value = $switch_button.data('switch-off');
				}
				self.$container.find('[data-field-control]').val(value);
			});
		},
		getValue: function() {
			return  this.$container.find('[data-field-control]');
		}
	};

})(jQuery);