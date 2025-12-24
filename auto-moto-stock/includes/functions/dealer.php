<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function amotos_dealer_get_sort_by() {
    return apply_filters('amotos_dealer_sort_by',array(
        'default' => esc_html__('Default Order','auto-moto-stock'),
        'a_name' => esc_html__('Name (A to Z)','auto-moto-stock'),
        'd_name' => esc_html__('Name (Z to A)','auto-moto-stock'),
        'a_date' => esc_html__('Date (Old to New)','auto-moto-stock'),
        'd_date' => esc_html__('Date (New to Old)','auto-moto-stock')
    ));
}

function amotos_dealer_get_social_data($dealer_id) {
    $data = array();
    $vimeo = get_term_meta( $dealer_id, 'dealer_vimeo_url', true );
    $facebook = get_term_meta( $dealer_id, 'dealer_facebook_url', true );
    $twitter = get_term_meta( $dealer_id, 'dealer_twitter_url', true );
    $linkedin = get_term_meta( $dealer_id, 'dealer_linkedin_url', true );
    $pinterest = get_term_meta( $dealer_id, 'dealer_pinterest_url', true );
    $instagram = get_term_meta( $dealer_id, 'dealer_instagram_url', true );
    $skype = get_term_meta( $dealer_id, 'dealer_skype', true );
    $youtube = get_term_meta( $dealer_id, 'dealer_youtube_url', true );

    if (!empty($facebook)) {
        $data['facebook'] = array(
            'priority' => 10,
            'label'    => esc_html__( 'Facebook', 'auto-moto-stock' ),
            'icon' => 'fa fa-facebook',
            'link' => $facebook,
        );
    }

    if (!empty($twitter)) {
        $data['twitter'] = array(
            'priority' => 20,
            'label'    => esc_html__( 'Twitter', 'auto-moto-stock' ),
            'icon' => 'fa fa-twitter',
            'link' => $twitter,
        );
    }

    if (!empty($skype)) {
        $data['skype'] = array(
            'priority' => 30,
            'label'    => esc_html__( 'Skype', 'auto-moto-stock' ),
            'icon' => 'fa fa-skype',
            'link' => $skype,
        );
    }

    if (!empty($linkedin)) {
        $data['linkedin'] = array(
            'priority' => 40,
            'label'    => esc_html__( 'Linkedin', 'auto-moto-stock' ),
            'icon' => 'fa fa-linkedin',
            'link' => $linkedin,
        );
    }

    if (!empty($pinterest)) {
        $data['pinterest'] = array(
            'priority' => 50,
            'label'    => esc_html__( 'Pinterest', 'auto-moto-stock' ),
            'icon' => 'fa fa-pinterest',
            'link' => $pinterest,
        );
    }

    if (!empty($instagram)) {
        $data['instagram'] = array(
            'priority' => 60,
            'label'    => esc_html__( 'Instagram', 'auto-moto-stock' ),
            'icon' => 'fa fa-instagram',
            'link' => $instagram,
        );
    }

    if (!empty($youtube)) {
        $data['youtube'] = array(
            'priority' => 70,
            'label'    => esc_html__( 'Youtube', 'auto-moto-stock' ),
            'icon' => 'fa fa-youtube-play',
            'link' => $youtube,
        );
    }

    if (!empty($vimeo)) {
        $data['vimeo'] = array(
            'priority' => 80,
            'label'    => esc_html__( 'Vimeo', 'auto-moto-stock' ),
            'icon' => 'fa fa-vimeo',
            'link' => $vimeo,
        );
    }

    $data = apply_filters( 'amotos_dealer_social_data', $data );
    uasort( $data, 'amotos_sort_by_order_callback' );
    return $data;
}

function amotos_dealer_get_contact_data_single($dealer_id) {
    $data = amotos_dealer_get_contact_data($dealer_id);
    if (isset($data['licenses'])) {
        unset($data['licenses']);
    }
    $data = apply_filters( 'amotos_dealer_contact_data_single', $data );
    uasort( $data, 'amotos_sort_by_order_callback' );
    return $data;
}

function amotos_dealer_get_contact_data($dealer_id) {
    $data = array();

    $email = get_term_meta( $dealer_id, 'dealer_email', true );
    $mobile_number = get_term_meta( $dealer_id, 'dealer_mobile_number', true );
    $fax_number = get_term_meta( $dealer_id, 'dealer_fax_number', true );
    $licenses = get_term_meta( $dealer_id, 'dealer_licenses', true );
    $office_number = get_term_meta( $dealer_id, 'dealer_office_number', true );
    $website_url = get_term_meta( $dealer_id, 'dealer_website_url', true );

    if (!empty($office_number)) {
        $data['office_number'] = array(
            'priority' => 10,
            'label'    => esc_html__( 'Phone', 'auto-moto-stock' ),
            'icon' => 'fa fa-phone',
            'value' => $office_number,
        );
    }

    if (!empty($mobile_number)) {
        $data['mobile_number'] = array(
            'priority' => 20,
            'label'    => esc_html__( 'Mobile', 'auto-moto-stock' ),
            'icon' => 'fa fa-mobile-phone',
            'value' => $mobile_number,
        );
    }

    if (!empty($fax_number)) {
        $data['fax_number'] = array(
            'priority' => 30,
            'label'    => esc_html__( 'Fax', 'auto-moto-stock' ),
            'icon' => 'fa fa-print',
            'value' => $fax_number,
        );
    }

    if (!empty($email)) {
        $data['email'] = array(
            'priority' => 40,
            'label'    => esc_html__( 'Email', 'auto-moto-stock' ),
            'icon' => 'fa fa-envelope',
            'value' => $email,
        );
    }

    if (!empty($website_url)) {
        $data['website'] = array(
            'priority' => 50,
            'label'    => esc_html__( 'Website', 'auto-moto-stock' ),
            'icon' => 'fa fa-external-link-square',
            'value' => $website_url,
        );
    }

    if (!empty($licenses)) {
        $data['licenses'] = array(
            'priority' => 60,
            'label'    => esc_html__( 'Licenses', 'auto-moto-stock' ),
            'icon' => 'fa fa-balance-scale',
            'value' => $licenses,
        );
    }

    $data = apply_filters( 'amotos_dealer_contact_data', $data );
    uasort( $data, 'amotos_sort_by_order_callback' );
    return $data;
}

