<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Sortable')) {
	class SQUICK_Field_Sortable extends SQUICK_Field
	{
		/**
		 * Enqueue field resources
		 */
		function enqueue() {
			wp_enqueue_script(SQUICK()->assetsHandle('field_sortable'), SQUICK()->helper()->getAssetUrl('fields/sortable/assets/sortable.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_sortable'), SQUICK()->helper()->getAssetUrl('fields/sortable/assets/sortable.min.css'), array(), SQUICK()->pluginVer());
		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$sort = array();
			if (isset($field_value['sort_order'])) {
				$sort = explode('|', $field_value['sort_order']);
			}

			if (is_array($this->_setting['options'])) {
				foreach ($this->_setting['options'] as $key => $value) {
					if (!in_array($key, $sort)) {
						$sort[] = $key;
					}
				}

				foreach ($sort as $key => $value) {
					if (!isset($this->_setting['options'][$value])) {
						unset($field_value[$key]);
					}
				}
			}

			?>
			<div class="squick-field-sortable-inner squick-clearfix">
				<?php foreach ($sort as $sortValue): ?>
					<?php if (isset($this->_setting['options'][$sortValue])): ?>
						<div class="squick-field-sortable-item">
							<i class="dashicons dashicons-menu"></i>
							<label>
								<input type="checkbox"
								       data-field-control=""
								       data-uncheck-novalue="true"
								       name="<?php $this->theInputName(); ?>[<?php echo esc_attr($sortValue) ?>]"
								       value="<?php echo esc_attr($sortValue) ?>"
									<?php SQUICK()->helper()->theChecked($sortValue, $field_value) ?>/>
								<span><?php echo esc_html($this->_setting['options'][$sortValue]); ?></span>
							</label>
						</div>
						<input class="squick-field-sortable-sort" data-field-control="" type="hidden" name="<?php $this->theInputName(); ?>[sort_order]" value="<?php echo esc_attr(join('|', $sort)) ?>"/>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function getDefault() {
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();

			return $field_default;
		}
	}
}