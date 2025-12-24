<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Slider')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Slider
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');
			return amotos_get_template_html('shortcodes/car-slider/car-slider.php', array('atts' => $atts));
		}
	}
}