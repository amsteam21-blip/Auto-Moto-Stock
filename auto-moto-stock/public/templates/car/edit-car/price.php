<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_car_fields,$car_data, $car_meta_data;
$dec_point = amotos_get_price_decimal_separator();
$car_price_short_format = '^[0-9]+([' . $dec_point . '][0-9]+)?$';
$car_price_short = isset( $car_meta_data[ AMOTOS_METABOX_PREFIX . 'car_price_short' ]) ? $car_meta_data[ AMOTOS_METABOX_PREFIX . 'car_price_short' ][0] : '';
$car_price_short = amotos_format_localized_price($car_price_short);
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Vehicle Price', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-price">
        <div class="row">
        <?php
        if (!in_array("car_price", $hide_car_fields)) {
            $enable_price_unit=amotos_get_option('enable_price_unit', '1');
            $price_short_class='col-sm-6';
            if($enable_price_unit=='1')
            {
                $price_short_class='col-sm-3';
            }
        ?>
            <div class="<?php echo esc_attr($price_short_class); ?>">
                <div class="form-group">
                    <label for="car_price_short"> <?php esc_html_e( 'Price', 'auto-moto-stock' ); 
                    echo esc_html(amotos_required_field( 'car_price' ));
                    echo esc_html(amotos_get_option('currency_sign', '')) . ' ';?>  </label>
	                <input pattern="<?php echo esc_attr($car_price_short_format)?>" type="text" id="car_price_short" class="form-control" name="car_price_short"
	                       value="<?php echo esc_attr($car_price_short)?>">
	                <small class="form-text text-muted">
                        <?php /* translators: %s: decimal point. */ echo sprintf(esc_html__('Example value: 1745%s25','auto-moto-stock'),esc_html($dec_point)) ?></small>
                </div>
            </div>
            <?php if($enable_price_unit=='1'){?>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="car_price_unit"><?php esc_html_e('Unit', 'auto-moto-stock');
                            echo esc_html(amotos_required_field('car_price_unit')); ?></label>
                        <select name="car_price_unit" id="car_price_unit" class="form-control">
                            <option value="1" <?php if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'] ) &&  $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'][0]=='1') { echo 'selected';} ?>><?php esc_html_e('None', 'auto-moto-stock');?></option>
                            <option value="1000" <?php if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'] ) &&  $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'][0]=='1000') { echo 'selected';} ?>><?php esc_html_e('Thousand', 'auto-moto-stock');?></option>
                            <option value="1000000" <?php if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'] ) &&  $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_unit'][0]=='1000000') { echo 'selected';} ?>><?php esc_html_e('Million', 'auto-moto-stock');?></option>
                        </select>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?php
        if (!in_array("car_price_prefix", $hide_car_fields)) {
            ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="car_price_prefix"><?php esc_html_e( 'Before Price Label (ex: Start From)', 'auto-moto-stock' ); 
                    echo esc_html(amotos_required_field( 'car_price_prefix' )); ?></label>
                    <input type="text" id="car_price_prefix" value="<?php if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_prefix'] ) ) { echo esc_attr( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_prefix'][0] ); } ?>" class="form-control" name="car_price_prefix">
                </div>
            </div>
        <?php } ?>
        <?php
        if (!in_array("car_price_postfix", $hide_car_fields)) {
            ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="car_price_postfix"><?php esc_html_e( 'After Price Label (ex: Per Month)', 'auto-moto-stock' ); 
                    echo esc_html(amotos_required_field( 'car_price_postfix' )); ?></label>
                    <input type="text" id="car_price_postfix" value="<?php if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_postfix'] ) ) { echo esc_attr( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_postfix'][0] ); } ?>" class="form-control" name="car_price_postfix">
                </div>
            </div>
        <?php } ?>
        <?php
        if (!in_array("car_price_on_call", $hide_car_fields)) {?>
            <div class="col-sm-12">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="car_price_on_call" name="car_price_on_call" <?php
                    if( isset( $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_on_call'] ) && $car_meta_data[AMOTOS_METABOX_PREFIX. 'car_price_on_call'][0]=='1') echo ' checked="checked"'?>
                    >
                    <label class="form-check-label" for="car_price_on_call"><?php esc_html_e( 'Price on Call', 'auto-moto-stock' ); 
                    echo esc_html(amotos_required_field( 'car_price_on_call' )); ?></label>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
</div>