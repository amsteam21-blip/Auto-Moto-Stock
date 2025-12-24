<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Role')) {
	/**
	 * Class AMOTOS_Role
	 */
	class AMOTOS_Role
	{
		/**
		 * Create roles and capabilities.
		 */
		public static function create_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			$amotos_customer_capability_type = 'car';
			// Customer role
			add_role( 'amotos_customer', esc_html__( 'AMS Customer', 'auto-moto-stock' ), array(
				'read' 					=> true,
				// Vehicle
				"edit_{$amotos_customer_capability_type}",
				"read_{$amotos_customer_capability_type}",
				"delete_{$amotos_customer_capability_type}",
				"edit_{$amotos_customer_capability_type}s",
				"read_private_{$amotos_customer_capability_type}s",
				"delete_{$amotos_customer_capability_type}s",
				"delete_private_{$amotos_customer_capability_type}s",
				"delete_published_{$amotos_customer_capability_type}s",
				"edit_private_{$amotos_customer_capability_type}s",
				"edit_published_{$amotos_customer_capability_type}s",
			));

			$capabilities = self::get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}

		/**
		 * Get core capabilities
		 * @return array
		 */
		private static function get_core_capabilities() {
			$capabilities = array();

			$capabilities['core'] = array(
				'manage_auto_moto'
			);

			$capability_types = array( 'car', 'manager','package','user_package','invoice','trans_action');

			foreach ( $capability_types as $capability_type ) {

				$capabilities[ $capability_type ] = array(
					// Post type
					"edit_{$capability_type}",
					"read_{$capability_type}",
					"delete_{$capability_type}",
					"edit_{$capability_type}s",
					"edit_others_{$capability_type}s",
					"publish_{$capability_type}s",
					"read_private_{$capability_type}s",
					"delete_{$capability_type}s",
					"delete_private_{$capability_type}s",
					"delete_published_{$capability_type}s",
					"delete_others_{$capability_type}s",
					"edit_private_{$capability_type}s",
					"edit_published_{$capability_type}s",

					// Terms
					"manage_{$capability_type}_terms",
					"edit_{$capability_type}_terms",
					"delete_{$capability_type}_terms",
					"assign_{$capability_type}_terms"
				);
			}

			return $capabilities;
		}
		/**
		 * Remove roles
		 */
		public static function remove_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			$capabilities = self::get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}

			remove_role( 'amotos_customer' );
		}
	}
}