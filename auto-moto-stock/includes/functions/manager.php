<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function amotos_manager_get_review_by_user_id($managerId, $userId) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT cm.comment_ID, cm.comment_content, mt.meta_value as rate FROM $wpdb->comments as cm 
                                             INNER JOIN $wpdb->commentmeta as mt ON cm.comment_ID = mt.comment_id 
                                             WHERE 
                                                cm.comment_post_ID = %d 
                                                AND cm.user_id = %d
                                                AND mt.meta_key = 'manager_rating'
                                             ORDER BY cm.comment_ID DESC",
        $managerId,
        $userId));
}

function amotos_manager_get_list_review($managerId,$userId)
{
    global $wpdb;
    return $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->comments as cm
                                       LEFT JOIN $wpdb->commentmeta as mt ON cm.comment_ID = mt.comment_id
                                       WHERE cm.comment_post_ID = %d
                                       AND (cm.comment_approved = 1 OR cm.user_id = %d)  
                                       AND mt.meta_key = 'manager_rating'
                                       ORDER BY cm.comment_ID DESC",
        $managerId,
        $userId) );
}

function amotos_manager_get_rating($managerId) {
    $data = get_post_meta($managerId, AMOTOS_METABOX_PREFIX . 'manager_rating', true);
    if (!is_array($data)) {
        $data = array(
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        );
    }
    return $data;
}

function amotos_manager_get_sort_by() {
    return apply_filters('amotos_manager_sort_by',array(
        'default' => esc_html__('Default Order','auto-moto-stock'),
        'a_name' => esc_html__('Name (A to Z)','auto-moto-stock'),
        'd_name' => esc_html__('Name (Z to A)','auto-moto-stock'),
        'a_date' => esc_html__('Date (Old to New)','auto-moto-stock'),
        'd_date' => esc_html__('Date (New to Old)','auto-moto-stock')
    ));
}



