<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * @var $css_class_field
 */
$request_stylings = isset($_GET['other_stylings']) ? amotos_clean(wp_unslash($_GET['other_stylings']))  : '';
if (!empty($request_stylings)) {
    $request_stylings = explode(';', $request_stylings);
}

$car_stylings = get_categories(array(
    'taxonomy' => 'car-styling',
    'hide_empty' => 0,
    'orderby' => 'term_id',
    'order' => 'ASC'
));

if (!is_array($car_stylings) || (count($car_stylings) == 0)) {
    return;
}


$parents_items = $child_items = array();
foreach ($car_stylings as $term) {
    if (0 == $term->parent) {
        $parents_items[] = $term;
    }
    if ($term->parent) {
        $child_items[] = $term;
    }
};

$prefix = uniqid('__');

$has_request_stylings = !empty($request_stylings) && is_array($request_stylings) & count($request_stylings) > 0;
?>
<div class="col-12 other-stylings-wrap">
    <div class="enable-other-stylings">
        <a class="btn-other-stylings" data-toggle="collapse" href="#amotos_search_other_stylings">
            <?php if ($has_request_stylings): ?>
                <i class="fa fa-chevron-up"></i>
            <?php else: ?>
                <i class="fa fa-chevron-down"></i>
            <?php endif; ?>
            <?php echo esc_html__('Other Styling', 'auto-moto-stock'); ?>
        </a>
    </div>
    <div class="collapse<?php echo esc_attr($has_request_stylings ? ' show' : ''); ?>" id="amotos_search_other_stylings">
        <div class="other-stylings-list mt-2<?php echo esc_attr($has_request_stylings ? 'amotos-display-block' : ''); ?>">
            <?php if (is_taxonomy_hierarchical('car-styling') && (count($child_items)>0)): ?>
                <?php foreach ($parents_items as $parents_item): ?>
                    <h4 class="car-styling-name"><?php echo esc_html($parents_item->name)?></h4>
                    <div class="row">
                        <?php foreach ($child_items as $child_item): ?>
                            <?php if ($child_item->parent == $parents_item->term_id): ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-12 mt-2">
                                    <div class="form-check">
                                        <?php if (!empty($request_stylings) && in_array($child_item->slug, $request_stylings)):  ?>
                                            <input type="checkbox" class="form-check-input" id="car_styling_<?php echo esc_attr($child_item->term_id . $prefix)?>" name="other_stylings" checked="checked" value="<?php echo esc_attr($child_item->slug) ?>" />
                                        <?php else: ?>
                                            <input type="checkbox" class="form-check-input" id="car_styling_<?php echo esc_attr($child_item->term_id . $prefix)?>" name="other_stylings" value="<?php echo esc_attr($child_item->slug) ?>" />
                                        <?php endif; ?>
                                        <label class="form-check-label" for="car_styling_<?php echo esc_attr($child_item->term_id . $prefix)?>">
                                            <?php echo esc_html($child_item->name); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($parents_items as $parents_item): ?>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-12 mt-2">
                            <div class="form-check">
                                <?php if (!empty($request_stylings) && in_array($parents_item->slug, $request_stylings)):  ?>
                                    <input type="checkbox" class="form-check-input" id="car_styling_<?php echo esc_attr($parents_item->term_id . $prefix)?>" name="other_stylings" checked="checked" value="<?php echo esc_attr($parents_item->slug) ?>" />
                                <?php else: ?>
                                    <input type="checkbox" class="form-check-input" id="car_styling_<?php echo esc_attr($parents_item->term_id . $prefix)?>" name="other_stylings" value="<?php echo esc_attr($parents_item->slug) ?>" />
                                <?php endif; ?>
                                <label class="form-check-label" for="car_styling_<?php echo esc_attr($parents_item->term_id . $prefix)?>">
                                    <?php echo esc_html($parents_item->name); ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>