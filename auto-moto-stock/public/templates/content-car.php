<?php
/**
 * @var $custom_car_image_size
 * @var $car_item_class
 * @var $car_image_class
 * @var $car_item_content_class
 */

if (!isset($car_item_class)) {
    $car_item_class = array();
}

if (!isset($car_image_class)) {
	$car_image_class = array();
}

if (!isset($car_item_content_class)) {
	$car_item_content_class = array();
}

if (!isset($custom_car_image_size)) {
    $custom_car_image_size = amotos_get_option( 'archive_car_image_size', amotos_get_loop_car_image_size_default() );
}


/**
 * amotos_before_loop_car hook.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
do_action( 'amotos_before_loop_car' );
/**
 * amotos_loop_car hook.
 *
 * @hooked loop_car - 10
 */
do_action( 'amotos_loop_car', $car_item_class, $custom_car_image_size, $car_image_class , $car_item_content_class);
/**
 * amotos_after_loop_car hook.
 */
do_action( 'amotos_after_loop_car' );