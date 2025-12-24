<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $data array
 * @var $google_map_address_url
 */
$wrapper_classes = array(
    'single-car-element',
    'car-location',
    'amotos__single-car-element',
    'amotos__single-car-address'
);

$wrapper_class = join(' ', apply_filters('amotos_single_car_address_wrapper_classes',$wrapper_classes));
$item_class = apply_filters('amotos_single_car_address_item_class','col-sm-6 col-12');
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Address', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
        <ul class="list-unstyled mb-0 row amotos__car-address-list">
            <?php foreach ($data as $k => $v): ?>
                <li class="<?php echo esc_attr($item_class)?> <?php echo esc_attr($k)?>">
                    <div class="d-flex amotos__car-location-item">
                        <strong class="mr-2"><?php echo wp_kses_post($v['label'])?></strong>
                        <span><?php echo wp_kses_post($v['content'])?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (!empty($google_map_address_url)): ?>
            <a class="open-on-google-maps" target="_blank"
               href="<?php echo esc_url($google_map_address_url); ?>"><?php esc_html_e('Open on Google Maps', 'auto-moto-stock'); ?>
                <i class="fa fa-map-marker"></i></a>
        <?php endif; ?>
    </div>
</div>
