<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<div class="car-element-inline">
    <?php
    /**
     * Hook: amotos_loop_car_meta.
     *
     * @hooked amotos_template_loop_car_type - 5
     * @hooked amotos_template_loop_car_manager - 10
     * @hooked amotos_template_loop_car_date - 15
     */
    do_action('amotos_loop_car_meta',$car_id);
    ?>
</div>
