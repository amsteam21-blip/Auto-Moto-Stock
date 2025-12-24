<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Widgets')) {
	class AMOTOS_Widgets
	{
		public function __construct()
		{
			require_once AMOTOS_PLUGIN_DIR . 'includes/abstracts/abstract-amotos-widget.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/abstracts/abstract-amotos-widget-acf.php';
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