<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $car_id
 * @var $data
 */
$wrapper_classes = array(
    'single-car-element',
    'car-nearby-places',
    'amotos__single-car-element',
    'amotos__single-car-nearby-places'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_nearby_places_wrapper_classes',$wrapper_classes));

$options = array(
    'cluster_marker_enable' => false
);

$location = amotos_car_get_location_data($car_id);

?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Nearby Places', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
        <div class="amotos__single-car-nearby-places-inner">
            <div class="amotos__nearby-places row">
                <div class="amotos__nbp-map col-md-5">
                    <div style="--amotos-map-height: <?php echo esc_attr($data['map_height'])?>px" data-location="<?php echo esc_attr(wp_json_encode($location)) ?>" data-options="<?php echo esc_attr(wp_json_encode($options))?>" id="amotos__single_car_nearby_places" data-nearby-options="<?php echo esc_attr(wp_json_encode($data))?>" class="amotos__map-canvas"></div>
                </div>
                <div class="amotos__nbp-content col-md-7">
                    <div class="amotos__nbp-content-inner"></div>
                </div>
            </div>

        </div>
    </div>
</div>
