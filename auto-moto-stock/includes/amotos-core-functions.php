<?php
/**
 * Core functions
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Get Option
 */
if (!function_exists('amotos_get_option')) {
    function amotos_get_option($key, $default = '')
    {
        $option = get_option(AMOTOS_OPTIONS_NAME);
        return (isset($option[$key])) ? $option[$key] : $default;
    }
}
/**
 * Get template part (for templates like the shop-loop).
 *
 * AMOTOS_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
if (!function_exists('amotos_get_template_part')) {
    function amotos_get_template_part($slug, $name = '')
    {
        $template = '';
        if ($name) {
            $template = locate_template(array("{$slug}-{$name}.php", AMOTOS()->template_path() . "{$slug}-{$name}.php"));
        }

        // Get default slug-name.php
        if (!$template && $name && file_exists(AMOTOS_PLUGIN_DIR . "/public/templates/{$slug}-{$name}.php")) {
            $template = AMOTOS_PLUGIN_DIR . "/public/templates/{$slug}-{$name}.php";
        }

        if (!$template) {
            $template = locate_template(array("{$slug}.php", AMOTOS()->template_path() . "{$slug}.php"));
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters('amotos_get_template_part', $template, $slug, $name);

        if ($template) {
            load_template($template, false);
        }
    }
}
/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
if (!function_exists('amotos_get_template')) {
    function amotos_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
    {

        $located = amotos_locate_template($template_name, $template_path, $default_path);

        if (!file_exists($located)) {
            return;
        }

        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters('amotos_get_template', $located, $template_name, $args, $template_path, $default_path);

	    $action_args = array(
		    'template_name' => $template_name,
		    'located'       => $located,
		    'args'          => $args,
		    'template_path' => $template_path
	    );

	    if ( ! empty( $args ) && is_array( $args ) ) {
		    if ( isset( $args['action_args'] ) ) {
			    unset( $args['action_args'] );
		    }
		    extract( $args ); // @codingStandardsIgnoreLine
	    }

        do_action('amotos_before_template_part', $action_args['template_name'],$action_args['template_path'], $action_args['located'] , $action_args['args']);

        include($action_args['located']);

	    do_action('amotos_after_template_part', $action_args['template_name'],$action_args['template_path'], $action_args['located'] , $action_args['args']);
    }
}
/**
 * Like amotos_get_template, but returns the HTML instead of outputting.
 * @see amotos_get_template
 * @since 1.0.0
 * @param string $template_name
 */
if (!function_exists('amotos_get_template_html')) {
    function amotos_get_template_html($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        ob_start();
        amotos_get_template($template_name, $args, $template_path, $default_path);
        return ob_get_clean();
    }
}
/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *        yourtheme        /    $template_path    /    $template_name
 *        yourtheme        /    $template_name
 *        $default_path    /    $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
if (!function_exists('amotos_locate_template')) {
    function amotos_locate_template($template_name, $template_path = '', $default_path = '')
    {
        if (!$template_path) {
            $template_path = AMOTOS()->template_path();
        }

        if (!$default_path) {
            $default_path = AMOTOS_PLUGIN_DIR . '/public/templates/';
        }

        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
                $template_name
            )
        );

        // Get default template/
        if (!$template) {
            $template = $default_path . $template_name;
        }

        // Return what we found.
        return apply_filters('amotos_locate_template', $template, $template_name, $template_path);
    }
}
/**
 * Check user as manager
 */
if (!function_exists('amotos_is_manager')) {
    function amotos_is_manager()
    {
        global $current_user;
        wp_get_current_user();
        $user_id = $current_user->ID;
        $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
        if (!empty($manager_id) && (get_post_type($manager_id) == 'manager') && (get_post_status($manager_id) == 'publish')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('amotos_is_manager_pending')) {
	function amotos_is_manager_pending() {
		global $current_user;
		wp_get_current_user();
		$user_id = $current_user->ID;
		$manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
		if (!empty($manager_id) && (get_post_type($manager_id) == 'manager') && (get_post_status($manager_id) == 'pending')) {
			return true;
		}
		return false;
	}
}

if (!function_exists('amotos_allow_submit')){
    function amotos_allow_submit(){
        $enable_submit_car_via_frontend = amotos_get_option('enable_submit_car_via_frontend', 1);
        $user_can_submit = amotos_get_option('user_can_submit', 1);
        $is_manager = amotos_is_manager();

        $allow_submit=true;
        if($enable_submit_car_via_frontend!=1)
        {
            $allow_submit=false;
        }
        else{
            if(!$is_manager && $user_can_submit!=1)
            {
                $allow_submit=false;
            }
        }
        return $allow_submit;
    }
}

/**
 * Get page id
 */
if (!function_exists('amotos_get_page_id')) {
    function amotos_get_page_id($page)
    {
        $page_id = amotos_get_option('amotos_' . $page . '_page_id');
        if ($page_id) {
            return absint(function_exists('pll_get_post') ? pll_get_post($page_id) : $page_id);
        } else {
            return 0;
        }
    }
}

/**
 * Get permalink
 */
if (!function_exists('amotos_get_permalink')) {
    function amotos_get_permalink($page)
    {
        if ($page_id = amotos_get_page_id($page)) {
            return get_permalink($page_id);
        } else {
            return false;
        }
    }
}

if (!function_exists('amotos_get_page_title')) {
	function amotos_get_page_title($page)
	{
		if ($page_id = amotos_get_page_id($page)) {
			return get_the_title($page_id);
		} else {
			return '';
		}
	}
}

/**
 * Format money
 */
if (!function_exists('amotos_get_format_money')) {
	function amotos_get_format_money($money = '', $price_unit='',$decimals = 0,$small_sign=false)
	{

		$money = doubleval($money);
		$currency = amotos_get_option('currency_sign', esc_html__('$', 'auto-moto-stock'));

		$dec_point = amotos_get_price_decimal_separator();
		$thousands_sep = amotos_get_option('thousand_separator', ',');
		if ($decimals == 0) {
			$decimals = amotos_get_option('number_of_decimals', 0);
		}

		$thousand_text_default=esc_html__('thousand', 'auto-moto-stock');
		$thousand_text = amotos_get_option('thousand_text', $thousand_text_default);

		$million_text_default=esc_html__('million', 'auto-moto-stock');
		$million_text= amotos_get_option('million_text', $million_text_default);

        $is_rtl = amotos_is_rtl();

		$currency_position = amotos_get_option('currency_position', 'before');

		$currency_data = apply_filters('amotos_currency_data', array(
			'money' => $money,
			'currency' => $currency,
			'dec_point' => $dec_point,
			'thousands_sep' => $thousands_sep,
			'decimals' => $decimals,
			'thousand_text' => $thousand_text,
			'million_text' => $million_text,
			'enable_rtl_mode' => $is_rtl,
			'currency_position' => $currency_position
		));
		extract($currency_data);

		$formatted_price=$money;

		if($price_unit=='')
		{
			$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);
		}
		else
		{
			$price_unit = intval($price_unit);

			$unit_text='';
			switch ($price_unit) {
				case 1000:
					$unit_text= $thousand_text;
					break;
				case 1000000:
					$unit_text= $million_text;
					break;
			}
			if($unit_text!='')
			{
				$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);

				if ($is_rtl) {
					$formatted_price=' '.$unit_text. ' '. $formatted_price;
				}
				else{
					$formatted_price=$formatted_price.' '.$unit_text .' ';
				}
			}
			else
			{
				$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);
			}
		}

		if($small_sign==true)
		{
			$currency='<sup>' . esc_html($currency) . '</sup>';
		}

		if ($currency_position == 'before') {
			$currency =  $currency . $formatted_price;
		} else {
			$currency =  $formatted_price . $currency;
		}
		return apply_filters('amotos_format_money',$currency,$money, $price_unit,$decimals,$small_sign) ;
	}
}

/**
 * Get format money search field
 */
