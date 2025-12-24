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
  'owl-carousel',
  'amotos__owl-carousel',
);
$nav_id = uniqid('amotos__owl-nav-container-');
$owl_attributes = apply_filters('amotos_sc_car_slider_navigation_owl_options',array(
    'items' => 1,
    'dots' => false,
    'nav' => true,
    'navContainer' => ".{$nav_id}",
    'autoHeight' => true,
    'autoplay' => false,
    'autoplayTimeout' => 5000
)) ;
$wrapper_class = join(' ', $wrapper_classes);

?>
<div class="<?php echo esc_attr($wrapper_class)?>" data-callback="owl_callback" data-plugin-options="<?php echo esc_attr(wp_json_encode($owl_attributes))?>">
    <?php if ($data->have_posts()): ?>
        <?php while ($data->have_posts()): ?>
            <?php $data->the_post(); ?>
            <div class="car-item">
                <div class="car-inner">
                    <div class="car-image">
                        <?php amotos_template_loop_car_image(array(
                            'image_size' => $image_size,
                            'image_size_default' => amotos_get_sc_car_slider_image_size_default()
                        )); ?>
                    </div>
                    <div class="car-item-content">
                        <div class="container">
                            <div class="car-item-content-inner owl-nav-inline">
                                <div class="owl-nav <?php echo esc_attr($nav_id)?>">
                                </div>
                                <div class="car-heading">
                                    <?php
                                    /**
                                     * Hook: amotos_before_sc_car_slider_layout_navigation_middle_loop_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_title - 5
                                     */
                                    do_action('amotos_before_sc_car_slider_layout_navigation_middle_loop_car_heading');
                                    ?>
                                    <div class="car-heading-inner">
                                        <?php
                                        /**
                                         * Hook: amotos_sc_car_slider_layout_navigation_middle_loop_car_heading.
                                         *
                                         * @hooked amotos_template_loop_car_price - 10
                                         * @hooked amotos_template_loop_car_term_status - 15
                                         * @hooked amotos_template_loop_car_location - 20
                                         */
                                        do_action('amotos_sc_car_slider_layout_navigation_middle_loop_car_heading');
                                        ?>
                                    </div>
                                    <?php do_action('amotos_after_sc_car_slider_layout_navigation_middle_loop_car_heading'); ?>

                                </div>
                                <?php
                                /**
                                 * Hook: amotos_after_sc_car_slider_layout_navigation_middle_loop_car_content.
                                 *
                                 * @hooked amotos_template_loop_car_info_layout_2 - 5
                                 */
                                do_action('amotos_after_sc_car_slider_layout_navigation_middle_loop_car_content');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <?php amotos_get_template('loop/content-none.php'); ?>
    <?php endif; ?>
</div>
