<?php
/**
 * Field Ace Editor
 *
 * @package QuickFramework
 * @subpackage Fields
 * @author stocktheme
 * @since 1.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Ace_Editor')) {
	class SQUICK_Field_Ace_Editor extends SQUICK_Field
	{
		function enqueue()
		{

			wp_enqueue_script('ace_editor', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js', array( 'jquery' ), '1.4.2', true);
			wp_enqueue_script(SQUICK()->assetsHandle('field_ace_editor'), SQUICK()->helper()->getAssetUrl('fields/ace_editor/assets/ace-editor.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_ace_editor'), SQUICK()->helper()->getAssetUrl('fields/ace_editor/assets/ace-editor.min.css'), array(), SQUICK()->pluginVer());
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			$settings = array(
				'minLines' => 8,
				'maxLines' => 20,
				'showPrintMargin' => false
			);
			if (isset($this->_setting['min_line'])) {
				$settings['minLines'] = $this->_setting['min_line'];
			}
			if (isset($this->_setting['max_line'])) {
				$settings['maxLines'] = $this->_setting['max_line'];
			}
			if (isset($this->_setting['js_options']) && is_array($this->_setting['js_options'])) {
				$settings = wp_parse_args( $this->_setting['js_options'], $settings );
			}
			$mode = isset($this->_setting['mode']) ? $this->_setting['mode'] : '';
			$theme = isset($this->_setting['theme']) ? $this->_setting['theme'] : 'chrome';

			$editor_id = $this->getID() . '__ace_editor';
			?>
			<div class="squick-field-ace-editor-inner">
				<textarea data-field-control="" name="<?php $this->theInputName(); ?>" class="squick-hidden-field "
				          data-mode="<?php echo esc_attr($mode); ?>"
				          data-theme="<?php echo esc_attr($theme); ?>"
				          data-options="<?php echo esc_attr(wp_json_encode($settings)); ?>"><?php echo esc_textarea($field_value); ?></textarea>
				<pre class="squick-ace-editor" id="<?php echo esc_attr($editor_id); ?>"><?php echo esc_html(htmlspecialchars($field_value)); ?></pre>
			</div>
		<?php
		}
	}
}