<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('SQUICK_Inc_Hook')) {
	class SQUICK_Inc_Hook {
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
		public function init() {
			$this->addAction();
			$this->addFilter();
		}

		private function addAction() {
			SQUICK()->adminAjax()->addAjaxAction();
			add_action('wp_enqueue_scripts',array($this,'frontEndAssets'));
			add_action('admin_enqueue_scripts',array($this,'adminAssets'));

		}

		private function addFilter() {

		}

		public function frontEndAssets() {
		}

		public function adminAssets() {
		}
	}
}