/**
 * selectize field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_SelectizeClass = function($container) {
    this.$container = $container;
};

(function($) {
    "use strict";

    /**
     * Define class field prototype
     */
    SQUICK_SelectizeClass.prototype = {
        init: function() {
            var self = this,
                $selectField = self.$container.find('.squick-selectize'),
                config = {
                    plugins: ['remove_button'],
                    onInitialize: function () {
                        self.onChange(this);
                    },
                    onChange: function () {
                        self.onChange(this);
                        self.$container.find('[data-field-control]').first().trigger('squick_field_control_changed');
                    }
                };

            if (!$selectField.attr('multiple')) {
                if ($selectField.data('allow-clear')) {
                    config.allowEmptyOption = true;
                    config.onItemAdd = function() {
                        self.addRemoveButton(this);
                    };
                    config.onItemRemove = function () {
                        self.addRemoveButton(this);
                    }
                }
            }
            if ($selectField.data('tags')) {
                config.create = true;
                config.persist = false;
            }
            if ($selectField.data('drag')) {
                config.plugins[1] = 'drag_drop';
            }
            $selectField.find('option.empty-select').remove();
            setTimeout(function () {
                var $select = $selectField.selectize(config);
                var control = $select[0].selectize;
                var val = $selectField.data('value');
                if (typeof (val) !== "undefined") {
                    control.setValue(val);
                }
                $selectField.data('field-no-change', false);
                $selectField.data('field-set-value', false);
            }, 1);

        },
        onChange: function(control) {
            var self = this,
                $selectField = self.$container.find('.squick-selectize'),
                $editField = self.$container.find('.squick-selectize-edit-link'),
                $btnCreate = self.$container.find('.squick-selectize-create-link'),
                value = $selectField.val(),
                $selectizeControl = self.$container.find('.selectize-control');
            if ($editField.length) {
                if ($selectizeControl.find('.squick-selectize-edit-link').length == 0) {
                    $editField.detach().appendTo($selectizeControl);
                }
                if (value === '' || value === 'inherit') {
                    $editField.hide();
                } else {
                    $editField.show();
                    var editLink = $editField.data('link');
                    editLink = editLink.replace('{{value}}', value);
                    $editField.attr('href',editLink);
                }
            }

            if ($btnCreate.length) {
                if ($selectizeControl.find('.squick-selectize-create-link').length == 0) {
                    $btnCreate.detach().appendTo($selectizeControl);
                }
            }
        },

        addRemoveButton: function (control) {
            if (control.getValue() != '') {
                if (control.$control.find('.selectize-remove').length == 0) {
                    control.$control.append('<span class="selectize-remove dashicons dashicons dashicons-no-alt"></span>');
                    var $this = control;
                    $('.selectize-remove', control.$control).on('click', function () {
                        $this.setValue('');
                    })
                }
            }
            else {
                $('.selectize-remove', this.$control).remove();
            }
        },
        getValue: function() {
            return this.$container.find('[data-field-control]').val();
        }
    };
})(jQuery);