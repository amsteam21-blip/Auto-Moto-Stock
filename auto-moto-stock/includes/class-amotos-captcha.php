<?php

/**
 * Class AMOTOS_Captcha
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('AMOTOS_Captcha')) {
	class AMOTOS_Captcha
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == null) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function render_recaptcha() {
			$enable_captcha = amotos_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				wp_enqueue_script('amotos-google-recaptcha');
				$captcha_site_key = amotos_get_option('captcha_site_key', '');
				?>
				<script type="text/javascript">
					var amotos_widget_ids = [];
					var amotos_captcha_site_key = '<?php echo esc_js($captcha_site_key); ?>';
					/**
					 * reCAPTCHA render
					 */
					var amotos_recaptcha_onload_callback = function() {
						jQuery('.amotos-google-recaptcha').each( function( index, el ) {
							var widget_id = grecaptcha.render( el, {
								'sitekey' : amotos_captcha_site_key
							} );
							amotos_widget_ids.push( widget_id );
						} );
					};
					/**
					 * reCAPTCHA reset
					 */
					var amotos_reset_recaptcha = function() {
						if( typeof amotos_widget_ids != 'undefined' ) {
							var arrayLength = amotos_widget_ids.length;
							for( var i = 0; i < arrayLength; i++ ) {
								grecaptcha.reset( amotos_widget_ids[i] );
							}
						}
					};
				</script>
				<?php
			}
		}

		public function render_recaptcha_wp_login() {
			$enable_captcha = amotos_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				$captcha_site_key = amotos_get_option('captcha_site_key', '');
				$recaptcha_src = esc_url_raw(add_query_arg(array(
					'render' => 'explicit',
					'onload' => 'amotos_recaptcha_onload_callback'
				), 'https://www.google.com/recaptcha/api.js'));
				?>
				<script type="text/javascript">
					var amotos_widget_ids = [];
					var amotos_captcha_site_key = '<?php echo esc_js($captcha_site_key); ?>';
					/**
					 * reCAPTCHA render
					 */
					var amotos_recaptcha_onload_callback = function() {

						for ( var i = 0; i < document.forms.length; i++ ) {
							var form = document.forms[i];
							var captcha_div = form.querySelector( '.amotos-google-recaptcha' );

							var widget_id = grecaptcha.render( captcha_div, {
								'sitekey' : amotos_captcha_site_key
							} );
							amotos_widget_ids.push( widget_id );
						}
					};
					/**
					 * reCAPTCHA reset
					 */
					var amotos_reset_recaptcha = function() {
						if( typeof amotos_widget_ids != 'undefined' ) {
							var arrayLength = amotos_widget_ids.length;
							for( var i = 0; i < arrayLength; i++ ) {
								grecaptcha.reset( amotos_widget_ids[i] );
							}
						}
					};
				</script>
				<script src="<?php echo esc_url( $recaptcha_src ); ?>"
				        async defer>
				</script>
				<?php
			}
		}

		public function verify_recaptcha() {
            $nonce = isset($_POST['amotos_recaptcha_nonce']) ? amotos_clean(wp_unslash($_POST['amotos_recaptcha_nonce'])) : '';
            if (!wp_verify_nonce($nonce,'amotos_recaptcha')) {
                echo wp_json_encode( array(
                    'success' => false,
                    'message' => esc_attr__( 'Captcha Invalid Security', 'auto-moto-stock' )
                ) );
                wp_die();
            }

			if (!$this->verify()) {
				echo wp_json_encode( array(
					'success' => false,
					'message' => esc_attr__( 'Captcha Invalid', 'auto-moto-stock' )
				) );
				wp_die();
			}
		}

		public function verify() {
			if (isset($_POST['g-recaptcha-response'])) {
				$captcha_secret_key = amotos_get_option('captcha_secret_key', '');
				$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $captcha_secret_key ."&response=". amotos_clean(wp_unslash($_POST['g-recaptcha-response'])));
				$response = json_decode($response["body"], true);
				return true == $response["success"];
			}
			return true;
		}

		public function form_recaptcha() {
			$enable_captcha = amotos_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				?>
				<div class="amotos-recaptcha-wrap clearfix">
                    <?php wp_nonce_field('amotos_recaptcha','amotos_recaptcha_nonce') ?>
					<div class="amotos-google-recaptcha"></div>
				</div>
				<?php
			}
		}

		public function verify_recaptcha_wp_login($user, $username = '', $password = '') {
			if ( ! $username ) {
				return $user;
			}

            $nonce = isset($_POST['amotos_recaptcha_nonce']) ? amotos_clean(wp_unslash($_POST['amotos_recaptcha_nonce'])) : '';
            if (!wp_verify_nonce($nonce,'amotos_recaptcha')) {
                return new WP_Error( 'captcha_error',__( '<strong>Error</strong>: Captcha Invalid Security', 'auto-moto-stock' ) );
            }

			if (!$this->verify()) {
				return new WP_Error( 'captcha_error',__( '<strong>Error</strong>: Captcha Invalid', 'auto-moto-stock' ) );
			}
			return $user;
		}

		public function verify_recaptcha_wp_lostpassword($errors) {
            $nonce = isset($_POST['amotos_recaptcha_nonce']) ? amotos_clean(wp_unslash($_POST['amotos_recaptcha_nonce'])) : '';
            if (!wp_verify_nonce($nonce,'amotos_recaptcha')) {
                $errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid Security', 'auto-moto-stock' ) );
            }

			if (!$this->verify()) {
				$errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid', 'auto-moto-stock' ) );
			}
			return $errors;
		}

		function verify_recaptcha_wp_registration( $errors, $sanitized_user_login, $user_email ) {
            $nonce = isset($_POST['amotos_recaptcha_nonce']) ? amotos_clean(wp_unslash($_POST['amotos_recaptcha_nonce'])) : '';
            if (!wp_verify_nonce($nonce,'amotos_recaptcha')) {
                $errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid Security', 'auto-moto-stock' ) );
            }

			if ( ! $this->verify() ) {
				$errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid', 'auto-moto-stock' ) );
			}

			return $errors;
		}
	}
}