if (!function_exists('amotos_get_format_money_search_field')) {
    function amotos_get_format_money_search_field($money)
    {
        $enable_price_number_short_scale= amotos_get_option('enable_price_number_short_scale', 0);
        if($enable_price_number_short_scale==0)
        {
            return amotos_get_format_money($money);
        }
        else
        {
            $money = doubleval($money);
            if ($money) {
                $million=$money/1000000;
                $thousand=$money/1000;
                $formatted_price=$money;
                $unit_text='';
                if($million>=1)
                {
                    $unit_text= esc_html__('million', 'auto-moto-stock');
                    $formatted_price=$million;
                }
                elseif($thousand>=1)
                {
                    $unit_text= esc_html__('thousand', 'auto-moto-stock');
                    $formatted_price=$thousand;
                }
                $formatted_price=$formatted_price.' '.$unit_text .' ';
                $is_rtl = amotos_is_rtl();
                if ($is_rtl) {
                    $formatted_price=' '.$unit_text. ' '. $formatted_price;
                }
                $currency = amotos_get_option('currency_sign', esc_html__('$', 'auto-moto-stock'));
                $currency_position = amotos_get_option('currency_position', 'before');
                if ($currency_position == 'before') {
                    return $currency . $formatted_price;
                } else {
                    return $formatted_price . $currency;
                }

            } else {
                $currency = 0;
            }
            return $currency;
        }
    }
}

/**
 * Get format number
 */
function amotos_get_format_number($number,$decimals = 0)
{
	if ($number === '') {
		return 0;
	}

	$number_floor = floor($number);

	$dec_point = amotos_get_price_decimal_separator();
	$thousands_sep = amotos_get_option('thousand_separator', ',');
	$number_floor =  number_format($number_floor, $decimals, $dec_point, $thousands_sep);

	$number_decimal = $number . '';
	$number_decimal_index = strpos($number_decimal, $dec_point);

	if ($number_decimal_index !== false) {
		$number_decimal = substr($number_decimal, $number_decimal_index + 1);
		if ($number_decimal !== '') {
			for ($i = strlen($number_decimal) - 1; $i >= 0; $i--) {
				if ($number_decimal[$i] !== '0') {
					break;
				}
			}
			$number_decimal = substr($number_decimal, 0, $i+1);
		}
	}
	else {
		$number_decimal = '';
	}

	return $number_decimal === '' ? $number_floor : $number_floor . $dec_point . $number_decimal;
}

/**
 * Image resize by url
 */
if (!function_exists('amotos_image_resize_url')) {
    function amotos_image_resize_url($url, $width = NULL, $height = NULL, $crop = true, $retina = false)
    {
        global $wpdb;

        if (empty($url))
            return new WP_Error('no_image_url', esc_html__('No image URL has been entered.', 'auto-moto-stock'), $url);

        if (class_exists('Jetpack') && method_exists('Jetpack', 'get_active_modules') && in_array('photon', Jetpack::get_active_modules())) {
            $args_crop = array(
                'resize' => $width . ',' . $height,
                'crop' => '0,0,' . $width . 'px,' . $height . 'px'
            );
            $url = jetpack_photon_url($url, $args_crop);
        }

        // Get default size from database
        $width = ($width) ? $width : get_option('thumbnail_size_w');
        $height = ($height) ? $height : get_option('thumbnail_size_h');

        // Allow for different retina sizes
        $retina = $retina ? ($retina === true ? 2 : $retina) : 1;

        // Get the image file path
        $file_path = wp_parse_url($url);

        $file_path = sanitize_text_field($_SERVER['DOCUMENT_ROOT']) . $file_path['path'];
        $wp_upload_dir = wp_upload_dir();
        $wp_upload_folder = $wp_upload_dir['basedir'];

        $file_path = explode('/uploads/', $file_path);

        if (is_array($file_path)) {
            if (count($file_path) > 1) {
                $file_path = $wp_upload_folder . '/' . $file_path[1];
            } elseif (count($file_path) > 0) {
                $file_path = $wp_upload_folder . '/' . $file_path[0];
            } else {
                $file_path = '';
            }
        }

        // Check for Multisite
        if (is_multisite()) {
            global $blog_id;
            $file_path = str_replace("/sites/{$blog_id}/sites/{$blog_id}/", "/sites/{$blog_id}/", $file_path);
            $wp_upload_dir = wp_get_upload_dir();
        }


        // Destination width and height variables
        $dest_width = $width * $retina;
        $dest_height = $height * $retina;

        // File name suffix (appended to original file name)
        $suffix = "{$dest_width}x{$dest_height}";

        // Some additional info about the image
        $info = pathinfo($file_path);
        $dir = $info['dirname'];
        $ext = $info['extension'];
        $name = wp_basename($file_path, ".$ext");

        if ('bmp' == $ext) {
            return new WP_Error('bmp_mime_type', esc_html__('Image is BMP. Please use either JPG or PNG.', 'auto-moto-stock'), $url);
        }


	    $file_name = "{$name}-{$suffix}.{$ext}";
	    $file_name = sanitize_file_name($file_name);

        // Get the destination file name
        $dest_file_name = "{$dir}/{$file_name}";
        if (!file_exists($dest_file_name)) {

            /**
             *  Bail if this image isn't in the Media Library.
             *  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
             */
            $attachment = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT ID as post_id FROM $wpdb->posts WHERE guid = %s",
                    $url
                )
            );
            if (!$attachment) {
                $relative_file_path = str_replace($wp_upload_dir['baseurl'] . '/', '', $url);
                $attachment = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
                        $relative_file_path
                    )
                );
            }

            if ($attachment) {
                $attachment_id = $attachment->post_id;
            } else {
                return array('url' => $url, 'width' => $width, 'height' => $height);
            }

            // Load WordPress Image Editor
            $editor = wp_get_image_editor($file_path);
            if (is_wp_error($editor))
                return array('url' => $url, 'width' => $width, 'height' => $height);

            // Get the original image size
            $size = $editor->get_size();
            $orig_width = $size['width'];
            $orig_height = $size['height'];

            $src_x = $src_y = 0;
            $src_w = $orig_width;
            $src_h = $orig_height;

            if ($crop) {

                $cmp_x = $orig_width / $dest_width;
                $cmp_y = $orig_height / $dest_height;

                // Calculate x or y coordinate, and width or height of source
                if ($cmp_x > $cmp_y) {
                    $src_w = round($orig_width / $cmp_x * $cmp_y);
                    $src_x = round(($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
                } else if ($cmp_y > $cmp_x) {
                    $src_h = round($orig_height / $cmp_y * $cmp_x);
                    $src_y = round(($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
                }
            }

            // Time to crop the image!
            $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);

            // Now let's save the image
            $saved = $editor->save($dest_file_name);

            if (is_a($saved, 'WP_Error')) {
	            $image_array = array(
		            'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
		            'width' => $dest_width,
		            'height' => $dest_height,
		            'type' => $ext
	            );
            } else {
	            // Get resized image information
	            $resized_url = str_replace(wp_basename($url), wp_basename($saved['path']), $url);
	            $resized_width = $saved['width'];
	            $resized_height = $saved['height'];
	            $resized_type = $saved['mime-type'];

	            // Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
	            $metadata = wp_get_attachment_metadata($attachment_id);
	            if (isset($metadata['image_meta'])) {
		            $metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
		            wp_update_attachment_metadata($attachment_id, $metadata);
	            }

	            // Create the image array
	            $image_array = array(
		            'url' => $resized_url,
		            'width' => $resized_width,
		            'height' => $resized_height,
		            'type' => $resized_type
	            );
            }

        } else {
            $image_array = array(
                'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
                'width' => $dest_width,
                'height' => $dest_height,
                'type' => $ext
            );
        }

        // Return image array
        return $image_array;
    }
}

/**
 * Image resize by id
 */
if (!function_exists('amotos_image_resize_id')) {
    function amotos_image_resize_id($images_id, $width = NULL, $height = NULL, $crop = true, $retina = false)
    {
        $output = '';
        $image_src = wp_get_attachment_image_src($images_id, 'full');
        if (is_array($image_src)) {
            $resize = amotos_image_resize_url($image_src[0], $width, $height, $crop, $retina);
            if ($resize != null && is_array($resize)) {
                $output = $resize['url'];
            }
        }
        return $output;
    }
}
add_action( 'delete_attachment', 'amotos_delete_resized_images' );

/**
 * Delete resized images
 */
if (!function_exists('amotos_delete_resized_images')) {
    function amotos_delete_resized_images($post_id)
    {
        // Get attachment image metadata
        $metadata = wp_get_attachment_metadata($post_id);
        if (!$metadata)
            return;

        // Do some bailing if we cannot continue
        if (!isset($metadata['file']) || !isset($metadata['image_meta']['resized_images']))
            return;
        $pathinfo = pathinfo($metadata['file']);
        $resized_images = $metadata['image_meta']['resized_images'];

        // Get Wordpress uploads directory (and bail if it doesn't exist)
        $wp_upload_dir = wp_upload_dir();
        $upload_dir = $wp_upload_dir['basedir'];
        if (!is_dir($upload_dir))
            return;

        // Delete the resized images
        foreach ($resized_images as $dims) {

            // Get the resized images filename
            $file = $upload_dir . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];

            // Delete the resized image
            wp_delete_file($file);
        }
    }
}

