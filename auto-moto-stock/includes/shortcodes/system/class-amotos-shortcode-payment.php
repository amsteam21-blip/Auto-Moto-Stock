<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Shortcode_Payment' ) ) {
	/**
	 * AMOTOS_Shortcode_Payment class.
	 */
	class AMOTOS_Shortcode_Payment {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_shortcode( 'amotos_payment', array( $this, 'payment_shortcode' ) );
			add_shortcode( 'amotos_payment_completed', array( $this, 'payment_completed_shortcode' ) );
		}

		/**
		 * Payment shortcode
		 */
		public function payment_shortcode() {
			return amotos_get_template_html( 'payment/payment.php' );
		}

		/**
		 * Payment completed shortcode
		 */
		public function payment_completed_shortcode() {
			return amotos_get_template_html( 'payment/payment-completed.php' );
		}
	}
}
new AMOTOS_Shortcode_Payment();