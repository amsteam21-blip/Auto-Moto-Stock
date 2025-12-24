/**
 * file field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_FileClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_FileClass.prototype = {
		init: function() {
			this.select();
			this.remove();
			this.sortable();
		},
		select: function() {
			var self = this,
				$button = self.$container.find('.squick-file-add > button'),
				$fileInner = self.$container.find('.squick-field-file-inner'),
				library_filter = $fileInner.data('lib-filter'),
				options = {
					title: SQUICK_FILE_FIELD_META.title,
					button: SQUICK_FILE_FIELD_META.button
				},
				_media = new SQUICK_Media();
			if ((typeof (library_filter) != "undefined") && (library_filter != null) && (library_filter != '')) {
				options.filter = library_filter;
			}

			_media.selectGallery($button, options, function(attachments) {
				if (attachments.length) {
					var $this = $(_media.clickedButton),
						$input = self.$container.find('input[type="hidden"]'),
						valInput = $input.val(),
						arrInput = valInput.split('|'),
						imgHtml = '',
						removeText = $fileInner.data('remove-text');
					attachments.each(function(attachment) {
						attachment = attachment.toJSON();

						if (arrInput.indexOf('' + attachment.id) != -1) {
							return;
						}
						if (valInput != '') {
							valInput += '|' + attachment.id;
						}
						else {
							valInput = '' + attachment.id;
						}
						arrInput.push('' + attachment.id);
						imgHtml += '<div class="squick-file-item" data-file-id="' + attachment.id + '">';
						imgHtml += '<span class="dashicons dashicons-media-document"></span>';
						imgHtml +='<div class="squick-file-info">';
						imgHtml += '<a class="squick-file-title" href="' + attachment.editLink + '" target="_blank">' + attachment.title + '</a>';
						imgHtml += '<div class="squick-file-name">' + attachment.filename + '</div>';
						imgHtml += '<div class="squick-file-action">';
						imgHtml += '<span class="squick-file-remove"><span class="dashicons dashicons-no-alt"></span> ' + removeText + '</span>';
						imgHtml += '</div>';
						imgHtml += '</div>';
						imgHtml += '</div>';
					});
					$input.val(valInput);

					var $element = $(imgHtml);
					$this.parent().before($element);
					self.remove($element);
					self.changeField();
				}
			});
		},
		remove: function($item) {
			if (typeof ($item) === "undefined") {
				$item = this.$container;
			}
			var self = this;
			$item.find('.squick-file-remove').on('click', function() {
				var $this = $(this).closest('.squick-file-item');
				var $parent = $this.parent();
				var $input = $parent.find('input[type="hidden"]');
				$this.remove();
				var valInput = '';
				$('.squick-file-item', $parent).each(function() {
					if (valInput != '') {
						valInput += '|' + $(this).data('file-id');
					}
					else {
						valInput = '' + $(this).data('file-id');
					}
				});
				$input.val(valInput);
				self.changeField();

			});
		},
		sortable: function () {
			var self = this;
			this.$container.sortable({
				placeholder: "squick-file-sortable-placeholder",
				items: '.squick-file-item',
				handle: '.dashicons-media-document',
				update: function( event, ui ) {
					var $wrapper = $(event.target);
					var valInput = '';
					$('.squick-file-item', $wrapper).each(function() {
						if (valInput != '') {
							valInput += '|' + $(this).data('file-id');
						}
						else {
							valInput = '' + $(this).data('file-id');
						}
					});
					var $input = $wrapper.find('input[type="hidden"]');
					$input.val(valInput);
					self.changeField();
				}
			});
		},
		changeField: function () {
			this.$container.find('[data-field-control]').trigger('squick_field_control_changed');
			this.$container.find('[data-field-control]').trigger('change');
		},
		getValue: function() {
			var val = [];
			this.$container.find('[data-field-control]').each(function () {
				val.push($(this).val());
			});
			return val;
		}
	};
})(jQuery);