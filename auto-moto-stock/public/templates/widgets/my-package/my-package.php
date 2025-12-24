<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$user_id = $current_user->ID;
$package_remaining_listings = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_listings', $user_id);
$package_featured_remaining_listings = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_number_featured', $user_id);
$package_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_id', $user_id);
$packages_link = amotos_get_permalink('packages');
if ($package_remaining_listings == -1) {
    $package_remaining_listings = esc_html__('Unlimited', 'auto-moto-stock');
}
if (amotos_package_is_visible($package_id)) :
    $package_title = get_the_title($package_id);
    $package_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true);
    $package_unlimited_listing = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_listing', true);
    $package_featured_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true);
    $amotos_package = new AMOTOS_Package();
    $expired_date = $amotos_package->get_expired_date($package_id, $user_id);
    ?>
    <ul class="list-group p-0 amotos-my-package">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('Package Name ', 'auto-moto-stock') ?><span class="badge"><?php echo esc_html($package_title) ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('Listings Included ', 'auto-moto-stock') ?>
            <span class="badge"><?php if ($package_unlimited_listing == 1) {
                    echo wp_kses_post($package_remaining_listings);
                } else {
                    echo esc_html($package_listings);
                }
                ?>
            </span></li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('Listings Remaining ', 'auto-moto-stock') ?><span class="badge"><?php echo wp_kses_post($package_remaining_listings); ?></span>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('Featured Included ', 'auto-moto-stock') ?><span class="badge"><?php echo esc_html($package_featured_listings) ?></span>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('Featured Remaining ', 'auto-moto-stock') ?><span class="badge"><?php echo esc_html($package_featured_remaining_listings) ?></span>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php esc_html_e('End Date ', 'auto-moto-stock') ?><span class="badge"><?php echo esc_html($expired_date) ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?php echo esc_url($packages_link); ?>" class="btn btn-primary btn-block"><?php esc_html_e('Change new package', 'auto-moto-stock'); ?></a>
        </li>
    </ul>
<?php else: ?>
    <div class="card-body">
    <p class="amotos-message alert alert-success"
       role="alert"><?php esc_html_e('Before you can list vehicles on our site, you must subscribe to a package. Currently, you don\'t have a package. So, to select a new package, please click the button below', 'auto-moto-stock'); ?></p>
    <a href="<?php echo esc_url($packages_link); ?>"
       class="btn btn-primary btn-block"><?php esc_html_e('Subscribe to a package', 'auto-moto-stock'); ?></a>
    </div>
<?php endif; ?>