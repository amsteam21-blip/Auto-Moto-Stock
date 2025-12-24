<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$currency = amotos_get_option('currency_sign');
if (empty($currency)) {
	$currency = esc_html__('$', 'auto-moto-stock');
}
$id =  uniqid('amotos__mc_');
?>
<div class="amotos__loan-calculator-wrap">
    <form class="needs-validation amotos__mc-form" novalidate>
        <div class="form-group">
            <label for="<?php echo esc_attr($id)?>sale_price"><?php echo esc_html__('Sale Price', 'auto-moto-stock'); ?></label>
            <input type="text" class="form-control" id="<?php echo esc_attr($id)?>sale_price" name="sale_price" placeholder="<?php echo esc_attr($currency) ?>" required>
        </div>
        <div class="form-group">
            <label for="<?php echo esc_attr($id)?>down_payment"><?php echo esc_html__('Down Payment', 'auto-moto-stock'); ?></label>
            <input type="text" class="form-control" id="<?php echo esc_attr($id)?>down_payment" name="down_payment" placeholder="<?php echo esc_attr($currency) ?>" required>
        </div>
        <div class="form-group">
            <label for="<?php echo esc_attr($id)?>term_years"><?php echo esc_html__('Term[Years]', 'auto-moto-stock'); ?></label>
            <input type="text" class="form-control" id="<?php echo esc_attr($id)?>term_years" name="term_years" placeholder="<?php echo esc_attr__('Year', 'auto-moto-stock'); ?>" required>
        </div>
        <div class="form-group">
            <label for="<?php echo esc_attr($id)?>interest_rate"><?php echo esc_html__('Interest Rate in %', 'auto-moto-stock'); ?></label>
            <input type="text" class="form-control" id="<?php echo esc_attr($id)?>interest_rate" name="interest_rate" placeholder="<?php echo esc_attr__('%', 'auto-moto-stock'); ?>" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block amotos__btn-submit-loan-calculator"><?php echo esc_html__('Calculate', 'auto-moto-stock'); ?></button>
        </div>
    </form>
</div>
