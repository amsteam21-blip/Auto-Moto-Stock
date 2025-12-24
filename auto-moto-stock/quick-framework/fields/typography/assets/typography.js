/**
 * font field script
 *
 * @package field
 * @version 1.0
 * @author  stocktheme
 */

/**
 * Define class field
 */
var SQUICK_TypographyClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	SQUICK_TypographyClass.prototype = {
		init: function() {
			this.fontSizeChange();
			this.fontFamilyChange();
			this.variantChange();
			this.colorField();
		},
		fontSizeChange: function () {
			var that = this;
			this.$container.find('.squick-typography-size-value,.squick-typography-size-unit').on('change', function () {
				var $sizeValue = that.$container.find('.squick-typography-size-value'),
					$sizeUnit = that.$container.find('.squick-typography-size-unit'),
					$fontSize = that.$container.find('.squick-typography-size');
				if ($sizeValue.val() != '') {
					$fontSize.val($sizeValue.val() + '' + $sizeUnit.val());
				}

				that.changeField();
			});
		},
		fontFamilyChange: function () {
			var that = this;
			this.$container.find('.squick-typography-font-family').on('change', function () {
				that.bindFontVariants();
				that.$container.find('.squick-typography-variants').trigger('change');
			});
		},
		variantChange: function () {
			var that = this;
			this.$container.find('.squick-typography-variants').on('change', function () {
				var variant = $(this).val();
				if (variant.indexOf('italic') != -1) {
					that.$container.find('.squick-typography-style').val('italic');
				}
				else {
					that.$container.find('.squick-typography-style').val('');
				}
				variant = variant.replace('italic', '');
				that.$container.find('.squick-typography-weight').val(variant);
				that.changeField();
			});
		},
		bindFontVariants: function () {
			var $this = this.$container.find('.squick-typography-font-family'),
				$variants = this.$container.find('.squick-typography-variants'),
				familyName = $this.val(),
				font = {},
				i,
				fontVar = $variants.val();

			for (i in SQUICK_TYPOGRAPHY_META_DATA.activeFonts) {
				if (SQUICK_TYPOGRAPHY_META_DATA.activeFonts[i].family == familyName) {
					font = SQUICK_TYPOGRAPHY_META_DATA.activeFonts[i];
					break;
				}
			}
			var html = '';
			var isVarSelected = false;

			if (font.variants != null) {
				for (i in font.variants) {
					if (font.variants[i].toLowerCase() === 'regular') {
						font.variants[i] = '400';
					}
					if (fontVar === font.variants[i]) {
						html += '<option value="' + font.variants[i] + '" selected="selected">' + font.variants[i] + '</option>';
						isVarSelected = true;
					}
					else {
						html += '<option value="' + font.variants[i] + '">' + font.variants[i] + '</option>';
					}
				}
			}

			$variants.html(html);

			if (!isVarSelected) {
				$variants.prepend('<option value="' + fontVar + '" selected="selected">' + fontVar + ' (Missing Variant)' + '</option>')
			}
		},

		changeField: function () {
			this.$container.find('.squick-typography-font-family').trigger('squick_field_control_changed');
		},
		getValue: function() {
			var val = {};
			this.$container.find('[data-field-control]').each(function () {
				var $this = $(this),
					name = $this.attr('name'),
					property = name.replace(/^(.*)(\[)([^\]]*)(\])*$/g,function(m,p1,p2,p3,p4) {return p3;});
				val[property] = $this.val();
			});
			return val;
		},
		colorField: function () {
            var self = this;
            var data = $.extend(
                {
                    change: function () {
                        setTimeout(function() {
                            self.changeField();
                        }, 50);
                    },
                    clear: function () {
                        setTimeout(function() {
                            self.changeField();
                        }, 50);
                    }
                }
            );
            if ($().wpColorPicker) {
	            this.$container.find('.squick-typography-color').wpColorPicker(data);
            }

        }
	};
})(jQuery);