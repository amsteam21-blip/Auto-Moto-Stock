<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 * @var $request_seats
 */
$request_seats = isset($_GET['seats']) ? amotos_clean(wp_unslash($_GET['seats']))  : '';
$seats_list = amotos_get_option('seats_list','1,2,3,4,5,6,7,8,9,10');
$seats_array = explode( ',', $seats_list );
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="seats" title="<?php esc_attr_e('Seats', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Seats', 'auto-moto-stock') ?>
        </option>
	    <?php if (is_array($seats_array) && !empty($seats_array) ): ?>
		    <?php foreach ($seats_array as $n) : ?>
			    <option <?php selected($request_seats,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo esc_html($n)?></option>
		    <?php endforeach; ?>
	    <?php endif; ?>
    </select>
</div>