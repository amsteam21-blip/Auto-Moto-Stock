<?php
/**
 * Updater plugin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('AMOTOS_Updater')) {
	/**
	 * Class AMOTOS_Updater
	 */
	class AMOTOS_Updater
	{
		public static function updater()
		{
			$amotos_fix_option = get_option('amotos_fix_option', false);
			$amotos_pre_version = get_option( 'amotos_version', AMOTOS_PLUGIN_VER );
			if (($amotos_fix_option === false) || (version_compare( AMOTOS_PLUGIN_VER, $amotos_pre_version, '>' ))) {
				if (function_exists('SQUICK')) {
					$configs = SQUICK()->adminThemeOption()->getOptionConfig();
					foreach ($configs as $page => $config) {
						$options_default = SQUICK()->helper()->getConfigDefault($config);

						$current_option = get_option($config['option_name'], array());
						$is_update = false;
						foreach ($options_default as $k => $v) {
							if (!isset($current_option[$k])) {
								$current_option[$k] = $v;
								$is_update = true;
							}
						}
						if ($is_update) {
							update_option($config['option_name'], $current_option);
						}
					}
					update_option('amotos_fix_option', true);
					update_option('amotos_version', AMOTOS_PLUGIN_VER);
				}
			}
		}
	}
}