<?php
/**
 * @var $type
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
do_action( 'amotos_access_denied_before', sanitize_title( $type ), $type );
?>
	<div class="amotos-access-denied">
		<div class="amotos-message alert alert-success" role="alert">
			<?php
			switch ( $type ) :
				case 'not_login' :
					?>
					<p class="amotos-account-sign-in"><?php esc_html_e( 'You need login to continue.', 'auto-moto-stock' ); ?>
						<button title="<?php esc_attr_e( 'Login Or Register', 'auto-moto-stock' ); ?>"
						        type="button" class="btn btn-primary btn-sm" data-toggle="modal"
						        data-target="#amotos_signin_modal">
							<?php esc_html_e( 'Login Or Register', 'auto-moto-stock' ); ?>
						</button>
					</p>
					<?php
					break;
				case 'not_permission' :
					echo wp_kses_post( __( '<strong>Access Denied!</strong> You can\'t access this page.', 'auto-moto-stock' ) );
					break;
				case 'not_allow_submit' :
					$enable_submit_car_via_frontend = amotos_get_option( 'enable_submit_car_via_frontend', 1 );
					$user_can_submit                     = amotos_get_option( 'user_can_submit', 1 );
					$is_manager                          = amotos_is_manager();
					if ( $enable_submit_car_via_frontend != 1 ) {
						echo wp_kses_post( __( '<strong>Access Denied!</strong> You can\'t access this page.', 'auto-moto-stock' ) );
					} else {
						if ( $user_can_submit != 1 ) {
							echo wp_kses_post( __( '<strong>Access Denied!</strong> You need to become an manager to access this page.', 'auto-moto-stock' ) );
						}
					}
					break;
				default :
					do_action( 'amotos_access_denied_' . sanitize_title( $type ), $type );
					break;
			endswitch;
			?></div>
		<?php if ( $type == 'not_allow_submit' ): ?>
			<a class="btn btn-primary" href="<?php echo esc_url(amotos_get_permalink( 'my_profile' )) ; ?>"
			   title="<?php esc_attr_e( 'Go to My Profile to become an manager', 'auto-moto-stock' ) ?>"><?php esc_html_e( 'Become an manager', 'auto-moto-stock' ) ?></a>
		<?php endif;
		if ( $type == 'not_permission' ):?>
			<a class="btn btn-primary" href="<?php echo esc_url(amotos_get_permalink( 'my_profile' )) ; ?>"
			   title="<?php esc_attr_e( 'Go to Dashboard', 'auto-moto-stock' ) ?>"><?php esc_html_e( 'My Profile', 'auto-moto-stock' ) ?></a>
		<?php endif; ?>
		<a class="btn btn-default" href="<?php echo esc_url(home_url()) ; ?>"
		   title="<?php esc_attr_e( 'Go to Home Page', 'auto-moto-stock' ) ?>"><?php esc_html_e( 'Home Page', 'auto-moto-stock' ) ?></a>
	</div>
<?php
do_action( 'amotos_access_denied_after', sanitize_title( $type ), $type );