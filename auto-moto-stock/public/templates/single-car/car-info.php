<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<div class="amotos__loop-car-info car-info layout-2 amotos__single-car-info">
    <div class="car-info-inner">
        <?php
        /**
         * Hook: amotos_single_car_info.
         *
         * @hooked amotos_template_loop_car_identity - 5
         * @hooked amotos_template_loop_car_mileage - 10
         * @hooked amotos_template_loop_car_seats - 15
         * @hooked amotos_template_loop_car_owners - 20
         */
        do_action('amotos_single_car_info',$car_id);
        ?>
    </div>
</div>
