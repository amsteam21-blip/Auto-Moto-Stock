<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 * @var $layout
 */
?>
<div class="amotos__loop-car-info car-info <?php echo esc_attr($layout)?>">
    <div class="car-info-inner">
        <?php
        /**
         * Hook: amotos_loop_car_info.
         *
         * @hooked amotos_template_loop_car_mileage - 5
         * @hooked amotos_template_loop_car_seats - 10
         * @hooked amotos_template_loop_car_owners - 15
         */
        do_action('amotos_loop_car_info',$car_id);
        ?>
    </div>
</div>
