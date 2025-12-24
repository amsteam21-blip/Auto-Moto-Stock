<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $car_address
 * @var $google_map_address_url
 * @var $extra_class
 */
$wrapper_classes = array(
  'car-location',
  'amotos__loop-car-location'
);

if (isset($extra_class)) {
    $wrapper_classes[] = $extra_class;
}

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>" title="<?php echo esc_attr( $car_address ) ?>">
	<i class="fa fa-map-marker"></i>
	<a target="_blank" href="<?php echo esc_url( $google_map_address_url ); ?>"><span><?php echo esc_attr( $car_address ) ?></span></a>
</div>
