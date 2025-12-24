<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

function amotos_template_loop_car_price( $car_id = '') {
    if (is_array($car_id)) {
        $args          = wp_parse_args( $car_id, array(
            'car_id' => get_the_ID(),
        ));
        $car_id = $args['car_id'];
    } elseif (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $data = amotos_car_get_price_data($car_id);
	amotos_get_template( 'loop/car-price.php', apply_filters('amotos_template_loop_car_price_args',$data));
}

function amotos_template_single_car_price($car_id = '' ) {
    if (is_array($car_id)) {
        $args          = wp_parse_args( $car_id, array(
            'car_id' => get_the_ID(),
        ));
        $car_id = $args['car_id'];
    } elseif (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $data = amotos_car_get_price_data($car_id);
    $data['extra_class'] = 'amotos__single-car-price';
    amotos_get_template( 'loop/car-price.php', apply_filters('amotos_template_single_car_price_args',$data));
}

function amotos_template_loop_car_title($car_id = '') {
	if (empty($car_id)) {
		$car_id = get_the_ID();
	}
	amotos_get_template('loop/car-title.php',array('car_id' => $car_id));
}

function amotos_template_loop_car_location($car_id = '') {
	if (empty($car_id)) {
		$car_id = get_the_ID();
	}
    $data = amotos_car_get_address_data($car_id);
    if ($data === false) {
        return;
    }

	amotos_get_template( 'loop/car-location.php',$data);
}

function amotos_template_single_car_location($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $data = amotos_car_get_address_data($car_id);
    if ($data === false) {
        return;
    }

    $data['extra_class'] = 'amotos__single-car-location';
    amotos_get_template( 'loop/car-location.php',$data);
}

function amotos_template_single_car_gallery($args = array()) {
    $args          = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $car_gallery = amotos_get_car_gallery_image($args['car_id']);
    if ($car_gallery === false || count($car_gallery) === 0) {
        return;
    }
    amotos_get_template( 'single-car/gallery.php',array(
        'car_gallery' => $car_gallery
    ) );
}

function amotos_template_single_car_description() {
    amotos_get_template( 'single-car/description.php' );
}

function amotos_template_single_car_address() {
    $data = amotos_get_single_car_address_data();
    if (empty($data)) {
        return;
    }
    $google_map_address_url  = '';
    $location  = amotos_car_get_address_data();
    if ($location !== false) {
        $google_map_address_url = $location['google_map_address_url'];
    }
    amotos_get_template( 'single-car/address.php',array(
        'data' => $data,
        'google_map_address_url' => $google_map_address_url
    ) );
}

function amotos_template_single_car_map($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $enable_map_directions = amotos_get_option( 'enable_map_directions', 1 );
    if (filter_var($enable_map_directions, FILTER_VALIDATE_BOOLEAN) === false) {
        return;
    }

    $position = amotos_car_get_map_position($car_id);
    if ($position === false) {
        return;
    }

    amotos_get_template( 'single-car/map.php',array('car_id' => $car_id));

}

function amotos_template_single_car_attachments($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_attachments = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_attachments', true);
    if (empty($car_attachments)) {
        return;
    }
    $car_attachments = explode('|', $car_attachments);
    $car_attachments = array_unique($car_attachments);
    if (empty($car_attachments)) {
        return;
    }

    $attachments = array();
    foreach ($car_attachments as $attach_id) {
        $attach_url = wp_get_attachment_url($attach_id);
        if ($attach_url === false) {
            continue;
        }

        $file_type = wp_check_filetype($attach_url);
        $file_type = isset($file_type['ext']) ? $file_type['ext'] : '';
        if (empty($file_type)) {
            continue;
        }

        $thumb = AMOTOS_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type . '.png';
        $file_name = basename($attach_url);

        $attachments[] = array(
          'url' => $attach_url,
          'thumb' => $thumb,
          'name' => $file_name
        );
    }

    if (count($attachments) === 0) {
        return;
    }

    amotos_get_template( 'single-car/attachments.php',array(
        'attachments' =>  $attachments) );
}

function amotos_template_single_car_stylings($car_id = '') {
	if (empty($car_id)) {
		$car_id = get_the_ID();
	}

    $tabs = amotos_get_single_car_stylings_tabs($car_id);

    if ( empty( $tabs ) ) {
        return;
    }
    $wrapper_classes = array(
        'single-car-element',
        'car-info-tabs',
        'car-tab'
    );
    $wrapper_class = join(' ', $wrapper_classes);
    amotos_get_template( 'global/tabs.php', array('tabs' => $tabs,'extend_class' => $wrapper_class) );
}

function amotos_template_single_car_overview($args = array()) {
    $args          = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $data = amotos_get_single_car_overview($args['car_id']);
    if (empty($data)) {
        return;
    }
    amotos_get_template('single-car/overview.php', array('data' => $data));
}

function amotos_template_single_car_styling($args = array())
{
    $args          = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $stylings = amotos_get_car_stylings($args);

    if (($stylings === false ) || empty($stylings)) {
        return;
    }

    amotos_get_template('single-car/styling.php',array('stylings' => $stylings));
}

function amotos_template_single_car_video($args = array()) {
    $args          = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $video = amotos_get_car_video($args);
    if ($video === false) {
        return;
    }
    amotos_get_template('single-car/video.php',$video);
}

function amotos_template_single_car_virtual_360($args = array()) {
    $args          = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $virtual_360 =  amotos_get_car_virtual_360($args);
    if ($virtual_360 === false) {
        return;
    }
    amotos_get_template('single-car/virtual-360.php', $virtual_360);
}

function amotos_template_single_car_identity($args = array() ) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $car_identity = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_identity', true );
    if ( empty( $car_identity ) ) {
        $car_identity = get_the_ID();
    }

    amotos_get_template('single-car/data/identity.php', array( 'car_identity' => $car_identity ));
}

function amotos_template_single_car_type($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $car_type = get_the_term_list( $args['car_id'], 'car-type', '', ', ', '' );
    if ( $car_type === false || is_a( $car_type, 'WP_Error' ) ) {
        return;
    }
    amotos_get_template( 'single-car/data/type.php', array( 'car_type' => $car_type ) );

}

function amotos_template_single_car_data_status($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $car_status = get_the_term_list( $args['car_id'], 'car-status', '', ', ', '' );
    if ( $car_status === false || is_a( $car_status, 'WP_Error' ) ) {
        return;
    }

    amotos_get_template( 'single-car/data/status.php', array( 'car_status' => $car_status ) );
}

function amotos_template_single_car_doors($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_doors = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_doors', true );
    if ( $car_doors === '' ) {
        return;
    }
    amotos_get_template( 'single-car/data/doors.php', array(
        'doors' => $car_doors
    ) );
}

function amotos_template_single_car_seats($args = array()){
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_seats = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_seats', true );
    if ( $car_seats === '' ) {
        return;
    }
    amotos_get_template( 'single-car/data/seats.php', array( 'car_seats' => $car_seats ) );
}

function amotos_template_single_car_owners($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_owners = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_owners', true );
    if ( $car_owners === '' ) {
        return;
    }
    amotos_get_template( 'single-car/data/owners.php', array( 'car_owners' => $car_owners ) );
}

function amotos_template_single_car_year($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_year = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_year', true );
    if ( $car_year === '' ) {
        return;
    }
    amotos_get_template( 'single-car/data/year.php', array( 'car_year' => $car_year ) );
}

function amotos_template_single_car_mileage($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_mileage = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_mileage', true );
    if ( $car_mileage === '' ) {
        return;
    }
    $measurement_units_mileage = amotos_get_measurement_units_mileage();
    amotos_get_template( 'single-car/data/mileage.php', array(
        'car_mileage'               => $car_mileage,
        'measurement_units_mileage' => $measurement_units_mileage
    ) );
}

function amotos_template_single_car_power($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_power = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_power', true );
    if ( $car_power === '' ) {
        return;
    }
    $measurement_units_power = amotos_get_measurement_units_power();
    amotos_get_template( 'single-car/data/power.php', array(
        'car_power'               => $car_power,
        'measurement_units_power' => $measurement_units_power
    ) );
}

function amotos_template_single_car_volume($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );
    $car_volume = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_volume', true );
    if ( $car_volume === '' ) {
        return;
    }
    $measurement_units_volume = amotos_get_measurement_units_volume();
    amotos_get_template( 'single-car/data/volume.php', array(
        'car_volume'               => $car_volume,
        'measurement_units_volume' => $measurement_units_volume
    ) );
}

