<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<div class="amotos__print-car-header">
    <div class="amotos__pph-content-left">
        <?php
        /**
         * amotos_print_car_header_left hook
         *
         * @hooked amotos_template_single_car_title - 5
         * @hooked amotos_template_single_car_location - 10
         * @hooked amotos_template_single_car_price - 15
         */
        do_action('amotos_print_car_header_left');
        ?>
    </div>
    <div class="amotos__pph-content-right">
        <?php
        /**
         * amotos_print_car_header_right hook
         *
         * @hooked amotos_template_print_car_qr_image - 5
         */
        do_action('amotos_print_car_header_right');
        ?>
    </div>
</div>
