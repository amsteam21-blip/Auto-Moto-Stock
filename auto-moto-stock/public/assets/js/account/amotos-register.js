(function ($) {
    'use strict';
    $(document).ready(function () {
        return;
        if (typeof amotos_register_vars !== "undefined") {
            var ajax_url = amotos_register_vars.ajax_url;
            var loading = amotos_register_vars.loading;
            $('.amotos-register').validate({
                errorElement: "span", // wrap error elements in span not label
                rules: {
                    user_login: {
                        required: true,
                        minlength: 3
                    },
                    user_email:{
                        required: true,
                        pattern: /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,10}$/
                    },
                    user_password: {
                        required: true
                    },
                    user_password_retype: {
                        required: true
                    }
                },
                messages: {
                    user_login: "",
                    user_email: "",
                    user_password: "",
                    user_password_retype: ""
                }
            });
            $('.amotos-register-button').on('click',function (e) {
                e.preventDefault();
                var $form = $(this).parents('form');
                var $redirect_url=$(this).data('redirect-url');
                var $messages = $(this).parents('.amotos-register-wrap').find('.amotos_messages');
                if ($form.valid()) {
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: $form.serialize(),
                        beforeSend: function () {
                            $messages.empty().append('<span class="success text-success"> ' + loading + '</span>');
                        },
                        success: function (response) {
                            if (response.success) {
                                $messages.empty().append('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');
                                if ($redirect_url == '') {
                                    setTimeout(function () {
                                        $("#amotos_login_modal_tab").click();
                                    }, 4000);
                                }
                                else {
                                    window.location.href = $redirect_url;
                                }
                            } else {
                                if (typeof amotos_reset_recaptcha == 'function') {
                                    amotos_reset_recaptcha();
                                }
                                $messages.empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                            }
                        }
                    });
                }
            });
        }
    });
})(jQuery);