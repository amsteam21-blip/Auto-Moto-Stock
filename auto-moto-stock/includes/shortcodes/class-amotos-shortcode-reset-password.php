<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('AMOTOS_Shortcode_Reset_Password')) {
    class AMOTOS_Shortcode_Reset_Password
    {
        /**
         * Class AMOTOS_Shortcode_Reset_Password
         *
         * @param array $atts
         */
        public static function output($atts)
        {
            return amotos_get_template_html('account/reset-password.php', array('atts' => $atts));
        }
    }
}