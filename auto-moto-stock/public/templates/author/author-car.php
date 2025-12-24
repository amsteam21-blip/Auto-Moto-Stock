<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wp_query;
$current_author = $wp_query->get_queried_object();
$author_id      = $current_author->ID;
$manager_id       = 0;
$amotos_car   = new AMOTOS_Car();
$total_car = $amotos_car->get_total_cars_by_user( $manager_id, $author_id );
?>
<?php if ( $total_car > 0 ): ?>
	<div class="manager-cars">
		<div class="manager-cars-inner">
			<div class="amotos-heading">
				<h2><?php esc_html_e( 'My Vehicles', 'auto-moto-stock' ); ?>
					<sub>(<?php echo esc_html(amotos_get_format_number( $total_car )) ; ?>)</sub></h2>
			</div>
			<?php return amotos_do_shortcode( 'amotos_car', array(
				'layout_style' => "car-list",
				'item_amount'  => 10,
				'show_paging'  => "true",
				'author_id'    => $author_id,
				'manager_id'   => $manager_id
			) ) ?>
		</div>
	</div>
<?php endif; ?>