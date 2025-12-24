<?php

/**
 * Class AMOTOS_Admin_Texts
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (! class_exists('AMOTOS_Admin_Texts')) {
    class AMOTOS_Admin_Texts
    {
        private $plugin_file;
        public function __construct()
        {
            $this->plugin_file = plugin_basename(AMOTOS_PLUGIN_FILE);
        }
        /**
         * Add hooks
         */
        public function add_hooks()
        {
            global $pagenow;
            // Hooks for Plugins overview page
            if ($pagenow === 'plugins.php') {
                add_filter('plugin_action_links_' . $this->plugin_file, [$this, 'add_plugin_settings_link'], 10, 2);
                add_filter('plugin_row_meta', [$this, 'add_plugin_meta_links'], 10, 2);
            }
        }

        /**
         * Add the settings link to the Plugins overview
         *
         * @param array $links
         * @param       $file
         *
         * @return array
         */
        public function add_plugin_settings_link($links, $file)
        {
            if ($file !== $this->plugin_file) {
                return $links;
            }
            $settings_link = '<a href="' . esc_url(admin_url('themes.php?page=amotos_options')) . '">' . esc_html__('Settings', 'auto-moto-stock') . '</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        /**
         * Adds meta links to the plugin in the WP Admin > Plugins screen
         *
         * @param array $links
         * @param string $file
         *
         * @return array
         */
        public function add_plugin_meta_links($links, $file)
        {
            if ($file !== $this->plugin_file) {
                return $links;
            }
            // documentation
            $links[  ] = '<a target="_blank" href="http://document.auto-moto-stock.com/auto-moto-stock">' . esc_html__('Documentation', 'auto-moto-stock') . '</a>';
            // extensions
            $links[  ] = '<a target="_blank" href="http://plugins.auto-moto-stock.com/ams/extensions/">' . esc_html__('Extensions', 'auto-moto-stock') . '</a>';
            // premium theme
            $links[  ] = '<a target="_blank" href="https://stocktheme.com/highway">' . esc_html__('Premium Theme', 'auto-moto-stock') . '</a>';
            $links     = (array) apply_filters('amotos_admin_plugin_meta_links', $links);
            return $links;
        }
    }
}
