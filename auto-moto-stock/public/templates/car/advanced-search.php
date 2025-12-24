<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$car_item_class = array('car-item');
$car_content_class = array('car-content');
$car_content_attributes = array();

$wrapper_classes = array(
    'amotos-car clearfix',
);
$custom_car_layout_style = amotos_get_option( 'search_car_layout_style', 'car-grid' );
$custom_car_items_amount = amotos_get_option( 'search_car_items_amount', '6' );
$custom_car_image_size = amotos_get_option( 'search_car_image_size', amotos_get_loop_car_image_size_default() );
$custom_car_columns      = amotos_get_option( 'search_car_columns', '3' );
$custom_car_columns_gap  = amotos_get_option( 'search_car_columns_gap', 'col-gap-30' );
$custom_car_items_md = amotos_get_option( 'search_car_items_md', '3' );
$custom_car_items_sm = amotos_get_option( 'search_car_items_sm', '2' );
$custom_car_items_xs = amotos_get_option( 'search_car_items_xs', '1' );
$custom_car_items_mb = amotos_get_option( 'search_car_items_mb', '1' );

AMOTOS_Compare::open_session();

$ss_car_view_as = isset($_SESSION["car_view_as"]) ? sanitize_text_field(wp_unslash($_SESSION["car_view_as"])) : '';
if(in_array($ss_car_view_as, array('car-list', 'car-grid'))) {
    $custom_car_layout_style = $ss_car_view_as;
}
$car_item_class         = array();
$wrapper_classes = array(
    'amotos-car clearfix',
    $custom_car_layout_style,
    $custom_car_columns_gap
);

if($custom_car_layout_style=='car-list'){
    $wrapper_classes[] = 'list-1-column';
}

if ( $custom_car_columns_gap == 'col-gap-30' ) {
    $car_item_class[] = 'mg-bottom-30';
} elseif ( $custom_car_columns_gap == 'col-gap-20' ) {
    $car_item_class[] = 'mg-bottom-20';
} elseif ( $custom_car_columns_gap == 'col-gap-10' ) {
    $car_item_class[] = 'mg-bottom-10';
}

$wrapper_classes[]     = 'columns-' . $custom_car_columns;
$wrapper_classes[]     = 'columns-md-' . $custom_car_items_md;
$wrapper_classes[]     = 'columns-sm-' . $custom_car_items_sm;
$wrapper_classes[]     = 'columns-xs-' . $custom_car_items_xs;
$wrapper_classes[]     = 'columns-mb-' . $custom_car_items_mb;
$car_item_class[]      = 'amotos-item-wrap';

$_atts =  [
    'item_amount' => $custom_car_items_amount
];

$enable_advanced_search_status_tab = amotos_get_option( 'enable_advanced_search_status_tab', '1' );
if (filter_var($enable_advanced_search_status_tab, FILTER_VALIDATE_BOOLEAN)) {
    $_atts['status'] = amotos_get_car_status_default_value();
}
$args =  amotos_get_car_query_args($_atts);
$args = apply_filters('amotos_advanced_search_query_args',$args);
$parameters = amotos_get_car_query_parameters();
$parameters = join('; ', $parameters);
$data       = new WP_Query( $args );
$search_query=$args;
$total_post = $data->found_posts;
wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'archive-car');
?>
<div class="amotos-advanced-search-wrap amotos-car-wrap">
    <?php
    /**
     * Hook: amotos_before_advanced_search.
     *
     * @hooked amotos_template_car_advanced_search_form - 10
     */
    do_action('amotos_before_advanced_search',$parameters,$search_query);
    ?>
    <div class="amotos-archive-car">
        <div class="above-archive-car">
            <?php
            /**
             * Hook: amotos_before_archive_car.
             *
             * @hooked amotos_template_archive_car_heading - 10
             * @hooked amotos_template_archive_car_action - 15
             */
            do_action('amotos_before_archive_car', $total_post);
            ?>
        </div>
        <div class="<?php echo esc_attr(join( ' ', $wrapper_classes )) ?>">
            <?php if ( $data->have_posts() ) :
                while ( $data->have_posts() ): $data->the_post(); ?>

                    <?php amotos_get_template( 'content-car.php', array(
                        'custom_car_image_size' => $custom_car_image_size,
                        'car_item_class' => $car_item_class
                    )); ?>

                <?php endwhile;
            else: ?>
                <?php amotos_get_template('loop/content-none.php'); ?>
            <?php endif; ?>
        </div>
        <?php
        $max_num_pages = $data->max_num_pages;
        amotos_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
        wp_reset_postdata(); ?>
    </div>
    <?php do_action('amotos_advanced_search_after_main_content'); ?>
</div>