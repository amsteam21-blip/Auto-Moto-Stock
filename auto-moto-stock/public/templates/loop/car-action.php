<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<div class="amotos__loop-car-action car-action">
    <?php
    /**
     * amotos_car_action hook.
     *
     * @hooked amotos_template_loop_car_action_view_gallery - 5
     * @hooked amotos_template_loop_car_action_favorite - 10
     * @hooked amotos_template_loop_car_action_compare - 15
     */
    do_action( 'amotos_loop_car_action', $car_id ); ?>
</div>