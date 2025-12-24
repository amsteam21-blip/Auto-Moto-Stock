<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$other_manager_layout_style = amotos_get_option( 'other_manager_layout_style', 'manager-slider' );
$other_staff_item_amount = amotos_get_option( 'other_staff_item_amount', 12 );
$other_manager_image_size   = amotos_get_option( 'other_manager_image_size', '270x340' );
$other_manager_show_paging  = amotos_get_option( 'other_manager_show_paging', array() );

$other_manager_column_lg = amotos_get_option( 'other_manager_column_lg', '4' );
$other_manager_column_md = amotos_get_option( 'other_manager_column_md', '3' );
$other_manager_column_xs = amotos_get_option( 'other_manager_column_sm', '2' );
$other_manager_column_sm = amotos_get_option( 'other_manager_column_xs', '2' );
$other_manager_column_mb = amotos_get_option( 'other_manager_column_mb', '1' );

if ( ! is_array( $other_manager_show_paging ) ) {
	$other_manager_show_paging = array();
}
if ( in_array( "show_paging_other_manager", $other_manager_show_paging ) ) {
	$manager_show_paging = 'true';
} else {
	$manager_show_paging = '';
}

if ( $other_manager_layout_style == 'manager-slider' ) {
	$manager_show_paging = '';
}

$dealer = amotos_get_option( 'manager_dealer', '' );
if ( ! empty( $dealer ) ) {
	$dealer = implode( ",", $dealer );
}
?>
<div class="single-manager-element amotos__single-manager-element manager-other amotos__single-manager-other-manager">
	<div class="amotos-heading">
		<h2><?php esc_html_e( 'Other Staff', 'auto-moto-stock' ); ?></h2>
	</div>
	<?php return amotos_do_shortcode( 'amotos_manager', array(
		'dealer'       => $dealer,
		'layout_style' => $other_manager_layout_style,
		'item_amount'  => $other_staff_item_amount,
		'items'        => $other_manager_column_lg,
		'items_md'     => $other_manager_column_md,
		'items_sm'     => $other_manager_column_sm,
		'items_xs'     => $other_manager_column_xs,
		'items_mb'     => $other_manager_column_mb,
		'image_size'   => $other_manager_image_size,
		'show_paging'  => $manager_show_paging,
		'post_not_in'  => get_the_ID()
	) ) ?>
</div>