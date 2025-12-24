<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Dimension')) {
	class SQUICK_Field_Dimension extends SQUICK_Field
	{
		function enqueue() {
			wp_enqueue_script(SQUICK()->assetsHandle('field_dimension'), SQUICK()->helper()->getAssetUrl('fields/dimension/assets/dimension.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_dimension'), SQUICK()->helper()->getAssetUrl('fields/dimension/assets/dimension.min.css'), array(), SQUICK()->pluginVer());
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$is_width = isset($this->_setting['width']) ? $this->_setting['width'] : true;
			$is_height = isset($this->_setting['height']) ? $this->_setting['height'] : true;
			$default = $this->getDefault();
			$field_value = wp_parse_args($field_value, $default);

			?>
			<div class="squick-field-dimension-inner">
				<?php if ($is_width): ?>
					<div class="squick-dimension-item">
						<div class="dashicons dashicons-leftright"></div>
						<input data-field-control="" class="squick-dimension" type="number" placeholder="<?php esc_attr_e('Width', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[width]" value="<?php echo esc_attr($field_value['width']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_height): ?>
					<div class="squick-dimension-item">
						<div class="dashicons dashicons-leftright squick-rotate-90deg" style="margin-right: 1px"></div>
						<input data-field-control="" class="squick-dimension" type="number" placeholder="<?php esc_attr_e('Height', 'auto-moto-stock'); ?>"
						       name="<?php $this->theInputName(); ?>[height]" value="<?php echo esc_attr($field_value['height']); ?>"/>
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
				'width' => '',
				'height' => '',
			);
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();

			return wp_parse_args($field_default, $default);
		}
	}
}