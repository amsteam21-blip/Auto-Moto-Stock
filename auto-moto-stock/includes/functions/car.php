<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Get Vehicle Gallery Image
 *
 * @param $car_Id
 *
 * @return false|string[]
 */
function amotos_get_car_gallery_image($car_Id) {
	$car_gallery = get_post_meta($car_Id, AMOTOS_METABOX_PREFIX . 'car_images', true);
	if (empty($car_gallery)) {
		return false;
	}
	return explode( '|', $car_gallery);
}

function amotos_get_single_car_stylings_tabs($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $tabs = array();

    $tabs['overview'] = array(
        'title'    => esc_html__( 'Overview', 'auto-moto-stock' ),
        'priority' => 10,
        'callback' => 'amotos_template_single_car_overview',
        'car_id' => $car_id
    );

    $tabs['stylings'] = array(
        'title'    => esc_html__( 'Styling', 'auto-moto-stock' ),
        'priority' => 20,
        'callback' => 'amotos_template_single_car_styling',
        'car_id' => $car_id
    );

    $tabs['video'] = array(
        'title'    => esc_html__( 'Video', 'auto-moto-stock' ),
        'priority' => 30,
        'callback' => 'amotos_template_single_car_video',
        'car_id' => $car_id
    );

    $tabs['virtual_360'] = array(
        'title'    => esc_html__( 'Virtual 360', 'auto-moto-stock' ),
        'priority' => 30,
        'callback' => 'amotos_template_single_car_virtual_360',
        'car_id' => $car_id
    );

    $tabs = apply_filters( 'amotos_single_car_stylings_tabs', $tabs , $car_id);

    uasort( $tabs, 'amotos_sort_by_order_callback' );

    $tabs = array_map( 'amotos_content_callback', $tabs );

    return array_filter( $tabs, 'amotos_filter_content_callback' );
}

function amotos_get_single_car_overview($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $overview = array();

    $overview['car_id'] = array(
        'title'    => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
        'priority' => 10,
        'callback' => 'amotos_template_single_car_identity',
        'car_id' => $car_id
    );

    $overview['price'] = array(
        'title'    => esc_html__( 'Price', 'auto-moto-stock' ),
        'priority' => 20,
        'callback' => 'amotos_template_loop_car_price',
        'car_id' => $car_id
    );

    $overview['type'] = array(
        'title'    => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
        'priority' => 30,
        'callback' => 'amotos_template_single_car_type',
        'car_id' => $car_id
    );

    $overview['status'] = array(
        'title'    => esc_html__( 'Status', 'auto-moto-stock' ),
        'priority' => 40,
        'callback' => 'amotos_template_single_car_data_status',
        'car_id' => $car_id
    );

    $overview['doors'] = array(
        'title'    => esc_html__( 'Doors', 'auto-moto-stock' ),
        'priority' => 50,
        'callback' => 'amotos_template_single_car_doors',
        'car_id' => $car_id
    );

    $overview['seats'] = array(
        'title'    => esc_html__( 'Seats', 'auto-moto-stock' ),
        'priority' => 60,
        'callback' => 'amotos_template_single_car_seats',
        'car_id' => $car_id
    );

    $overview['owners'] = array(
        'title'    => esc_html__( 'Owners', 'auto-moto-stock' ),
        'priority' => 70,
        'callback' => 'amotos_template_single_car_owners',
        'car_id' => $car_id
    );

    $overview['year'] = array(
        'title'    => esc_html__( 'Year Vehicle', 'auto-moto-stock' ),
        'priority' => 80,
        'callback' => 'amotos_template_single_car_year',
        'car_id' => $car_id
    );

    $overview['mileage'] = array(
        'title'    => esc_html__( 'Mileage', 'auto-moto-stock' ),
        'priority' => 90,
        'callback' => 'amotos_template_single_car_mileage',
        'car_id' => $car_id
    );

    $overview['power'] = array(
        'title'    => esc_html__( 'Power', 'auto-moto-stock' ),
        'priority' => 100,
        'callback' => 'amotos_template_single_car_power',
        'car_id' => $car_id
    );

    $overview['volume'] = array(
        'title'    => esc_html__( 'Cubic Capacity', 'auto-moto-stock' ),
        'priority' => 110,
        'callback' => 'amotos_template_single_car_volume',
        'car_id' => $car_id
    );

    $overview['label'] = array(
        'title'    => esc_html__( 'Label', 'auto-moto-stock' ),
        'priority' => 120,
        'callback' => 'amotos_template_single_car_label',
        'car_id' => $car_id
    );

    $priority = 140;
    $additional_fields = amotos_render_additional_fields();
    foreach ( $additional_fields as $key => $field ) {
        $car_field         = get_post_meta( $car_id, $field['id'], true );
        $car_field_content = $car_field;
        if ( $field['type'] == 'checkbox_list' ) {
            $car_field_content = '';
            if ( is_array( $car_field ) ) {
                foreach ( $car_field as $value => $v ) {
                    $car_field_content .= $v . ', ';
                }
            }
            $car_field_content = rtrim( $car_field_content, ', ' );
        }
        if ( $field['type'] === 'textarea' ) {
            $car_field_content = wpautop( $car_field_content );
        }
        if ( ! empty( $car_field_content ) ) {
            $overview[ $field['id'] ] = array(
                'title'    => $field['title'],
                'content'  => '<span>' . $car_field_content . '</span>',
                'priority' => $priority,
            );
        }
        $priority+= 10;
    }

    $additional_stylings = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'additional_stylings', true );
    if ( !empty($additional_stylings) ) {
        $additional_styling_title = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_title', true );
        $additional_styling_value = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'additional_styling_value', true );
        if (!empty($additional_styling_title) && !empty($additional_styling_value)) {
            for ( $i = 0; $i < $additional_stylings; $i ++ ) {
                if ( ! empty( $additional_styling_title[ $i ] ) && ! empty( $additional_styling_value[ $i ] ) ) {
                    $overview[ sanitize_title( $additional_styling_title[ $i ] ) ] = array(
                        'title'    => $additional_styling_title[ $i ],
                        'content'  => '<span>' . $additional_styling_value[ $i ] . '</span>',
                        'priority' => $priority,
                    );
                    $priority+= 10;
                }
            }
        }
    }

    $overview = apply_filters( 'amotos_single_car_overview', $overview );

    uasort( $overview, 'amotos_sort_by_order_callback' );

    $overview = array_map( 'amotos_content_callback', $overview );

    return array_filter( $overview, 'amotos_filter_content_callback' );
}

