<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_country = isset($_GET['country']) ? amotos_clean(wp_unslash($_GET['country']))  : '';
$countries = amotos_get_selected_countries();
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="country" class="amotos-car-country-ajax search-field form-control" title="<?php esc_attr_e('Countries', 'auto-moto-stock'); ?>" data-selected="<?php echo esc_attr($request_country); ?>" data-default-value="">
	    <option <?php selected('',$request_country) ?> value=""><?php echo esc_html__('All Countries', 'auto-moto-stock') ?></option>
	    <?php foreach ($countries as $k => $v): ?>
		    <option <?php selected($k,$request_country) ?> value="<?php echo esc_attr($k)?>"><?php echo esc_html($v)?></option>
	    <?php endforeach; ?>
    </select>
</div>