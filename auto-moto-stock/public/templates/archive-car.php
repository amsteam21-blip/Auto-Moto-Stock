<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header('amotos');
/**
 * amotos_before_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action( 'amotos_before_main_content' );
?>
<?php
global $post, $taxonomy_title, $taxonomy_name;

$custom_car_layout_style = amotos_get_option('archive_car_layout_style', 'car-grid');
$custom_car_items_amount = amotos_get_option('archive_car_items_amount', '6');
$custom_car_image_size = amotos_get_option( 'archive_car_image_size', amotos_get_loop_car_image_size_default() );
$custom_car_columns = amotos_get_option('archive_car_columns', '3');
$custom_car_columns_gap = amotos_get_option('archive_car_columns_gap', 'col-gap-30');
$custom_car_items_md = amotos_get_option('archive_car_items_md', '3');
$custom_car_items_sm = amotos_get_option('archive_car_items_sm', '2');
$custom_car_items_xs = amotos_get_option('archive_car_items_xs', '1');
$custom_car_items_mb = amotos_get_option('archive_car_items_mb', '1');

$car_item_class = array();
AMOTOS_Compare::open_session();

$ss_car_view_as = isset($_SESSION["car_view_as"]) ? sanitize_text_field(wp_unslash($_SESSION["car_view_as"])) : '';

if (in_array($ss_car_view_as, array('car-list', 'car-grid'))) {
    $custom_car_layout_style = $ss_car_view_as;
}

$wrapper_classes = array(
    'amotos-car clearfix',
    $custom_car_layout_style,
    $custom_car_columns_gap
);

if ($custom_car_layout_style == 'car-list') {
    $wrapper_classes[] = 'list-1-column';
}

if ($custom_car_columns_gap == 'col-gap-30') {
    $car_item_class[] = 'mg-bottom-30';
} elseif ($custom_car_columns_gap == 'col-gap-20') {
    $car_item_class[] = 'mg-bottom-20';
} elseif ($custom_car_columns_gap == 'col-gap-10') {
    $car_item_class[] = 'mg-bottom-10';
}

$wrapper_classes[] = 'columns-' . $custom_car_columns;
$wrapper_classes[] = 'columns-md-' . $custom_car_items_md;
$wrapper_classes[] = 'columns-sm-' . $custom_car_items_sm;
$wrapper_classes[] = 'columns-xs-' . $custom_car_items_xs;
$wrapper_classes[] = 'columns-mb-' . $custom_car_items_mb;
$car_item_class[] = 'amotos-item-wrap';

$_atts =  [
    'item_amount' => $custom_car_items_amount
];

$query_args = [];
$tax_query = [];

if (is_tax()) {
    $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $taxonomy_title = $current_term->name;
    $taxonomy_name = get_query_var('taxonomy');
    if (!empty($taxonomy_name)) {
        $tax_query[] = array(
            'taxonomy' => $taxonomy_name,
            'field' => 'slug',
            'terms' => $current_term->slug
        );
    }
}
if (count($tax_query) > 0) {
    $query_args['tax_query'] = [
        'relation' => 'AND',
        $tax_query,
    ];
}



$author_id = $manager_id = '';
$_user_id = isset($_GET['user_id']) ? amotos_clean(wp_unslash($_GET['user_id'])) : '';
$_manager_id = isset($_GET['manager_id']) ? amotos_clean(wp_unslash($_GET['manager_id'])) : '';
if (($_user_id != '' ) || ($_manager_id != '') ) {
    if ($_user_id != '') {
        $author_id = $_user_id;
        $manager_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_manager_id', $author_id);
    }
    if ($_manager_id != '') {
        $manager_id = $_manager_id;
        $author_id = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', true);
    }
}
$_atts['author_id'] = $author_id;
$_atts['manager_id'] = $manager_id;
$args = amotos_get_car_query_args($_atts,$query_args);
$args = apply_filters('amotos_car_archive_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
    <div class="amotos-archive-car-wrap amotos-car-wrap">
        <?php do_action('amotos_archive_car_before_main_content'); ?>
        <div class="amotos-archive-car archive-car">
            <div class="above-archive-car">
                <?php
                /**
                 * Hook: amotos_before_archive_car.
                 *
                 * @hooked amotos_template_archive_car_heading - 10
                 * @hooked amotos_template_archive_car_action - 15
                 */
                do_action('amotos_before_archive_car', $total_post, $taxonomy_title, $manager_id, $author_id);
                ?>
            </div>
            <div class="<?php echo esc_attr(join(' ', $wrapper_classes))?>">
                <?php if ($data->have_posts()) :
                    while ($data->have_posts()): $data->the_post(); ?>
                        <?php amotos_get_template('content-car.php', array(
                            'car_item_class' => $car_item_class,
                            'custom_car_image_size' => $custom_car_image_size
                        )); ?>
                    <?php endwhile;
                else: ?>
                    <?php amotos_get_template('loop/content-none.php'); ?>
                <?php endif; ?>
            </div>
            <?php
            $max_num_pages = $data->max_num_pages;
            amotos_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
            wp_reset_postdata(); ?>
        </div>
        <?php do_action('amotos_archive_car_after_main_content'); ?>
    </div>
<?php
/**
 * amotos_after_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'amotos_after_main_content' );
/**
 * amotos_sidebar_car hook.
 *
 * @hooked amotos_sidebar_car - 10
 */
do_action('amotos_sidebar_car');
get_footer('amotos');