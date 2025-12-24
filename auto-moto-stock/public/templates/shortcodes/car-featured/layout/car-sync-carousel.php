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
$images_arr = array();
$wrapper_class = join(' ', apply_filters('amotos_sc_car_featured_layout_car_sync_carousel_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php if ($data->have_posts()): ?>
        <div class="car-sync-content-wrap row">
            <div class="col-xl-6 car-main-content">
                <div class="main-content-inner">
                    <?php if ($include_heading) {
                        amotos_template_heading(array(
                            'heading_title' => $heading_title,
                            'heading_sub_title' => $heading_sub_title,
                            'heading_text_align' => $heading_text_align,
                            'color_scheme' => $color_scheme
                        ));
                    }?>
                    <div class="car-content-carousel owl-carousel">
                        <?php while ($data->have_posts()): ?>
                            <?php $data->the_post(); ?>
                            <div class="car-item">
                                <?php ob_start(); ?>
                                <div class="car-image">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>">
                                        <?php amotos_template_loop_car_image(array(
                                            'image_size' => $image_size4,
                                            'image_size_default' => '570x320'
                                        )); ?>
                                    </a>
                                </div>
                                <?php $images_arr[] = ob_get_clean(); ?>
                                <div class="car-item-content">
                                    <?php
                                    /**
                                     * Hook: amotos_sc_car_featured_layout_car_sync_carousel_loop_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_title - 5
                                     */
                                    do_action('amotos_before_sc_car_featured_layout_car_sync_carousel_loop_car_heading');
                                    ?>

                                    <div class="car-heading-inner">
                                        <?php
                                        /**
                                         * Hook: amotos_sc_car_featured_layout_car_sync_carousel_loop_car_heading.
                                         *
                                         * @hooked amotos_template_loop_car_price - 5
                                         * @hooked amotos_template_loop_car_status - 10

                                         */
                                        do_action('amotos_sc_car_featured_layout_car_sync_carousel_loop_car_heading');
                                        ?>
                                    </div>

                                    <?php
                                    /**
                                     * Hook: amotos_after_sc_car_featured_layout_car_sync_carousel_loop_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_location - 5
                                     * @hooked amotos_template_loop_car_excerpt - 10
                                     * @hooked amotos_template_single_car_info - 15
                                     */
                                    do_action('amotos_after_sc_car_featured_layout_car_sync_carousel_loop_car_heading');
                                    ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 car-image-content">
                <div class="car-image-carousel owl-carousel  owl-nav-inline">
                    <?php foreach ($images_arr as $image) {
                        echo wp_kses_post($image);
                    } ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php amotos_get_template('loop/content-none.php'); ?>
    <?php endif; ?>
</div>
