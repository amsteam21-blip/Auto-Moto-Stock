<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$users_can_register = AMOTOS_Login_Register::getInstance()->users_can_register();
?>
<?php if (!$users_can_register): ?>
<div class="modal modal-login fade" id="amotos_signin_modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo esc_html__('Login','auto-moto-stock') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php echo do_shortcode( '[amotos_login redirect="current_page"]' ); ?>
			</div>
		</div>
	</div>
</div>
<?php else: ?>
	<div class="modal modal-login fade" id="amotos_signin_modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<ul class="nav nav-tabs list-inline mb-0">
						<li class="list-inline-item">
							<a class="active" id="amotos_login_modal_tab" href="#login"
							   data-toggle="tab"><?php esc_html_e( 'Login', 'auto-moto-stock' ); ?></a>
						</li>
						<li class="list-inline-item">
							<a id="amotos_register_modal_tab" href="#register"
							   data-toggle="tab"><?php esc_html_e( 'Register', 'auto-moto-stock' ); ?></a>
						</li>
					</ul>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="tab-content ">
						<div class="tab-pane active" id="login">
							<?php echo do_shortcode( '[amotos_login redirect="current_page"]' ); ?>
						</div>
						<div class="tab-pane" id="register">
							<?php echo do_shortcode( '[amotos_register redirect="login_tab"]' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>