<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('AMOTOS_Shortcode_Payment_Completed')) {
    /**
     * Shortcode Payment class.
     */
    class AMOTOS_Shortcode_Payment_Completed
    {
        /**
         * @param $atts
         */
        public static function output($atts)
        {
            return amotos_get_template_html('payment/payment-completed.php', array('atts' => $atts));
        }
    }
}