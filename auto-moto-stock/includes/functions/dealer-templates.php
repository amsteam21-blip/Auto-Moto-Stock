<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function amotos_template_archive_dealer_action_search() {
    amotos_get_template('shortcodes/dealer/actions/search.php');
}

function amotos_template_archive_dealer_action_orderby() {
    $sort_by_list = amotos_dealer_get_sort_by();
    amotos_get_template('archive-car/actions/orderby.php',array('sort_by_list' => $sort_by_list));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_image($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $logo = get_term_meta( $dealer->term_id, 'dealer_logo', true );
    if (empty($logo) || !is_array($logo) || empty($logo['url'])) {
        return;
    }
    $image = $logo['url'];
    amotos_get_template('shortcodes/dealer/loop/image.php',array('dealer' => $dealer, 'image' => $image));
}

function amotos_template_loop_dealer_title_address_start() {
    echo '<div class="amotos__loop-dealer-title_address">';
}

function amotos_template_loop_dealer_title_address_end() {
    echo '</div>';
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_title($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    amotos_get_template('shortcodes/dealer/loop/title.php',array('dealer' => $dealer));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_address($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $address = get_term_meta( $dealer->term_id, 'dealer_address', true );
    if (empty($address)) {
        return;
    }
    amotos_get_template('shortcodes/dealer/loop/address.php',array('address' => $address));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_social($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $data = amotos_dealer_get_social_data($dealer->term_id);
    if (empty($data)) {
        return;
    }
    amotos_get_template('shortcodes/dealer/loop/social.php',array('data' => $data));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_desc($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $desc = $dealer->description;
    if (empty($desc)) {
        return;
    }
    amotos_get_template('shortcodes/dealer/loop/desc.php',array('desc' => $desc));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_loop_dealer_meta($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $data = amotos_dealer_get_contact_data($dealer->term_id);
    if (empty($data)) {
        return;
    }
    amotos_get_template('shortcodes/dealer/loop/meta.php',array('data' => $data));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_header($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    amotos_get_template('single-dealer/header.php',array('dealer' => $dealer));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_title($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $name = $dealer->name;
    amotos_get_template('single-dealer/elements/title.php',array('title' => $name));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_address($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $address = get_term_meta( $dealer->term_id, 'dealer_address', true );
    if (empty($address)) {
        return;
    }
    amotos_get_template('single-dealer/elements/address.php',array('address' => $address));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_meta($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $data = amotos_dealer_get_meta_data($dealer->term_id);
    if (empty($data)) {
        return;
    }
    amotos_get_template('single-dealer/elements/meta.php',array('data' => $data));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_contact_info($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }

    $data = amotos_dealer_get_contact_data_single($dealer->term_id);
    if (empty($data)) {
        return;
    }
    amotos_get_template('single-dealer/elements/contact.php',array('data' => $data));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function amotos_template_single_dealer_social($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $data = amotos_dealer_get_social_data($dealer->term_id);
    if (empty($data)) {
        return;
    }
    amotos_get_template('single-dealer/elements/social.php',array('data' => $data));
}

/**
 * @param $dealer WP_Term
 * @return void
 */
function  amotos_template_single_dealer_image($dealer)
{
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $logo = get_term_meta( $dealer->term_id, 'dealer_logo', true );
    if (empty($logo) || !is_array($logo) || empty($logo['url'])) {
        return;
    }
    $image = $logo['url'];
    amotos_get_template('single-dealer/elements/image.php',array('dealer' => $dealer, 'image' => $image));
}

function amotos_template_single_dealer_contact_form($dealer) {
    $email         = get_term_meta( $dealer->term_id, 'dealer_email', true );
    if (empty($email)) {
        return;
    }
    $enable_captcha= amotos_enable_captcha( 'contact_dealer' ) ;
    amotos_get_template('global/contact-form.php',array('email' =>  $email, 'enable_captcha' => $enable_captcha,'extend_class' => 'amotos__single-dealer-contact-form'));
}

/**
 * @param $dealer
 * @return void
 */
function amotos_template_single_dealer_tabs($dealer) {
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $tabs = amotos_dealer_get_tabs($dealer->term_id);
    $wrapper_classes = array(
        'amotos__single-dealer-element',
        'amotos__single-dealer-tabs',
    );
    $wrapper_class = join(' ', $wrapper_classes);
    amotos_get_template('global/tabs.php',array('tabs' => $tabs,'extend_class' => $wrapper_class));
}

function amotos_template_single_dealer_overview($dealer) {
    $dealer_id = '';
    if (is_array($dealer)) {
        $args          = wp_parse_args( $dealer, array(
            'dealer_id' => '',
        ) );
        $dealer_id = $args['dealer_id'];
    } elseif (is_a($dealer,'WP_Term')) {
        $dealer_id = $dealer->term_id;
    }

    if (empty($dealer_id)) {
        return;
    }

    $desc     = get_term_meta( $dealer_id, 'dealer_des', true );
    if (empty($desc)) {
        return;
    }
    $desc     = wpautop( $desc );
    amotos_get_template('single-dealer/tabs/overview.php', array('desc' => $desc));
}

function amotos_template_single_dealer_car($dealer) {
    $dealer_id = '';
    if (is_array($dealer)) {
        $args          = wp_parse_args( $dealer, array(
            'dealer_id' => '',
        ) );
        $dealer_id = $args['dealer_id'];
    } elseif (is_a($dealer,'WP_Term')) {
        $dealer_id = $dealer->term_id;
    }

    $enable_car_of_dealer = amotos_get_option( 'enable_car_of_dealer', '1' );
    if (!filter_var($enable_car_of_dealer,FILTER_VALIDATE_BOOLEAN)) {
        return;
    }
    amotos_get_template('single-dealer/tabs/car.php',array('dealer_id' => $dealer_id));
}

function amotos_template_single_dealer_location($dealer) {
    $dealer_id = '';
    if (is_array($dealer)) {
        $args          = wp_parse_args( $dealer, array(
            'dealer_id' => '',
        ) );
        $dealer_id = $args['dealer_id'];
    } elseif (is_a($dealer,'WP_Term')) {
        $dealer_id = $dealer->term_id;
    }

    $position =  amotos_dealer_get_map_position($dealer_id);
    if ($position === false) {
        return;
    }

    $location = array(
        'position' => $position,
        'marker_type' => 'simple'
    );
    amotos_get_template('single-dealer/tabs/map.php',array('location' => $location));
}

function amotos_template_single_dealer_manager($dealer)
{
    if (!is_a($dealer,'WP_Term')) {
        return;
    }
    $enable_staff_of_dealer = amotos_get_option( 'enable_staff_of_dealer', '1' );
    if (filter_var($enable_staff_of_dealer,FILTER_VALIDATE_BOOLEAN) === false) {
        return;
    }
    amotos_get_template('single-dealer/manager.php',array('dealer' =>  $dealer));
}
