<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $css_class_field
 */
$request_keyword = isset($_GET['keyword']) ? amotos_clean(wp_unslash($_GET['keyword']))  : '';
$keyword_field = amotos_get_option('keyword_field','veh_address');

if( $keyword_field == 'veh_title' ) {
	$keyword_placeholder = esc_html__('Enter Keyword...','auto-moto-stock');

} else if( $keyword_field == 'veh_city_state_county' ) {
	$keyword_placeholder = esc_html__('Search City, State','auto-moto-stock');

} else if( $keyword_field == 'veh_address' ) {
	$keyword_placeholder = esc_html__('Enter an address, zip or Vehicle ID','auto-moto-stock');

} else {
	$keyword_placeholder = esc_html__('Enter Keyword...','auto-moto-stock');
}

?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
	<input type="text" class="form-control search-field" data-default-value="" value="<?php echo esc_attr($request_keyword); ?>" name="keyword" placeholder="<?php echo esc_attr($keyword_placeholder)?>">
</div>