function amotos_get_car_stylings( $args = array() ) {
    $args     = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $stylings = get_the_terms( $args['car_id'], 'car-styling' );

    if ( is_a( $stylings, 'WP_Error' ) ) {
        return false;
    }

    return $stylings;
}

function amotos_get_car_video( $args = array() ) {
    $args     = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_id = $args['car_id'];
    $car_video       = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_video_url', true );
    if ($car_video == '') {
        return false;
    }
    $car_video_image = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_video_image', true );
    return array(
        'video_url'   => $car_video,
        'video_image' => $car_video_image
    );

}

function amotos_get_car_virtual_360($args = array()) {
    $args     = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_id = $args['car_id'];
    $car_image_360         = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_image_360', true );
    $car_image_360         = ( isset( $car_image_360 ) && is_array( $car_image_360 ) ) ? $car_image_360['url'] : '';
    $car_virtual_360      = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_virtual_360', true );
    $car_virtual_360_type = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_virtual_360_type', true );
    if ( empty( $car_virtual_360_type ) ) {
        $car_virtual_360_type = '0';
    }
    if ( ! empty( $car_virtual_360 ) || $car_image_360 != '' ) {
        return array(
            'car_image_360'         => $car_image_360,
            'car_virtual_360'      => $car_virtual_360,
            'car_virtual_360_type' => $car_virtual_360_type
        );
    }

    return false;

}

function amotos_get_car_price_slider($status = '') {
    $min_price = amotos_get_option('car_price_slider_min',200);
    $max_price = amotos_get_option('car_price_slider_max',2500000);
    if (!empty($status)) {
        $car_price_slider_search_field = amotos_get_option('car_price_slider_search_field','');
        if ($car_price_slider_search_field != '') {
            foreach ($car_price_slider_search_field as $data) {
                $term_id =(isset($data['car_price_slider_car_status']) ? $data['car_price_slider_car_status'] : '');
                $term = get_term_by('id', $term_id, 'car-status');
                if($term)
                {
                    if ( $term->slug == $status)
                    {
                        $min_price = (isset($data['car_price_slider_min']) ? $data['car_price_slider_min'] : $min_price);
                        $max_price = (isset($data['car_price_slider_max']) ? $data['car_price_slider_max'] : $max_price);
                        break;
                    }
                }
            }
        }
    }
    return [
        'min-price' => $min_price,
        'max-price' => $max_price
    ];
}

