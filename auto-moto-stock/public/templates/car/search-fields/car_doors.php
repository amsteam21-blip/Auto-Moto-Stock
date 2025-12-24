<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_doors = isset($_GET['doors']) ? amotos_clean(wp_unslash($_GET['doors']))  : '';
$doors_list = amotos_get_option('doors_list','1,2,3,4,5,6,7,8,9,10');
$doors_array = explode( ',', $doors_list );
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="doors" title="<?php esc_attr_e('Doors', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Doors', 'auto-moto-stock') ?>
        </option>
	    <?php if (is_array($doors_array) && !empty($doors_array) ): ?>
		    <?php foreach ($doors_array as $n) : ?>
			    <option <?php selected($request_doors,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo esc_html($n)?></option>
		    <?php endforeach; ?>
	    <?php endif; ?>
    </select>
</div>