function amotos_template_single_car_label($args = array()) {
    $args = wp_parse_args( $args, array(
        'car_id' => get_the_ID(),
    ) );

    $car_label = get_the_term_list( $args['car_id'], 'car-label', '', ', ', '' );
    if ( $car_label === false || is_a( $car_label, 'WP_Error' ) ) {
        return;
    }
    amotos_get_template( 'single-car/data/label.php', array( 'car_label' => $car_label ) );
}

function amotos_template_single_car_info($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    amotos_get_template( 'single-car/car-info.php', array(
        'car_id'       => $car_id
    ));
}

function amotos_template_car_search_form($atts = array(),$css_class_field = '', $css_class_half_field = '', $show_status_tab = true) {
    $args = array(
        'atts' => $atts,
        'show_status_tab'      => $show_status_tab,
        'css_class_field'      => $css_class_field,
        'css_class_half_field' => $css_class_half_field,
    );
    amotos_get_template('car/search-form.php', $args);
}

function amotos_template_car_map_search($extend_class) {
    $map_id = 'amotos_result_map-'.wp_rand();
    amotos_get_template('car/search-map.php',array(
        'extend_class' => $extend_class,
        'map_id' => $map_id
    ));
}

function amotos_template_loop_car_action($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'loop/car-action.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_action_view_gallery($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_gallery = amotos_get_car_gallery_image($car_id);
    $total_image = 0;
    if ($car_gallery) {
        $total_image = count($car_gallery);
    }
    amotos_get_template( 'loop/car-action/view-gallery.php', array(
        'car_id'       => $car_id,
        'total_image' => $total_image
    ));

}

