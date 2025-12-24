<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_neighborhood = isset($_GET['neighborhood']) ? amotos_clean(wp_unslash($_GET['neighborhood']))  : '';
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="neighborhood" class="amotos-car-neighborhood-ajax search-field form-control" title="<?php esc_attr_e('Neighborhoods', 'auto-moto-stock'); ?>" data-selected="<?php echo esc_attr($request_neighborhood); ?>" data-default-value="">
        <?php amotos_get_taxonomy_slug('car-neighborhood', $request_neighborhood); ?>
        <option value="" <?php selected('', $request_neighborhood); ?>>
            <?php esc_html_e('All Neighborhoods', 'auto-moto-stock'); ?>
        </option>
    </select>
</div>