function amotos_get_car_price_dropdown( $status = '' ) {
    $car_price_dropdown_min = apply_filters( 'amotos_price_dropdown_min_default', amotos_get_option( 'car_price_dropdown_min', '0,100,300,500,700,900,1100,1300,1500,1700,1900' ) );
    $car_price_dropdown_max = apply_filters( 'amotos_price_dropdown_max_default', amotos_get_option( 'car_price_dropdown_max', '200,400,600,800,1000,1200,1400,1600,1800,2000' ) );
    if ( ! empty( $status ) ) {
        $car_price_dropdown_search_field = amotos_get_option( 'car_price_dropdown_search_field', '' );
        if ( ! empty( $car_price_dropdown_search_field ) && is_array( $car_price_dropdown_search_field ) ) {
            foreach ( $car_price_dropdown_search_field as $data ) {
                $term_id = $data['car_price_dropdown_car_status'] ?? '';
                $term    = get_term_by( 'id', $term_id, 'car-status' );
                if ( $term ) {
                    if ( $term->slug == $status ) {
                        $car_price_dropdown_min = $data['car_price_dropdown_min'] ?? $car_price_dropdown_min;
                        $car_price_dropdown_max = $data['car_price_dropdown_max'] ?? $car_price_dropdown_max;
                        break;
                    }
                }
            }
        }
    }

    return [
        'min-price' => $car_price_dropdown_min,
        'max-price' => $car_price_dropdown_max,
    ];
}

function amotos_get_car_query_args($atts = array(), $query_args = array()) {
    return AMOTOS_Query::get_instance()->get_car_query_args($atts, $query_args);
}

function amotos_get_car_query_parameters() {
    return AMOTOS_Query::get_instance()->get_parameters();
}

function amotos_get_car_sort_by() {
    return apply_filters('amotos_car_sort_by',[
        'default' => esc_html__('Default Order','auto-moto-stock'),
        'featured' => esc_html__('Featured','auto-moto-stock'),
        'most_viewed' => esc_html__('Most Viewed','auto-moto-stock'),
        'a_price' => esc_html__('Price (Low to High)','auto-moto-stock'),
        'd_price' => esc_html__('Price (High to Low)','auto-moto-stock'),
        'a_date' => esc_html__('Date (Old to New)','auto-moto-stock'),
        'd_date' => esc_html__('Date (New to Old)','auto-moto-stock')
    ]);
}

function amotos_get_loop_car_featured_label($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $meta = array();

    $meta['featured'] = array(
        'priority' => 10,
        'callback' => 'amotos_template_loop_car_featured',
        'car_id' => $car_id
    );

    $meta['label'] = array(
        'priority' => 20,
        'callback' => 'amotos_template_loop_car_term_label',
        'car_id' => $car_id
    );


    $meta = apply_filters( 'amotos_loop_car_featured_label', $meta );

    uasort( $meta, 'amotos_sort_by_order_callback' );

    $meta = array_map( 'amotos_content_callback', $meta );

    return array_filter( $meta, 'amotos_filter_content_callback' );
}

function amotos_get_manager_info_of_car($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $manager_display_option = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'manager_display_option',true);
    $car_manager       = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'car_manager', true);
    $manager_name           = $manager_link = '';
    if ( $manager_display_option == 'author_info' ) {
        $user_id = get_post_field( 'post_author', $car_id );
        $user_info = get_userdata( $user_id );
        if ( empty( $user_info->first_name ) && empty( $user_info->last_name ) ) {
            $manager_name = $user_info->user_login;
        } else {
            $manager_name = $user_info->first_name . ' ' . $user_info->last_name;
        }
        if ( !empty($author_manager_id) && (get_post_status( $author_manager_id ) == 'publish') ) {
            $manager_link = get_the_permalink( $author_manager_id );
        } else {
            $manager_link = '#';
        }

    } elseif ( $manager_display_option == 'other_info' ) {
        $manager_name = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_name' , true);
    } elseif ( $manager_display_option == 'manager_info' && ! empty( $car_manager ) ) {
        $manager_name = get_the_title( $car_manager );
        $manager_link = get_the_permalink( $car_manager );
    }

    if (empty($manager_name)) {
        return false;
    }

    if (empty($manager_link)) {
        $manager_link = '#';
    }

    return array(
        'name' => $manager_name,
        'link' => $manager_link
    );
}

