<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Shortcode_Car_Gallery')) {
	/**
	 * Class AMOTOS_Shortcode_Package
	 */
	class AMOTOS_Shortcode_Car_Gallery
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			$filter_style = isset($atts['filter_style']) ? $atts['filter_style'] : 'filter-isotope';

			if ($filter_style == 'filter-isotope') {
				wp_enqueue_script('isotope');
			}

			wp_enqueue_script('imageLoaded');
			wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'car_gallery');

            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');

			return amotos_get_template_html('shortcodes/car-gallery/car-gallery.php', array('atts' => $atts));
		}
	}
}