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
<span class="amotos__car-volume"><?php echo wp_kses_post(sprintf( '%s %s', amotos_get_format_number($car_volume), $measurement_units_volume)); ?></span>
