<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Sorter')) {
	class SQUICK_Field_Sorter extends SQUICK_Field
	{
		/**
		 * Enqueue field resources
		 */
		function enqueue() {
			wp_enqueue_script(SQUICK()->assetsHandle('field_sorter'), SQUICK()->helper()->getAssetUrl('fields/sorter/assets/sorter.min.js'), array(), SQUICK()->pluginVer(), true);
			wp_enqueue_style(SQUICK()->assetsHandle('field_sorter'), SQUICK()->helper()->getAssetUrl('fields/sorter/assets/sorter.min.css'), array(), SQUICK()->pluginVer());
		}

		function renderContent()
		{
			$field_value = $this->getFieldValue();
			$default = $this->getDefault();
			$field_value = wp_parse_args($field_value, $default);

			$fielKeyValue = array();
			foreach ($default as $group_key => $group) {
				foreach ($group as $item_key => $item_value) {
					$fielKeyValue[$item_key] = $item_value;
				}
			}
			foreach ($field_value as $group_key => $group) {
				if (!isset($default[$group_key])) {
					 unset($field_value[$group_key]);
				}
			}

			$field_value = apply_filters('squick_sorter_value',$field_value,$this);
			?>
			<div class="squick-field-sorter-inner squick-clearfix">
				<?php foreach ($field_value as $group_key => $group): ?>
					<div class="squick-field-sorter-group" data-group="<?php echo esc_attr($group_key); ?>">
						<div class="squick-field-sorter-title"><?php echo esc_html($group_key); ?></div>
                        <div class="squick-field-sorter-items">
                            <?php foreach ($group as $item_key => $item_value): ?>
                                <?php if ($item_key === '__no_value__') { continue; } ?>
                                <?php $item_value = isset($fielKeyValue[$item_key]) ? $fielKeyValue[$item_key] : $item_value; ?>
                                <div class="squick-field-sorter-item" data-id="<?php echo esc_attr($item_key); ?>">
                                    <input data-field-control="" type="hidden"
                                           name="<?php $this->theInputName(); ?>[<?php echo esc_attr($group_key); ?>][<?php echo esc_attr($item_key); ?>]"
                                           value="<?php echo esc_attr($item_value); ?>"/>
                                    <?php echo esc_html($item_value); ?>
                                </div>
                            <?php endforeach;?>
                            <input data-field-control="" type="hidden"
                                   name="<?php $this->theInputName(); ?>[<?php echo esc_attr($group_key); ?>][__no_value__]"
                                   value="__no_value__"/>
                        </div>
					</div>
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
			$default = array(
				'enable' => array(),
				'disable' => array()
			);
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();
			if (empty($field_default)) {
				$field_default = $default;
			}

			return $field_default;
		}
	}
}