function amotos_get_manager_contact_info_of_car($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $manager_display_option = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'manager_display_option',true);
    $manager_id       = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'car_manager', true);
    $user_id = '';
    $manager_name = $manager_link = $email = $avatar_src = $manager_position = $manager_facebook_url = $manager_twitter_url = $manager_linkedin_url = $manager_pinterest_url = $manager_skype = $manager_youtube_url = $manager_vimeo_url = $manager_mobile_number = $manager_office_address = $manager_website_url = $manager_description = '';
    $manager_type = $manager_instagram_url = '';
    if ($manager_display_option === 'manager_info') {
        $car_manager_status = get_post_status($manager_id);
        if ($car_manager_status !== 'publish') {
            $manager_display_option = 'author_info';
        }
    }

    $avatar_width = 270;
    $avatar_height = 340;
    $avatar_size = apply_filters('amotos_single_car_contact_manager_avatar_size','270x340') ;
    if (preg_match('/\d+x\d+/', $avatar_size)) {
        $avatar_sizes = explode('x', $avatar_size);
        $avatar_width = $avatar_sizes[0];
        $avatar_height = $avatar_sizes[1];
    }

    $no_avatar_src = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
    $default_avatar=amotos_get_option('default_user_avatar','');
    if($default_avatar!='')
    {
        if(is_array($default_avatar)&& $default_avatar['url']!='')
        {
            $resize = amotos_image_resize_url($default_avatar['url'], $avatar_width, $avatar_height, true);
            if ($resize != null && is_array($resize)) {
                $no_avatar_src = $resize['url'];
            }
        }
    }

    if ( $manager_display_option == 'author_info' ) {
        $user_id = get_post_field( 'post_author', $car_id );
        $user_info = get_userdata( $user_id );
        if ( empty( $user_info->first_name ) && empty( $user_info->last_name ) ) {
            $manager_name = $user_info->user_login;
        } else {
            $manager_name = $user_info->first_name . ' ' . $user_info->last_name;
        }

        $author_manager_id = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id );
        if (!empty($author_manager_id) && (get_post_status( $author_manager_id ) == 'publish') ) {
            $manager_position = esc_html__( 'Vehicle Manager', 'auto-moto-stock' );
            $manager_type = esc_html__( 'Manager', 'auto-moto-stock' );
            $manager_link = get_the_permalink($author_manager_id);
        } else {
            $manager_position = esc_html__( 'Vehicle Seller', 'auto-moto-stock' );
            $manager_type = esc_html__( 'Seller', 'auto-moto-stock' );
            $manager_link = '#';
        }

        $email = $user_info->user_email;
        $author_picture_id = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_picture_id', $user_id );
        $avatar_src = amotos_image_resize_id($author_picture_id, $avatar_width, $avatar_height, true);

        $manager_facebook_url   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_facebook_url', $user_id );
        $manager_twitter_url    = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_twitter_url', $user_id );
        $manager_linkedin_url   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $user_id );
        $manager_pinterest_url  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $user_id );
        $manager_instagram_url  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_instagram_url', $user_id );
        $manager_skype          = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_skype', $user_id );
        $manager_youtube_url    = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_youtube_url', $user_id );
        $manager_vimeo_url      = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $user_id );

        $manager_mobile_number  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_mobile_number', $user_id );
        $manager_office_address = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_office_address', $user_id );
        $manager_website_url    = get_the_author_meta( 'user_url', $user_id );

    } elseif ( $manager_display_option == 'other_info' ) {
        $manager_name = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_name' , true);

        $email = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_mail' , true);
        $manager_mobile_number = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_phone' , true);
        $manager_description = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_other_contact_description' , true);

    } elseif ( $manager_display_option == 'manager_info' && ! empty( $manager_id ) ) {
        $manager_name = get_the_title( $manager_id );
        $manager_link = get_the_permalink( $manager_id );
        $email = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_email', true);
        $avatar_id = get_post_thumbnail_id($manager_id);
        $avatar_src = amotos_image_resize_id($avatar_id, $avatar_width, $avatar_height, true);
        $manager_position = esc_html__( 'Vehicle Manager', 'auto-moto-stock' );
        $manager_type = esc_html__( 'Manager', 'auto-moto-stock' );

        $manager_facebook_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_facebook_url', true);
        $manager_twitter_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_twitter_url', true);
        $manager_linkedin_url =  get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_linkedin_url', true);
        $manager_pinterest_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_pinterest_url', true);
        $manager_instagram_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_instagram_url', true);
        $manager_skype = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_skype', true);
        $manager_youtube_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_youtube_url', true);
        $manager_vimeo_url =  get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_vimeo_url', true);

        $manager_mobile_number = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_mobile_number', true);
        $manager_office_address = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_office_address', true);
        $manager_website_url = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_website_url', true);
    }

    if (empty($manager_link)) {
        $manager_link = '#';
    }

    $is_login= true;
    $hide_contact_information_if_not_login = amotos_get_option( 'hide_contact_information_if_not_login', 0 );
    if (filter_var($hide_contact_information_if_not_login,FILTER_VALIDATE_BOOLEAN)) {
        $is_login = is_user_logged_in();
    }

    return array(
        'name' => $manager_name,
        'link' => $manager_link,
        'display_type' => $manager_display_option,
        'email' => $email,
        'avatar' => $avatar_src,
        'position' => $manager_position,
        'type' => $manager_type,
        'facebook' => $manager_facebook_url,
        'twitter' => $manager_twitter_url,
        'linkedin' => $manager_linkedin_url,
        'pinterest' => $manager_pinterest_url,
        'instagram' => $manager_instagram_url,
        'skype' => $manager_skype,
        'youtube' => $manager_youtube_url,
        'vimeo' => $manager_vimeo_url,
        'mobile' => $manager_mobile_number,
        'office' => $manager_office_address,
        'website' => $manager_website_url,
        'no_avatar_src' => $no_avatar_src,
        'is_login' => $is_login,
        'desc' => $manager_description,
        'user_id' => $user_id,
        'manager_id' => $manager_id
    );
}

