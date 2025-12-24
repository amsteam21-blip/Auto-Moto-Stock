<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Widget_My_Package')) {
	class AMOTOS_Widget_My_Package extends AMOTOS_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'amotos_widget amotos_widget_my_package';
			$this->widget_description = esc_html__("Display the user pack in the sidebar.", 'auto-moto-stock');
			$this->widget_id = 'amotos_widget_my_package';
			$this->widget_name = esc_html__('AMS My Package', 'auto-moto-stock');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('My Package', 'auto-moto-stock'),
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
			echo amotos_get_template_html('widgets/my-package/my-package.php',array('args' => $args, 'instance' => $instance));

			$this->widget_end($args);
		}
	}
}