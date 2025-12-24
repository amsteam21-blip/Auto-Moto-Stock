/**
 * select field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_SelectClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_SelectClass.prototype = {
		init: function() {
		},
		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		}
	};
})(jQuery);