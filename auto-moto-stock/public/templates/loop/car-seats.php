<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_seats
 */
?>
<div class="amotos__loop-car-info-item car-seats" data-toggle="tooltip" title="<?php /* translators: %s: Number of Vehicle seats. */ echo esc_attr(sprintf( _n( '%s Seat', '%s Seats', $car_seats, 'auto-moto-stock' ), $car_seats )); ?>">
    <i class="fa fa-ellipsis-h"></i>
    <div class="amotos__lpi-content">
        <span class="amotos__lpi-value"><?php echo esc_html( $car_seats ) ?></span>
        <span class="amotos__lpi-label"><?php echo esc_html(_n( 'Seat', 'Seats', $car_seats, 'auto-moto-stock' )) ?></span>
    </div>
</div>