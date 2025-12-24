<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var $field SQUICK_Field
 */
global $post;
$current_post = 0;
if ( isset( $post ) && isset( $post->ID ) ) {
	$current_post = $post->ID;
}
$field_count = $field->getFieldValue();
if ( ! is_numeric( $field_count ) ) {
	$field_count = 1;
}

$field_titles = array();
$field_values = array();
if ( $current_post > 0 ) {
	$field_titles = get_post_meta( $current_post, 'auto_moto_additional_styling_title', true );
	$field_values = get_post_meta( $current_post, 'auto_moto_additional_styling_value', true );
}

?>
<div id="auto_moto_additional_stylings" class="squick-field squick-field-additional-details">
	<input type="hidden" name="<?php echo esc_attr( $field->getName() ) ?>"
	       value="<?php echo esc_attr( $field_count ) ?>" class="total">
	<div class="squick-label">
		<div class="squick-title"><?php echo esc_html( $field->getFieldTitle() ) ?></div>
	</div>

	<table>
		<thead>
			<tr>
				<th class="sort"></th>
				<th class="title"><?php echo esc_html__( 'Title', 'auto-moto-stock' ) ?></th>
				<th class="value"><?php echo esc_html__( 'Value', 'auto-moto-stock' ) ?></th>
				<th class="remove"></th>
			</tr>
		</thead>
		<tbody>
			<?php for ( $i = 0; $i < $field_count; $i ++ ): ?>
				<tr>
					<td class="sort">
						<span><i class="dashicons dashicons-menu"></i></span>
					</td>
					<td class="title">
						<input type="text" name="auto_moto_additional_styling_title[<?php echo esc_attr( $i ) ?>]"
						       value="<?php echo isset( $field_titles[ $i ] ) ? esc_attr( $field_titles[ $i ] ) : '' ?>">
					</td>
					<td class="value">
						<input type="text" name="auto_moto_additional_styling_value[<?php echo esc_attr( $i ) ?>]"
						       value="<?php echo isset( $field_values[ $i ] ) ? esc_attr( $field_values[ $i ] ) : '' ?>">
					</td>
					<td class="remove">
						<i class="dashicons dashicons-dismiss"></i>
					</td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
	<p>
		<button class="button button-secondary"
		        type="button"><?php echo esc_html__( '+ Add more', 'auto-moto-stock' ) ?></button>
	</p>
</div>
