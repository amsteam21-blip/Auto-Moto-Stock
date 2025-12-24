/**
 * select popup field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_Select_popupClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_Select_popupClass.prototype = {
		_$popup: [],

		init: function() {
			var that = this,
				template = wp.template('squick-select-popup');

			that.$container.find('.squick-field-select_popup-info > .info-select').on('click', function () {
				$('#squick-popup-select-wrapper').remove();

				var items = $(this).data('items');
				if (!items) {
					items = 1;
				}
				var html = template({
					options: $(this).data('options'),
					items: items,
					popup_width: $(this).data('popup-width'),
					title: $(this).data('title')
				});

				var $popup = $(html),
					$popupListing = $popup.find('.squick-popup-select-listing');

				$('body').append($popup);

				that._$popup = $popup;

				$popup.find('.squick-popup-select-item').on('click', function () {
					var $this = $(this),
						$img = $this.find('img');

					if (!$this.hasClass('active')) {
                        that.$container.find('input[data-field-control]').val($this.data('value'));
                        that.$container.find('.squick-field-select_popup-preview').attr('src',$img.data('thumb'));
                        that.$container.find('.squick-field-select_popup-info > .info-name').text($img.attr('alt'));
                        that.$container.find('input[data-field-control]').trigger('change');
					}

					STUtils.popup.close();
				});

				that.open(that, that.$container.find('input[data-field-control]').val());
			});
		},

		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		},
		open: function (that, currentValue) {
			STUtils.popup.show({
				type: 'target',
				target: '#squick-popup-select-target',
				callback: function ($container) {
					$container.find('.squick-popup-select-item[data-value="' + currentValue + '"]').addClass('active');
				}
			});
		}
	};
})(jQuery);