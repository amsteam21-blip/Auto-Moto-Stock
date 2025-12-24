<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include_once( AMOTOS_PLUGIN_DIR . 'includes/forms/class-amotos-form-submit-car.php' );
if ( ! class_exists( 'AMOTOS_Form_Edit_Car' ) ) {
	/**
	 * Form Edit Vehicle class.
	 */
	class AMOTOS_Form_Edit_Car extends AMOTOS_Form_Submit_Car {
		public $form_name = 'edit-car';
		protected static $_instance = null;

		/**
		 * Main Instance
		 * @return null|AMOTOS_Form_Edit_Car
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->car_id = ! empty( $_REQUEST['car_id'] ) ? absint( amotos_clean( wp_unslash( $_REQUEST['car_id'] ) ) ) : 0;
			$amotos_car      = new AMOTOS_Car();
			if ( ! $amotos_car->user_can_edit_car( $this->car_id ) ) {
				$this->car_id = 0;
			}
		}

		/**
		 * Output function.
		 */
		public function output( $atts = array() ) {
			$this->submit_handler();
			$this->submit();
		}

		/**
		 * Submit Step
		 */
		public function submit() {
            if (!is_user_logged_in()) {
                return amotos_get_template_html('global/access-denied.php', array('type' => 'not_login'));

                return;
            }

			if (!amotos_is_cap_customer()) {
				return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_permission' ) );

				return;
			}
			if ( empty( $this->car_id ) ) {
				echo esc_html__( 'Invalid listing', 'auto-moto-stock' );

				return;
			}
			amotos_get_template( 'car/car-submit.php', array(
				'form'               => $this->form_name,
				'car_id'             => $this->get_car_id(),
				'action'             => $this->get_action(),
				'step'               => $this->get_step(),
				'submit_button_text' => esc_html__( 'Save changes', 'auto-moto-stock' )
			) );
		}

		/**
		 * Submit handler
		 */
		public function submit_handler() {
			if ( empty( $_POST['car_form'] ) ) {
				return;
			}
			if ( ! is_user_logged_in() ) {
				return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

				return;
			}

			if (!amotos_is_cap_customer()) {
				return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_permission' ) );

				return;
			}

			try {
				if ( wp_verify_nonce( amotos_clean( wp_unslash( $_POST['amotos_submit_car_nonce_field'] ) ), 'amotos_submit_car_action' ) ) {
					$car_id = apply_filters( 'amotos_submit_car', array() );
					if ( $car_id < 1 || is_null( $car_id ) ) {
						echo '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post( __( '<strong>Warning!</strong> Can not edit this vehicle', 'auto-moto-stock' )) . '</div>';

						return;
					}
					$this->car_id = $car_id;
					do_action( 'amotos_car_edited', $this->car_id );
				}
				$post_status = get_post_status( $this->car_id );
				if ( $post_status == 'pending' ) {
					$args = array(
						'listing_title' => get_the_title( $this->car_id ),
						'listing_id'    => $this->car_id
					);
					global $current_user;
					wp_get_current_user();
					$user_email  = $current_user->user_email;
					$admin_email = get_bloginfo( 'admin_email' );
					amotos_send_email( $user_email, 'mail_new_modification_listing', $args );
					amotos_send_email( $admin_email, 'admin_mail_new_modification_listing', $args );
				}
				$my_cars_page_link = amotos_get_permalink( 'my_cars' );
				$return_link             = add_query_arg( array( 'edit_id' => $this->car_id ), $my_cars_page_link );
				wp_safe_redirect( esc_url_raw( $return_link ) );
				exit;

			} catch ( Exception $e ) {
				echo '<div class="amotos-error">' . esc_html($e->getMessage())  . '</div>';

				return;
			}
		}
	}
}