/**
 * Admin taxonomy terms
 */
if (!function_exists('amotos_admin_taxonomy_terms')) {
    function amotos_admin_taxonomy_terms($post_id, $taxonomy, $post_type)
    {
        $terms = get_the_terms($post_id, $taxonomy);

        if (!empty ($terms)) {
            $results = array();
            foreach ($terms as $term) {
                $results[] = sprintf('<a href="%s">%s</a>',
                    esc_url(add_query_arg(array('post_type' => $post_type, $taxonomy => $term->slug), 'edit.php')),
                    esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'display'))
                );
            }
            return join(', ', $results);
        }

        return false;
    }
}

/**
 * Send email
 */
if (!function_exists('amotos_send_email')) {
    function amotos_send_email($email, $email_type, $args = array())
    {
        global $amotos_background_emailer;
        $args['user_lang'] = apply_filters( 'wpml_current_language', NULL );
        $amotos_background_emailer->push_to_queue( array( 'email' => $email, 'email_type' =>$email_type, 'args'=>$args) );
    }
}

/**
 * Required field
 */
if (!function_exists('amotos_required_field')) {
    function amotos_required_field($field)
    {
        $required_fields = amotos_get_option('required_fields', array('car_title', 'car_type', 'car_price', 'car_map_address'));
        if (is_array($required_fields) && in_array($field, $required_fields)) {
            return '*';
        }
        return '';
    }
}

/**
 * Enable captcha
 */
if (!function_exists('amotos_enable_captcha')) {
    function amotos_enable_captcha($form_submit)
    {
        $enable_captcha = amotos_get_option('enable_captcha', array());
        if (is_array($enable_captcha) && in_array($form_submit, $enable_captcha)) {
            return true;
        }
        return false;
    }
}
/**
 * Taxonomy target by ID
 */
if (!function_exists('amotos_get_taxonomy_target_by_id')) {
    function amotos_get_taxonomy_target_by_id( $taxonomy_terms, $target_term_id, $prefix = "")
    {
        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
                if ((is_array($target_term_id) && in_array($term->term_id,$target_term_id)) || ($target_term_id == $term->term_id)) {
                    echo '<option value="' . esc_attr($term->term_id)  . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
                } else {
                    echo '<option value="' . esc_attr($term->term_id)  . '">' . esc_html($prefix . $term->name)  . '</option>';
                }
            }
        }
    }
}
/**
 * Taxonomy target by name
 */
if (!function_exists('amotos_get_taxonomy_target_by_name')) {
    function amotos_get_taxonomy_target_by_name($taxonomy_terms, $target_term_name, $prefix = "")
    {
        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
                if ((is_array($target_term_name) && in_array($term->name,$target_term_name)) || ($target_term_name == $term->name)) {
                    echo '<option value="' . esc_attr($term->slug ) . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
                } else {
                    echo '<option value="' . esc_attr($term->slug)  . '">' . esc_html($prefix . $term->name)  . '</option>';
                }
            }
        }
    }
}

/**
 * Taxonomy by post ID
 */
if (!function_exists('amotos_get_taxonomy_by_post_id')) {
    function amotos_get_taxonomy_by_post_id($post_id, $taxonomy_name, $is_target_by_name = false, $show_default_none = true, $multiple = false,$parent = 0, $prefix = '', $target = null)
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );
        $target_by_name = $target !== null ? $target : ($multiple ? array() : '');
        $target_by_id = $target != null ? $target : ($multiple ? array() : 0);
	    $tax_terms = $target != null ? '' : get_the_terms($post_id, $taxonomy_name);

        if ($is_target_by_name) {
        	if (!empty($tax_terms)) {
                foreach ($tax_terms as $tax_term) {
                	if ($multiple) {
		                $target_by_name[] = $tax_term->name;
	                } else {
		                $target_by_name = $tax_term->name;
		                break;
	                }

                }
            }
            if($show_default_none && $parent === 0) {
                if (empty($target_by_name)) {
                    echo '<option value="" selected>' . esc_html__('None', 'auto-moto-stock') . '</option>';
                } else {
                    echo '<option value="">' . esc_html__('None', 'auto-moto-stock') . '</option>';
                }
            }

	        if (!empty($taxonomy_terms)) {
		        foreach ($taxonomy_terms as $term) {
			        if (empty($term) || (!isset($term->parent))) {
				        continue;
			        }
			        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
				        continue;
			        }

			        if ((is_array($target_by_name) && in_array($term->name,$target_by_name)) || ($target_by_name == $term->name)) {
				        echo '<option value="' . esc_attr($term->slug)  . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
			        } else {
				        echo '<option value="' . esc_attr($term->slug)  . '">' . esc_html($prefix . $term->name)  . '</option>';
			        }

			        amotos_get_taxonomy_by_post_id($post_id,$taxonomy_name,$is_target_by_name,$show_default_none,$multiple,$term->term_id, $prefix . '&#8212;',$target_by_name);
		        }
	        }

        } else {
            if (!empty($tax_terms)) {
                foreach ($tax_terms as $tax_term) {
                	if ($multiple) {
		                $target_by_id[] = $tax_term->term_id;
	                } else {
		                $target_by_id = $tax_term->term_id;
		                break;
	                }

                }
            }
            if($show_default_none && $parent === 0)
            {
                if (empty($target_by_id)) {
                    echo '<option value="-1" selected>' . esc_html__('None', 'auto-moto-stock') . '</option>';
                } else {
                    echo '<option value="-1">' . esc_html__('None', 'auto-moto-stock') . '</option>';
                }
            }
	        if (!empty($taxonomy_terms)) {
		        foreach ($taxonomy_terms as $term) {
			        if (empty($term) || (!isset($term->parent))) {
				        continue;
			        }
			        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
				        continue;
			        }

			        if ((is_array($target_by_id) && in_array($term->term_id,$target_by_id)) || ($target_by_id == $term->term_id)) {
				        echo '<option value="' . esc_attr($term->term_id)  . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
			        } else {
				        echo '<option value="' . esc_attr($term->term_id ) . '">' . esc_html($prefix . $term->name)  . '</option>';
			        }

			        amotos_get_taxonomy_by_post_id($post_id,$taxonomy_name,$is_target_by_name,$show_default_none,$multiple,$term->term_id, $prefix . '&#8212;',$target_by_id);
		        }
	        }
        }
    }
}
if (!function_exists('amotos_get_taxonomy_slug_by_post_id')) {
    function amotos_get_taxonomy_slug_by_post_id($post_id, $taxonomy_name)
    {
        $tax_terms = get_the_terms($post_id, $taxonomy_name);
        if (!empty($tax_terms)) {
            foreach ($tax_terms as $tax_term) {
                return $tax_term->slug;
            }
        }
        return null;
    }
}

/**
 * Taxonomy
 */
if (!function_exists('amotos_get_taxonomy')) {
    function amotos_get_taxonomy($taxonomy_name, $value_as_slug = false, $show_default_none = true,$parent = 0, $prefix='')
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );
        if ($show_default_none && $parent === 0) {
            echo '<option value="" selected>' . esc_html__('None', 'auto-moto-stock') . '</option>';
        }
        if (!empty($taxonomy_terms)) {
	        foreach ($taxonomy_terms as $term) {
		        if (empty($term) || (!isset($term->parent))) {
			        continue;
		        }
		        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
			        continue;
		        }

		        if ($value_as_slug) {
			        echo '<option value="' . esc_attr($term->slug ) . '">' . esc_html($prefix . $term->name)  . '</option>';
		        } else {
			        echo '<option value="' . esc_attr($term->term_id)  . '">' . esc_html($prefix . $term->name ) . '</option>';
		        }

		        amotos_get_taxonomy($taxonomy_name,$value_as_slug,$show_default_none, $term->term_id,$prefix . '&#8212;');


	        }
        }
    }
}

/**
 * Taxonomy name by post ID
 */
