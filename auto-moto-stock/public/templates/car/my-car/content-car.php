<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$image_size = amotos_get_my_car_image_size();
?>
<div class="amotos__car-item">
    <div class="amotos__car-item-inner">
        <div class="amotos__car-image">
            <?php amotos_template_loop_car_image(array(
                'image_size' => $image_size
            ));
            /**
             * Hook: amotos_after_loop_my_car_thumbnail.
             *
             * @hooked amotos_template_loop_my_car_featured - 5
             * @hooked amotos_template_loop_my_car_status - 10
             */
            do_action('amotos_after_loop_my_car_thumbnail');
            ?>
        </div>
        <div class="amotos__car-content">
            <?php
            /**
             * Hook: amotos_loop_my_car_content.
             *
             * @hooked amotos_template_loop_my_car_title - 5
             * @hooked amotos_template_loop_my_car_meta - 10
             * @hooked amotos_template_loop_my_car_action - 15
             */
            do_action('amotos_loop_my_car_content');
            ?>
        </div>
    </div>
</div>
