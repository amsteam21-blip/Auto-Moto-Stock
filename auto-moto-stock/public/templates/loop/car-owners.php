<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_owners
 */
?>
<div class="amotos__loop-car-info-item car-owners" data-toggle="tooltip" title="<?php  /* translators: %s: Number of Vehicle owners. */ echo esc_attr(sprintf( _n( '%s Owner', '%s Owners', $car_owners, 'auto-moto-stock' ), $car_owners )); ?>">
    <i class="fa fa-users"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo esc_html( $car_owners ) ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html(_n( 'Owner', 'Owners', $car_owners, 'auto-moto-stock' )) ?></span>
    </div>
</div>
