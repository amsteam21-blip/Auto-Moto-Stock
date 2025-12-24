<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * @see amotos_template_single_car_header
 * @see amotos_template_single_car_gallery
 * @see amotos_template_single_car_description
 * @see amotos_template_single_car_address
 * @see amotos_template_single_car_stylings
 * @see amotos_template_single_car_map
 * @see amotos_template_single_car_nearby_places
 * @see amotos_template_single_car_walk_score
 * @see amotos_template_single_car_contact_manager
 * @see amotos_template_single_car_footer
 * @see amotos_template_single_car_reviews
 *
 */
add_action('amotos_single_car_summary','amotos_template_single_car_header',5);
add_action( 'amotos_single_car_summary', 'amotos_template_single_car_gallery', 10 );
add_action( 'amotos_single_car_summary', 'amotos_template_single_car_description', 15 );
add_action( 'amotos_single_car_summary', 'amotos_template_single_car_address', 20 );
add_action( 'amotos_single_car_summary', 'amotos_template_single_car_stylings', 25 );
add_action('amotos_single_car_summary','amotos_template_single_car_attachments',30);
add_action('amotos_single_car_summary','amotos_template_single_car_map',35);
add_action('amotos_single_car_summary','amotos_template_single_car_nearby_places',40);
add_action('amotos_single_car_summary','amotos_template_single_car_walk_score',45);
add_action('amotos_single_car_summary','amotos_template_single_car_contact_manager',50);
add_action('amotos_single_car_summary','amotos_template_single_car_footer',90);
add_action('amotos_single_car_summary','amotos_template_single_car_reviews',95);

/**
 * @see amotos_template_loop_car_action_view_gallery
 * @see amotos_template_loop_car_action_favorite
 * @see amotos_template_loop_car_action_compare
 */
add_action('amotos_loop_car_action','amotos_template_loop_car_action_view_gallery',5);
add_action('amotos_loop_car_action','amotos_template_loop_car_action_favorite',10);
add_action('amotos_loop_car_action','amotos_template_loop_car_action_compare',15);

/**
 * @see amotos_template_loop_car_action
 * @see amotos_template_loop_car_featured_label
 * @see amotos_template_loop_car_term_status
 * @see amotos_template_loop_car_link
 */
add_action('amotos_after_loop_car_thumbnail','amotos_template_loop_car_action',5);
add_action('amotos_after_loop_car_thumbnail','amotos_template_loop_car_featured_label',10);
add_action('amotos_after_loop_car_thumbnail','amotos_template_loop_car_term_status',15);
add_action('amotos_after_loop_car_thumbnail','amotos_template_loop_car_link',20);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 */
add_action('amotos_loop_car_heading','amotos_template_loop_car_title',5);
add_action('amotos_loop_car_heading','amotos_template_loop_car_price',10);

/**
 * @see amotos_template_loop_car_location
 * @see amotos_template_loop_car_meta
 * @see amotos_template_loop_car_excerpt
 * @see amotos_template_loop_car_info
 */
add_action('amotos_after_loop_car_heading','amotos_template_loop_car_location',5);
add_action('amotos_after_loop_car_heading','amotos_template_loop_car_meta',10);
add_action('amotos_after_loop_car_heading','amotos_template_loop_car_excerpt',15);
add_action('amotos_after_loop_car_heading','amotos_template_loop_car_info',20);

/**
 * @see amotos_template_loop_car_type
 * @see amotos_template_loop_car_manager
 * @see amotos_template_loop_car_date
 */
add_action('amotos_loop_car_meta','amotos_template_loop_car_type',5);
add_action('amotos_loop_car_meta','amotos_template_loop_car_manager',10);
add_action('amotos_loop_car_meta','amotos_template_loop_car_date',15);

/**
 * @see amotos_template_loop_car_mileage
 * @see amotos_template_loop_car_power
 * @see amotos_template_loop_car_volume
 * @see amotos_template_loop_car_seats
 * @see amotos_template_loop_car_owners
 */
