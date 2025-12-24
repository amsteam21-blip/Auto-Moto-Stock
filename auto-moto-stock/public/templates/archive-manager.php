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
$dealer = amotos_get_option('manager_dealer', '');
$manager_layout_style = amotos_get_option('archive_manager_layout_style', 'manager-grid');
$custom_manager_image_size = amotos_get_option( 'archive_manager_image_size', '270x340' );
$posts_per_page = amotos_get_option('archive_manager_item_amount', 12);
$column_lg = amotos_get_option('archive_manager_column_lg', '4');
$column_md = amotos_get_option('archive_manager_column_md', '3');
$column_sm = amotos_get_option('archive_manager_column_sm', '2');
$column_xs = amotos_get_option('archive_manager_column_xs', '2');
$column_mb = amotos_get_option('archive_manager_column_mb', '1');

AMOTOS_Compare::open_session();
$ss_manager_view_as = isset($_SESSION["manager_view_as"]) ? amotos_clean(wp_unslash($_SESSION["manager_view_as"])) : '';
if (in_array($ss_manager_view_as, array('manager-list', 'manager-grid'))) {
    $manager_layout_style = $ss_manager_view_as;
}

$wrapper_classes = array(
    'amotos-manager clearfix',
    $manager_layout_style,
);
if ($manager_layout_style == 'manager-list') {
    $wrapper_classes[] = 'list-1-column';
}

$sf_item_wrap = '';

$sf_item_wrap = 'amotos-item-wrap';
$wrapper_classes[] = 'row columns-' . $column_lg . ' columns-md-' . $column_md . ' columns-sm-' . $column_sm . ' columns-xs-' . $column_xs . ' columns-mb-' . $column_mb . '';

$args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => 'manager',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
    'offset' => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish'
);
$sortby = isset($_GET['sortby']) ? amotos_clean(wp_unslash($_GET['sortby'])) : '';
if (in_array($sortby, array('a_date','d_date','a_name','d_name'))) {
    if ($sortby == 'a_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    } else if ($sortby == 'd_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }else if ($sortby == 'a_name') {
        $args['orderby'] = 'post_title';
        $args['order'] = 'ASC';
    }else if ($sortby == 'd_name') {
        $args['orderby'] = 'post_title';
        $args['order'] = 'DESC';
    }
}
if (!empty($dealer)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'dealer',
            'field' => 'term_id',
            'terms' => $dealer,
            'operator' => 'IN'
        )
    );
}
$keyword = isset($_GET['manager_name']) ? amotos_clean(wp_unslash($_GET['manager_name'])) : '';
if (!empty($keyword)) {
	$args['s'] = $keyword;
}

$args = apply_filters('amotos_manager_archive_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
$wrapper_classes = implode(' ', array_filter($wrapper_classes));
?>
    <div class="amotos-archive-manager-wrap">
        <?php do_action('amotos_archive_manager_before_main_content');?>
        <div class="amotos-archive-manager">
            <div class="above-archive-manager amotos__archive-manager-above">
                <?php
                /**
                 * Hook: amotos_before_archive_manager.
                 *
                 * @hooked amotos_template_archive_manager_heading - 5
                 * @hooked amotos_template_archive_manager_action - 15
                 */
                do_action('amotos_before_archive_manager', $total_post);
                ?>
            </div>
            <?php if ($data->have_posts()): ?>
                <div class="<?php echo esc_attr($wrapper_classes) ?>">
                    <?php while ($data->have_posts()): $data->the_post(); ?>
                        <?php amotos_get_template('content-manager.php', array(
                            'sf_item_wrap' => $sf_item_wrap,
                            'manager_layout_style' => $manager_layout_style,
                            'custom_manager_image_size'=>$custom_manager_image_size
                        )); ?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <?php amotos_get_template('loop/content-none.php'); ?>
                <?php
            endif; ?>
            <div class="clearfix"></div>
            <?php
            $max_num_pages = $data->max_num_pages;
            amotos_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
            wp_reset_postdata(); ?>
        </div>
        <?php do_action('amotos_archive_manager_after_main_content');?>
    </div>
<?php
/**
 * amotos_after_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'amotos_after_main_content' );
/**
 * amotos_sidebar_manager hook.
 *
 * @hooked amotos_sidebar_manager - 10
 */
do_action('amotos_sidebar_manager');
get_footer('amotos');
