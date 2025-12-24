<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_NearBy_Places')) {
	/**
	 * Class AMOTOS_Shortcode_NearBy_Places
	 */
	class AMOTOS_Shortcode_NearBy_Places
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'nearby-places');
			return amotos_get_template_html('shortcodes/nearby-places/nearby-places.php', array('atts' => $atts));
		}
	}
}