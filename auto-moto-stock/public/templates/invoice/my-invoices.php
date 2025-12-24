<?php
/**
 * @var $invoices
 * @var $max_num_pages
 * @var $start_date
 * @var $end_date
 * @var $invoice_type
 * @var $invoice_status
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! is_user_logged_in() ) {
	return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

	return;
}
$allow_submit = amotos_allow_submit();
if ( ! $allow_submit ) {
	return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_permission' ) );

	return;
}
wp_enqueue_script( 'moment' );
wp_enqueue_script( 'bootstrap-datetimepicker' );
wp_enqueue_script( 'jquery-ui-datepicker' );

$amotos_date_language = esc_html( amotos_get_option( 'date_language', 'en-GB' ) );

if ( ! empty( $amotos_date_language ) ) {
	wp_enqueue_script( "datepicker-" . $amotos_date_language, AMOTOS_PLUGIN_URL . 'public/assets/packages/i18n/datepicker-' . $amotos_date_language . '.js', array( 'jquery' ), '1.0', true );
}

if ( function_exists( 'icl_translate' ) ) {
	if ( ICL_LANGUAGE_CODE != 'en' ) {
		wp_enqueue_script( "datepicker-" . ICL_LANGUAGE_CODE, AMOTOS_PLUGIN_URL . 'public/assets/js/i18n/datepicker-' . ICL_LANGUAGE_CODE . '.js', array( 'jquery' ), '1.0', true );
	}
	$amotos_date_language = ICL_LANGUAGE_CODE;
}
$my_invoices_columns = apply_filters( 'amotos_my_invoices_columns', array(
	'id'        => esc_html__( 'Order ID', 'auto-moto-stock' ),
	'date'      => esc_html__( 'Purchase Date', 'auto-moto-stock' ),
	'type'      => esc_html__( 'Type', 'auto-moto-stock' ),
	'item_name' => esc_html__( 'Item Name', 'auto-moto-stock' ),
	'status'    => esc_html__( 'Status', 'auto-moto-stock' ),
	'total'     => esc_html__( 'Total', 'auto-moto-stock' ),
	'view'      => '',
) );
?>
<div class="row amotos-user-dashboard">
	<div class="col-lg-3 amotos-dashboard-sidebar">
		<?php amotos_get_template( 'global/dashboard-menu.php', array( 'cur_menu' => 'my_invoices' ) ); ?>
	</div>
	<div class="col-lg-9 amotos-dashboard-content">
		<div class="card amotos-card amotos-my-invoices">
            <div class="card-header"><h5 class="card-title m-0"><?php echo esc_html__('My Invoices', 'auto-moto-stock'); ?></h5></div>
            <div class="card-body">
				<form method="get" action="<?php echo esc_url(get_page_link()) ; ?>">
					<div class="row">
						<div class="col-lg-2 col-sm-6">
							<div class="form-group">
								<label class="sr-only"
								       for="start_date"><?php esc_html_e( 'Start Date', 'auto-moto-stock' ); ?></label>
								<input type="text" id="start_date" value="<?php echo esc_attr( $start_date ); ?>"
								       name="start_date"
								       placeholder="<?php esc_attr_e( 'Start Date', 'auto-moto-stock' ); ?>"
								       class="form-control input_date">
							</div>
						</div>
						<div class="col-lg-2 col-sm-6">
							<div class="form-group">
								<label class="sr-only"
								       for="end_date"><?php esc_html_e( 'End Date', 'auto-moto-stock' ); ?></label>
								<input type="text" id="end_date" value="<?php echo esc_attr( $end_date ); ?>"
								       name="end_date"
								       placeholder="<?php esc_attr_e( 'End Date', 'auto-moto-stock' ); ?>"
								       class="form-control input_date">
							</div>
						</div>
						<div class="col-lg-3 col-sm-6">
							<div class="form-group">
								<label class="sr-only"
								       for="invoice_type"><?php esc_html_e( 'Invoice Type', 'auto-moto-stock' ); ?></label>
								<select class="selectpicker form-control" id="invoice_type" name="invoice_type">
									<option
											value="" <?php if ( $invoice_type == '' )
										echo ' selected' ?>><?php esc_html_e( 'All Invoice Type', 'auto-moto-stock' ); ?></option>
									<option
											value="Package" <?php if ( $invoice_type == 'Package' )
										echo ' selected' ?>><?php esc_html_e( 'Package', 'auto-moto-stock' ); ?></option>
									<option
											value="Listing" <?php if ( $invoice_type == 'Listing' )
										echo ' selected' ?>><?php esc_html_e( 'Listing', 'auto-moto-stock' ); ?></option>
									<option
											value="Upgrade_To_Featured"<?php if ( $invoice_type == 'Upgrade_To_Featured' )
										echo ' selected' ?>><?php esc_html_e( 'Upgrade to Featured', 'auto-moto-stock' ); ?></option>
									<option
											value="Listing_With_Featured"<?php if ( $invoice_type == 'Listing_With_Featured' )
										echo ' selected' ?>><?php esc_html_e( 'Listing with Featured', 'auto-moto-stock' ); ?></option>
								</select>
							</div>
						</div>
						<div class="col-lg-3 col-sm-6">
							<div class="form-group">
								<label class="sr-only"
								       for="invoice_status"><?php esc_html_e( 'Payment Status', 'auto-moto-stock' ); ?></label>
								<select class="selectpicker form-control" id="invoice_status" name="invoice_status">
									<option
											value="" <?php if ( $invoice_status == '' )
										echo ' selected' ?>><?php esc_html_e( 'All Payment Status', 'auto-moto-stock' ); ?></option>
									<option
											value="1" <?php if ( $invoice_status == '1' )
										echo ' selected' ?>><?php esc_html_e( 'Paid', 'auto-moto-stock' ); ?></option>
									<option
											value="0" <?php if ( $invoice_status == '0' )
										echo ' selected' ?>><?php esc_html_e( 'Not Paid', 'auto-moto-stock' ); ?></option>
								</select>
							</div>
						</div>
						<div class="col-lg-2 col-sm-6">
							<div class="form-group">
								<input id="search_invoice" type="submit" class="btn btn-default d-inline-block"
								       value="<?php esc_attr_e( 'Search', 'auto-moto-stock' ); ?>">
							</div>
						</div>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<?php foreach ( $my_invoices_columns as $key => $column ) : ?>
									<th class="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $column ); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php if ( ! $invoices ) : ?>
								<tr>
									<td colspan="7"
									    data-title="<?php esc_attr_e( 'Results', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'You don\'t have any invoices listed.', 'auto-moto-stock' ); ?></td>
								</tr>
							<?php else : ?>
								<?php foreach ( $invoices as $invoice ) :
									$amotos_invoice = new AMOTOS_Invoice();
									$invoice_meta = $amotos_invoice->get_invoice_meta( $invoice->ID );
									?>
									<tr>
										<?php foreach ( $my_invoices_columns as $key => $column ) : ?>
											<td class="<?php echo esc_attr( $key ); ?>"
											    data-title="<?php echo esc_attr( $column ); ?>">
												<?php if ( 'id' === $key ): ?>
													<a href="<?php echo esc_url(get_permalink( $invoice->ID )) ; ?>"><?php echo esc_html( $invoice->ID ); ?></a>
												<?php
												elseif ( 'date' === $key ) :
													echo esc_html(date_i18n( get_option( 'date_format' ), strtotime( $invoice->post_date ) )) ;
												elseif ( 'type' === $key ):
													echo esc_html(AMOTOS_Invoice::get_invoice_payment_type( $invoice_meta['invoice_payment_type'] )) ;
												elseif ( 'item_name' === $key ):
													$item_name = get_the_title( $invoice_meta['invoice_item_id'] );
													echo esc_html( $item_name );
												elseif ( 'status' === $key ):
													$invoice_status = get_post_meta( $invoice->ID, AMOTOS_METABOX_PREFIX . 'invoice_payment_status', true );
													if ( $invoice_status == 1 ) {
														esc_html_e( 'Paid', 'auto-moto-stock' );
													} else {
														esc_html_e( 'Not Paid', 'auto-moto-stock' );
													}
												elseif ( 'total' === $key ):
													echo wp_kses_post(amotos_get_format_money( $invoice_meta['invoice_item_price'] )) ;
													do_action( 'amotos_my_invoices_item_price', $invoice_meta );
												elseif ( 'view' === $key ):?>
													<a class="btn-action" data-toggle="tooltip"
													   data-placement="bottom"
													   title="<?php esc_attr_e( 'Print Invoice', 'auto-moto-stock' ); ?>"
													   href="<?php echo esc_url(get_permalink( $invoice->ID )) ; ?>"><i
																class="fa fa-print"></i></a>
												<?php endif; ?>
											</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<?php amotos_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
				<script>
					jQuery(document).ready(function ($) {
						if ($('.input_date').length > 0) {
							$(".input_date").datepicker(["<?php echo esc_js( $amotos_date_language ); ?>"]);
						}
					});
				</script>
			</div>
		</div>
	</div>
</div>