<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
$wrapper_classes = array(
    'compare-car',
    'amotos__loop-car_action-item'
);
$wrapper_class = join(' ', $wrapper_classes);
?>
<a class="<?php echo esc_attr($wrapper_class)?>" href="javascript:void(0)"
   data-car-id="<?php echo esc_attr($car_id) ?>" data-toggle="tooltip"
   title="<?php esc_attr_e('Compare', 'auto-moto-stock') ?>">
    <i class="fa fa-plus"></i>
</a>
