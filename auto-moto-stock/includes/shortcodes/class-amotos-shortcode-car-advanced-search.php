<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Advanced_Search')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Advanced_Search
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'advanced_search_js');
			return amotos_get_template_html('shortcodes/car-advanced-search/car-advanced-search.php', array('atts' => $atts));
		}
	}
}