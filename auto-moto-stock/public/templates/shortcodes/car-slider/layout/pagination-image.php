<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $data
 * @var $image_size
 */
$wrapper_classes = array(
    'car-content',
);
$wrapper_class = join(' ', $wrapper_classes);
$car_ids = array();
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php if ($data->have_posts()): ?>
        <div class="car-content-slider owl-carousel">
            <?php while ($data->have_posts()): ?>
                <?php
                $data->the_post();
                $car_ids[] = get_the_ID();
                ?>
            <div class="car-item">
                <div class="car-inner">
                    <div class="car-image">
                        <?php amotos_template_loop_car_image(array(
                            'image_size' => $image_size,
                            'image_size_default' => amotos_get_sc_car_slider_image_size_default()
                        )); ?>
                    </div>
                    <div class="car-item-content">
                        <div class="car-heading">
                            <?php
                            /**
                             * Hook: amotos_sc_car_gallery_layout_pagination_image_loop_car_heading.
                             *
                             * @hooked amotos_template_loop_car_location - 5
                             * @hooked amotos_template_loop_car_title - 10
                             * @hooked amotos_template_loop_car_price - 15
                             */
                            do_action('amotos_sc_car_gallery_layout_pagination_image_loop_car_heading');
                            ?>
                        </div>
                        <?php
                        /**
                         * Hook: amotos_after_sc_car_gallery_layout_pagination_image_loop_car_content.
                         *
                         * @hooked amotos_template_loop_car_info_layout_2 - 5
                         */
                        do_action('amotos_after_sc_car_gallery_layout_pagination_image_loop_car_content');
                        ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="car-slider-image-wrap">
            <div class="car-image-slider owl-carousel">
                <?php $thumb_image_size = amotos_get_sc_car_slider_thumb_image_size_default(); ?>
                <?php foreach ($car_ids as $car_id): ?>
                    <div class="car-image">
                        <?php amotos_template_loop_car_image(array(
                            'car_id' => $car_id,
                            'image_size' => $thumb_image_size,
                            'image_size_default' => $thumb_image_size
                        )); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <?php amotos_get_template('loop/content-none.php'); ?>
    <?php endif; ?>
</div>
