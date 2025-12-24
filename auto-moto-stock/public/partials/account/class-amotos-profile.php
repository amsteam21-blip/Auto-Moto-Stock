<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Profile')) {
    /**
     * Class AMOTOS_Profile
     */
    class AMOTOS_Profile
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
         * Upload profile avatar
         */
        public function profile_image_upload_ajax()
        {
            // Verify Nonce
            $nonce = isset($_REQUEST['nonce']) ? amotos_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'amotos_allow_upload_nonce')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['amotos_upload_file'];

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

                $ajax_response = array(
                    'success' => true,
                    'url' => $thumbnail_url,
                    'attachment_id' => $attach_id
                );

                echo wp_json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!!', 'auto-moto-stock'));
                echo wp_json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Update profile
         */
        public function update_profile_ajax()
        {
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            check_ajax_referer('amotos_update_profile_ajax_nonce', 'amotos_security_update_profile');

            // Update first name
	        $user_firstname = isset($_POST['user_firstname']) ? amotos_clean(wp_unslash($_POST['user_firstname'])) : '';
            if (!empty($user_firstname)) {
                update_user_meta($user_id, 'first_name', $user_firstname);
            } else {
                delete_user_meta($user_id, 'first_name');
            }

            // Update last name
	        $user_lastname = isset($_POST['user_lastname']) ? amotos_clean(wp_unslash($_POST['user_lastname'])) : '';
            if (!empty($user_lastname)) {
                update_user_meta($user_id, 'last_name', $user_lastname);
            } else {
                delete_user_meta($user_id, 'last_name');
            }

            // Update author_position
	        $user_position = isset($_POST['user_position']) ? amotos_clean(wp_unslash($_POST['user_position'])) : '';
            if (!empty($user_position)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_position', $user_position);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_position');
            }
            // Update author_fax_number
	        $user_fax_number = isset($_POST['user_fax_number']) ? amotos_clean(wp_unslash($_POST['user_fax_number'])) : '';
            if (!empty($user_fax_number)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_fax_number', $user_fax_number);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_fax_number');
            }
            // Update author_company
	        $user_company = isset($_POST['user_company']) ? amotos_clean(wp_unslash($_POST['user_company'])) : '';
            if (!empty($user_company)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_company', $user_company);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_company');
            }

            // Update author_company
	        $user_licenses = isset($_POST['user_licenses']) ? amotos_clean(wp_unslash($_POST['user_licenses'])) : '';
            if (!empty($user_licenses)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_licenses', $user_licenses);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_licenses');
            }

	        $user_office_address = isset($_POST['user_office_address']) ? amotos_clean(wp_unslash($_POST['user_office_address'])) : '';
            if (!empty($user_office_address)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_address', $user_office_address);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_address');
            }

            // Update Phone
	        $user_office_number = isset($_POST['user_office_number']) ? amotos_clean(wp_unslash($_POST['user_office_number'])) : '';
            if (!empty($user_office_number)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_number', $user_office_number);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_number');
            }

            // Update Mobile
	        $user_mobile_number = isset($_POST['user_mobile_number']) ? amotos_clean(wp_unslash($_POST['user_mobile_number'])) : '';
            if (!empty($user_mobile_number)) {
                if ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\/\(\)\.]/', '', $user_mobile_number ) ) ) ) {
                    echo wp_json_encode(array('success' => false, 'message' => esc_html__('The Mobile phone number you entered is not valid. Please try again.', 'auto-moto-stock')));
                    wp_die();
                }
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_mobile_number', $user_mobile_number);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_mobile_number');
            }

            // Update Skype
	        $user_skype = isset($_POST['user_skype']) ? amotos_clean(wp_unslash($_POST['user_skype'])) : '';
            if (!empty($user_skype)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_skype', $user_skype);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_skype');
            }

            // Update facebook
	        $user_facebook_url = isset($_POST['user_facebook_url']) ? sanitize_url(wp_unslash($_POST['user_facebook_url'])) : '';
            if (!empty($user_facebook_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_facebook_url', $user_facebook_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_facebook_url');
            }

            // Update twitter
	        $user_twitter_url = isset($_POST['user_twitter_url']) ? sanitize_url(wp_unslash($_POST['user_twitter_url'])) : '';
            if (!empty($user_twitter_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_twitter_url', $user_twitter_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_twitter_url');
            }

            // Update linkedin
	        $user_linkedin_url = isset($_POST['user_linkedin_url']) ? sanitize_url(wp_unslash($_POST['user_linkedin_url'])) : '';
            if (!empty($user_linkedin_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $user_linkedin_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_linkedin_url');
            }

            // Update instagram
	        $user_instagram_url = isset($_POST['user_instagram_url']) ? sanitize_url(wp_unslash($_POST['user_instagram_url'])) : '';
            if (!empty($user_instagram_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_instagram_url', $user_instagram_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_instagram_url');
            }

            // Update pinterest
	        $user_pinterest_url = isset($_POST['user_pinterest_url']) ? sanitize_url(wp_unslash($_POST['user_pinterest_url'])) : '';
            if (!empty($user_pinterest_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $user_pinterest_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_pinterest_url');
            }

            // Update youtube
	        $user_youtube_url = isset($_POST['user_youtube_url']) ? sanitize_url(wp_unslash($_POST['user_youtube_url'])) : '';
            if (!empty($user_youtube_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_youtube_url', $user_youtube_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_youtube_url');
            }

            // Update vimeo
	        $user_vimeo_url = isset($_POST['user_vimeo_url']) ? sanitize_url(wp_unslash($_POST['user_vimeo_url'])) : '';
            if (!empty($user_vimeo_url)) {
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $user_vimeo_url);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_vimeo_url');
            }


            // Update Profile Picture
	        $profile_pic_id = isset($_POST['profile_pic']) ? amotos_clean(wp_unslash($_POST['profile_pic'])) : '';
            if (!empty($profile_pic_id)) {
                $profile_pic = wp_get_attachment_url($profile_pic_id);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_custom_picture', $profile_pic);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_picture_id', $profile_pic_id);
            } else {
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_custom_picture');
                delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_picture_id');
            }
            // Update About
	        $user_des = isset($_POST['user_des']) ? sanitize_textarea_field(wp_unslash($_POST['user_des'])) : '';
	        wp_update_user(array('ID' => $user_id, 'description' => $user_des));

            // Update website
	        $user_website_url = isset($_POST['user_website_url']) ? sanitize_url(wp_unslash($_POST['user_website_url'])) : '';
	        wp_update_user(array('ID' => $user_id, 'user_url' => $user_website_url));

            // Update email
	        $user_email = isset($_POST['user_email']) ? sanitize_email(wp_unslash($_POST['user_email'])) : '';
            if (!empty($user_email)) {
                $user_email = is_email($user_email);
                if (!$user_email) {
                    echo wp_json_encode(array('success' => false, 'message' => esc_html__('The Email you entered is not valid. Please try again.', 'auto-moto-stock')));
                    wp_die();
                } else {
                    $email_exists = email_exists($user_email);
                    if ($email_exists) {
                        if ($email_exists != $user_id) {
                            echo wp_json_encode(array('success' => false, 'message' => esc_html__('This Email is already used by another user. Please try a different one.', 'auto-moto-stock')));
                            wp_die();
                        }
                    } else {
                        $return = wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
                        if (is_wp_error($return)) {
                            $error = $return->get_error_message();
                            echo esc_html($error);
                            wp_die();
                        }
                    }
                }
            }
            $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
            $user_as_manager = amotos_get_option('user_as_manager', 1);
            if ($user_as_manager == 1 && !empty($manager_id) && (get_post_type($manager_id) == 'manager')) {
                if (!empty($user_firstname) || !empty($user_lastname)) {
                    wp_update_post(array(
                        'ID' => $manager_id,
                        'post_title' => $user_firstname . ' ' . $user_lastname,
                        'post_content' => $user_des
                    ));
                }
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_description', $user_des);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_position', $user_position);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_email', $user_email);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_mobile_number', $user_mobile_number);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_fax_number', $user_fax_number);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_company', $user_company);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_number', $user_office_number);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_address', $user_office_address);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_licenses', $user_licenses);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_facebook_url', $user_facebook_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_twitter_url', $user_twitter_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_linkedin_url', $user_linkedin_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_pinterest_url', $user_pinterest_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_instagram_url', $user_instagram_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_skype', $user_skype);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_youtube_url', $user_youtube_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_vimeo_url', $user_vimeo_url);
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_website_url', $user_website_url);
                update_post_meta($manager_id, '_thumbnail_id', $profile_pic_id);
            }
            echo wp_json_encode(array('success' => true, 'message' => esc_html__('Profile updated', 'auto-moto-stock')));
            do_action('amotos_update_profile_ajax_success', $user_id);
            wp_die();
        }

        /**
         * Register user as seller
         */
        public function leave_manager_ajax()
        {
            check_ajax_referer('amotos_leave_manager_ajax_nonce', 'amotos_security_leave_manager');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
            if (!empty($manager_id) && (get_post_type($manager_id) == 'manager')) {
                wp_delete_post($manager_id);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_manager_id', '');
            }
            $ajax_response = array('success' => true, 'message' => esc_html__('Success!', 'auto-moto-stock'));
            echo wp_json_encode($ajax_response);
            wp_die();
        }

        public function register_user_as_manager($user_id) {
			if (amotos_is_manager()) {
				$ajax_response = array('success' => true, 'message' => esc_html__('You are already an manager, you cannot register as an manager again!', 'auto-moto-stock'));
				return $ajax_response;
			}

			if (amotos_is_manager_pending()) {
				$ajax_response = array('success' => true, 'message' => esc_html__('You have successfully registered and is pending approval by an admin!', 'auto-moto-stock'));
				return $ajax_response;
			}



	        $user = get_user_by( 'id', $user_id );

	        $full_name = $user->user_login;
	        $manager_firstname = $user->first_name;
	        $manager_lastname = $user->last_name;
	        $manager_description = $user->description;
	        if (!empty($manager_firstname) || !empty($manager_lastname)) {
		        $full_name = $manager_firstname . ' ' . $manager_lastname;
	        }
	        $post_status = 'publish';
	        $auto_approved_manager = amotos_get_option('auto_approved_manager', 1);
	        if ($auto_approved_manager != 1) {
		        $post_status = 'pending';
	        }
	        //Insert Manager
	        $manager_id = wp_insert_post(array(
		        'post_title' => $full_name,
		        'post_type' => 'manager',
		        'post_status' => $post_status,
		        'post_content' => $manager_description
	        ));
	        if ($manager_id > 0) {
		        if ($auto_approved_manager != 1) {
			        $args = array(
				        'manager_name' => $full_name,
				        'manager_url' => get_permalink($manager_id)
			        );
			        $admin_email = get_bloginfo('admin_email');
			        amotos_send_email($admin_email, 'admin_mail_approved_manager', $args);
		        }
		        update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_manager_id', $manager_id);
		        $manager_email = $user->user_email;
		        $manager_website_url = $user->user_url;
		        $manager_mobile_number = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_mobile_number', $user_id);
		        $manager_fax_number = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_fax_number', $user_id);
		        $manager_company = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_company', $user_id);
		        $manager_licenses = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_licenses', $user_id);
		        $manager_office_number = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_office_number', $user_id);
		        $manager_office_address = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_office_address', $user_id);
		        $manager_facebook_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_facebook_url', $user_id);
		        $manager_twitter_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_twitter_url', $user_id);
		        $manager_linkedin_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $user_id);
		        $manager_pinterest_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $user_id);
		        $manager_instagram_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_instagram_url', $user_id);
		        $manager_youtube_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_youtube_url', $user_id);
		        $manager_vimeo_url = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $user_id);
		        $manager_skype = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_skype', $user_id);
		        $manager_position = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_position', $user_id);
		        $author_picture_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_picture_id', $user_id);

		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', $user_id);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_description', $manager_description);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_position', $manager_position);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_email', $manager_email);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_mobile_number', $manager_mobile_number);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_fax_number', $manager_fax_number);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_company', $manager_company);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_licenses', $manager_licenses);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_number', $manager_office_number);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_address', $manager_office_address);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_facebook_url', $manager_facebook_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_twitter_url', $manager_twitter_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_linkedin_url', $manager_linkedin_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_pinterest_url', $manager_pinterest_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_instagram_url', $manager_instagram_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_skype', $manager_skype);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_youtube_url', $manager_youtube_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_vimeo_url', $manager_vimeo_url);
		        update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_website_url', $manager_website_url);
		        update_post_meta($manager_id, '_thumbnail_id', $author_picture_id);
		        if ($auto_approved_manager != 1) {
			        $ajax_response = array('success' => true, 'message' => esc_html__('You have successfully registered and is pending approval by an admin!', 'auto-moto-stock'));
		        } else {
			        $ajax_response = array('success' => true, 'message' => esc_html__('You have successfully registered!', 'auto-moto-stock'));
		        }
	        } else {
		        $ajax_response = array('success' => true, 'message' => esc_html__('Failed!', 'auto-moto-stock'));
	        }

            return $ajax_response;
        }

        /**
         * Register user as seller
         */
        public function register_user_as_manager_ajax()
        {
            check_ajax_referer('amotos_become_manager_ajax_nonce', 'amotos_security_become_manager');
            $user_as_manager = amotos_get_option('user_as_manager', 1);
            if ($user_as_manager == 1) {
                global $current_user;
                wp_get_current_user();
                $user_id = $current_user->ID;

	            $ajax_response = $this->register_user_as_manager($user_id);
            } else {
                $ajax_response = array('success' => false, 'message' => esc_html__('Failed!', 'auto-moto-stock'));
            }
            echo wp_json_encode($ajax_response);
            wp_die();
        }

        /**
         * Change password
         */
        public function change_password_ajax()
        {
            check_ajax_referer('amotos_change_password_ajax_nonce', 'amotos_security_change_password');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $oldpass = isset($_POST['oldpass']) ? amotos_clean(wp_unslash($_POST['oldpass'])) : '';
            $newpass = isset($_POST['newpass']) ? amotos_clean(wp_unslash($_POST['newpass'])) : '';
            $confirmpass = isset($_POST['confirmpass']) ? amotos_clean(wp_slash($_POST['confirmpass'])) : '';

            if ($newpass == '' || $confirmpass == '') {
                echo wp_json_encode(array('success' => false, 'message' => esc_html__('New password or confirm password is blank', 'auto-moto-stock')));
                wp_die();
            }
            if ($newpass != $confirmpass) {
                echo wp_json_encode(array('success' => false, 'message' => esc_html__('Passwords do not match', 'auto-moto-stock')));
                wp_die();
            }

            $user = get_user_by('id', $user_id);
            if ($user && wp_check_password($oldpass, $user->data->user_pass, $user_id)) {
                wp_set_password($newpass, $user_id);
                echo wp_json_encode(array('success' => true, 'message' => esc_html__('Password Updated', 'auto-moto-stock')));
            } else {
                echo wp_json_encode(array('success' => false, 'message' => esc_html__('Old password is not correct', 'auto-moto-stock')));
            }
            wp_die();
        }

        public function profile_update($user_id)
        {
            $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
            if (!empty($manager_id)) {
                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', $user_id);
            }
        }

        /**
         * Check package available
         *
         * @param $user_id
         * @return int
         */
        public function user_package_available($user_id)
        {
            $package_available_result = 1;

            $package_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_id', $user_id);
            if (!amotos_package_is_visible($package_id)) {
	            $package_available_result = 0;
            } else {
                $amotos_package = new AMOTOS_Package();
                $package_unlimited_time = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_time', true);
                if ($package_unlimited_time == 0) {
                    $expired_date = $amotos_package->get_expired_time($package_id, $user_id);
                    $today = time();
                    if ($today > $expired_date) {
	                    $package_available_result = -1;
                    }
                }
                if ($package_available_result != -1) {
	                $package_num_cars = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_listings', $user_id);
	                if ($package_num_cars != -1 && $package_num_cars < 1) {
		                $package_available_result = -2;
	                }
                }
            }

            return apply_filters('amotos_user_package_available', $package_available_result, $user_id);
        }

        public function custom_user_profile_fields($user)
        {
            $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user->ID);
            $is_manager=(!empty($manager_id) && (get_post_type($manager_id) == 'manager'));
            $picture_url=get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_custom_picture', $user->ID);
            if(empty($picture_url)&& $is_manager)
            {
                $picture_url=get_the_post_thumbnail_url($manager_id);
            }
            ?>
            <h3><?php esc_html_e('Profile Info', 'auto-moto-stock'); ?></h3>
            <table class="form-table">
                <tbody>
                <tr class="author-custom-picture-wrap">
                    <th><label><?php echo esc_html__('Profile Picture', 'auto-moto-stock'); ?></label></th>
                    <td>
                        <img width="96px"
                             src="<?php echo esc_url($picture_url); ?>">
                    </td>
                </tr>
                <tr class="author-mobile-number-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_mobile_number');?>"><?php echo esc_html__('Mobile', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_mobile_number');?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_mobile_number'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_mobile_number', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-fax-number-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_fax_number'); ?>"><?php echo esc_html__('Fax Number', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_fax_number'); ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_fax_number'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_fax_number', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-skype-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_skype'); ?>"><?php echo esc_html__('Skype', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_skype'); ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_skype') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_skype', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <?php
                if ($is_manager):?>
                    <tr class="author-company-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_company'); ?>"><?php echo esc_html__('Company Name', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_company'); ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_company') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_company', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author_position-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_position'); ?>"><?php esc_html_e('Position', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_position'); ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_position'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_position', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-office-address-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_address'); ?>"><?php echo esc_html__('Office Address', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_address') ; ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_address'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_office_address', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-office-number-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_number') ; ?>"><?php echo esc_html__('Office Number', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_number'); ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_office_number'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_office_number', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-licenses-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_licenses') ; ?>"><?php echo esc_html__('Licenses', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_licenses') ; ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_licenses') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_licenses', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                <?php endif; ?>
                    <tr class="author-manager-id-wrap">
                        <th><label
                                for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_manager_id') ; ?>"><?php echo esc_html__('Manager Id', 'auto-moto-stock'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_manager_id') ; ?>"
                                   id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_manager_id') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <?php
            $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
            if ($paid_submission_type == 'per_package'):
                $package_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_id', $user->ID);
                if (amotos_package_is_visible($package_id)):
                    $package_remaining_listings = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_listings', $user->ID);
                    $package_featured_remaining_listings = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_featured', $user->ID);
                    if ($package_remaining_listings == -1) {
                        $package_remaining_listings = esc_html__('Unlimited', 'auto-moto-stock');
                    }
                    $package_title = get_the_title($package_id);
                    $package_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true);
                    $package_unlimited_listing = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_listing', true);
                    $package_featured_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true);
                    $amotos_package = new AMOTOS_Package();
                    $expired_date = $amotos_package->get_expired_date($package_id, $user->ID);
                    ?>
                    <h2><?php echo esc_html__('Package Info', 'auto-moto-stock'); ?></h2>
                    <table class="form-table">
                        <tbody>
                        <tr class="user-package-id-wrap">
                            <th><label><?php echo esc_html__('Package Id', 'auto-moto-stock'); ?></label></th>
                            <td><?php echo esc_html($package_id); ?></td>
                        </tr>
                        <tr class="user-package-name-wrap">
                            <th><label><?php echo esc_html__('Package Name', 'auto-moto-stock'); ?></label></th>
                            <td><?php echo esc_html($package_title); ?></td>
                        </tr>
                        <tr class="user-package-remaining-listings-wrap">
                            <th><label><?php echo esc_html__('Listings Included', 'auto-moto-stock'); ?></label>
                            </th>
                            <td><?php if ($package_unlimited_listing == 1) {
                                    echo wp_kses_post($package_remaining_listings);
                                } else {
                                    echo esc_html($package_listings);
                                }
                                ?></td>
                        </tr>
                        <tr class="user-package-remaining-listings-wrap">
                            <th><label><?php echo esc_html__('Listings Remaining', 'auto-moto-stock'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_remaining_listings); ?></td>
                        </tr>
                        <tr class="user-package-featured-wrap">
                            <th><label><?php echo esc_html__('Featured Included', 'auto-moto-stock'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_featured_listings); ?></td>
                        </tr>
                        <tr class="user-package-remaining-wrap">
                            <th><label><?php echo esc_html__('Featured Remaining', 'auto-moto-stock'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_featured_remaining_listings); ?></td>
                        </tr>
                        <tr class="user-package-end-date-wrap">
                            <th><label><?php echo esc_html__('End Date', 'auto-moto-stock'); ?></label></th>
                            <td><?php echo esc_html($expired_date); ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php endif;
            endif; ?>
            <h2><?php echo esc_html__('Social Profiles', 'auto-moto-stock'); ?></h2>
            <table class="form-table">
                <tbody>
                <tr class="author-facebook-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_facebook_url') ; ?>"><?php echo esc_html__('Facebook', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_facebook_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_facebook_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_facebook_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-twitter-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_twitter_url') ; ?>"><?php echo esc_html__('Twitter', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_twitter_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_twitter_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_twitter_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-linkedin-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_linkedin_url') ; ?>"><?php echo esc_html__('LinkedIn', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_linkedin_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_linkedin_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-pinterest-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_pinterest_url'); ?>"><?php echo esc_html__('Pinterest', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_pinterest_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_pinterest_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-instagram-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_instagram_url') ; ?>"><?php echo esc_html__('Instagram', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_instagram_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_instagram_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_instagram_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-youtube-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_youtube_url') ; ?>"><?php echo esc_html__('Youtube', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_youtube_url'); ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_youtube_url'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_youtube_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-vimeo-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_vimeo_url'); ?>"><?php echo esc_html__('Vimeo', 'auto-moto-stock'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_vimeo_url') ; ?>"
                               id="<?php echo esc_attr(AMOTOS_METABOX_PREFIX . 'author_vimeo_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        public function update_custom_user_profile_fields($user_id)
        {
            if (current_user_can('edit_user', $user_id)) {

                $author_mobile_number = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_mobile_number']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_mobile_number'])) : '';
                $author_fax_number = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_fax_number']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_fax_number'])) : '';
                $author_skype = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_skype']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_skype'])) : '';
                $author_facebook_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_facebook_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_facebook_url'])) : '';
                $author_twitter_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_twitter_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_twitter_url'])) : '';
                $author_linkedin_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_linkedin_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_linkedin_url'])) : '';
                $author_pinterest_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_pinterest_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_pinterest_url'])) : '';
                $author_instagram_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_instagram_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_instagram_url'])) : '';
                $author_youtube_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_youtube_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_youtube_url'])) : '';
                $author_vimeo_url = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_vimeo_url']) ? sanitize_url(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_vimeo_url'])) : '';

                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_mobile_number', $author_mobile_number);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_fax_number', $author_fax_number);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_skype', $author_skype);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_facebook_url', $author_facebook_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_twitter_url', $author_twitter_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $author_linkedin_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $author_pinterest_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_instagram_url', $author_instagram_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_youtube_url', $author_youtube_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $author_vimeo_url);
                $manager_id = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_manager_id']) ? absint(amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_manager_id']))) : 0;
                if ($manager_id > 0 && get_post_type($manager_id) == 'manager') {

                    $author_company = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_company']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_company'])) : '';
                    $author_position = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_position']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_position'])) : '';
                    $author_office_address = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_office_address']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_office_address'])) : '';
                    $author_office_number = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_office_number']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_office_number'])) : '';
                    $author_licenses = isset($_POST[AMOTOS_METABOX_PREFIX . 'author_licenses']) ? amotos_clean(wp_unslash($_POST[AMOTOS_METABOX_PREFIX . 'author_licenses'])) : '';
                    $description = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';
                    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
                    $manager_website_url = isset($_POST['url']) ? sanitize_url(wp_unslash($_POST['url'])) : '';

                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_manager_id', $manager_id);
                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_company', $author_company);
                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_position', $author_position);
                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_address', $author_office_address);
                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_number', $author_office_number);
                    update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_licenses', $author_licenses);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_description', $description);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_email', $email);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_website_url', $manager_website_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_position', $author_position);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_mobile_number', $author_mobile_number);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_fax_number', $author_fax_number);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_company', $author_company);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_number', $author_office_address);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_office_address', $author_office_number);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_licenses', $author_licenses);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_skype', $author_skype);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_facebook_url', $author_facebook_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_twitter_url', $author_twitter_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_linkedin_url', $author_linkedin_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_pinterest_url', $author_pinterest_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_instagram_url', $author_instagram_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_youtube_url', $author_youtube_url);
                    update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_vimeo_url', $author_vimeo_url);
                }
            }
        }
    }
}