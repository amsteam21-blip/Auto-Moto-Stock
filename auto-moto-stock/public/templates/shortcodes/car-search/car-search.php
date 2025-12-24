<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $atts
 */
$search_styles = $show_status_tab = $keyword_enable = $title_enable = $address_enable = $city_enable = $type_enable = $status_enable = $doors_enable = $seats_enable =
$owners_enable =  $price_enable = $price_is_slider= $mileage_enable = $mileage_is_slider= $power_enable = $power_is_slider = $volume_enable = $volume_is_slider = $map_search_enable = $advanced_search_enable =
$country_enable = $state_enable =$neighborhood_enable = $label_enable =
$car_identity_enable=$other_stylings_enable = $color_scheme = $el_class = $request_city='';
extract(shortcode_atts(array(
    'search_styles' => 'style-default',
    'show_status_tab' => 'true',
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
    'price_is_slider'=>'',
    'mileage_enable' => '',
    'mileage_is_slider'=>'',
    'power_enable' => '',
    'power_is_slider'=>'',
    'volume_enable' => '',
    'volume_is_slider'=>'',
    'label_enable' => '',
    'car_identity_enable' => '',
    'other_stylings_enable' => '',
    'map_search_enable' => '',
    'color_scheme' => 'color-light',
    'el_class' => '',
), $atts));

if ($search_styles == 'style-mini-line') {
	$show_status_tab = false;
}

$status_default = $show_status_tab == 'true' ?  amotos_get_car_status_default_value() : '';

$wrapper_classes = array(
    'amotos-search-cars',
    'clearfix',
    $search_styles,
    $color_scheme,
    $el_class
);

if ($search_styles === 'style-vertical' || $search_styles === 'style-absolute') {
    $map_search_enable='true';
}
if ($map_search_enable=='true'){
    $wrapper_classes[] = 'amotos-search-cars-map';
}
if($show_status_tab=='true' && $search_styles!='style-mini-line')
{
    $wrapper_classes[] = 'amotos-show-status-tab';
}
if ($search_styles === 'style-vertical') {
    $class_col_half_map = 'col-lg-6';
    $wrapper_classes[] = 'row';
    $wrapper_classes[] = 'no-gutters';
} else {
    $class_col_half_map = '';
}

