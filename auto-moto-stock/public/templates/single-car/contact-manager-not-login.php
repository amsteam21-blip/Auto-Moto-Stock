<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<p class="amotos-account-sign-in"><?php esc_html_e('You must login or register to view contact information!', 'auto-moto-stock'); ?>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
            data-target="#amotos_signin_modal">
        <?php esc_html_e('Login', 'auto-moto-stock'); ?>
    </button>
</p>