if (!function_exists('amotos_get_taxonomy_name_by_post_id')) {
    function amotos_get_taxonomy_name_by_post_id($post_id, $taxonomy_name)
    {
        $tax_terms = get_the_terms($post_id, $taxonomy_name);
        $tax_name = '';
        if (!empty($tax_terms)) {
            foreach ($tax_terms as $tax_term) {
                if (is_object($tax_term)) {
                    $tax_name = $tax_term->name;
                }
                break;
            }
        }
        return $tax_name;
    }
}
/**
 * Taxonomy slug
 */
if (!function_exists('amotos_get_taxonomy_slug')) {
    function amotos_get_taxonomy_slug($taxonomy_name, $target_term_slug='',$parent = 0, $prefix='')
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );

        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
	            if (empty($term) || (!isset($term->parent))) {
		            continue;
	            }
	            if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
		            continue;
	            }

                if ($target_term_slug == $term->slug) {
                    echo '<option value="' . esc_attr($term->slug)  . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
                } else {
                    echo '<option value="' . esc_attr($term->slug)  . '">' . esc_html($prefix . $term->name)  . '</option>';
                }

	            amotos_get_taxonomy_slug($taxonomy_name,$target_term_slug, $term->term_id,$prefix . '&#8212;');

            }
        }
    }
}

/**
 * Vehicle status search
 */
if (!function_exists('amotos_get_car_status_search')) {
    function amotos_get_car_status_search()
    {
        $car_status = get_categories(array(
            'taxonomy' => 'car-status',
            'hide_empty' => false,
            'meta_key'=>'car_status_order_number',
            'orderby'=>'meta_value_num',
            'order' => 'ASC'
        ));
        if(count($car_status)==0)
        {
            $car_status = get_categories(array(
                'taxonomy' => 'car-status',
                'hide_empty' =>false,
                'orderby' => 'name',
                'order' => 'ASC',
                'parent' => 0
            ));
        }
        return $car_status;
    }
}

/**
 * Vehicle status default value
 */
if (!function_exists('amotos_get_car_status_default_value')) {
    function amotos_get_car_status_default_value()
    {
        $car_status = amotos_get_car_status_search();
        $car_status_arr = array();
        if ($car_status) {
            foreach ($car_status as $car_stt) {
                $car_status_arr[] = $car_stt->slug;
            }
        }
        $status_default='';
        if(is_array($car_status_arr) && count($car_status_arr)>0)
        {
            $status_default=$car_status_arr[0];
        }
        return $status_default;
    }
}

/**
 * Vehicle status search slug
 */
if (!function_exists('amotos_get_car_status_search_slug')) {
    function amotos_get_car_status_search_slug($target_term_slug='',$prefix='')
    {
        $car_status = amotos_get_car_status_search();
        if (!empty($car_status)) {
            foreach ($car_status as $term) {
                if ($target_term_slug == $term->slug) {
                    echo '<option value="' . esc_attr($term->slug)  . '" selected>' . esc_html($prefix . $term->name)  . '</option>';
                } else {
                    echo '<option value="' . esc_attr($term->slug)  . '">' . esc_html($prefix . $term->name)  . '</option>';
                }
            }
        }
    }
}

/**
 * Countries
 */
