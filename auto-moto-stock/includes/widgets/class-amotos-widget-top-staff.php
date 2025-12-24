<?php

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('AMOTOS_Widget_Top_Staff')) {

	class AMOTOS_Widget_Top_Staff extends AMOTOS_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'amotos_widget amotos_widget_top_staff';
			$this->widget_description = esc_html__("Display the top staff.", 'auto-moto-stock');
			$this->widget_id = 'amotos_widget_top_staff';
			$this->widget_name = esc_html__('AMS Top Staff', 'auto-moto-stock');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('Top Staff', 'auto-moto-stock'),
					'label' => esc_html__('Title', 'auto-moto-stock')
				),
				'number' => array(
					'type' => 'number',
					'std' => '3',
					'label' => esc_html__('Number of top staff', 'auto-moto-stock')
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
			echo amotos_get_template_html('widgets/top-staff/top-staff.php', array('args' => $args, 'instance' => $instance));

			$this->widget_end($args);
		}
	}
}