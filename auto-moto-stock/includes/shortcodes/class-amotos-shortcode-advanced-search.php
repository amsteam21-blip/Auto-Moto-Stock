<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Advanced_Search')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Advanced_Search
	{
		public static function output( $atts )
		{
			return amotos_get_template_html('car/advanced-search.php', array('atts' => $atts));
		}
	}
}