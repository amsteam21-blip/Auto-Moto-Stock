<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Icon')) {
	class SQUICK_Field_Icon extends SQUICK_Field
	{
		function enqueue() {
			SQUICK_Core_Icons_Popup::getInstance()->enqueue();
			wp_enqueue_script(SQUICK()->assetsHandle('field_icon'), SQUICK()->helper()->getAssetUrl('fields/icon/assets/icon.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_icon'), SQUICK()->helper()->getAssetUrl('fields/icon/assets/icon.min.css'), array(), SQUICK()->pluginVer());
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			?>
			<div class="squick-field-icon-inner">
				<input data-field-control="" type="hidden"
				       name="<?php $this->theInputName(); ?>"
				       value="<?php echo esc_attr($field_value); ?>"/>
				<div class="squick-field-icon-item"
				     data-icon-title="<?php esc_attr_e('Select icon', 'auto-moto-stock'); ?>"
				     data-icon-remove="<?php esc_attr_e('Remove icon', 'auto-moto-stock'); ?>"
				     data-icon-search="<?php esc_attr_e('Search icon...', 'auto-moto-stock'); ?>">
					<div class="squick-field-icon-item-info">
						<span class="<?php echo esc_attr($field_value); ?>"></span>
						<div class="squick-field-icon-item-label"><?php esc_html_e('Set Icon', 'auto-moto-stock'); ?></div>
                        <a href="javascript:;" title="<?php esc_attr_e('Remove icon', 'auto-moto-stock'); ?>" class="squick-field-icon-remove"><i class="dashicons dashicons-no-alt"></i></a>
					</div>
				</div>
			</div>
		<?php
		}
	}
}