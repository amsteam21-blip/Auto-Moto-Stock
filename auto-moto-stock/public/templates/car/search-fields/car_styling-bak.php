<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 * @var $request_stylings_search
 * @var $request_stylings
 */
?>
<div class="col-12 other-stylings-wrap clearfix">
    <div class="enable-other-stylings">
        <?php if (!empty($request_stylings_search) && $request_stylings_search == '1') {
            $class_other_stylings = 'show';
        } else {
            $class_other_stylings = '';
        } ?>
        <a href="javascript:void(0)" class="btn-other-stylings <?php echo esc_attr($class_other_stylings); ?>">
            <i class="fa fa-chevron-down"></i><?php esc_html_e('Other Styling', 'auto-moto-stock'); ?>
        </a>
        <input type="hidden" name="stylings-search" class="search-field" data-default-value="0"
               value="<?php if (!empty($request_stylings_search) && $request_stylings_search == '1') {
                   echo esc_attr('1');
               } else {
                   echo esc_attr('0');
               } ?>">
    </div>
    <?php if (!empty($request_stylings_search) && $request_stylings_search == '1') {
        $class_featured_show = 'amotos-display-block';
    } else {
        $class_featured_show = '';
    } ?>
    <div class="other-stylings-list <?php echo esc_attr($class_featured_show); ?>">
        <?php
        $car_stylings = get_categories(array(
            'taxonomy' => 'car-styling',
            'hide_empty' => 0,
            'orderby' => 'term_id',
            'order' => 'ASC'
        ));
        $parents_items = $child_items = array();
        if ($car_stylings) {
            foreach ($car_stylings as $term) {
                if (0 == $term->parent) $parents_items[] = $term;
                if ($term->parent) $child_items[] = $term;
            };
            if (is_taxonomy_hierarchical('car-styling') && count($child_items)>0) {
                foreach ($parents_items as $parents_item) {
                    echo '<h4 class="car-styling-name">' . esc_html($parents_item->name)  . '</h4>';
                    echo '<div class="row">';
                    foreach ($child_items as $child_item) {
                        if ($child_item->parent == $parents_item->term_id) {
                            echo '<div class="col-md-2 col-sm-6 col-xs-6 col-mb-12"><div class="checkbox"><label>';
                            if (!empty($request_stylings) && in_array($child_item->slug, $request_stylings)) {
                                echo '<input type="checkbox" name="other_stylings" value="' . esc_attr($child_item->slug) . '" checked/>';
                            } else {
                                echo '<input type="checkbox" name="other_stylings" value="' . esc_attr($child_item->slug) . '" />';
                            }
                            echo esc_html($child_item->name);
                            echo '</label></div></div>';
                        };
                    };
                    echo '</div>';
                };
            } else {
                echo '<div class="row">';
                foreach ($parents_items as $parents_item) {
                    echo '<div class="col-md-2 col-sm-6 col-xs-6 col-mb-12"><div class="checkbox"><label>';
                    if (!empty($request_stylings) && in_array($parents_item->slug, $request_stylings)) {
                        echo '<input type="checkbox" name="other_stylings" value="' . esc_attr($parents_item->slug) . '" checked/>';
                    } else {
                        echo '<input type="checkbox" name="other_stylings" value="' . esc_attr($parents_item->slug) . '" />';
                    }
                    echo esc_html($parents_item->name);
                    echo '</label></div></div>';
                };
                echo '</div>';
            };
        };
        ?>
    </div>
</div>