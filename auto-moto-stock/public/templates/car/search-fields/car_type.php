<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_type = isset($_GET['type']) ? amotos_clean(wp_unslash($_GET['type']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="type" title="<?php esc_attr_e('Vehicle Type', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <?php amotos_get_taxonomy_slug('car-type', $request_type); ?>
        <option
            value="" <?php selected('',$request_type)?>>
            <?php esc_html_e('All Types', 'auto-moto-stock') ?>
        </option>
    </select>
</div>