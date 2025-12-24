<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Type')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Type
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('shortcodes/car-type/car-type.php', array('atts' => $atts));
		}
	}
}