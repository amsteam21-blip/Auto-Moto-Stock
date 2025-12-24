<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('SQUICK_Inc_Assets')) {
	class SQUICK_Inc_Assets {
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_action('init', array($this, 'registerScript'), 0);
			add_action('init', array($this, 'registerStyle'), 0);
		}

		public function registerScript() {
			/**
			 * Vendors script
			 */
			wp_register_script('nouislider', SQUICK()->helper()->getAssetUrl('assets/vendors/noUiSlider/nouislider.min.js'), array(), '15.6.1', true);
			wp_register_script('perfect-scrollbar', SQUICK()->helper()->getAssetUrl('assets/vendors/perfect-scrollbar/js/perfect-scrollbar.min.js'), array('jquery'), '1.5.3', true);
			wp_register_script('selectize', SQUICK()->helper()->getAssetUrl('assets/vendors/selectize/js/selectize.min.js'), array('jquery'), '0.15.1', true);
			wp_register_script('wp-color-picker-alpha', SQUICK()->helper()->getAssetUrl('assets/vendors/wp-color-picker-alpha.min.js'), array('jquery'), '2.1.2', true);
			wp_register_script('hc-sticky', SQUICK()->helper()->getAssetUrl('assets/vendors/hc-sticky/hc-sticky.min.js'), array('jquery'), '2.2.7', true);

			wp_register_script('st-utils', SQUICK()->helper()->getAssetUrl('assets/vendors/st-utils/st-utils.min.js'), array('jquery'), '1.0.0', true);

			/**
			 * Framework script
			 */
			wp_register_script(SQUICK()->assetsHandle('media'), SQUICK()->helper()->getAssetUrl('assets/js/media.min.js'), array('jquery'), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('fields'), SQUICK()->helper()->getAssetUrl('assets/js/fields.min.js'), array('jquery', 'wp-util', 'hc-sticky', 'st-utils'), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('options'), SQUICK()->helper()->getAssetUrl('assets/js/options.min.js'), array('jquery', 'jquery-form'), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('term-meta'), SQUICK()->helper()->getAssetUrl('assets/js/term-meta.min.js'), array('jquery'), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('widget'), SQUICK()->helper()->getAssetUrl('assets/js/widget.min.js'), array('jquery'), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('user-meta'), SQUICK()->helper()->getAssetUrl('assets/js/user-meta.min.js'), array('jquery'), SQUICK()->pluginVer(), true);
            wp_register_script(SQUICK()->assetsHandle('meta-box'), SQUICK()->helper()->getAssetUrl('assets/js/meta-box.min.js'), array('jquery'), SQUICK()->pluginVer(), true);

            /**
             * Fields
             */
			wp_register_script(SQUICK()->assetsHandle('field_image'), SQUICK()->helper()->getAssetUrl('fields/image/assets/image.min.js'), array(SQUICK()->assetsHandle('media')), SQUICK()->pluginVer(), true);
			wp_register_script(SQUICK()->assetsHandle('field-select-popup'), SQUICK()->helper()->getAssetUrl('fields/select_popup/assets/select-popup.min.js'), array('perfect-scrollbar'), SQUICK()->pluginVer(), true);

			global $wp_version;
			if ( version_compare($wp_version,'5.5') >= 0) {
				wp_localize_script('wp-color-picker-alpha',
					'wpColorPickerL10n',
					array(
						'clear'            => esc_html__( 'Clear','auto-moto-stock' ),
						'clearAriaLabel'   => esc_html__( 'Clear color','auto-moto-stock'  ),
						'defaultString'    => esc_html__( 'Default','auto-moto-stock'  ),
						'defaultAriaLabel' => esc_html__( 'Select default color','auto-moto-stock'  ),
						'pick'             => esc_html__( 'Select Color','auto-moto-stock'  ),
						'defaultLabel'     => esc_html__( 'Color value','auto-moto-stock'  ),
					));
			}



		}
		public function registerStyle() {
			/**
			 * Vendors style
			 */
			wp_register_style( 'nouislider', SQUICK()->helper()->getAssetUrl( 'assets/vendors/noUiSlider/nouislider.min.css' ), array(), '15.6.1' );
			wp_register_style( 'perfect-scrollbar', SQUICK()->helper()->getAssetUrl( 'assets/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css' ), array(), '1.5.3' );
			wp_register_style( 'selectize-default', SQUICK()->helper()->getAssetUrl( 'assets/vendors/selectize/css/selectize.default.min.css' ), array(), '0.15.1' );
			wp_register_style( 'selectize', SQUICK()->helper()->getAssetUrl( 'assets/vendors/selectize/css/selectize.min.css' ), array('selectize-default'), '0.15.1' );


			wp_register_style( 'st-utils', SQUICK()->helper()->getAssetUrl( 'assets/vendors/st-utils/st-utils.min.css' ), array(), '1.0.0' );

			/**
			 * Framework style
			 */
			wp_register_style(SQUICK()->assetsHandle('fields'), SQUICK()->helper()->getAssetUrl('assets/css/fields.min.css'), array('st-utils'), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('field_button_set'), SQUICK()->helper()->getAssetUrl('fields/button_set/assets/button-set.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('field_image_set'), SQUICK()->helper()->getAssetUrl('fields/image_set/assets/image-set.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('field_switch'), SQUICK()->helper()->getAssetUrl('fields/switch/assets/switch.min.css'), array(), SQUICK()->pluginVer());

			wp_register_style(SQUICK()->assetsHandle('options'), SQUICK()->helper()->getAssetUrl('assets/css/options.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('term-meta'), SQUICK()->helper()->getAssetUrl('assets/css/term-meta.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('widget'), SQUICK()->helper()->getAssetUrl('assets/css/widget.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('user-meta'), SQUICK()->helper()->getAssetUrl('assets/css/user-meta.min.css'), array(), SQUICK()->pluginVer());

			/**
			 * Fields
			 */
			wp_register_style(SQUICK()->assetsHandle('field_image'), SQUICK()->helper()->getAssetUrl('fields/image/assets/image.min.css'), array(), SQUICK()->pluginVer());
			wp_register_style(SQUICK()->assetsHandle('field-select-popup'), SQUICK()->helper()->getAssetUrl('fields/select_popup/assets/select-popup.min.css'), array('perfect-scrollbar'), SQUICK()->pluginVer());
		}
	}
}