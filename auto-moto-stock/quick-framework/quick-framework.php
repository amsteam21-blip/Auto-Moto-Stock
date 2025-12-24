<?php
/**
 * @version 1.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Framework')) {
	class SQUICK_Framework
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance() {
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $metaFields = array();

		/**
		 * @param string $meta_type option | post_meta | term_meta | user_meta
		 * @return array
		 */
		public function getMetaField($meta_type = 'option') {
			return isset($this->metaFields[$meta_type]) ? $this->metaFields[$meta_type] : array();
		}

		public function init() {
			do_action('squick_before_init');

			spl_autoload_register(array($this, 'incAutoload'));
			spl_autoload_register(array($this, 'fieldsAutoload'));
			$this->includes();
			$this->hook()->init();
			SQUICK()->assets()->init();
			SQUICK()->adminMetaBoxes()->init();
			SQUICK()->adminThemeOption()->init();
			SQUICK()->adminWidget()->init();
			SQUICK()->adminTaxonomy()->init();
			SQUICK()->adminUserMeta()->init();
			$this->customCss()->init();
			$this->content_inject()->init();

			$this->loadFile(SQUICK()->pluginDir('core/icons-popup/icons-popup.class.php'));
			SQUICK_Core_Icons_Popup::getInstance()->init();

			$this->loadFile(SQUICK()->pluginDir('core/fonts/fonts.class.php'));
			SQUICK_Core_Fonts::getInstance()->init();
			$this->load_textdomain();

			do_action('squick_init');
		}


		/**
		 * Inc library auto loader
		 *
		 * @param $class
		 */
		public function incAutoload($class) {
			$file_name = preg_replace('/^SQUICK_Inc_/', '', $class);
			if ($file_name !== $class) {
				$file_name = strtolower($file_name);
				$file_name = str_replace('_', '-', $file_name);
				$this->loadFile(SQUICK()->pluginDir("inc/{$file_name}.class.php"));
			}
		}

		/**
		 * Field auto loader
		 * @param $class
		 */
		public function fieldsAutoload($class) {
			$file_name = preg_replace('/^SQUICK_Field_/', '', $class);
			if ($file_name !== $class) {
				$file_name = strtolower($file_name);
				$this->loadFile(SQUICK()->pluginDir("fields/{$file_name}/{$file_name}.class.php"));
			}
		}

		public function loadFile($path) {
			if ($path && is_readable($path)) {
				include_once($path);
				return true;
			}
			return false;
		}

		/**
		 * Include library
		 */
		private function includes() {
			require_once SQUICK()->pluginDir('fields/field.php');
		}

		public function pluginVer() {
			return '1.0.0';
		}

		/**
		 *
		 * @param string $path
		 * @return string
		 */
		public function pluginUrl($path = '') {
			return trailingslashit(SQUICK_PLUGIN_URI) . $path;
		}

		/**
		 * Get Plugin Dir
		 *
		 * @param string $path
		 * @return string
		 */
		public function pluginDir($path = '') {
			return plugin_dir_path(__FILE__) . $path;
		}

		public function assetsHandle($handle = '') {
			return apply_filters('squick_assets_prefix', 'squick_') . $handle;
		}

		public function load_textdomain() {
			$text_domain_file = SQUICK()->pluginDir() . 'languages/quick-framework-' . get_locale() . '.mo';
			if (is_readable($text_domain_file)) {
				load_textdomain('quick-framework', $text_domain_file);
			}
			load_plugin_textdomain('quick-framework', false, SQUICK()->pluginDir() . 'languages');

			$loco_settings_key = '';
			if (defined('SQUICK_PLUGIN_OWNER_FILE')) {
				$loco_settings_key = 'loco_plugin_config__' . plugin_basename(SQUICK_PLUGIN_OWNER_FILE);
			}

			if ($loco_settings_key !== '') {
				$loca_config = get_option($loco_settings_key);
				if (isset($loca_config['d'][2]) && is_array($loca_config['d'][2])) {
					$plugin_configs = $loca_config['d'][2];
					$is_exists_text_domain = false;
					foreach ($plugin_configs as $p) {
						if (!isset($p[0]) || !isset($p[1]) || !isset($p[1]['name'])) {
							continue;
						}
						if (($p[0] === 'domain') && ($p[1]['name'] === 'quick-framework')) {
							$is_exists_text_domain = true;
						}
					}
					if (!$is_exists_text_domain) {
						array_push($loca_config['d'][2], array(
							0 => 'domain',
							1 => array(
								'name' => 'quick-framework',
							),
							2 => array(
								0 => array(
									0 => 'project',
									1 => array(
										'name' => esc_html__('Quick Framework', 'auto-moto-stock'),
										'slug' => 'quick-framework',
									),
									2 => array(
										0 => array(
											0 => 'source',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'directory',
													1 => array(),
													2 => array(''),
												),
											),
										),
										1 => array(
											0 => 'target',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'directory',
													1 => array(),
													2 => array('quick-framework/languages'),
												),
											),
										),
										2 => array(
											0 => 'template',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'file',
													1 => array(),
													2 => array('quick-framework/languages/quick-framework.pot'),
												),
											),
										),
									),
								),
							),
						));
						update_option($loco_settings_key, $loca_config);
					}

				}
			}
		}

		/**
		 * @return SQUICK_Inc_Hook
		 */
		public function hook() {
			return SQUICK_Inc_Hook::getInstance();
		}

		/**
		 * SQUICK helper function
		 * @return SQUICK_Inc_Helper
		 */
		public function helper() {
			return SQUICK_Inc_Helper::getInstance();
		}

		/**
		 * @return SQUICK_Inc_Custom_Css
		 */
		public function customCss() {
			return SQUICK_Inc_Custom_Css::getInstance();
		}

		/**
		 * SQUICK Assets
		 *
		 * @return SQUICK_Inc_Assets
		 */
		public function assets() {
			return SQUICK_Inc_Assets::getInstance();
		}

		/**
		 * SQUICK ajax
		 * @return SQUICK_Inc_Admin_Ajax
		 */
		public function adminAjax() {
			return SQUICK_Inc_Admin_Ajax::getInstance();
		}


		/**
		 * SQUICK Theme Options
		 * @return SQUICK_Inc_Admin_Theme_Options
		 */
		public function adminThemeOption() {
			return SQUICK_Inc_Admin_Theme_Options::getInstance();
		}

		/**
		 * SQUICK Meta Boxes
		 * @return SQUICK_Inc_Admin_Meta_Boxes
		 */
		public function adminMetaBoxes() {
			return SQUICK_Inc_Admin_Meta_Boxes::getInstance();
		}

		/**
		 * Widget Loader
		 *
		 * @return SQUICK_Inc_Admin_Widget
		 */
		public function adminWidget() {
			return SQUICK_Inc_Admin_Widget::getInstance();
		}

		/**
		 * SQUICK Taxonomy
		 * @return SQUICK_Inc_Admin_Taxonomy
		 */
		public function adminTaxonomy() {
			return SQUICK_Inc_Admin_Taxonomy::getInstance();
		}

		/**
		 * SQUICK User Meta
		 * @return SQUICK_Inc_Admin_User_Meta
		 */
		public function adminUserMeta() {
			return SQUICK_Inc_Admin_User_Meta::getInstance();
		}

		/**
		 * @return SQUICK_Inc_File
		 */
		public function file() {
			return SQUICK_Inc_File::getInstance();
		}

		/**
		 * @return SQUICK_Inc_Content_Inject
		 */
		public function content_inject() {
			return SQUICK_Inc_Content_Inject::getInstance();
		}
	}

	/**
	 * @return SQUICK_Framework
	 */
	function SQUICK() {
		return SQUICK_Framework::getInstance();
	}

	/**
	 * Init Quick Framework
	 */
	SQUICK()->init();
}