<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $data array
 */
global $post;
$car_id=get_the_ID();
$car_meta_data = get_post_custom($car_id);
$car_neighborhood = get_the_terms($car_id, 'car-neighborhood');
$car_neighborhood_arr = array();
if ($car_neighborhood) {
    foreach ($car_neighborhood as $neighborhood_item) {
        $car_neighborhood_arr[] = $neighborhood_item->name;
    }
}

$car_city = get_the_terms($car_id, 'car-city');
$car_city_arr = array();
if ($car_city) {
    foreach ($car_city as $city_item) {
        $car_city_arr[] = $city_item->name;
    }
}

$car_state = get_the_terms($car_id, 'car-state');
$car_state_arr = array();
if ($car_state) {
    foreach ($car_state as $state_item) {
        $car_state_arr[] = $state_item->name;
    }
}

$car_location = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_location', true);
$car_address = isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_address']) ? $car_meta_data[AMOTOS_METABOX_PREFIX . 'car_address'][0] : '';
$car_country = isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_country']) ? $car_meta_data[AMOTOS_METABOX_PREFIX . 'car_country'][0] : '';
$car_zip = isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_zip']) ? $car_meta_data[AMOTOS_METABOX_PREFIX . 'car_zip'][0] : '';

$wrapper_classes = array(
    'single-car-element',
    'car-location',
    'amotos__single-car-element',
    'amotos__single-car-location'
);

$wrapper_class = join(' ', apply_filters('amotos_single_car_location_wrapper_classes',$wrapper_classes));
$item_class = apply_filters('amotos_single_car_location_item_class','col-sm-6 col-12');
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Address', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
        <ul class="list-unstyled row amotos__car-address-list">
            <?php foreach ($data as $k => $v): ?>
                <li class="<?php echo esc_attr($item_class)?> <?php echo esc_attr($k)?>">
                    <div class="d-flex amotos__car-location-item">
                        <strong class="mr-2"><?php echo wp_kses_post($v['label'])?></strong>
                        <span><?php echo wp_kses_post($v['content'])?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (!empty($car_address)): ?>
            <div class="car-address">
                <strong><?php esc_html_e('Address:', 'auto-moto-stock'); ?></strong>
                <span><?php echo esc_html($car_address) ?></span>
            </div>
        <?php endif; ?>
        <ul class="amotos__list-2-col">
            <?php if (!empty($car_country)):
                $car_country = amotos_get_country_by_code($car_country); ?>
                <li>
                    <strong><?php esc_html_e('Country:', 'auto-moto-stock'); ?></strong>
                    <span><?php echo esc_html($car_country); ?></span>
                </li>
            <?php endif;
            if (count($car_state_arr) > 0): ?>
                <li>
                    <strong><?php esc_html_e('Province/State:', 'auto-moto-stock'); ?></strong>
                    <span><?php echo esc_html(join(', ', $car_state_arr)) ; ?></span>
                </li>
            <?php endif;
            if (count($car_city_arr) > 0): ?>
                <li>
                    <strong><?php esc_html_e('City/Town:', 'auto-moto-stock'); ?></strong>
                    <span><?php echo esc_html(join(', ', $car_city_arr)); ?></span>
                </li>
            <?php endif;
            if (count($car_neighborhood_arr) > 0): ?>
                <li>
                    <strong><?php esc_html_e('Neighborhood:', 'auto-moto-stock'); ?></strong>
                    <span><?php echo esc_html(join(', ', $car_neighborhood_arr)); ?></span>
                </li>
            <?php endif;
            if (!empty($car_zip)): ?>
                <li>
                    <strong><?php esc_html_e('Postal code / ZIP:', 'auto-moto-stock'); ?></strong>
                    <span><?php echo esc_html($car_zip) ?></span>
                </li>
            <?php endif; ?>
        </ul>
        <?php if ($car_location):
            $google_map_address_url = "http://maps.google.com/?q=" . $car_location['address'];
            ?>
            <a class="open-on-google-maps" target="_blank"
               href="<?php echo esc_url($google_map_address_url); ?>"><?php esc_html_e('Open on Google Maps', 'auto-moto-stock'); ?>
                <i class="fa fa-map-marker"></i></a>
        <?php endif; ?>
    </div>
</div>