if (!function_exists('amotos_get_countries')) {
    function amotos_get_countries()
    {
        $countries = array(
            'AF' => esc_html__('Afghanistan', 'auto-moto-stock'),
            'AX' => esc_html__('Aland Islands', 'auto-moto-stock'),
            'AL' => esc_html__('Albania', 'auto-moto-stock'),
            'DZ' => esc_html__('Algeria', 'auto-moto-stock'),
            'AS' => esc_html__('American Samoa', 'auto-moto-stock'),
            'AD' => esc_html__('Andorra', 'auto-moto-stock'),
            'AO' => esc_html__('Angola', 'auto-moto-stock'),
            'AI' => esc_html__('Anguilla', 'auto-moto-stock'),
            'AQ' => esc_html__('Antarctica', 'auto-moto-stock'),
            'AG' => esc_html__('Antigua and Barbuda', 'auto-moto-stock'),
            'AR' => esc_html__('Argentina', 'auto-moto-stock'),
            'AM' => esc_html__('Armenia', 'auto-moto-stock'),
            'AW' => esc_html__('Aruba', 'auto-moto-stock'),
            'AU' => esc_html__('Australia', 'auto-moto-stock'),
            'AT' => esc_html__('Austria', 'auto-moto-stock'),
            'AZ' => esc_html__('Azerbaijan', 'auto-moto-stock'),
            'BS' => esc_html__('Bahamas the', 'auto-moto-stock'),
            'BH' => esc_html__('Bahrain', 'auto-moto-stock'),
            'BD' => esc_html__('Bangladesh', 'auto-moto-stock'),
            'BB' => esc_html__('Barbados', 'auto-moto-stock'),
            'BY' => esc_html__('Belarus', 'auto-moto-stock'),
            'BE' => esc_html__('Belgium', 'auto-moto-stock'),
            'BZ' => esc_html__('Belize', 'auto-moto-stock'),
            'BJ' => esc_html__('Benin', 'auto-moto-stock'),
            'BM' => esc_html__('Bermuda', 'auto-moto-stock'),
            'BT' => esc_html__('Bhutan', 'auto-moto-stock'),
            'BO' => esc_html__('Bolivia', 'auto-moto-stock'),
            'BA' => esc_html__('Bosnia and Herzegovina', 'auto-moto-stock'),
            'BW' => esc_html__('Botswana', 'auto-moto-stock'),
            'BV' => esc_html__('Bouvet Island (Bouvetoya)', 'auto-moto-stock'),
            'BR' => esc_html__('Brazil', 'auto-moto-stock'),
            'IO' => esc_html__('British Indian Ocean Territory (Chagos Archipelago)', 'auto-moto-stock'),
            'VG' => esc_html__('British Virgin Islands', 'auto-moto-stock'),
            'BN' => esc_html__('Brunei Darussalam', 'auto-moto-stock'),
            'BG' => esc_html__('Bulgaria', 'auto-moto-stock'),
            'BF' => esc_html__('Burkina Faso', 'auto-moto-stock'),
            'BI' => esc_html__('Burundi', 'auto-moto-stock'),
            'KH' => esc_html__('Cambodia', 'auto-moto-stock'),
            'CM' => esc_html__('Cameroon', 'auto-moto-stock'),
            'CA' => esc_html__('Canada', 'auto-moto-stock'),
            'CV' => esc_html__('Cape Verde', 'auto-moto-stock'),
            'KY' => esc_html__('Cayman Islands', 'auto-moto-stock'),
            'CF' => esc_html__('Central African Republic', 'auto-moto-stock'),
            'TD' => esc_html__('Chad', 'auto-moto-stock'),
            'CL' => esc_html__('Chile', 'auto-moto-stock'),
            'CN' => esc_html__('China', 'auto-moto-stock'),
            'CX' => esc_html__('Christmas Island', 'auto-moto-stock'),
            'CC' => esc_html__('Cocos (Keeling) Islands', 'auto-moto-stock'),
            'CO' => esc_html__('Colombia', 'auto-moto-stock'),
            'KM' => esc_html__('Comoros the', 'auto-moto-stock'),
            'CD' => esc_html__('Congo', 'auto-moto-stock'),
            'CG' => esc_html__('Congo the', 'auto-moto-stock'),
            'CK' => esc_html__('Cook Islands', 'auto-moto-stock'),
            'CR' => esc_html__('Costa Rica', 'auto-moto-stock'),
            'CI' => esc_html__("Cote d'Ivoire", 'auto-moto-stock'),
            'HR' => esc_html__('Croatia', 'auto-moto-stock'),
            'CU' => esc_html__('Cuba', 'auto-moto-stock'),
            'CY' => esc_html__('Cyprus', 'auto-moto-stock'),
            'CZ' => esc_html__('Czech Republic', 'auto-moto-stock'),
            'DK' => esc_html__('Denmark', 'auto-moto-stock'),
            'DJ' => esc_html__('Djibouti', 'auto-moto-stock'),
            'DM' => esc_html__('Dominica', 'auto-moto-stock'),
            'DO' => esc_html__('Dominican Republic', 'auto-moto-stock'),
            'EC' => esc_html__('Ecuador', 'auto-moto-stock'),
            'EG' => esc_html__('Egypt', 'auto-moto-stock'),
            'SV' => esc_html__('El Salvador', 'auto-moto-stock'),
            'GQ' => esc_html__('Equatorial Guinea', 'auto-moto-stock'),
            'ER' => esc_html__('Eritrea', 'auto-moto-stock'),
            'EE' => esc_html__('Estonia', 'auto-moto-stock'),
            'ET' => esc_html__('Ethiopia', 'auto-moto-stock'),
            'FO' => esc_html__('Faroe Islands', 'auto-moto-stock'),
            'FK' => esc_html__('Falkland Islands (Malvinas)', 'auto-moto-stock'),
            'FJ' => esc_html__('Fiji the Fiji Islands', 'auto-moto-stock'),
            'FI' => esc_html__('Finland', 'auto-moto-stock'),
            'FR' => esc_html__('France', 'auto-moto-stock'),
            'GF' => esc_html__('French Guiana', 'auto-moto-stock'),
            'PF' => esc_html__('French Polynesia', 'auto-moto-stock'),
            'TF' => esc_html__('French Southern Territories', 'auto-moto-stock'),
            'GA' => esc_html__('Gabon', 'auto-moto-stock'),
            'GM' => esc_html__('Gambia the', 'auto-moto-stock'),
            'GE' => esc_html__('Georgia', 'auto-moto-stock'),
            'DE' => esc_html__('Germany', 'auto-moto-stock'),
            'GH' => esc_html__('Ghana', 'auto-moto-stock'),
            'GI' => esc_html__('Gibraltar', 'auto-moto-stock'),
            'GR' => esc_html__('Greece', 'auto-moto-stock'),
            'GL' => esc_html__('Greenland', 'auto-moto-stock'),
            'GD' => esc_html__('Grenada', 'auto-moto-stock'),
            'GP' => esc_html__('Guadeloupe', 'auto-moto-stock'),
            'GU' => esc_html__('Guam', 'auto-moto-stock'),
            'GT' => esc_html__('Guatemala', 'auto-moto-stock'),
            'GG' => esc_html__('Guernsey', 'auto-moto-stock'),
            'GN' => esc_html__('Guinea', 'auto-moto-stock'),
            'GW' => esc_html__('Guinea-Bissau', 'auto-moto-stock'),
            'GY' => esc_html__('Guyana', 'auto-moto-stock'),
            'HT' => esc_html__('Haiti', 'auto-moto-stock'),
            'HM' => esc_html__('Heard Island and McDonald Islands', 'auto-moto-stock'),
            'VA' => esc_html__('Holy See (Vatican City State)', 'auto-moto-stock'),
            'HN' => esc_html__('Honduras', 'auto-moto-stock'),
            'HK' => esc_html__('Hong Kong', 'auto-moto-stock'),
            'HU' => esc_html__('Hungary', 'auto-moto-stock'),
            'IS' => esc_html__('Iceland', 'auto-moto-stock'),
            'IN' => esc_html__('India', 'auto-moto-stock'),
            'ID' => esc_html__('Indonesia', 'auto-moto-stock'),
            'IR' => esc_html__('Iran', 'auto-moto-stock'),
            'IQ' => esc_html__('Iraq', 'auto-moto-stock'),
            'IE' => esc_html__('Ireland', 'auto-moto-stock'),
            'IM' => esc_html__('Isle of Man', 'auto-moto-stock'),
            'IL' => esc_html__('Israel', 'auto-moto-stock'),
            'IT' => esc_html__('Italy', 'auto-moto-stock'),
            'JM' => esc_html__('Jamaica', 'auto-moto-stock'),
            'JP' => esc_html__('Japan', 'auto-moto-stock'),
            'JE' => esc_html__('Jersey', 'auto-moto-stock'),
            'JO' => esc_html__('Jordan', 'auto-moto-stock'),
            'KZ' => esc_html__('Kazakhstan', 'auto-moto-stock'),
            'KE' => esc_html__('Kenya', 'auto-moto-stock'),
            'KI' => esc_html__('Kiribati', 'auto-moto-stock'),
            'KP' => esc_html__('Korea', 'auto-moto-stock'),
            'KR' => esc_html__('Korea', 'auto-moto-stock'),
            'KW' => esc_html__('Kuwait', 'auto-moto-stock'),
            'KG' => esc_html__('Kyrgyz Republic', 'auto-moto-stock'),
            'LA' => esc_html__('Lao', 'auto-moto-stock'),
            'LV' => esc_html__('Latvia', 'auto-moto-stock'),
            'LB' => esc_html__('Lebanon', 'auto-moto-stock'),
            'LS' => esc_html__('Lesotho', 'auto-moto-stock'),
            'LR' => esc_html__('Liberia', 'auto-moto-stock'),
            'LY' => esc_html__('Libyan Arab Jamahiriya', 'auto-moto-stock'),
            'LI' => esc_html__('Liechtenstein', 'auto-moto-stock'),
            'LT' => esc_html__('Lithuania', 'auto-moto-stock'),
            'LU' => esc_html__('Luxembourg', 'auto-moto-stock'),
            'MO' => esc_html__('Macao', 'auto-moto-stock'),
            'MK' => esc_html__('Macedonia', 'auto-moto-stock'),
            'MG' => esc_html__('Madagascar', 'auto-moto-stock'),
            'MW' => esc_html__('Malawi', 'auto-moto-stock'),
            'MY' => esc_html__('Malaysia', 'auto-moto-stock'),
            'MV' => esc_html__('Maldives', 'auto-moto-stock'),
            'ML' => esc_html__('Mali', 'auto-moto-stock'),
            'MT' => esc_html__('Malta', 'auto-moto-stock'),
            'MH' => esc_html__('Marshall Islands', 'auto-moto-stock'),
            'MQ' => esc_html__('Martinique', 'auto-moto-stock'),
            'MR' => esc_html__('Mauritania', 'auto-moto-stock'),
            'MU' => esc_html__('Mauritius', 'auto-moto-stock'),
            'YT' => esc_html__('Mayotte', 'auto-moto-stock'),
            'MX' => esc_html__('Mexico', 'auto-moto-stock'),
            'FM' => esc_html__('Micronesia', 'auto-moto-stock'),
            'MD' => esc_html__('Moldova', 'auto-moto-stock'),
            'MC' => esc_html__('Monaco', 'auto-moto-stock'),
            'MN' => esc_html__('Mongolia', 'auto-moto-stock'),
            'ME' => esc_html__('Montenegro', 'auto-moto-stock'),
            'MS' => esc_html__('Montserrat', 'auto-moto-stock'),
            'MA' => esc_html__('Morocco', 'auto-moto-stock'),
            'MZ' => esc_html__('Mozambique', 'auto-moto-stock'),
            'MM' => esc_html__('Myanmar', 'auto-moto-stock'),
            'NA' => esc_html__('Namibia', 'auto-moto-stock'),
            'NR' => esc_html__('Nauru', 'auto-moto-stock'),
            'NP' => esc_html__('Nepal', 'auto-moto-stock'),
            'AN' => esc_html__('Netherlands Antilles', 'auto-moto-stock'),
            'NL' => esc_html__('Netherlands the', 'auto-moto-stock'),
            'NC' => esc_html__('New Caledonia', 'auto-moto-stock'),
            'NZ' => esc_html__('New Zealand', 'auto-moto-stock'),
            'NI' => esc_html__('Nicaragua', 'auto-moto-stock'),
            'NE' => esc_html__('Niger', 'auto-moto-stock'),
            'NG' => esc_html__('Nigeria', 'auto-moto-stock'),
            'NU' => esc_html__('Niue', 'auto-moto-stock'),
            'NF' => esc_html__('Norfolk Island', 'auto-moto-stock'),
            'MP' => esc_html__('Northern Mariana Islands', 'auto-moto-stock'),
            'NO' => esc_html__('Norway', 'auto-moto-stock'),
            'OM' => esc_html__('Oman', 'auto-moto-stock'),
            'PK' => esc_html__('Pakistan', 'auto-moto-stock'),
            'PW' => esc_html__('Palau', 'auto-moto-stock'),
            'PS' => esc_html__('Palestinian Territory', 'auto-moto-stock'),
            'PA' => esc_html__('Panama', 'auto-moto-stock'),
            'PG' => esc_html__('Papua New Guinea', 'auto-moto-stock'),
            'PY' => esc_html__('Paraguay', 'auto-moto-stock'),
            'PE' => esc_html__('Peru', 'auto-moto-stock'),
            'PH' => esc_html__('Philippines', 'auto-moto-stock'),
            'PN' => esc_html__('Pitcairn Islands', 'auto-moto-stock'),
            'PL' => esc_html__('Poland', 'auto-moto-stock'),
            'PT' => esc_html__('Portugal, Portuguese Republic', 'auto-moto-stock'),
            'PR' => esc_html__('Puerto Rico', 'auto-moto-stock'),
            'QA' => esc_html__('Qatar', 'auto-moto-stock'),
            'RE' => esc_html__('Reunion', 'auto-moto-stock'),
            'RO' => esc_html__('Romania', 'auto-moto-stock'),
            'RU' => esc_html__('Russian Federation', 'auto-moto-stock'),
            'RW' => esc_html__('Rwanda', 'auto-moto-stock'),
            'BL' => esc_html__('Saint Barthelemy', 'auto-moto-stock'),
            'SH' => esc_html__('Saint Helena', 'auto-moto-stock'),
            'KN' => esc_html__('Saint Kitts and Nevis', 'auto-moto-stock'),
            'LC' => esc_html__('Saint Lucia', 'auto-moto-stock'),
            'MF' => esc_html__('Saint Martin', 'auto-moto-stock'),
            'PM' => esc_html__('Saint Pierre and Miquelon', 'auto-moto-stock'),
            'VC' => esc_html__('Saint Vincent and the Grenadines', 'auto-moto-stock'),
            'WS' => esc_html__('Samoa', 'auto-moto-stock'),
            'SM' => esc_html__('San Marino', 'auto-moto-stock'),
            'ST' => esc_html__('Sao Tome and Principe', 'auto-moto-stock'),
            'SA' => esc_html__('Saudi Arabia', 'auto-moto-stock'),
            'SN' => esc_html__('Senegal', 'auto-moto-stock'),
            'RS' => esc_html__('Serbia', 'auto-moto-stock'),
            'SC' => esc_html__('Seychelles', 'auto-moto-stock'),
            'SL' => esc_html__('Sierra Leone', 'auto-moto-stock'),
            'SG' => esc_html__('Singapore', 'auto-moto-stock'),
            'SK' => esc_html__('Slovakia (Slovak Republic)', 'auto-moto-stock'),
            'SI' => esc_html__('Slovenia', 'auto-moto-stock'),
            'SB' => esc_html__('Solomon Islands', 'auto-moto-stock'),
            'SO' => esc_html__('Somalia, Somali Republic', 'auto-moto-stock'),
            'ZA' => esc_html__('South Africa', 'auto-moto-stock'),
            'GS' => esc_html__('South Georgia and the South Sandwich Islands', 'auto-moto-stock'),
            'ES' => esc_html__('Spain', 'auto-moto-stock'),
            'LK' => esc_html__('Sri Lanka', 'auto-moto-stock'),
            'SD' => esc_html__('Sudan', 'auto-moto-stock'),
            'SR' => esc_html__('Suriname', 'auto-moto-stock'),
            'SJ' => esc_html__('Svalbard & Jan Mayen Islands', 'auto-moto-stock'),
            'SZ' => esc_html__('Swaziland', 'auto-moto-stock'),
            'SE' => esc_html__('Sweden', 'auto-moto-stock'),
            'CH' => esc_html__('Switzerland, Swiss Confederation', 'auto-moto-stock'),
            'SY' => esc_html__('Syrian Arab Republic', 'auto-moto-stock'),
            'TW' => esc_html__('Taiwan', 'auto-moto-stock'),
            'TJ' => esc_html__('Tajikistan', 'auto-moto-stock'),
            'TZ' => esc_html__('Tanzania', 'auto-moto-stock'),
            'TH' => esc_html__('Thailand', 'auto-moto-stock'),
            'TL' => esc_html__('Timor-Leste', 'auto-moto-stock'),
            'TG' => esc_html__('Togo', 'auto-moto-stock'),
            'TK' => esc_html__('Tokelau', 'auto-moto-stock'),
            'TO' => esc_html__('Tonga', 'auto-moto-stock'),
            'TT' => esc_html__('Trinidad and Tobago', 'auto-moto-stock'),
            'TN' => esc_html__('Tunisia', 'auto-moto-stock'),
            'TR' => esc_html__('Turkey', 'auto-moto-stock'),
            'TM' => esc_html__('Turkmenistan', 'auto-moto-stock'),
            'TC' => esc_html__('Turks and Caicos Islands', 'auto-moto-stock'),
            'TV' => esc_html__('Tuvalu', 'auto-moto-stock'),
            'UG' => esc_html__('Uganda', 'auto-moto-stock'),
            'UA' => esc_html__('Ukraine', 'auto-moto-stock'),
            'AE' => esc_html__('United Arab Emirates', 'auto-moto-stock'),
            'GB' => esc_html__('United Kingdom', 'auto-moto-stock'),
            'US' => esc_html__('United States', 'auto-moto-stock'),
            'UM' => esc_html__('United States Minor Outlying Islands', 'auto-moto-stock'),
            'VI' => esc_html__('United States Virgin Islands', 'auto-moto-stock'),
            'UY' => esc_html__('Uruguay, Eastern Republic of', 'auto-moto-stock'),
            'UZ' => esc_html__('Uzbekistan', 'auto-moto-stock'),
            'VU' => esc_html__('Vanuatu', 'auto-moto-stock'),
            'VE' => esc_html__('Venezuela', 'auto-moto-stock'),
            'VN' => esc_html__('Vietnam', 'auto-moto-stock'),
            'WF' => esc_html__('Wallis and Futuna', 'auto-moto-stock'),
            'EH' => esc_html__('Western Sahara', 'auto-moto-stock'),
            'YE' => esc_html__('Yemen', 'auto-moto-stock'),
            'ZM' => esc_html__('Zambia', 'auto-moto-stock'),
            'ZW' => esc_html__('Zimbabwe', 'auto-moto-stock'),
	        'SS' => esc_html__('South Sudan','auto-moto-stock')
        );
        return $countries;
    }
}

