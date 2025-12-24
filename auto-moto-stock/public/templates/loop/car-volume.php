<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * @var $car_volume
 * @var $measurement_units_volume
 */
?>
<div class="amotos__loop-car-info-item car-volume" data-toggle="tooltip" title="<?php esc_attr_e( 'Cubic Capacity', 'auto-moto-stock' ); ?>">
    <i class="fa fa-superpowers"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo wp_kses_post( sprintf( '%s %s', amotos_get_format_number( $car_volume ), $measurement_units_volume ) ); ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html__('Cubic Capacity', 'auto-moto-stock')?></span>
    </div>
</div>

