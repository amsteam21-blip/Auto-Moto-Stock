<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Manager')) {
	/**
	 * Class AMOTOS_Shortcode_Manager
	 */
	class AMOTOS_Shortcode_Manager
	{
		/**
		 * Manager shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('shortcodes/manager/manager.php', array('atts' => $atts));
		}
	}
}