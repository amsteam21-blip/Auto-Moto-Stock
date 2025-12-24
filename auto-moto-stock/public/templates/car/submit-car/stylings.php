<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="car-fields-wrap">
    <?php
    $car_stylings = get_categories(array(
        'taxonomy'  => 'car-styling',
        'hide_empty' => 0,
        'orderby' => 'term_id',
        'order' => 'ASC'
    ));
    $parents_items=$child_items=array();
    if ($car_stylings) {
        foreach ($car_stylings as $term) {
            if (0 == $term->parent) $parents_items[] = $term;
            if ($term->parent) $child_items[] = $term;
        };
        if (is_taxonomy_hierarchical('car-styling') && count($child_items)>0) {
            foreach ($parents_items as $parents_item) {
                echo '<div class="amotos-heading-style2 car-fields-title">';
                echo '<h2>' . esc_html($parents_item->name)  . '</h2>';
                echo '</div>';
                echo '<div class="car-fields car-styling">';
                echo '<div class="row">';
                foreach ($child_items as $child_item) {
                    if ($child_item->parent == $parents_item->term_id) {
                        echo '<div class="col-sm-3"><div class="form-check form-group">';
                        echo '<input type="checkbox" class="form-check-input" name="car_styling[]" id="amotos__styling_'. esc_attr($child_item->term_id) .'" value="' . esc_attr($child_item->term_id) . '" />';
                        echo '<label class="form-check-label" for="amotos__styling_'. esc_attr($child_item->term_id) .'">';
                        echo esc_html($child_item->name);
                        echo '</label></div></div>';
                    };
                };
                echo '</div>';
                echo '</div>';
            };
        } else {
            echo '<div class="amotos-heading-style2 car-fields-title">';
            echo '<h2>' . esc_html__( 'Styling', 'auto-moto-stock' ). '</h2>';
            echo '</div>';
            echo '<div class="car-fields car-styling">';
            echo '<div class="row">';
            foreach ($parents_items as $parents_item) {
                echo '<div class="col-sm-3"><div class="form-check form-group">';
                echo '<input type="checkbox" class="form-check-input" name="car_styling[]" id="amotos__styling_'. esc_attr($parents_item->term_id) .'" value="' . esc_attr($parents_item->term_id) . '" />';
                echo '<label class="form-check-label" for="amotos__styling_'. esc_attr($parents_item->term_id) .'">';
                echo esc_html($parents_item->name);
                echo '</label></div></div>';
            };
            echo '</div>';
            echo '</div>';
        };
    };
    ?>
</div>
