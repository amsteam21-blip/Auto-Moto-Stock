<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * @see amotos_template_archive_dealer_action_search
 * @see amotos_template_archive_dealer_action_orderby
 */
add_action('amotos_archive_dealer_actions','amotos_template_archive_dealer_action_search',5);
add_action('amotos_archive_dealer_actions','amotos_template_archive_dealer_action_orderby',10);

/**
 * @see amotos_template_loop_dealer_image
 */
add_action('amotos_before_loop_dealer_content','amotos_template_loop_dealer_image',10);

/**
 * @see amotos_template_loop_dealer_title
 * @see amotos_template_loop_dealer_address
 * @see amotos_template_loop_dealer_social
 */
add_action('amotos_loop_dealer_heading','amotos_template_loop_dealer_title_address_start',4);
add_action('amotos_loop_dealer_heading','amotos_template_loop_dealer_title',5);
add_action('amotos_loop_dealer_heading','amotos_template_loop_dealer_address',10);
add_action('amotos_loop_dealer_heading','amotos_template_loop_dealer_title_address_end',11);
add_action('amotos_loop_dealer_heading','amotos_template_loop_dealer_social',15);

/**
 * @see amotos_template_loop_dealer_desc
 * @see amotos_template_loop_dealer_meta
 */
add_action('amotos_after_loop_dealer_heading','amotos_template_loop_dealer_desc',5);
add_action('amotos_after_loop_dealer_heading','amotos_template_loop_dealer_meta',10);

/**
 * @see amotos_template_single_dealer_header
 */
add_action('amotos_taxonomy_dealer_summary','amotos_template_single_dealer_header',5);

/**
 * @see amotos_template_single_dealer_tabs
 * @see amotos_template_single_dealer_manager
 */
add_action('amotos_taxonomy_dealer_after_summary','amotos_template_single_dealer_tabs',5);
add_action('amotos_taxonomy_dealer_after_summary','amotos_template_single_dealer_manager',10);

/**
 * @see amotos_template_single_dealer_title
 * @see amotos_template_single_dealer_address
 * @see amotos_template_single_dealer_meta
 * @see amotos_template_single_dealer_contact_info
 * @see amotos_template_single_dealer_social
 */
add_action('amotos_single_dealer_summary','amotos_template_single_dealer_title',5);
add_action('amotos_single_dealer_summary','amotos_template_single_dealer_address',10);
add_action('amotos_single_dealer_summary','amotos_template_single_dealer_meta',15);
add_action('amotos_single_dealer_summary','amotos_template_single_dealer_contact_info',20);
add_action('amotos_single_dealer_summary','amotos_template_single_dealer_social',25);

/**
 * @see amotos_template_single_dealer_image
 */
add_action('amotos_before_single_dealer_summary','amotos_template_single_dealer_image',5);

/**
 * @see amotos_template_single_dealer_contact_form
 */
add_action('amotos_after_single_dealer_summary','amotos_template_single_dealer_contact_form',5);




