<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
$wrapper_classes = array(
  'amotos__single-car-action'
);

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php
    /**
     * amotos_single_car_action hook.
     *
     * @hooked amotos_template_single_car_action_social_share - 5
     * @hooked amotos_template_loop_car_action_favorite - 10
     * @hooked amotos_template_loop_car_action_compare - 15
     * @hooked amotos_template_single_car_action_print - 20
     */
    do_action('amotos_single_car_action',$car_id);
    ?>
</div>