add_action('amotos_loop_car_info','amotos_template_loop_car_mileage',5);
add_action('amotos_loop_car_info','amotos_template_loop_car_power',10);
add_action('amotos_loop_car_info','amotos_template_loop_car_volume',15);
add_action('amotos_loop_car_info','amotos_template_loop_car_seats',20);
add_action('amotos_loop_car_info','amotos_template_loop_car_owners',25);

/**
 * @see amotos_template_archive_car_heading
 * @see amotos_template_archive_car_action
 */
add_action('amotos_before_archive_car','amotos_template_archive_car_heading',10,4);
add_action('amotos_before_archive_car','amotos_template_archive_car_action',15);

/**
 * @see amotos_template_archive_car_action_status
 * @see amotos_template_archive_car_action_orderby
 * @see amotos_template_archive_car_action_switch_layout
 */
add_action('amotos_archive_car_actions','amotos_template_archive_car_action_status',5);
add_action('amotos_archive_car_actions','amotos_template_archive_car_action_orderby',10);
add_action('amotos_archive_car_actions','amotos_template_archive_car_action_switch_layout',15);

/**
 * @see amotos_template_car_advanced_search_form
 */
add_action('amotos_before_advanced_search','amotos_template_car_advanced_search_form', 10,2);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 * @see amotos_template_loop_car_location
 */
add_action('amotos_sc_car_gallery_loop_car_content','amotos_template_loop_car_title',5);
add_action('amotos_sc_car_gallery_loop_car_content','amotos_template_loop_car_price',10);
add_action('amotos_sc_car_gallery_loop_car_content','amotos_template_loop_car_location',15);

/**
 * @@see amotos_template_loop_car_link
 */
add_action('amotos_sc_car_gallery_after_loop_car_content','amotos_template_loop_car_link',5);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 * @see amotos_template_loop_car_term_status
 * @see amotos_template_loop_car_location
 */
add_action('amotos_sc_car_slider_layout_navigation_middle_loop_car_heading','amotos_template_loop_car_price',10);
add_action('amotos_sc_car_slider_layout_navigation_middle_loop_car_heading','amotos_template_loop_car_term_status',15);
add_action('amotos_sc_car_slider_layout_navigation_middle_loop_car_heading','amotos_template_loop_car_location',20);

/**
 * @see amotos_template_loop_car_title
 */
add_action('amotos_before_sc_car_slider_layout_navigation_middle_loop_car_heading','amotos_template_loop_car_title', 5);

/**
 * @see amotos_template_loop_car_info_layout_2
 */
add_action('amotos_after_sc_car_slider_layout_navigation_middle_loop_car_content','amotos_template_loop_car_info_layout_2',5);

/**
 * @see amotos_template_loop_car_location
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 */
add_action('amotos_sc_car_gallery_layout_pagination_image_loop_car_heading','amotos_template_loop_car_location',5);
add_action('amotos_sc_car_gallery_layout_pagination_image_loop_car_heading','amotos_template_loop_car_title',10);
add_action('amotos_sc_car_gallery_layout_pagination_image_loop_car_heading','amotos_template_loop_car_price',15);

/**
 * @see amotos_template_loop_car_info_layout_2
 */
add_action('amotos_after_sc_car_gallery_layout_pagination_image_loop_car_content','amotos_template_loop_car_info_layout_2',5);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 * @see amotos_template_loop_car_location
 * @see amotos_template_loop_car_excerpt
 * @see amotos_template_loop_car_link_detail
 */
add_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content','amotos_template_loop_car_title',5);
add_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content','amotos_template_loop_car_price',10);
add_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content','amotos_template_loop_car_location',15);
add_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content','amotos_template_loop_car_excerpt',20);
add_action('amotos_sc_car_featured_layout_car_list_two_columns_loop_car_content','amotos_template_loop_car_link_detail',25);

/**
 * @see amotos_template_loop_car_title
 */
add_action('amotos_before_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_loop_car_title',5);

/**
 * @see amotos_template_loop_car_price
 * @see amotos_template_loop_car_status
 */
add_action('amotos_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_loop_car_price',5);
add_action('amotos_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_loop_car_status',10);

