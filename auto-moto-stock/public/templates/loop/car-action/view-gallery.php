<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 * @var $total_image
 */
$wrapper_classes = array(
    'car-view-gallery',
    'amotos__loop-car_action-item'
);
$wrapper_class = join(' ', $wrapper_classes);
?>
<a data-toggle="tooltip"
   title="<?php /* translators: %s: Number of photos. */ echo esc_attr(sprintf(__('(%s) Photos', 'auto-moto-stock'), $total_image)); ?>"
   data-car-id="<?php echo esc_attr($car_id); ?>"
   href="javascript:void(0)" class="<?php echo esc_attr($wrapper_class)?>">
    <i class="fa fa-camera"></i>
</a>
