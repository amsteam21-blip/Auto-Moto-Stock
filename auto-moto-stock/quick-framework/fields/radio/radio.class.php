<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Radio')) {
	class SQUICK_Field_Radio extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_script(SQUICK()->assetsHandle('field_radio'), SQUICK()->helper()->getAssetUrl('fields/radio/assets/radio.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_radio'), SQUICK()->helper()->getAssetUrl('fields/radio/assets/radio.min.css'), array(), SQUICK()->pluginVer());
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
			?>
			<div class="squick-field-radio-inner <?php echo esc_attr($value_inline ? 'value-inline' : ''); ?>">
				<?php foreach ($this->_setting['options'] as $key => $value): ?>
					<label>
						<input data-field-control="" type="radio" <?php SQUICK()->helper()->theChecked($key, $field_value) ?>
						       name="<?php $this->theInputName(); ?>"
						       value="<?php echo esc_attr($key); ?>" />
						<span><?php echo esc_html($value); ?></span>
					</label>
				<?php endforeach;?>
			</div>
		<?php
		}
	}
}