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
<span class="amotos__car-power"><?php echo wp_kses_post(sprintf( '%s %s', amotos_get_format_number($car_power), $measurement_units_power)); ?></span>
