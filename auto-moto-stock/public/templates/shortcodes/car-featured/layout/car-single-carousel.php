<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $layout_style
 * @var $data
 * @var $car_type
 * @var $car_status
 * @var $car_styling
 * @var $car_cities
 * @var $car_state
 * @var $car_neighborhood
 * @var $car_label
 * @var $color_scheme
 * @var $item_amount
 * @var $image_size1
 * @var $image_size2
 * @var $image_size3
 * @var $image_size4
 * @var $include_heading
 * @var $heading_sub_title
 * @var $heading_title
 * @var $heading_text_align
 * @var $car_city
 * @var $el_class
 */

$wrapper_classes = array(
    'amotos-car-featured',
    'amotos-car',
    'clearfix',
    $layout_style,
    $color_scheme,
    $el_class
);

$owl_options = apply_filters('amotos_sc_car_featured_layout_car_single_carousel_owl_options',array(
    'dots' => true,
    'nav' => false,
    'items' => 1
));

$wrapper_class = join(' ', apply_filters('amotos_sc_car_featured_layout_car_single_carousel_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php if ($include_heading) {
        amotos_template_heading(array(
            'heading_title' => $heading_title,
            'heading_sub_title' => $heading_sub_title,
            'heading_text_align' => $heading_text_align,
            'color_scheme' => $color_scheme
        ));
    }?>
    <?php if ($data->have_posts()): ?>
        <div class="car-content-wrap">
            <div class="car-content-inner">
                <div class="car-content owl-carousel amotos__owl-carousel" data-plugin-options="<?php echo esc_attr(wp_json_encode($owl_options)) ?>">
                    <?php while ($data->have_posts()): ?>
                        <?php $data->the_post(); ?>
                        <div class="car-item">
                            <div class="car-inner">
                                <div class="car-image">
                                    <?php amotos_template_loop_car_image(array(
                                        'image_size' => $image_size3,
                                        'image_size_default' => '570x320'
                                    )); ?>
                                </div>
                                <div class="car-item-content">
                                    <?php
                                    /**
                                     * Hook: amotos_sc_car_featured_layout_car_single_carousel_loop_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_title - 5
                                     */
                                    do_action('amotos_before_sc_car_featured_layout_car_single_carousel_loop_car_heading');
                                    ?>

                                    <div class="car-heading-inner">
                                        <?php
                                        /**
                                         * Hook: amotos_sc_car_featured_layout_car_single_carousel_loop_car_heading.
                                         *
                                         * @hooked amotos_template_loop_car_price - 5
                                         * @hooked amotos_template_loop_car_status - 10

                                         */
                                        do_action('amotos_sc_car_featured_layout_car_single_carousel_loop_car_heading');
                                        ?>
                                    </div>

                                    <?php
                                    /**
                                     * Hook: amotos_after_sc_car_featured_layout_car_single_carousel_loop_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_location - 5
                                     * @hooked amotos_template_loop_car_excerpt - 10
                                     * @hooked amotos_template_single_car_info - 15
                                     */
                                    do_action('amotos_after_sc_car_featured_layout_car_single_carousel_loop_car_heading');
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php amotos_get_template('loop/content-none.php'); ?>
    <?php endif; ?>
</div>
