/**
 * text field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */
/**
 * Define class field
 */
var SQUICK_TextClass = function($container) {
    this.$container = $container;
};
(function($) {
    "use strict";

    /**
     * Define class field prototype
     */
    SQUICK_TextClass.prototype = {
        init: function() {
            this.unique_id();
        },
        unique_id : function() {
            var self = this;
            this.$container.find('[data-unique_id="true"]').each(function(){
                var $this = $(this),
                    prefix = $this.data('unique_id-prefix'),
                    value = self.getValue();
                if (value === '' || value === prefix) {
                    $this.val(prefix + (new Date().getTime()));
                }
            });
        },
        getValue: function() {
            return this.$container.find('[data-field-control]').val();
        }
    };

    $(document).on('squick_add_clone_field', function(event) {
        var $container = $(event.target);
        $container.find('[data-unique_id="true"]').val('');
    });


})(jQuery);