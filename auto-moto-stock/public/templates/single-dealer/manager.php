<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $dealer WP_Term
 */
$wrapper_classes = array(
    'amotos__single-dealer-element',
    'amotos__single-dealer-manager'
);

$wrapper_class = join(' ', $wrapper_classes);

$layout_style = amotos_get_option( 'staff_of_dealer_layout_style', 'manager-slider' );
$item_amount  = amotos_get_option( 'staff_of_dealer_item_amount', 12 );
$image_size   = amotos_get_option( 'staff_of_dealer_image_size', '270x340' );
$show_paging  = amotos_get_option( 'staff_of_dealer_show_paging', array() );

$column_lg = amotos_get_option( 'staff_of_dealer_column_lg', '4' );
$column_md = amotos_get_option( 'staff_of_dealer_column_md', '3' );
$column_sm = amotos_get_option( 'staff_of_dealer_column_sm', '2' );
$column_xs = amotos_get_option( 'staff_of_dealer_column_xs', '2' );
$column_mb = amotos_get_option( 'staff_of_dealer_column_mb', '1' );

if ( ! is_array( $show_paging ) ) {
    $show_paging = array();
}
if ( in_array( "show_paging_other_manager", $show_paging ) ) {
    $manager_show_paging = 'true';
} else {
    $manager_show_paging            = '';
    $item_amount = - 1;
}

if ( $layout_style == 'manager-slider' ) {
    $manager_show_paging = '';
}
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading">
        <h2><?php echo esc_html__( 'Our Staff', 'auto-moto-stock' ); ?></h2>
        <p><?php echo esc_html__( 'We Have Professional Staff', 'auto-moto-stock' ); ?></p>
    </div>
    <?php
    return amotos_do_shortcode( 'amotos_manager', array(
        'dealer'       => $dealer->slug,
        'layout_style' => $layout_style,
        'item_amount'  => $item_amount,
        'items'        => $column_lg,
        'items_md'     => $column_md,
        'items_sm'     => $column_sm,
        'items_xs'     => $column_xs,
        'items_mb'     => $column_mb,
        'image_size'   => $image_size,
        'show_paging'  => $manager_show_paging
    ) );
    ?>
</div>