if (!function_exists('amotos_get_selected_countries')) {
    function amotos_get_selected_countries()
    {
        $countries = amotos_get_countries();
        $countries_selected = get_option( 'country_list' );
        if(!empty($countries_selected) && is_array($countries_selected))
        {
            $results=array();
            foreach($countries_selected as $country){
                foreach($countries as $key => $value)
                {
                    if($country===$key)
                    {
                        $results[$key]=$value;
                    }
                }
            }
            return $results;
        }
        else
        {
            return $countries;
        }
    }
}

/**
 * Countries by code
 */
if (!function_exists('amotos_get_country_by_code')) {
    function amotos_get_country_by_code($code)
    {
        $countries = amotos_get_countries();
        foreach ($countries as $key => $val) {
            if ($key == $code) return $val;
        }
        return null;
    }
}

/**
 * Countries by name
 */
if (!function_exists('amotos_get_code_country_by_name')) {
    function amotos_get_code_country_by_name($name)
    {
        $countries = amotos_get_countries();
        $country_code = array_search($name, $countries);
        return $country_code;
    }
}

if (!function_exists('amotos_clean_double_val')) {
    function amotos_clean_double_val($string)
    {
        $string = preg_replace('/&#36;/', '', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $string = preg_replace('/\D/', '', $string);
        return $string;
    }
}

/**
 * Measurement units mileage
 */
if (!function_exists('amotos_get_measurement_units_mileage')) {
    function amotos_get_measurement_units_mileage()
    {
        $measurement_units_mileage = amotos_get_option('measurement_units_mileage', 'Mi');
        if ($measurement_units_mileage == 'custom') {
            $measurement_units_mileage = amotos_get_option('custom_measurement_units_mileage', 'Mi');
        }
        else
        {
	        if ($measurement_units_mileage === 'km') {
		        $measurement_units_mileage = 'km';
	        }
        }
        return apply_filters('amotos_get_measurement_units_mileage', $measurement_units_mileage);
    }
}

/**
 * Measurement units power
 */
if (!function_exists('amotos_get_measurement_units_power')) {
    function amotos_get_measurement_units_power()
    {
        $measurement_units_power = amotos_get_option('measurement_units_power', 'Hp');
        if ($measurement_units_power == 'custom') {
            $measurement_units_power = amotos_get_option('custom_measurement_units_power', 'Hp');
        }
        else
        {
	        if ($measurement_units_power === 'kW') {
		        $measurement_units_power = 'kW';
	        }
        }
        return apply_filters('amotos_get_measurement_units_power', $measurement_units_power);
    }
}

/**
 * Measurement units volume
 */
if (!function_exists('amotos_get_measurement_units_volume')) {
    function amotos_get_measurement_units_volume()
    {
        $measurement_units_volume = amotos_get_option('measurement_units_volume', 'CID');
        if ($measurement_units_volume == 'custom') {
            $measurement_units_volume = amotos_get_option('custom_measurement_units_volume', 'CID');
        }
        else
        {
	        if ($measurement_units_volume === 'cm3') {
		        $measurement_units_volume = 'cm<sup>3</sup>';
	        }
        }
        return apply_filters('amotos_get_measurement_units_volume', $measurement_units_volume);
    }
}

/**
 * Render Additional Fields
 */
if (!function_exists('amotos_render_additional_fields')) {
    function amotos_render_additional_fields()
    {
        $meta_prefix = AMOTOS_METABOX_PREFIX;
        $form_fields = amotos_get_option('additional_fields');
        $configs = array();
        if ($form_fields && is_array($form_fields)) {
            foreach ($form_fields as $key => $field) {
                if(!empty($field['label']))
                {
                    $type = $field['field_type'];
                    $id = isset($field['id']) && !empty($field['id'])
	                    ? $field['id']
	                    : sanitize_title($field['label']);

                    if (in_array($id,array('car_price_short','car_price_prefix','car_price_postfix','car_price_on_call','car_mileage','car_power','car_volume','car_doors','car_seats','car_owners','car_year','car_identity','additional_stylings','car_featured','car_address','car_zip','car_location','car_images','car_attachments','car_video_url','car_video_image','car_virtual_360_type','car_virtual_360','car_image_360','manager_display_option','car_manager','car_other_contact_name','car_other_contact_mail','car_other_contact_phone','car_other_contact_description','private_note'))) {
                        $id = 'additional_detail__' . $id;
                    }

                    $config = array(
                        'title' => $field['label'],
                        'id' => $meta_prefix . $id,
                        'type' => $type
                    );
                    $first_opt = '';
                    switch ($type) {
                        case 'checkbox_list':
                        case 'select':
                        case 'radio':
                            $options = array();
                            $options_arr = isset($field['select_choices']) ? $field['select_choices'] : '';
                            $options_arr = str_replace("\r\n", "\n", $options_arr);
                            $options_arr = str_replace("\r", "\n", $options_arr);
                            $options_arr = explode("\n", $options_arr);
                            $first_opt = !empty($options_arr) ? $options_arr[0] : '';
                            foreach ($options_arr as $opt_value) {
                                $options[$opt_value] = $opt_value;
                            }

                            $config['options'] = $options;
                            break;
                    }
                    if (in_array($type, array('select', 'radio'))) {
                        $config['default'] = $first_opt;
                    }

                    $configs[] = $config;
                }
            }
        }
        return $configs;
    }
}

if (!function_exists('amotos_get_search_additional_fields')) {
	function amotos_get_search_additional_fields() {
		$additional_fields = amotos_get_option('additional_fields');
		$search_builtIn_fields =  amotos_get_search_builtIn_fields();
		$configs = array();
		if ($additional_fields && is_array($additional_fields)) {
			foreach ($additional_fields as $key => $field) {
				if ((isset($field['label']) && !empty($field['label'])) && (isset($field['is_search']) && $field['is_search'] === 'on')) {
					$id = isset($field['id']) && !empty($field['id'])
						? $field['id']
						: sanitize_title($field['label']);
					if (in_array($id,$search_builtIn_fields)) {
						$id = 'additional_detail__' . $id;
					}

					$configs[$id] = $field['label'];
				}
			}
		}
		return $configs;
	}
}

if (!function_exists('amotos_get_search_additional_field')) {
	function amotos_get_search_additional_field($key) {
		$additional_fields = amotos_get_option('additional_fields');
		$search_builtIn_fields =  amotos_get_search_builtIn_fields();
		if ($additional_fields && is_array($additional_fields)) {
			foreach ($additional_fields as $k => $field) {
				$id = isset($field['id']) && !empty($field['id'])
					? $field['id']
					: sanitize_title($field['label']);

				if (in_array($id,$search_builtIn_fields)) {
					$id = 'additional_detail__' . $id;
				}

				if (($id === $key) && (isset($field['is_search']) && $field['is_search'] === 'on')) {
					$field['id'] = $id;
					return $field;
				}
			}
		}
		return false;
	}
}

if(!function_exists('amotos_get_search_builtIn_fields')) {
	function amotos_get_search_builtIn_fields() {
		return apply_filters('amotos_search_builtIn_fields',array(
			'keyword',
			'car_status',
			'car_type',
			'car_title',
			'car_address',
			'car_country',
			'car_state',
			'car_city',
			'car_neighborhood',
			'car_doors',
			'car_seats',
			'car_owners',
			'car_price',
			'car_mileage',
			'car_power',
			'car_volume',
			'car_label',
			'car_identity',
			'car_styling',
		));
	}
}

if (!function_exists('amotos_get_search_form_fields_config')) {
	function amotos_get_search_form_fields_config() {
		$config =  array(
			'keyword' => esc_html__('Keyword', 'auto-moto-stock'),
            'car_type' => esc_html__('Type', 'auto-moto-stock'),
            'car_title' => esc_html__('Title', 'auto-moto-stock'),
			'car_status' => esc_html__('Status', 'auto-moto-stock'),
            'car_label' => esc_html__('Label', 'auto-moto-stock'),
            'car_price' => esc_html__('Price', 'auto-moto-stock'),
			'car_address' => esc_html__('Address', 'auto-moto-stock'),
			'car_country' => esc_html__('Country', 'auto-moto-stock'),
			'car_state' => esc_html__('Province/State', 'auto-moto-stock'),
			'car_city' => esc_html__('City/Town', 'auto-moto-stock'),
			'car_neighborhood' => esc_html__('Neighborhood', 'auto-moto-stock'),
            'car_owners' => esc_html__('Owners', 'auto-moto-stock'),
            'car_mileage' => esc_html__('Mileage', 'auto-moto-stock'),
			'car_power' => esc_html__('Power', 'auto-moto-stock'),
			'car_volume' => esc_html__('Cubic Capacity', 'auto-moto-stock'),
            'car_styling' => esc_html__('Styling', 'auto-moto-stock'),
			'car_doors' => esc_html__('Doors', 'auto-moto-stock'),
			'car_seats' => esc_html__('Seats', 'auto-moto-stock'),
			'car_identity' => esc_html__('Vehicle ID', 'auto-moto-stock'),
		);
		$additional_fields = amotos_get_search_additional_fields();
		$config = wp_parse_args($additional_fields,$config);
		return apply_filters('get_search_form_fields_config',$config);
	}
}

function amotos_get_search_form_fields_config_default() {
    return apply_filters('amotos_search_form_fields_config_default',array(
        'car_status',
        'car_type',
        'car_title',
        'car_address',
        'car_country',
        'car_state',
        'car_city',
        'car_neighborhood',
        'car_doors',
        'car_seats',
        'car_owners',
        'car_price',
        'car_mileage',
        'car_power',
        'car_volume',
        'car_label',
        'car_identity',
        'car_styling'
    ));
}

if ( ! function_exists('amotos_server_protocol') ) {

    function amotos_server_protocol() {
        if ( is_ssl() ) {
            return 'https://';
        }
        return 'http://';
    }

}

if ( ! function_exists('amotos_get_comment_time') ) {
    function amotos_get_comment_time($comment_id = 0)
    {
        return sprintf(
            /* translators: %s: human time diff  */
            _x('%s ago', 'Human-readable time', 'auto-moto-stock'),
            human_time_diff(
                get_comment_date('U', $comment_id),
                current_time('timestamp')
            )
        );
    }
}

if (!function_exists('amotos_get_number_text')) {
    function amotos_get_number_text($number, $many_text, $singular_text) {
        if($number != 1) {
            return $many_text;
        } else {
            return $singular_text;
        }
    }
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
if (!function_exists('amotos_clean')) {
    function amotos_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'amotos_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( wp_unslash($var) ) : $var;
        }
    }
}

