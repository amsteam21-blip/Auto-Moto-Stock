/**
 * background field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */

var SQUICK_BackgroundClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_BackgroundClass.prototype = {
		init: function() {
			var self = this,
				$colorField = self.$container.find('.squick-background-color'),
				$chooseImageButton = self.$container.find('.squick-background-choose-image'),
				$removeImageButton = self.$container.find('.squick-background-remove-image'),
				$urlField = self.$container.find('.squick-background-url'),
				$imageField = self.$container.find('.squick-background-image');

			/**
			 * Init Color
			 */
			var data = $.extend(
				{
					change: function () {
						var $this = $(this);
						if (typeof (this.squickIsInitColorChanged) === "undefined") {
							this.squickIsInitColorChanged = true;
						}
						else {
							setTimeout(function() {
								self.changeField();
							}, 50);
						}
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

			/**
			 * Init Media
			 */
			var _media = new SQUICK_Media();
			_media.selectImage($chooseImageButton, {filter: 'image'}, function(attachment) {
				if (attachment) {
					var $parent = $(_media.clickedButton).parent();
					$urlField.val(attachment.url);
					$imageField.val(attachment.id);

					self.changeField();
				}
			});

			/**
			 * Remove button
			 */
			$removeImageButton.on('click', function() {
				$urlField.val('');
				$imageField.val('');
				self.changePreview();
				self.changeField();
			});

			/**
			 * Image Url Change
			 */
			$urlField.on('change', function() {
				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl + '?action=squick_get_attachment_id',
					data: {
						url: $urlField.val(),
						_wpnonce: SQUICK_META_DATA.nonce
					},
					type: 'GET',
					error: function() {
						$imageField.val('0');
						self.changeField();
					},
					success: function(res) {
						$imageField.val(res);
						self.changeField();
					}
				});
			});

			/**
			 * Select Url Change
			 */
			self.$container.find('[data-field-control]').on('change', function() {
				self.changePreview();
			});
			self.changePreview();
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
		changeField: function() {
			this.$container.find('.squick-background-color').trigger('squick_field_control_changed');
			this.changePreview();
		},
		changePreview: function() {
			var self = this,
				$colorField = self.$container.find('.squick-background-color'),
				$preview = self.$container.find('.squick-background-preview '),
				bg_url = self.$container.find('.squick-background-url').val(),
				bg_repeat = self.$container.find('.squick-background-repeat').val(),
				bg_size = self.$container.find('.squick-background-size').val(),
				bg_position = self.$container.find('.squick-background-position').val(),
				bg_attachment = self.$container.find('.squick-background-attachment').val();
			$preview.css('background-color', $colorField.val());
			if (bg_url != '') {
				$preview.css('background-image', 'url(' + bg_url + ')');
				$preview.css('background-repeat', bg_repeat);
				$preview.css('background-size', bg_size);
				$preview.css('background-position', bg_position);
				$preview.css('background-attachment', bg_attachment);
			}
			else {
				$preview.css('background-image', '');
				$preview.css('background-repeat', '');
				$preview.css('background-size', '');
				$preview.css('background-position', '');
				$preview.css('background-attachment', '');
			}
		}
	};
})(jQuery);