function amotos_template_loop_car_action_favorite($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $enable_favorite_car = amotos_get_option( 'enable_favorite_car', '1' );
    if (!filter_var($enable_favorite_car, FILTER_VALIDATE_BOOLEAN)) {
        return;
    }

    global $current_user;
    wp_get_current_user();
    $key = false;
    $user_id = $current_user->ID;
    $my_favorites = get_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', true);
    if (!empty($my_favorites)) {
        $key = array_search($car_id, $my_favorites);
    }
    $icon_favorite = apply_filters('amotos_icon_favorite','fa fa-star') ;
    $icon_not_favorite = apply_filters('amotos_icon_not_favorite','fa fa-star-o');

    if ($key !== false) {
        $icon_class = $icon_favorite;
        $title = esc_attr__('It is my favorite', 'auto-moto-stock');
    } else {
        $icon_class = $icon_not_favorite;
        $title = esc_attr__('Add to Favorite', 'auto-moto-stock');
    }


    amotos_get_template( 'loop/car-action/favorite.php', array(
        'car_id'       => $car_id,
        'icon_class' => $icon_class,
        'title' => $title,
        'icon_favorite' => $icon_favorite,
        'icon_not_favorite' => $icon_not_favorite
    ));
}

function amotos_template_loop_car_action_compare($car_id) {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $enable_compare_cars =  amotos_get_option( 'enable_compare_cars', '1' );
    if (!filter_var($enable_compare_cars, FILTER_VALIDATE_BOOLEAN)) {
        return;
    }

    amotos_get_template( 'loop/car-action/compare.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_thumbnail($args = array())
{
    $args = wp_parse_args($args,array(
        'car_id'       => get_the_ID(),
        'image_size' => '',
        'extra_classes' => ''
    ));

    amotos_get_template('loop/car-thumbnail.php', array(
        'car_id' => $args['car_id'],
        'image_size' => $args['image_size'],
        'extra_classes' => $args['extra_classes']
    ));
}

function amotos_template_loop_car_image($args = array()) {
    $image_size = amotos_get_option( 'archive_car_image_size', amotos_get_loop_car_image_size_default() );
    $args = wp_parse_args($args,array(
        'car_id'       => get_the_ID(),
        'image_size' => $image_size,
        'image_size_default' => amotos_get_loop_car_image_size_default()
    ));
    amotos_get_template('loop/car-image.php',array(
        'car_id'       => $args['car_id'],
        'image_size' => $args['image_size'],
        'image_size_default' => $args['image_size_default']
    ));
}

function amotos_template_loop_car_featured_label($args = array()) {
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }

    $car_badge = amotos_get_loop_car_featured_label($args['car_id']);
    if ( empty( $car_badge ) ) {
        return;
    }

    amotos_get_template('loop/car-badge.php', array(
        'badge' => $car_badge,
        'extra_classes' => 'amotos__lpb-featured-label'
    ));
}

function amotos_template_loop_car_term_status($args = array()) {
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }

    $car_item_status = get_the_terms( $args['car_id'], 'car-status' );
    if ( $car_item_status === false || is_a( $car_item_status, 'WP_Error' ) ) {
        return;
    }

    amotos_get_template('loop/car-term-status.php', array(
        'car_item_status' => $car_item_status
    ));
}

