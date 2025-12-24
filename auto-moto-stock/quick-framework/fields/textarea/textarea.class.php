<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Textarea')) {
	class SQUICK_Field_Textarea extends SQUICK_Field
	{
		function enqueue()
		{
			wp_enqueue_script(SQUICK()->assetsHandle('field_textarea'), SQUICK()->helper()->getAssetUrl('fields/textarea/assets/textarea.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_textarea'), SQUICK()->helper()->getAssetUrl('fields/textarea/assets/textarea.min.css'), array(), SQUICK()->pluginVer());
		}

		function renderContent($content_args = '')
		{
			$field_value = $this->getFieldValue();
			$attr = array();

			$row = (isset($this->_setting['args']) && isset($this->_setting['args']['row'])) ? esc_attr($this->_setting['args']['row']) : '5';

			$attr['cols'] = $row;

			if (isset($this->_setting['args']['col']) && (isset($this->_setting['args']['col']) !== '')) {
				$attr['rows'] = $this->_setting['args']['col'];
			}
			if (isset($this->_setting['is_required']) && ($this->_setting['is_required'] === true)) {
				$attr['required'] = 'required';
			}
			if (isset($this->_setting['placeholder']) && ($this->_setting['placeholder'] !== '')) {
				$attr['placeholder'] = $this->_setting['placeholder'];
			}
			?>
			<div class="squick-field-textarea-inner">
			<textarea data-field-control="" name="<?php $this->theInputName(); ?>"
	            <?php SQUICK()->helper()->render_html_attr($attr); ?>><?php echo esc_textarea($field_value); ?></textarea>
			</div>
			<?php
		}
	}
}