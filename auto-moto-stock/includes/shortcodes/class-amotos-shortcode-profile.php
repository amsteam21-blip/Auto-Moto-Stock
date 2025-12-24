<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('AMOTOS_Shortcode_Profile')) {
    class AMOTOS_Shortcode_Profile
    {
        /**
         * Class AMOTOS_Shortcode_Profile
         *
         * @param array $atts
         */
        public static function output($atts)
        {
            return amotos_get_template_html('account/my-profile.php', array('atts' => $atts));
        }
    }
}