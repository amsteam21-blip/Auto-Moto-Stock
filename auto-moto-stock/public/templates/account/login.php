<?php
/**
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$redirect = 'my_profile';
extract( shortcode_atts( array(
	'redirect' => 'my_profile'
), $atts ) );
$redirect_url = amotos_get_permalink( 'my_profile' );
if ( $redirect != 'my_profile' ) {
	$redirect_url = '';
}
$rememberId = uniqid('remember_');
?>
<div class="amotos__account-login-wrap amotos-login-wrap">
	<form class="amotos-login needs-validation" novalidate>
        <div class="amotos_messages message"></div>
		<div class="form-group control-username">
            <label class="sr-only"><?php echo esc_html__( 'Username or email address', 'auto-moto-stock' ); ?></label>
            <input required name="user_login" class="form-control login_user_login"
                   placeholder="<?php echo esc_attr__( 'Username or email address', 'auto-moto-stock' ); ?>"
                   type="text"/>
		</div>
		<div class="form-group control-password">
            <label class="sr-only"><?php echo esc_html__( 'Password', 'auto-moto-stock' ); ?></label>
            <div class="input-group">
                <input required name="user_password" class="form-control amotos__password"
                       placeholder="<?php echo esc_attr__( 'Password', 'auto-moto-stock' ); ?>" type="password"/>
                <div class="input-group-append amotos__show-password">
                    <div class="input-group-text"><i class="fa fa-eye"></i></div>
                </div>
            </div>
		</div>
		<?php
		/**
		 * Fires following the 'Password' field in the login form.
		 *
		 * @since 2.1.0
		 */
		do_action( 'login_form' );
		?>
        <div class="form-group d-flex justify-content-between">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="<?php echo esc_attr($rememberId)?>">
                <label class="form-check-label" for="<?php echo esc_attr($rememberId)?>"><?php echo esc_html__( 'Remember me', 'auto-moto-stock' ); ?></label>
            </div>
            <a href="javascript:void(0)" class="amotos-reset-password"><?php echo esc_html__( 'Forgot password?', 'auto-moto-stock' ) ?></a>
        </div>
        <button type="submit" data-redirect-url="<?php echo esc_url( $redirect_url ); ?>"
                class="amotos-login-button btn btn-primary btn-block"><?php esc_html_e( 'Login', 'auto-moto-stock' ); ?></button>

		<input type="hidden" name="amotos_security_login"
		       value="<?php echo esc_attr(wp_create_nonce( 'amotos_login_ajax_nonce' )); ?>"/>
		<input type="hidden" name="action" value="amotos_login_ajax">
	</form>
</div>
<?php return amotos_get_template_html( 'account/reset-password.php' ); ?>