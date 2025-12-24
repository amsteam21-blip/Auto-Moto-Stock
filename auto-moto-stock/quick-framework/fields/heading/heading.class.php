<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Heading')) {
	class SQUICK_Field_Heading extends SQUICK_Field
	{
		function enqueue()
		{
			wp_enqueue_style(SQUICK()->assetsHandle('field_heading'), SQUICK()->helper()->getAssetUrl('fields/heading/assets/heading.min.css'), array(), SQUICK()->pluginVer());
		}
		function render()
		{
			$title = isset($this->_setting['title']) ? $this->_setting['title']: '';
			$class_inner = array('squick-heading-inner');
			if (isset($this->_setting['style'])) {
				$class_inner[] = 'squick-heading-style-' . $this->_setting['style'];
			}

			$field_classes = array('squick-field');
			$field_classes[] = 'squick-field-' . $this->getType();
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo esc_attr(join(' ', $field_classes)); ?>">
				<div class="<?php echo esc_attr(join(' ', $class_inner)) ?>">
					<div class="squick-heading-content">
						<?php if (!empty($title)): ?>
							<h3><?php echo wp_kses_post($title); ?></h3>
						<?php endif;?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}