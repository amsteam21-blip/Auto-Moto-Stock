var SQUICK_Fonts = SQUICK_Fonts || {};
(function($) {
    "use strict";
    SQUICK_Fonts = {
        _loadFontCount: 0,
        _fonts: {},
        _isSubmitting: false,
        _icon_spin: 'squick-icon-spinner',
        _nonce: '',
        init: function () {
            this._nonce = $('.squick-fonts-wrapper').data('nonce');
            console.log(this._nonce);
            this.tabClick();
            this.binderFonts();
            this.searchFonts();
            this.events();
        },
        events : function() {
            $('.squick-reset-active-fonts').on('click',function(event){
                event.preventDefault();
                if (!confirm(SQUICK_FONT_META_DATA.msgConfirmResetFont)) {
                    return;
                }
                if (SQUICK_Fonts._isSubmitting) {
                    return;
                }
                SQUICK_Fonts._isSubmitting = true;
                var $this = $(this);
                $this.find('i').addClass(SQUICK_Fonts._icon_spin);
                $.ajax({
                    url: SQUICK_FONT_META_DATA.ajaxUrl,
                    data: {
                        action: 'squick_reset_active_font',
                        _nonce: SQUICK_Fonts._nonce
                    },
                    type: 'post',
                    success: function (res) {
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        SQUICK_Fonts._isSubmitting = false;
                        if (res.success) {
                            SQUICK_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        SQUICK_Fonts._isSubmitting = false;
                    }
                });
            });

            $(document).on('click', '.squick-font-item-change-font', function (event) {
                event.preventDefault();

                var $this = $(this),
                    fontType = $this.data('type'),
                    familyName = $this.closest('.squick-font-item,.squick-font-active-item').data('name'),
                    fontObj = SQUICK_Fonts.findFontSource(SQUICK_Fonts._fonts[fontType], familyName);

                if (fontObj == null) {
                    return;
                }

                var template = wp.template('squick-popup-change-font');

                $('body').append(template(SQUICK_Fonts._fonts['active']));

                $('.squick-popup-change-font').find('.squick-popup-close').on('click', function () {
                    $('.squick-popup-wrap').remove();
                });

                $('.squick-popup-change-font').find('.squick-change-font-item button').on('click', function () {
                    var msgConfirm = $(this).closest('.squick-popup-change-font').data('msg-confirm'),
                        fromFont = $(this).data('name');
                    msgConfirm = msgConfirm.replace('{1}', fromFont);
                    msgConfirm = msgConfirm.replace('{2}', familyName);
                    if (!confirm(msgConfirm)) {
                        return;
                    }

                    $('.squick-popup-wrap').remove();

                    if (SQUICK_Fonts._isSubmitting) {
                        return true;
                    }
                    SQUICK_Fonts._isSubmitting = true;

                    $this.find('i').addClass(SQUICK_Fonts._icon_spin);

                    $.ajax({
                        url: SQUICK_FONT_META_DATA.ajaxUrl,
                        data: {
                            action: 'squick_change_font',
                            _nonce: SQUICK_Fonts._nonce,
                            font_data: fontObj,
                            from_font: fromFont,
                            to_font: familyName
                        },
                        type: 'post',
                        success: function (res) {
                            SQUICK_Fonts._isSubmitting = false;
                            $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                            if (res.success) {
                                $this.closest('.squick-font-item')
                                    .find('.squick-font-item-action-add')
                                    .find('i').attr('class', 'dashicons dashicons-yes');
                                SQUICK_Fonts.bindActiveFont();
                            }
                            else {
                                alert(res.data);
                            }
                        },
                        error: function () {
                            SQUICK_Fonts._isSubmitting = false;
                            $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        }
                    });
                });
            });

        },
        binderFonts: function () {
            var fontTypes = ['google', 'standard', 'custom'];
            for (var i in fontTypes) {
                this.bindFonts(fontTypes[i]);
            }
            this.bindActiveFont();
        },
        getFontFamily: function (name) {
            if (name.indexOf(',') != -1) {
                return name;
            }
            if (name.indexOf(' ') != -1) {
                return "'" + name + "'";
            }
            return name;
        },
        enqueueFont: function (font) {
            var url = '';
            switch (font.kind) {
                case 'webfonts#webfont': {
                    url = 'https://fonts.googleapis.com/css?family=' + font.family.replace(' ', '+') + ':100,200,300,400,500,600,700,800,900,1000';
                    break;
                }
                case 'custom': {
                    url = typeof font.css_url !== 'undefined' ? font.css_url :   SQUICK_FONT_META_DATA.font_url + font.css_file;
                    break;
                }
            }
            if (url !== '') {
                $('body').append('<link class="squick-preview-css-font" rel="stylesheet" href="' + url +  '" type="text/css" media="all" />');
            }
        },
        bindFonts: function (fontType, isShow) {
            if (isShow == null) {
                isShow = false;
            }
            $.ajax({
                url: SQUICK_FONT_META_DATA.ajaxUrl,
                data: {
                    action: 'squick_get_font_list',
                    _nonce: SQUICK_Fonts._nonce,
                    font_type: fontType
                },
                type: 'get',
                success: function (res) {
                    if (!res.success) {
                        return;
                    }
                    SQUICK_Fonts._fonts[res.data.font_type] = res.data.fonts.items;
                    var template;
                    switch (res.data.font_type) {
                        case 'google': {
                            template = wp.template('squick-google-fonts');
                            break;
                        }
                        case 'standard': {
                            template = wp.template('squick-standard-fonts');
                            break;
                        }
                        case 'custom': {
                            template = wp.template('squick-custom-fonts');
                            break;
                        }
                    }

                    if (template) {
                        var $listing = $('.squick-font-listing-inner'),
                            $element = $(template(res.data));
                        $('#' + fontType + '_fonts').remove();
                        $listing.append($element);
                        SQUICK_Fonts.addEventListener($element);
                        $element.find('.squick-font-categories li a').first().trigger('click');
                        if (isShow) {
                            $element.show();
                        }
                    }
                    SQUICK_Fonts._loadFontCount++;
                },
                error: function () {
                    SQUICK_Fonts._loadFontCount++;
                }
            });
        },
        addEventListener: function ($container) {
            $container.find('form').ajaxForm({
                beforeSubmit: function() {
                    if (SQUICK_Fonts._isSubmitting) {
                        return false;
                    }
                    STUtils.loadingButton.show($('.squick-custom-font-popup form button'));
                    SQUICK_Fonts._isSubmitting = true;
                },
                success: function (res) {
                    STUtils.loadingButton.hide($('.squick-custom-font-popup form button'));
                    SQUICK_Fonts._isSubmitting = false;
                    if (res.success) {
                        SQUICK_Fonts.bindFonts('custom', true);
                        STUtils.popup.close();
                    }
                    else {
                        alert(res.data);
                    }
                }
            });

            $container.find('.squick-font-categories li a').on('click', function () {
                var $this = $(this),
                    cate = $this.parent().data('ref');
                $container.find('.squick-font-categories li').removeClass('active');
                $this.parent().addClass('active');
                SQUICK_Fonts.filterFontsByCate($container, cate);
                $('#search_fonts').val('');
            });

            $container.find('.squick-add-custom-font button').on('click', function () {
                STUtils.popup.show({
                    type: 'target',
                    target: '#squick-custom-font-popup',
                    callback: function ($pcontent) {
                        $pcontent.find('form')[0].reset();
                    }
                });
            });

            $container.find('.squick-font-item-action-delete').on('click', function (event) {
                event.preventDefault();
                if (!confirm(SQUICK_FONT_META_DATA.msgConfirmDeleteCustomFont)) {
                    return;
                }
                if (SQUICK_Fonts._isSubmitting) {
                    return;
                }
                SQUICK_Fonts._isSubmitting = true;
                var $this = $(this),
                    familyName = $this.closest('.squick-font-item').data('name');
                $this.find('i').addClass(SQUICK_Fonts._icon_spin);
                $.ajax({
                    url: SQUICK_FONT_META_DATA.ajaxUrl,
                    data: {
                        action: 'squick_delete_custom_font',
                        _nonce: SQUICK_Fonts._nonce,
                        family_name: familyName
                    },
                    type: 'post',
                    success: function (res) {
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        SQUICK_Fonts._isSubmitting = false;
                        if (res.success) {
                            SQUICK_Fonts.bindFonts('custom', true);
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        SQUICK_Fonts._isSubmitting = false;
                    }
                });
            });

            $container.find('.squick-font-item-action-add').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    fontType = $this.data('type'),
                    familyName = $this.closest('.squick-font-item').data('name'),
                    fontObj = SQUICK_Fonts.findFontSource(SQUICK_Fonts._fonts[fontType], familyName);

                if (fontObj == null) {
                    return;
                }
                if ($this.find('i').hasClass('dashicons-yes')) {
                    return;
                }
                if (SQUICK_Fonts._isSubmitting) {
                    return true;
                }
                SQUICK_Fonts._isSubmitting = true;

                $this.find('i').addClass(SQUICK_Fonts._icon_spin);

                $.ajax({
                    url: SQUICK_FONT_META_DATA.ajaxUrl,
                    data: {
                        action: 'squick_using_font',
                        _nonce: SQUICK_Fonts._nonce,
                        font_data: fontObj
                    },
                    type: 'post',
                    success: function (res) {
                        SQUICK_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        if (res.success) {
                            $this.find('i').attr('class', 'dashicons dashicons-yes');
                            SQUICK_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        SQUICK_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                    }
                });
            });



        },
        bindActiveFont: function () {
            var _nonce = $('.squick-fonts-wrapper').data('nonce');
            $.ajax({
                url: SQUICK_FONT_META_DATA.ajaxUrl,
                data: {
                    action: 'squick_get_font_list',
                    _nonce: SQUICK_Fonts._nonce,
                    font_type: 'active'
                },
                type: 'get',
                success: function (res) {
                    if (!res.success) {
                        return;
                    }
                    SQUICK_Fonts._fonts[res.data.font_type] = res.data.fonts.items;
                    var template = wp.template('squick-active-fonts');

                    if (template) {
                        var $listing = $('.squick-font-active-listing'),
                            $element = $(template(res.data));
                        $('#active_fonts').remove();
                        $listing.append($element);
                        SQUICK_Fonts.activeFontAddEventListener($element);
                        $('.squick-preview-css-font').remove();
                        for (var i in res.data.fonts.items) {
                            SQUICK_Fonts.enqueueFont(res.data.fonts.items[i]);
                        }
                    }
                },
                error: function () {
                }
            });
        },
        activeFontAddEventListener: function ($container) {
            $container.find('.squick-font-active-item-header').on('click', function (event) {
                if ($(event.target).closest('.squick-font-active-item-remove,.squick-font-item-change-font').length) {
                    return;
                }
                $(this).toggleClass('in');
                $(this).next('.squick-font-active-content').slideToggle();
            });

            $container.find('.squick-font-active-item-remove').on('click', function (event) {
                event.preventDefault();
                if (!confirm(SQUICK_FONT_META_DATA.msgConfirmRemoveActiveFont)) {
                    return;
                }

                var $this = $(this),
                    $item = $this.closest('.squick-font-active-item'),
                    familyName = $item.data('name');

                if (SQUICK_Fonts._isSubmitting) {
                    return true;
                }
                SQUICK_Fonts._isSubmitting = true;
                $this.find('i').addClass(SQUICK_Fonts._icon_spin);

                $.ajax({
                    url: SQUICK_FONT_META_DATA.ajaxUrl,
                    data: {
                        action: 'squick_remove_active_font',
                        _nonce: SQUICK_Fonts._nonce,
                        family_name: familyName
                    },
                    type: 'post',
                    success: function (res) {
                        SQUICK_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                        if (res.success) {
                            $('.squick-font-item[data-name="' + res.data.family + '"]').find('.squick-font-item-action-add i').attr('class', 'dashicons dashicons-plus-alt2');
                            SQUICK_Fonts.bindActiveFont();
                        }
                        else {
                            alert(res.data);
                        }
                    },
                    error: function () {
                        SQUICK_Fonts._isSubmitting = false;
                        $this.find('i').removeClass(SQUICK_Fonts._icon_spin);
                    }
                });
            });
            $container.find('form').ajaxForm({
                beforeSubmit: function() {
                    if (SQUICK_Fonts._isSubmitting) {
                        return false;
                    }
                    $container.find('form').find('button i').addClass(SQUICK_Fonts._icon_spin);
                    SQUICK_Fonts._isSubmitting = true;
                },
                success: function (res) {
                    $container.find('form').find('button i').removeClass(SQUICK_Fonts._icon_spin);
                    SQUICK_Fonts._isSubmitting = false;
                    if (!res.success) {
                        alert(res.data);
                    }
                }
            });


        },

        findFontSource: function (sources, name) {
            for (var i in sources) {
                if (sources[i].family == name) {
                    return sources[i];
                }
            }
            return null;
        },
        filterFontsByCate: function ($container, cate) {
            var $items = $container.find('.squick-font-item');
            $items.each(function(){
                var $this = $(this);
                if ($this.data('category') !== cate) {
                    $this.hide();
                } else {
                    $this.show();
                }
            });
        },
        filterFontByKeyWord: function ($container, keyword) {
            var $items = $container.find('.squick-font-item');
            $items.each(function(){
                var $this = $(this),
                    name = $this.find('.squick-font-item-name').text();

                try {
                    if (name.search(new RegExp(keyword, "i")) < 0) {
                        $this.hide();
                    } else {
                        $this.show();
                    }
                }
                catch (ex)  {}
            });
        },
        searchFonts: function () {
            $('#search_fonts').on('keyup', function (event) {
                var $container = $('.squick-font-container:visible'),
                    keyword = $(this).val();

                $container.find('.squick-font-categories li').removeClass('active');
                SQUICK_Fonts.filterFontByKeyWord($container, keyword);
            });
        },
        tabClick: function () {
            $('.squick-font-type > li > a').on('click', function (event) {
                event.preventDefault();
                if (SQUICK_Fonts._loadFontCount < 3) {
                    return;
                }
                var $this = $(this);
                if ($this.parent().hasClass('active')) {
                    return;
                }
                var ref = $(this).data('ref');

                $('.squick-font-type > li').removeClass('active');
                $this.parent().addClass('active');

                $('.squick-font-container').each(function() {
                    var $container = $(this);
                    if (($container.data('ref') != ref)) {
                        $container.slideUp();
                    }
                });
                $('#' + ref + '_fonts').slideDown(function() {
                    if ($('#' + ref + '_fonts').find('.squick-font-categories li a').length) {
                        $('#' + ref + '_fonts').find('.squick-font-categories li a').first().trigger('click');
                    }
                    else {
                        $('#search_fonts').val('');
                        $('#search_fonts').trigger('keyup');
                    }
                });
            });
        }
    }
    $(document).ready(function () {
        SQUICK_Fonts.init();
    });
})(jQuery);