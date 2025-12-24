<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'SQUICK_Field_DateTimePicker' ) ) {
	class SQUICK_Field_DateTimePicker extends SQUICK_Field {
		public function enqueue() {
			wp_enqueue_script( 'datetimepicker', SQUICK()->helper()->getAssetUrl( 'fields/datetimepicker/assets/jquery.datetimepicker.full.min.js' ), array(), '1.3.4', true );
			wp_enqueue_style( 'datetimepicker', SQUICK()->helper()->getAssetUrl( 'fields/datetimepicker/assets/jquery.datetimepicker.min.css' ), array(), '1.3.4' );
			wp_enqueue_script( SQUICK()->assetsHandle( 'field-datetimepicker'), SQUICK()->helper()->getAssetUrl( 'fields/datetimepicker/assets/datetimepicker.min.js' ), array(), SQUICK()->pluginVer(), true );
			wp_localize_script(SQUICK()->assetsHandle( 'field-datetimepicker'), 'squick_datetimepicker_variable', array(
				'locale' => get_locale()
			));
		}

		public function renderContent() {
			$field_value = $this->getFieldValue();
			$opt_default = array(
			);
			$option      = isset( $this->_setting['js_options'] ) ? $this->_setting['js_options'] : array();
			$option      = wp_parse_args( $option, $opt_default );
			?>
			<div class="squick-field-text-inner">
				<input autocomplete="off" type="text" class="squick-date-time-picker"
				       data-options="<?php echo esc_attr(wp_json_encode( $option )) ?>" data-field-control
				       name="<?php $this->theInputName(); ?>" value="<?php echo esc_attr( $field_value ) ?>"/>
			</div>
			<?php
		}
	}
}