if (!function_exists('amotos_sanitize_filter_post_kses')) {
	function amotos_sanitize_filter_post_kses( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'amotos_sanitize_filter_post_kses', $var );
		} else {
			return is_scalar( $var ) ? wp_filter_post_kses( wp_unslash($var) ) : $var;
		}
	}
}

if (!function_exists('amotos_sort_by_order_callback')) {
	function amotos_sort_by_order_callback( $a, $b ) {
		if ( ! isset( $a['priority'] ) ) {
			$a['priority'] = 100;
		}
		if ( ! isset( $b['priority'] ) ) {
			$b['priority'] = 100;
		}

		return $a['priority'] === $b['priority'] ? 0 : ( $a['priority'] > $b['priority'] ? 1 : - 1 );
	}
}

/**
 * Execute callback and capture its HTML output.
 *
 * Escaping MUST be handled at output time.
 *
 * @param array $k Callback configuration.
 * @return array
 */
function amotos_content_callback( $k ) {
    if ( isset( $k['callback'] ) && is_callable( $k['callback'] ) ) {
        ob_start();
        call_user_func( $k['callback'], $k );
        $k['content'] = ob_get_clean();
    }
    return $k;
}

/*if (!function_exists('amotos_content_callback')) {
	function amotos_content_callback( $k ) {
		if ( isset( $k['callback'] ) ) {
			ob_start();
			call_user_func( $k['callback'], $k );
			$content      = ob_get_clean();
			$k['content'] = $content;
		}
		return $k;
	}
}*/

