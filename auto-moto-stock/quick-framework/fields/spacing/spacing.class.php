<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Spacing')) {
	class SQUICK_Field_Spacing extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_script(SQUICK()->assetsHandle('field_spacing'), SQUICK()->helper()->getAssetUrl('fields/spacing/assets/spacing.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_spacing'), SQUICK()->helper()->getAssetUrl('fields/spacing/assets/spacing.min.css'), array(), SQUICK()->pluginVer());
		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();
			$default = $this->getDefault();
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$field_value = wp_parse_args($field_value, $default);

			$is_left = isset($this->_setting['left']) ? $this->_setting['left'] : true;
			$is_right = isset($this->_setting['right']) ? $this->_setting['right'] : true;
			$is_top = isset($this->_setting['top']) ? $this->_setting['top'] : true;
			$is_bottom = isset($this->_setting['bottom']) ? $this->_setting['bottom'] : true;
			?>
			<div class="squick-field-spacing-inner">
				<?php if ($is_left): ?>
					<div class="squick-spacing-item">
						<div class="dashicons dashicons-arrow-left-alt"></div>
						<input data-field-control="" class="squick-spacing" type="number" placeholder="<?php esc_attr_e('Left', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[left]" value="<?php echo esc_attr($field_value['left']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_right): ?>
					<div class="squick-spacing-item">
						<div class="dashicons dashicons-arrow-right-alt"></div>
						<input data-field-control="" class="squick-spacing" type="number" placeholder="<?php esc_attr_e('Right', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[right]" value="<?php echo esc_attr($field_value['right']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_top): ?>
					<div class="squick-spacing-item">
						<div class="dashicons dashicons-arrow-up-alt"></div>
						<input data-field-control="" class="squick-spacing" type="number" placeholder="<?php esc_attr_e('Top', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[top]" value="<?php echo esc_attr($field_value['top']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_bottom): ?>
					<div class="squick-spacing-item">
						<div class="dashicons dashicons-arrow-down-alt"></div>
						<input data-field-control="" class="squick-spacing" type="number" placeholder="<?php esc_attr_e('Bottom', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[bottom]" value="<?php echo esc_attr($field_value['bottom']); ?>"/>
					</div>
				<?php endif;?>
			</div>

		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function getDefault() {
			$default = array(
				'left' => '',
				'right' => '',
				'top' => '',
				'bottom' => '',
			);
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();

			return wp_parse_args($field_default, $default);
		}
	}
}