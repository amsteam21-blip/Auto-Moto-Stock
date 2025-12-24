<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$wrapper_classes = array(
    'amotos__single-car-header-price-location'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_header_price_location_wrapper_classes',$wrapper_classes));

?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php
    /**
     * Hook: amotos_single_car_header_price_location.
     *
     * @hooked amotos_template_single_car_price - 5
     * @hooked amotos_template_single_car_status - 10
     * @hooked amotos_template_single_car_location - 15
     */
    do_action('amotos_single_car_header_price_location');
    ?>
</div>