function amotos_get_loop_car_image_size_default()
{
    return apply_filters('amotos_loop_car_image_size_default', '330x180');
}

function amotos_get_sc_car_gallery_image_size_default()
{
    return apply_filters('amotos_sc_car_gallery_image_size_default', '290x270');
}

function amotos_get_sc_car_slider_image_size_default()
{
    return apply_filters('amotos_sc_car_slider_image_size_default', '1200x600');
}

function amotos_get_sc_car_slider_thumb_image_size_default()
{
    return apply_filters('amotos_sc_car_slider_thumb_image_size_default', '170x90');
}

function amotos_get_single_car_gallery_image_size()
{
    return apply_filters('amotos_single_car_gallery_image_size', '870x420');
}

function amotos_get_single_car_gallery_thumb_image_size()
{
    return apply_filters('amotos_single_car_gallery_thumb_image_size', '250x130');
}

function amotos_car_get_rating($carId) {
    $data = get_post_meta($carId, AMOTOS_METABOX_PREFIX . 'car_rating', true);
    if (!is_array($data)) {
        $data = array(
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        );
    }
    return $data;
}

function amotos_car_get_list_review($carId,$userId)
{
    global $wpdb;
    return $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->comments as cm
                                       LEFT JOIN $wpdb->commentmeta as mt ON cm.comment_ID = mt.comment_id
                                       WHERE cm.comment_post_ID = %d
                                       AND (cm.comment_approved = 1 OR cm.user_id = %d)  
                                       AND mt.meta_key = 'car_rating'
                                       ORDER BY cm.comment_ID DESC",
        $carId,
        $userId) );
}

function amotos_car_get_review_by_user_id($carId, $userId) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT cm.comment_ID, cm.comment_content, mt.meta_value as rate FROM $wpdb->comments as cm 
                                             INNER JOIN $wpdb->commentmeta as mt ON cm.comment_ID = mt.comment_id 
                                             WHERE 
                                                cm.comment_post_ID = %d 
                                                AND cm.user_id = %d
                                                AND mt.meta_key = 'car_rating'
                                             ORDER BY cm.comment_ID DESC",
        $carId,
        $userId));
}

function amotos_car_get_price_data($carId) {
    $price            = get_post_meta( $carId, AMOTOS_METABOX_PREFIX . 'car_price', true );
    $price_short      = get_post_meta( $carId, AMOTOS_METABOX_PREFIX . 'car_price_short', true );
    $price_unit       = get_post_meta( $carId, AMOTOS_METABOX_PREFIX . 'car_price_unit', true );
    $price_prefix     = get_post_meta( $carId, AMOTOS_METABOX_PREFIX . 'car_price_prefix', true );
    $price_postfix    = get_post_meta( $carId, AMOTOS_METABOX_PREFIX . 'car_price_postfix', true );
    $empty_price_text = amotos_get_option( 'empty_price_text' );
    return array(
        'price'            => $price,
        'price_short'      => $price_short,
        'price_unit'       => $price_unit,
        'price_prefix'     => $price_prefix,
        'price_postfix'    => $price_postfix,
        'empty_price_text' => $empty_price_text
    );
}

function amotos_car_get_status($carId) {
    $car_item_status = get_the_terms( $carId, 'car-status' );
    if ( $car_item_status === false || is_a( $car_item_status, 'WP_Error' ) ) {
        return false;
    }
    return $car_item_status;
}
function amotos_car_get_address_data($carId = '') {
    if (empty($carId)) {
        $carId = get_the_ID();
    }
    $car_address   = get_post_meta($carId,AMOTOS_METABOX_PREFIX . 'car_address', TRUE);
    if (empty($car_address)) {
        return false;
    }

    $position = amotos_car_get_map_position($carId);
    if ($position && !empty($position['address'])) {
        $car_address = $position['address'];
    }
    $google_map_address_url = "http://maps.google.com/?q=" . $car_address;
    return array(
        'car_address'       => $car_address,
        'google_map_address_url' => $google_map_address_url,
    );
}

