<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$car_gallery = amotos_get_car_gallery_image(get_the_ID());
$total_image = 0;
if ($car_gallery) {
	$total_image = count($car_gallery);
}
?>
<div class="car-view-gallery-wrap" data-toggle="tooltip" title="<?php /* translators: %s: Number of photos. */ echo esc_attr(sprintf( __( '(%s) Photos', 'auto-moto-stock' ), $total_image)) ; ?>">
    <a data-car-id="<?php the_ID(); ?>"
       href="javascript:void(0)" class="car-view-gallery"><i
            class="fa fa-camera"></i></a>
</div>