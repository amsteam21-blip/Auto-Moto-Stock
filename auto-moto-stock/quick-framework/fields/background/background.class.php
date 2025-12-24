<?php
/**
 * Field Background
 *
 * @package QuickFramework
 * @subpackage Fields
 * @author stocktheme
 * @since 1.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Background')) {
	class SQUICK_Field_Background extends SQUICK_Field
	{
		function enqueue()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script(SQUICK()->assetsHandle('media'));
			wp_enqueue_script('wp-color-picker-alpha');

			wp_enqueue_style(SQUICK()->assetsHandle('field_background'), SQUICK()->helper()->getAssetUrl('fields/background/assets/background.min.css'), array(), SQUICK()->pluginVer());
			wp_enqueue_script(SQUICK()->assetsHandle('field_background'), SQUICK()->helper()->getAssetUrl('fields/background/assets/background.min.js'), array(), SQUICK()->pluginVer(), true);

		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$field_default = $this->getDefault();

			$field_value = wp_parse_args($field_value, $field_default);

			$background_repeat = array(
				'repeat'    => 'Repeat',
				'repeat-x'  => 'Repeat Horizontal',
				'repeat-y'  => 'Repeat Vertical',
				'no-repeat' => 'No Repeat',
				'inherit'   => 'Inherit',
				'initial'   => 'Initial',
			);

			$background_size = array(
				'auto'    => 'Auto',
				'length'  => 'Length',
				'cover'   => 'Cover',
				'contain' => 'Contain',
				'inherit' => 'Inherit',
				'initial' => 'Initial',
			);

			$background_position = array(
				'left top'      => 'Left Top',
				'left center'   => 'Left Center',
				'left bottom'   => 'Left Bottom',
				'center top'    => 'Center Top',
				'center center' => 'Center Center',
				'center bottom' => 'Center Bottom',
				'right top'     => 'Right Top',
				'right center'  => 'Right Center',
				'right bottom'  => 'Right Bottom',
			);

			$background_attachment = array(
				'scroll'  => 'Scroll',
				'fixed'   => 'Fixed',
				'local'   => 'Local',
				'inherit' => 'Inherit',
				'initial' => 'Initial',
			);

			$image_preview_class = '';
			if (empty($field_value)) {
				$image_preview_class = 'no-preview';
			}

			$is_background_color = isset($this->_setting['background_color']) ? $this->_setting['background_color'] : true;

			?>
			<div class="squick-field-background-inner squick-clearfix">
				<input data-field-control="" type="hidden"
				       class="squick-background-image"
				       name="<?php $this->theInputName(); ?>[background_image_id]"
				       value="<?php echo esc_attr($field_value['background_image_id']); ?>"/>
				<div class="squick-background-preview <?php echo esc_attr($image_preview_class); ?>"></div>
				<div class="squick-background-info">
					<?php if ($is_background_color) : ?>
					<div><input data-field-control=""
					            data-field-no-change="true"
					            type="text"
					            data-alpha="true"
					            class="squick-background-color" name="<?php $this->theInputName(); ?>[background_color]" value="<?php echo esc_attr($field_value['background_color']); ?>"/></div>
					<?php endif; ?>
					<div>
						<input data-field-control="" type="text"
						       placeholder="<?php esc_attr_e('No background image', 'auto-moto-stock'); ?>" class="squick-background-url"
						       name="<?php $this->theInputName(); ?>[background_image_url]"
						       value="<?php echo esc_url($field_value['background_image_url']); ?>"/>
						<button type="button" class="button squick-background-choose-image"><?php esc_html_e('Choose Image', 'auto-moto-stock'); ?></button>
						<button type="button" class="button squick-background-remove-image"><?php esc_html_e('Remove', 'auto-moto-stock'); ?></button>
					</div>
					<div class="squick-background-attr">
						<div class="squick-background-attr-title"><?php esc_html_e('Background Image Settings', 'auto-moto-stock'); ?></div>
						<select data-field-control="" name="<?php $this->theInputName(); ?>[background_repeat]" class="squick-background-repeat">
							<?php foreach ($background_repeat as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_repeat'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php $this->theInputName(); ?>[background_size]" class="squick-background-size">
							<?php foreach ($background_size as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_size'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php $this->theInputName(); ?>[background_position]" class="squick-background-position">
							<?php foreach ($background_position as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_position'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php $this->theInputName(); ?>[background_attachment]" class="squick-background-attachment">
							<?php foreach ($background_attachment as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_attachment'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
					</div>
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
				'background_color'      => '#fff',
				'background_image_id'      => 0,
				'background_image_url'      => '',
				'background_repeat'     => 'repeat',
				'background_size'       => 'contain',
				'background_position'   => 'center center',
				'background_attachment' => 'scroll',
			);
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();

			if (isset($this->_setting['default'])) {
				if (is_array($this->_setting['default'])) {
					if (isset($field_default['background_image_id']) && is_numeric($field_default['background_image_id'])) {
						$field_default = array(
							'background_image_id' => $field_default['background_image_id'],
							'background_image_url' => wp_get_attachment_url($field_default['background_image_id']),
						);
					}
					elseif (isset($field_default['background_image_url']) && !empty($field_default['background_image_url'])) {
						$field_default = array(
							'background_image_id' => SQUICK()->helper()->getAttachmentIdByUrl($field_default['background_image_url']),
							'background_image_url' => $field_default['background_image_url'],
						);
					}
				} else {
					if (is_numeric($field_default)) {
						$field_default = array(
							'background_image_id' => $field_default,
							'background_image_url' => wp_get_attachment_url($field_default),
						);
					}
					else {
						$field_default = array(
							'background_image_id' => SQUICK()->helper()->getAttachmentIdByUrl($field_default),
							'background_image_url' => $field_default,
						);
					}
				}

			}

			$default = wp_parse_args($field_default, $default);
			return $default;
		}
	}
}