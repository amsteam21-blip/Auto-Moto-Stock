<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$amotos_ayment = new AMOTOS_Payment();
$payment_method = isset($_GET['payment_method']) ? absint(amotos_clean(wp_unslash($_GET['payment_method']))) : -1;
if ($payment_method == 1) {
    $amotos_ayment->paypal_payment_completed();
} elseif ($payment_method == 2) {
    $amotos_ayment->stripe_payment_completed();
}
?>
<div class="amotos-payment-completed-wrap">
    <?php
    do_action('amotos_before_payment_completed');
    if (isset($_GET['order_id']) && $_GET['order_id'] != ''):
        $order_id = absint(amotos_clean(wp_unslash($_GET['order_id'])));
        $amotos_invoice = new AMOTOS_Invoice();
        $invoice_meta = $amotos_invoice->get_invoice_meta($order_id);
        ?>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="card amotos-card">
                    <div class="card-header"><h5 class="card-title m-0"><?php esc_html_e('My Order', 'auto-moto-stock'); ?></h5></div>
                    <ul class="list-group p-0">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php esc_html_e('Order Number', 'auto-moto-stock'); ?>
                            <strong><?php echo esc_html($order_id); ?></strong></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"><?php esc_html_e('Date', 'auto-moto-stock'); ?>
                            <strong><?php echo get_the_date('', $order_id); ?></strong></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"><?php esc_html_e('Total', 'auto-moto-stock'); ?>
                            <strong><?php echo wp_kses_post(amotos_get_format_money($invoice_meta['invoice_item_price'])) ; ?></strong></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"><?php esc_html_e('Payment Method', 'auto-moto-stock'); ?>
                            <strong>
                                <?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_method($invoice_meta['invoice_payment_method'])) ;  ?>
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"><?php esc_html_e('Payment Type', 'auto-moto-stock'); ?>
                            <strong>
                                <?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_type($invoice_meta['invoice_payment_type']));  ?>
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="amotos-heading">
                    <h2><?php echo wp_kses_post(amotos_get_option('thankyou_title_wire_transfer','')) ; ?></h2>
                </div>
                <div class="amotos-thankyou-content">
                    <?php
                    $html_info=amotos_get_option('thankyou_content_wire_transfer','');
                    echo wp_kses_post(wpautop($html_info)); ?>
                </div>
                <a href="<?php echo esc_url(amotos_get_permalink('my_cars')); ?>"
                   class="btn btn-primary"> <?php esc_html_e('Go to Dashboard', 'auto-moto-stock'); ?> </a>
            </div>
        </div>
    <?php else: ?>
        <div class="amotos-heading">
            <h2><?php echo wp_kses_post(amotos_get_option('thankyou_title','')); ?></h2>
        </div>
        <div class="amotos-thankyou-content">
            <?php
            $html_info=amotos_get_option('thankyou_content','');
            echo wp_kses_post(wpautop($html_info)); ?>
           </div>
        <a href="<?php echo esc_url(amotos_get_permalink('my_cars')) ; ?>"
           class="btn btn-primary"> <?php esc_html_e('Go to Dashboard', 'auto-moto-stock'); ?> </a>
    <?php endif;
    do_action('amotos_after_payment_completed');
    ?>
</div>