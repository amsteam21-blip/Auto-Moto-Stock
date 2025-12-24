<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Package')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Package
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('package/package.php', array('atts' => $atts));
		}
	}
}