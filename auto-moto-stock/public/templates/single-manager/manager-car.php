<?php
/**
 * @var $manager_post_meta_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$manager_id                       = get_the_ID();
$manager_post_meta_data           = get_post_custom( $manager_id );
$car_of_manager_layout_style = amotos_get_option( 'car_of_manager_layout_style', 'car-grid' );
$car_of_manager_items_amount = amotos_get_option( 'car_of_manager_items_amount', 6 );
$car_of_manager_image_size   = amotos_get_option( 'car_of_manager_image_size', '330x180' );
$car_of_manager_show_paging  = amotos_get_option( 'car_of_manager_show_paging', array() );

$car_of_manager_column_lg = amotos_get_option( 'car_of_manager_column_lg', '3' );
$car_of_manager_column_md = amotos_get_option( 'car_of_manager_column_md', '3' );
$car_of_manager_column_sm = amotos_get_option( 'car_of_manager_column_sm', '2' );
$car_of_manager_column_xs = amotos_get_option( 'car_of_manager_column_xs', '1' );
$car_of_manager_column_mb = amotos_get_option( 'car_of_manager_column_mb', '1' );

$custom_car_of_manager_columns_gap = amotos_get_option( 'car_of_manager_columns_gap', 'col-gap-30' );

if ( ! is_array( $car_of_manager_show_paging ) ) {
	$car_of_manager_show_paging = array();
}

if ( in_array( "show_paging_car_of_manager", $car_of_manager_show_paging ) ) {
	$car_of_manager_show_paging = 'true';
} else {
	$car_of_manager_show_paging = '';
}

$manager_user_id = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_user_id' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_user_id' ][0] : '';
$user          = get_user_by( 'id', $manager_user_id );
if ( empty( $user ) ) {
	$manager_user_id = 0;
}
$amotos_car   = new AMOTOS_Car();
$total_car = $amotos_car->get_total_cars_by_user( $manager_id, $manager_user_id );

?>
<?php if ( $total_car > 0 ): ?>
	<div class="single-manager-element manager-cars amotos__single-manager-element amotos__single-manager-car">
		<div class="amotos-heading">
			<h2><?php esc_html_e( 'My Vehicles', 'auto-moto-stock' ); ?>
				<sub>(<?php echo esc_html(amotos_get_format_number( $total_car ) ); ?>)</sub></h2>
		</div>
		<?php return amotos_do_shortcode( 'amotos_car', array(
			'layout_style' => $car_of_manager_layout_style,
			'item_amount'  => $car_of_manager_items_amount,
			'columns'      => $car_of_manager_column_lg,
			'items_md'     => $car_of_manager_column_md,
			'items_sm'     => $car_of_manager_column_sm,
			'items_xs'     => $car_of_manager_column_xs,
			'items_mb'     => $car_of_manager_column_mb,
			'image_size'   => $car_of_manager_image_size,
			'columns_gap'  => $custom_car_of_manager_columns_gap,
			'show_paging'  => $car_of_manager_show_paging,
			'author_id'    => $manager_user_id,
			'manager_id'   => $manager_id
		) ) ?>
	</div>
<?php endif; ?>