<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */

$wrapper_classes = array(
    'single-car-element',
    'amotos__single-car-element',
    'amotos__single-car-map'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_map_wrapper_classes',$wrapper_classes));
$location = amotos_car_get_location_data($car_id);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Get Directions', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
        <div id="amotos__single_car_map" class="amotos__map-canvas" data-location="<?php echo esc_attr(wp_json_encode($location)) ?>"></div>
        <div class="amotos__single-car-map-directions">
            <input type="text" class="form-control amotos__scmd-input" placeholder="<?php esc_attr_e('Enter a location', 'auto-moto-stock'); ?>">
            <button type="button" class="btn btn-primary amotos__scmd-btn"><i class="fa fa-search"></i></button>
            <span style="display: none" class="amotos__scmd-total"><span><?php echo esc_html__('Distance:','auto-moto-stock')?></span> <span class="amotos__scmd-number"></span></span>
        </div>
    </div>
</div>

