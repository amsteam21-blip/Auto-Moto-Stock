<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Dealer')) {
	/**
	 * Class AMOTOS_Shortcode_Dealer
	 */
	class AMOTOS_Shortcode_Dealer
	{
		/**
		 * Dealer shortcode
		 */
		public static function output( $atts )
		{
			return amotos_get_template_html('shortcodes/dealer/dealer.php', array('atts' => $atts));
		}
	}
}