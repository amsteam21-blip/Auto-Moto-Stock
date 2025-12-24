var AMOTOS_Car_Search = AMOTOS_Car_Search || {};
(function ($) {
    'use strict';
    var ajax_url = '';// amotos_search_vars.ajax_url;
    var price_is_slider = '';// amotos_search_vars.price_is_slider;
    var css_class_wrap = '.amotos-search-cars';
    AMOTOS_Car_Search = {
        init: function () {
            if ($(css_class_wrap).length === 0) {
                return;
            }

            if ($(css_class_wrap).hasClass('amotos-search-cars-map')) {
                return;
            }

            if (typeof ($(css_class_wrap).data('options')) !== "undefined") {
                window['amotos_search_vars'] = $(css_class_wrap).data('options');
            }

            if (typeof (amotos_search_vars) === "undefined") {
                return;
            }


            ajax_url = amotos_search_vars.ajax_url;
            price_is_slider = amotos_search_vars.price_is_slider;


            var enable_filter_location=amotos_search_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.amotos-car-country-ajax', css_class_wrap).select2();
                $('.amotos-car-state-ajax', css_class_wrap).select2();
                $('.amotos-car-city-ajax', css_class_wrap).select2();
                $('.amotos-car-neighborhood-ajax', css_class_wrap).select2();
            }

            this.get_states_by_country();
            $(".amotos-car-country-ajax", css_class_wrap).on('change', function () {
                AMOTOS_Car_Search.get_states_by_country();
            });
            this.get_cities_by_state();
            $(".amotos-car-state-ajax", css_class_wrap).on('change', function () {
                AMOTOS_Car_Search.get_cities_by_state();
            });
            this.get_neighborhoods_by_city();
            $(".amotos-car-city-ajax", css_class_wrap).on('change', function () {
                AMOTOS_Car_Search.get_neighborhoods_by_city();
            });
            $('.btn-status-filter', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var status = $(this).data("value");
                $(this).parent().find('input').val(status);
                $(this).parent().find('button').removeClass('active');
                $(this).addClass('active');
                AMOTOS_Car_Search.change_price_on_status_change(status);
            });
            $('select[name="status"]', css_class_wrap).on('change', function (e) {
                e.preventDefault();
                var status = $(this).val();
                AMOTOS_Car_Search.change_price_on_status_change(status);
            });
            this.execute_url_search();
            $(".amotos-sliderbar-filter.amotos-sliderbar-price", css_class_wrap).on('register.again', function () {
                $(".amotos-sliderbar-filter.amotos-sliderbar-price", css_class_wrap).each(function () {
                    var slider_filter = $(this);
                    AMOTOS_Car_Search.set_slider_filter(slider_filter);
                });
            });
            this.register_slider_filter();
            this.set_slider_value();
        },
        get_states_by_country: function () {
            var $this = $(".amotos-car-country-ajax", css_class_wrap);
            if ($this.length) {
                var selected_country = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'amotos_get_states_by_country_ajax',
                        'country': selected_country,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".amotos-car-state-ajax", css_class_wrap).html(response);
                        var val_selected = $(".amotos-car-state-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".amotos-car-state-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_cities_by_state: function () {
            var $this = $(".amotos-car-state-ajax", css_class_wrap);
            if ($this.length) {
                var selected_state = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'amotos_get_cities_by_state_ajax',
                        'state': selected_state,
                        'type': 1
                    },
                    success: function (response) {
                        $(".amotos-car-city-ajax", css_class_wrap).html(response);
                        var val_selected = $(".amotos-car-city-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".amotos-car-city-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_neighborhoods_by_city: function () {
            var $this = $(".amotos-car-city-ajax", css_class_wrap);
            if ($this.length) {
                var selected_city = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'amotos_get_neighborhoods_by_city_ajax',
                        'city': selected_city,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".amotos-car-neighborhood-ajax", css_class_wrap).html(response);
                        var val_selected = $(".amotos-car-neighborhood-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".amotos-car-neighborhood-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        execute_url_search: function () {
            $('.amotos-advanced-search-btn', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var search_form = $(this).closest('.search-cars-form'),
                    search_url = search_form.data('href'),
                    search_field = [],
                    query_string = '?';
                if (search_url.indexOf('?') !== -1) {
                    query_string = '&';
                }
                $('.search-field', search_form).each(function () {
                    var $this = $(this),
                        field_name = $this.attr('name'),
                        current_value = $this.val(),
                        default_value = $this.data('default-value');
                    if (current_value != default_value) {
                        search_field[field_name] = current_value;
                    }
                });
                $('.amotos-sliderbar-filter', search_form).each(function () {
                    var $this = $(this),
                        field_name_min = $this.find('.min-input-request').attr('name'),
                        field_name_max = $this.find('.max-input-request').attr('name'),
                        current_value_min = $this.find('.min-input-request').val(),
                        current_value_max = $this.find('.max-input-request').val(),
                        default_value_min = $this.data('min-default'),
                        default_value_max = $this.data('max-default');
                    if (current_value_min != default_value_min || current_value_max != default_value_max) {
                        search_field[field_name_min] = current_value_min;
                        search_field[field_name_max] = current_value_max;
                    }
                });
                var other_stylings = '';
                $('[name="other_stylings"]', search_form).each(function () {
                    var $this = $(this),
                        value = $this.attr('value');
                    if ($this.is(':checked')) {
                        other_stylings += value + ";";
                    }
                });
                if (other_stylings !== '') {
                    other_stylings = other_stylings.substring(0, other_stylings.length - 1);
                    search_field['other_stylings'] = other_stylings;
                }
                if (search_field !== []) {
                    for (var k in search_field) {
                        if (search_field.hasOwnProperty(k)) {
                            query_string += k + "=" + encodeURIComponent(search_field[k]) + "&";
                        }
                    }
                }

                query_string = query_string.substring('0', query_string.length - 1);
                window.location.href = search_url + query_string;
            });
        },
        set_slider_filter: function (elm) {
            var $container = elm,
                min = parseInt($container.attr('data-min-default')),
                max = parseInt($container.attr('data-max-default')),
                min_value = $container.attr('data-min'),
                max_value = $container.attr('data-max'),
                $sidebar_filter = $container.find('.sidebar-filter'),
                min_text = '',
                max_text = '',
                x, y;
            $sidebar_filter.slider({
                min: min,
                max: max,
                range: true,
                values: [min_value, max_value],
                slide: function (event, ui) {
                    x = ui.values[0];
                    y = ui.values[1];
                    $container.attr('data-min', x);
                    $container.attr('data-max', y);
                    $container.find('input.min-input-request').attr('value', x);
                    $container.find('input.max-input-request').attr('value', y);

                    if ($container.find('span').hasClass("not-format")) {
                        min_text =  x;
                        max_text =  y;
                    } else {
                        min_text =  AMOTOS.number_format(x);
                        max_text = AMOTOS.number_format(y);
                    }

                    if ($container.hasClass('amotos-sliderbar-price')) {
                        if (amotos_main_vars.currency_position === 'before') {
                            min_text =  amotos_main_vars.currency + min_text;
                            max_text = amotos_main_vars.currency + max_text;
                        } else {
                            min_text = min_text +  amotos_main_vars.currency;
                            max_text = max_text + amotos_main_vars.currency;
                        }
                    }

                    $container.find('span.min-value').html(min_text);
                    $container.find('span.max-value').html(max_text);
                },
                stop: function (event, ui) {

                }
            });
        },
        register_slider_filter: function () {
            $(".amotos-sliderbar-filter", css_class_wrap).each(function () {
                var slider_filter = $(this);
                AMOTOS_Car_Search.set_slider_filter(slider_filter);
            });
        },
        set_slider_value: function () {
            $('.amotos-sliderbar-filter', css_class_wrap).each(function () {
                var $this = $(this),
                    min_default = $this.attr('data-min-default'),
                    max_default = $this.attr('data-max-default'),
                    min_value = $this.attr('data-min'),
                    max_value = $this.attr('data-max'),
                    left = (min_value - min_default) / (max_default - min_default) * 100 + '%',
                    width = (max_value - min_value) / (max_default - min_default) * 100 + '%',
                    left_max = (max_value - min_default) / (max_default - min_default) * 100 + '%';
                $this.find('.ui-slider-range.ui-corner-all.ui-widget-header').css({
                    'left': left,
                    'width': width
                });
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default').css('left', left);
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default:last-child').css('left', left_max);
            })
        },
        change_price_on_status_change: function (status) {
            $.ajax({
                type: 'POST',
                url: ajax_url,
                dataType: 'json',
                data: {
                    'action': 'amotos_ajax_change_price_on_status_change',
                    'status': status,
                    'price_is_slider': price_is_slider
                },
                success: function (response) {
                    if (response.slide_html) {
                        $('.amotos-sliderbar-price-wrap', css_class_wrap).html(response.slide_html);
                        AMOTOS_Car_Search.register_slider_filter();
                        AMOTOS_Car_Search.set_slider_value();
                    }
                    else {
                        if (response.min_price_html) {
                            $('select[name="min-price"]', css_class_wrap).html(response.min_price_html);
                        }
                        if (response.max_price_html) {
                            $('select[name="max-price"]', css_class_wrap).html(response.max_price_html);
                        }
                    }
                }
            });
        }
    };
    $(document).ready(function () {
        if (!$('body').hasClass('elementor-editor-active')) {
            AMOTOS_Car_Search.init();
        }
    });
})(jQuery);