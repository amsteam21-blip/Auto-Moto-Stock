<?php

/**
 * Fired during plugin deactivation
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
if (!class_exists('AMOTOS_Deactivator')) {
	require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-role.php';
	require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-schedule.php';
	/**
	 * Fired during plugin deactivation
	 * Class AMOTOS_Deactivator
	 */
	class AMOTOS_Deactivator
	{
		/**
		 * Run when plugin deactivated
		 */
		public static function deactivate()
		{
		 AMOTOS_Role::remove_roles();
		 AMOTOS_Schedule::clear_scheduled_hook();
		}
	}
}