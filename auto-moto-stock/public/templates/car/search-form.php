<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
/**
 * @var $atts
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $show_status_tab
 */
$address_enable = $keyword_enable = $title_enable = $city_enable = $type_enable = $status_enable = $doors_enable = $seats_enable =
$owners_enable = $price_enable = $price_is_slider = $mileage_enable = $mileage_is_slider = $power_enable = $power_is_slider = $volume_enable = $volume_is_slider = $country_enable = $state_enable = $neighborhood_enable = $label_enable =
$car_identity_enable = $other_stylings_enable = $show_advanced_search_btn = '';
extract(shortcode_atts(array(
    'status_enable' => 'true',
    'type_enable' => 'true',
    'keyword_enable' => 'true',
    'title_enable' => 'true',
    'address_enable' => 'true',
    'country_enable' => '',
    'state_enable' => '',
    'city_enable' => '',
    'neighborhood_enable' => '',
    'doors_enable' => '',
    'seats_enable' => '',
    'owners_enable' => '',
    'price_enable' => 'true',
    'price_is_slider' => '',
    'mileage_enable' => '',
    'mileage_is_slider' => '',
    'power_enable' => '',
    'power_is_slider' => '',
    'volume_enable' => '',
    'volume_is_slider' => '',
    'label_enable' => '',
    'car_identity_enable' => '',
    'other_stylings_enable' => '',
    'show_advanced_search_btn' => 'true',
), $atts));

$advanced_search = amotos_get_permalink( 'advanced_search' );
$wrapper_classes = [
    'form-search-wrap',
];

if ( filter_var( $status_enable, FILTER_VALIDATE_BOOLEAN ) && filter_var( $show_status_tab, FILTER_VALIDATE_BOOLEAN ) ) {
    $wrapper_classes[] = 'has-status-tab';
}
$additional_fields = amotos_get_search_additional_fields();
$search_fields = amotos_get_option('search_fields', array('car_status',  'car_type', 'car_title', 'car_address','car_country', 'car_state', 'car_city', 'car_neighborhood', 'car_seats', 'car_owners', 'car_price', 'car_mileage', 'car_power', 'car_volume', 'car_label', 'car_identity', 'car_styling'));
if (array_key_exists('sort_order',$search_fields)) {
    unset($search_fields['sort_order']);
}

if (filter_var( $show_status_tab, FILTER_VALIDATE_BOOLEAN ) ) {
    if (array_key_exists('car_status', $search_fields)) {
        unset($search_fields['car_status']);
    }
}

if (empty($search_fields)) {
    return;
}
?>
<div class="<?php echo esc_attr( join( ' ', $wrapper_classes ) ) ?>">
    <div class="form-search-inner">
        <div class="amotos-search-content">
            <div data-href="<?php echo esc_url( $advanced_search ) ?>" class="search-cars-form">
                <?php if ( filter_var( $status_enable, FILTER_VALIDATE_BOOLEAN ) && filter_var( $show_status_tab, FILTER_VALIDATE_BOOLEAN ) ): ?>
                    <?php amotos_get_template('car/search-fields/car_status_tabs.php'); ?>
                <?php endif; ?>
                <div class="form-search">
                    <div class="row">
                        <?php
                        do_action('amotos_before_car_search_form', $search_fields , $css_class_field, $css_class_half_field);
                        foreach ($search_fields as $field) {
                            switch ($field) {
                                case 'car_status':
                                    if (filter_var($status_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_type':
                                    if (filter_var($type_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'keyword':
                                    if (filter_var($keyword_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_title':
                                    if (filter_var($title_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_address':
                                    if (filter_var($address_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_country':
                                    if (filter_var($country_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_state':
                                    if (filter_var($state_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_city':
                                    if (filter_var($city_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_neighborhood':
                                    if (filter_var($neighborhood_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_doors':
                                    if (filter_var($doors_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_seats':
                                    if (filter_var($seats_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_owners':
                                    if (filter_var($owners_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field
                                        ));
                                    }
                                    break;
                                case 'car_price':
                                    if (filter_var($price_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'price_is_slider' => $price_is_slider,
                                            'show_status_tab' => $show_status_tab
                                        ));
                                    }
                                    break;
                                case 'car_mileage':
                                    if (filter_var($mileage_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'mileage_is_slider' => $mileage_is_slider
                                        ));
                                    }
                                    break;
                                case 'car_power':
                                    if (filter_var($power_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'power_is_slider' => $power_is_slider
                                        ));
                                    }
                                    break;
                                case 'car_volume':
                                    if (filter_var($volume_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'volume_is_slider' => $volume_is_slider
                                        ));
                                    }
                                    break;
                                case 'car_label':
                                    if (filter_var($label_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                        ));
                                    }
                                    break;
                                case 'car_identity':
                                    if (filter_var($car_identity_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                        ));
                                    }
                                    break;
                                case 'car_styling':
                                    if (filter_var($other_stylings_enable,FILTER_VALIDATE_BOOLEAN)) {
                                        amotos_get_template('car/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                        ));
                                    }
                                    break;
                                default:
                                    if (array_key_exists($field,$additional_fields)) {
                                        if (isset($atts["{$field}_enable"]) && filter_var($atts["{$field}_enable"],FILTER_VALIDATE_BOOLEAN)) {
                                            $additional_field = amotos_get_search_additional_field($field);
                                            if ($additional_field !== false) {
                                                $type = isset($additional_field['field_type']) ? $additional_field['field_type'] : 'text';
                                                $file_type = $type;
                                                if ($type === 'textarea') {
                                                    $file_type = 'text';
                                                }

                                                if ($type === 'checkbox_list' || $type === 'radio') {
                                                    $file_type = 'select';
                                                }

                                                amotos_get_template('car/search-fields/custom-fields/' . $file_type . '.php', array(
                                                    'css_class_field' => $css_class_field,
                                                    'field' => $additional_field
                                                ));
                                            }
                                        }
                                    }
                                    break;
                            }
                            do_action('amotos_car_search_form',$field, $css_class_field, $css_class_half_field);
                        }
                        do_action('amotos_after_car_search_form', $search_fields , $css_class_field, $css_class_half_field);
                        ?>
                        <?php if (filter_var($show_advanced_search_btn,FILTER_VALIDATE_BOOLEAN)): ?>
                            <div class="form-group <?php echo esc_attr($css_class_field)?> submit-search-form">
                                <button type="button" class="amotos-advanced-search-btn"><i class="fa fa-search"></i>
                                    <?php echo esc_html__('Search', 'auto-moto-stock') ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>