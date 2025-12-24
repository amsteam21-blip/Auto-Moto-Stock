<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Search')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Search
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			$search_styles = isset($atts['search_styles']) ? $atts['search_styles'] : 'style-default';
			$map_search_enable = isset($atts['map_search_enable']) ? $atts['map_search_enable'] : '';

			if ($search_styles === 'style-vertical' || $search_styles === 'style-absolute') {
				$map_search_enable='true';
			}

			if ($map_search_enable == 'true') {
				wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'search_js_map');
			} else {
				wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'search_js');
			}
			return amotos_get_template_html('shortcodes/car-search/car-search.php', array('atts' => $atts));
		}
	}
}