function amotos_dealer_get_meta_data($dealer_id) {
    $data = array();

    $total_car = amotos_dealer_get_total_car($dealer_id);
    $data['cars'] = array(
        'priority' => 10,
        'label'    => esc_html__( 'Vehicles', 'auto-moto-stock' ),
        'value' => $total_car,
    );

    $total_manager = amotos_dealer_get_total_manager($dealer_id);
    $data['staff'] = array(
        'priority' => 20,
        'label'    => esc_html__( 'Staff', 'auto-moto-stock' ),
        'value' => $total_manager,
    );

    $licenses = get_term_meta( $dealer_id, 'dealer_licenses', true );
    if (!empty($licenses)) {
        $data['licenses'] = array(
            'priority' => 30,
            'label'    => esc_html__( 'Licenses', 'auto-moto-stock' ),
            'value' => $licenses,
        );
    }

    $data = apply_filters( 'amotos_dealer_meta_data', $data );
    uasort( $data, 'amotos_sort_by_order_callback' );
    return $data;
}

function amotos_dealer_get_manager_ids($dealer_id) {
    $args         = array(
        'post_type'   => 'manager',
        'post_status' => 'publish',
        'numberposts' => -1,
        'tax_query'   => array(
            array(
                'taxonomy' => 'dealer',
                'terms'    => $dealer_id,
            )
        )
    );
    $posts = get_posts($args);
    $manager_ids = array();
    $manager_user_ids = array();

    foreach ($posts as $post) {
        $manager_ids[] = $post->ID;
        $manager_user_id = get_post_meta( $post->ID, AMOTOS_METABOX_PREFIX . 'manager_user_id', true );
        if ( ! empty( $manager_user_id ) ) {
            $manager_user_ids[] = $manager_user_id;
        }
    }
    return array(
        'manager_ids' => $manager_ids,
        'manager_user_ids' => $manager_user_ids
    );
}
function amotos_dealer_get_total_car($dealer_id) {
    $data = amotos_dealer_get_manager_ids($dealer_id);
    $manager_ids = $data['manager_ids'];
    $manager_user_ids = $data['manager_user_ids'];
    return amotos_car_get_total_by_user($manager_ids, $manager_user_ids);
}

function amotos_dealer_get_total_manager($dealer_id) {
    $total = 0;
    $dealer = get_term($dealer_id,'dealer');
    if (is_a($dealer,'WP_Term')) {
        $total = $dealer->count;
    }
    return $total;
}

function amotos_dealer_get_tabs($dealer_id) {

    $tabs = array();

    $tabs['overview'] = array(
        'title'    => esc_html__( 'Overview', 'auto-moto-stock' ),
        'priority' => 10,
        'callback' => 'amotos_template_single_dealer_overview',
        'dealer_id' => $dealer_id
    );

    $tabs['car'] = array(
        'title'    => esc_html__( 'Vehicles', 'auto-moto-stock' ),
        'priority' => 20,
        'callback' => 'amotos_template_single_dealer_car',
        'dealer_id' => $dealer_id
    );

    $tabs['location'] = array(
        'title'    => esc_html__( 'Location', 'auto-moto-stock' ),
        'priority' => 30,
        'callback' => 'amotos_template_single_dealer_location',
        'dealer_id' => $dealer_id
    );

    /*

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
    );*/

    $tabs = apply_filters( 'amotos_dealer_tabs', $tabs , $dealer_id);

    uasort( $tabs, 'amotos_sort_by_order_callback' );

    $tabs = array_map( 'amotos_content_callback', $tabs );

    return array_filter( $tabs, 'amotos_filter_content_callback' );
}

function amotos_dealer_get_map_position($dealer_id) {
    $location = get_term_meta( $dealer_id, 'dealer_map_address', true );
    if (empty($location)) {
        return false;
    }
    list( $lat, $lng ) =  isset($location['location']) && !empty($location['location']) ? explode( ',', $location['location'] ) : array('', '');
    if (empty($lng) || empty($lat)) {
        return false;
    }
    return array(
        'lat' => floatval($lat) ,
        'lng' => floatval($lng),
    );

}