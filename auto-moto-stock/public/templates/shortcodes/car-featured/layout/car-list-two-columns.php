<?php
    // Do not allow directly accessing this file.
    if (! defined('ABSPATH')) {
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
    $wrapper_classes = [
        'amotos-car-featured',
        'amotos-car',
        'car-list',
        'clearfix',
        $layout_style,
        $color_scheme,
        $el_class,
    ];

    $car_list_classes = [
        'car-content-wrap',
        'row',
        'columns-2',
    ];

    $car_item_classes = [
        'amotos-item-wrap',
        'mg-bottom-30',
    ];

    $wrapper_class  = join(' ', apply_filters('amotos_sc_car_featured_layout_car_list_two_columns_wrapper_classes', $wrapper_classes));
    $car_list_class = join(' ', apply_filters('amotos_sc_car_featured_layout_car_list_two_columns_car_list_classes', $car_list_classes));
    $car_item_class = join(' ', apply_filters('amotos_sc_car_featured_layout_car_list_two_columns_car_item_classes', $car_item_classes));
?>
<div class="<?php echo esc_attr($wrapper_class) ?>">
    <?php if ($include_heading) {
            amotos_template_heading([
                'heading_title'      => $heading_title,
                'heading_sub_title'  => $heading_sub_title,
                'heading_text_align' => $heading_text_align,
                'color_scheme'       => $color_scheme,
            ]);
    }?>
    <?php if ($data->have_posts()): ?>
        <div class="<?php echo esc_attr($car_list_class) ?>">
            <?php while ($data->have_posts()): ?>
            <?php $data->the_post(); ?>
            <div class="<?php echo esc_attr($car_item_class) ?>">
                <div class="car-inner">
                    <?php amotos_template_loop_car_thumbnail([
                            'image_size' => $image_size1,
                    ]); ?>
                    <div class="car-item-content">
                        <?php
                            /**
                             * Hook: amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content.
                             *
                             * @hooked amotos_template_loop_car_title - 5
                             * @hooked amotos_template_loop_car_price - 10
                             * @hooked amotos_template_loop_car_location - 15
                             * @hooked amotos_template_loop_car_excerpt - 20
                             * @hooked amotos_template_loop_car_link_detail - 25
                             */
                            do_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content');
                        ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <?php amotos_get_template('loop/content-none.php'); ?>
    <?php endif; ?>
</div>