$enable_filter_location = amotos_get_option('enable_filter_location', 0);
$options = array();
if ($map_search_enable=='true'){
    $googlemap_zoom_level = amotos_get_option('googlemap_zoom_level', '12');
    $pin_cluster_enable = amotos_get_option('googlemap_pin_cluster', '1');
    $google_map_style = amotos_get_option('googlemap_style', '');
    $google_map_needed = 'true';
    $map_cluster_icon_url = AMOTOS_PLUGIN_URL . 'public/assets/images/map-cluster-icon.png';
    $default_cluster=amotos_get_option('cluster_icon','');
    if($default_cluster!='')
    {
        if(is_array($default_cluster)&& $default_cluster['url']!='')
        {
            $map_cluster_icon_url=$default_cluster['url'];
        }
    }
	$options = array(
		'ajax_url' => AMOTOS_AJAX_URL,
		'not_found' => esc_html__("We didn't find any results, you can retry with other keyword.", 'auto-moto-stock'),
		'googlemap_default_zoom' => esc_attr($googlemap_zoom_level),
		'clusterIcon' => esc_url($map_cluster_icon_url) ,
		'google_map_needed' => $google_map_needed,
		'google_map_style' => esc_attr($google_map_style) ,
		'pin_cluster_enable' => esc_attr($pin_cluster_enable),
		'price_is_slider'=> esc_attr($price_is_slider),
		'enable_filter_location'=> esc_attr($enable_filter_location)
	);
}else{
	$options = array(
		'ajax_url' => AMOTOS_AJAX_URL,
		'price_is_slider'=> esc_attr($price_is_slider),
		'enable_filter_location'=> esc_attr($enable_filter_location)
	);
}
$geo_location = amotos_get_option('geo_location');
/* Class col style for form*/
switch ($search_styles) {
    case 'style-mini-line':
        $css_class_field = 'col-xl-3 col-lg-6 col-md-6 col-12';
        $css_class_half_field = 'col-xl-3 col-lg-3 col-md-3 col-12';
        $show_status_tab='false';
        break;
    case 'style-default-small':
        $css_class_field = 'col-lg-4 col-md-6 col-12';
        $css_class_half_field = 'col-lg-2 col-md-3 col-12';
        break;
    case 'style-absolute':
        $css_class_field = 'col-lg-12 col-md-12 col-12';
        $css_class_half_field = 'col-lg-6 col-md-6 col-12';
        break;
    case 'style-vertical':
        $css_class_field = 'col-lg-6 col-md-6 col-12';
        $css_class_half_field = 'col-lg-3 col-md-3 col-12';
        break;
    default:
        $css_class_field = 'col-lg-4 col-md-6 col-12';
        $css_class_half_field = 'col-lg-2 col-md-3 col-12';
        break;
}
$css_class_field = apply_filters('amotos_car_search_css_class_field',$css_class_field,$search_styles);
$css_class_half_field = apply_filters('amotos_car_search_css_class_half_field',$css_class_half_field,$search_styles);
$wrapper_class = join(' ', $wrapper_classes);
?>
<div data-options="<?php echo esc_attr(wp_json_encode($options)); ?>" class="<?php echo esc_attr($wrapper_class) ?>">
    <?php if ($map_search_enable=='true') {
        amotos_template_car_map_search($class_col_half_map);
    } ?>
    <?php if($search_styles === 'style-vertical'):?>
    <div class="col-scroll-vertical col-lg-6">
        <?php endif;?>
        <?php amotos_template_car_search_form($atts,$css_class_field,$css_class_half_field,filter_var($show_status_tab, FILTER_VALIDATE_BOOLEAN)); ?>
        <?php if ($search_styles === 'style-vertical'): ?>
            <div class="car-result-wrap">
                <div class="list-car-result-ajax">
                    <?php
                    $_atts = [];
                    if (filter_var( $status_enable, FILTER_VALIDATE_BOOLEAN ) && !empty($status_default)) {
                        $_atts['status'] = $status_default;
                    }
                    if (filter_var( $price_is_slider, FILTER_VALIDATE_BOOLEAN ) && filter_var($price_enable, FILTER_VALIDATE_BOOLEAN)) {
                        $range_price = amotos_get_car_price_slider($status_default);
                        $_atts['min-price'] = $range_price['min-price'];
                        $_atts['max-price'] = $range_price['max-price'];
                    }

                    if (filter_var( $mileage_is_slider, FILTER_VALIDATE_BOOLEAN ) && filter_var($mileage_enable, FILTER_VALIDATE_BOOLEAN)) {
                        $min_mileage = amotos_get_option('car_mileage_slider_min', 0);
                        $max_mileage = amotos_get_option('car_mileage_slider_max', 1000);
                        $_atts['min-mileage'] = $min_mileage;
                        $_atts['max-mileage'] = $max_mileage;
                    }

                    if (filter_var( $power_is_slider, FILTER_VALIDATE_BOOLEAN ) && filter_var($power_enable, FILTER_VALIDATE_BOOLEAN)) {
                        $min_power = amotos_get_option('car_power_slider_min',0);
                        $max_power = amotos_get_option('car_power_slider_max',1000);
                        $_atts['min-power'] = $min_power;
                        $_atts['max-power'] = $max_power;

                    }

                    if (filter_var( $volume_is_slider, FILTER_VALIDATE_BOOLEAN ) && filter_var($volume_enable, FILTER_VALIDATE_BOOLEAN)) {
                        $min_volume = amotos_get_option('car_volume_slider_min',0);
                        $max_volume = amotos_get_option('car_volume_slider_max',1000);
                        $_atts['min-volume'] = $min_volume;
                        $_atts['max-volume'] = $max_volume;

                    }
                    $query_args = amotos_get_car_query_args($_atts);
                    $query_args = apply_filters('amotos_shortcodes_car_search_query_args',$query_args);
                    $data_vertical = new WP_Query($query_args);
                    $total_post = $data_vertical->found_posts;
                    $custom_car_image_size = amotos_get_loop_car_image_size_default();
                    $car_item_class = array('car-item');
                    ?>
                    <div class="title-result">
                        <h2 class="uppercase">
                            <span class="number-result"><?php echo esc_html($total_post) ?></span>
                            <span class="text-result">
	                            <?php echo esc_html__('Vehicles','auto-moto-stock'); ?>
                            </span>
                            <span class="text-no-result"><?php esc_html_e(' No Vehicle found', 'auto-moto-stock') ?></span>
                        </h2>
                    </div>
                    <div class="amotos-car car-carousel">
                        <?php
                        $owl_attributes = array(
                            'items' => 2,
                            'margin' => 30,
                            'nav' => true,
                            'responsive' => array(
	                            '0' => array(
		                            'items' => 1
	                            ),
	                            '600' => array(
		                            'items' => 2
	                            ),
	                            '992' => array(
		                            'items' => 1
	                            ),
	                            '1200' => array(
		                            'items' => 2
	                            )
                            )
                        );

                        ?>
                        <div class="owl-carousel amotos__owl-carousel owl-nav-top-right" data-plugin-options="<?php echo esc_attr(wp_json_encode($owl_attributes)) ?>">
                            <?php if ($data_vertical->have_posts()) :
                                $index = 0;
                                while ($data_vertical->have_posts()): $data_vertical->the_post();?>
                                    <?php amotos_get_template('content-car.php', array(
                                        'custom_car_image_size' => $custom_car_image_size,
                                        'car_item_class' => $car_item_class,
                                    )); ?>
                                <?php endwhile;
                            else: ?>
                                <?php amotos_get_template('loop/content-none.php'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php wp_reset_postdata();?>
        <?php endif; ?>
        <?php if($search_styles === 'style-vertical'):?>
    </div>
<?php endif;?>
</div>