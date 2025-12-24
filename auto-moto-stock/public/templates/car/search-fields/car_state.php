<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_state = isset($_GET['state']) ? amotos_clean(wp_unslash($_GET['state']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="state" class="amotos-car-state-ajax search-field form-control" title="<?php esc_attr_e('States', 'auto-moto-stock'); ?>" data-selected="<?php echo esc_attr($request_state); ?>" data-default-value="">
        <?php amotos_get_taxonomy_slug('car-state', $request_state); ?>
        <option value="" <?php selected('', $request_state)?>>
            <?php esc_html_e('All States', 'auto-moto-stock'); ?>
        </option>
    </select>
</div>