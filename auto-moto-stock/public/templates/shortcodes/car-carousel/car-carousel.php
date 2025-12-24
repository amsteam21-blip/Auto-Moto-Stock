<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Shortcode attributes
 * @var $atts
 */
$car_type = $car_status = $car_styling = $car_city = $car_state = $car_neighborhood =
$car_label = $car_featured = $item_amount = $columns_gap=$image_size = $color_scheme = $include_heading = $heading_sub_title = $heading_title = $el_class = '';
extract(shortcode_atts(array(
    'car_type' => '',
    'car_status' => '',
    'car_styling' => '',
    'car_city' => '',
    'car_state' => '',
    'car_neighborhood' => '',
    'car_label' => '',
    'car_featured' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-0',
    'image_size' => amotos_get_loop_car_image_size_default(),
    'color_scheme' => 'color-dark',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'el_class' => ''
), $atts));

$car_item_class = array('car-item');
$car_content_class = array('car-content');

$wrapper_classes = array(
	'amotos-car-carousel',
	'amotos-car',
	'car-carousel',
	'owl-nav-inline',
    $color_scheme,
	$el_class
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
$car_content_class[] = 'owl-carousel amotos__owl-carousel';

$owl_attributes = array(
	'dots' => false,
	'nav' => true,
	'responsive' => array(
		'0' => array(
			'items' => 1,
			'margin' => $col_gap
		),
		'768' => array(
			'items' => 2,
			'margin' => $col_gap
		),
		'1200' => array(
			'items' => 3,
			'margin' => $col_gap
		),
		'1820' => array(
			'items' => 4,
			'margin' => $col_gap
		),
	)
);


$_atts =  array(
    'item_amount' => ($item_amount > 0) ? $item_amount : -1,
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
$args = apply_filters('amotos_shortcodes_car_carousel_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
<div class="amotos-car-wrap">
    <div class="<?php echo esc_attr(join(' ', $wrapper_classes))  ?>">
        <div class="navigation-wrap">
            <?php
                if ($include_heading) {
                    amotos_template_heading(array(
                        'heading_title' => $heading_title,
                        'heading_sub_title' => $heading_sub_title,
                        'color_scheme' => $color_scheme
                    ));
                }
            ?>
        </div>
        <div class="<?php echo esc_attr(join(' ', $car_content_class)) ?>" data-callback="owl_callback" data-plugin-options="<?php echo esc_attr(wp_json_encode($owl_attributes)) ?>">
           <?php  if ($data->have_posts())  {
                while ($data->have_posts()) {
                    $data->the_post();
                    amotos_get_template('content-car.php', array(
                        'custom_car_image_size' => $image_size,
                        'car_item_class' => $car_item_class,
                    ));
                }
           } else {
               amotos_get_template('loop/content-none.php');
           } ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</div>

