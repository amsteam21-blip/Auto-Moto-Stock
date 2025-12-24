<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://auto-moto-stock.com
 * @since      1.0.0
 *
 * @package    Auto_Moto_Stock
 * @subpackage Auto_Moto_Stock/includes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_i18n')) {
	/**
	 * Define the internationalization functionality
	 * Class AMOTOS_i18n
	 */
	class AMOTOS_i18n
	{
		/**
		 * The domain specified for this plugin.
		 */
		private $domain;

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain()
		{
			load_plugin_textdomain(
				$this->domain,
				false,
				dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
			);
		}

		/**
		 * Set the domain equal to that of the specified domain.
		 */
		public function set_domain($domain)
		{
			$this->domain = $domain;
		}
	}
}