function amotos_template_loop_car_status($args = array()) {
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }

    $car_item_status = amotos_car_get_status($args['car_id']);
    if ($car_item_status === false) {
        return;
    }

    amotos_get_template('loop/car-status.php', array(
        'car_item_status' => $car_item_status
    ));
}

function amotos_template_single_car_status($args = array())
{
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }

    $car_item_status = amotos_car_get_status($args['car_id']);
    if ($car_item_status === false) {
        return;
    }

    amotos_get_template('loop/car-status.php', array(
        'car_item_status' => $car_item_status,
        'extra_class' => 'amotos__single-car-status'
    ));
}

function amotos_template_loop_car_featured($args = array()) {
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }
    $car_featured = get_post_meta( $args['car_id'], AMOTOS_METABOX_PREFIX . 'car_featured', true );
    if ( !filter_var($car_featured, FILTER_VALIDATE_BOOLEAN)) {
        return;
    }
    amotos_get_template('loop/car-featured.php');
}

function amotos_template_loop_car_term_label($args = array()) {
    if (is_array($args)) {
        $args = wp_parse_args($args,array(
            'car_id'       => get_the_ID(),
        ));
    } else {
        $args = wp_parse_args($args,array(
            'car_id'       => $args,
        ));
    }
    $car_term_label = get_the_terms( $args['car_id'], 'car-label' );
    if ( $car_term_label === false || is_a( $car_term_label, 'WP_Error' ) ) {
        return;
    }

    amotos_get_template('loop/car-term-label.php', array(
        'car_term_label' => $car_term_label
    ));

}

function amotos_template_loop_car_link($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'loop/car-link.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_link_detail($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'loop/car-link-detail.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_excerpt($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $excerpt            = get_the_excerpt($car_id);
    if (empty($excerpt)) {
        return;
    }
    amotos_get_template( 'loop/car-excerpt.php', array(
        'excerpt'       => $excerpt,
    ));
}

function amotos_template_loop_car_meta($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    amotos_get_template( 'loop/car-meta.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_info($car_id = '', $layout = 'layout-1') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    amotos_get_template( 'loop/car-info.php', array(
        'car_id'       => $car_id,
        'layout' => $layout
    ));
}

