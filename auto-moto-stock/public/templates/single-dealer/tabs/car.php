<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $dealer_id
 */

$manager_ids_arr = amotos_dealer_get_manager_ids($dealer_id);
$manager_ids  = join( ',', $manager_ids_arr['manager_ids'] );
$author_ids = join( ',', $manager_ids_arr['manager_user_ids'] );

$layout_style = amotos_get_option( 'car_of_dealer_layout_style', 'car-grid' );
$item_amount = amotos_get_option( 'car_of_dealer_items_amount', 6 );
$image_size   = amotos_get_option( 'car_of_dealer_image_size', amotos_get_loop_car_image_size_default() );
$show_paging  = amotos_get_option( 'car_of_dealer_show_paging', array() );

$column_lg = amotos_get_option( 'car_of_dealer_column_lg', '3' );
$column_md = amotos_get_option( 'car_of_dealer_column_md', '3' );
$column_sm = amotos_get_option( 'car_of_dealer_column_sm', '2' );
$column_xs = amotos_get_option( 'car_of_dealer_column_xs', '1' );
$column_mb = amotos_get_option( 'car_of_dealer_column_mb', '1' );

$columns_gap = amotos_get_option( 'car_of_dealer_columns_gap', 'col-gap-30' );

if ( ! is_array( $show_paging ) ) {
    $show_paging = array();
}

if ( in_array( "show_paging_car_of_dealer", $show_paging ) ) {
    $show_paging = 'true';
} else {
    $show_paging  = '';
    $item_amount = -1;
}
return amotos_do_shortcode( 'amotos_car', array(
    'layout_style' => $layout_style,
    'item_amount'  => $item_amount,
    'columns'      => $column_lg,
    'items_md'     => $column_md,
    'items_sm'     => $column_sm,
    'items_xs'     => $column_xs,
    'items_mb'     => $column_mb,
    'image_size'   => $image_size,
    'columns_gap'  => $columns_gap,
    'show_paging'  => $show_paging,
    'author_id'    => $author_ids,
    'manager_id'   => $manager_ids
) );