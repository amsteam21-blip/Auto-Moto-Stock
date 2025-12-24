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
var SQUICK_RadioClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_RadioClass.prototype = {
		init: function() {},
		getValue: function() {
			var val = '';
			this.$container.find('[data-field-control]').each(function () {
				var $this = $(this);
				if ($this.prop('checked')) {
					val = $this.val();
				}
			});
			return val;
		}
	};

})(jQuery);