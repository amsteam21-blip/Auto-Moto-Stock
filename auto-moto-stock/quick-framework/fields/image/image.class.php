<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Image')) {
	class SQUICK_Field_Image extends SQUICK_Field
	{
		function enqueue()
		{
			wp_enqueue_script(SQUICK()->assetsHandle('field_image'));
			wp_enqueue_style(SQUICK()->assetsHandle('field_image'));
		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$default = $this->getDefault();
			$field_value = wp_parse_args($field_value, $default);

			$thumb_url = $field_value['url'];
			$image_attributes = wp_get_attachment_image_src($field_value['id']);
			if (!empty($image_attributes) && is_array($image_attributes)) {
				$thumb_url = $image_attributes[0];
			}
			?>
			<div class="squick-field-image-inner squick-clearfix">
				<input data-field-control="" type="hidden"
				       class="squick-image-id"
				       name="<?php $this->theInputName(); ?>[id]"
				       value="<?php echo esc_attr($field_value['id']); ?>"/>
				<div class="squick-image-preview">
					<div class="centered">
						<img src="<?php echo esc_url($thumb_url); ?>" alt="" style="<?php echo esc_attr(empty($thumb_url) ? 'display:none' : '') ?>"/>
					</div>
				</div>
				<div class="squick-image-info">
					<input data-field-control="" type="text"
					       class="squick-image-url" placeholder="<?php esc_attr_e('No image', 'auto-moto-stock'); ?>"
					       name="<?php $this->theInputName(); ?>[url]"
					       value="<?php echo esc_url($field_value['url']); ?>"/>
					<button type="button" class="button squick-image-choose-image"><?php esc_html_e('Choose Image', 'auto-moto-stock'); ?></button>
					<button type="button"
					        class="button squick-image-remove"><?php esc_html_e('Remove', 'auto-moto-stock'); ?></button>
				</div>
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
				'id'  => 0,
				'url' => ''
			);

			if (isset($this->_setting['default'])) {
				if (is_array($this->_setting['default'])) {
					$default = $this->_setting['default'];
				}
				elseif (is_numeric($this->_setting['default'])) {
					$default['id'] = $this->_setting['default'];
					$default['url'] = wp_get_attachment_url($default['id']);
				}
				else {
					$default['url'] = $this->_setting['default'];
					$default['id'] = SQUICK()->helper()->getAttachmentIdByUrl($default['url']);
				}
			}

			return $default;
		}
	}
}