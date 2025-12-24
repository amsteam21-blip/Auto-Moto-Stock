<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_car_identity = isset($_GET['car_identity']) ? amotos_clean(wp_unslash($_GET['car_identity']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <input type="text" class="amotos-car-identity form-control search-field" data-default-value=""
           value="<?php echo esc_attr($request_car_identity); ?>"
           name="car_identity"
           placeholder="<?php esc_attr_e('Vehicle ID', 'auto-moto-stock') ?>">
</div>