/**
 * @see amotos_template_loop_car_location
 * @see amotos_template_loop_car_excerpt
 * @see amotos_template_single_car_info
 */
add_action('amotos_after_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_loop_car_location', 5);
add_action('amotos_after_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_loop_car_excerpt', 10);
add_action('amotos_after_sc_car_featured_layout_car_single_carousel_loop_car_heading','amotos_template_single_car_info', 15);

/**
 * @see amotos_template_loop_car_identity
 * @see amotos_template_loop_car_mileage
 * @see amotos_template_loop_car_power
 * @see amotos_template_loop_car_volume
 * @see amotos_template_loop_car_seats
 * @see amotos_template_loop_car_owners
 */
add_action('amotos_single_car_info','amotos_template_loop_car_identity',5);
add_action('amotos_single_car_info','amotos_template_loop_car_mileage',10);
add_action('amotos_single_car_info','amotos_template_loop_car_power',15);
add_action('amotos_single_car_info','amotos_template_loop_car_volume',20);
add_action('amotos_single_car_info','amotos_template_loop_car_seats',25);
add_action('amotos_single_car_info','amotos_template_loop_car_owners',30);

/**
 * @see amotos_template_loop_car_title
 */
add_action('amotos_before_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_loop_car_title',5);

/**
 * @see amotos_template_loop_car_price
 * @see amotos_template_loop_car_status
 */
add_action('amotos_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_loop_car_price',5);
add_action('amotos_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_loop_car_status',10);

/**
 * @see amotos_template_loop_car_location
 * @see amotos_template_loop_car_excerpt
 * @see amotos_template_single_car_info
 */
add_action('amotos_after_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_loop_car_location', 5);
add_action('amotos_after_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_loop_car_excerpt', 10);
add_action('amotos_after_sc_car_featured_layout_car_sync_carousel_loop_car_heading','amotos_template_single_car_info', 15);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 */
add_action('amotos_sc_car_featured_layout_car_cities_filter_loop_car_heading','amotos_template_loop_car_title',5);
add_action('amotos_sc_car_featured_layout_car_cities_filter_loop_car_heading','amotos_template_loop_car_price',10);

/**
 * @see amotos_template_single_car_info
 */
add_action('amotos_after_sc_car_featured_layout_car_cities_filter_loop_car_heading','amotos_template_single_car_info',5);

/**
 * @see amotos_template_single_car_title
 * @see amotos_template_single_car_header_price_location
 */
add_action('amotos_single_car_header_info','amotos_template_single_car_title',5);
add_action('amotos_single_car_header_info','amotos_template_single_car_header_price_location',10);

/**
 * @see amotos_template_single_car_header_meta_action
 */
add_action('amotos_after_single_car_header_info','amotos_template_single_car_header_meta_action',5);


/**
 * @see amotos_template_single_car_price
 * @see amotos_template_single_car_status
 * @see amotos_template_single_car_location
 */
add_action('amotos_single_car_header_price_location','amotos_template_single_car_price',5);
add_action('amotos_single_car_header_price_location','amotos_template_single_car_status',10);
add_action('amotos_single_car_header_price_location','amotos_template_single_car_location',15);

/**
 * @see amotos_template_single_car_info
 * @see amotos_template_single_car_action
 */
add_action('amotos_single_car_header_meta_action','amotos_template_single_car_info',5);
add_action('amotos_single_car_header_meta_action','amotos_template_single_car_action',10);

/**
 * @see amotos_template_single_car_action_social_share
 * @see amotos_template_loop_car_action_favorite
 * @see amotos_template_loop_car_action_compare
 * @see amotos_template_single_car_action_print
 */
add_action('amotos_single_car_action','amotos_template_single_car_action_social_share',5);
add_action('amotos_single_car_action','amotos_template_loop_car_action_favorite',10);
add_action('amotos_single_car_action','amotos_template_loop_car_action_compare',15);
add_action('amotos_single_car_action','amotos_template_single_car_action_print',20);

/**
 * @see amotos_template_print_car_logo
 * @see amotos_template_print_car_header
 * @see amotos_template_single_car_info
 * @see amotos_template_print_car_image
 */
