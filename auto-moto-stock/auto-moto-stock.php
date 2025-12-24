<?php
/**
 * Plugin Name: Auto Moto Stock
 * Plugin URI: https://auto-moto-stock.com
 * Description: Auto Moto Stock is a plugin designed for the search and sale of vehicles (cars, track, motorhome, motorcycles, etc.). Suitable for dealers, staff, private sellers. Convenient and fully functional plugin. Built-in payment gateways, the ability to submit, search, view and track vehicles, ads, payments and more. With Auto Moto Stock you can easily create an automotive website.
 * Version: 1.0.0
 * Author: AMS Team
 * Author URI: http://www.auto-moto-stock.com
 * Text Domain: auto-moto-stock
 * Domain Path: /languages/
 * License: GPLv2 or later
 */

/*
Copyright 2025 by AMS Team

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if (! defined('WPINC')) {
    die;
}

if (! defined('AMOTOS_PLUGIN_VER')) {
    define('AMOTOS_PLUGIN_VER', '1.0.0');
}

if (! defined('AMOTOS_PLUGIN_FILE')) {
    define('AMOTOS_PLUGIN_FILE', __FILE__);
}

if (! defined('AMOTOS_PLUGIN_NAME')) {
    $plugin_dir_name = dirname(__FILE__);
    $plugin_dir_name = str_replace('\\', '/', $plugin_dir_name);
    $plugin_dir_name = explode('/', $plugin_dir_name);
    $plugin_dir_name = end($plugin_dir_name);
    define('AMOTOS_PLUGIN_NAME', $plugin_dir_name);
}

if (! defined('AMOTOS_PLUGIN_DIR')) {
    $plugin_dir = plugin_dir_path(__FILE__);
    define('AMOTOS_PLUGIN_DIR', $plugin_dir);
}

if (! defined('AMOTOS_PLUGIN_URL')) {
    $plugin_url = plugins_url('/', __FILE__);
    define('AMOTOS_PLUGIN_URL', $plugin_url);
}

if (! defined('AMOTOS_PLUGIN_PREFIX')) {
    define('AMOTOS_PLUGIN_PREFIX', 'amotos_');
}

if (! defined('AMOTOS_METABOX_PREFIX')) {
    define('AMOTOS_METABOX_PREFIX', 'auto_moto_');
}

if (! defined('AMOTOS_OPTIONS_NAME')) {
    define('AMOTOS_OPTIONS_NAME', 'amotos_options');
}

if (! defined('AMOTOS_AJAX_URL')) {
    $ajax_url        = admin_url('admin-ajax.php', 'relative');
    $my_current_lang = apply_filters('wpml_current_language', null);
    if ($my_current_lang) {
        $ajax_url = add_query_arg('amotos_wpml_lang', $my_current_lang, $ajax_url);
    }
    define('AMOTOS_AJAX_URL', $ajax_url);
}

if (! defined('AMOTOS_ROUNDING_PRECISION')) {
    define('AMOTOS_ROUNDING_PRECISION', 6);
}

/**
 * The code that runs during plugin activation.
 */
function amotos_activate()
{
    require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-activator.php';
    AMOTOS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function amotos_deactivate()
{
    require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-deactivator.php';
    AMOTOS_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'amotos_activate');
register_deactivation_hook(__FILE__, 'amotos_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require AMOTOS_PLUGIN_DIR . 'includes/class-auto-moto-stock.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
AMOTOS()->run();

add_filter('squick_google_map_api_url', 'amotos_google_map_api_url', 1);
function amotos_google_map_api_url()
{
    $googlemap_ssl     = amotos_get_option('googlemap_ssl', 0);
    $googlemap_api_key = amotos_get_option('googlemap_api_key', 'AIzaSyCLyuWY0RUhv7GxftSyI8Ka1VbeU7CTDls');
    if (esc_html($googlemap_ssl) == 1 || is_ssl()) {
        return 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key);
    } else {
        return 'http://maps.googleapis.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key);
    }
}