function amotos_template_loop_car_info_layout_2($car_id = '') {
    amotos_template_loop_car_info($car_id,'layout-2');
}

function amotos_template_heading($args = array()) {
    $args = wp_parse_args($args, array(
            'heading_text_align' => '',
            'heading_title' => '',
            'heading_sub_title' => '',
            'color_scheme' => '',
            'extra_classes' => array()
        ));
    if (empty($args['heading_title']) && empty($args['heading_sub_title'])) {
        return;
    }
    amotos_get_template('global/heading.php', $args);
}

function amotos_template_loop_car_type($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'loop/car-type.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_manager($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $manager_info = amotos_get_manager_info_of_car($car_id);
    if ($manager_info === false) {
        return;
    }
    amotos_get_template( 'loop/car-manager.php',$manager_info);

}

function amotos_template_loop_car_date($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'loop/car-date.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_loop_car_mileage($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_mileage = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_mileage', true );
    if ( $car_mileage === '' ) {
        return;
    }
    $measurement_units_mileage = amotos_get_measurement_units_mileage();
    amotos_get_template( 'loop/car-mileage.php', array(
        'car_mileage'     => $car_mileage,
        'measurement_units_mileage' => $measurement_units_mileage
    ) );
}

function amotos_template_loop_car_power($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_power = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_power', true );
    if ( $car_power === '' ) {
        return;
    }
    $measurement_units_power = amotos_get_measurement_units_power();
    amotos_get_template( 'loop/car-power.php', array(
        'car_power'     => $car_power,
        'measurement_units_power' => $measurement_units_power
    ) );
}

function amotos_template_loop_car_volume($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_volume = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_volume', true );
    if ( $car_volume === '' ) {
        return;
    }
    $measurement_units_volume = amotos_get_measurement_units_volume();
    amotos_get_template( 'loop/car-volume.php', array(
        'car_volume'     => $car_volume,
        'measurement_units_volume' => $measurement_units_volume
    ) );
}

function amotos_template_loop_car_identity($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_identity = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_identity', true );
    if ( empty( $car_identity ) ) {
        $car_identity = get_the_ID();
    }
    amotos_get_template( 'loop/car-identity.php', array(
        'car_identity'     => $car_identity
    ) );
}

function amotos_template_loop_car_doors($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $car_doors = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_doors', true );
    if ( $car_doors === '' ) {
        return;
    }
    amotos_get_template( 'loop/car-doors.php', array( 'car_doors' => $car_doors ) );
}

function amotos_template_loop_car_seats($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $car_seats = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_seats', true );
    if ( $car_seats === '' ) {
        return;
    }
    amotos_get_template( 'loop/car-seats.php', array( 'car_seats' => $car_seats ) );
}

function amotos_template_loop_car_owners($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $car_owners = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_owners', true );
    if ( $car_owners === '' ) {
        return;
    }
    amotos_get_template( 'loop/car-owners.php', array( 'car_owners' => $car_owners ) );
}

function amotos_template_archive_car_action() {
    amotos_get_template('archive-car/action.php');
}

function amotos_template_archive_car_heading($total_post = 0, $taxonomy_title = '', $manager_id = 0, $author_id = 0 ) {
    amotos_get_template( 'archive-car/heading.php', array(
        'total_post'     => $total_post,
        'taxonomy_title' => $taxonomy_title,
        'manager_id'       => $manager_id,
        'author_id'      => $author_id
    ) );
}

function amotos_template_archive_car_action_status() {
    if (!(is_post_type_archive('car') || is_page('cars')) && !is_tax(get_object_taxonomies('car'))) {
        return;
    }
    amotos_get_template('archive-car/actions/status.php');
}

