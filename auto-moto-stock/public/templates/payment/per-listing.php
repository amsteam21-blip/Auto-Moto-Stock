<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$car_id = isset($_GET['car_id']) ? absint(amotos_clean(wp_unslash($_GET['car_id'])))  : 0;
$terms_conditions = amotos_get_option('payment_terms_condition');
$enable_paypal = amotos_get_option('enable_paypal',1);
$enable_stripe = amotos_get_option('enable_stripe',1);
$enable_wire_transfer = amotos_get_option('enable_wire_transfer',1);
$price_per_listing = amotos_get_option('price_per_listing',0);
$price_featured_listing = amotos_get_option('price_featured_listing',0);
$price_per_listing_with_featured = intval($price_per_listing) + intval($price_featured_listing);
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="amotos-payment-for panel panel-default">
            <div class="amotos-package-title panel-heading"><?php esc_html_e('Choose Option', 'auto-moto-stock'); ?></div>
            <ul class="list-group p-0">
                <li class="list-group-item">
            <span
                class="badge"><?php echo wp_kses_post(amotos_get_format_money($price_per_listing)); ?></span>
                    <label>
                        <input type="radio" class="amotos_payment_for" name="amotos_payment_for" value="1" checked>
                        <?php esc_html_e('Submission standard', 'auto-moto-stock'); ?>
                    </label>
                </li>
                <li class="list-group-item">
            <span
                class="badge"><?php echo wp_kses_post(amotos_get_format_money($price_per_listing_with_featured)); ?></span>
                    <label>
                        <input type="radio" class="amotos_payment_for" name="amotos_payment_for" value="2">
                        <?php esc_html_e('Submission with featured', 'auto-moto-stock'); ?>
                    </label>
                </li>
                <?php
                $per_listing_expire_days=amotos_get_option('per_listing_expire_days',0);
                if($per_listing_expire_days==1):?>
                <li class="list-group-item list-group-item-info">
                    <?php
                    $number_expire_days=amotos_get_option('number_expire_days',0);
                    /* translators: %s: Number expire days. */
                    echo wp_kses_post(sprintf( _n( 'Note: Number expire days: <strong>%s day</strong>', 'Note: Number expire days: <strong>%s days</strong>', $number_expire_days, 'auto-moto-stock' ), $number_expire_days ));
                    ?>
                </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="amotos-payment-method-wrap">
            <div class="amotos-heading">
                <h2><?php esc_html_e('Payment Method','auto-moto-stock'); ?></h2>
            </div>
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
                    $amotos_payment->stripe_payment_per_listing($car_id, $price_per_listing);
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
	        <?php do_action('amotos_select_payment_method', 1) ?>
        </div>
        <input type="hidden" id="amotos_car_id" name="amotos_car_id" value="<?php echo esc_attr($car_id); ?>">

        <p class="terms-conditions"><i class="fa fa-hand-o-right"></i> <?php /* translators: %s: link terms & conditions of page . */ echo wp_kses_post(sprintf(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> first', 'auto-moto-stock'),get_permalink($terms_conditions))); ?></p>
        <button id="amotos_payment_listing" type="button"
                class="btn btn-success btn-submit"> <?php esc_html_e('Pay Now', 'auto-moto-stock'); ?> </button>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('.amotos_payment_for').change(function(){
            if( jQuery(this).val() == 1 ){
                jQuery("#amotos_stripe_per_listing script").attr("data-amount","<?php echo esc_js(intval($price_per_listing*100)); ?>");
                jQuery("#amotos_stripe_per_listing input[name='payment_money']").val("<?php echo esc_js(intval($price_per_listing*100)); ?>");
                jQuery("#amotos_stripe_per_listing input[name='payment_for']").val("1");
            }
            if( jQuery(this).val() == 2 ){
                jQuery("#amotos_stripe_per_listing script").attr("data-amount","<?php echo esc_js(intval($price_per_listing_with_featured*100)); ?>");
                jQuery("#amotos_stripe_per_listing input[name='payment_money']").val("<?php echo esc_js(intval($price_per_listing_with_featured*100)) ; ?>");
                jQuery("#amotos_stripe_per_listing input[name='payment_for']").val("2");
            }
        });
    });
</script>