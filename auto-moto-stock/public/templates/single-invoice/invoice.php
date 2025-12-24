<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
if(!is_user_logged_in()){
	return amotos_get_template_html('global/access-denied.php',array('type'=>'not_login'));

	return;
}
$invoice_id = get_the_ID();
$amotos_invoice = new AMOTOS_Invoice();
$invoice_meta = $amotos_invoice->get_invoice_meta($invoice_id);
$invoice_date = $invoice_meta['invoice_purchase_date'];
$user_id=$invoice_meta['invoice_user_id'];
global $current_user;
wp_get_current_user();
if($user_id!=$current_user->ID){
	esc_html_e('You can\'t view this invoice','auto-moto-stock');
	
	return;
}
$manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $user_id);
$manager_status = get_post_status($manager_id);
if ($manager_status == 'publish') {
	$manager_email = get_post_meta( $manager_id, AMOTOS_METABOX_PREFIX . 'manager_email', true );
	$manager_name = get_the_title($manager_id);
}
else
{
	$user_firstname = get_the_author_meta('first_name', $user_id);
	$user_lastname = get_the_author_meta('last_name', $user_id);
	$user_email = get_the_author_meta('user_email', $user_id);
	if(empty($user_firstname)&& empty($user_lastname))
	{
		$manager_name=get_the_author_meta('user_login', $user_id);
	}
	else
	{
		$manager_name =$user_firstname.' '.$user_lastname;
	}
	$manager_email = $user_email;
}
$print_logo = amotos_get_option( 'print_logo', '' );
$attach_id = '';
if(is_array( $print_logo ) && count( $print_logo ) > 0) {
	$attach_id = $print_logo['id'];
}
$image_size = amotos_get_option( 'print_logo_size','200x100' );
$image_src  = '';
$width      = '';
$height     = '';
if($attach_id) {
	if ( preg_match( '/\d+x\d+/', $image_size ) ) {
		$image_sizes = explode( 'x', $image_size );
		$image_src  = amotos_image_resize_id( $attach_id, $image_sizes[0], $image_sizes[1], true );
	} else {
		if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
			$image_size = 'full';
		}
		$image_src = wp_get_attachment_image_src( $attach_id, $image_size );
		if ( $image_src && ! empty( $image_src[0] ) ) {
			$image_src = $image_src[0];
		}
	}
	if(!empty( $image_src )) {
		list( $width, $height ) = getimagesize( $image_src );
	}
}

$page_name = get_bloginfo( 'name', '' );
$company_address = amotos_get_option( 'company_address', '' );
$company_name = amotos_get_option( 'company_name', '' );
$company_phone = amotos_get_option( 'company_phone', '' );
$item_name = get_the_title($invoice_meta['invoice_item_id']);
$payment_type = AMOTOS_Invoice::get_invoice_payment_type($invoice_meta['invoice_payment_type']);
$payment_method = AMOTOS_Invoice::get_invoice_payment_method($invoice_meta['invoice_payment_method']);
$total_price = amotos_get_format_money( $invoice_meta['invoice_item_price'] );
?>
<div class="single-invoice-wrap">
    <div class="amotos__single-invoice-header">
        <div class="home-page-info">
            <?php if(!empty( $image_src )): ?>
                <img src="<?php echo esc_url( $image_src ) ?>" alt="<?php echo esc_attr( $page_name ) ?>"
                     width="<?php echo esc_attr( $width ) ?>" height="<?php echo esc_attr( $height ) ?>">
            <?php endif; ?>
        </div>
        <div class="invoice-info">
            <p class="invoice-id">
                <span><?php esc_html_e( 'Invoice ID: ', 'auto-moto-stock' ); ?></span>
                <?php echo esc_html( $invoice_id ); ?>
            </p>
            <p class="invoice-date">
                <span><?php esc_html_e( 'Date: ', 'auto-moto-stock' ); ?></span>
                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($invoice_date))); ?>
            </p>
        </div>
    </div>
	<!-- Begin Manager Info -->
	<div class="amotos__single-invoice-manager manager-info">
		<div class="manager-main-info">
			<p><?php esc_html_e( 'To:', 'auto-moto-stock' ) ?></p>
			<?php if(!empty( $manager_name )): ?>
				<div class="full-name">
					<span><?php esc_html_e( 'Full name: ', 'auto-moto-stock' ) ?></span>
					<?php echo esc_html( $manager_name ); ?>
				</div>
			<?php endif; ?>
			<?php if(!empty( $manager_email )): ?>
				<div class="manager-email">
					<span><?php esc_html_e( 'Email: ', 'auto-moto-stock' ) ?></span>
					<?php echo esc_html( $manager_email ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="manager-company-info">
			<p><?php esc_html_e( 'From:', 'auto-moto-stock' ) ?></p>
			<?php if(!empty( $company_name )): ?>
				<div class="company-name">
					<span><?php esc_html_e( 'Company Name: ', 'auto-moto-stock' ) ?></span>
					<?php echo esc_html( $company_name ); ?>
				</div>
			<?php endif; ?>
			<?php if(!empty( $company_address )): ?>
				<div class="company-address">
					<span><?php esc_html_e( 'Company Address: ', 'auto-moto-stock' ) ?></span>
					<?php echo esc_html( $company_address ); ?>
				</div>
			<?php endif; ?>
			<?php if(!empty( $company_phone )): ?>
				<div class="company-phone">
					<span><?php esc_html_e( 'Phone: ', 'auto-moto-stock' ) ?></span>
					<?php echo esc_html( $company_phone ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<!-- End Manager Info -->
	<div class="amotos__single-invoice-billing billing-info">
		<table>
			<tbody>
			<tr>
				<th><?php esc_html_e( 'Item Name:', 'auto-moto-stock' ); ?></th>
				<td><?php echo esc_html( $item_name ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Payment Type:', 'auto-moto-stock' ); ?></th>
				<td><?php echo esc_html( $payment_type ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Payment Method:', 'auto-moto-stock' ); ?></th>
				<td><?php echo esc_html( $payment_method ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Total Price:', 'auto-moto-stock' ); ?></th>
				<td><?php echo wp_kses_post( $total_price ); ?></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="amotos__single-invoice-action single-invoice-action">
		<?php if(amotos_get_option('enable_print_invoice','1')=='1'):?>
		<a href="javascript:void(0)" id="invoice-print"
           data-toggle="tooltip"
		   title="<?php esc_attr_e( 'Print', 'auto-moto-stock' ); ?>"
		   data-invoice-id="<?php echo esc_attr( $invoice_id ); ?>"
           data-nonce="<?php echo esc_attr(wp_create_nonce('amotos_print_invoice'))?>"
		   data-ajax-url="<?php echo esc_url(AMOTOS_AJAX_URL) ; ?>">
			<i class="fa fa-print"></i>
		</a>
		<?php endif;?>
		<a href="<?php echo esc_url( amotos_get_permalink( 'my_invoices' ) ); ?>" data-toggle="tooltip" title="<?php esc_attr_e( 'Back to My Invoices', 'auto-moto-stock' ) ?>">
			<i class="fa fa-reply-all"></i>
		</a>
	</div>
</div>