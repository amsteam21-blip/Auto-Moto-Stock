<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$car_id = isset($_GET['car_id']) ? absint(amotos_clean(wp_unslash($_GET['car_id']))) : 0;
$terms_conditions = amotos_get_option('payment_terms_condition');
$enable_paypal = amotos_get_option('enable_paypal',1);
$enable_stripe = amotos_get_option('enable_stripe',1);
$enable_wire_transfer = amotos_get_option('enable_wire_transfer',1);

$price_featured_listing = amotos_get_option('price_featured_listing',0);
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="amotos-payment-for card amotos-card amotos-package-item">
            <div class="card-header"><h5 class="card-title m-0"><?php esc_html_e('Choose Option', 'auto-moto-stock'); ?></h5></div>
            <ul class="list-group p-0">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label>
                        <input type="radio" class="amotos_payment_for" name="amotos_payment_for" value="3" checked>
                        <?php esc_html_e('Upgrade to Featured', 'auto-moto-stock'); ?>
                    </label>
                    <span class="badge"><?php echo wp_kses_post(amotos_get_format_money($price_featured_listing)); ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="amotos-payment-method-wrap">
            <?php if ($enable_paypal != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-paypal" name="amotos_payment_method" value="paypal" checked><i
                            class="fa fa-paypal"></i>
                        <?php esc_html_e('Pay With PayPal', 'auto-moto-stock'); ?>
                    </label>
                </div>
            <?php endif; ?>

            <?php if ($enable_stripe != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-stripe" name="amotos_payment_method" value="stripe">
                        <i class="fa fa-credit-card"></i> <?php esc_html_e('Pay with Credit Card', 'auto-moto-stock'); ?>
                    </label>
                    <?php
                    $amotos_payment = new AMOTOS_Payment();
                    $amotos_payment->stripe_payment_upgrade_listing($car_id, $price_featured_listing);
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($enable_wire_transfer != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" name="amotos_payment_method" value="wire_transfer">
                        <i class="fa fa-send-o"></i> <?php esc_html_e('Wire transfer', 'auto-moto-stock'); ?>
                    </label>
                </div>
                <div class="amotos-wire-transfer-info">
                    <?php
                    $html_info=amotos_get_option('wire_transfer_info','');
                    echo wp_kses_post(wpautop($html_info)); ?>
                </div>
            <?php endif; ?>
	        <?php do_action('amotos_select_payment_method', 3) ?>
        </div>
        <input type="hidden" id="amotos_car_id" name="amotos_car_id" value="<?php echo esc_attr($car_id); ?>">
        <p class="terms-conditions"
           role="alert"><?php /* translators: %s: Terms & Conditions link of page . */ echo wp_kses_post(sprintf(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> before click "Pay Now"', 'auto-moto-stock'), get_permalink($terms_conditions))); ?></p>
        <button id="amotos_upgrade_listing" type="button"
                class="btn btn-success btn-submit"> <?php esc_html_e('Pay Now', 'auto-moto-stock'); ?> </button>
    </div>
</div>