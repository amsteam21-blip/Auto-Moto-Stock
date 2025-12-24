var STUtils = STUtils || {};
(function($) {
    "use strict";

    STUtils.popup = {
        show: function (data) {
            var $body = $('body');

            $body.css('overflow', 'hidden');

            var $popupWrap = $('<div class="stu-popup-wrap"></div>');

            $body.append($popupWrap);

            $popupWrap.on('stu-popup-close', function () {
                var $this = $(this);
                if ($this.data('stu-popup-type') === 'target') {
                    $this.children().appendTo($this.data('stu-popup-target'));
                }
                $this.remove();

                $body.css('overflow', '');
            });

            STUtils.loading.show($popupWrap);


            var $buttonClose = $('<button type="button" class="stu-popup-close">×</button>');
            $buttonClose.on('click', function () {
                $(this).closest('.stu-popup-wrap').trigger('stu-popup-close');
            });
            $popupWrap.data('stu-popup-type', data.type);

            if (data.type === 'target') {
                $(data.target).children().appendTo($popupWrap);
                $popupWrap.data('stu-popup-target', data.target);

                $popupWrap.find('.stu-popup-close').remove();
                $popupWrap.find('.stu-popup-header').append($buttonClose);

                STUtils.loading.close();

                if (typeof (data.callback) === 'function') {
                    data.callback($popupWrap);
                }
            } else if (data.type === 'inline') {
                $popupWrap.append(data.content);
                $popupWrap.find('.stu-popup-close').remove();
                $popupWrap.find('.stu-popup-header').append($buttonClose);

                STUtils.loading.close();
                if (typeof (data.callback) === 'function') {
                    data.callback($popupWrap);
                }

            } else {
                $.ajax({
                    type: typeof (data.method) === 'undefined' ? 'GET' : data.method,
                    url: data.src,
                    success: function (res) {
                        $popupWrap.append(res);
                        $popupWrap.find('.stu-popup-close').remove();
                        $popupWrap.find('.stu-popup-header').append($buttonClose);

                        $popupWrap.find('.stu-navigation a').on('click', function (event) {
                            event.preventDefault();
                            var $this = $(this);
                            if ($this.closest('.stu-navigation-disabled').length === 0) {

                            }
                        });

                        if (typeof (data.callback) === 'function') {
                            data.callback($popupWrap);
                        }
                    },
                    error: function () {
                        $body.css('overflow', '');
                        $popupWrap.remove();
                    },
                    complete: function () {
                        STUtils.loading.close();
                    }
                });
            }
        },
        close: function () {
            $('.stu-popup-wrap').last().trigger('stu-popup-close');
        }
    };

    STUtils.loading = {
        $_loading: null,
        show: function ($wrap) {
            if (STUtils.loading.$_loading === null) {
                STUtils.loading.$_loading = $('<div class="stu-loading"><span></span></div>');
            }
            STUtils.loading.$_loading.appendTo($wrap);
        },
        close: function () {
            if (STUtils.loading.$_loading !== null) {
                STUtils.loading.$_loading.remove();
            }
        }
    };

    STUtils.loadingButton = {
        show: function ($btn) {
            var loadingType = $btn.data('stu-lb') === undefined ? 'stu-lb-left': 'stu-lb-' . $btn.data('stu-lb');

            $btn.append('<div class="stu-lb"></div>');
            $btn.addClass(loadingType).addClass('stu-lb-running');
        },
        hide: function ($btn) {
            var loadingType = $btn.data('stu-lb') === undefined ? 'stu-lb-left': 'stu-lb-' . $btn.data('stu-lb');
            $btn.removeClass(loadingType).removeClass('stu-lb-running');
            $btn.find('.stu-lb').remove();
        }
    };


})(jQuery);