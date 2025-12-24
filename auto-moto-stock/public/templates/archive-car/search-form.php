<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$hide_archive_search_fields = amotos_get_option( 'hide_archive_search_fields', array(
	'car_country',
	'car_state',
	'car_neighborhood',
	'car_label',
	'car_doors'
) );
if ( ! is_array( $hide_archive_search_fields ) ) {
	$hide_archive_search_fields = array();
}
$status_enable            = ! in_array( "car_status", $hide_archive_search_fields );
$type_enable              = ! in_array( "car_type", $hide_archive_search_fields );
$keyword_enable           = ! in_array( "keyword", $hide_archive_search_fields );
$title_enable             = ! in_array( "car_title", $hide_archive_search_fields );
$address_enable           = ! in_array( "car_address", $hide_archive_search_fields );
$country_enable           = ! in_array( "car_country", $hide_archive_search_fields );
$state_enable             = ! in_array( "car_state", $hide_archive_search_fields );
$city_enable              = ! in_array( "car_city", $hide_archive_search_fields );
$neighborhood_enable      = ! in_array( "car_neighborhood", $hide_archive_search_fields );
$doors_enable             = ! in_array( "car_doors", $hide_archive_search_fields );
$seats_enable             = ! in_array( "car_seats", $hide_archive_search_fields );
$owners_enable            = ! in_array( "car_owners", $hide_archive_search_fields );
$price_enable             = ! in_array( "car_price", $hide_archive_search_fields );
$mileage_enable           = ! in_array( "car_mileage", $hide_archive_search_fields );
$power_enable             = ! in_array( "car_power", $hide_archive_search_fields );
$volume_enable            = ! in_array( "car_volume", $hide_archive_search_fields );
$label_enable             = ! in_array( "car_label", $hide_archive_search_fields );
$car_identity_enable = ! in_array( "car_identity", $hide_archive_search_fields );
$other_stylings_enable    = ! in_array( "car_styling", $hide_archive_search_fields );
?>
	<div class="amotos-heading-style2">
		<h2><?php esc_html_e( 'Search Vehicle', 'auto-moto-stock' ) ?></h2>
	</div>
<?php
$car_price_field_layout = amotos_get_option( 'archive_search_price_field_layout', '0' );
$car_mileage_field_layout  = amotos_get_option( 'archive_search_mileage_field_layout', '0' );
$car_power_field_layout  = amotos_get_option( 'archive_search_power_field_layout', '0' );
$car_volume_field_layout  = amotos_get_option( 'archive_search_volume_field_layout', '0' );
$shortcode_attr              = array(
	'layout'                   => 'tab',
	'column'                   => 3,
	'color_scheme'             => 'color-dark',
	'status_enable'            => $status_enable ? 'true' : 'false',
	'type_enable'              => $type_enable ? 'true' : 'false',
	'title_enable'             => $title_enable ? 'true' : 'false',
	'keyword_enable'           => $keyword_enable ? 'true' : 'false',
	'address_enable'           => $address_enable ? 'true' : 'false',
	'country_enable'           => $country_enable ? 'true' : 'false',
	'state_enable'             => $state_enable ? 'true' : 'false',
	'city_enable'              => $city_enable ? 'true' : 'false',
	'neighborhood_enable'      => $neighborhood_enable ? 'true' : 'false',
	'doors_enable'             => $doors_enable ? 'true' : 'false',
	'seats_enable'             => $seats_enable ? 'true' : 'false',
	'owners_enable'            => $owners_enable ? 'true' : 'false',
	'price_enable'             => $price_enable ? 'true' : 'false',
	'price_is_slider'          => ( $car_price_field_layout == '1' ) ? 'true' : 'false',
	'mileage_enable'           => $mileage_enable ? 'true' : 'false',
	'mileage_is_slider'        => ( $car_mileage_field_layout == '1' ) ? 'true' : 'false',
	'power_enable'             => $power_enable ? 'true' : 'false',
	'power_is_slider'          => ( $car_power_field_layout == '1' ) ? 'true' : 'false',
	'volume_enable'             => $volume_enable ? 'true' : 'false',
	'volume_is_slider'          => ( $car_volume_field_layout == '1' ) ? 'true' : 'false',
	'label_enable'             => $label_enable ? 'true' : 'false',
	'car_identity_enable'      => $car_identity_enable ? 'true' : 'false',
	'other_stylings_enable'    => $other_stylings_enable ? 'true' : 'false'
);
$additional_fields           = amotos_get_search_additional_fields();
foreach ( $additional_fields as $k => $v ) {
	$shortcode_attr["{$k}_enable"] = ! in_array( $k, $hide_archive_search_fields ) ? "true" : "false";
}
return amotos_do_shortcode( 'amotos_car_advanced_search', $shortcode_attr );