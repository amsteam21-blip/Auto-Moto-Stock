<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_identity
 */
?>
<div class="amotos__loop-car-info-item car-identity" data-toggle="tooltip" title="<?php esc_attr_e( 'Vehicle ID', 'auto-moto-stock' ); ?>">
    <i class="fa fa-barcode"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo esc_html($car_identity); ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html__('Vehicle ID', 'auto-moto-stock')?></span>
    </div>
</div>
