<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 * @var $image_size
 * @var $extra_classes
 */

$wrapper_classes = array(
    'car-image'
);

if (!empty($extra_classes)) {
    if (is_array($extra_classes)) {
        $wrapper_classes = wp_parse_args($extra_classes, $wrapper_classes);
    }

    if (is_string($extra_classes)) {
        $wrapper_classes[] = $extra_classes;
    }
}
$wrapper_class = join(' ', apply_filters('amotos_loop_car_thumbnail_wrapper_classes', $wrapper_classes, $car_id));
?>
<div class="<?php echo esc_attr($wrapper_class) ?>">
    <?php
    amotos_template_loop_car_image(array(
        'image_size' => $image_size,
        'car_id' => $car_id
    ));
    /**
     * Hook: amotos_after_loop_car_thumbnail.
     *
     * @hooked amotos_template_loop_car_action - 5
     * @hooked amotos_template_loop_car_featured_label - 10
     * @hooked amotos_template_loop_car_term_status - 15
     * @hooked amotos_template_loop_car_link - 20
     */
    do_action('amotos_after_loop_car_thumbnail');
    ?>
</div>

