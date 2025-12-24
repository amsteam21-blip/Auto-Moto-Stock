<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Inc_Admin_Theme_Options')) {
	class SQUICK_Inc_Admin_Theme_Options
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $is_theme_option_page = false;
		public $current_section = '';
		public $current_page = '';
		public $current_preset = '';

		public function init() {
			add_action('admin_menu', array($this, 'themeOptionsMenu'),11);

			add_action('wp_ajax_squick_save_options', array($this, 'saveOptions'));
			add_action('wp_ajax_squick_import_popup', array($this, 'importPopup'));
			add_action('wp_ajax_squick_export_theme_options', array($this, 'exportThemeOption'));
			add_action('wp_ajax_squick_import_theme_options', array($this, 'importThemeOptions'));
			add_action('wp_ajax_squick_reset_theme_options', array($this, 'resetThemeOptions'));
			add_action('wp_ajax_squick_reset_section_options', array($this, 'resetSectionOptions'));
			add_action('wp_ajax_squick_create_preset_options', array($this, 'createPresetOptions'));
			add_action('wp_ajax_squick_ajax_theme_options', array($this, 'ajaxThemeOption'));
			add_action('wp_ajax_squick_delete_preset', array($this, 'deletePreset'));
			add_action('wp_ajax_squick_make_default_options', array($this, 'makeDefaultOption'));
		}

		public function themeOptionsMenu() {
			$current_page = isset($_GET['page']) ? SQUICK()->helper()->sanitize_text($_GET['page']) : '';
			$configs = &$this->getOptionConfig();

			foreach ($configs as $page => $config) {
				if (isset($config['parent_slug'])) {
					if (empty($config['parent_slug'])) {
						add_menu_page(
							$config['page_title'],
							$config['menu_title'],
							$config['permission'],
							$page,
							array($this, 'binderPage'),
							isset($config['icon_url']) ? $config['icon_url'] : '',
							isset($config['position']) ? $config['position'] : null
						);
					}
					else {
						add_submenu_page(
							$config['parent_slug'],
							$config['page_title'],
							$config['menu_title'],
							$config['permission'],
							$page,
							array($this, 'binderPage')
						);
					}
				}
				else {
					add_theme_page(
						$config['page_title'],
						$config['menu_title'],
						$config['permission'],
						$page,
						array($this, 'binderPage')
					);
				}

				if ($current_page == $page) {
					// Enqueue common styles and scripts
					add_action('admin_init', array($this, 'adminEnqueueStyles'));
					add_action('admin_init', array($this, 'adminEnqueueScripts'), 15);
				}
			}
		}

		public function adminEnqueueStyles() {
			wp_enqueue_media();
			wp_enqueue_style(SQUICK()->assetsHandle('options'));
			wp_enqueue_style(SQUICK()->assetsHandle('fields'));

		}

		public function adminEnqueueScripts() {
			wp_enqueue_media();
			wp_enqueue_script('quicktags');

			wp_enqueue_script(SQUICK()->assetsHandle('fields'));
			wp_enqueue_script(SQUICK()->assetsHandle('options'));
			wp_localize_script(SQUICK()->assetsHandle('fields'), 'SQUICK_META_DATA', array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => SQUICK()->helper()->getNonceValue(),
				'msgSavingOptions' => esc_html__('Saving Options...', 'auto-moto-stock'),
				'msgResettingOptions' => esc_html__('Resetting Options...', 'auto-moto-stock'),
				'msgResettingSection' => esc_html__('Resetting Section...', 'auto-moto-stock'),
				'msgConfirmResetSection'   => esc_html__('Are you sure? Resetting will lose all custom values.', 'auto-moto-stock'),
				'msgConfirmResetOptions' => esc_html__('Are you sure? Resetting will lose all custom values in this section.', 'auto-moto-stock'),
				'msgResetOptionsDone' => esc_html__('Reset theme options done', 'auto-moto-stock'),
				'msgResetOptionsError' => esc_html__('Reset theme options error', 'auto-moto-stock'),
				'msgResetSectionDone' => esc_html__('Reset section done', 'auto-moto-stock'),
				'msgResetSectionError' => esc_html__('Reset section error', 'auto-moto-stock'),
				'msgConfirmImportData'               => esc_html__('Are you sure?  This will overwrite all existing option values, please proceed with caution!', 'auto-moto-stock'),
				'msgImportDone'                      => esc_html__('Import option done', 'auto-moto-stock'),
				'msgImportError'                     => esc_html__('Import option error', 'auto-moto-stock'),
				'msgSaveWarning' => esc_html__('Settings have changed, you should save them!', 'auto-moto-stock'),
				'msgSaveSuccess' => esc_html__('Data saved successfully!', 'auto-moto-stock'),
				'msgConfirmDeletePreset'   => esc_html__('Are you sure? The current preset will be deleted!', 'auto-moto-stock'),
				'msgDeletePresetDone' => esc_html__('Delete preset options done', 'auto-moto-stock'),
				'msgDeletePresetError' => esc_html__('Delete preset options error', 'auto-moto-stock'),
				'msgDeletingPreset' => esc_html__('Deleting Section...', 'auto-moto-stock'),
				'msgPreventChangeData' => esc_html__('Changes you made may not be saved. Do you want change options?', 'auto-moto-stock'),
				'msgConfirmMakeDefaultOptions' => esc_html__('Are you sure? Make this preset to default options.', 'auto-moto-stock'),
				'msgMakingDefaultOptions' => esc_html__('Make this preset to default options...', 'auto-moto-stock'),
				'msgMakeDefaultOptionsError' => esc_html__('Make this preset to default options error', 'auto-moto-stock'),
			));
		}

		public function &getOptionConfig($page = '', $in_preset = false) {
			if (!isset($GLOBALS['squick_option_config'])) {
				$configs = apply_filters('squick_option_config', array());
				SQUICK()->helper()->processConfigsFieldID($configs, $GLOBALS['squick_option_config']);
			}
			if ($page === '') {
				return $GLOBALS['squick_option_config'];
			}
			if (isset($GLOBALS['squick_option_config'][$page])) {
				$configs = &$GLOBALS['squick_option_config'][$page];
				$enable_preset = isset($configs['preset']) ? $configs['preset'] : false;
				if ($enable_preset && $in_preset) {
					$this->processPresetConfigSection($configs, $in_preset);
				}

				return $configs;
			}
			return array();
		}

		private function processPresetConfigSection(&$configs, $in_preset) {
			if (isset($configs['section'])) {
				foreach ($configs['section'] as $key => &$section) {
					if ($in_preset && isset($section['general_options']) && $section['general_options']) {
						unset($configs['section'][$key]);
						continue;
					}
					if (isset($section['fields'])) {
						$this->processPresetConfigField($section['fields'], $in_preset);
					}

				}
			}
			else {
				if (isset($configs['fields'])) {
					$this->processPresetConfigField($configs['fields'], $in_preset);
				}
			}
		}

		private function processPresetConfigField(&$fields, $in_preset) {
			foreach ($fields as $key => &$field) {
				if ($in_preset && isset($field['general_options']) && $field['general_options']) {
					unset($fields[$key]);
					continue;
				}
				$type = isset($field['type']) ? $field['type'] : '';

				switch ($type) {
					case 'group':
					case 'row':
					case 'panel':
					case 'repeater':
						if (isset($field['fields'])) {
							$this->processPresetConfigField($field['fields'], $in_preset);
						}
						break;
				}
			}
		}

		public function get_current_option_key($option_name, $preset_name = '') {
			if (isset($_REQUEST['_squick_preset'])) {
				$preset_name_query = SQUICK()->helper()->sanitize_text($_REQUEST['_squick_preset']);
				$preset_name_query = explode('|', $preset_name_query);

				foreach ($preset_name_query as $preset_key) {
					if (strpos($preset_key, $option_name) === 0) {
						$preset_name = $preset_key;
						break;
					}
				}

			}
			$options_key = $this->getOptionPresetName($option_name, $preset_name);

			return $options_key;
		}

		public function &getOptions($option_name, $preset_name = '',$allow_cache = true) {
			if (!isset($GLOBALS['squick_options'])) {
				$GLOBALS['squick_options'] = array();
			}
			$options_key = $this->get_current_option_key($option_name, $preset_name);


			if ($allow_cache && isset($GLOBALS['squick_options'][$options_key])) {
				return $GLOBALS['squick_options'][$options_key];
			}

			$options = get_option($option_name);

			if (!is_array($options)) {
				$options = array();
			}

			if ($options_key !== $option_name) {
				$preset_options = get_option($options_key);
				if (is_array($preset_options)) {
					foreach ($preset_options as $key => $value) {
						$options[$key] = $value;
					}
				}
			}

			if (!$allow_cache) {
				return $options;
			}

			$GLOBALS['squick_options'][$options_key] = &$options;
			return $GLOBALS['squick_options'][$options_key];
		}

		public function setOptions($option_name, &$new_options) {
			if (!isset($GLOBALS['squick_options'])) {
				$GLOBALS['squick_options'] = array();
			}
			$GLOBALS['squick_options'][$option_name] = $new_options;
		}

		public function getPresetOptionKeys($option_name) {
			$option_keys = get_option('squick_preset_options_keys_' . $option_name);
			if (!is_array($option_keys)) {
				$option_keys = array();
			}
			return $option_keys;
		}

		/*public function getOptionPresetName($option_name, $preset_name) {
			return $option_name . (!empty($preset_name) ? '__' . $preset_name : '');
		}*/

		public function getOptionPresetName($option_name, $preset_name) {
			return empty($preset_name) ? $option_name : $preset_name;
		}

		public function updatePresetOptionsKey($option_name, $preset_name, $preset_title) {
			$option_keys = $this->getPresetOptionKeys($option_name);
			if (!isset($option_keys[$preset_name])) {
				$option_keys[$preset_name] = $preset_title;
			}
			update_option('squick_preset_options_keys_' . $option_name, $option_keys);
		}
		public function deletePresetOptionKeys($option_name, $preset_name) {
			$option_keys = $this->getPresetOptionKeys($option_name);
			if (isset($option_keys[$preset_name])) {
				unset($option_keys[$preset_name]);
			}
			update_option('squick_preset_options_keys_' . $option_name, $option_keys);
		}

		public function makeDefaultOption() {
			check_ajax_referer('squick_theme_options_management');

            $page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];
			$backup = get_option($this->getOptionPresetName($option_name, $current_preset));

			$options = get_option($option_name);

			$option_default = SQUICK()->helper()->getConfigDefault($configs);

			foreach ($backup as $key => $value) {
				if (isset($option_default[$key])) {
					$options[$key] = $value;
				}
			}


			/**
			 * Update Options
			 */
			update_option($option_name, $options);

			/**
			 * Call action after change options
			 */
			do_action("squick_after_change_options/{$option_name}", $options, '');

			echo 1;
			die();
		}

		/**
		 * Binder Option Page
		 */
		public function binderPage() {
			add_action('admin_footer', array($this, 'binderPresetPopup'));
			$page = isset($_GET['page']) ? SQUICK()->helper()->sanitize_text($_GET['page']) : '';
			$current_preset = isset($_GET['_squick_preset']) ? SQUICK()->helper()->sanitize_text($_GET['_squick_preset']) : '';
			$configs = &$this->getOptionConfig($page, !empty($current_preset));
			$enable_preset = isset($configs['preset']) ? $configs['preset'] : false;

			if (!$enable_preset) {
				$current_preset = '';
			}

			$current_section_id = isset($_GET['section']) ? SQUICK()->helper()->sanitize_text($_GET['section']) : '';

			if (($current_section_id === '') && isset($configs['section'])) {
				$section_keys = array_keys($configs['section']);
				$current_section_id = isset($section_keys[0]) ? $section_keys[0] : '';
			}

			$this->current_section = $current_section_id;

			$this->enqueueOptionsAssets($configs);
			$theme = wp_get_theme();
			$option_name = $configs['option_name'];
			$version = isset($configs['version']) ? $configs['version'] :  $theme->get('Version');
			if (!empty($current_preset)) {
				$preset_keys = $this->getPresetOptionKeys($option_name);
				if (isset($preset_keys[$current_preset])) {
					$configs['page_title'] = $preset_keys[$current_preset];
				}
			}

			SQUICK()->helper()->setFieldLayout(isset($configs['layout']) ? $configs['layout'] : 'inline');

			/**
			 * Get Options Value
			 */
			$options = get_option($this->getOptionPresetName($option_name, $current_preset));
			?>
			<div class="squick-theme-options-page">
				<?php
				SQUICK()->helper()->getTemplate('admin/templates/theme-options-start',
					array(
						'option_name' => $option_name,
						'version' => $version,
						'page' => $page,
						'current_preset' => $current_preset,
						'page_title' => $configs['page_title'],
						'desc'       => isset($configs['desc']) ? $configs['desc'] : '',
						'preset' => $enable_preset,
					)
				);
				SQUICK()->helper()->renderFields($configs, $options, $current_preset);
				SQUICK()->helper()->getTemplate('admin/templates/theme-options-end', array(
					'is_exists_section' => isset($configs['section'])
				));
				?>
			</div><!-- /.squick-theme-options-page -->
			<?php
		}

		public function binderPresetPopup() {
			SQUICK()->helper()->getTemplate('admin/templates/preset-popup');
		}

		public function createPresetOptions() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);
			$preset_title = SQUICK()->helper()->sanitize_text($_POST['_preset_title']);
			$new_preset_name = sanitize_title($preset_title);

			$configs = &$this->getOptionConfig($page, !empty($new_preset_name));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$enable_preset = isset($configs['preset']) ? $configs['preset'] : false;

			$current_section_id = isset($_POST['_current_section']) ? SQUICK()->helper()->sanitize_text($_POST['_current_section']) : '';
			if (($current_section_id === '') && isset($configs['section'])) {
				$section_keys = array_keys($configs['section']);
				$current_section_id = isset($section_keys[0]) ? $section_keys[0] : '';
			}

			$this->current_section = $current_section_id;

			if (!$enable_preset) {
				die();
			}

			$theme = wp_get_theme();
			$option_name = $configs['option_name'];
			$version = isset($configs['version']) ? $configs['version'] :  $theme->get('Version');

			$options = get_option($this->getOptionPresetName($option_name, $current_preset));

			$new_preset_name = $option_name . '__' . sanitize_title($preset_title);

			$option_keys = $this->getPresetOptionKeys($option_name);
			if (!isset($option_keys[$new_preset_name])) {
				if (!empty($new_preset_name)) {
					$option_default = SQUICK()->helper()->getConfigDefault($configs);

					foreach ($option_default as $key => $value) {
						if (!isset($options[$key])) {
							$options[$key] = $option_default[$key];
						}
					}
					foreach ($options as $key => $value) {
						if (!isset($option_default[$key])) {
							unset($options[$key]);
						}
					}

					update_option($this->getOptionPresetName($option_name, $new_preset_name), $options);
					$configs['page_title'] = $preset_title;
					$this->updatePresetOptionsKey($option_name, $new_preset_name, $preset_title);
				}
				$configs['option_name'] = $this->getOptionPresetName($option_name, $new_preset_name);
			}


			SQUICK()->helper()->setFieldLayout(isset($configs['layout']) ? $configs['layout'] : 'inline');
			SQUICK()->helper()->getTemplate('admin/templates/theme-options-start',
				array(
					'option_name' => $option_name,
					'version' => $version,
					'page' => $page,
					'current_preset' => $new_preset_name,
					'page_title' => $configs['page_title'],
					'desc'       => isset($configs['desc']) ? $configs['desc'] : '',
					'preset' => true,
				)
			);

			SQUICK()->helper()->renderFields($configs, $options, $current_preset);
			SQUICK()->helper()->getTemplate('admin/templates/theme-options-end', array(
				'is_exists_section' => isset($configs['section'])
			));
			die();
		}

		public function ajaxThemeOption() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);

			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$current_section_id = isset($_POST['_current_section']) ? SQUICK()->helper()->sanitize_text($_POST['_current_section']) : '';
			if (($current_section_id === '') && isset($configs['section'])) {
				$section_keys = array_keys($configs['section']);
				$current_section_id = isset($section_keys[0]) ? $section_keys[0] : '';
			}

			$this->current_section = $current_section_id;

			$theme = wp_get_theme();
			$option_name = $configs['option_name'];
			$version = isset($configs['version']) ? $configs['version'] :  $theme->get('Version');
			$options = get_option($this->getOptionPresetName($option_name, $current_preset));

			$option_keys = $this->getPresetOptionKeys($option_name);
			if (isset($option_keys[$current_preset])) {
				$configs['page_title'] = $option_keys[$current_preset];
				$configs['option_name'] = $this->getOptionPresetName($option_name, $current_preset);
			}
			SQUICK()->helper()->setFieldLayout(isset($configs['layout']) ? $configs['layout'] : 'inline');
			SQUICK()->helper()->getTemplate('admin/templates/theme-options-start',
				array(
					'option_name' => $option_name,
					'version' => $version,
					'page' => $page,
					'current_preset' => $current_preset,
					'page_title' => $configs['page_title'],
					'desc'       => isset($configs['desc']) ? $configs['desc'] : '',
					'preset' => isset($configs['preset']) ? $configs['preset'] : false,
				)
			);

			SQUICK()->helper()->renderFields($configs, $options, $current_preset);
			SQUICK()->helper()->getTemplate('admin/templates/theme-options-end', array(
				'is_exists_section' => isset($configs['section'])
			));
			die();
		}

		public function importPopup() {
            check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_GET['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_GET['_current_preset']);
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';

			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];
			$options = get_option($this->getOptionPresetName($option_name, $current_preset));
			?>
			<div class="stu-popup-container">
				<div class="stu-popup squick-theme-options-backup-popup">
					<h4 class="stu-popup-header"><?php esc_html_e( 'Import/Export Options', 'auto-moto-stock' ); ?></h4>
					<div class="stu-popup-body squick-theme-options-backup-content">
						<section>
							<h5><?php esc_html_e( 'Import Options', 'auto-moto-stock' ); ?></h5>
							<div class="squick-theme-options-backup-import">
								<textarea></textarea>
								<button type="button" class="button"
								        data-import-text="<?php esc_attr_e( 'Import', 'auto-moto-stock' ); ?>"
								        data-importing-text="<?php esc_attr_e( 'Importing...', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'Import', 'auto-moto-stock' ); ?></button>
								<span class=""><?php esc_html_e( 'WARNING! This will overwrite all existing option values, please proceed with caution!', 'auto-moto-stock' ); ?></span>
							</div>
						</section>
						<section>
							<h5><?php esc_html_e( 'Export Options', 'auto-moto-stock' ); ?></h5>
							<div class="squick-theme-options-backup-export">
								<textarea readonly><?php echo esc_textarea(base64_encode( wp_json_encode( $options ) )); ?></textarea>
								<button type="button"
								        class="button"><?php esc_html_e( 'Download Data File', 'auto-moto-stock' ); ?></button>
							</div>
						</section>
					</div>
				</div>
			</div>
		<?php
			die();
		}

		/**
		 * Save Options
		 */
		public function saveOptions() {
            check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);

			$configs = &$this->getOptionConfig($page, !empty($current_preset));

            $capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';

            if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
                die();
			}

			$option_name = $configs['option_name'];
			$current_section = isset($_POST['_current_section']) ? SQUICK()->helper()->sanitize_text($_POST['_current_section']) : '';

			$config_keys = SQUICK()->helper()->getConfigKeys($configs, $current_section);
			$field_default = SQUICK()->helper()->getConfigDefault($configs);
			$config_options = array();
			foreach ($config_keys as $meta_id => $field_meta) {
				$field_type = isset($field_meta['type']) ? $field_meta['type'] : 'text';

                if (in_array($field_type,array('text', 'ace_editor', 'textarea', 'editor', 'panel', 'repeater'))) {
                    $meta_value = isset($_POST[$meta_id]) ? ($_POST[$meta_id]) : $field_meta['empty_value'];
                }
                else {
					$meta_value = isset($_POST[$meta_id]) ? SQUICK()->helper()->sanitize_text($_POST[$meta_id]) : $field_meta['empty_value'];
				}

				$meta_value = apply_filters('squick_get_filed_value_on_save_option',$meta_value,$meta_id,$field_meta );
				$config_options[$meta_id] = wp_unslash($meta_value);
			}
			$options = $this->getOptions($option_name, $current_preset, false);
			$config_options = wp_parse_args($config_options, $options);

			/**
			 * Call action before save options
			 */
			do_action("squick_before_save_options/{$option_name}", $config_options, $current_preset, $current_section);

			/**
			 * Update options
			 */
			update_option($this->getOptionPresetName($option_name, $current_preset), $config_options);

			if (!empty($current_preset)) {
				$default_options = get_option($option_name);
				$config_options = wp_parse_args($config_options, $default_options);
			}

			/**
			 * Call action after save options
			 */
			do_action("squick_after_save_options/{$option_name}", $config_options, $current_preset, $current_section);

			/**
			 * Call action after change options
			 */
			do_action("squick_after_change_options/{$option_name}", $config_options, $current_preset, $current_section);

			wp_send_json_success(esc_html__('Save options Done', 'auto-moto-stock'));
		}

		/**
		 * Export theme options
		 */
		public function exportThemeOption() {
            check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_GET['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_GET['_current_preset']);
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];

			$options = get_option($this->getOptionPresetName($option_name, $current_preset));
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/txt' );
			header( 'Content-Disposition: attachment; filename="quick_framework_' . $option_name . '_backup_' . gmdate( 'd-m-Y' ) . '.json"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );

			echo wp_kses_post(base64_encode(wp_json_encode($options)));
			die();
		}

		/**
		 * Import Options
		 */
		public function importThemeOptions() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];

			if (!isset($_POST['backup_data'])) {
				return;
			}

			$backup_data = SQUICK()->helper()->sanitize_text($_POST['backup_data']);

			$backup = json_decode(base64_decode($backup_data), true);
			if (($backup == null) || !is_array($backup)) {
				return;
			}

			$options = get_option($this->getOptionPresetName($option_name, $current_preset));

			$option_default = SQUICK()->helper()->getConfigDefault($configs);

			foreach ($backup as $key => $value) {
				if (isset($option_default[$key])) {
					$options[$key] = $value;
				}
			}
			/**
			 * Call action after save options
			 */
			do_action("squick_before_import_options/{$option_name}", $options, $current_preset);

			/**
			 * Update Options
			 */
			update_option($this->getOptionPresetName($option_name, $current_preset), $options);

			if (!empty($current_preset)) {
				$default_options = get_option($option_name);
				$options = wp_parse_args($options, $default_options);
			}

			/**
			 * Call action after save options
			 */
			do_action("squick_after_import_options/{$option_name}", $options, $current_preset);

			/**
			 * Call action after change options
			 */
			do_action("squick_after_change_options/{$option_name}", $options, $current_preset);

			echo 1;
			die();
		}

		public function resetThemeOptions() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = isset($_POST['_current_preset']) ?  SQUICK()->helper()->sanitize_text($_POST['_current_preset']) : '';
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];

			$options = SQUICK()->helper()->getConfigDefault($configs);

			do_action("squick_before_reset_options/{$option_name}", $options, $current_preset);

			/**
			 * Update Options
			 */
			update_option($this->getOptionPresetName($option_name, $current_preset), $options);

			if (!empty($current_preset)) {
				$default_options = get_option($option_name);
				$options = wp_parse_args($options, $default_options);
			}

			/**
			 * Call action after reset options
			 */
			do_action("squick_after_reset_options/{$option_name}", $options, $current_preset);

			/**
			 * Call action after change options
			 */
			do_action("squick_after_change_options/{$option_name}", $options, $current_preset);

			echo 1;
			die();

		}

		public function resetSectionOptions() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = isset($_POST['_current_preset']) ? SQUICK()->helper()->sanitize_text($_POST['_current_preset']) : '';
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];
			$section = SQUICK()->helper()->sanitize_text($_POST['section']);
			if (empty($section)) {
				return;
			}

			$option_default = SQUICK()->helper()->getConfigDefault($configs, $section);

			$options = get_option($this->getOptionPresetName($option_name, $current_preset));

			foreach ($option_default as $key => $value) {
				$options[$key] = $value;
			}

			do_action("squick_before_reset_section/{$option_name}", $options, $current_preset);

			/**
			 * Update Options
			 */
			update_option($this->getOptionPresetName($option_name, $current_preset), $options);

			if (!empty($current_preset)) {
				$default_options = get_option($option_name);
				$options = wp_parse_args($options, $default_options);
			}

			/**
			 * Call action after reset options
			 */
			do_action("squick_after_reset_section/{$option_name}", $options, $current_preset);

			/**
			 * Call action after change options
			 */
			do_action("squick_after_change_options/{$option_name}", $options, $current_preset);

			echo 1;
			die();
		}
		public function deletePreset() {
			check_ajax_referer('squick_theme_options_management');

			$page = SQUICK()->helper()->sanitize_text($_POST['_current_page']);
			$current_preset = SQUICK()->helper()->sanitize_text($_POST['_current_preset']);
			$configs = &$this->getOptionConfig($page, !empty($current_preset));

			$capability = isset($configs['permission']) ? $configs['permission'] : 'manage_options';
			if (!current_user_can($capability)) {
				wp_send_json_error(esc_html__('Access Deny!', 'auto-moto-stock'));
				die();
			}

			$option_name = $configs['option_name'];

			/**
			 * Call action before delete preset
			 */
			do_action('squick_before_delete_preset', $option_name, $current_preset);

			delete_option($this->getOptionPresetName($option_name, $current_preset));
			$this->deletePresetOptionKeys($option_name, $current_preset);

			/**
			 * Call action after delete preset
			 */
			do_action('squick_after_delete_preset', $option_name, $current_preset);

			echo 1;
			die();
		}

		private function enqueueOptionsAssets(&$configs) {
			if (isset($configs['section'])) {
				foreach ($configs['section'] as $key => &$section) {
					$this->enqueueOptionsAssetsField($section['fields']);
				}
			}
			else {
				if (isset($configs['fields'])) {
					$this->enqueueOptionsAssetsField($configs['fields']);
				}
			}
		}

		private function enqueueOptionsAssetsField($configs) {
			foreach ($configs as $config) {
				$type = isset($config['type']) ?  $config['type'] : '';
				if (empty($type)) {
					continue;
				}

				$field = SQUICK()->helper()->createField($type);
				if ($field) {
					$field->enqueue();
				}

				switch ($type) {
					case 'row':
					case 'group':
					case 'panel':
					case 'repeater':
						if (isset($config['fields']) && is_array($config['fields'])) {
							$this->enqueueOptionsAssetsField($config['fields']);
						}
						break;
				}
			}
		}


		public function saveDefaultOptions($page, $preset_name = '') {
			$default_options = array();

			$configs = SQUICK()->adminThemeOption()->getOptionConfig($page, false);
			$option_name = $configs['option_name'];

			if (isset($configs['section'])) {
				foreach ($configs['section'] as $key => &$section) {
					if (isset($section['fields'])) {
						$this->getDefaultField($section['fields'], $default_options);
					}
				}
			}
			else {
				if (isset($configs['fields'])) {
					$this->getDefaultField($configs['fields'], $default_options);
				}
			}
			$options = &$this->getOptions($option_name, $preset_name);

			foreach ( $default_options as $key => $value ) {
				if (!isset($options[$key])) {
					$options[$key] = $default_options[$key];
				}
			}
			if (isset($_REQUEST['_squick_preset'])) {
				$preset_name = SQUICK()->helper()->sanitize_text($_REQUEST['_squick_preset']);
			}
			$options_key = $this->getOptionPresetName($option_name, $preset_name);
			update_option($options_key, $options);
		}

		private function getDefaultField($configs, &$default_options) {
			foreach ($configs as $key => &$config) {
				$type = isset($config['type']) ? $config['type'] : '';
				$id = isset($config['id']) ? $config['id'] : '';
				if (empty($type)) {
					continue;
				}
				switch ($type) {
					case 'group':
					case 'row':
						if (isset($config['fields'])) {
							$this->getDefaultField($config['fields'], $default_options);
						}
						break;
					case 'divide':
					case 'info':
						break;
					default:
						if (!empty($id)) {
							$field = SQUICK()->helper()->createField($type);
							$field->_setting = $config;
							$default =  $field->getFieldDefault();
							$default_options[$id] = $default;
						}
						break;
				}
			}
		}
	}
}