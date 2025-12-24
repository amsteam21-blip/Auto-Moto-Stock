<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('SQUICK_Field_Repeater')) {
	class SQUICK_Field_Repeater extends  SQUICK_Field {
		public function renderContentWrapper() {
			if ($this->_value === null) {
				$this->_value = $this->getFieldDefault();
			}
			?>
			<div class="squick-field-content">
				<?php $this->repeaterLabel(); ?>
				<?php if ($this->isClone()): ?>
					<?php
					$count_clone = !is_array($this->_value) ? 1 : count($this->_value);
					$count_clone = $count_clone > 0 ? $count_clone : 1;
					?>
					<div class="squick-field-clone-wrapper squick-field-repeater-clone <?php echo esc_attr($this->isSort() ? 'squick-field-clone-sortable' : ''); ?>">
						<?php for ($index = 0; $index < $count_clone; $index++): ?>
							<div class="squick-field-clone-item squick-field-repeater-clone-item" data-clone-index="<?php echo esc_attr($index); ?>">
								<div class="squick-field-content-inner">
									<?php
									$this->_cloneIndex = $index;
									if ($this->isSort()) {
										$this->renderCloneSortButton();
									}
									$this->repeaterContent($index);
									$this->renderCloneRemoveButton();
									?>
								</div>
							</div>
						<?php endfor; ?>
					</div>
					<?php $this->repeaterDescription(); ?>
					<?php $this->renderCloneAddButton(); ?>
				<?php else:; ?>
					<div class="squick-field-content-inner">
						<?php $this->repeaterContent(''); ?>
						<?php $this->repeaterDescription(); ?>
					</div>
				<?php endif;?>
			</div>
		<?php
		}

		public function renderStart() {
			$col = $this->getCol();
			$this->_setting['col'] = '';
			parent::renderStart();
			$this->_setting['col'] = $col;
		}

		public function repeaterLabel() {
			$header_classes = array('squick-row', 'squick-field-repeater-header');
			if ($this->isClone()) {
				$header_classes[] = 'squick-field-repeater-is-clone';
			}
			if ($this->isSort()) {
				$header_classes[] = 'squick-field-repeater-is-sortable';
			}
			?>
			<div class="<?php echo esc_attr(join(' ', $header_classes)) ; ?>">
				<?php
			foreach ($this->_setting['fields'] as &$setting) {
				if (empty($setting['type'])) {
					continue;
				}
				if (in_array($setting['type'], array('row', 'group', 'repeater', 'panel'))) {
					continue;
				}
				$col = isset($setting['col']) ? $setting['col'] :  $this->getCol();
				$title = isset($setting['title']) ? $setting['title'] : '';
				$subtitle = isset($setting['subtitle']) ? $setting['subtitle'] : '';
				$this->repeaterLabelField($col, $title, $subtitle);
			}
			?>
			</div><!-- /.squick-row -->
			<?php
		}
		public function repeaterLabelField($col, $title, $subtitle) {
			?>
			<div class="squick-col squick-col-<?php echo esc_attr($col); ?>">
				<div class="squick-field-repeater-title"><?php echo esc_attr($title); ?></div>
				<?php if (!empty($subtitle)): ?>
					<div class="squick-field-repeater-subtitle">
						<?php echo esc_html($subtitle); ?>
					</div>
				<?php endif;?>
			</div>
			<?php
		}


		public function repeaterContent($repeater_index) {
			?>
			<div class="squick-row">
			<?php
			foreach ($this->_setting['fields'] as &$setting) {
				if (empty($setting['type'])) {
					continue;
				}
				if (in_array($setting['type'], array('row', 'group', 'repeater', 'panel'))) {
					continue;
				}

				$field = SQUICK()->helper()->createField($setting['type']);

				$field->_parentType = $this->getType();
				$field->_panelID = $this->_panelID;
				$field->_panelIndex = $this->_panelIndex;
				$field->_repeaterID = isset($this->_setting['id']) ? $this->_setting['id'] : '';
				$field->_repeaterIndex = $repeater_index;
				$field->_colDefault = $this->getCol();
				$field->_setting = &$setting;
				$field->_panel_clone = $this->_panel_clone;
				$field->_repeater_clone = $this->isClone();

				$id = isset($setting['id']) ? $setting['id'] : '';

				if ($repeater_index !== '') {
					if (!empty($id) && isset($this->_value[$repeater_index]) && isset($this->_value[$repeater_index][$id])) {
						$field->_value = &$this->_value[$repeater_index][$id];
					}
					else {
						$field->_value = null;
					}
				}
				else {
					if (!empty($id) && isset($this->_value[$id])) {
						$field->_value = &$this->_value[$id];
					}
					else {
						$field->_value = null;
					}
				}

				$field->render();
			}
			?>
			</div><!-- /.squick-row -->
			<?php
		}

		public function repeaterDescription() {
			$header_classes = array('squick-row', 'squick-field-repeater-footer');
			if ($this->isClone()) {
				$header_classes[] = 'squick-field-repeater-is-clone';
			}
			if ($this->isSort()) {
				$header_classes[] = 'squick-field-repeater-is-sortable';
			}
			?>
			<div class="<?php echo esc_attr(join(' ', $header_classes)); ?>">
				<?php
				foreach ($this->_setting['fields'] as &$setting) {
					if (empty($setting['type'])) {
						continue;
					}
					if (in_array($setting['type'], array('row', 'group', 'repeater', 'panel'))) {
						continue;
					}
					$col = isset($setting['col']) ? $setting['col'] :  $this->getCol();
					$desc = isset($setting['desc']) ? $setting['desc'] : '';
					if (!empty($desc)) {
						echo sprintf('<div class="squick-col squick-col-%s"><p class="squick-desc">%s</p></div>', esc_attr($col), esc_html($desc));
					}
				}
				?>
			</div><!-- /.squick-row -->
		<?php
		}

		public function getDefault() {
			return isset($this->_setting['default'])
				? $this->_setting['default']
				: array();
		}
		public function getFieldDefault() {
			return $this->getDefault();
		}

	}
}