function amotos_template_archive_car_action_switch_layout() {
    amotos_get_template('archive-car/actions/switch-layout.php');
}

function amotos_template_archive_car_action_orderby() {
    $sort_by_list = amotos_get_car_sort_by();
    amotos_get_template('archive-car/actions/orderby.php',array('sort_by_list' => $sort_by_list));
}

function amotos_template_car_advanced_search_form($parameters,$search_query) {
    $enable_advanced_search_form = amotos_get_option( 'enable_advanced_search_form', '1' );
    if (!filter_var($enable_advanced_search_form, FILTER_VALIDATE_BOOLEAN)) {
        return;
    }

    $enable_advanced_search_status_tab = amotos_get_option( 'enable_advanced_search_status_tab', '1' );
    $car_price_field_layout = amotos_get_option( 'advanced_search_price_field_layout', '0' );
    $car_mileage_field_layout = amotos_get_option( 'advanced_search_mileage_field_layout', '0' );
    $car_power_field_layout = amotos_get_option( 'advanced_search_power_field_layout', '0' );
    $car_volume_field_layout = amotos_get_option('advanced_search_volume_field_layout', '0');
    $shortcode_attr = array(
        'layout'                =>  "tab",
        'column'                => 3,
        'color_scheme'          => "color-dark",
        'status_enable'         => "true",
        'type_enable'           => "true",
        'keyword_enable'        => "true",
        'title_enable'          => "true",
        'address_enable'        => "true",
        'country_enable'        => "true",
        'state_enable'          => "true",
        'city_enable'           => "true",
        'neighborhood_enable'   => "true",
        'doors_enable'          => "true",
        'seats_enable'          => "true",
        'owners_enable'         => "true",
        'price_enable'          => "true",
        'price_is_slider'       => ( $car_price_field_layout == '1' ) ? 'true' : 'false',
        'mileage_enable'        => "true",
        'mileage_is_slider'     => ( $car_mileage_field_layout == '1' ) ? 'true' : 'false',
        'power_enable'          => "true",
        'power_is_slider'       => ( $car_power_field_layout == '1' ) ? 'true' : 'false',
        'volume_enable'          => "true",
        'volume_is_slider'       => ( $car_volume_field_layout == '1' ) ? 'true' : 'false',
        'label_enable'          => "true",
        'car_identity_enable'   => "true",
        'other_stylings_enable' => "true",
    );
    $additional_fields      = amotos_get_search_additional_fields();
    foreach ( $additional_fields as $k => $v ) {
        $shortcode_attr["{$k}_enable"] = "true";
    }
    $enable_saved_search = amotos_get_option('enable_saved_search', 1);
    if (!filter_var($enable_advanced_search_status_tab, FILTER_VALIDATE_BOOLEAN)) {
        $shortcode_attr['layout'] = '';
    }
    amotos_get_template('car/advanced-search-form.php', array(
        'atts' => $shortcode_attr,
        'enable_saved_search' => $enable_saved_search,
        'parameters' => $parameters,
        'search_query' => $search_query
    ));
}