if (!function_exists('amotos_filter_content_callback')) {
	function amotos_filter_content_callback( $k ) {
		return isset( $k['content'] ) && ! empty( $k['content'] );
	}
}

/*if (!function_exists('amotos_urlencode')) {
	function amotos_urlencode( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'urlencode', $var );
		} else {
			return urlencode($var);
		}
	}
}*/
if ( ! function_exists( 'amotos_urlencode' ) ) {
    function amotos_urlencode( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'amotos_urlencode', $var );
        }

        if ( is_scalar( $var ) ) {
            return rawurlencode( (string) $var );
        }

        return '';
    }
}

if (!function_exists('amotos_string_end_with')) {
	function amotos_string_end_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
}

if (!function_exists('amotos_custom_load_textdomain')) {
	add_action('plugin_loaded', 'amotos_custom_load_textdomain', 1);
	function amotos_custom_load_textdomain() {
		if (is_admin()) {
			$script_name = isset($_SERVER['SCRIPT_NAME']) ? sanitize_text_field($_SERVER['SCRIPT_NAME']) : '';

			if ((amotos_string_end_with($script_name, '/wp-admin/post-new.php') && (isset($_REQUEST['post_type']) && (sanitize_text_field(wp_unslash($_REQUEST['post_type'])) === 'car')))
				|| (amotos_string_end_with($script_name, '/wp-admin/post.php') && isset($_REQUEST['post']) && (isset($_REQUEST['action']) && (sanitize_text_field(wp_unslash($_REQUEST['action']))  === 'edit')))) {
				global $sitepress;
				if (isset($sitepress) && $sitepress) {
					$current_lang = $sitepress->get_current_language();
					$current_locale = $sitepress->locale_utils->get_locale($current_lang);
					$text_domain = 'admin_texts_amotos_options';

					$mo_file = WP_CONTENT_DIR . "/languages/wpml/{$text_domain}-{$current_locale}.mo";
					if (is_readable($mo_file)) {
						load_textdomain( $text_domain, $mo_file);
					}
				}
			}
		}
	}
}

if (!function_exists('amotos_do_shortcode')) {
	function amotos_do_shortcode($name, $attrs = array()) {
		$special_chars = array('[', ']', '"', "'");
		$sc_params = '';
		foreach ($attrs as $k => $v) {
			$v = str_replace($special_chars, '', $v);
			/*if (!is_array($v) || !is_object($v)) {
				$sc_params .= " {$k}=\"{$v}\"";
			}*/
            if ( ! is_array( $v ) && ! is_object( $v ) ) {
    $sc_params .= sprintf(
        ' %s="%s"',
        sanitize_key( $k ),
        esc_attr( $v )
    );
}
		}
		$short_code = "[{$name}{$sc_params}]";
		return do_shortcode($short_code);
	}
}

if (!function_exists('amotos_get_admin_template')) {
	function amotos_get_admin_template($template, $args = array())
	{
		if (!empty($args) && is_array($args)) {
			extract($args);
		}

		$located =  apply_filters('amotos_get_admin_template', AMOTOS_PLUGIN_DIR . $template, $template, $args);

		do_action('amotos_before_get_admin_template', $located, $template, $args);

		include($located);

		do_action('amotos_after_get_admin_template', $located, $template, $args);
	}
}

if (!function_exists('amotos_render_html_attr')) {
	function amotos_render_html_attr($attrs) {
		foreach ($attrs as $k => $v) {
		    if (is_bool($v)) {
                echo esc_attr($k) . '="'. ($v ? 'true' : 'false') . '" ';
            }
		    else {
                echo esc_attr($k) . '="'. esc_attr(is_scalar($v) ? $v : wp_json_encode($v)) . '" ';
            }
		}
	}
}

if (!function_exists('amotos_render_attr_iff')) {
	function amotos_render_attr_iff($condition, $attr, $value) {
		if ($condition) {
            if (is_bool($value)) {
                echo esc_attr($attr) . '="' . ($value ? 'true' : 'false') . '"';
            }
            else {
                echo esc_attr($attr) . '="' . esc_attr(is_scalar($value) ? $value : wp_json_encode($value)) . '"';
            }
		}
	}
}

if (!function_exists('amotos_render_js_array')) {
	function amotos_render_js_array($arr) {
		echo '[';
		foreach ($arr as $index => $v) {
			if ($index > 0) {
				echo ',';
			}
			echo "'" . esc_js($v) . "'";
		}
		echo ']';
	}
}

function amotos_get_google_map_skins() {
    return apply_filters( 'amotos_google_map_skins', array(
        ''  => _x( 'Default', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin1'  => _x( 'Vanilla', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin2'  => _x( 'Midnight', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin3'  => _x( 'Grayscale', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin4'  => _x( 'Blue Water', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin5'  => _x( 'Nature', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin6'  => _x( 'Light', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin7'  => _x( 'Teal', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin8'  => _x( 'Iceberg', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin9'  => _x( 'Violet', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin10' => _x( 'Ocean', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin11' => _x( 'Dark', 'Google Maps Skin', 'auto-moto-stock' ),
        'skin12' => _x( 'Standard', 'Google Maps Skin', 'auto-moto-stock' ),
        'custom' => _x( 'Custom', 'Google Maps Skin', 'auto-moto-stock' )
    ) );
}

function amotos_get_google_map_autocomplete_types() {
    return apply_filters( 'amotos_google_map_autocomplete_types', array(
        'geocode'       => esc_html__( 'Geocode', 'auto-moto-stock' ),
        'address'       => esc_html__( 'Address', 'auto-moto-stock' ),
        'establishment' => esc_html__( 'Establishment', 'auto-moto-stock' ),
        '(regions)'     => esc_html__( 'Regions', 'auto-moto-stock' ),
        '(cities)'      => esc_html__( 'Cities', 'auto-moto-stock' )
    ) );
}

function amotos_get_car_form_section_config()
{
    return array(
        //'title_des' => esc_html__( 'Title & Description', 'auto-moto-stock' ),
        'basic_info' => esc_html__( 'Basic Info', 'auto-moto-stock' ),
        'tech_data' => esc_html__( 'Technical Data', 'auto-moto-stock' ),
        'stylings'  => esc_html__( 'Styling', 'auto-moto-stock' ),
        'location'  => esc_html__( 'Location', 'auto-moto-stock' ),
        //'type'      => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
        'price'     => esc_html__( 'Price', 'auto-moto-stock' ),        
        //'details'   => esc_html__( 'Details', 'auto-moto-stock' ),
        'media'     => esc_html__( 'Media Files', 'auto-moto-stock' ),
        'contact'   => esc_html__( 'Contact Information', 'auto-moto-stock' ),
    );
}

function amotos_get_car_form_section_config_default()
{
    return array(
        //'title_des',
        'basic_info',
        'tech_data',
        'stylings',
        'location',
        //'type',
        'price',
        //'details',
        'media',
        'contact'
    );
}

function amotos_is_rtl()
{
    $enable_rtl_mode = amotos_get_option('enable_rtl_mode', 0);
    return is_rtl() || ($enable_rtl_mode == 1) || isset($_GET['RTL']);
}