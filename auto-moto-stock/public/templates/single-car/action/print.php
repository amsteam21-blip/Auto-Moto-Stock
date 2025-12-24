<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
$wrapper_classes = array(
    'amotos__car-print',
    'amotos__loop-car_action-item'
);
$wrapper_class = join(' ', $wrapper_classes);

$wrapper_class = join(' ', $wrapper_classes);
?>
<a class="<?php echo esc_attr($wrapper_class)?>" href="javascript:void(0)" id="car-print"
   data-ajax-url="<?php echo esc_url(AMOTOS_AJAX_URL) ; ?>" data-toggle="tooltip"
   data-original-title="<?php esc_attr_e( 'Print', 'auto-moto-stock' ); ?>"
   data-car-id="<?php echo esc_attr( $car_id ); ?>"><i class="fa fa-print"></i></a>

