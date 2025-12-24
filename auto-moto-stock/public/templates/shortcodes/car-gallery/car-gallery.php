<?php
/**
 * @var $atts
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$car_types = $car_status = $car_styling = $car_cities = $car_state =
$car_neighborhood = $car_label = $car_featured = $is_carousel = $color_scheme = $category_filter = $filter_style =
$include_heading = $heading_sub_title = $heading_title = $item_amount = $image_size = $columns_gap = $columns =
$dots = $nav = $autoplay = $autoplaytimeout = $car_type = $el_class = '';
extract(shortcode_atts(array(
    'car_types' => '',
    'car_status' => '',
    'car_styling' => '',
    'car_cities' => '',
    'car_state' => '',
    'car_neighborhood' => '',
    'car_label' => '',
    'car_featured' => '',
    'is_carousel' => '',
    'color_scheme' => 'color-dark',
    'category_filter' => '',
    'filter_style' => 'filter-isotope',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'item_amount' => '6',
    'image_size' => amotos_get_sc_car_gallery_image_size_default(),
    'columns_gap' => 'col-gap-0',
    'columns' => '4',
    'dots' => '',
    'nav' => '',
    'autoplay' => 'false',
    'autoplaytimeout' => 1000,
    'car_type' => '',
    'el_class' => ''
), $atts));

$car_item_class = array('car-item');
$car_content_class = array('car-content clearfix');
$car_content_attributes = array();
$content_attributes = array();
$filter_class = array('hidden-mb car-filter-content');

$filter_attributes = array();

if (empty($car_types)) {
    $car_types_all = get_categories(array('taxonomy' => 'car-type', 'hide_empty' => 0, 'orderby' => 'ASC'));
    $car_types = array();
    if (is_array($car_types_all)) {
        foreach ($car_types_all as $car_typ) {
            $car_types[] = $car_typ->slug;
        }
        $car_types = join(',', $car_types);
    }
}

if ($category_filter) {
    $filter_attributes['data-is-carousel'] = $is_carousel;
    $filter_attributes['data-columns-gap'] = $columns_gap;
    $filter_attributes['data-columns'] = $columns;
    $filter_attributes['data-item-amount'] = $item_amount;
    $filter_attributes['data-image-size'] = $image_size;
    $filter_attributes['data-color-scheme'] = $color_scheme;
    $filter_attributes['data-item'] = '.car-item';

    $content_attributes['data-filter-content'] = 'filter';
}
$wrapper_classes = array(
    'amotos-car-gallery',
    'amotos-car',
    'clearfix',
    $color_scheme,
    $el_class,
);

if ($columns_gap == 'col-gap-30') {
    $col_gap = 30;
} elseif ($columns_gap == 'col-gap-20') {
    $col_gap = 20;
} elseif ($columns_gap == 'col-gap-10') {
    $col_gap = 10;
} else {
    $col_gap = 0;
}
if (filter_var($is_carousel,FILTER_VALIDATE_BOOLEAN)) {
    $content_attributes['data-type'] = 'carousel';
    $car_content_class[] = 'owl-carousel amotos__owl-carousel';

    $owl_attributes = array(
        'dots' => (bool) $dots,
        'nav' => (bool) $nav,
        'items' => (int)$columns,
        'autoplay' => (bool) $autoplay,
        'autoplayTimeout' => ($autoplaytimeout ? (int)$autoplaytimeout  : 1000),
        'responsive' => array(
            '0' => array(
                'items' => 1,
                'margin' => 0
            ),
            '480' => array(
	            'items' => 2,
	            'margin' => $col_gap
            ),
            '992' => array(
	            'items' => ($columns >= 3) ? 3 : (int)$columns,
	            'margin' => $col_gap
            ),
            '1200' => array(
	            'items' => (int)$columns,
	            'margin' => $col_gap
            )
        )
    );
    $car_content_attributes['data-plugin-options'] = $owl_attributes;

    if ($category_filter) {
        $filter_class[] = 'car-filter-carousel';
        $filter_attributes['data-filter-type'] = 'carousel';
        $content_attributes['data-layout'] = 'filter';
    }
} else {
    $content_attributes['data-type'] = 'grid';
    $content_attributes['data-layout'] = 'fitRows';

    $car_content_class[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $car_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $car_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $car_item_class[] = 'mg-bottom-10';
    }
    $car_content_class[] = 'row';
    $car_content_class[] = 'columns-' . $columns;
    $car_content_class[] = 'columns-md-' . ($columns >= 3 ? 3 : $columns);
    $car_content_class[] = 'columns-sm-2';
    $car_content_class[] = 'columns-xs-2';
    $car_content_class[] = 'columns-mb-1';
    $car_item_class[] = 'amotos-item-wrap';
    if ($category_filter) {
        $filter_attributes['data-filter-type'] = 'filter';
        $filter_attributes['data-filter-style'] = $filter_style;
    }
}


$_atts =  array(
    'item_amount' => ($item_amount > 0) ? $item_amount : -1,
    'featured' => $car_featured
);


if (!empty($author)) {
    $_atts['author_id'] = $author;
}

if (!empty($car_type)) {
    $_atts['type'] = explode(',', $car_type);
}

if (!empty($car_status)) {
    $_atts['status'] = explode(',', $car_status);
}

if (!empty($car_styling)) {
    $_atts['stylings'] = explode(',', $car_styling);
}
if (!empty($car_city)) {
    $_atts['city'] = explode(',', $car_city);
}

if (!empty($car_state)) {
    $_atts['state'] = explode(',', $car_state);
}
if (!empty($car_neighborhood)) {
    $_atts['neighborhood'] = explode(',', $car_neighborhood);
}

if (!empty($car_label)) {
    $_atts['label'] = explode(',', $car_label);
}

$args = amotos_get_car_query_args($_atts);
$args = apply_filters('amotos_shortcodes_car_gallery_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
<div class="amotos-car-wrap">
    <div class="<?php echo esc_attr(join(' ', $wrapper_classes))?>">
        <?php $filter_id = wp_rand(); ?>
        <?php if ($category_filter):
            $filter_item_class = 'portfolio-filter-category';
            ?>
            <div class="filter-wrap">
                <div class="filter-inner" data-admin-url="<?php echo esc_url( wp_nonce_url( AMOTOS_AJAX_URL, 'amotos_car_gallery_fillter_ajax_action', 'amotos_car_gallery_fillter_ajax_nonce' ) ); ?>">
                    <?php
                        if ($include_heading) {
                            amotos_template_heading(array(
                                'heading_title' => $heading_title,
                                'heading_sub_title' => $heading_sub_title,
                                'extra_classes' => array($color_scheme)
                            ));
                        }
                     ?>
                    <div data-filter_id="<?php echo esc_attr($filter_id); ?>" <?php amotos_render_html_attr($filter_attributes); ?>
                        class="<?php echo esc_attr(join(' ', $filter_class)); ?>">
                        <?php
                        if (!empty($car_types)) {
                            $car_type_arr = explode(',', $car_types);?>
                            <a data-filter="*" class="<?php echo esc_attr($filter_item_class); ?> active-filter"><?php esc_html_e('All', 'auto-moto-stock') ?></a>
                            <?php
                            foreach ($car_type_arr as $type_item) {
                                $type = get_term_by('slug', $type_item, 'car-type', 'OBJECT'); ?>
                                <a class="<?php echo esc_attr($filter_item_class); ?>"
                                   data-filter=".<?php echo esc_attr($type_item); ?>"><?php echo esc_attr($type->name) ?></a>
                                <?php
                            }
                        } ?>
                    </div>
                    <select class="visible-mb car-filter-mb form-control">
                        <?php
                        if (!empty($car_types)) {
                            $car_type_arr = explode(',', $car_types);?>
                            <option value="*" selected><?php esc_html_e('All', 'auto-moto-stock') ?></option>
                            <?php
                            foreach ($car_type_arr as $type_item) {
                                $type = get_term_by('slug', $type_item, 'car-type', 'OBJECT'); ?>
                                <option value=".<?php echo esc_attr($type_item); ?>"><?php echo esc_html($type->name) ?></option>
                                <?php
                            }
                        } ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($is_carousel): ?>
        <div class="<?php echo esc_attr(join(' ', $car_content_class))  ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
            data-callback="owl_callback" <?php echo esc_attr(amotos_render_html_attr($car_content_attributes)); ?>
            <?php amotos_render_html_attr($content_attributes);  ?>>
            <?php else: ?>
            <div class="<?php echo esc_attr(join(' ', $car_content_class))  ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
                <?php amotos_render_html_attr($content_attributes);  ?>>
                <?php endif; ?>

                <?php if ($data->have_posts()): ?>
                    <?php while ($data->have_posts()): $data->the_post(); ?>
                        <?php
                            $car_id=get_the_ID();
                            $car_type_list = get_the_terms($car_id, 'car-type');
                            $car_type_class = array();
                            if ($car_type_list) {
                                foreach ($car_type_list as $type) {
                                    $car_type_class[] = $type->slug;
                                }
                            }
                        ?>
                    <div class="<?php echo esc_attr(join(' ', array_merge($car_item_class, $car_type_class))); ?>">
                        <div class="car-inner">
                            <div class="car-image">
                                <?php amotos_template_loop_car_image(array(
                                    'image_size' => $image_size,
                                    'car_id' => $car_id,
                                    'image_size_default' => amotos_get_sc_car_gallery_image_size_default()
                                )); ?>
                                <div class="car-item-content">
                                    <?php
                                    /**
                                     * Hook: amotos_sc_car_gallery_loop_car_content.
                                     *
                                     * @hooked amotos_template_loop_car_title - 5
                                     * @hooked amotos_template_loop_car_price - 10
                                     * @hooked amotos_template_loop_car_location - 15
                                     */
                                    do_action('amotos_sc_car_gallery_loop_car_content');
                                    ?>
                                </div>
                                <?php
                                /**
                                 * Hook: amotos_sc_car_gallery_after_loop_car_content.
                                 * @hooked amotos_template_loop_car_link - 5
                                 */
                                do_action('amotos_sc_car_gallery_after_loop_car_content');
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <?php amotos_get_template('loop/content-none.php'); ?>
                <?php endif; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>