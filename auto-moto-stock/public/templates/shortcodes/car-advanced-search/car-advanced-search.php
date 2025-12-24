<?php
/**
 * Created by StockTheme.
 */
/**
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$layout = $column = $address_enable = $keyword_enable = $title_enable = $city_enable = $type_enable = $status_enable = $doors_enable = $seats_enable =
$owners_enable = $price_enable = $price_is_slider = $mileage_enable = $mileage_is_slider = $power_enable = $power_is_slider = $volume_enable = $volume_is_slider = $country_enable = $state_enable = $neighborhood_enable = $label_enable =
$car_identity_enable = $other_stylings_enable = $color_scheme = $el_class = $request_city = '';
extract(shortcode_atts(array(
    'layout' => 'tab',
    'column' => '3',
    'status_enable' => 'true',
    'type_enable' => 'true',
    'keyword_enable' => 'true',
    'title_enable' => 'true',
    'address_enable' => 'true',
    'country_enable' => '',
    'state_enable' => '',
    'city_enable' => '',
    'neighborhood_enable' => '',
    'doors_enable' => '',
    'seats_enable' => '',
    'owners_enable' => '',
    'price_enable' => 'true',
    'price_is_slider' => '',
    'mileage_enable' => '',
    'mileage_is_slider' => '',
    'power_enable' => '',
    'power_is_slider' => '',
    'volume_enable' => '',
    'volume_is_slider' => '',
    'label_enable' => '',
    'car_identity_enable' => '',
    'other_stylings_enable' => '',
    'color_scheme' => 'color-light',
    'el_class' => ''
), $atts));

$wrapper_classes = array(
    'amotos-car-advanced-search',
    'clearfix',
    $layout,
    $color_scheme,
    $el_class,
);
$enable_filter_location = amotos_get_option('enable_filter_location', 0);
$options = array(
	'ajax_url' => esc_url(AMOTOS_AJAX_URL),
	'price_is_slider' => esc_attr($price_is_slider) ,
	'enable_filter_location'=> esc_attr($enable_filter_location)
);
$css_class_field = 'col-lg-4 col-md-6 col-12';
$css_class_half_field = 'col-lg-2 col-md-3 col-12';
if ($column == '1') {
    $css_class_field = 'col-lg-12 col-md-12 col-12';
    $css_class_half_field = 'col-lg-6 col-md-6 col-12';
} elseif ($column == '2') {
    $css_class_field = 'col-lg-6 col-md-6 col-12';
    $css_class_half_field = 'col-lg-3 col-md-3 col-12';
} elseif ($column == '3') {
    $css_class_field = 'col-lg-4 col-md-6 col-12';
    $css_class_half_field = 'col-lg-2 col-md-3 col-12';
} elseif ($column == '4') {
    $css_class_field = 'col-lg-3 col-md-6 col-12';
    $css_class_half_field = 'col-lg-3 col-md-3 col-12';
}
$css_class_field = apply_filters('amotos_car_advanced_search_css_class_field',$css_class_field,$column);
$css_class_half_field = apply_filters('amotos_car_advanced_search_css_class_half_field',$css_class_half_field,$column);
$show_status_tab = $status_enable == 'true' && $layout == 'tab';
$wrapper_class = join(' ', $wrapper_classes);
?>
<div data-options="<?php echo esc_attr(wp_json_encode($options)); ?>" class="<?php echo esc_attr($wrapper_class) ?>">
    <?php amotos_template_car_search_form($atts,$css_class_field,$css_class_half_field,$show_status_tab); ?>
</div>