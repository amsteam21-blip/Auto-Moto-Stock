<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<div class="amotos__loop-my-car-meta">
    <?php
    /**
     * Hook: amotos_loop_my_car_meta.
     *
     * @hooked amotos_template_loop_my_car_meta_view - 5
     * @hooked amotos_template_loop_my_car_meta_date - 10
     * @hooked amotos_template_loop_my_car_meta_expire_date - 15
     */
    do_action('amotos_loop_my_car_meta');
    ?>
</div>
