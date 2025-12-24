<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('AMOTOS_Shortcode_Register')) {
    class AMOTOS_Shortcode_Register
    {
        /**
         * Class AMOTOS_Shortcode_Register
         *
         * @param array $atts
         */
        public static function output($atts)
        {
            return amotos_get_template_html('account/register.php', array('atts' => $atts));
        }
    }
}