<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Button_Set')) {
	class SQUICK_Field_Button_Set extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_style(SQUICK()->assetsHandle('field_button_set'));
			wp_enqueue_script(SQUICK()->assetsHandle('field_button_set'), SQUICK()->helper()->getAssetUrl('fields/button_set/assets/button-set.min.js'), array(), SQUICK()->pluginVer(), true);
		}
		function renderContent()
		{
			if (!isset($this->_setting['options']) || !is_array($this->_setting['options'])) {
				return;
			}
			$field_value = $this->getFieldValue();

			$is_multiple = isset($this->_setting['multiple']) && $this->_setting['multiple'];
			if ($is_multiple && !is_array($field_value)) {
				$field_value = (array)$field_value;
			}
			$allowClear = (!isset($this->_setting['multiple']) || !$this->_setting['multiple'])
						&& (isset($this->_setting['allow_clear']) && $this->_setting['allow_clear']);
			?>
			<div class="squick-field-button_set-inner" <?php SQUICK()->helper()->render_attr_iff($is_multiple, 'data-multiple', 'true'); ?>>
				<?php foreach ($this->_setting['options'] as $key => $value): ?>
					<?php $key = (string)$key; ?>
					<label>
						<?php if (isset($this->_setting['multiple']) && $this->_setting['multiple']): ?>
							<input data-field-control="" type="checkbox" name="<?php $this->theInputName(); ?>[]" value="<?php echo esc_attr($key); ?>" <?php SQUICK()->helper()->theChecked($key, $field_value) ?>/>
						<?php else: ?>
							<input data-field-control="" type="radio" name="<?php $this->theInputName(); ?>" value="<?php echo esc_attr($key); ?>" <?php SQUICK()->helper()->theChecked($key, $field_value) ?>/>
						<?php endif;?>
						<span class="<?php echo esc_attr($allowClear ? 'squick-allow-clear' : ''); ?>"><?php echo esc_html($value); ?></span>
					</label>
				<?php endforeach;?>
			</div>
		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array | string
		 */
		function getDefault() {
			$default = '';
			if (isset($this->_setting['multiple']) && $this->_setting['multiple']) {
				$default = array();
			}
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : $default;
			return $field_default;
		}
	}
}