<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<script type="text/html" id="tmpl-amotos-processing-template">
    <div class="amotos-processing">
        <div class="loading">
            <i class="{{{data.ico}}}"></i><span>{{{data.text}}}</span>
        </div>
    </div>
</script>
<script type="text/html" id="tmpl-amotos-alert-template">
    <div class="amotos-alert-popup">
        <div class="content-popup">
            <div class="message">
                <i class="{{{data.ico}}}"></i><span>{{{data.text}}}</span>
            </div>
            <div class="btn-group">
                <a href="javascript:void(0)" class="btn-close"><?php esc_html_e('Close', 'auto-moto-stock') ?></a>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="tmpl-amotos-dialog-template">
    <div class="amotos-dialog-popup" id="amotos-dialog-popup">
        <div class="content-popup">
            <div class="message">
                <i class="{{{data.ico}}}"></i><span>{{{data.message}}}</span>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="tmpl-amotos__mc_template">
    <div class="amotos__mc-result">
        <div class="amotos__loan-amount"><span><?php echo esc_html__('Loan Amount','auto-moto-stock') ?>:</span> <strong>{{{data.loan_amount}}}</strong></div>
        <div class="amotos__years"><span><?php echo esc_html__('Years','auto-moto-stock') ?>:</span> <strong>{{{data.years}}}</strong></div>
        <div class="amotos__monthly"><span><?php echo esc_html__('Monthly','auto-moto-stock') ?>:</span> <strong>{{{data.monthly_payment}}}</strong></div>
        <div class="amotos__bi_weekly"><span><?php echo esc_html__('Bi Weekly','auto-moto-stock') ?>:</span> <strong>{{{data.bi_weekly_payment}}}</strong></div>
        <div class="amotos__weekly"><span><?php echo esc_html__('Weekly','auto-moto-stock') ?>:</span> <strong>{{{data.weekly_payment}}}</strong></div>
    </div>
</script>