<?php

/**
 * Fired during plugin activation
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
if (!class_exists('AMOTOS_Activator')) {
	require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-role.php';
	require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-updater.php';
	/**
	 * Fired during plugin activation
	 * Class AMOTOS_Activator
	 */
	class AMOTOS_Activator
	{
		/**
		 * Run when plugin activated
		 */
		public static function activate()
		{
		 	AMOTOS_Role::create_roles();
			self::setup_page();
		 	AMOTOS_Save_Search::create_table_save_search();
		}

		private static function setup_page()
		{
			// Redirect to setup screen for new setup_pages
			if (!get_option('amotos_version')) {
				set_transient('_amotos_activation_redirect', 1, HOUR_IN_SECONDS);
			}
			AMOTOS_Updater::updater();
			update_option('amotos_version', AMOTOS_PLUGIN_VER);
		}
	}
}