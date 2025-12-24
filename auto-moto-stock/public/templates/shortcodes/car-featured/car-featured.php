<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Shortcode attributes
 * @var $atts
 */
$layout_style = $car_type = $car_status = $car_styling = $car_cities = $car_state =
$car_neighborhood = $car_label = $color_scheme = $item_amount = $include_heading =
$heading_sub_title = $heading_title = $heading_text_align = $car_city = $el_class = $image_size1=$image_size2=$image_size3=$image_size4='';
extract(shortcode_atts(array(
	'layout_style' => 'car-list-two-columns',
	'car_type' => '',
	'car_status' => '',
	'car_styling' => '',
	'car_cities' => '',
	'car_state' => '',
	'car_neighborhood' => '',
	'car_label' => '',
	'color_scheme' => 'color-dark',
	'item_amount' => '6',
	'image_size1'        => '240x180',
	'image_size2'        => '835x320',
	'image_size3'        => '570x320',
	'image_size4'        => '945x605',
	'include_heading' => '',
	'heading_sub_title' => '',
	'heading_title' => '',
	'heading_text_align' => '',
	'car_city' => '',
	'el_class' => ''
), $atts));

if (!in_array($layout_style,array('car-cities-filter','car-list-two-columns','car-single-carousel','car-sync-carousel'))) {
    $layout_style = 'car-cities-filter';
}

$wrapper_styles = array();
$car_item_class = array();
$car_content_class = array('car-content-wrap');
if (empty($car_cities)) {
	$car_ids = array();
	$args1 = array(
		'posts_per_page' => -1,
		'post_type' => 'car',
		'orderby'   => array(
			'menu_order'=>'ASC',
			'date' =>'DESC',
		),
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => AMOTOS_METABOX_PREFIX . 'car_featured',
				'value' => true,
				'compare' => '=',
			)
		)
	);
	$data = new WP_Query($args1);
	if ($data->have_posts()) :
		while ($data->have_posts()): $data->the_post();
			$car_ids[] = get_the_ID();
		endwhile;
	endif;
	wp_reset_postdata();

	$car_city_all = wp_get_object_terms($car_ids, 'car-city');
	$car_cities = array();
	if (is_array($car_city_all)) {
		foreach ($car_city_all as $car_ct) {
			$car_cities[] = $car_ct->slug;
		}
		$car_cities = join(',', $car_cities);
	}
}
if ($layout_style == 'car-cities-filter') {
	if (!empty($car_cities) && empty($car_city)) {
		$car_city = explode(',', $car_cities)[0];
	}
}
$wrapper_classes = array(
	'amotos-car-featured clearfix',
	$layout_style,
	$color_scheme,
	$el_class
);

if ($layout_style == 'car-list-two-columns') {
	$car_content_class[] = 'row';
	$car_item_class[] = 'mg-bottom-30';
	$car_content_class[] = 'columns-2';
	$car_item_class[] = 'amotos-item-wrap';
	$car_item_class[] = 'mg-bottom-30';
	$wrapper_classes[] = 'amotos-car car-list';
}

$_atts =  array(
    'item_amount' => ($item_amount > 0) ? $item_amount : -1,
    'featured' => true
);
if (!empty($car_city)) {
    $_atts['city'] = explode(',', $car_city);
} elseif (!empty($car_cities)) {
    $_atts['city'] = explode(',', $car_cities);
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
$args = apply_filters('amotos_shortcodes_car_featured_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;

amotos_get_template('shortcodes/car-featured/layout/' . $layout_style . '.php',
    array(
        'layout_style' => $layout_style,
        'data' => $data,
        'car_type' => $car_type,
        'car_status' => $car_status,
        'car_styling' => $car_styling,
        'car_cities' => $car_cities,
        'car_state' => $car_state,
        'car_neighborhood' => $car_neighborhood,
        'car_label' => $car_label,
        'color_scheme' => $color_scheme,
        'item_amount' => $item_amount,
        'image_size1' => $image_size1,
        'image_size2' => $image_size2,
        'image_size3' => $image_size3,
        'image_size4' => $image_size4,
        'include_heading' => $include_heading,
        'heading_sub_title' => $heading_sub_title,
        'heading_title' => $heading_title,
        'heading_text_align' => $heading_text_align,
        'car_city' => $car_city,
        'el_class' => $el_class,
    ));
wp_reset_postdata();


