/**
 * image_set field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_Image_setClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_Image_setClass.prototype = {
		init: function() {
			var self = this;

			self.allowClearChecked = false;

			self.$container.find('.squick-allow-clear').on('click mousedown', function(event) {
				var $input = $(this).closest('label').find('input[type="radio"]');

				if ($input.length > 0) {
					if (event.type == 'click') {
						setTimeout(function() {
							if (self.allowClearChecked) {
								$input[0].checked = false;
							}
						}, 10);
					}
					else {
						self.allowClearChecked = $input[0].checked;
					}
				}
			});
		},
		getValue: function() {
			var isMultiple = this.$container.find('.squick-field-image_set-inner').data('multiple'),
				val = isMultiple ? [] : '';
			if (isMultiple) {
				this.$container.find('[data-field-control]').each(function () {
					var $this = $(this);
					if ($this.prop('checked')) {
						val.push($this.val());
					}
				});
			}
			else {
				this.$container.find('[data-field-control]').each(function () {
					var $this = $(this);
					if ($this.prop('checked')) {
						val = $this.val();
					}
				});
			}
			return val;
		}
	};
})(jQuery);