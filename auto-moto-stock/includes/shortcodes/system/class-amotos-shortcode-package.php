<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Shortcode_Package' ) ) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Package {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_shortcode( 'amotos_package', array( $this, 'package_shortcode' ) );
		}

		/**
		 * Package shortcode
		 */
		public function package_shortcode() {
			return amotos_get_template_html( 'package/package.php' );
		}
	}
}
new AMOTOS_Shortcode_Package();