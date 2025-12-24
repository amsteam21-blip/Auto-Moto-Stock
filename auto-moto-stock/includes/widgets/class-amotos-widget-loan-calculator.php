<?php
/**
 * Created by StockTheme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Widget_Loan_Calculator')) {
	class AMOTOS_Widget_Loan_Calculator extends AMOTOS_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'amotos_widget amotos_widget_loan_calculator';
			$this->widget_description = esc_html__('Show Loan Calculator', 'auto-moto-stock');
			$this->widget_id = 'amotos_widget_loan_calculator';
			$this->widget_name = esc_html__('AMS Loan Calculator', 'auto-moto-stock');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('Loan Calculator', 'auto-moto-stock'),
					'label' => esc_html__('Title', 'auto-moto-stock')
				),
			);

			parent::__construct();
		}
		/**
		 * Output widget
		 * @param array $args
		 * @param array $instance
		 */
		public function widget($args, $instance)
		{
			$this->widget_start($args, $instance);

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo amotos_get_template_html('widgets/loan-calculator/loan-calculator.php');

			$this->widget_end($args);
		}
	}
}