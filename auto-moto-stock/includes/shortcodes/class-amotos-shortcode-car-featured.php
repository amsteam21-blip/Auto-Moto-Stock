<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Featured')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Featured
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'car_featured');

			return amotos_get_template_html('shortcodes/car-featured/car-featured.php', array('atts' => $atts));
		}
	}
}