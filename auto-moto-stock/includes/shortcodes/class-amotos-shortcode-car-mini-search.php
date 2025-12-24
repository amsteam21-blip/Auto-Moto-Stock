<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Mini_Search')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Mini_Search
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'mini_search_js');
			return amotos_get_template_html('shortcodes/car-mini-search/car-mini-search.php', array('atts' => $atts));
		}
	}
}