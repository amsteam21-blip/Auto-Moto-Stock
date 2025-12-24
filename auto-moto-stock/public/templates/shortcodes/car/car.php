<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$layout_style = $car_type = $car_status = $car_styling = $car_city = $car_state = $car_neighborhood =
$car_label = $car_featured = $item_amount = $columns_gap = $columns = $items_md = $items_sm = $items_xs = $items_mb =
$view_all_link = $image_size = $show_paging = $include_heading = $heading_sub_title = $heading_title =
$dots = $nav = $move_nav = $nav_position = $autoplay = $autoplaytimeout = $paged = $author_id = $manager_id = $el_class = '';
extract(shortcode_atts(array(
    'layout_style' => 'car-grid',
    'car_type' => '',
    'car_status' => '',
    'car_styling' => '',
    'car_city' => '',
    'car_state' => '',
    'car_neighborhood' => '',
    'car_label' => '',
    'car_featured' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-30',
    'columns' => '3',
    'items_lg' => '4',
    'items_md' => '3',
    'items_sm' => '2',
    'items_xs' => '1',
    'items_mb' => '1',
    'view_all_link' => '',
    'image_size' => amotos_get_loop_car_image_size_default(),
    'show_paging' => '',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'dots' => '',
    'nav' => 'true',
    'move_nav' => '',
    'nav_position' => '',
    'autoplay' => 'true',
    'autoplaytimeout' => '1000',
    'paged' => '1',
    'author_id' => '',
    'manager_id' => '',
    'el_class' => ''
), $atts));
$car_item_class = array('amotos-item-wrap car-item');
$car_content_classes = array('car-content');
$car_content_attributes = array();
$wrapper_attributes = array();
$wrapper_classes = array(
    'amotos-car',
    'clearfix',
    $layout_style,
    $el_class
);

if ($layout_style == 'car-zigzac' || $layout_style == 'car-list') {
    $columns_gap = 'col-gap-0';
}
if ($layout_style == 'car-carousel') {
    $car_content_classes[] = 'owl-carousel amotos__owl-carousel';
    if ($nav) {
	    if (in_array($nav_position, array('top-right','bottom-center'))) {
            $car_content_classes[] = 'owl-nav-size-sm';
	    }
        if (!$move_nav && !empty($nav_position)) {
            $car_content_classes[] = 'owl-nav-' . $nav_position;
        } elseif ($move_nav) {
            $car_content_classes[] = 'owl-nav-top-right';
            $car_content_classes[] = 'owl-nav-size-sm';
            $wrapper_classes[] = 'owl-move-nav-par-with-heading';
        }
    }
    if ($columns_gap == 'col-gap-30') {
        $col_gap = 30;
    } elseif ($columns_gap == 'col-gap-20') {
        $col_gap = 20;
    } elseif ($columns_gap == 'col-gap-10') {
        $col_gap = 10;
    } else {
        $col_gap = 0;
    }

	$owl_attributes = array(
		'dots' => (bool) $dots,
		'nav' => (bool) $nav,
		'autoplay' => (bool) $autoplay,
		'autoplayTimeout' => $autoplaytimeout ? (int)$autoplaytimeout  : 1000,
		'responsive' => array(
			"0" => array(
				'items' => (int)$items_mb,
				'margin' => ($items_mb > 1) ? $col_gap  : 0
			),
			"480" => array(
				'items' => (int)$items_xs,
				'margin' => ($items_xs > 1) ? $col_gap  : 0
			),
			"768" => array(
				'items' => (int)$items_sm,
				'margin' => ($items_sm > 1) ? $col_gap  : 0
			),
			"992" => array(
				'items' => (int)$items_md,
				'margin' => ($items_md > 1) ? $col_gap  : 0
			),
			"1200" => array(
				'items' => ($columns >= 4) ? 4 : (int) $columns,
				'margin' => ($columns > 1) ? $col_gap  : 0
			),
			"1820" => array(
				'items' => (int)$columns,
				'margin' => $col_gap
			)
		)
	);

    $car_content_attributes['data-plugin-options'] = $owl_attributes;
} else {
    $car_content_classes[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $car_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $car_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $car_item_class[] = 'mg-bottom-10';
    }
    $car_content_classes[] = 'clearfix';
    if ($layout_style == 'car-grid') {
        $car_content_classes[] = 'columns-' . $columns . ' columns-md-' . $items_md . ' columns-sm-' . $items_sm . ' columns-xs-' . $items_xs . ' columns-mb-' . $items_mb;
    }
    if ($layout_style == 'car-list') {
        //$image_size = '330x180';
        $car_item_class[] = 'mg-bottom-30';
    }
    if ($layout_style == 'car-zigzac') {
        //$image_size = '290x270';
        $car_content_classes[] = 'columns-2 columns-md-2 columns-sm-1';
    }
}

