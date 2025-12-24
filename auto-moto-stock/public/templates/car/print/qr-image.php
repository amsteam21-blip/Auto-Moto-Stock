<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<img class="amotos__print-car-qr-image"
     src="https://quickchart.io/qr?text=<?php echo esc_url(get_permalink($car_id)); ?>&size=100"
     title="<?php echo esc_attr(get_the_title($car_id)); ?>"/>
