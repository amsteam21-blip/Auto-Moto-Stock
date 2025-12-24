<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if (!function_exists('amotos_dashboard_get_menu')) {
    function amotos_dashboard_get_menu() {
        $menu = array();

        $my_profile_url = amotos_get_permalink( 'my_profile' );
        if ($my_profile_url) {
            $menu['my_profile'] = array(
                'title' => esc_html__('My Profile', 'auto-moto-stock'),
                'link' => $my_profile_url,
                'icon' => 'fa fa-user',
                'priority' => 10
            );
        }

        $allow_submit        = amotos_allow_submit();
        if ($allow_submit) {
            $my_cars_url = amotos_get_permalink( 'my_cars' );
            if ($my_cars_url) {

                $total_cars    = AMOTOS_Car::getInstance()->get_total_my_cars( array( 'publish', 'pending', 'expired', 'hidden' ) );
                $menu['my_cars'] = array(
                    'title' => esc_html__('My Vehicles', 'auto-moto-stock'),
                    'link' => $my_cars_url,
                    'icon' => 'fa fa-list-alt',
                    'count' => $total_cars,
                    'priority' => 20
                );
            }

            $paid_submission_type = amotos_get_option( 'paid_submission_type', 'no' );
            if ($paid_submission_type != 'no') {
                $my_invoices_url = amotos_get_permalink( 'my_invoices' );
                if ($my_invoices_url) {
                    $total_invoices      = AMOTOS_Invoice::getInstance()->get_total_my_invoice();
                    $menu['my_invoices'] = array(
                        'title' => esc_html__('My Invoices', 'auto-moto-stock'),
                        'link' => $my_invoices_url,
                        'icon' => 'fa fa-file-text-o',
                        'count' => $total_invoices,
                        'priority' => 30
                    );
                }
            }
        }

        $enable_favorite = amotos_get_option( 'enable_favorite_car', 1 );
        if ($enable_favorite == 1) {
            $my_favorites_url = amotos_get_permalink( 'my_favorites' );
            if ($my_favorites_url) {
                $total_favorite      = AMOTOS_Car::getInstance()->get_total_favorite();
                $menu['my_favorites'] = array(
                    'title' => esc_html__('My Favorites', 'auto-moto-stock'),
                    'link' => $my_favorites_url,
                    'icon' => 'fa fa-heart',
                    'count' => $total_favorite,
                    'priority' => 40
                );
            }
        }

        $enable_saved_search = amotos_get_option( 'enable_saved_search', 1 );
        if ($enable_saved_search == 1) {
            $my_save_search_url =  amotos_get_permalink( 'my_save_search' );
            if ($my_save_search_url) {
                $total_save_search   = AMOTOS_Save_Search::getInstance()->get_total_save_search();
                $menu['my_save_search'] = array(
                    'title' => esc_html__('My Saved Search', 'auto-moto-stock'),
                    'link' => $my_save_search_url,
                    'icon' => 'fa fa-search',
                    'count' => $total_save_search,
                    'priority' => 50
                );
            }
        }

        if ($allow_submit) {
            $submit_car_url =  amotos_get_permalink( 'submit_car' );
            if ($submit_car_url) {
                $menu['submit_car'] = array(
                    'title' => esc_html__('Submit Vehicle', 'auto-moto-stock'),
                    'link' => $submit_car_url,
                    'icon' => 'fa fa-file-o',
                    'priority' => 60
                );
            }
        }

        $menu = apply_filters( 'amotos_dashboard_menu', $menu);
        uasort( $menu, 'amotos_sort_by_order_callback' );
        return $menu;
    }
}

if (!function_exists('amotos_dashboard_get_menu_title')) {
    function amotos_dashboard_get_menu_title($menu) {
        $menu_title = '';
        switch ( $menu ) {
            case "my_profile":
                $menu_title =  esc_html__( 'My Profile', 'auto-moto-stock' );
                break;
            case "my_cars":
                $menu_title = esc_html__( 'My Vehicles', 'auto-moto-stock' );
                break;
            case "my_invoices":
                $menu_title = esc_html__( 'My Invoices', 'auto-moto-stock' );
                break;
            case "my_favorites":
                $menu_title = esc_html__( 'My Favorites', 'auto-moto-stock' );
                break;
            case "my_save_search":
                $menu_title = esc_html__( 'My Saved Search', 'auto-moto-stock' );
                break;
        }

        return apply_filters('amotos_dashboard_menu_title',$menu_title, $menu);
    }
}