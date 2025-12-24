<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Compare')) {
	/**
	 * Class AMOTOS_Shortcode_Compare
	 */
	class AMOTOS_Shortcode_Compare
	{
		/**
		 * Compare shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('car/compare.php', array('atts' => $atts));
		}
	}
}