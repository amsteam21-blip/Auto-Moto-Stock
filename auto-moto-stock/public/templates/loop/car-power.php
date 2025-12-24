<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * @var $car_power
 * @var $measurement_units_power
 */
?>
<div class="amotos__loop-car-info-item car-power" data-toggle="tooltip" title="<?php esc_attr_e( 'Power', 'auto-moto-stock' ); ?>">
    <i class="fa fa-product-hunt"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo wp_kses_post( sprintf( '%s %s', amotos_get_format_number( $car_power ), $measurement_units_power ) ); ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html__('Power', 'auto-moto-stock')?></span>
    </div>
</div>