function amotos_template_single_car_reviews(){
    $enable_comments_reviews_car = amotos_get_option( 'enable_comments_reviews_car', 1 );
    if ( $enable_comments_reviews_car == 2 ) {
        $rating = 0;
        $total_reviews = 0;
        $total_stars = 0;
        $my_rating = 0;
        $my_comment = '';
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $car_id = get_the_ID();
        $rating_data = amotos_car_get_rating($car_id);

        $comments = amotos_car_get_list_review($car_id, $user_id);
        if ( $comments !== null ) {
            foreach ( $comments as $comment ) {
                if ( $comment->comment_approved == 1 ) {
                    $total_reviews++;
                    $total_stars += $comment->meta_value;
                }
            }
            if ( $total_reviews > 0 ) {
                $rating = ( $total_stars / $total_reviews );
            }
        }

        $my_review = amotos_car_get_review_by_user_id($car_id,$user_id);
        if ($my_review !== null) {
            $my_comment = $my_review->comment_content;
            $my_rating = $my_review->rate;
        }

        $wrapper_classes  = array(
            'single-car-element',
            'amotos__single-car-element',
            'amotos__single-car-review'
        );

        $wrapper_class =  join(' ', apply_filters('amotos_single_car_review_wrapper_classes',$wrapper_classes));

        amotos_get_template('global/reviews.php',array(
            'extra_class' => $wrapper_class,
            'rating' => $rating,
            'total_reviews' => $total_reviews,
            'rating_data' => $rating_data,
            'type' => 'car',
            'comments' => $comments,
            'my_rating' => $my_rating,
            'my_comment' => $my_comment
        ));
    }
}

function amotos_template_single_car_header()
{
    amotos_get_template( 'single-car/header.php' );
}

function amotos_template_single_car_title() {
    amotos_get_template('single-car/title.php');
}

function amotos_template_single_car_header_price_location() {
    amotos_get_template('single-car/header/price-location.php');
}

function amotos_template_single_car_header_meta_action() {
    amotos_get_template('single-car/header/meta-action.php');
}

function amotos_template_single_car_action($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template( 'single-car/car-action.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_single_car_action_social_share() {
    $enable_social_share = amotos_get_option('enable_social_share', '1');
    if (filter_var($enable_social_share,FILTER_VALIDATE_BOOLEAN) === false ) {
        return;
    }

    amotos_get_template('global/social-share.php', array(
        'extra_class' => 'amotos__single-car-social-share amotos__loop-car_action-item'
    ));
}

function amotos_template_single_car_action_print($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $enable_print_car = amotos_get_option('enable_print_car','1');
    if (filter_var($enable_print_car,FILTER_VALIDATE_BOOLEAN) === false) {
        return;
    }

    amotos_get_template( 'single-car/action/print.php', array(
        'car_id'       => $car_id,
    ));
}

function amotos_template_single_car_nearby_places($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $enable_nearby_places = amotos_get_option( 'enable_nearby_places', 1 );
    if (filter_var($enable_nearby_places, FILTER_VALIDATE_BOOLEAN) === false) {
        return;
    }

    $data = amotos_car_get_nearby_places_data($car_id);
    if ($data === false) {
        return;
    }
    amotos_get_template( 'single-car/nearby-places.php', array( 'car_id' => $car_id, 'data' => $data ) );
}

function amotos_template_single_car_walk_score($car_id = '')
{
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $enable_walk_score = amotos_get_option( 'enable_walk_score', 0 );
    if (filter_var($enable_walk_score, FILTER_VALIDATE_BOOLEAN) === false) {
        return;
    }
    $data = amotos_car_get_walk_score_data($car_id);
    amotos_get_template( 'single-car/walk-score.php', array( 'car_id' => $car_id,'data' => $data) );
}

function amotos_template_single_car_contact_manager($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $car_form_sections = amotos_get_option( 'car_form_sections', amotos_get_car_form_section_config_default() );
    if (!in_array( 'contact', $car_form_sections )) {
        return;
    }

    $manager_display_option = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'manager_display_option', true);
    if ($manager_display_option === 'no') {
        return;
    }
    $manager_info = amotos_get_manager_contact_info_of_car($car_id);
    if ($manager_info['is_login']) {
        amotos_get_template( 'single-car/contact-manager.php', $manager_info);
    } else {
        amotos_get_template( 'single-car/contact-manager-not-login.php');
    }
}