if (!empty($view_all_link)) {
    $wrapper_attributes['data-view-all-link'] = $view_all_link;
}

$_atts =  array(
    'item_amount' => ($item_amount > 0) ? $item_amount : -1,
    'paged' => $paged,
    'author_id' => $author_id,
    'manager_id' => $manager_id,
    'featured' => $car_featured
);

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
$args = apply_filters('amotos_shortcodes_car_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
$car_content_class = join(' ', apply_filters('amotos_shortcodes_car_content_classes',$car_content_classes,$atts));
$wrapper_class = join(' ', apply_filters('amotos_shortcodes_car_wrapper_classes',$wrapper_classes,$atts));
?>
<div class="amotos-car-wrap">
    <div class="<?php echo esc_attr($wrapper_class)  ?>" <?php amotos_render_html_attr($wrapper_attributes); ?>>
        <?php if ($include_heading) : ?>
        <div class="container">
            <?php amotos_template_heading(array(
                'heading_title' => $heading_title,
                'heading_sub_title' => $heading_sub_title,
            )) ?>
        </div>
        <?php endif; ?>
        <?php if ($layout_style == 'car-carousel'): ?>
        <div class="<?php echo esc_attr($car_content_class)  ?>" data-section-id="<?php echo esc_attr(uniqid()) ; ?>"
             data-callback="owl_callback" <?php amotos_render_html_attr($car_content_attributes); ?>>
            <?php else: ?>
            <div class="<?php echo esc_attr($car_content_class)  ?>">
                <?php endif; ?>
                <?php if ($data->have_posts()) :
                    while ($data->have_posts()): $data->the_post();
	                    amotos_get_template('content-car.php', array(
		                    'custom_car_image_size' => $image_size,
		                    'car_item_class' => $car_item_class,
	                    ));
                        ?>
                    <?php endwhile;
                else: if (empty($manager_id) && empty($author_id)): ?>
                    <?php amotos_get_template('loop/content-none.php'); ?>
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($layout_style == 'car-carousel'): ?>
            </div>
            <?php else: ?>
        </div>
    <?php endif; ?>
        <?php if (!empty($view_all_link)): ?>
            <div class="view-all-link">
                <a href="<?php echo esc_url($view_all_link) ?>"
                   class="btn btn-xs btn-dark btn-classic"><?php esc_html_e('View All', 'auto-moto-stock') ?></a>
            </div>
        <?php endif; ?>
        <?php
        if ($show_paging == 'true') { ?>
            <div class="car-paging-wrap"
                 data-admin-url="<?php echo esc_url(wp_nonce_url( AMOTOS_AJAX_URL, 'amotos_car_paging_ajax_action', 'amotos_car_paging_ajax_nonce' ))   ?>"
                 data-layout="<?php echo esc_attr($layout_style); ?>"
                 data-items-amount="<?php echo esc_attr($item_amount); ?>"
                 data-columns="<?php echo esc_attr($columns); ?>"
                 data-image-size="<?php echo esc_attr($image_size); ?>"
                 data-columns-gap="<?php echo esc_attr($columns_gap); ?>"
                 data-view-all-link="<?php echo esc_attr($view_all_link); ?>"
                 data-car-type="<?php echo esc_attr($car_type); ?>"
                 data-car-status="<?php echo esc_attr($car_status); ?>"
                 data-car-styling="<?php echo esc_attr($car_styling); ?>"
                 data-car-city="<?php echo esc_attr($car_city); ?>"
                 data-car-state="<?php echo esc_attr($car_state); ?>"
                 data-car-neighborhood="<?php echo esc_attr($car_neighborhood); ?>"
                 data-car-label="<?php echo esc_attr($car_label); ?>"
                 data-car-featured="<?php echo esc_attr($car_featured); ?>"
                 data-author-id="<?php echo esc_attr($author_id); ?>"
                 data-manager-id="<?php echo esc_attr($manager_id); ?>">
                <?php $max_num_pages = $data->max_num_pages;
                set_query_var('paged', $paged);
                amotos_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                ?>
            </div>
        <?php }
        wp_reset_postdata(); ?>
    </div>
</div>

