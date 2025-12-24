<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Custom')) {
	class SQUICK_Field_Custom extends SQUICK_Field
	{
		function render()
		{
			$field_classes = array('squick-field');
			$field_classes[] = 'squick-field-' . $this->getType();
			$template_file = $this->_setting['template'];
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo esc_attr(join(' ', $field_classes)) ?>">
				<?php
				extract(array(
					'field' => $this
				));
				include $template_file;
				?>
			</div>
			<?php
		}
	}
}