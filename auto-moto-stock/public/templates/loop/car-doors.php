<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_doors
 */
?>
<div class="amotos__loop-car-info-item car-doors" data-toggle="tooltip" title="<?php /* translators: %s: Number of Vehicle doors. */ echo esc_attr(sprintf( _n( '%s Door', '%s Doors', $car_doors, 'auto-moto-stock' ), $car_doors )); ?>">
    <i class="fa fa-inbox"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo esc_html( $car_doors ) ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html(_n( 'Door', 'Doors', $car_doors, 'auto-moto-stock' )) ?></span>
    </div>
</div>