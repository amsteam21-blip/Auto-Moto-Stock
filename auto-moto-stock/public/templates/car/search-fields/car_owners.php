<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 */
$request_owners = isset($_GET['owners']) ? amotos_clean(wp_unslash($_GET['owners']))  : '';
$owners_list = amotos_get_option('owners_list','1,2,3,4,5,6,7,8,9,10');
$owners_array = explode( ',', $owners_list );
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="owners" title="<?php esc_attr_e('Owners', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Owners', 'auto-moto-stock') ?>
        </option>
	    <?php if (is_array($owners_array) && !empty($owners_array) ): ?>
	        <?php foreach ($owners_array as $n) : ?>
			    <option <?php selected($request_owners,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo esc_html($n)?></option>
	        <?php endforeach; ?>
	    <?php endif; ?>
    </select>
</div>