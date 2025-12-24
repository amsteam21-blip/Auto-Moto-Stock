<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Car')) {
    /**
     * Class AMOTOS_Car
     */
    class AMOTOS_Car
    {
	    /*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }

        /**
         * Validate remove attachment file
         *
         * @param $car_id
         * @param $attachment_id
         * @param $type
         *
         * @return bool
         */
        public function validate_remove_attachment($car_id, $attachment_id, $type = 'gallery') {
            $user_id = get_current_user_id();

            $is_check = false;

            if ($car_id > 0) {
                // Check vehicle of current user
                $car = get_post($car_id);
                if ($car->post_author != $user_id) {
                    return false;
                }

                // Check $attachment_id of $car_id
                $meta_key = '';
                if ($type === 'gallery') {
                    $meta_key = AMOTOS_METABOX_PREFIX . 'car_images';
                } else {
                    $meta_key = AMOTOS_METABOX_PREFIX . 'car_attachments';
                }
                $car_images = get_post_meta($car_id,$meta_key,true);
                $car_images = array_map('intval',  array_filter(explode('|', $car_images))) ;
                $str_img_ids = '';

                if (!empty($car_images)) {
                    foreach ($car_images as $car_image) {
                        if ($car_image == $attachment_id) {
                            $is_check = true;
                        } else {
                            $str_img_ids .= '|' . intval($car_image);
                        }
                    }
                    $str_img_ids = substr($str_img_ids, 1);

                    if ($is_check) {
                        update_post_meta($car_id,$meta_key,$str_img_ids);
                    }
                }
            }

            // Check $attachment_id of current user
            if (!$is_check) {
                $meta_key = AMOTOS_METABOX_PREFIX . 'car_attachment';
                $car_images = get_user_meta($user_id,$meta_key,true);
                $car_images = array_map('intval',  array_filter(explode('|', $car_images))) ;
                $str_img_ids = '';
                if (!empty($car_images)) {
                    foreach ($car_images as $car_image) {
                        if ($car_image == $attachment_id) {
                            $is_check = true;
                        } else {
                            $str_img_ids .= '|' . intval($car_image);
                        }
                    }
                    $str_img_ids = substr($str_img_ids, 1);
                    update_user_meta($user_id,$meta_key,$str_img_ids);
                }
            }

            return $is_check;
        }

	    /**
         * Remove Vehicle thumbnail
         */
        public function remove_car_attachment_ajax()
        {
            if (!amotos_is_cap_customer()) {
                $json_response = array(
                    'success' => false,
                    'reason' => esc_html__('Security check fails', 'auto-moto-stock')
                );
                echo wp_json_encode($json_response);
                wp_die();
            }

            $nonce = isset($_POST['removeNonce']) ? amotos_clean(wp_unslash($_POST['removeNonce'])) : '';
			$user_id = get_current_user_id();
	        $attachment_id = isset($_POST['attachment_id']) ? absint(amotos_clean(wp_unslash($_POST['attachment_id']))) : 0;
	        $car_id = isset($_POST['car_id']) ? absint(amotos_clean(wp_unslash($_POST['car_id']))) : 0;

            if (!wp_verify_nonce($nonce, "AMOTOS_Delete_Car_Attachment_{$user_id}_{$attachment_id}_{$car_id}")) {
                $json_response = array(
                    'success' => false,
                    'reason' => esc_html__('Security check fails', 'auto-moto-stock')
                );
                echo wp_json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['car_id']) && isset($_POST['attachment_id'])) {
                $car_id = absint(amotos_clean(wp_unslash($_POST['car_id']))) ;
                $type = isset($_POST['type']) ? amotos_clean(wp_unslash($_POST['type'])) : '';
                $attachment_id = absint(amotos_clean(wp_unslash($_POST['attachment_id'])));
                $is_check = $this->validate_remove_attachment($car_id, $attachment_id, $type);

                if (($attachment_id > 0) && $is_check) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            $ajax_response = array(
                'success' => $success,
            );
            echo wp_json_encode($ajax_response);
            wp_die();
        }

        public function delete_car_attachments($postid)
        {
            global $post_type;
            if ($post_type == 'car') {
                $media = get_children(array(
                    'post_parent' => $postid,
                    'post_type' => 'attachment'
                ));
                if (!empty($media)) {
                    foreach ($media as $file) {
                        wp_delete_attachment($file->ID);
                    }
                }
                $gallery_ids = get_post_meta($postid, AMOTOS_METABOX_PREFIX . 'car_images', true);
                $gallery_ids = explode('|', $gallery_ids);
                if (!empty($gallery_ids)) {
                    foreach ($gallery_ids as $id) {
                        wp_delete_attachment($id);
                    }
                }

                $attachment_ids = get_post_meta($postid, AMOTOS_METABOX_PREFIX . 'car_attachments', true);
                $attachment_ids = explode('|', $attachment_ids);
                if (!empty($attachment_ids)) {
                    foreach ($attachment_ids as $id) {
                        wp_delete_attachment($id);
                    }
                }
            }
            return;
        }

        public function car_img_upload_ajax()
        {
            if (!amotos_is_cap_customer()) {
                $json_response = array(
                    'success' => false,
                    'reason' => esc_html__('Security check fails', 'auto-moto-stock')
                );
                echo wp_json_encode($json_response);
                wp_die();
            }

            $nonce = isset($_REQUEST['nonce']) ? amotos_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'car_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['car_upload_file'];

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid' => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $thumbnail_url = wp_get_attachment_thumb_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

	            $user_id = get_current_user_id();
                $meta_key = AMOTOS_METABOX_PREFIX . 'car_attachment';
                $meta_value = get_user_meta($user_id,$meta_key,true);
                if (empty($meta_value)) {
                    $meta_value = $attach_id;
                } else {
                    $meta_value .= "|{$attach_id}";
                }
                update_user_meta($user_id,$meta_key,$meta_value);


                $ajax_response = array(
                    'success' => true,
                    'url' => $thumbnail_url,
                    'attachment_id' => $attach_id,
                    'full_image' => $fullimage_url[0],
                    'delete_nonce' => wp_create_nonce("AMOTOS_Delete_Car_Attachment_{$user_id}_{$attach_id}_0")
                );
                echo wp_json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }
        }

        public function car_attachment_upload_ajax()
        {
            if (!amotos_is_cap_customer()) {
                $json_response = array(
                    'success' => false,
                    'reason' => esc_html__('Security check fails', 'auto-moto-stock')
                );
                echo wp_json_encode($json_response);
                wp_die();
            }

            $nonce = isset($_REQUEST['nonce']) ? amotos_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'car_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['car_upload_file'];
            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);


                $attachment_details = array(
                    'guid' => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $attach_url = wp_get_attachment_url($attach_id);
                $file_type_name = isset($file_type['ext']) ? $file_type['ext'] : '';
                $thumb_url = AMOTOS_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type_name . '.png';

				$user_id = get_current_user_id();
                $meta_key = AMOTOS_METABOX_PREFIX . 'car_attachment';
                $meta_value = get_user_meta($user_id,$meta_key,true);
                if (empty($meta_value)) {
                    $meta_value = $attach_id;
                } else {
                    $meta_value .= "|{$attach_id}";
                }
                update_user_meta($user_id,$meta_key,$meta_value);

                $ajax_response = array(
                    'success' => true,
                    'url' => $attach_url,
                    'attachment_id' => $attach_id,
                    'thumb_url' => $thumb_url,
                    'file_name' => $file_name,
	                'delete_nonce' => wp_create_nonce("AMOTOS_Delete_Car_Attachment_{$user_id}_{$attach_id}_0")
                );
                echo wp_json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Document upload failed!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Submit Vehicle
         * @param array $new_car
         * @return int|null|WP_Error
         */
        public function submit_car($new_car = array())
        {
            $new_car['post_type'] = 'car';
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $new_car['post_author'] = $user_id;
            $auto_publish = amotos_get_option('auto_publish', 1);
            $auto_publish_edited = amotos_get_option('auto_publish_edited', 1);
            $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
	        $package_num_cars = 0;

            if (isset($_POST['car_title'])) {
                $new_car['post_title'] = amotos_clean(wp_unslash($_POST['car_title']));
            }

            if (isset($_POST['car_des'])) {
                $new_car['post_content'] = wp_filter_post_kses($_POST['car_des']);
            }

            $submit_action = isset($_POST['car_form']) ? amotos_clean(wp_unslash($_POST['car_form'])) : '';
            $car_id = 0;
            if ($submit_action == 'submit-car') {
                if ($paid_submission_type == 'per_listing') {
                    $price_per_listing = amotos_get_option('price_per_listing', 0);
                    if ($price_per_listing <= 0) {
                        if ($auto_publish == 1) {
                            $new_car['post_status'] = 'publish';
                        } else {
                            $new_car['post_status'] = 'pending';
                        }
                    } else {
                        $new_car['post_status'] = 'draft';
                    }
                } else {
                    if ($auto_publish == 1) {
                        $new_car['post_status'] = 'publish';
                    } else {
                        $new_car['post_status'] = 'pending';
                    }
                }

                $car_id = wp_insert_post($new_car, true);
                if ($car_id > 0) {
                    if ($paid_submission_type == 'per_package') {
                        $package_key = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_key', $user_id);
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'package_key', $package_key);
                        $package_num_cars = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_listings', $user_id);
	                    $package_num_cars = intval($package_num_cars);
						if ($package_num_cars - 1 >= 0) {
                            update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', $package_num_cars - 1);
                        }
                    }
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 0);
                }
            } else if ($submit_action == 'edit-car') {
                $car_id = absint(amotos_clean(wp_unslash($_POST['car_id']))) ;
                $new_car['ID'] = intval($car_id);
                if ($paid_submission_type == 'per_package') {
                    $current_package_key = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_key', $user_id);
                    $car_package_key = get_post_meta($new_car['ID'], AMOTOS_METABOX_PREFIX . 'package_key', true);
                    $amotos_profile = new AMOTOS_Profile();
                    $check_package = $amotos_profile->user_package_available($user_id);

					// Default: allow = 1
					$amotos_check_edit_car_for_per_package = apply_filters('amotos_check_edit_car_for_per_package', 1, $new_car['ID'], $user_id);

                    if (($amotos_check_edit_car_for_per_package == 1) && (empty($car_package_key) || ($current_package_key != $car_package_key) || ($check_package == -1) || ($check_package == 0))) {
                        return -1;
                    }
                }
                if ($auto_publish_edited != 1) {
                    $new_car['post_status'] = 'pending';
                }

                $car_id = wp_update_post($new_car);

            }
            if ($car_id > 0) {
                $car_price_on_call = isset($_POST['car_price_on_call']) ? 1 : 0;
                update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_on_call', $car_price_on_call);
                if ($car_price_on_call == 1) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_short', '');
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', 1);
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price', '');
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_prefix', '');
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_postfix', '');
                } else {
                    if (isset($_POST['car_price_unit'])) {
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', amotos_clean(wp_unslash($_POST['car_price_unit'])));
                    } else {
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', 1);
                    }
                    if (isset($_POST['car_price_short'])) {
                        $car_price_short = amotos_format_decimal(amotos_clean(wp_unslash($_POST['car_price_short'])));
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_short', $car_price_short);
                        if (is_numeric($car_price_short)) {
                            if (isset($_POST['car_price_unit']) && is_numeric($_POST['car_price_unit'])) {
                                $car_price_unit = absint(amotos_clean(wp_unslash($_POST['car_price_unit'])));
                            } else {
                                $car_price_unit = 1;
                            }
                            $car_price = doubleval($car_price_short) * intval($car_price_unit);
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price', $car_price);
                        } else {
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price', '');
                        }
                    }
                    if (isset($_POST['car_price_prefix'])) {
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_prefix', amotos_clean(wp_unslash($_POST['car_price_prefix'])));
                    }

                    if (isset($_POST['car_price_postfix'])) {
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price_postfix', amotos_clean(wp_unslash($_POST['car_price_postfix'])));
                    }
                }

                if (isset($_POST['car_mileage'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_mileage', amotos_clean(wp_unslash($_POST['car_mileage'])));
                }

                if (isset($_POST['car_power'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_power', amotos_clean(wp_unslash($_POST['car_power'])));
                }

                if (isset($_POST['car_volume'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_volume', amotos_clean(wp_unslash($_POST['car_volume'])));
                }

                if (isset($_POST['car_doors'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_doors', amotos_clean(wp_unslash($_POST['car_doors'])));
                }

                if (isset($_POST['car_seats'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_seats', amotos_clean(wp_unslash($_POST['car_seats'])));
                }

                if (isset($_POST['car_owners'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_owners', amotos_clean(wp_unslash($_POST['car_owners'])));
                }

                if (isset($_POST['car_year'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_year', amotos_clean(wp_unslash($_POST['car_year'])));
                }

                if (isset($_POST['car_video_url'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_video_url', amotos_clean(wp_unslash($_POST['car_video_url'])));
                }
                if (isset($_POST['car_identity']) && !empty($_POST['car_identity'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_identity', amotos_clean(wp_unslash($_POST['car_identity'])));
                } else {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_identity', $car_id);
                }
                $additional_fields = amotos_render_additional_fields();
                if (count($additional_fields) > 0) {
                    foreach ($additional_fields as $key => $field) {
                        if (isset($_POST[$field['id']])) {
                            if ($field['type'] == 'checkbox_list') {
                                $arr = array();
                                foreach ($_POST[$field['id']] as $v) {
                                    $arr[] = $v;
                                }
                                update_post_meta($car_id, $field['id'], $arr);
                            } elseif ($field['type'] == 'textarea') {
	                            update_post_meta($car_id, $field['id'], wp_filter_post_kses($_POST[$field['id']]));
                            } else {
                                update_post_meta($car_id, $field['id'], amotos_clean(wp_unslash($_POST[$field['id']])));
                            }
                        }
                    }
                }
                if (isset($_POST['car_image_360_id']) && isset($_POST['car_image_360_url'])) {
                    $car_image_360 = array(
                        'id' => absint(amotos_clean(wp_unslash($_POST['car_image_360_id']))),
                        'url' => esc_url_raw(wp_unslash($_POST['car_image_360_url'])),
                    );
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_image_360', $car_image_360);
                }
                $_car_image_ids = isset($_POST['car_image_ids']) ? amotos_clean(wp_unslash($_POST['car_image_ids'])) : '';
	            if (is_array($_car_image_ids)) {
		            $car_image_ids = array();
		            $str_img_ids = '';
		            foreach ($_car_image_ids as $car_img_id) {
			            $car_image_ids[] = intval($car_img_id);
			            $str_img_ids .= '|' . intval($car_img_id);
		            }
		            $str_img_ids = substr($str_img_ids, 1);
		            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_images', $str_img_ids);

		            $featured_image_id = isset($_POST['featured_image_id']) ? absint(amotos_clean(wp_unslash($_POST['featured_image_id'])))  : 0;
		            if ($featured_image_id > 0) {
			            if (in_array($featured_image_id, $car_image_ids)) {
				            update_post_meta($car_id, '_thumbnail_id', $featured_image_id);
				            if (!empty($_POST['car_video_url'])) {
					            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_video_image', $featured_image_id);
				            }
			            }
		            } elseif (!empty ($car_image_ids)) {
			            update_post_meta($car_id, '_thumbnail_id', $car_image_ids[0]);
		            }
	            }

	            $_car_attachment_ids = isset($_POST['car_attachment_ids']) ? amotos_clean(wp_unslash($_POST['car_attachment_ids'])) : '';
	            if (!empty($_car_attachment_ids) && is_array($_car_attachment_ids)) {
		            $car_attachment_ids = array();
		            $str_attachment_ids = '';
		            foreach ($_car_attachment_ids as $car_attachment_id) {
			            $car_attachment_ids[] = intval($car_attachment_id);
			            $str_attachment_ids .= '|' . intval($car_attachment_id);
		            }
		            $str_attachment_ids = substr($str_attachment_ids, 1);
		            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_attachments', $str_attachment_ids);
	            }

                $car_type = isset($_POST['car_type']) ? array_map('intval',wp_unslash($_POST['car_type'])) : null;
	            wp_set_object_terms($car_id, $car_type, 'car-type');

	            $car_status = isset($_POST['car_status']) ? array_map('intval',wp_unslash($_POST['car_status'])) : null;
	            wp_set_object_terms($car_id, $car_status, 'car-status');

	            $car_label = isset($_POST['car_label']) ? array_map('intval',wp_unslash($_POST['car_label'])) : null;
	            wp_set_object_terms($car_id, $car_label, 'car-label');


                if (isset($_POST['locality'])) {
                    $car_city = amotos_clean(wp_unslash($_POST['locality']));
                    wp_set_object_terms($car_id, $car_city, 'car-city');
                } elseif (isset($_POST['car_city'])) {
                    $car_city = amotos_clean(wp_unslash($_POST['car_city']));
                    wp_set_object_terms($car_id, $car_city, 'car-city');
                }
                if (isset($_POST['neighborhood'])) {
                    $car_neighborhood = amotos_clean(wp_unslash($_POST['neighborhood']));
                    wp_set_object_terms($car_id, $car_neighborhood, 'car-neighborhood');
                } elseif (isset($_POST['car_neighborhood'])) {
                    $car_neighborhood = amotos_clean(wp_unslash($_POST['car_neighborhood']));
                    wp_set_object_terms($car_id, $car_neighborhood, 'car-neighborhood');
                }

                if (isset($_POST['administrative_area_level_1'])) {
                    $car_state = amotos_clean(wp_unslash($_POST['administrative_area_level_1']));
                    wp_set_object_terms($car_id, $car_state, 'car-state');
                } elseif (isset($_POST['car_state'])) {
                    $car_state = amotos_clean(wp_unslash($_POST['car_state']));
                    wp_set_object_terms($car_id, $car_state, 'car-state');
                }

                $_car_styling = isset($_POST['car_styling']) ? amotos_clean(wp_unslash($_POST['car_styling'])) : '';
                if (is_array($_car_styling)) {
                    $stylings_array = array();
                    foreach ($_car_styling as $styling_id) {
                        $stylings_array[] = intval($styling_id);
                    }
                    wp_set_object_terms($car_id, $stylings_array, 'car-styling');
                }

                if (isset($_POST['manager_display_option'])) {
                    $car_manager_display_option = amotos_clean(wp_unslash($_POST['manager_display_option']));
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'manager_display_option', $car_manager_display_option);
                    if ($car_manager_display_option == 'other_info') {
                        if (isset($_POST['car_other_contact_name'])) {
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_name', amotos_clean(wp_unslash($_POST['car_other_contact_name'])));
                        }
                        if (isset($_POST['car_other_contact_mail'])) {
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_mail', sanitize_email(wp_unslash($_POST['car_other_contact_mail'])));
                        }
                        if (isset($_POST['car_other_contact_phone'])) {
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_phone', amotos_clean(wp_unslash($_POST['car_other_contact_phone'])));
                        }
                        if (isset($_POST['car_other_contact_description'])) {
                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_description', wp_filter_post_kses($_POST['car_other_contact_description']));
                        }
                    } else {
                        update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_author', $user_id);
                    }

                } else {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'manager_display_option', 'author_info');
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_author', $user_id);
                }

                if (isset($_POST['car_map_address'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_address', amotos_clean(wp_unslash($_POST['car_map_address'])));
                }

                if ((isset($_POST['lat']) && !empty($_POST['lat'])) && (isset($_POST['lng']) && !empty($_POST['lng']))) {
                    $lat = amotos_clean(wp_unslash($_POST['lat']));
                    $lng = amotos_clean(wp_unslash($_POST['lng']));
                    $lat_lng = $lat . ',' . $lng;
                    $address = apply_filters('amotos_submit_car_map_address',amotos_clean(wp_unslash($_POST['car_map_address']))) ;
                    $arr_location = array(
                        'location' => $lat_lng,
                        'address' => $address
                    );
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_location', $arr_location);
                }
                if (isset($_POST['country_short'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_country', amotos_clean(wp_unslash($_POST['country_short'])));
                } elseif (isset($_POST['car_country'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_country', amotos_clean(wp_unslash($_POST['car_country'])));
                }
                if (isset($_POST['postal_code'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_zip', amotos_clean(wp_unslash($_POST['postal_code'])));
                }

                if (isset($_POST['additional_styling_title']) && isset($_POST['additional_styling_value'])) {
                    $additional_styling_title = amotos_clean(wp_unslash($_POST['additional_styling_title'])) ;
                    $additional_styling_value = amotos_clean(wp_unslash($_POST['additional_styling_value'])) ;
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_stylings', count($additional_styling_title));
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_title', $additional_styling_title);
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_value', $additional_styling_value);
                } else {
	                delete_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_stylings');
	                delete_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_title');
	                delete_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_value');
                }

                if (isset($_POST['private_note'])) {
                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'private_note', amotos_clean(wp_unslash($_POST['private_note'])));
                }

                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'car_attachment');

	            do_action('amotos_submit_car_done', $car_id, $submit_action, $paid_submission_type, $package_num_cars);

                return $car_id;
            }

            return null;
        }

        /**
         * True if an the user can edit a vehicle.
         * @param $car_id
         * @return mixed
         */
        public function user_can_edit_car($car_id)
        {
            $can_edit = true;

            if (!is_user_logged_in() || !$car_id) {
                $can_edit = false;
            } else {
                $car = get_post($car_id);

                if (!$car || (absint($car->post_author) !== get_current_user_id() && !current_user_can('edit_post', $car_id))) {
                    $can_edit = false;
                }
            }

            return apply_filters('amotos_user_can_edit_car', $can_edit, $car_id);
        }

        /**
         * Get total my vehicles
         * @param $post_status
         * @return int
         */
        public function get_total_my_cars($post_status)
        {
            $args = array(
                'post_type' => 'car',
                'author' => get_current_user_id(),
            );

            if (!empty($post_status)) {
                $args['post_status'] = $post_status;
            }

            $cars = new WP_Query($args);
            wp_reset_postdata();
            return $cars->found_posts;
        }

        /**
         * Get total vehicles by user
         * @param $manager_id
         * @param $user_id
         * @return int
         */
        public function get_total_cars_by_user($manager_id, $user_id)
        {
            if (!is_array($manager_id)) {
                $manager_id = explode(',', $manager_id);
            }

            if (!is_array($user_id)) {
                $user_id =  explode(',', $user_id);
            }

            $args = array(
                'post_type' => 'car',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => AMOTOS_METABOX_PREFIX . 'car_manager',
                        'value' => $manager_id,
                        'compare' => 'IN'
                    ),
                    array(
                        'key' => AMOTOS_METABOX_PREFIX . 'car_author',
                        'value' => $user_id,
                        'compare' => 'IN'
                    )
                )
            );
            $cars = new WP_Query($args);
            return $cars->found_posts;
        }

        /**
         * Contact manager
         */
        public function contact_manager_ajax()
        {
            check_ajax_referer('amotos_contact_manager_ajax_nonce', 'amotos_security_contact_manager');
            $sender_phone = isset($_POST['sender_phone']) ? amotos_clean(wp_unslash($_POST['sender_phone'])) : '';

            $target_email = isset($_POST['target_email']) ?  sanitize_email(wp_unslash($_POST['target_email'])) : '';
            $car_url = isset($_POST['car_url']) ?  sanitize_url(wp_unslash($_POST['car_url'])) : '';
            $target_email = is_email($target_email);
            if (!$target_email) {
                echo wp_json_encode(array('success' => false, 'message' => esc_html__('Target Email address is not properly configured!', 'auto-moto-stock')));
                wp_die();
            }
            //recaptcha
            if (amotos_enable_captcha('contact_manager') || amotos_enable_captcha('contact_dealer')) {
                do_action('amotos_verify_recaptcha');
            }
            $sender_email = isset($_POST['sender_email']) ? sanitize_email(wp_unslash($_POST['sender_email'])) : '';

            $sender_name = isset($_POST['sender_name']) ?  amotos_clean(wp_unslash($_POST['sender_name'])) : '';
            $sender_msg = isset($_POST['sender_msg']) ?  wp_filter_post_kses($_POST['sender_msg']) : '';

            /* translators: %1$s: name of user contact, %2$s: site name. */
            $email_subject = esc_html(sprintf(__('New message sent by %1$s using contact form at %2$s', 'auto-moto-stock'), $sender_name, get_bloginfo('name')));

            $email_body = esc_html__('You have received a message from: ', 'auto-moto-stock') . esc_html($sender_name) . " <br/>";
            if (!empty($sender_phone)) {
                $email_body .= esc_html__('Phone Number : ', 'auto-moto-stock') . esc_html($sender_phone) . " <br/>";
            }
            if (!empty($car_url)) {
                $email_body .= esc_html__('Vehicle Url: ', 'auto-moto-stock') . '<a href="' . esc_url($car_url) . '">' . esc_url($car_url) . '</a><br/>';
            }
            $email_body .= esc_html__('Additional message is as follows.', 'auto-moto-stock') . " <br/>";
            $email_body .= wpautop($sender_msg) . " <br/>";
            /* translators: %1$s: name of user contact, %2$s: email of user contact. */
            $email_body .= esc_html(sprintf(__('You can contact %1$s via email %2$s', 'auto-moto-stock'), $sender_name, $sender_email));

            $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
            $header = apply_filters("amotos_contact_manager_mail_header", $header);
            $header .= 'From: ' . $sender_name . " <" . $sender_email . "> \r\n";

            if (wp_mail($target_email, $email_subject, $email_body, $header)) {
                echo wp_json_encode(array('success' => true, 'message' => esc_html__('Message Sent Successfully!', 'auto-moto-stock')));
            } else {
                echo wp_json_encode(array('success' => false, 'message' => esc_html__('Server Error: WordPress mail function failed!', 'auto-moto-stock')));
            }
            wp_die();
        }

        /**
         * Favorite Vehicle
         */
        public function favorite_ajax()
        {
            global $current_user;
            $car_id = isset($_POST['car_id']) ? absint(amotos_clean(wp_unslash($_POST['car_id']))) : 0;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $added = $removed = false;
            $ajax_response = '';
            if ($user_id > 0) {
                $my_favorites = get_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', true);

                if (!empty($my_favorites) && (!in_array($car_id, $my_favorites))) {
                    array_push($my_favorites, $car_id);
                    $added = true;
                } else {
                    if (empty($my_favorites)) {
                        $my_favorites = array($car_id);
                        $added = true;
                    } else {
                        //Delete favorite
                        $key = array_search($car_id, $my_favorites);
                        if ($key !== false) {
                            unset($my_favorites[$key]);
                            $removed = true;
                        }
                    }
                }

                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', $my_favorites);
                if ($added) {
                    $ajax_response = array('added' => 1, 'message' => esc_html__('Added', 'auto-moto-stock'));
                }
                if ($removed) {
                    $ajax_response = array('added' => 0, 'message' => esc_html__('Removed', 'auto-moto-stock'));
                }
            } else {
                $ajax_response = array(
                    'added' => -1,
                    'message' => esc_html__('You are not login', 'auto-moto-stock')
                );
            }
            echo wp_json_encode($ajax_response);
            wp_die();
        }

	    public function view_gallery_ajax() {
		    $car_id = isset($_POST['car_id']) ?  absint(amotos_clean(wp_unslash($_POST['car_id']))) : 0;
		    $gallery = amotos_get_car_gallery_image($car_id);
		    if ($gallery === FALSE) {
			    wp_send_json_error(esc_html__('No Images','auto-moto-stock'));
		    }
		    $images = [];
		    foreach ($gallery as $image) {
			    $image_src = wp_get_attachment_image_src($image, 'full');
			    if (is_array($image_src)) {
				    $images[] = $image_src[0];
			    }
		    }
		    wp_send_json_success($images);
	    }

        /**
         * Get total favorite
         * @return int
         */
        public function get_total_favorite()
        {
            $user_id = get_current_user_id();
            $my_favorites = get_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', true);
            if (empty($my_favorites)) {
                $my_favorites = array(0);
            }
            $args = array(
                'post_type' => 'car',
                'post__in' => $my_favorites
            );
            $favorites = new WP_Query($args);
            wp_reset_postdata();
            return $favorites->found_posts;
        }

        /**
         * Print Vehicle
         */
        public function car_print_ajax()
        {
            $car_id = isset($_POST['car_id']) ? absint(amotos_clean(wp_unslash($_POST['car_id']))) : 0;
            if ($car_id <= 0) {
            	return;
            }
            $isRTL = 'false';
            if (isset($_POST['isRTL'])) {
                $isRTL = sanitize_text_field($_POST['isRTL']);
            }
            amotos_get_template('car/car-print.php', array('car_id' => $car_id, 'isRTL' => $isRTL));
            wp_die();
        }

        /**
         *    set_views_counter
         */
        public function set_views_counter()
        {
            global $post;
            // Is it a single post
            if (is_single() && (get_post_type() == 'car')) {
                // Check if user already visited this page
                $visited_posts = array();
                // Check cookie for list of visited posts
	            $_car_views = isset($_COOKIE['car_views']) ? amotos_clean(wp_unslash($_COOKIE['car_views'])) : '';

                if (!empty($_car_views)) {
                    // We expect list of comma separated post ids in the cookie
                    $visited_posts = array_map('intval', explode(',', $_car_views));
                }
                if (in_array($post->ID, $visited_posts)) {
                    // User already visited this post
                    return;
                }
                // The visitor is reading this post first time, so we count
                // Get current view count
                $views = (int)get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'car_views_count', true);
                // Increase by one and save
                update_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'car_views_count', $views + 1);
                // Add post id and set cookie
                $visited_posts[] = $post->ID;
                // Set cookie for one hour, it should be set on all pages se we use / as path
                setcookie('car_views', implode(',', $visited_posts), time() + 3600, '/');
            }
        }

        /**
         * get_total_views
         * @param null $post_id
         * @return int
         */
        public function get_total_views($post_id = null)
        {
            global $post;
            /**
             * If no given post id, then current post
             */
            if (!$post_id && isset($post->ID)) {
                $post_id = $post->ID;
            }
            if (!$post_id) {
                return 0;
            }
            $views = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_views_count', true);
            return intval($views);
        }

        public function get_states_by_country_ajax()
        {
            if (!isset($_POST['country'])) {
                return;
            }
            $country = amotos_clean(wp_unslash($_POST['country'])) ;
            $type = isset($_POST['type']) ?  amotos_clean(wp_unslash($_POST['type'])) : '';
            if (!empty($country)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-state',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'car_state_country',
                                'value' => $country,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-state',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }

            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'auto-moto-stock') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->term_id)  . '">' . esc_html($term->name)  . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->slug)  . '">' . esc_html($term->name)  . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All States', 'auto-moto-stock') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                 'value' => true,
                 'selected' => true
            )));
            wp_die();
        }

        public function get_cities_by_state_ajax()
        {
            if (!isset($_POST['state'])) {
                return;
            }
            $state = amotos_clean(wp_unslash($_POST['state']));
            $type = isset($_POST['type']) ? amotos_clean(wp_unslash($_POST['type'])) : '';
            if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                $car_state = get_term_by('id', $state, 'car-state');
            }
            else{
                $car_state = get_term_by('slug', $state, 'car-state');
            }

            if (!empty($state) && $car_state) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-city',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'car_city_state',
                                'value' => $car_state->term_id,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-city',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }
            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'auto-moto-stock') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->term_id)  . '">' . esc_html($term->name ) . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->slug ) . '">' . esc_html($term->name ) . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All Cities', 'auto-moto-stock') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                    'value' => true,
                    'selected' => true
                )));
            wp_die();
        }

        public function get_neighborhoods_by_city_ajax()
        {
            if (!isset($_POST['city'])) {
                return;
            }
            $city = amotos_clean(wp_unslash($_POST['city']));
            $type = isset($_POST['type']) ? amotos_clean(wp_unslash($_POST['type'])) : '';
            if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                $car_city = get_term_by('id', $city, 'car-city');
            }
            else{
                $car_city = get_term_by('slug', $city, 'car-city');
            }

            if (!empty($city) && $car_city) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-neighborhood',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'car_neighborhood_city',
                                'value' => $car_city->term_id,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'car-neighborhood',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }

            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'auto-moto-stock') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->term_id)  . '">' . esc_html($term->name)  . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . esc_attr($term->slug)  . '">' . esc_html($term->name)  . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All Neighborhoods', 'auto-moto-stock') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                    'value' => true,
                    'selected' => true
                )));
            wp_die();
        }

        /**
         * submit review
         */
        public function submit_review_ajax()
        {
            check_ajax_referer('amotos_submit_review_ajax_nonce', 'amotos_security_submit_review');
            global $wpdb, $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user = get_user_by('id', $user_id);
            $car_id = isset($_POST['car_id']) ? amotos_clean(wp_unslash($_POST['car_id'])) : '';
            $rating_value = isset($_POST['rating']) ? amotos_clean(wp_unslash($_POST['rating'])) : '';
            $my_review = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = %d AND comment.user_id = %d  AND meta.meta_key = 'car_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC",$car_id,$user_id));
            $comment_approved = 1;
            $auto_publish_review_car = amotos_get_option( 'review_car_approved_by_admin',0 );
            if ($auto_publish_review_car == 1) {
                $comment_approved = 0;
            }
            if ( $my_review === null ) {
                $data = Array();
                $user = $user->data;
                $data['comment_post_ID'] = $car_id;
                $data['comment_content'] = isset($_POST['message']) ?  wp_filter_post_kses($_POST['message']) : '';
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;
                $data['comment_author'] = $user->user_login;
                $data['comment_author_email'] = $user->user_email;
                $data['comment_author_url'] = $user->user_url;
                $data['user_id'] = $user_id;
                $comment_id = wp_insert_comment($data);

                add_comment_meta($comment_id, 'car_rating', $rating_value);
                if ($comment_approved == 1) {
	                do_action('amotos_car_rating_meta',$car_id, $rating_value);
                }
            } else {
                $data = Array();
                $data['comment_ID'] = $my_review->comment_ID;
                $data['comment_post_ID'] = $car_id;
                $data['comment_content'] = isset($_POST['message']) ? wp_filter_post_kses($_POST['message']) : '';
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;

                wp_update_comment($data);
                update_comment_meta($my_review->comment_ID, 'car_rating', $rating_value, $my_review->meta_value);

                if ($comment_approved == 1) {
                    do_action('amotos_car_rating_meta',$car_id, $rating_value, false, $my_review->meta_value);
                }
            }
            wp_send_json_success();
        }

        /**
         * @param $car_id
         * @param $rating_value
         * @param bool|true $comment_exist
         * @param int $old_rating_value
         */
        public function rating_meta_filter($car_id, $rating_value, $comment_exist = true, $old_rating_value = 0)
        {
            $car_rating = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_rating', true);
            if ($comment_exist == true) {
                if (is_array($car_rating) && isset($car_rating[$rating_value])) {
                    $car_rating[$rating_value]++;
                } else {
                    $car_rating = Array();
                    $car_rating[1] = 0;
                    $car_rating[2] = 0;
                    $car_rating[3] = 0;
                    $car_rating[4] = 0;
                    $car_rating[5] = 0;
                    $car_rating[$rating_value]++;
                }
            } else {
                $car_rating[$old_rating_value]--;
                $car_rating[$rating_value]++;
            }
            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_rating', $car_rating);
        }

        /**
         * delete review
         * @param $comment_id
         */
        public function delete_review($comment_id)
        {
            global $wpdb;
            $rating_value = get_comment_meta($comment_id, 'car_rating', true);
            if ($rating_value !== '') {
                $comments = $wpdb->get_row($wpdb->prepare("SELECT comment_post_ID as car_ID FROM $wpdb->comments WHERE comment_ID = %d",$comment_id));
                if ($comments !== null) {
	                $car_id = $comments->car_ID;
	                $car_rating = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_rating', true);
	                if (is_array($car_rating) && isset($car_rating[$rating_value])) {
		                $car_rating[$rating_value]--;
		                update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_rating', $car_rating);
	                }
                }

            }
        }

        /**
         * approve review
         * @param $new_status
         * @param $old_status
         * @param $comment
         */
        public function approve_review($new_status, $old_status, $comment)
        {
            if ($old_status != $new_status) {
                $rating_value = get_comment_meta($comment->comment_ID, 'car_rating', true);
                $car_rating = get_post_meta($comment->comment_post_ID, AMOTOS_METABOX_PREFIX . 'car_rating', true);
				if (!is_array($car_rating)) {
					$car_rating = Array();
					$car_rating[1] = 0;
					$car_rating[2] = 0;
					$car_rating[3] = 0;
					$car_rating[4] = 0;
					$car_rating[5] = 0;
				}
                if (($rating_value !== '') && is_array($car_rating) && isset($car_rating[$rating_value])) {
	                if ($new_status == 'approved') {
		                $car_rating[$rating_value]++;
	                } else {
		                $car_rating[$rating_value]--;
	                }
	                if ($car_rating[$rating_value] < 0) {
		                $car_rating[$rating_value] = 0;
	                }
	                update_post_meta($comment->comment_post_ID, AMOTOS_METABOX_PREFIX . 'car_rating', $car_rating);
                }
            }
        }
    }
}