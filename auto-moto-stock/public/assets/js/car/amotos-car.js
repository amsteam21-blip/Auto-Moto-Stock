(function ($) {
    'use strict';
    $(document).ready(function () {
        if (typeof amotos_car_vars !== "undefined") {
            var dtGlobals = {}; // Global storage
            dtGlobals.isMobile	= (/(Android|BlackBerry|iPhone|iPad|Palm|Symbian|Opera Mini|IEMobile|webOS)/.test(navigator.userAgent));
            dtGlobals.isAndroid	= (/(Android)/.test(navigator.userAgent));
            dtGlobals.isiOS		= (/(iPhone|iPod|iPad)/.test(navigator.userAgent));
            dtGlobals.isiPhone	= (/(iPhone|iPod)/.test(navigator.userAgent));
            dtGlobals.isiPad	= (/(iPad|iPod)/.test(navigator.userAgent));
            var ajax_url = amotos_car_vars.ajax_url,
                ajax_upload_url = amotos_car_vars.ajax_upload_url,
                ajax_upload_attachment_url = amotos_car_vars.ajax_upload_attachment_url,
                css_class_wrap = '.car-manage-form',
                amotos_metabox_prefix = amotos_car_vars.amotos_metabox_prefix,
                googlemap_zoom_level = amotos_car_vars.googlemap_zoom_level,
                google_map_style = amotos_car_vars.google_map_style,
                googlemap_marker_icon = amotos_car_vars.googlemap_marker_icon,
                googlemap_default_country = amotos_car_vars.googlemap_default_country,
                googlemap_coordinate_default = amotos_car_vars.googlemap_coordinate_default,
                upload_nonce = amotos_car_vars.upload_nonce,
                file_type_title = amotos_car_vars.localization.file_type_title,
                max_car_images = amotos_car_vars.max_car_images,
                image_max_file_size = amotos_car_vars.image_max_file_size,
                max_car_attachments = amotos_car_vars.max_car_attachments,
                attachment_max_file_size = amotos_car_vars.attachment_max_file_size,
                attachment_file_type = amotos_car_vars.attachment_file_type;

            var uploader_gallery = null,
	            uploader_attachments = null;


            var map; var location; var geocomplete = $("#geocomplete");
            var amotos_geocomplete_map = function () {
                if (geocomplete.length === 0) return;
                var car_form = $('input[name="car_form"]').val();
                var styles = [];
                if (google_map_style !== '') {
                    styles = JSON.parse(google_map_style);
                }

                geocomplete.geocomplete({
                    map: ".map_canvas",
                    details: "form",
                    country: googlemap_default_country,
                    geocodeAfterResult: true,
                    types: ["geocode", "establishment"],
                    mapOptions: {
                        zoom: parseInt(googlemap_zoom_level),
                        styles: styles
                    },
                    markerOptions: {
                        draggable: true,
                        icon: googlemap_marker_icon
                    },
                    location: geocomplete.val() !== ''  ? geocomplete.val() : googlemap_coordinate_default
                }).one("geocode:result", function (event, result) {
                    map = geocomplete.geocomplete("map");
                    google.maps.event.addListenerOnce(map, 'idle', function() {
                        google.maps.event.trigger(map, 'resize');
                        location=result.geometry.location;
                        map.setCenter(result.geometry.location);
                    });
                });
                geocomplete.bind("geocode:dragged", function (event, latLng) {
                    $("input[name=lat]").val(latLng.lat());
                    $("input[name=lng]").val(latLng.lng());
                    $("#reset").show();
                });
                geocomplete.on('focus',function(){
                    google.maps.event.trigger(map, 'resize');
                });
                $("#reset").on('click', function (e) {
                    e.preventDefault();
                    geocomplete.geocomplete("resetMarker");
                    $("#reset").hide();
                    return false;
                });
                $("#find").on('click', function (e) {
                    e.preventDefault();
                    map = geocomplete.geocomplete("map");
                    var marker = geocomplete.geocomplete("marker");
                    location = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());
                    map.setCenter(location);
                    marker.setPosition(location);
                });
                $(window).on('load',function () {
                    geocomplete.trigger("geocode");
                });
            };
            amotos_geocomplete_map();

            $('input[name="manager_display_option"]', css_class_wrap).on('change', function () {
                $('select[name="car_manager"]').hide();
                if ($(this).val() == 'other_info') {
                    $("#car_other_contact").slideDown('slow');
                }
                else {
                    $("#car_other_contact").slideUp('slow');
                }
            });
            var amotos_car_price_on_call_change = function () {
                if ($('input[name="car_price_on_call"]').is(':checked')) {
                    $('input[name="car_price_short"]').attr('disabled', 'disabled');
                    $('select[name="car_price_unit"]').attr('disabled', 'disabled');
                    $('input[name="car_price_prefix"]').attr('disabled', 'disabled');
                    $('input[name="car_price_postfix"]').attr('disabled', 'disabled');
                }
                else {
                    $('input[name="car_price_short"]').removeAttr('disabled');
                    $('select[name="car_price_unit"]').removeAttr('disabled');
                    $('input[name="car_price_prefix"]').removeAttr('disabled');
                    $('input[name="car_price_postfix"]').removeAttr('disabled');
                }
            };
            amotos_car_price_on_call_change();
            $('input[name="car_price_on_call"]', css_class_wrap).on('change', function () {
                amotos_car_price_on_call_change();
            });

            /**
             *  Vehicle Additional Styling
             */ 
            var amotos_execute_additional_order = function () {
                var $i = 0;
                $('tr', '#amotos_additional_details').each(function () {
                    var input_title = $('input[name*="additional_styling_title"]', $(this)),
                        input_value = $('input[name*="additional_styling_value"]', $(this));
                    input_title.attr('name', 'additional_styling_title[' + $i + ']');
                    input_title.attr('id', 'additional_styling_title_' + $i);
                    input_value.attr('name', 'additional_styling_value[' + $i + ']');
                    input_value.attr('id', 'additional_styling_value_' + $i);
                    $i++;
                });
            };
            $('#amotos_additional_details').sortable({
                revert: 100,
                placeholder: "detail-placeholder",
                handle: ".sort-additional-row",
                cursor: "move",
                stop: function (event, ui) {
                    amotos_execute_additional_order();
                }
            });

            $('.add-additional-styling', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var row_num = $(this).data("increment") + 1;
                $(this).data('increment', row_num);
                $(this).attr({
                    "data-increment": row_num
                });

                var new_styling = '<tr>' +
                    '<td class="action-field">' +
                    '<span class="sort-additional-row"><i class="fa fa-navicon"></i></span>' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_styling_title[' + row_num + ']" id="additional_styling_title_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_styling_value[' + row_num + ']" id="additional_styling_value_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<span data-remove="' + row_num + '" class="remove-additional-styling"><i class="fa fa-remove"></i></span>' +
                    '</td>' +
                    '</tr>';
                $('#amotos_additional_details').append(new_styling);
                amotos_remove_additional_styling();
            });

            var amotos_remove_additional_styling = function () {
                $('.remove-additional-styling', css_class_wrap).on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        parent = $this.closest('.additional-block'),
                        button_add = parent.find('.add-additional-styling'),
                        increment = parseInt(button_add.data('increment')) - 1;

                    $this.closest('tr').remove();
                    button_add.data('increment', increment);
                    button_add.attr('data-increment', increment);
                    amotos_execute_additional_order();
                });
            };
            amotos_remove_additional_styling();

            // Vehicle Thumbnails
            var amotos_car_gallery_event = function () {

                // Set Featured Image
                $('.icon-featured', '.amotos-car-gallery').off('click').on('click', function () {

                    var $this = $(this);
                    var thumb_id = $this.data('attachment-id');
                    var icon = $this.find('i');

                    $('.media-thumb .featured-image-id').remove();
                    $('.media-thumb .icon-featured i').removeClass('fa-star').addClass('fa-star-o');

                    $this.closest('.media-thumb').append('<input type="hidden" class="featured-image-id" name="featured_image_id" value="' + thumb_id + '">');
                    icon.removeClass('fa-star-o').addClass('fa-star');
                });

                $('.icon-delete', '.amotos-car-gallery').off('click').on('click', function () {
                    var $this = $(this),
	                    $wrap = $this.closest('.media-thumb-wrap'),
	                    file_id = $wrap.attr('id'),
                        icon_delete = $this.children('i'),
                        thumbnail = $this.closest('.media-thumb-wrap'),
                        car_id = $this.data('car-id'),
                        attachment_id = $this.data('attachment-id');
	                if (typeof file_id !== typeof undefined && file_id !== false) {
		                file_id = file_id.replace('holder-','');
	                }

                    icon_delete.addClass('fa-spinner fa-spin');
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'amotos_remove_car_attachment_ajax',
                            'car_id': car_id,
                            'attachment_id': attachment_id,
                            'type': 'gallery',
                            'removeNonce': $this.data('nonce')
                        },
                        success: function (response) {
                            if (response.success) {
                                thumbnail.remove();
                                thumbnail.hide();
                                if ((uploader_gallery)
                                && (typeof file_id !== typeof undefined && file_id !== false)) {
                                	for (var i = 0; i < uploader_gallery.files.length ; i ++) {
                                		if (uploader_gallery.files[i].id == file_id) {
			                                uploader_gallery.removeFile(uploader_gallery.files[i]);
                                			break;
		                                }
	                                }
                                }

                                if ($('.amotos-car-gallery').find('[name="featured_image_id"]').length === 0) {
                                    var $firstImage = $('.amotos-car-gallery').find('.media-thumb-wrap');
                                    if ($firstImage.length > 0) {
                                        $firstImage = $($firstImage[0]);
                                        var attachment_id  = $firstImage.find('.car_image_ids').val();

                                        $firstImage.find('.icon-featured i').removeClass('fa-star-o').addClass('fa-star');
                                        $firstImage.find('.media-thumb').append('<input type="hidden" class="featured-image-id" name="featured_image_id" value="' + attachment_id + '">');
                                    }
                                }
                            }
                            icon_delete.removeClass('fa-spinner fa-spin');
                        },
                        error: function () {
                            icon_delete.removeClass('fa-spinner fa-spin');
                        }
                    });
                });
            };

            amotos_car_gallery_event();

            // Vehicle Thumbnails
            var amotos_car_attachments_event = function () {
                $('.icon-delete', '.amotos-car-attachments').off('click').on('click', function () {
                    var $this = $(this),
	                    $wrap = $this.closest('.media-thumb-wrap'),
	                    file_id = $wrap.attr('id'),
                        icon_delete = $this.children('i'),
                        thumbnail = $this.closest('.media-thumb-wrap'),
                        car_id = $this.data('car-id'),
                        attachment_id = $this.data('attachment-id');

	                if (typeof file_id !== typeof undefined && file_id !== false) {
		                file_id = file_id.replace('holder-','');
	                }

                    icon_delete.addClass('fa-spinner fa-spin');

                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'amotos_remove_car_attachment_ajax',
                            'car_id': car_id,
                            'attachment_id': attachment_id,
                            'type': 'attachments',
                            'removeNonce': $this.data('nonce')
                        },
                        success: function (response) {
                            if (response.success) {
                                thumbnail.remove();
                                thumbnail.hide();
	                            if ((uploader_attachments)
	                            && (typeof file_id !== typeof undefined && file_id !== false))  {
		                            for (var i = 0; i < uploader_attachments.files.length ; i ++) {
			                            if (uploader_attachments.files[i].id == file_id) {
				                            uploader_attachments.removeFile(uploader_attachments.files[i]);
				                            break;
			                            }
		                            }
	                            }
                            }
                            icon_delete.removeClass('fa-spinner fa-spin');
                        },
                        error: function () {
                            icon_delete.removeClass('fa-spinner fa-spin');
                        }
                    });
                });
            };

            amotos_car_attachments_event();

            // Vehicle Gallery images
            var amotos_car_gallery_images = function () {

                $("#car_gallery_thumbs_container").sortable();

                /* initialize uploader */
                uploader_gallery = new plupload.Uploader({
                    browse_button: 'amotos_select_gallery_images',          // this can be an id of a DOM element or the DOM element itself
                    file_data_name: 'car_upload_file',
                    container: 'amotos_gallery_plupload_container',
                    drop_element: 'amotos_gallery_plupload_container',
                    multi_selection: true,
                    url: ajax_upload_url,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
	            uploader_gallery.init();

	            uploader_gallery.bind('FilesAdded', function (up, files) {
                    var carThumb = "";
                    var maxfiles = max_car_images;
                    var totalFiles = $('#car_gallery_thumbs_container').find('.__thumb').length + up.files.length;
                    if (totalFiles > maxfiles) {
                        $.each(files, function(i, file) {
                            up.removeFile(file);
                        });
                        alert(amotos_car_vars.localization.no_more_than + ' ' + maxfiles + ' ' + amotos_car_vars.localization.files);
                        return;
                    }
                    plupload.each(files, function (file) {
                        carThumb += '<div id="holder-' + file.id + '" class="col-sm-2 media-thumb-wrap"></div>';
                    });
                    document.getElementById('car_gallery_thumbs_container').innerHTML += carThumb;
                    up.refresh();
		            up.start();
                });

	            uploader_gallery.bind('UploadProgress', function (up, file) {
                    document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
                });

	            uploader_gallery.bind('Error', function (up, err) {
                    document.getElementById('amotos_gallery_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });

	            uploader_gallery.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);

                    if (response.success) {
                        var $html =
                            '<figure class="media-thumb">' +
                            '<img src="' + response.url + '"/>' +
                            '<div class="media-item-actions">' +
                            '<a class="icon icon-delete" data-car-id="0" data-nonce="' + response.delete_nonce + '" data-attachment-id="' + response.attachment_id + '" href="javascript:;"><i class="fa fa-trash-o"></i></a>' +
                            '<a class="icon icon-featured" data-car-id="0" data-attachment-id="' + response.attachment_id + '" href="javascript:;"><i class="fa fa-star-o"></i></a>' +
                            '<input type="hidden" class="car_image_ids" name="car_image_ids[]" value="' + response.attachment_id + '"/>' +
                            '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                            '</div>' +
                            '</figure>';

                        document.getElementById("holder-" + file.id).innerHTML = $html;

                        if ($('.amotos-car-gallery').find('[name="featured_image_id"]').length === 0) {
                            $('#holder-'+ file.id).find('.icon-featured i').removeClass('fa-star-o').addClass('fa-star');
                            $('#holder-'+ file.id).find('.media-thumb').append('<input type="hidden" class="featured-image-id" name="featured_image_id" value="' + response.attachment_id + '">');
                        }
                        amotos_car_gallery_event();
                    }
                });
            };
            amotos_car_gallery_images();

            // Vehicle Attashments
            var amotos_car_attachments = function () {

                $("#car_attachments_thumbs_container").sortable();

                /* initialize uploader */
                uploader_attachments = new plupload.Uploader({
                    browse_button: 'amotos_select_file_attachments',          // this can be an id of a DOM element or the DOM element itself
                    file_data_name: 'car_upload_file',
                    container: 'amotos_attachments_plupload_container',
                    drop_element: 'amotos_attachments_plupload_container',
                    multi_selection: true,
                    url: ajax_upload_attachment_url,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: attachment_file_type}
                        ],
                        max_file_size: attachment_max_file_size,
                        prevent_duplicates: true
                    }
                });
	            uploader_attachments.init();

	            uploader_attachments.bind('FilesAdded', function (up, files) {
                    var carThumb = "";
                    var maxfiles = max_car_attachments;
		            var totalFiles = $('#car_attachments_thumbs_container').find('.__thumb').length + up.files.length;
                    if (totalFiles > maxfiles) {
	                    $.each(files, function(i, file) {
		                    up.removeFile(file);
	                    });
                        alert(amotos_car_vars.localization.no_more_than + ' ' + maxfiles + ' ' + amotos_car_vars.localization.files);
                        return;
                    }
                    plupload.each(files, function (file) {
                        carThumb += '<div id="holder-' + file.id + '" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 media-thumb-wrap"></div>';
                    });
                    document.getElementById('car_attachments_thumbs_container').innerHTML += carThumb;
                    up.refresh();
                    up.start();
                });

	            uploader_attachments.bind('UploadProgress', function (up, file) {
                    document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
                });

	            uploader_attachments.bind('Error', function (up, err) {
                    document.getElementById('amotos_attachments_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });

	            uploader_attachments.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);

                    if (response.success) {

                        var $html =
                            '<figure class="media-thumb">' +
                            '<img src="' + response.thumb_url + '"/>' +
                            '<a href="'+ response.url +'">' + response.file_name + '</a>'+
                            '<div class="media-item-actions">' +
                            '<a class="icon icon-delete" data-nonce="' + response.delete_nonce + '" data-car-id="0"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                            '<input type="hidden" class="car_attachment_ids" name="car_attachment_ids[]" value="' + response.attachment_id + '"/>' +
                            '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                            '</div>' +
                            '</figure>';

                        document.getElementById("holder-" + file.id).innerHTML = $html;
                        amotos_car_attachments_event();
                    }
                });
            };
            amotos_car_attachments();
            // Image 360
            var amotos_image_360 = function () {

                var uploader_image_360 = new plupload.Uploader({
                    browse_button: 'amotos_select_images_360',
                    file_data_name: 'car_upload_file',
                    container: 'amotos_image_360_plupload_container',
                    url: ajax_upload_url,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader_image_360.init();

                uploader_image_360.bind('FilesAdded', function (up, files) {
                    var maxfiles = max_car_images;
                    if (up.files.length > maxfiles) {
	                    $.each(files, function(i, file) {
		                    up.removeFile(file);
	                    });
                        alert(amotos_car_vars.localization.no_more_than + ' ' + maxfiles + ' ' + amotos_car_vars.localization.files);
                        return;
                    }
                    plupload.each(files, function (file) {

                    });
                    up.refresh();
	                up.start();
                });
                uploader_image_360.bind('Error', function (up, err) {
                    document.getElementById('amotos_image_360_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });
                uploader_image_360.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);
                    if (response.success) {
                        $('.amotos_image_360_url').val(response.full_image);
                        $('.amotos_image_360_id').val(response.attachment_id);
                        var plugin_url = $('#amotos_car_image_360_view').attr('data-plugin-url');
                        var _iframe = '<iframe width="100%" height="200" scrolling="no" allowfullscreen src="' + plugin_url + 'public/assets/packages/vr-view/index.html?image=' + response.full_image + '"></iframe>';
                        $('#amotos_car_image_360_view').html(_iframe);
                    }
                });
            };
            amotos_image_360();

            var amotos_get_states_by_country = function () {
                var $this = $(".amotos-car-country-ajax", css_class_wrap);
                if ($this.length) {
                    var selected_country = $this.val();
                    if ($('#geocomplete').length > 0) {
                        var autocomplete = $('#geocomplete').geocomplete("autocomplete");
                        autocomplete.setComponentRestrictions({ country: selected_country });
                    }

                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'amotos_get_states_by_country_ajax',
                            'country': selected_country,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.amotos-loading').remove();
                            $this.parent().append('<span class="amotos-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".amotos-car-state-ajax", css_class_wrap).html(response);
                            var val_selected = $(".amotos-car-state-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".amotos-car-state-ajax", css_class_wrap).val(val_selected);
                            }
                            $this.parent().children('.amotos-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.amotos-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.amotos-loading').remove();
                        }
                    });
                }
            };
            amotos_get_states_by_country();

            $(".amotos-car-country-ajax", css_class_wrap).on('change', function () {
                amotos_get_states_by_country();
            });

            var amotos_get_cities_by_state = function () {
                var $this = $(".amotos-car-state-ajax", css_class_wrap);
                if ($this.length) {
                    var selected_state = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'amotos_get_cities_by_state_ajax',
                            'state': selected_state,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.amotos-loading').remove();
                            $this.parent().append('<span class="amotos-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".amotos-car-city-ajax", css_class_wrap).html(response);
                            var val_selected = $(".amotos-car-city-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".amotos-car-city-ajax", css_class_wrap).val(val_selected);
                            }
                            $this.parent().children('.amotos-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.amotos-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.amotos-loading').remove();
                        }
                    });
                }
            };
            amotos_get_cities_by_state();

            $(".amotos-car-state-ajax", css_class_wrap).on('change', function () {
                amotos_get_cities_by_state();
            });

            var amotos_get_neighborhoods_by_city = function () {
                var $this = $(".amotos-car-city-ajax", css_class_wrap);
                if ($this.length) {
                    var selected_city = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'amotos_get_neighborhoods_by_city_ajax',
                            'city': selected_city,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.amotos-loading').remove();
                            $this.parent().append('<span class="amotos-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".amotos-car-neighborhood-ajax", css_class_wrap).html(response);
                            var val_selected = $(".amotos-car-neighborhood-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".amotos-car-neighborhood-ajax", css_class_wrap).val(val_selected);
                            }
                            $this.parent().children('.amotos-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.amotos-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.amotos-loading').remove();
                        }
                    });
                }
            };
            amotos_get_neighborhoods_by_city();

            $(".amotos-car-city-ajax", css_class_wrap).on('change', function () {
                amotos_get_neighborhoods_by_city();
            });
            var amotos_car_multi_step = $(".amotos-car-multi-step");
            amotos_car_multi_step.find('.amotos-btn-next').on('click', function () {
                if(dtGlobals.isiOS) {
                    amotos_car_gallery_images();
                    amotos_car_attachments();
                    amotos_image_360();
                }
                if ($('#step-location').attr('aria-hidden') === 'false') {
                    if (typeof geocomplete !== 'undefined') {
                        geocomplete.trigger("geocode");
                    }
                }
            });
            amotos_car_multi_step.find('.amotos-btn-edit').on('click', function () {
                if(dtGlobals.isiOS) {
                    amotos_car_gallery_images();
                    amotos_car_attachments();
                    amotos_image_360();
                }
                if ($('#step-location').attr('aria-hidden') === 'false') {
                    if (typeof geocomplete !== 'undefined') {
                        geocomplete.trigger("geocode");
                    }
                }
            });
            var enable_filter_location=amotos_car_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.amotos-car-country-ajax', css_class_wrap).select2();
                $('.amotos-car-state-ajax', css_class_wrap).select2();
                $('.amotos-car-city-ajax', css_class_wrap).select2();
                $('.amotos-car-neighborhood-ajax', css_class_wrap).select2();
            }

            $('#car_type, #car_status, #car_label', css_class_wrap).select2();

        }
    });
})(jQuery);