<?php
/**
 * @var $sf_item_wrap
 * @var $manager_layout_style
 * @var $custom_manager_image_size
 */
/**
 * amotos_before_loop_manager hook.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
do_action('amotos_before_loop_manager');
/**
 * amotos_loop_manager hook.
 *
 * @hooked amotos_loop_manager - 10
 */
do_action('amotos_loop_manager', $sf_item_wrap, $manager_layout_style, $custom_manager_image_size);
/**
 * amotos_after_loop_manager hook.
 */
do_action('amotos_after_loop_manager');