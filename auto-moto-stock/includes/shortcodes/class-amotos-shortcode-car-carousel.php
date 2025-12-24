<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Carousel')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Carousel
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{

			return amotos_get_template_html('shortcodes/car-carousel/car-carousel.php', array('atts' => $atts));
		}
	}
}