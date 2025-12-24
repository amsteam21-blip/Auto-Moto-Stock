<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $hide_compare_fields;
$hide_compare_fields = amotos_get_option('hide_compare_fields', array());
$additional_fields = amotos_render_additional_fields();
if (!is_array($hide_compare_fields)) {
    $hide_compare_fields = array();
}
AMOTOS_Compare::open_session();
$car_ids = $_SESSION['amotos_compare_cars'];
$car_ids = array_diff($car_ids, ["0"]);
$args = array(
    'post_type' => 'car',
    'post__in' => $car_ids,
    'post_status' => 'publish',
    'orderby' => 'post__in',
    'posts_per_page' => -1
);
$data = new WP_Query($args);
$type_html = $status_html = $mileage_html = $power_html = $volume_html = $door_html = $seat_html = $owner_html = $year_html = '';
$has_type = $has_status = $has_mileage = $has_power = $has_volume = $has_door = $has_seat = $has_owner = $has_year = false;
$empty_field = '<td class="check-no"><i class="fa fa-minus"></i></td>';
$measurement_units_mileage = amotos_get_measurement_units_mileage();
$measurement_units_power = amotos_get_measurement_units_power();
$measurement_units_volume = amotos_get_measurement_units_volume();
$car_stylings = get_categories(array(
    'hide_empty' => 0,
    'taxonomy'  => 'car-styling'
));
$compare_terms = array();
foreach ($car_ids as $post_id) {
    $compare_terms[$post_id] = wp_get_post_terms($post_id, 'car-styling', array('fields' => 'ids'));
}
?>
<?php if ($data->have_posts()): ?>
    <div class="table-responsive-xl amotos__compare-table-wrap compare-table-wrap amotos-car">
    <table class="table amotos__compare-tables compare-tables table-striped">
        <thead>
            <tr>
                <th class="title-list-check"></th>
                <?php while ($data->have_posts()):$data->the_post(); ?>
                    <th>
                        <div class="amotos__car-compare">
                            <div class="car-inner">
                                <div class="car-image">
                                    <?php
                                    amotos_template_loop_car_image();
                                    /**
                                     * Hook: amotos_after_loop_compare_car_thumbnail.
                                     *
                                     * @hooked amotos_template_loop_car_featured_label - 5
                                     * @hooked amotos_template_loop_car_term_status - 10
                                     * @hooked amotos_template_loop_car_link - 15
                                     */
                                    do_action('amotos_after_loop_compare_car_thumbnail');
                                    ?>
                                </div>
                                <div class="car-item-content">
                                    <div class="car-heading">
                                        <?php
                                        /**
                                         * Hook: amotos_loop_compare_car_heading.
                                         *
                                         * @hooked amotos_template_loop_car_title - 5
                                         * @hooked amotos_template_loop_car_price - 10
                                         */
                                        do_action('amotos_loop_compare_car_heading');
                                        ?>
                                    </div>
                                    <?php
                                    /**
                                     * Hook: amotos_after_loop_compare_car_heading.
                                     *
                                     * @hooked amotos_template_loop_car_location - 5
                                     */
                                    do_action('amotos_after_loop_compare_car_heading');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </th>
                    <?php
                    if (!in_array("car_type", $hide_compare_fields)) {
                        $type = get_the_term_list(get_the_ID(),'car-type','',', ','');
                        if (!empty($type)) {
                            $has_type = true;
                            $type_html .= '<td>' . $type . '</td>';
                        } else {
                            $type_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_status", $hide_compare_fields)) {
                        $status =  get_the_term_list(get_the_ID(),'car-status','',', ','');
                        if (!empty($status)) {
                            $has_status = true;
                            $status_html .= '<td>' . $status . '</td>';
                        } else {
                            $status_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_mileage", $hide_compare_fields)) {
                        $mileage = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_mileage', true);
                        if (!empty($mileage)) {
                            $has_mileage = true;
                            $mileage_html .= '<td>' . wp_kses_post(sprintf( '%s %s', amotos_get_format_number($mileage), $measurement_units_mileage)) . '</td>';
                        } else {
                            $mileage_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_power", $hide_compare_fields)) {
                        $power = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_power', true);
                        if (!empty($power)) {
                            $has_power = true;
                            $power_html .= '<td>' . wp_kses_post(sprintf( '%s %s', amotos_get_format_number($power), $measurement_units_power)) . '</td>';
                        } else {
                            $power_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_volume", $hide_compare_fields)) {
                        $volume = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_volume', true);
                        if (!empty($volume)) {
                            $has_volume = true;
                            $volume_html .= '<td>' . wp_kses_post(sprintf( '%s %s', amotos_get_format_number($volume), $measurement_units_volume)) . '</td>';
                        } else {
                            $volume_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_doors", $hide_compare_fields)) {
                        $door = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_doors', true);
                        if (!empty($door)) {
                            $has_door = true;
                            $door_html .= '<td>' . $door . '</td>';
                        } else {
                            $door_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_seats", $hide_compare_fields)) {
                        $seat = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_seats', true);
                        if (!empty($seat)) {
                            $has_seat = true;
                            $seat_html .= '<td>' . $seat . '</td>';
                        } else {
                            $door_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_owners", $hide_compare_fields)) {
                        $owner = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_owners', true);
                        if (!empty($owner)) {
                            $has_owner = true;
                            $owner_html .= '<td>' . $owner . '</td>';
                        } else {
                            $owner_html .= $empty_field;
                        }
                    }

                    if (!in_array("car_year", $hide_compare_fields)) {
                        $year = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_year',true);
                        if (!empty($year)) {
                            $has_year = true;
                            $year_html .= '<td>' . $year . '</td>';
                        } else {
                            $year_html .= $empty_field;
                        }
                    }

                    ?>
                <?php endwhile; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($has_type): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Type', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($type_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_status): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Status', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($status_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_mileage): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Mileage', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($mileage_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_power): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Power', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($power_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_volume): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Cubic Capacity', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($volume_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_door): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Doors', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($door_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_seat): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Seats', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($seat_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_owner): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Owners', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($owner_html)?>
                </tr>
            <?php endif; ?>
            <?php if ($has_year): ?>
                <tr>
                    <td class="title-list-check"><?php echo esc_html__('Vehicle Year', 'auto-moto-stock'); ?></td>
                    <?php echo wp_kses_post($year_html)?>
                </tr>
            <?php endif; ?>
            <?php foreach ($car_stylings as $styling): ?>
            <tr>
                <td class="title-list-check"><?php echo esc_html($styling->name); ?></td>
                <?php foreach ($car_ids as $car_id): ?>
                    <?php if (in_array($styling->term_id, $compare_terms[$car_id])): ?>
                        <td><div class="check-yes"><i class="fa fa-check"></i></div></td>
                    <?php else: ?>
                        <td><div class="check-no"><i class="fa fa-minus"></i></div></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($additional_fields as $k => $field): ?>
            <?php
                $field_html = '';
                $field_check = false;
            ?>
            <?php ob_start(); ?>
            <tr>
                <td class="title-list-check"><?php echo esc_html($field['title'])?></td>
                <?php foreach ($car_ids as $car_id): ?>
                <?php $value = get_post_meta($car_id,$field['id'], true); ?>
                <?php if (!empty($value)): ?>
                        <?php
                            $field_check = true;
                            if ($field['type'] == 'checkbox_list') {
                                $text = '';
                                if (count($value) > 0) {
                                    foreach ($value as $v) {
                                        $text .= $v . ', ';
                                    }
                                }
                                $value = rtrim($text, ', ');
                            }
                        ?>
                        <td><?php echo esc_html($value)?></td>
                <?php else: ?>
                        <?php echo wp_kses_post($empty_field); ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </tr>
            <?php $field_html = ob_get_clean(); ?>
            <?php if ($field_check) {
                echo wp_kses_post($field_html);
                } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php wp_reset_postdata(); ?>
<?php else: ?>
    <?php amotos_get_template('loop/content-none.php'); ?>
<?php endif; ?>
