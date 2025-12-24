<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Shortcode attributes
 * @var $atts
 */
$item_amount = $show_paging = $include_heading = $heading_sub_title
	= $heading_title  = $el_class = '';
extract( shortcode_atts( array(
	'item_amount'       => '6',
	'show_paging'       => '',
	'include_heading'   => '',
	'heading_sub_title' => '',
	'heading_title'     => '',
	'el_class'          => ''
), $atts ) );

$dealer_item_classes = array( 'dealer-item','amotos__loop-dealer-item');
$wrapper_classes = array(
	'amotos-dealer',
    'amotos-dealer-wrap',
    'amotos__dealer-wrap',
	$el_class
);
$keyword = '';$unique=array();
$args = array(
	'number' => ( $item_amount > 0 ) ? $item_amount : - 1,
	'taxonomy'      => 'dealer',
	'orderby'        => 'date',
	'offset' => (max(1, get_query_var('paged')) - 1) * $item_amount,
	'order'          => 'DESC',
);

if (isset ($_GET['keyword'])) {
	$keyword = amotos_clean(wp_unslash($_GET['keyword']));
	$q1 = get_categories(array(
		'fields' => 'ids',
		'taxonomy'      => 'dealer',
		'name__like' => $keyword
	));
	$q2 = get_categories(array(
		'fields' => 'ids',
		'taxonomy'      => 'dealer',
		'meta_query' => array(
			array(
				'key'       => 'dealer_address',
				'value'     => $keyword,
				'compare'   => 'LIKE'
			)
		)
	));
	$unique = array_unique( array_merge( $q1, $q2 ) );
	if(empty($unique))
	{
		$unique[]=-1;
	}
	$args['include'] = $unique;
}

$sortby = isset($_GET['sortby']) ? amotos_clean(wp_unslash($_GET['sortby'])) : '';
if (in_array($sortby, array('a_date','d_date','a_name','d_name'))) {
	if ($sortby == 'a_date') {
		$args['orderby'] = 'date';
		$args['order'] = 'ASC';
	} else if ($sortby == 'd_date') {
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
	}else if ($sortby == 'a_name') {
		$args['orderby'] = 'name__like';
		$args['order'] = 'ASC';
	}else if ($sortby == 'd_name') {
		$args['orderby'] = 'name__like';
		$args['order'] = 'DESC';
	}
}
$args = apply_filters('amotos_shortcodes_dealer_query_args',$args);
$dealers = get_categories($args);
$dealer_item_class = join(' ',$dealer_item_classes);
$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)  ?>">
    <?php if ( $include_heading && (!empty( $heading_sub_title ) || !empty( $heading_title ))): ?>
        <div class="amotos__archive-dealer-above">
            <?php amotos_template_heading(array(
                'heading_title' => $heading_title,
                'heading_sub_title' => $heading_sub_title
            )) ?>
            <div class="amotos__archive-actions amotos__archive-dealer-actions">
                <?php
                /**
                 * Hook: amotos_archive_dealer_actions.
                 *
                 * @hooked amotos_template_archive_dealer_action_search - 5
                 * @hooked amotos_template_archive_dealer_action_orderby - 10
                 */
                do_action('amotos_archive_dealer_actions');
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="dealer-content amotos__dealer-list">
        <?php if ( $dealers ) :
            foreach ($dealers as $dealer) :
                ?>
                <div class="<?php echo esc_attr($dealer_item_class); ?>">
                    <?php
                    /**
                     * Hook: amotos_before_loop_dealer_content.
                     *
                     * @hooked amotos_template_loop_dealer_image - 10
                     */
                    do_action('amotos_before_loop_dealer_content',$dealer);
                    ?>
                    <div class="dealer-item-content amotos__loop-dealer-content">
                        <div class="amotos__loop-dealer-heading">
                            <?php
                            /**
                             * Hook: amotos_loop_car_heading.
                             *
                             * @hooked amotos_template_loop_dealer_title - 5
                             * @hooked amotos_template_loop_dealer_address - 10
                             * @hooked amotos_template_loop_dealer_social - 15
                             */
                            do_action('amotos_loop_dealer_heading',$dealer);
                            ?>
                        </div>
                        <?php
                        /**
                         * Hook: amotos_after_loop_dealer_heading.
                         *
                         * @hooked amotos_template_loop_dealer_desc - 5
                         * @hooked amotos_template_loop_dealer_meta - 10
                         */
                        do_action('amotos_after_loop_dealer_heading',$dealer);
                        ?>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <?php amotos_get_template('loop/content-none.php'); ?>
        <?php endif; ?>
    </div>
    <?php if (filter_var($show_paging,FILTER_VALIDATE_BOOLEAN)): ?>
        <div class="dealer-paging-wrap amotos__dealer-paging" data-admin-url="<?php echo esc_url(AMOTOS_AJAX_URL) ; ?>"
             data-items-amount="<?php echo esc_attr( $item_amount ); ?>" >
            <?php
            $args = array(
                'taxonomy'      => 'dealer'
            );
            if (isset ($_GET['keyword'])) {
                $args['include'] = $unique;
            }
            $all_dealer = get_categories($args);
            $max_num_pages = floor(count( $all_dealer ) / $item_amount);
            if(count( $all_dealer ) % $item_amount > 0) {
                $max_num_pages++;
            }
            amotos_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
            ?>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>