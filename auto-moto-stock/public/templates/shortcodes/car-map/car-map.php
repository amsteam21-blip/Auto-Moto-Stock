<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Shortcode attributes
 * @var $atts
 */
$map_style = $icon = $car_id = $lat = $lng = $map_height = $el_class = '';
extract(shortcode_atts(array(
    'map_style'   => 'car',
    'icon'        => '',
    'car_id' => '',
    'lat'         => '',
    'lng'         => '',
    'map_height'  => '500px',
    'el_class'    => ''
), $atts));


$title = $icon_url = $img_src = $car_address = $link = $share_social = '';
$icon = isset($icon) ? $icon : '';
$id = uniqid('amotos_sc_car_map');
$location_args = array(
);
if ($map_style == 'car') {
    $location_args = amotos_car_get_location_data($car_id);
    $location_args['marker_type'] = 'simple';
}

if ($map_style == 'normal') {
    $location_args  = array(
        'position' => array(
            'lat' => floatval($lat) ,
            'lng' => floatval($lng),
        ),
        'marker_type' => 'simple'
    );
}


$wrapper_attr = array();
if ($map_height != '') {
    $wrapper_attr['style'] = "--amotos-map-height: {$map_height};";
}
if (!empty($icon)) {
    $icon_src = wp_get_attachment_image_src($icon, 'full');
    if (is_array($icon_src)) {
        $marker_html = sprintf( '<img src="%s" />', esc_url( $icon_src[0] ) );
        $location_args['marker'] = array(
            'type' => 'image',
            'html' =>  $marker_html
        );
        $location_args['marker_type'] = 'basic';
    }
}

$wrapper_classes = array(
     'amotos__map-canvas',
    'amotos__sc-car-map'
);

if (!empty($el_class)) {
    $wrapper_classes[] = $el_class;
}

$wrapper_class = join(' ', $wrapper_classes);

?>
<div <?php amotos_render_html_attr($wrapper_attr); ?> id="<?php echo esc_attr($id)?>" class="<?php echo esc_attr($wrapper_class)?>" data-location="<?php echo esc_attr(wp_json_encode($location_args)) ?>"></div>


