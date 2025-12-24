<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car')) {
	/**
	 * Class AMOTOS_Shortcode_Car
	 */
	class AMOTOS_Shortcode_Car
	{
		/**
		 * Vehicle shortcode
		 */
		public static function output( $atts )
		{
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');
			return amotos_get_template_html('shortcodes/car/car.php', array('atts' => $atts));
		}
	}
}