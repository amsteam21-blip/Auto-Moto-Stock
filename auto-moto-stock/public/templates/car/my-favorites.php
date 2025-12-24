<?php
/**
 * @var $favorites
 * @var $max_num_pages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    return amotos_get_template_html('global/access-denied.php', array('type' => 'not_login'));

    return;
}
wp_enqueue_style('dashicons');

$wrapper_classes = array(
    'amotos-car',
    'clearfix',
    'car-grid',
    'col-gap-10',
    'columns-3',
    'columns-md-2',
    'columns-sm-2',
    'columns-xs-1'
);
$car_item_class = apply_filters('amotos_my_favorites_car_item_class',array(
    'amotos-item-wrap',
    'mg-bottom-10'
));
$custom_car_image_size = amotos_get_option('archive_car_image_size', amotos_get_loop_car_image_size_default());
$wrapper_class = join(' ', apply_filters('amotos_my_favorites_wrapper_classes',$wrapper_classes) );
?>
<div class="row amotos-user-dashboard">
    <div class="col-lg-3 amotos-dashboard-sidebar">
        <?php amotos_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_favorites')); ?>
    </div>
    <div class="col-lg-9 amotos-dashboard-content">
        <div class="card amotos-card amotos-my-favorites">
            <div class="card-header"><h5 class="card-title m-0"><?php echo esc_html__('My Favorites', 'auto-moto-stock'); ?></h5></div>
            <div class="card-body">
                <div class="<?php echo esc_attr($wrapper_class)  ?>">
                    <?php if ($favorites->have_posts()) :
                        while ($favorites->have_posts()): $favorites->the_post(); ?>
                            <?php amotos_get_template('content-car.php', array(
                                'car_item_class' => $car_item_class,
                                'custom_car_image_size' => $custom_car_image_size
                            )); ?>
                        <?php endwhile;
                    else: ?>
                        <?php amotos_get_template('loop/content-none.php'); ?>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <?php
                    $max_num_pages = $favorites->max_num_pages;
                    amotos_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>