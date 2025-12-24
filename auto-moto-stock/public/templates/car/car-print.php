<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $isRTL
 * @var $car_id
 */
$post_object = get_post( $car_id );
if (! is_a( $post_object, 'WP_Post' ) || $post_object->post_type !== 'car' ) {
    echo esc_html__('Posts ineligible to print!', 'auto-moto-stock');
    return;
}
remove_action( 'wp_head',             '_wp_render_title_tag',            1     );
remove_action( 'wp_head',             'wp_resource_hints',               2     );
remove_action( 'wp_head',             'feed_links',                      2     );
remove_action( 'wp_head',             'feed_links_extra',                3     );
remove_action( 'wp_head',             'rsd_link'                               );
remove_action( 'wp_head',             'wlwmanifest_link'                       );
remove_action( 'wp_head',             'adjacent_posts_rel_link_wp_head', 10);
remove_action( 'publish_future_post', 'check_and_publish_future_post',   10);
remove_action( 'wp_head',             'noindex',                          1    );
remove_action( 'wp_head',             'print_emoji_detection_script',     7    );
remove_action( 'wp_head',             'wp_generator'                           );
remove_action( 'wp_head',             'rel_canonical'                          );
remove_action( 'wp_head',             'wp_shortlink_wp_head',            10);
remove_action( 'wp_head',             'wp_custom_css_cb',                101   );
remove_action( 'wp_head',             'wp_site_icon',                    99    );
//add_action('wp_enqueue_scripts','amotos_dequeue_assets_print_car',9999);
function amotos_dequeue_assets_print_car() {
    foreach (wp_styles()->registered as $k => $v) {
        if (!in_array($k,array('bootstrap','font-awesome','star-rating',AMOTOS_PLUGIN_PREFIX . 'main',AMOTOS_PLUGIN_PREFIX . 'main-rtl',AMOTOS_PLUGIN_PREFIX . 'car-print',AMOTOS_PLUGIN_PREFIX . 'car-print-rtl'))) {
            unset(wp_styles()->registered[$k]);
        }
    }
}

?>
<html <?php language_attributes(); ?>>
    <head>
        <?php wp_head(); ?>
        <script type="text/javascript">
            (function( $ ) {
                'use strict';
                $(document).ready(function () {
                    $(window).on('load',function (){
                        print();
                    });
                });
            })( jQuery );
        </script>
    </head>
    <body <?php body_class(); ?>>
    <?php
    setup_postdata( $GLOBALS['post'] =& $post_object );
    ?>
    <div class="amotos__car-print-wrap" id="car-print-wrap">

        <?php
        /**
         * amotos_before_print_car_summary hook.
         *
         * @hooked amotos_template_print_car_logo - 5
         * @hooked amotos_template_print_car_header - 10
         * @hooked amotos_template_single_car_info - 15
         * @hooked amotos_template_print_car_image - 20
         */
        do_action('amotos_before_print_car_summary');
        ?>
        <div class="amotos__print-car-summary">
            <?php
            /**
             * amotos_print_car_summary hook
             *
             * @hooked amotos_template_single_car_description - 5
             * @hooked amotos_template_single_car_address - 10
             * @hooked amotos_template_single_car_overview - 15
             * @hooked amotos_template_single_car_styling - 20
             * @hooked amotos_template_print_car_contact_manager - 254
             */
            do_action('amotos_print_car_summary');
            ?>
        </div>
    </div>
    <?php
        wp_reset_postdata();
    ?>
    </body>
</html>
