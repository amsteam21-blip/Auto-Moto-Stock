<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $atts
 */
$show_status_tab = $keyword_enable = $title_enable = $address_enable = $city_enable = $type_enable = $status_enable = $doors_enable = $seats_enable =
$owners_enable = $price_enable = $price_is_slider = $mileage_enable = $mileage_is_slider = $power_enable = $power_is_slider = $volume_enable = $volume_is_slider = $map_search_enable = $advanced_search_enable =
$country_enable = $state_enable = $neighborhood_enable = $label_enable =
$car_identity_enable = $other_stylings_enable = $color_scheme = $el_class = $request_city = $item_amount = $marker_image_size = $show_advanced_search_btn = '';
extract( shortcode_atts( array(
	'show_status_tab'          => 'true',
	'status_enable'            => 'true',
	'type_enable'              => 'true',
	'keyword_enable'           => 'true',
	'title_enable'             => 'true',
	'address_enable'           => 'true',
	'country_enable'           => '',
	'state_enable'             => '',
	'city_enable'              => '',
	'neighborhood_enable'      => '',
	'doors_enable'             => '',
	'seats_enable'             => '',
	'owners_enable'            => '',
	'price_enable'             => 'true',
	'price_is_slider'          => '',
	'mileage_enable'              => '',
	'mileage_is_slider'           => '',
	'power_enable'         => '',
	'power_is_slider'      => '',
	'volume_enable'         => '',
	'volume_is_slider'      => '',
	'label_enable'             => '',
	'car_identity_enable'      => '',
	'other_stylings_enable'    => '',
	'show_advanced_search_btn' => 'true',
	'item_amount'              => '18',
	'marker_image_size'        => '100x100',
	'el_class'                 => '',
), $atts ) );

$status_default            = $show_status_tab == 'true' ? amotos_get_car_status_default_value() : '';
$request_paged             = isset( $_GET['paged'] ) ? absint(amotos_clean(wp_unslash( $_GET['paged'] ))  ) : 1;
$wrapper_classes    = array(
    'amotos-search-map-cars',
    'clearfix',
	'color-light',
    'row',
    'no-gutters',
	$el_class,
);

$amotos_search           = new AMOTOS_Search();
$googlemap_zoom_level = amotos_get_option( 'googlemap_zoom_level', '12' );
$pin_cluster_enable   = amotos_get_option( 'googlemap_pin_cluster', '1' );
$google_map_style     = amotos_get_option( 'googlemap_style', '' );
$google_map_needed    = 'true';
$map_cluster_icon_url = AMOTOS_PLUGIN_URL . 'public/assets/images/map-cluster-icon.png';
$default_cluster      = amotos_get_option( 'cluster_icon', '' );
if ( $default_cluster != '' ) {
	if ( is_array( $default_cluster ) && $default_cluster['url'] != '' ) {
		$map_cluster_icon_url = $default_cluster['url'];
	}
}
/* Class col style for form*/
$css_class_field      = apply_filters('amotos_car_search_map_css_class_field','col-lg-4 col-md-4 col-12') ;
$css_class_half_field = apply_filters('amotos_car_search_map_css_class_half_field','col-lg-2 col-md-3 col-12') ;
$enable_filter_location = amotos_get_option('enable_filter_location', 0);
$options = array(
	'ajax_url'               => AMOTOS_AJAX_URL,
	'not_found'              => esc_html__( "We didn't find any results, you can retry with other keyword.", 'auto-moto-stock' ),
	'googlemap_default_zoom' => esc_attr($googlemap_zoom_level) ,
	'clusterIcon'            => esc_url($map_cluster_icon_url),
	'google_map_needed'      => $google_map_needed,
	'google_map_style'       => esc_attr($google_map_style),
	'pin_cluster_enable'     => esc_attr($pin_cluster_enable),
	'price_is_slider'        => esc_attr($price_is_slider),
	'item_amount'            => esc_attr($item_amount) ,
	'marker_image_size'      => esc_attr($marker_image_size) ,
	'enable_filter_location' => esc_attr($enable_filter_location)
);

$car_col_class = apply_filters('amotos_sc_car_search_map_car_col_class','columns-3 columns-md-3 columns-sm-2 columns-xs-1 columns-mb-1');

?>
<div data-options="<?php echo esc_attr(wp_json_encode($options)); ?>" class="<?php echo esc_attr(join( ' ', $wrapper_classes ))  ?>">
    <?php amotos_template_car_map_search('col-xl-5'); ?>
	<div class="col-scroll-vertical col-xl-7">
		<div class="col-scroll-vertical-inner">
            <?php amotos_template_car_search_form($atts,$css_class_field,$css_class_half_field,filter_var($show_status_tab,FILTER_VALIDATE_BOOLEAN)) ?>
			<div class="car-result-wrap">
				<div class="list-car-result-ajax ">
					<?php
                    $_atts =  [
                        'item_amount' => ( $item_amount > 0 ) ? $item_amount : - 1
                    ];
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
                    $query_args = apply_filters('amotos_shortcodes_car_search_map_query_args',$query_args);
                    $data_vertical              = new WP_Query( $query_args );
					$total_post                 = $data_vertical->found_posts;
					$custom_car_image_size = '370x220';
					$car_item_class        = array( 'car-item amotos-item-wrap mg-bottom-20' );
					?>
					<div class="title-result">
						<h2 class="uppercase">
							<span class="number-result"><?php echo esc_html( $total_post ) ?></span>
							<span class="text-result">
                                <?php echo esc_html__('Vehicles','auto-moto-stock'); ?>
                            </span>
							<span class="text-no-result">
	                            <?php esc_html_e( ' No vehicle found', 'auto-moto-stock' ) ?>
                            </span>
						</h2>
					</div>
					<div class="amotos-car clearfix car-grid car-vertical-map-listing col-gap-20 <?php echo esc_attr($car_col_class)?>">
						<?php if ( $data_vertical->have_posts() ) :
							while ( $data_vertical->have_posts() ): $data_vertical->the_post(); ?>
								<?php amotos_get_template( 'content-car.php', array(
									'custom_car_image_size' => $custom_car_image_size,
									'car_item_class'        => $car_item_class,
								) ); ?>
							<?php endwhile;
						endif; ?>
					</div>
					<div class="car-search-map-paging-wrap">
						<?php $max_num_pages = $data_vertical->max_num_pages;
						set_query_var( 'paged', $request_paged );
						amotos_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
						?>
					</div>
				</div>
			</div>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</div>