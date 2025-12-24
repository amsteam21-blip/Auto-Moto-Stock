<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Checkbox_List')) {
	class SQUICK_Field_Checkbox_List extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_style(SQUICK()->assetsHandle('field_checkbox_list'), SQUICK()->helper()->getAssetUrl('fields/checkbox_list/assets/checkbox-list.min.css'), array(), SQUICK()->pluginVer());
			wp_enqueue_script(SQUICK()->assetsHandle('field_checkbox_list'), SQUICK()->helper()->getAssetUrl('fields/checkbox_list/assets/checkbox-list.min.js'), array(), SQUICK()->pluginVer(), true);
		}
		function renderContent()
		{
			if (isset($this->_setting['data'])) {
				switch ($this->_setting['data']) {
					case 'preset':
						if (isset($this->_setting['data-option'])) {
							$this->_setting['options'] = SQUICK()->adminThemeOption()->getPresetOptionKeys($this->_setting['data-option']);
						}
						break;
					case 'sidebar':
						$this->_setting['options'] = SQUICK()->helper()->getSidebars();
						break;
					case 'menu':
						$this->_setting['options'] = SQUICK()->helper()->getMenus();
						break;
					case 'taxonomy':
						$this->_setting['options'] = SQUICK()->helper()->getTaxonomies(isset($this->_setting['data_args']) ? $this->_setting['data_args'] : array());
						break;
					default:
						if (isset($this->_setting['data_args']) && !isset($this->_setting['data_args']['post_type'])) {
							$this->_setting['data_args']['post_type'] = $this->_setting['data'];
						}
						$this->_setting['options'] = SQUICK()->helper()->getPosts(isset($this->_setting['data_args']) ? $this->_setting['data_args'] : array('post_type' => $this->_setting['data']));
						break;
				}
			}

			if (!isset($this->_setting['options']) || !is_array($this->_setting['options'])) {
				return;
			}
			$field_value = $this->getFieldValue();
			$value_inline = isset($this->_setting['value_inline']) ? $this->_setting['value_inline'] : true;

			if (!is_array($field_value)) {
				$field_value = (array)$field_value;
			}
			?>
			<div class="squick-field-checkbox_list-inner <?php echo esc_attr($value_inline ? 'value-inline' : ''); ?>">
				<?php foreach ($this->_setting['options'] as $key => $value): ?>
					<label>
						<input data-field-control="" type="checkbox"
						       name="<?php $this->theInputName(); ?>[]"
						       value="<?php echo esc_attr($key); ?>"
							<?php SQUICK()->helper()->theChecked($key, $field_value) ?>/>
						<span><?php echo esc_html($value); ?></span>
					</label>
				<?php endforeach;?>
			</div>
		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function getDefault() {
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();
			return $field_default;
		}

		function getEmptyValue() {
			return array();
		}

	}
}