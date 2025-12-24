<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Divide')) {
	class SQUICK_Field_Divide extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_style(SQUICK()->assetsHandle('field_divide'), SQUICK()->helper()->getAssetUrl('fields/divide/assets/divide.min.css'), array(), SQUICK()->pluginVer());
		}
		function render()
		{
			$field_classes = array('squick-field');
			$field_classes[] = 'squick-field-' . $this->getType();
			if (isset($this->_setting['style'])) {
				$field_classes[] = 'squick-divide-style-' . $this->_setting['style'];
			}
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo esc_attr(join(' ', $field_classes)) ?>">
				<div><span></span></div>
			</div>
			<?php
		}
	}
}