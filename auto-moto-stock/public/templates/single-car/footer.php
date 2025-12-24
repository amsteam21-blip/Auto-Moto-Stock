<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $car_id
 * @var $enable_create_date
 * @var $enable_views_count
 * @var $total_views
 */
global $post;

$wrapper_classes = array(
    'single-car-element',
    'car-info-footer',
    'amotos__single-car-element',
    'amotos__single-car-info-footer'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_footer_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-car-element">
        <?php if ($enable_create_date): ?>
            <span class="amotos__date">
		        <i class="fa fa-calendar"></i> <?php echo esc_html(get_the_time(get_option('date_format'), $car_id)); ?>
	        </span>
        <?php endif; ?>

        <?php if ($enable_views_count): ?>
            <span class="amotos__views-count">
		        <i class="fa fa-eye"></i>
                <?php
                /* translators: %s: Number of reviews. */
                echo esc_html(sprintf(_n('%s view', '%s views', $total_views, 'auto-moto-stock'), amotos_get_format_number($total_views)));
                ?>
	        </span>
        <?php endif; ?>
    </div>
</div>