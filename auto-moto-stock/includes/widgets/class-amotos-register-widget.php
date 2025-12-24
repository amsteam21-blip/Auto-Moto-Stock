<?php
/**
 * WooCommerce Widget Functions
 *
 * Widget related functions and widget registration.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	WooCommerce/Functions
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Register_Widgets')) {
	class AMOTOS_Register_Widgets
	{
		/**
		 * Construct
		 */
		public function __construct()
		{
			require_once AMOTOS_PLUGIN_DIR . 'includes/abstracts/abstract-amotos-widget.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/abstracts/abstract-amotos-widget-acf.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-login-menu.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-my-package.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-loan-calculator.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-top-staff.php';
			//require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-related-cars.php';
			//require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-featured-cars.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-search-form.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/widgets/class-amotos-widget-listing-car-taxonomy.php';
		}

		/**
		 * Register Widgets.
		 */
		public function register_widgets()
		{
			register_widget('AMOTOS_Widget_Login_Menu');
			register_widget('AMOTOS_Widget_My_Package');
			register_widget('AMOTOS_Widget_Loan_Calculator');
			register_widget('AMOTOS_Widget_Top_Staff');
			//register_widget('AMOTOS_Widget_Related_Cars');
			//register_widget('AMOTOS_Widget_Featured_Cars');
			register_widget('AMOTOS_Widget_Search_Form');
			register_widget('AMOTOS_Widget_Listing_Car_Taxonomy');
		}
	}
}