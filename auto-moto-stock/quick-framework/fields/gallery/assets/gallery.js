/**
 * Define class field
 */
var SQUICK_GalleryClass = function($container) {
    this.$container = $container;
};
(function ($) {
    "use strict";
    /**
     * Define class field prototype
     */
    SQUICK_GalleryClass.prototype = {
        init: function() {
            this.select();
            this.remove();
            this.sortable();
            this.changeField();
        },
        select: function () {
            var _media = new SQUICK_Media(),
                $addButton = this.$container.find('.squick-gallery-add');
            _media.selectGallery($addButton, {filter: 'image'}, function(attachments) {
                if (attachments.length) {
                    var $this = $(_media.clickedButton);
                    var $parent = $this.parent();
                    var $input = $parent.find('input[type="hidden"]');
                    var valInput = $input.val();
                    var arrInput = valInput.split('|');
                    var imgHtml = '';
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

                        var url = '';
                        if (attachment.sizes.thumbnail == null) {
                            url = attachment.sizes.full.url;
                        } else {
                            url = attachment.sizes.thumbnail.url;
                        }

                        imgHtml += '<div class="squick-gallery-image-preview" data-id="' + attachment.id + '">';
                        imgHtml +='<div class="centered">';
                        imgHtml += '<img src="' + url + '" alt=""/>';
                        imgHtml += '</div>';
                        imgHtml += '<span class="squick-gallery-remove dashicons dashicons dashicons-no-alt"></span>';
                        imgHtml += '</div>';
                    });
                    $input.val(valInput);
                    $input.trigger('change');
                    $this.before(imgHtml);
                    $this.trigger('squick-gallery-selected');
                }
            });
        },
        remove: function() {
            this.$container.on('click', '.squick-gallery-remove', function() {
                var $this = $(this).parent();
                var $parent = $this.parent();
                var $input = $parent.find('input[type="hidden"]');
                $this.remove();
                var valInput = '';
                $('.squick-gallery-image-preview', $parent).each(function() {
                    if (valInput != '') {
                        valInput += '|' + $(this).data('id');
                    }
                    else {
                        valInput = '' + $(this).data('id');
                    }
                });
                $input.val(valInput);
                $input.trigger('change');
                $parent.trigger('squick-gallery-removed');
            });
        },
        sortable: function () {
            this.$container.sortable({
                placeholder: "squick-gallery-sortable-placeholder",
                items: '.squick-gallery-image-preview',
                update: function( event, ui ) {
                    var $wrapper = $(event.target);
                    var valInput = '';
                    $('.squick-gallery-image-preview', $wrapper).each(function() {
                        if (valInput != '') {
                            valInput += '|' + $(this).data('id');
                        }
                        else {
                            valInput = '' + $(this).data('id');
                        }
                    });
                    var $input = $wrapper.find('input[type="hidden"]');
                    $input.val(valInput);
                    $input.trigger('change');
                    $wrapper.trigger('squick-gallery-sortable-updated');
                }
            });
        },
        changeField: function () {
            var self = this;
            this.$container.on('squick-gallery-selected squick-gallery-removed squick-gallery-sortable-updated ',function(event){
                self.$container.find('[data-field-control]').trigger('squick_field_control_changed');
            });
        },
        getValue: function() {
            return this.$container.find('[data-field-control]').val();
        }
    };
})(jQuery);
