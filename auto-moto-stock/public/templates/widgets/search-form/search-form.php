<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$layout                   = ( ! empty( $instance['layout'] ) ) ? ( $instance['layout'] ) : 'tab';
$status_enable            = ( ! empty( $instance['status_enable'] ) ) ? ( $instance['status_enable'] ) : '0';
$type_enable              = ( ! empty( $instance['type_enable'] ) ) ? ( $instance['type_enable'] ) : '0';
$title_enable             = ( ! empty( $instance['title_enable'] ) ) ? ( $instance['title_enable'] ) : '0';
$address_enable           = ( ! empty( $instance['address_enable'] ) ) ? ( $instance['address_enable'] ) : '0';
$country_enable           = ( ! empty( $instance['country_enable'] ) ) ? ( $instance['country_enable'] ) : '0';
$state_enable             = ( ! empty( $instance['state_enable'] ) ) ? ( $instance['state_enable'] ) : '0';
$city_enable              = ( ! empty( $instance['city_enable'] ) ) ? ( $instance['city_enable'] ) : '0';
$neighborhood_enable      = ( ! empty( $instance['neighborhood_enable'] ) ) ? ( $instance['neighborhood_enable'] ) : '0';
$doors_enable             = ( ! empty( $instance['doors_enable'] ) ) ? ( $instance['doors_enable'] ) : '0';
$seats_enable          = ( ! empty( $instance['seats_enable'] ) ) ? ( $instance['seats_enable'] ) : '0';
$owners_enable         = ( ! empty( $instance['owners_enable'] ) ) ? ( $instance['owners_enable'] ) : '0';
$price_enable             = ( ! empty( $instance['price_enable'] ) ) ? ( $instance['price_enable'] ) : 'false';
$price_is_slider          = ( ! empty( $instance['price_is_slider'] ) ) ? ( $instance['price_is_slider'] ) : '0';
$mileage_enable              = ( ! empty( $instance['mileage_enable'] ) ) ? ( $instance['mileage_enable'] ) : '0';
$mileage_is_slider           = ( ! empty( $instance['mileage_is_slider'] ) ) ? ( $instance['mileage_is_slider'] ) : '0';
$power_enable         = ( ! empty( $instance['power_enable'] ) ) ? ( $instance['power_enable'] ) : '0';
$power_is_slider      = ( ! empty( $instance['power_is_slider'] ) ) ? ( $instance['power_is_slider'] ) : '0';
$volume_enable         = ( ! empty( $instance['volume_enable'] ) ) ? ( $instance['volume_enable'] ) : '0';
$volume_is_slider      = ( ! empty( $instance['volume_is_slider'] ) ) ? ( $instance['volume_is_slider'] ) : '0';
$label_enable             = ( ! empty( $instance['label_enable'] ) ) ? ( $instance['label_enable'] ) : '0';
$car_identity_enable = ( ! empty( $instance['car_identity_enable'] ) ) ? ( $instance['car_identity_enable'] ) : '0';
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo amotos_do_shortcode( 'amotos_car_advanced_search', array(
	'layout'                   => $layout,
	'column'                   => 1,
	'color_scheme'             => "color-dark",
	'status_enable'            => $status_enable == 1 ? 'true' : 'false',
	'type_enable'              => $type_enable == 1 ? 'true' : 'false',
	'title_enable'             => $title_enable == 1 ? 'true' : 'false',
	'address_enable'           => $address_enable == 1 ? 'true' : 'false',
	'country_enable'           => $country_enable == 1 ? 'true' : 'false',
	'state_enable'             => $state_enable == 1 ? 'true' : 'false',
	'city_enable'              => $city_enable == 1 ? 'true' : 'false',
	'neighborhood_enable'      => $neighborhood_enable == 1 ? 'true' : 'false',
	'doors_enable'             => $doors_enable == 1 ? 'true' : 'false',
	'seats_enable'             => $seats_enable == 1 ? 'true' : 'false',
	'owners_enable'            => $owners_enable == 1 ? 'true' : 'false',
	'price_enable'             => $price_enable == 1 ? 'true' : 'false',
	'price_is_slider'          => $price_is_slider == 1 ? 'true' : 'false',
	'mileage_enable'           => $mileage_enable == 1 ? 'true' : 'false',
	'mileage_is_slider'        => $mileage_is_slider == 1 ? 'true' : 'false',
	'power_enable'             => $power_enable == 1 ? 'true' : 'false',
	'power_is_slider'          => $power_is_slider == 1 ? 'true' : 'false',
	'volume_enable'            => $volume_enable == 1 ? 'true' : 'false',
	'volume_is_slider'         => $volume_is_slider == 1 ? 'true' : 'false',
	'label_enable'             => $label_enable == 1 ? 'true' : 'false',
	'car_identity_enable'      => $car_identity_enable == 1 ? 'true' : 'false'
) );