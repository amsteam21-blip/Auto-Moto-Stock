<?php
/**
 * @var $parameters
 * @var $search_query
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade amotos_save_search_modal" id="amotos_save_search_modal" tabindex="-1" role="dialog"
     aria-labelledby="SaveSearchModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mb-0" id="SaveSearchModalLabel"><?php esc_html_e( 'Saved Search', 'auto-moto-stock' ); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info" role="alert">
					<i class="fa fa-info-circle"></i>
					<?php esc_html_e( 'With Saved Search, You will receive an email notification whenever someone posts a new Vehicle that matches your saved search criteria.', 'auto-moto-stock' ); ?>
				</div>
				<form id="amotos_save_search_form" method="post">
					<div class="form-group">
						<label for="amotos_title"><?php esc_html_e( 'Title', 'auto-moto-stock' ); ?></label>
						<input type="text" name="amotos_title" id="amotos_title"
						       placeholder="<?php esc_attr_e( 'Input title', 'auto-moto-stock' ); ?>"
						       class="form-control"
						       value="" aria-describedby="parameters">
						<input type="hidden" name="amotos_params"
						       value="<?php echo esc_attr(base64_encode( $parameters )); ?>">
						<input type="hidden" name="amotos_query"
						       value="<?php echo esc_attr(base64_encode( serialize( $search_query ))); ?>">
						<input type="hidden" name="amotos_url" value="<?php echo esc_url( sanitize_url($_SERVER['REQUEST_URI']) ) ?>">
						<input type="hidden" name="action" value='amotos_save_search_ajax'>
						<input type="hidden" name="amotos_save_search_ajax"
						       value="<?php echo esc_attr(wp_create_nonce( 'amotos_save_search_nonce_field' ))  ?>">
						<small id="parameters" class="form-text text-muted">
                            <?php
                                /* translators: %s: parameters save search. */
                                echo wp_kses_post( sprintf( esc_html__( 'Parameters: %s', 'auto-moto-stock' ), $parameters ) );
                            ?>
                        </small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark btn-default"
				        data-dismiss="modal"><?php esc_html_e( 'Close', 'auto-moto-stock' ); ?></button>
				<button data-ajax-url="<?php echo esc_url( AMOTOS_AJAX_URL ) ?>" id="amotos_save_search"
				        class="btn btn-primary"
				        type="button"><?php esc_html_e( 'Save', 'auto-moto-stock' ); ?></button>
			</div>
		</div>
	</div>
</div>