<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Checkbox')) {
	class SQUICK_Field_Checkbox extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_style(SQUICK()->assetsHandle('field_checkbox'), SQUICK()->helper()->getAssetUrl('fields/checkbox/assets/checkbox.min.css'), array(), SQUICK()->pluginVer());
			wp_enqueue_script(SQUICK()->assetsHandle('field_checkbox'), SQUICK()->helper()->getAssetUrl('fields/checkbox/assets/checkbox.min.js'), array(), SQUICK()->pluginVer(), true);
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			?>
			<div class="squick-field-checkbox-inner">
				<label>
					<input data-field-control="" type="checkbox" <?php SQUICK()->helper()->render_attr_iff($field_value, 'checked', 'checked'); ?>
					       name="<?php $this->theInputName(); ?>"
					       value="1"/>
                    <?php if (isset($this->_setting['desc'])): ?>
					<span><?php echo wp_kses_post($this->_setting['desc']) ?></span>
                    <?php endif; ?>
				</label>
			</div>
		<?php
		}
		function renderDescription() {}

		function getEmptyValue() {
			return '';
		}
	}
}