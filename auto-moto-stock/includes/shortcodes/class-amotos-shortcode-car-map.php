<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Map')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Map
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('shortcodes/car-map/car-map.php', array('atts' => $atts));
		}
	}
}