function amotos_get_single_car_address_data() {
    $meta = array();

    $car_address = get_post_meta( get_the_ID(), AMOTOS_METABOX_PREFIX . 'car_address', true );
    if ( ! empty( $car_address ) ) {
        $meta['address'] = array(
            'priority' => 10,
            'label'    => esc_html__( 'Address', 'auto-moto-stock' ),
            'content'  => $car_address
        );
    }

    $car_country = get_post_meta( get_the_ID(), AMOTOS_METABOX_PREFIX . 'car_country', true );
    if ( ! empty( $car_country ) ) {
        $car_country = amotos_get_country_by_code( $car_country );
        $meta['country']  = array(
            'priority' => 20,
            'label'    => esc_html__( 'Country', 'auto-moto-stock' ),
            'content'  => $car_country
        );
    }

    $car_state = get_the_term_list( get_the_ID(), 'car-state', '', ', ', '' );
    if ( ! empty( $car_state ) ) {
        $meta['state'] = array(
            'priority' => 30,
            'label'    => esc_html__( 'Province/State', 'auto-moto-stock' ),
            'content'  => $car_state
        );
    }


    $car_city = get_the_term_list( get_the_ID(), 'car-city', '', ', ', '' );
    if ( ! empty( $car_city ) ) {
        $meta['city'] = array(
            'priority' => 40,
            'label'    => esc_html__( 'City/Town', 'auto-moto-stock' ),
            'content'  => $car_city
        );
    }

    $car_neighborhood = get_the_term_list( get_the_ID(), 'car-neighborhood', '', ', ', '' );
    if ( ! empty( $car_neighborhood ) ) {
        $meta['neighborhood'] = array(
            'priority' => 50,
            'label'    => esc_html__( 'Neighborhood', 'auto-moto-stock' ),
            'content'  => $car_neighborhood
        );
    }

    $car_zip = get_post_meta( get_the_ID(), AMOTOS_METABOX_PREFIX . 'car_zip', true );
    if ( ! empty( $car_zip ) ) {
        $meta['zip'] = array(
            'priority' => 50,
            'label'    => esc_html__( 'Postal code/ZIP', 'auto-moto-stock' ),
            'content'  => $car_zip
        );
    }


    $meta = apply_filters( 'amotos_single_car_address', $meta );

    uasort( $meta, 'amotos_sort_by_order_callback' );

    $meta = array_map( 'amotos_content_callback', $meta );

    return array_filter( $meta, 'amotos_filter_content_callback' );
}

function amotos_car_get_map_position($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_location = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'car_location',TRUE);
    if (empty($car_location)) {
        return false;
    }
    list( $lat, $lng ) =  isset($car_location['location']) && !empty($car_location['location']) ? explode( ',', $car_location['location'] ) : array('', '');
    if (empty($lng) || empty($lat)) {
        return false;
    }

    $address = isset($car_location['address']) ? $car_location['address'] : '';

    return array(
        'lat' => floatval($lat) ,
        'lng' => floatval($lng),
        'address' => $address
    );

}

function amotos_car_get_map_marker( $id ) {
    $categories     = get_the_terms( $id, 'car-type' );
    $first_category = $categories ? $categories[0] : false;
    $marker         = false;
    if ( $first_category ) {
        $marker_image = get_term_meta( $first_category->term_id, 'car_type_icon', true );
        if ( is_array( $marker_image ) && isset( $marker_image['url'] ) && ! empty( $marker_image['url'] ) ) {
            $marker_html = sprintf( '<img src="%s" />', esc_url( $marker_image['url'] ) );
            $marker = array(
                'type' => 'image',
                'html' => $marker_html
            );
        }
    }
    return $marker;
}

function amotos_car_get_location_data($car_id) {
    $car = get_post($car_id);
    if (!is_a($car,'WP_Post')) {
        return false;
    }
    $position = amotos_car_get_map_position($car->ID);
    if ($position === false) {
        return  false;
    }
    ob_start();
    amotos_template_loop_car_price($car_id);
    $price = ob_get_clean();

    ob_start();
    amotos_template_loop_car_image( array(
        'car_id'       => $car_id,
        'image_size' => '100x100',
    ));
    $car_image = ob_get_clean();

    $address_data = amotos_car_get_address_data();
    $address = '';
    if ($address_data !== false) {
        $address = $address_data['car_address'];
    }

    return array(
        'position' => $position,
        'marker' => amotos_car_get_map_marker($car_id),
        'popup' => array(
            'title' => get_the_title($car_id),
            'url' => get_the_permalink($car_id),
            'thumb' => $car_image,
            'price' => $price,
            'address' => $address,
        )
    );
}

