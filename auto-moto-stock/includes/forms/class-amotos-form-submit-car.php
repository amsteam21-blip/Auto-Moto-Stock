<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'AMOTOS_Form_Submit_Car' ) ) {
	/**
	 * Class AMOTOS_Form_Submit_Car
	 */
	class AMOTOS_Form_Submit_Car extends AMOTOS_Form {
		public $form_name = 'submit-car';
		protected $car_id;
		protected static $_instance = null;

		/**
		 * Main Instance
		 * @return null|AMOTOS_Form_Submit_Car
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'process' ) );
			$this->steps = (array) apply_filters( 'submit_car_steps', array(
				'submit' => array(
					'name'     => __( 'Submit Details', 'auto-moto-stock' ),
					'view'     => array( $this, 'submit' ),
					'handler'  => array( $this, 'submit_handler' ),
					'priority' => 10
				),
				'done'   => array(
					'name'     => __( 'Done', 'auto-moto-stock' ),
					'view'     => array( $this, 'done' ),
					'priority' => 20
				)
			) );

			uasort( $this->steps, array( $this, 'sort_by_priority' ) );

			if ( isset( $_POST['step'] ) ) {
				$this->step = is_numeric( amotos_clean( wp_unslash( $_POST['step'] ) ) ) ? max( absint( amotos_clean( wp_unslash( $_POST['step'] ) ) ), 0 ) : array_search( amotos_clean( wp_unslash( $_POST['step'] ) ), array_keys( $this->steps ) );
			} elseif ( ! empty( $_GET['step'] ) ) {
				$this->step = is_numeric( amotos_clean( wp_unslash( $_GET['step'] ) ) ) ? max( absint( amotos_clean( wp_unslash( $_GET['step'] ) ) ), 0 ) : array_search( amotos_clean( wp_unslash( $_GET['step'] ) ), array_keys( $this->steps ) );
			}

			$this->car_id = ! empty( $_REQUEST['car_id'] ) ? absint( amotos_clean( wp_unslash( $_REQUEST['car_id'] ) ) ) : 0;
			$amotos_car      = new AMOTOS_Car();
			if ( ! $amotos_car->user_can_edit_car( $this->car_id ) ) {
				$this->car_id = 0;
			}
		}

		/**
		 * Get the submitted vehicle ID
		 * @return int
		 */
		public function get_car_id() {
			return absint( $this->car_id );
		}

		/**
		 * Submit step
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

			amotos_get_template( 'car/car-submit.php', array(
				'form'               => $this->form_name,
				'car_id'             => $this->get_car_id(),
				'action'             => $this->get_action(),
				'step'               => $this->get_step(),
				'submit_button_text' => apply_filters( 'submit_car_form_submit_button_text', esc_html__( 'Submit Vehicle', 'auto-moto-stock' ) )
			) );
		}

		/**
		 * Submit handler
		 */
		public function submit_handler() {
			$submit_action = isset( $_POST['car_form'] ) ? amotos_clean( wp_unslash( $_POST['car_form'] ) ) : '';
			if ( $submit_action === '' ) {
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
				$paid_submission_type = amotos_get_option( 'paid_submission_type', 'no' );
				$payment_page_link    = amotos_get_permalink( 'payment' );
				wp_get_current_user();
				if ( wp_verify_nonce( amotos_clean( wp_unslash( $_POST['amotos_submit_car_nonce_field'] ) ), 'amotos_submit_car_action' ) ) {
					$car_id       = apply_filters( 'amotos_submit_car', array() );
					$this->car_id = $car_id;
					if ( $paid_submission_type == 'per_listing' ) {
						$price_per_listing = amotos_get_option( 'price_per_listing', 0 );
						if ( $price_per_listing > 0 && ! empty( $payment_page_link ) && $submit_action != 'edit-car' ) {
							$return_link = add_query_arg( array( 'car_id' => $car_id ), $payment_page_link );
							wp_safe_redirect( esc_url_raw( $return_link ) );
							exit;
						}
					}
					// Successful, show next step
					$this->step ++;
				}
			} catch ( Exception $e ) {
				echo '<div class="amotos-error">' . esc_html($e->getMessage())  . '</div>';

				return;
			}
		}

		/**
		 * Done Step
		 */
		public function done() {
			do_action( 'amotos_car_submitted', $this->car_id );
			global $current_user;
			wp_get_current_user();
			$user_email  = $current_user->user_email;
			$admin_email = get_bloginfo( 'admin_email' );
			$args        = array(
				'listing_title' => get_the_title( $this->car_id ),
				'listing_id'    => $this->car_id
			);
			amotos_send_email( $user_email, 'mail_new_submission_listing', $args );
			amotos_send_email( $admin_email, 'admin_mail_new_submission_listing', $args );

			$my_cars_page_link = amotos_get_permalink( 'my_cars' );
			$return_link             = add_query_arg( array( 'new_id' => $this->car_id ), $my_cars_page_link );
			wp_safe_redirect( esc_url_raw ( $return_link ) );
			exit;
		}
	}
}