function amotos_template_single_car_footer($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $enable_create_date = amotos_get_option('enable_create_date', 1);
    $enable_views_count = amotos_get_option('enable_views_count', 1);

    $enable_create_date = filter_var($enable_create_date,FILTER_VALIDATE_BOOLEAN);
    $enable_views_count = filter_var($enable_views_count,FILTER_VALIDATE_BOOLEAN);

    if (!$enable_create_date && !$enable_views_count) {
        return;
    }
    $total_views = 0;
    if ($enable_views_count) {
        $total_views = amotos_car_get_total_views($car_id);
    }
    amotos_get_template( 'single-car/footer.php', array(
        'car_id' => $car_id,
        'enable_create_date' => $enable_create_date,
        'enable_views_count' => $enable_views_count,
        'total_views' => $total_views
    ) );
}

function amotos_template_print_car_logo()
{
    amotos_get_template('car/print/logo.php');
}
function amotos_template_print_car_header()
{
    amotos_get_template('car/print/header.php');
}

function amotos_template_print_car_qr_image()
{
    amotos_get_template('car/print/qr-image.php',array('car_id' => get_the_ID()));
}

function amotos_template_print_car_image() {
    if (!has_post_thumbnail()) {
        return;
    }
    ?>
        <div class="amotos__print-car-image">
            <?php
            $image_size = '1160x500';
            amotos_template_loop_car_image(array(
                'car_id'             => get_the_ID(),
                'image_size'         => $image_size,
                'image_size_default' => $image_size
            ));
            ?>
        </div>
    <?php
}

function amotos_template_print_car_contact_manager($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $manager_display_option = get_post_meta($car_id,AMOTOS_METABOX_PREFIX . 'manager_display_option', true);
    if ($manager_display_option === 'no') {
        return;
    }
    $manager_info = amotos_get_manager_contact_info_of_car($car_id);
    if (!$manager_info['is_login']) {
        return;
    }
    amotos_get_template( 'car/print/contact-manager.php', $manager_info);
}

function amotos_template_my_car_search()
{
    amotos_get_template('car/my-car/search.php');
}

function amotos_template_my_car_filter() {
    amotos_get_template('car/my-car/filter.php');
}

function amotos_template_loop_my_car_title($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    amotos_get_template('car/my-car/loop/title.php',array('car_id' => $car_id));
}

function amotos_template_loop_my_car_meta() {
    amotos_get_template('car/my-car/loop/meta.php');
}
function amotos_template_loop_my_car_action($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $actions = amotos_my_car_get_action($car_id);
    amotos_get_template('car/my-car/loop/action.php',array('car_id' => $car_id,'actions' => $actions));
}

function amotos_template_loop_my_car_meta_view($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $total_views = AMOTOS_Car::getInstance()->get_total_views($car_id);
    amotos_get_template('car/my-car/loop/meta/view.php',array('total_views' => $total_views));
}

function amotos_template_loop_my_car_meta_date() {
    amotos_get_template('car/my-car/loop/meta/date.php');
}

function amotos_template_loop_my_car_meta_expire_date($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
    if ($paid_submission_type != 'per_listing') {
        return;
    }
    $listing_expire = amotos_get_option('per_listing_expire_days');
    if ($listing_expire != 1) {
        return;
    }
    amotos_get_template('car/my-car/loop/meta/expire-date.php',array('car_id' => $car_id));
}

function amotos_template_loop_my_car_featured($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $car_featured = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true );
    if ( !filter_var($car_featured, FILTER_VALIDATE_BOOLEAN)) {
        return;
    }
    amotos_get_template('car/my-car/loop/featured.php');
}

function amotos_template_loop_my_car_status($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }

    $status = get_post_status($car_id);

    amotos_get_template('car/my-car/loop/status.php',array('status' => $status));
}

function amotos_template_loop_my_car_meta_location($car_id = '') {
    if (empty($car_id)) {
        $car_id = get_the_ID();
    }
    $data = amotos_car_get_address_data($car_id);
    if ($data === false) {
        return;
    }

    amotos_get_template('car/my-car/loop/meta/location.php',$data);
}