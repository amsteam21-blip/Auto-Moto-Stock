/**
 * checkbox field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_CheckboxClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_CheckboxClass.prototype = {
		init: function() {},
		getValue: function () {
			var $check = this.$container.find('[data-field-control]');
			if ($check.prop('checked')) {
				return $check.val();
			}
			return '0';
		}
	};
})(jQuery);