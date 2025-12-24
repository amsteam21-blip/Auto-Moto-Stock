<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post;
?>
<div id="invoice-<?php the_ID(); ?>" <?php post_class('amotos-invoice-single-wrap'); ?>>
<?php
/**
 * amotos_single_invoice_before_summary hook.
 */
do_action( 'amotos_single_invoice_before_summary' );
?>
<?php
/**
 * amotos_single_invoice_summary hook.
 *
 * @hooked single_invoice - 5
 */
do_action( 'amotos_single_invoice_summary' ); ?>
<?php
/**
 * amotos_single_invoice_after_summary hook.
 */
do_action( 'amotos_single_invoice_after_summary' );
?>
</div>