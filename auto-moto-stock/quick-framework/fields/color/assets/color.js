/**
 * color field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_ColorClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_ColorClass.prototype = {
		init: function() {
			var self = this;
			var data = $.extend(
				{
					change: function () {
						setTimeout(function() {
							self.changeField();
						}, 50);
					},
					clear: function () {
						setTimeout(function() {
							self.changeField();
						}, 50);
					}
				}
			);
			this.$container.find('[data-field-control]').wpColorPicker(data);
		},
		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		},
		changeField: function () {
			this.$container.find('[data-field-control]').trigger('squick_field_control_changed');
		}
	};
})(jQuery);