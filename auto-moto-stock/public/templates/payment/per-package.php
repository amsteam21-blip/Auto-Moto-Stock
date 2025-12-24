<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $current_user;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$package_id = isset($_GET['package_id']) ? sanitize_text_field(wp_unslash($_GET['package_id']))  : '';
$user_package_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'package_id', $user_id);
$amotos_profile=new AMOTOS_Profile();
$check_package=$amotos_profile->user_package_available($user_id);

$package_free = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_free', true);
if($package_free==1)
{
    $package_price=0;
}
else
{
    $package_price = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_price', true);
}

$package_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true);
$package_featured_listings = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true);
$package_unlimited_listing = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_listing', true);
$package_unlimited_time = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_time', true);
$package_time_unit = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_time_unit', true);
$package_title = get_the_title($package_id);
$package_billing_frquency = get_post_meta($package_id, AMOTOS_METABOX_PREFIX . 'package_period', true);

if ($package_billing_frquency > 1) {
    $package_time_unit .= 's';
}
$terms_conditions = amotos_get_option('payment_terms_condition');
$allowed_html = array(
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target'=>array()
    ),
    'strong' => array()
);
$enable_paypal = amotos_get_option('enable_paypal', 1);
$enable_stripe = amotos_get_option('enable_stripe', 1);
$enable_wire_transfer = amotos_get_option('enable_wire_transfer', 1);
$select_packages_link = amotos_get_permalink('packages');
?>
<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="amotos-payment-for card amotos-card amotos-package-item">
            <div class="card-header"><h5 class="card-title m-0"><?php esc_html_e('Selected Package', 'auto-moto-stock'); ?></h5></div>
            <ul class="list-group p-0">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php esc_html_e('Package', 'auto-moto-stock'); ?>
                    <span class="badge"><?php echo esc_html(get_the_title($package_id)) ; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php esc_html_e('Package Time:', 'auto-moto-stock'); ?>
            <span
                class="badge"><?php if($package_unlimited_time==1)
                {
                    esc_html_e('Unlimited', 'auto-moto-stock');
                }
                else
                {
                    echo esc_html($package_billing_frquency) . ' ' . esc_html(AMOTOS_Package::get_time_unit($package_time_unit));
                }
                ?></span>


                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php esc_html_e('Listing Included:', 'auto-moto-stock'); ?>
        <span class="badge"><?php if ($package_unlimited_listing == 1) {
                esc_html_e('Unlimited', 'auto-moto-stock');
            } else {
                echo esc_html($package_listings);
            } ?></span>


                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php esc_html_e('Featured Listing Included:', 'auto-moto-stock'); ?>
            <span class="badge"> <?php echo esc_html($package_featured_listings); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php esc_html_e('Total Price:', 'auto-moto-stock'); ?>
            <span class="badge"><?php echo wp_kses_post(amotos_get_format_money($package_price)) ; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-center align-items-center text-center">
                    <a class="btn btn-default"
                       href="<?php echo esc_url($select_packages_link); ?>"><?php esc_html_e('Change Package', 'auto-moto-stock'); ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-8 col-sm-6">
        <?php if(($package_id==$user_package_id) && $check_package==1):?>
            <div class="alert alert-warning" role="alert"><?php /* translators: %s: package title. */ echo esc_html(sprintf( __( 'You currently have "%s" package. The package hasn\'t expired yet, so you cannot buy it at this time. If you would like, you can buy another package.', 'auto-moto-stock' ), $package_title)) ; ?></div>
        <?php else:?>
        <?php if ($package_price > 0): ?>
            <div class="amotos-payment-method-wrap">
                <div class="amotos-heading">
                    <h2><?php esc_html_e('Payment Method','auto-moto-stock'); ?></h2>
                </div>
                <?php if ($enable_paypal != 0) : ?>
                    <div class="radio">
                        <label>
                            <input type="radio" class="payment-paypal" name="amotos_payment_method" value="paypal"
                                   checked><i
                                class="fa fa-paypal"></i>
                            <?php esc_html_e('Pay With PayPal', 'auto-moto-stock'); ?>
                        </label>
                    </div>
                <?php endif; ?>

                <?php if ($enable_stripe != 0): ?>
                    <div class="radio">
                        <label>
                            <input type="radio" class="payment-stripe" name="amotos_payment_method" value="stripe">
                            <i class="fa fa-credit-card"></i> <?php esc_html_e('Pay with Credit Card', 'auto-moto-stock'); ?>
                        </label>
                        <?php
                        $amotos_payment = new AMOTOS_Payment();
                        $amotos_payment->stripe_payment_per_package($package_id); ?>
                    </div>
                <?php endif; ?>

                <?php if ($enable_wire_transfer != 0) : ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="amotos_payment_method" value="wire_transfer">
                            <i class="fa fa-send-o"></i> <?php esc_html_e('Wire Transfer', 'auto-moto-stock'); ?>
                        </label>
                    </div>
                    <div class="amotos-wire-transfer-info">
                        <?php
                        $html_info=amotos_get_option('wire_transfer_info','');
                        echo wp_kses_post(wpautop($html_info)); ?>
                    </div>
                <?php endif; ?>
	            <?php do_action('amotos_select_payment_method', 0) ?>
            </div>
        <?php endif; ?>
        <input type="hidden" name="amotos_package_id" value="<?php echo esc_attr($package_id); ?>">

        <p class="terms-conditions"><i class="fa fa-hand-o-right"></i> <?php /* translators: %s: link terms & conditions of page . */ echo wp_kses(sprintf(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> first', 'auto-moto-stock'),get_permalink($terms_conditions)), $allowed_html); ?></p>
        <?php if ($package_price > 0): ?>
            <button id="amotos_payment_package" type="submit"
                    class="btn btn-success btn-submit"> <?php esc_html_e('Pay Now', 'auto-moto-stock'); ?> </button>
        <?php else:
            $user_free_package = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'free_package', $user_id);
            if ($user_free_package == 'yes'):?>
                <div class="amotos-message alert alert-warning"
                     role="alert"><?php esc_html_e('You have already used your first free package, please choose different package.', 'auto-moto-stock'); ?></div>
            <?php else: ?>
                <button id="amotos_free_package" type="submit"
                        class="btn btn-success btn-submit"> <?php esc_html_e('Get Free Listing Package', 'auto-moto-stock'); ?> </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php endif;?>
    </div>
</div>