function amotos_car_get_nearby_places_data($car_id) {
    $position = amotos_car_get_map_position($car_id);
    if ($position === false) {
        return false;
    }
    $nearby_places_radius = amotos_get_option('nearby_places_radius');
    $nearby_places_rank_by = amotos_get_option('nearby_places_rank_by');
    $nearby_places_distance_in = amotos_get_option('nearby_places_distance_in');
    $nearby_places_field = amotos_get_option('nearby_places_field');
    $fields = array();
    $types = array();
    if (is_array($nearby_places_field)) {
        foreach ($nearby_places_field as $k => $v) {
            $type = isset($v['nearby_places_select_field_type']) ? $v['nearby_places_select_field_type'] : '';
            $label = isset($v['nearby_places_field_label']) ? $v['nearby_places_field_label'] : '';
            $icon = isset($v['nearby_places_field_icon']) && isset($v['nearby_places_field_icon']['url']) ? $v['nearby_places_field_icon']['url'] : '';
            if (in_array($type,array('establishment','food','health','place_of_worship'))) {
                continue;
            }
            $fields[$type] = array(
              'icon' => $icon,
              'label' => $label
            );
            $types[] = $type;
        }
    }
    if (empty($nearby_places_radius)) {
        $nearby_places_radius = '5000';
    }

    if (empty($fields)) {
        return false;
    }

    $separator = amotos_get_price_decimal_separator();
    $map_height = amotos_get_option('set_map_height');
    if (empty($map_height)) {
        $map_height = '475';
    }

    return apply_filters('amotos_car_nearby_places_data',array(
        'radius' => $nearby_places_radius,
        'rankPreference' => $nearby_places_rank_by,
        'unit' => $nearby_places_distance_in,
        'fields' => $fields,
        'types' => $types,
        'position' => $position,
        'separator' => $separator,
        'map_height' => $map_height,
        'i18n' => array(
            'no_result' => esc_html__( 'No result!', 'auto-moto-stock' )
        )
    ));
}

function amotos_car_get_walk_score_data($car_id) {

    $api_key = amotos_get_option('walk_score_api_key', '');
    if (empty($api_key)) {
        return false;
    }

    $position = amotos_car_get_map_position($car_id);
    if ($position === false) {
        return false;
    }

    $url_request = sprintf("http://api.walkscore.com/score?format=json&transit=1&bike=1&address=%s&lat=%s&lon=%s&wsapikey=%s",
        urlencode($position['address']),
        $position['lat'],
        $position['lng'],
        $api_key);
    $request = wp_remote_get($url_request);

    if( is_wp_error( $request ) ) {
        return false;
    }

    $body = wp_remote_retrieve_body( $request );

    $data = json_decode( $body );

    if( empty( $data ) ) {
        return false;
    }

    if ($data->status !== 1) {
        return false;
    }

    $items = array();
    if (!empty($data->walkscore)) {
        $items['walk'] = array(
            'title' => esc_html__('Walk Score', 'auto-moto-stock'),
            'score' => $data->walkscore,
            'desc' => $data->description ?? '',
            'url' => $data->ws_link ?? ''
        );
    }

    if (isset($data->transit) && !empty($data->transit->score)) {
        $items['transit'] = array(
            'title' => esc_html__('Transit Score', 'auto-moto-stock'),
            'score' => $data->transit->score,
            'desc' => $data->transit->description ?? '',
            'url' => $data->ws_link ?? ''
        );
    }

    if (isset($data->bike) && !empty($data->bike->score)) {
        $items['bike'] = array(
            'title' => esc_html__('Bike Score', 'auto-moto-stock'),
            'score' => $data->bike->score,
            'desc' => $data->bike->description ?? '',
            'url' => $data->ws_link ?? ''
        );
    }

    return apply_filters('amotos_car_walk_score_data',array(
        'logo' =>  $data->logo_url ?? '',
        'items' => $items
    ));
}

function amotos_car_get_total_views($car_id) {
    return  AMOTOS_Car::getInstance()->get_total_views($car_id);
}

function amotos_car_get_total_by_user($manager_id, $user_id) {
    return AMOTOS_Car::getInstance()->get_total_cars_by_user( $manager_id, $user_id );
}

