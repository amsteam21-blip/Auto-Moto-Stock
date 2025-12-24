<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'AMOTOS_Forms' ) ) {
	/**
	 * Class AMOTOS_Forms
	 */
	class AMOTOS_Forms {
		/**
		 * If a form was posted, load its class so that it can be processed before display.
		 */
		public function load_posted_form() {
			if ( ! empty( $_POST['car_form'] ) ) {
				$this->load_form_class( amotos_clean( wp_unslash( $_POST['car_form'] ) ) );
			}
		}

		/**
		 * @param $form_name
		 *
		 * @return bool|mixed
		 */
		private function load_form_class( $form_name ) {
			if ( ! class_exists( 'AMOTOS_Form' ) ) {
				include( AMOTOS_PLUGIN_DIR . 'includes/abstracts/abstract-amotos-form.php' );
			}
			$form_class = 'AMOTOS_Form_' . str_replace( '-', '_', $form_name );
			$form_file  = AMOTOS_PLUGIN_DIR . 'includes/forms/class-amotos-form-' . $form_name . '.php';

			if ( class_exists( $form_class ) ) {
				return call_user_func( array( $form_class, 'instance' ) );
			}

			if ( ! file_exists( $form_file ) ) {
				return false;
			}

			if ( ! class_exists( $form_class ) ) {
				include $form_file;
			}

			return call_user_func( array( $form_class, 'instance' ) );
		}

		/**
		 * @param $form_name
		 * @param array $atts
		 *
		 * @return null
		 */
		public function get_form( $form_name, $atts = array() ) {
			if ( $form = $this->load_form_class( $form_name ) ) {
				return $form->output( $atts );
			}

			return null;
		}
	}
}