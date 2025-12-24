<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Info')) {
	class SQUICK_Field_Info extends SQUICK_Field
	{
		function enqueue()
		{
			wp_enqueue_style(SQUICK()->assetsHandle('field_info'), SQUICK()->helper()->getAssetUrl('fields/info/assets/info.min.css'), array(), SQUICK()->pluginVer());
		}
		function render()
		{
			$desc = isset($this->_setting['desc']) ? $this->_setting['desc']: '';
			$title = isset($this->_setting['title']) ? $this->_setting['title']: '';
			$class_inner = array('squick-info-inner');
			if (isset($this->_setting['style'])) {
				$class_inner[] = 'squick-info-style-' . $this->_setting['style'];
			}

			$icon = isset($this->_setting['icon']) ? $this->_setting['icon'] : '';
			if ($icon === true) {
				if (isset($this->_setting['style'])) {
					switch ($this->_setting['style']) {
						case 'info':
							$icon = 'dashicons-info';
							break;
						case 'warning':
							$icon = 'dashicons-shield-alt';
							break;
						case 'success':
							$icon = 'dashicons-yes';
							break;
						case 'error':
							$icon = 'dashicons-dismiss';
							break;
					}
				}
				else {
					$icon = 'dashicons-wordpress';
				}
			}

			if (isset($this->_setting['icon'])) {
				$class_inner[] = 'squick-info-has-icon squick-clearfix';
			}
			$field_classes = array('squick-field');
			$field_classes[] = 'squick-field-' . $this->getType();
			$field_classes[] = 'squick-layout-' . $this->getLayout();
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo esc_attr(join(' ', $field_classes)); ?>">
				<div class="<?php echo esc_attr(join(' ', $class_inner)) ?>">
					<div class="squick-info-content">
						<?php if (isset($this->_setting['icon'])): ?>
							<span class="squick-info-content-icon dashicons <?php echo esc_attr($icon); ?>"></span>
						<?php endif;?>
						<?php if (!empty($title)): ?>
							<div class="squick-info-content-title">
								<?php echo wp_kses_post($title); ?>
							</div>
						<?php endif;?>
						<?php if (!empty($desc)): ?>
							<div class="squick-info-content-desc">
								<?php echo wp_kses_post($desc); ?>
							</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}