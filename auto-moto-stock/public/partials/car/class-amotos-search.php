<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Search')) {
    /**
     * Class AMOTOS_Search
     */

    class AMOTOS_Search
    {
    	/*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }


        public function query_all_cars()
        {
            $data = array(
                'post_type' => 'car',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby'   => array(
                    'menu_order'=>'ASC',
                    'date' =>'DESC',
                ),
            );
            $featured_toplist = amotos_get_option('featured_toplist', 1);
            if($featured_toplist!=0)
            {
                /*$data['orderby'] = array(
                    'menu_order'=>'ASC',
                    'meta_value_num' => 'DESC',
                    'date' => 'DESC',
                );
                $data['meta_key'] = AMOTOS_METABOX_PREFIX . 'car_featured';*/
	            $data['amotos_orderby_featured'] = true;
            }
            return new WP_Query($data);
        }

        public function amotos_car_search_ajax()
        {
            check_ajax_referer('amotos_search_map_ajax_nonce', 'amotos_security_search_map');
            $search_type = isset($_REQUEST['search_type']) ? amotos_clean(wp_unslash($_REQUEST['search_type']))  : '';
            $query_args = amotos_get_car_query_args();
            $query_args = apply_filters('amotos_car_search_ajax_query_args',$query_args);
            $the_query = new WP_Query($query_args);
            $cars = array();
            $car_html = '';
            $custom_car_image_size = amotos_get_option( 'search_car_image_size', amotos_get_loop_car_image_size_default() );
            $car_item_class = array('car-item');
            if($search_type == 'map_and_content') {
                $car_html = '<div class="list-car-result-ajax">';
            }
            while ($the_query->have_posts()): $the_query->the_post();
                $car_id = get_the_ID();
                $car_location = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_location', true);
                if (!empty($car_location['location'])) {
                    $lat_lng = explode(',', $car_location['location']);
                } else {
                    $lat_lng = array();
                }
                $attach_id = get_post_thumbnail_id();
                $width = 100;
                $height = 100;
                if (!empty($attach_id)) {
                    $image_src = amotos_image_resize_id($attach_id, $height, $width, true);
                } else {
                    $image_src= AMOTOS_PLUGIN_URL . 'public/assets/images/no-image.jpg';
                    $default_image=amotos_get_option('default_car_image','');
                    if($default_image!='')
                    {
                        $image_src=$default_image['url'];
                    }
                }
                $car_type = get_the_terms($car_id, 'car-type');
                $car_url = '';
                if ($car_type) {
                    $car_type_id = $car_type[0]->term_id;
                    $car_type_icon = get_term_meta($car_type_id, 'car_type_icon', true);
                    if (is_array($car_type_icon) && count($car_type_icon) > 0) {
                        $car_url = $car_type_icon['url'];
                    }
                }

                $car_address = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_address', true);
                $cars_price = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price', true);
                $cars_price = amotos_get_format_money($cars_price);
                $veh = new stdClass();
                $veh->image_url = $image_src;
                $veh->title = get_the_title();
                $veh->lat = $lat_lng[0];
                $veh->lng = $lat_lng[1];
                $veh->url = get_permalink();
                $veh->price = $cars_price;
                $veh->address = $car_address;
                if ($car_url == '') {
                    $car_url = AMOTOS_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
                    $default_marker=amotos_get_option('marker_icon','');
                    if($default_marker!='')
                    {
                        if(is_array($default_marker)&& $default_marker['url']!='')
                        {
                            $car_url=$default_marker['url'];
                        }
                    }
                }
                $veh->marker_icon = $car_url;
                array_push($cars, $veh);

                if($search_type == 'map_and_content') {
                    $car_html .= amotos_get_template_html('content-car.php', array(
                        'custom_car_image_size' => $custom_car_image_size,
                        'car_item_class' => $car_item_class,
                    ));
                }
            endwhile;
            if($search_type == 'map_and_content') {
                $car_html .= '</div>';
            }
            if (count($cars) > 0) {
                echo wp_json_encode(array('success' => true, 'cars' => $cars, 'car_html' => $car_html));
            } else {
                echo wp_json_encode(array('success' => false));
            }
            wp_reset_postdata();
            die();
        }

        public function amotos_car_search_map_ajax()
        {
            check_ajax_referer('amotos_search_map_ajax_nonce', 'amotos_security_search_map');


            $meta_query = array();
            $tax_query = array();
	        $keyword_array = '';

	        $keyword = isset($_REQUEST['keyword']) ? amotos_clean(wp_unslash($_REQUEST['keyword']))  : '';
            $title = isset($_REQUEST['title']) ? amotos_clean(wp_unslash($_REQUEST['title']))  : '';
            $address = isset($_REQUEST['address']) ? amotos_clean(wp_unslash($_REQUEST['address']))  : '';
            $type = isset($_REQUEST['type']) ? amotos_clean(wp_unslash($_REQUEST['type']))  : '';
            $city = isset($_REQUEST['city']) ? amotos_clean(wp_unslash($_REQUEST['city']))  : '';
            $status = isset($_REQUEST['status']) ? amotos_clean(wp_unslash($_REQUEST['status']))  : '';
	        $doors = isset($_REQUEST['doors']) ? amotos_clean(wp_unslash($_REQUEST['doors']))  : '';
            $owners = isset($_REQUEST['owners']) ? amotos_clean(wp_unslash($_REQUEST['owners']))  : '';
            $seats = isset($_REQUEST['seats']) ? amotos_clean(wp_unslash($_REQUEST['seats']))  : '';
            $min_mileage = isset($_REQUEST['min_mileage']) ? amotos_clean(wp_unslash($_REQUEST['min_mileage']))  : '';
            $max_mileage = isset($_REQUEST['max_mileage']) ? amotos_clean(wp_unslash($_REQUEST['max_mileage'])) : '';
            $min_price = isset($_REQUEST['min_price']) ? amotos_clean(wp_unslash($_REQUEST['min_price'])) : '';
            $max_price = isset($_REQUEST['max_price']) ? amotos_clean(wp_unslash($_REQUEST['max_price'])) : '';
            $state = isset($_REQUEST['state']) ? amotos_clean(wp_unslash($_REQUEST['state'])) : '';
            $country = isset($_REQUEST['country']) ? amotos_clean(wp_unslash($_REQUEST['country'])) : '';
            $neighborhood = isset($_REQUEST['neighborhood']) ? amotos_clean(wp_unslash($_REQUEST['neighborhood'])) : '';
            $label = isset($_REQUEST['label']) ? amotos_clean(wp_unslash($_REQUEST['label'])) : '';
            $min_power = isset($_REQUEST['min_power']) ? amotos_clean(wp_unslash($_REQUEST['min_power'])) : '';
            $max_power = isset($_REQUEST['max_power']) ? amotos_clean(wp_unslash($_REQUEST['max_power'])) : '';
            $min_volume = isset($_REQUEST['min_volume']) ? amotos_clean(wp_unslash($_REQUEST['min_volume'])) : '';
            $max_volume = isset($_REQUEST['max_volume']) ? amotos_clean(wp_unslash($_REQUEST['max_volume'])) : '';
            $car_identity = isset($_REQUEST['car_identity']) ? amotos_clean(wp_unslash($_REQUEST['car_identity'])) : '';
            $stylings = isset($_REQUEST['stylings']) ? amotos_clean(wp_unslash($_REQUEST['stylings'])) : '';
            $search_type = isset($_REQUEST['search_type']) ? amotos_clean(wp_unslash($_REQUEST['search_type'])) : '';
            $paged = isset($_REQUEST['paged']) ? amotos_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $item_amount = isset($_REQUEST['item_amount']) ? amotos_clean(wp_unslash($_REQUEST['item_amount'])) : '18';
            $marker_image_size = isset($_REQUEST['marker_image_size']) ? amotos_clean(wp_unslash($_REQUEST['marker_image_size'])) : '100x100';
            $_atts = array(
                'keyword' => $keyword,
                'title' => $title,
                'address' => $address,
                'type' => $type,
                'city' => $city,
                'status' => $status,
                'owners' => $owners,
                'seats' => $seats,
                'doors' => $doors,
                'min-mileage' => $min_mileage,
                'max-mileage' => $max_mileage,
                'min-price' => $min_price,
                'max-price' => $max_price,
                'state' => $state,
                'country' => $country,
                'neighborhood' => $neighborhood,
                'label' => $label,
                'min-power' => $min_power,
                'max-power' => $max_power,
                'min-volume' => $min_volume,
                'max-volume' => $max_volume,
                'car_identity' => $car_identity,
                'stylings' => $stylings,
                'item_amount' => ($item_amount > 0) ? $item_amount : -1,
                'paged' => $paged,
            );
            $query_args = amotos_get_car_query_args($_atts);
	        $query_args = apply_filters('amotos_car_search_map_ajax_query_args',$query_args);
            $data = new WP_Query($query_args);
            $cars = array();
            $total_post = $data->found_posts;
	        ob_start();
            if($total_post > 0){
                $custom_car_image_size = '370x220';
                $car_item_class = array('car-item amotos-item-wrap');

                if ($search_type == 'map_and_content') {
                    ?>
                    <div class="list-car-result-ajax">
                    <?php
                }
                ?>
                <div class="amotos-car clearfix car-grid car-vertical-map-listing col-gap-10 columns-3 columns-md-3 columns-sm-2 columns-xs-1 columns-mb-1">
                <?php
                while ($data->have_posts()): $data->the_post();
                    $car_id = get_the_ID();
                    $car_location = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_location', true);
                    $lat = '';
                    $lng = '';
                    if (!empty($car_location['location'])) {
                        $lat_lng = explode(',', $car_location['location']);
                        $lat = $lat_lng[0];
                        $lng = $lat_lng[1];
                    } else {
                        $lat_lng = array();
                    }
                    $attach_id = get_post_thumbnail_id();
                    if (preg_match('/\d+x\d+/', $marker_image_size)) {
                        $image_sizes = explode('x', $marker_image_size);
                        $width=$image_sizes[0];$height= $image_sizes[1];
                        $image_src = amotos_image_resize_id($attach_id, $width, $height, true);
                    } else {
                        if (!in_array($marker_image_size, array('full', 'thumbnail'))) {
                            $marker_image_size = 'full';
                        }
                        $image_src_arr = wp_get_attachment_image_src($attach_id, $marker_image_size);
                        if (is_array($image_src_arr)) {
                        	$image_src = $image_src_arr[0];
                        }
                    }
                    //$marker_image_size
                    $car_type = get_the_terms($car_id, 'car-type');
                    $car_url = '';
                    if ($car_type) {
                        $car_type_id = $car_type[0]->term_id;
                        $car_type_icon = get_term_meta($car_type_id, 'car_type_icon', true);
                        if (is_array($car_type_icon) && count($car_type_icon) > 0) {
                            $car_url = $car_type_icon['url'];
                        }
                    }

                    $car_address = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_address', true);
                    $cars_price = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_price', true);
                    $cars_price = amotos_get_format_money($cars_price);
                    $veh = new stdClass();
                    $veh->image_url = $image_src;
                    $veh->title = get_the_title();
                    $veh->lat = $lat;
                    $veh->lng = $lng;
                    $veh->url = get_permalink();
                    $veh->price = $cars_price;
                    $veh->address = $car_address;
                    if ($car_url == '') {
                        $car_url = AMOTOS_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
                        $default_marker=amotos_get_option('marker_icon','');
                        if($default_marker!='')
                        {
                            if(is_array($default_marker)&& $default_marker['url']!='')
                            {
                                $car_url=$default_marker['url'];
                            }
                        }
                    }
                    $veh->marker_icon = $car_url;
                    array_push($cars, $veh);
                    if ($search_type == 'map_and_content') {
                        amotos_get_template('content-car.php', array(
                            'custom_car_image_size' => $custom_car_image_size,
                            'car_item_class' => $car_item_class,
                        ));
                    }
                endwhile;?>
                </div>
                <div class="car-search-map-paging-wrap">
                    <?php $max_num_pages = $data->max_num_pages;
                    set_query_var('paged', $paged);
                    amotos_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                    ?>
                </div>
                <?php
                if ($search_type == 'map_and_content') {?>
                    </div><?php
                }
            }
            wp_reset_postdata();
            $car_html = ob_get_clean();
            if (count($cars) > 0) {
                echo wp_json_encode(array('success' => true, 'cars' => $cars, 'car_html' => $car_html,'total_post'=>$total_post));
            } else {
                echo wp_json_encode(array('success' => false));
            }
            die();
        }

        public function amotos_ajax_change_price_on_status_change()
        {
            $slide_html=$min_price_html=$max_price_html='';
            $request_status = isset($_POST['status']) ? amotos_clean(wp_unslash($_POST['status']))  : '';
            $price_is_slider = isset($_POST['price_is_slider']) ? amotos_clean(wp_unslash($_POST['price_is_slider']))  : '';
            if (!empty($price_is_slider)&& $price_is_slider=='true') {
	            $min_price = amotos_get_option('car_price_slider_min',200);
	            $max_price = amotos_get_option('car_price_slider_max',2500000);
	            if ($request_status !== '') {
		            $car_price_slider_search_field = amotos_get_option('car_price_slider_search_field', '');
		            if ($car_price_slider_search_field != '') {
			            foreach ($car_price_slider_search_field as $data) {
				            $term_id = (isset($data['car_price_slider_car_status']) ? $data['car_price_slider_car_status'] : '');
				            $term = get_term_by('id', $term_id, 'car-status');
				            if ($term->slug == $request_status) {
					            $min_price = (isset($data['car_price_slider_min']) ? $data['car_price_slider_min'] : $min_price);
					            $max_price = (isset($data['car_price_slider_max']) ? $data['car_price_slider_max'] : $max_price);
					            break;
				            }
			            }
		            }
	            }

                $min_price_change = $min_price;
                $max_price_change = $max_price;
                $slide_html='<div class="amotos-sliderbar-price amotos-sliderbar-filter" data-min-default="'. esc_attr($min_price) .'" data-max-default="'. esc_attr($max_price) .'" data-min="'. esc_attr($min_price_change) .'" data-max="'. esc_attr($max_price_change) .'">
                    <div class="title-slider-filter">'. esc_html__('Price', 'auto-moto-stock').'[<span class="min-value">'. wp_kses_post(amotos_get_format_money($min_price_change))  .'</span> - <span class="max-value">'. wp_kses_post(amotos_get_format_money($max_price_change))  .'</span>]
                        <input type="hidden" name="min-price" class="min-input-request"
                               value="'. esc_attr($min_price_change) .'">
                        <input type="hidden" name="max-price" class="max-input-request"
                               value="'. esc_attr($max_price_change).'">
                    </div>
                    <div class="sidebar-filter"></div>
                </div>';
            }
            else
            {
	            $car_price_dropdown_min= apply_filters('amotos_price_dropdown_min_default', amotos_get_option('car_price_dropdown_min','0,100,300,500,700,900,1100,1300,1500,1700,1900')) ;
	            $car_price_dropdown_max= apply_filters('amotos_price_dropdown_max_default', amotos_get_option('car_price_dropdown_max','200,400,600,800,1000,1200,1400,1600,1800,2000')) ;
                $car_price_dropdown_search_field = amotos_get_option('car_price_dropdown_search_field','');
                if ($car_price_dropdown_search_field != '') {
                    foreach ($car_price_dropdown_search_field as $data) {
                        $term_id =(isset($data['car_price_dropdown_car_status']) ? $data['car_price_dropdown_car_status'] : '');
                        $term = get_term_by('id', $term_id, 'car-status');
                        if($term->slug==$request_status)
                        {
                            $car_price_dropdown_min = (isset($data['car_price_dropdown_min']) ? $data['car_price_dropdown_min'] : $car_price_dropdown_min);
                            $car_price_dropdown_max = (isset($data['car_price_dropdown_max']) ? $data['car_price_dropdown_max'] : $car_price_dropdown_max);
                            break;
                        }
                    }
                }
                $min_price_html='<option value="">'.esc_html__('Min Price', 'auto-moto-stock').'</option>';
                $car_price_array_min = explode(',', $car_price_dropdown_min);
                if (is_array($car_price_array_min) && !empty($car_price_array_min)) {
                    foreach ($car_price_array_min as $n) {
                        $min_price_html.='<option value="'. esc_attr($n) .'">';
                        $min_price_html.=  amotos_get_format_money_search_field($n).'</option>';
                    }
                }
                $max_price_html='<option value="">'.esc_html__('Max Price', 'auto-moto-stock').'</option>';
                $car_price_array_max = explode(',', $car_price_dropdown_max);
                if (is_array($car_price_array_max) && !empty($car_price_array_max)) {
                    foreach ($car_price_array_max as $n) {
                        $max_price_html.='<option value="'. esc_attr($n) .'">';
                        $max_price_html.=amotos_get_format_money_search_field($n).'</option>';
                    }
                }
            }
            echo wp_json_encode(array('slide_html' => $slide_html, 'min_price_html' => $min_price_html, 'max_price_html' => $max_price_html));
            die();
        }
    }
}