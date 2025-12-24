<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $car_data, $car_meta_data;
?>
<div class="car-fields-wrap">
    <?php
    $stylings_terms_id = array();
    $stylings_terms = get_the_terms( $car_data->ID, 'car-styling' );
    if ( $stylings_terms && ! is_wp_error( $stylings_terms ) ) {
        foreach( $stylings_terms as $styling ) {
            $stylings_terms_id[] = intval( $styling->term_id );
        }
    }
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
                        if ( in_array( $child_item->term_id, $stylings_terms_id ) ) {
                            echo '<input class="form-check-input" id="amotos__styling_'. esc_attr($child_item->term_id) .'" type="checkbox" name="car_styling[]" value="' . esc_attr($child_item->term_id) . '" checked/>';
                        }
                        else
                        {
                            echo '<input class="form-check-input" id="amotos__styling_'. esc_attr($child_item->term_id) .'" type="checkbox" name="car_styling[]" value="' . esc_attr($child_item->term_id) . '" />';
                        }
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
                if ( in_array( $parents_item->term_id, $stylings_terms_id ) ) {
                    echo '<input class="form-check-input" id="amotos__styling_'. esc_attr($parents_item->term_id) .'" type="checkbox" name="car_styling[]" value="' . esc_attr($parents_item->term_id) . '" checked/>';
                }
                else
                {
                    echo '<input class="form-check-input" id="amotos__styling_'. esc_attr($parents_item->term_id) .'" type="checkbox" name="car_styling[]" value="' . esc_attr($parents_item->term_id) . '" />';
                }
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