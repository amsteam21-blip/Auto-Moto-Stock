<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * @var $car_mileage
 * @var $measurement_units_mileage
 */
?>
<div class="amotos__loop-car-info-item car-mileage" data-toggle="tooltip" title="<?php esc_attr_e( 'Mileage', 'auto-moto-stock' ); ?>">
    <i class="fa fa-exchange"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo wp_kses_post( sprintf( '%s %s', amotos_get_format_number( $car_mileage ), $measurement_units_mileage ) ); ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html__('Mileage', 'auto-moto-stock')?></span>
    </div>
</div>

