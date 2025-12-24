<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @see amotos_template_manager_reviews
 */
add_action('amotos_single_manager_summary','amotos_template_manager_reviews',15);

/**
 * @see amotos_template_archive_manager_heading
 * @see amotos_template_archive_manager_action
 */
add_action('amotos_before_archive_manager','amotos_template_archive_manager_heading',5);
add_action('amotos_before_archive_manager','amotos_template_archive_manager_action',10);

/**
 * @see amotos_template_archive_manager_action_search
 * @see amotos_template_archive_manager_action_orderby
 * @see amotos_template_archive_manager_action_switch_layout
 */
add_action('amotos_archive_manager_actions','amotos_template_archive_manager_action_search',5);
add_action('amotos_archive_manager_actions','amotos_template_archive_manager_action_orderby',10);
add_action('amotos_archive_manager_actions','amotos_template_archive_manager_action_switch_layout',15);



