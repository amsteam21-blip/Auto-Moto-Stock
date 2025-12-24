<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$wrapper_classes = array(
    'amotos__single-car-element',
    'single-car-element',
    'car-info-header',
    'car-info-action',
    'amotos__single-car-header-info'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_header_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">

    <div class="amotos__single-car-header-info-inner">
        <?php
        /**
         * Hook: amotos_single_car_header_info.
         *
         * @hooked amotos_template_single_car_title - 5
         * @hooked amotos_template_single_car_header_price_location - 10
         */
        do_action('amotos_single_car_header_info');
        ?>
    </div>
    <?php
    /**
     * Hook: amotos_after_single_car_header_info.
     *
     * @hooked amotos_template_single_car_header_meta_action - 5
     */
    do_action('amotos_after_single_car_header_info');
    ?>
</div>
