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

$filter_classes = array(
    'car-filter-content',
    'car-filter-carousel'
);

$filter_id = wp_rand();

$filter_attributes = array(
    'data-layout_style' => $layout_style,
    'data-car_type' => $car_type,
    'data-car_status' => $car_status,
    'data-car_styling' => $car_styling,
    'data-car_cities' => $car_cities,
    'data-car_state' => $car_state,
    'data-car_neighborhood' => $car_neighborhood,
    'data-car_label' => $car_label,
    'data-color_scheme' => $color_scheme,
    'data-item_amount' => $item_amount,
    'data-image_size' => $image_size2,
    'data-include_heading' => $include_heading,
    'data-heading_sub_title' => $heading_sub_title,
    'data-heading_title' => $heading_title,
    'data-heading_text_align' => $heading_text_align,
    'data-car_city' => $car_city,
    'data-el_class' => $el_class,
    'data-item' => '.car-item',
    'data-filter-type' => 'carousel',
    'data-filter_id' => $filter_id
);


$owl_attributes = array(
    'dots' => true,
    'nav' => false,
    'items' => 1,
    'autoHeight' => true,
    'autoplay' => false,
    'autoplayTimeout' => 1000
);

$car_content_attributes = array(
    'data-type' => 'carousel',
    'data-filter-content' => 'filter',
    'data-plugin-options' => $owl_attributes,
    'data-layout' => 'filter',
    'data-filter_id' => $filter_id
);

$car_city_arr = explode(',', $car_cities);
$filter_class = join(' ', $filter_classes);
$wrapper_class = join(' ', apply_filters('amotos_sc_car_featured_layout_car_cities_filter_wrapper_classes',$wrapper_classes));
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
    <div class="car-content-wrap row no-gutters">
        <div class="filter-wrap col-lg-3" data-admin-url="<?php echo esc_url( wp_nonce_url( AMOTOS_AJAX_URL, 'amotos_car_featured_fillter_city_ajax_action', 'amotos_car_featured_fillter_city_ajax_nonce' ) ); ?>">
            <div class="<?php echo esc_attr($filter_class)?>" <?php amotos_render_html_attr($filter_attributes); ?>>
                <?php foreach ($car_city_arr as $k => $v): ?>
                    <?php
                        $city = get_term_by('slug', $v, 'car-city', 'OBJECT');
                        if (!is_a($city,'WP_Term')) {
                            continue;
                        }
                        $a_classes = array('portfolio-filter-category');
                        if ($k == 0) {
                            $a_classes[] = 'active-filter';
                        }
                        $a_class = join(' ', $a_classes);
                    ?>
                    <a class="<?php echo esc_attr($a_class)?>" data-filter=".<?php echo esc_attr($city->slug)?>" href="#"><?php echo esc_html($city->name)?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="car-content-inner col-lg-9">
            <?php if ($data->have_posts()): ?>
                <div class="car-content owl-carousel amotos__owl-carousel" <?php amotos_render_html_attr($car_content_attributes); ?>>
                    <?php while ($data->have_posts()): ?>
                    <?php $data->the_post(); ?>
                        <div class="car-item">
                            <div class="car-inner">
                                <div class="car-image">
                                    <?php amotos_template_loop_car_image(array(
                                        'image_size' => $image_size2,
                                        'image_size_default' => '835x320'
                                    )); ?>
                                </div>
                                <div class="car-item-content">
                                    <div class="car-heading-inner">
                                        <?php
                                        /**
                                         * Hook: amotos_sc_car_featured_layout_car_cities_filter_loop_car_heading.
                                         *
                                         * @hooked amotos_template_loop_car_title - 5
                                         * @hooked amotos_template_loop_car_price - 10

                                         */
                                        do_action('amotos_sc_car_featured_layout_car_cities_filter_loop_car_heading');
                                        ?>
                                    </div>
                                    <?php
                                    /**
                                     * Hook: amotos_after_sc_car_featured_layout_car_cities_filter_loop_car_heading.
                                     *
                                     * @hooked amotos_template_single_car_info - 5
                                     */
                                    do_action('amotos_after_sc_car_featured_layout_car_cities_filter_loop_car_heading');
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
    </div>
</div>
