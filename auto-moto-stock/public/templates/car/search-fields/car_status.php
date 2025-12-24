<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_status = isset($_GET['status']) ? amotos_clean(wp_unslash($_GET['status']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="status" title="<?php esc_attr_e('Status', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <?php amotos_get_car_status_search_slug($request_status); ?>
	    <option value="" <?php selected('',$request_status) ?>>
		    <?php esc_html_e('All Status', 'auto-moto-stock') ?>
	    </option>
    </select>
</div>