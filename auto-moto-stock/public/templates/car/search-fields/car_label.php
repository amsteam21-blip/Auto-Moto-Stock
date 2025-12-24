<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_label = isset($_GET['label']) ? amotos_clean(wp_unslash($_GET['label']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="label" title="<?php esc_attr_e('Label', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <?php amotos_get_taxonomy_slug('car-label', $request_label); ?>
        <option value="" <?php selected('',$request_label); ?>>
            <?php esc_html_e('All Labels', 'auto-moto-stock') ?></option>
    </select>
</div>