add_action('amotos_before_print_car_summary','amotos_template_print_car_logo',5);
add_action('amotos_before_print_car_summary','amotos_template_print_car_header',10);
add_action('amotos_before_print_car_summary','amotos_template_single_car_info',15);
add_action('amotos_before_print_car_summary','amotos_template_print_car_image',20);

/**
 * @see amotos_template_single_car_title
 * @see amotos_template_single_car_location
 * @see amotos_template_single_car_price
 */
add_action('amotos_print_car_header_left','amotos_template_single_car_title',5);
add_action('amotos_print_car_header_left','amotos_template_single_car_location',10);
add_action('amotos_print_car_header_left','amotos_template_single_car_price',15);

/**
 * @see amotos_template_print_car_qr_image
 */
add_action('amotos_print_car_header_right','amotos_template_print_car_qr_image',5);

/**
 * @see amotos_template_single_car_description
 * @see amotos_template_single_car_address
 * @see amotos_template_single_car_overview
 * @see amotos_template_single_car_styling
 * @see amotos_template_print_car_contact_manager
 */
add_action('amotos_print_car_summary','amotos_template_single_car_description',5);
add_action('amotos_print_car_summary','amotos_template_single_car_address',10);
add_action('amotos_print_car_summary','amotos_template_single_car_overview',15);
add_action('amotos_print_car_summary','amotos_template_single_car_styling',20);
add_action('amotos_print_car_summary','amotos_template_print_car_contact_manager',25);

/**
 * @see amotos_template_my_car_search
 * @see amotos_template_my_car_filter
 */
add_action('amotos_my_car_toolbar','amotos_template_my_car_search',5);
add_action('amotos_my_car_toolbar','amotos_template_my_car_filter',10);

/**
 * @see amotos_template_loop_my_car_title
 * @see amotos_template_loop_my_car_meta
 * @see amotos_template_loop_my_car_action
 */
add_action('amotos_loop_my_car_content','amotos_template_loop_my_car_title',5);
add_action('amotos_loop_my_car_content','amotos_template_loop_my_car_meta',10);
add_action('amotos_loop_my_car_content','amotos_template_loop_my_car_action',15);

/**
 * @see amotos_template_loop_my_car_meta_location
 * @see amotos_template_loop_my_car_meta_view
 * @see amotos_template_loop_my_car_meta_date
 * @see amotos_template_loop_my_car_meta_expire_date
 */
add_action('amotos_loop_my_car_meta','amotos_template_loop_my_car_meta_location',5);
add_action('amotos_loop_my_car_meta','amotos_template_loop_my_car_meta_view',10);
add_action('amotos_loop_my_car_meta','amotos_template_loop_my_car_meta_date',15);
add_action('amotos_loop_my_car_meta','amotos_template_loop_my_car_meta_expire_date',20);

/**
 * @see amotos_template_loop_my_car_featured
 * @see amotos_template_loop_my_car_status
 */
add_action('amotos_after_loop_my_car_thumbnail','amotos_template_loop_my_car_featured',5);
add_action('amotos_after_loop_my_car_thumbnail','amotos_template_loop_my_car_status',10);

/**
 * @see amotos_template_loop_car_featured_label
 * @see amotos_template_loop_car_term_status
 * @see amotos_template_loop_car_link
 */
add_action('amotos_after_loop_compare_car_thumbnail','amotos_template_loop_car_featured_label',5);
add_action('amotos_after_loop_compare_car_thumbnail','amotos_template_loop_car_term_status',10);
add_action('amotos_after_loop_compare_car_thumbnail','amotos_template_loop_car_link',15);

/**
 * @see amotos_template_loop_car_title
 * @see amotos_template_loop_car_price
 */
add_action('amotos_loop_compare_car_heading','amotos_template_loop_car_title',5);
add_action('amotos_loop_compare_car_heading','amotos_template_loop_car_price',10);

/**
 * @see amotos_template_loop_car_location
 */
add_action('amotos_after_loop_compare_car_heading','amotos_template_loop_car_location',5);








