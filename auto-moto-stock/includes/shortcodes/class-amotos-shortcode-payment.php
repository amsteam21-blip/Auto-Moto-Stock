<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('AMOTOS_Shortcode_Payment')) {
    /**
     * Shortcode Payment class.
     */
    class AMOTOS_Shortcode_Payment
    {
        /**
         * @param $atts
         */
        public static function output($atts)
        {
            return amotos_get_template_html('payment/payment.php', array('atts' => $atts));
        }
    }
}