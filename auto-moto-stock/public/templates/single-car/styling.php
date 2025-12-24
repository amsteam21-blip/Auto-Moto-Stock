<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $stylings WP_Term
 */
$stylings_terms_id = array();
foreach ($stylings as $styling) {
    $stylings_terms_id[] = intval($styling->term_id);
}

$all_stylings = get_categories(array(
    'taxonomy' => 'car-styling',
    'hide_empty' => 0,
    'orderby' => 'term_id',
    'order' => 'ASC'
));

$parents_items = array();
$multi_level = false;

$hide_empty_stylings = amotos_get_option('hide_empty_stylings', 1);
$parents = array();
foreach ($all_stylings as $term) {
    if (0 == $term->parent) {
        if (!isset($parents_items[$term->term_id])) {
            $parents_items[$term->term_id] = array(
                'term' => $term,
                'child' => array()
            );
        }
    } else {
        $multi_level = true;
        $parents[$term->term_id] = $term->parent;
    }
};

foreach ($parents as $k => $v) {
	$parent_id = $v;
	while (isset($parents[$parent_id])) {
		$parents[$k] = $parents[$parent_id];
		$parent_id = $parents[$parent_id];
	}
}

foreach ($all_stylings as $term) {
    if (0 != $term->parent) {
        $parent_id = $parents[$term->term_id];
        $parents_items[$parent_id]['child'][] = $term;
    }
}

$car_archive_link = get_post_type_archive_link('car');

$wrapper_classes = array(
    'single-car-element',
    'car-styling',
    'amotos__single-car-element',
    'amotos__single-car-styling'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_styling_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Styling', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <?php if ($multi_level): ?>
            <?php foreach ($parents_items as $k => $v): ?>
                <?php
                $found = FALSE;
                $found_child = FALSE;
                $term = $v['term'];
                $child = isset($v['child']) ? $v['child'] : array();
                if (in_array($term->term_id,$stylings_terms_id) || ($hide_empty_stylings != 1)) {
                    $found = true;
                }
                ?>
                <?php if (is_array($child) && count($child) > 0): ?>
                    <?php ob_start();?>
                    <div class="row mg-bottom-30">
                        <?php foreach ($child as $child_term ): ?>
                            <?php $term_link = get_term_link($child_term, 'car-styling'); ?>
                            <?php if (in_array($child_term->term_id, $stylings_terms_id)): ?>
                                <?php $found = true;
                                $found_child = true;?>
                                <div class="col-md-3 col-sm-6 car-styling-wrap mb-1">
                                    <a class="styling-checked" href="<?php echo esc_url($term_link)?>"><i class="fa fa-check-square-o"></i> <?php echo esc_html($child_term->name) ?></a>
                                </div>
                            <?php elseif ($hide_empty_stylings != 1): ?>
                                <?php $found = true;
                                $found_child = true;?>
                                <div class="col-md-3 col-sm-6 car-styling-wrap mb-1">
                                    <a class="styling-unchecked" href="<?php echo esc_url($term_link)?>"><i class="fa fa-square-o"></i> <?php echo esc_html($child_term->name) ?></a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php $child_content = ob_get_clean(); ?>
                <?php endif; ?>

                <?php ob_start(); ?>
                <h4><?php echo esc_html($term->name)?></h4>
                <?php if ($found_child) {
                    echo wp_kses_post($child_content);
                } ?>
                <?php $content = ob_get_clean(); ?>

                <?php if ($found) {
                    echo wp_kses_post($content);
                } ?>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="row">
                <?php foreach ($parents_items as $k => $v): ?>
                    <?php $term = $v['term'] ?>
                    <?php $term_link = get_term_link($term, 'car-styling');  ?>
                    <?php if (in_array($term->term_id, $stylings_terms_id)): ?>
                        <div class="col-md-3 col-sm-6 car-styling-wrap mb-1">
                            <a class="styling-checked" href="<?php echo esc_url($term_link)?>"><i class="fa fa-check-square-o"></i> <?php echo esc_html($term->name) ?></a>
                        </div>
                    <?php elseif ($hide_empty_stylings != 1): ?>
                        <div class="col-md-3 col-sm-6 car-styling-wrap mb-1">
                            <a class="styling-unchecked" href="<?php echo esc_url($term_link)?>"><i class="fa fa-square-o"></i> <?php echo esc_html($term->name) ?></a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

