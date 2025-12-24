<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $atts
 * @var $enable_saved_search
 * @var $parameters
 * @var $search_query
 */
$data_target='#amotos_save_search_modal';
if (!is_user_logged_in()){
    $data_target='#amotos_signin_modal';
}
?>
<div class="amotos_car-advanced-search-form-wrap">
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
    echo amotos_do_shortcode( 'amotos_car_advanced_search', $atts ); ?>
    <?php if (filter_var($enable_saved_search, FILTER_VALIDATE_BOOLEAN)): ?>
        <div class="advanced-saved-searches">
            <button type="button" class="btn btn-primary btn-xs btn-save-search" data-toggle="modal" data-target="<?php echo esc_attr($data_target); ?>">
                <?php esc_html_e( 'Save Search', 'auto-moto-stock' ) ?></button>
        </div>
        <?php amotos_get_template('global/save-search-modal.php',array('parameters'=>$parameters,'search_query'=>$search_query)); ?>
    <?php endif; ?>
</div>
