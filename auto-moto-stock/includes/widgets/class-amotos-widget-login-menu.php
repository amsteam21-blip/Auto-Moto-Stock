<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Widget_Login_Menu')) {
	class AMOTOS_Widget_Login_Menu extends AMOTOS_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'amotos_widget amotos_widget_login_menu';
			$this->widget_description = esc_html__("Show Login/Logout menu.", 'auto-moto-stock');
			$this->widget_id = 'amotos_widget_login_menu';
			$this->widget_name = esc_html__('AMS Login Menu', 'auto-moto-stock');
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
			echo amotos_get_template_html('widgets/login-menu/login-menu.php',array('args' => $args, 'instance' => $instance));
			$this->widget_end($args);
		}
	}
}