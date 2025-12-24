<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$number = (!empty($instance['number'])) ? absint($instance['number']) : 3;
if (!$number)
    $number = 3;

$args = array(
    'post_type' => 'car',
    'ignore_sticky_posts' => true,
    'posts_per_page' => $number,
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
    'post_status' => 'publish',
);
$filter_by_manager= (!empty($instance['filter_by_manager'])) ? ($instance['filter_by_manager']) : '0';
if ($filter_by_manager==1 && is_single() &&  get_post_type() == 'manager') {
    $manager_id = get_the_ID();
    $manager_post_meta_data = get_post_custom( $manager_id);
    $manager_user_id = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_user_id']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_user_id'][0] : '';
    $user = get_user_by('id', $manager_user_id);
    if (empty($user)) {
        $manager_user_id = 0;
    }
    $args['meta_query'] = array();
    if (!empty($manager_user_id) && !empty($manager_id)) {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => AMOTOS_METABOX_PREFIX . 'car_manager',
                'value' => explode(',', $manager_id),
                'compare' => 'IN'
            ),
            array(
                'key' => AMOTOS_METABOX_PREFIX . 'car_author',
                'value' => explode(',', $manager_user_id),
                'compare' => 'IN'
            )
        );
    } else {
        if (!empty($manager_user_id)) {
            $args['author'] = $manager_user_id;
        } else if (!empty($manager_id)) {
            $args['meta_query'] = array(
                array(
                    'key' => AMOTOS_METABOX_PREFIX . 'car_manager',
                    'value' => explode(',', $manager_id),
                    'compare' => 'IN'
                )
            );
        }
    }
}
$args['meta_query'][] = array(
    'key' => AMOTOS_METABOX_PREFIX . 'car_featured',
    'value' => true,
    'compare' => '=',
);
$data = new WP_Query($args);

$owl_attributes = array(
	'items' => 1,
	'dots' => true,
	'nav' => false,
	'autoplay' => false,
	'loop' => true,
	'responsive' => array()
);
?>
<div class="list-featured-cars amotos-car car-grid">
    <div class="owl-carousel amotos__owl-carousel" data-plugin-options="<?php echo esc_attr(wp_json_encode($owl_attributes)) ?>">
        <?php if ($data->have_posts()):
            while ($data->have_posts()): $data->the_post();
                $image_size = apply_filters('amotos_widget_featured_cars_image_size','370x180') ;
                ?>
                <div class="car-item">
                    <div class="car-inner">
                        <?php amotos_template_loop_car_thumbnail(array(
                            'image_size' => $image_size
                        ));?>
                        <div class="car-item-content">
	                        <?php amotos_template_loop_car_title(); ?>
	                        <?php amotos_template_loop_car_price(); ?>
	                        <?php amotos_template_loop_car_location(); ?>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        else: ?>
            <?php amotos_get_template('loop/content-none.php'); ?>
        <?php endif; ?>
    </div>
    <?php if(isset($instance['link']) && !empty($instance['link'])):?>
    <a class="amotos-link-more" href="<?php echo esc_url($instance['link']) ?>"><?php esc_html_e('More...', 'auto-moto-stock'); ?></a>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();