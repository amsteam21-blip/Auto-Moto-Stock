/**
 * border field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_BorderClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_BorderClass.prototype = {
		init: function() {
			var self = this,
				$colorField = self.$container.find('.squick-border-color');
			/**
			 * Init Color
			 */
			var data = $.extend(
				{
					change: function () {
						var $this = $(this);
						setTimeout(function() {
							self.changeField();
						}, 50);
					},
					clear: function () {
						setTimeout(function() {
							self.changeField();
						}, 50);
					}
				},
				$colorField.data('options')
			);
			$colorField.wpColorPicker(data);
		},
		getValue: function() {
			var val = {};
			this.$container.find('[data-field-control]').each(function () {
				var $this = $(this),
					name = $this.attr('name'),
					property = name.replace(/^(.*)(\[)([^\]]*)(\])*$/g,function(m,p1,p2,p3,p4) {return p3;});
				val[property] = $(this).val();
			});
			return val;
		},
		changeField: function () {
			this.$container.find('.squick-border-color').trigger('squick_field_control_changed');
			this.$container.find('.squick-border-color').trigger('change');
		}
	};
})(jQuery);