<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$layout_style = $car_type = $car_status = $car_styling = $car_city = $car_state = $car_neighborhood =
$car_label = $car_featured = $item_amount = $image_size = $el_class = '';
extract( shortcode_atts( array(
	'layout_style' => 'navigation-middle',
	'car_type' => '',
	'car_status' => '',
	'car_styling' => '',
	'car_city' => '',
	'car_state' => '',
	'car_neighborhood' => '',
	'car_label' => '',
    'car_featured' => '',
	'item_amount'       => '6',
	'image_size'        => amotos_get_sc_car_slider_image_size_default(),
	'el_class'          => ''
), $atts ) );

if (!in_array($layout_style,array('navigation-middle','pagination-image'))) {
    $layout_style = 'navigation-middle';
}


$wrapper_classes = array(
	'amotos-car-slider',
    'amotos-car',
    'clearfix',
	$layout_style,
	$el_class
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
$args = apply_filters('amotos_shortcodes_car_slider_query_args',$args);
$data = new WP_Query( $args );
$total_post = $data->found_posts;
?>
<div class="amotos-car-wrap">
	<div class="<?php echo esc_attr(join( ' ', $wrapper_classes ))  ?>">
        <?php amotos_get_template("shortcodes/car-slider/layout/{$layout_style}.php",array(
                'data' => $data,
                'image_size' => $image_size
        )); ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>