function amotos_my_car_get_action($car_id) {
    $actions = array(
        'edit' => array(
            'label' => __('Edit', 'auto-moto-stock'),
            'tooltip' => __('Edit vehicle', 'auto-moto-stock'),
            'nonce' => false,
            'confirm' => ''
        ),
        'mark_featured' => array(
            'label' => __('Mark featured', 'auto-moto-stock'),
            'tooltip' => __('Make this a Featured Vehicle', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to mark this vehicle as Featured?', 'auto-moto-stock')
        ),
        'allow_edit' => array(
            'label' => __('Allow Editing', 'auto-moto-stock'),
            'tooltip' => __('This vehicle listing belongs to an expired Package therefore if you wish to edit it, it will be charged as a new listing from your current Package.', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to allow editing this vehicle listing?', 'auto-moto-stock')
        ),
        'remove_featured' => array(
            'label' => __('Remove featured', 'auto-moto-stock'),
            'tooltip' => __('Remove Featured of Vehicle', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to remove featured of vehicle?', 'auto-moto-stock')
        ),
        'relist_per_package' => array(
            'label' => __('Reactivate Listing', 'auto-moto-stock'),
            'tooltip' => __('Reactivate Listing', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to reactivate this vehicle?', 'auto-moto-stock')
        ),
        'relist_per_listing' => array(
            'label' => __('Resend this Listing for Approval', 'auto-moto-stock'),
            'tooltip' => __('Resend this Listing for Approval', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to resend this vehicle for approval?', 'auto-moto-stock')
        ),
        'show' => array(
            'label' => __('Show', 'auto-moto-stock'),
            'tooltip' => __('Show Vehicle', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to show this vehicle?', 'auto-moto-stock')
        ),
        'delete' => array(
            'label' => __('Delete', 'auto-moto-stock'),
            'tooltip' => __('Delete Vehicle', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to delete this vehicle?', 'auto-moto-stock')
        ),
        'hidden' => array(
            'label' => __('Hide', 'auto-moto-stock'),
            'tooltip' => __('Hide Vehicle', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to hide this vehicle?', 'auto-moto-stock')
        ),
        'payment_listing' => array(
            'label' => __('Pay Now', 'auto-moto-stock'),
            'tooltip' => __('Pay for this vehicle listing', 'auto-moto-stock'),
            'nonce' => true,
            'confirm' => esc_html__('Are you sure you want to pay for this listing?', 'auto-moto-stock')
        )
    );
    $actions_key = array();
    $post_status = get_post_status($car_id);
    $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $veh_featured = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true);
    $check_package = AMOTOS_Profile::getInstance()->user_package_available($user_id);
    $payment_status = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'payment_status', true);
    $price_per_listing = amotos_get_option('price_per_listing', 0);
    switch ($post_status) {
        case 'publish':
            if ($paid_submission_type === 'per_package') {
                $current_package_key = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_key', $user_id);
                $car_package_key = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'package_key', true);
                if (!empty($car_package_key) && ($current_package_key == $car_package_key)) {
                    if (($check_package != -1) && ($check_package != 0)) {
                        $actions_key[] = 'edit';
                        $package_num_featured_listings = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_featured', $user_id);
                        if (($package_num_featured_listings > 0) && ($veh_featured != 1)) {
                            $actions_key[] = 'mark_featured';
                        }
                    }
                }

                if (($current_package_key != $car_package_key) && ($check_package == 1)) {
                    $actions_key[] = 'allow_edit';
                }
            } else {
                if (($paid_submission_type === 'per_listing') && ($veh_featured != 1) ) {
                    $actions_key[] = 'mark_featured';
                }
                $actions_key[] = 'edit';
            }

            if ($veh_featured == 1) {
                $actions_key[] = 'remove_featured';
            }
            $actions_key[] = 'hidden';
            break;
        case 'expired':
            if ($paid_submission_type === 'per_package') {
                if ($check_package == 1) {
                    $actions_key[] = 'relist_per_package';
                }
            }

            if (($paid_submission_type === 'per_listing') && (($payment_status === 'paid') || ($price_per_listing <= 0))  ) {
                $actions_key[] = 'relist_per_listing';
            }
            break;
        case 'pending':
            $actions_key[] = 'edit';
            break;
        case 'hidden':
            $actions_key[] = 'show';
            break;
    }

    $actions_key[] = 'delete';

    if (($paid_submission_type == 'per_listing') && ($payment_status != 'paid') && ($post_status != 'hidden')) {
        if ($price_per_listing > 0) {
            $actions_key[] = 'payment_listing';
        }
    }

    foreach ($actions as $k => $v) {
        if (!in_array($k,$actions_key)) {
            unset($actions[$k]);
        }
    }
    $actions = apply_filters('amotos_my_cars_actions', $actions, get_post($car_id));
    return $actions;
}

function amotos_get_my_car_image_size()
{
    return apply_filters('amotos_my_car_image_size', '150x150');
}

/**
 * Check current user is amotos_customer role
 *
 * @return bool
 */
function amotos_is_cap_customer() {
    return current_user_can('amotos_customer') || current_user_can('administrator');
}
