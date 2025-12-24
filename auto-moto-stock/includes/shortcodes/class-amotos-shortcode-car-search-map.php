<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Search_Map')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Search_Map
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'search_map');
			return amotos_get_template_html('shortcodes/car-search-map/car-search-map.php', array('atts' => $atts));
		}
	}
}