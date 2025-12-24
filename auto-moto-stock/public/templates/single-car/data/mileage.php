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
<span class="amotos__car-mileage"><?php echo wp_kses_post(sprintf( '%s %s', amotos_get_format_number($car_mileage), $measurement_units_mileage)); ?></span>
