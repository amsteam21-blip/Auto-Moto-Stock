<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!is_user_logged_in()){
    return amotos_get_template_html('global/access-denied.php',array('type'=>'not_login'));

    return;
}
$allow_submit=amotos_allow_submit();
if (!$allow_submit)
{
    return amotos_get_template_html('global/access-denied.php',array('type'=>'not_permission'));

    return;
}
$package_id = isset($_GET['package_id']) ? absint(amotos_clean(wp_unslash($_GET['package_id'])))  : '';
$car_id = isset($_GET['car_id']) ? absint(amotos_clean(wp_unslash($_GET['car_id'])))  : '';
$is_upgrade = isset($_GET['is_upgrade']) ? absint(amotos_clean(wp_unslash($_GET['is_upgrade'])))  : '';
if ($is_upgrade == 1) {
    $veh_featured = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true);
    if ($veh_featured == 1) {
        wp_safe_redirect( esc_url_raw(home_url()) );
        exit;
    }
}
if (!amotos_package_is_visible($package_id) && empty($car_id)) {
    wp_safe_redirect( esc_url_raw(home_url()) );
    exit;
}
$amotos_car = new AMOTOS_Car();
if (!empty($car_id) && !$amotos_car->user_can_edit_car($car_id)) {
    wp_safe_redirect( esc_url_raw(home_url()) );
    exit;
}
wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'payment');
set_time_limit(700);
$paid_submission_type = amotos_get_option('paid_submission_type','no');
?>
<div class="payment-wrap">
    <?php
    do_action('amotos_payment_before');
    if ($paid_submission_type == 'per_package') {
        amotos_get_template('payment/per-package.php');
    } else if ($paid_submission_type == 'per_listing') {
        if ($is_upgrade == 1) {
            amotos_get_template('payment/per-listing-upgrade.php');
        } else {
            amotos_get_template('payment/per-listing.php');
        }
    }
    wp_nonce_field('amotos_payment_ajax_nonce', 'amotos_security_payment');
    do_action('amotos_payment_after');
    ?>
</div>