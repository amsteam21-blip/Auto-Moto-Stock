<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Trans_Action')) {
    /**
     * Class AMOTOS_Trans_Action
     */
    class AMOTOS_Trans_Action
    {
        /**
         * Insert action
         * @param $payment_type
         * @param $item_id
         * @param $user_id
         * @param $payment_for
         * @param $payment_method
         * @param int $paid
         * @param string $payment_id
         * @param string $payer_id
         * @return int|WP_Error
         */
        public function insert_trans_action( $payment_type, $item_id, $user_id,$payment_for, $payment_method,$paid=0,$payment_id='',$payer_id='',$status=1,$message='' ) {

            $price_per_submission = amotos_get_option('price_per_listing','0');
            $price_per_submission      = floatval( $price_per_submission );

            $price_featured_submission = amotos_get_option('price_featured_listing','0');
            $price_featured_submission = floatval( $price_featured_submission );
            $total_money=0;
            if( $payment_type != 'Package' ) {
                if( $payment_for ==3 ) {
                    $total_money = $price_featured_submission;
                } else {
                    if( $payment_for == 2 ) {
                        $total_money = $price_per_submission+$price_featured_submission;
                    } elseif($payment_for == 1) {
                        $total_money = $price_per_submission;
                    }
                }
            } else {
                $package_free = get_post_meta($item_id, AMOTOS_METABOX_PREFIX . 'package_free', true);
                if($package_free==1)
                {
                    $total_money=0;
                }
                else
                {
                    $total_money = get_post_meta( $item_id,AMOTOS_METABOX_PREFIX. 'package_price', true);
                }
            }

	        $total_money = apply_filters('amotos_trans_action_total_money', $total_money, $payment_type, $item_id, $payment_for, $payment_method);

            $time = time();
            $trans_action_date = gmdate('Y-m-d H:i:s', $time);

            $amotos_meta = array();
            $amotos_meta['trans_action_item_id'] = $item_id;
            $amotos_meta['trans_action_item_price'] = $total_money;
            $amotos_meta['trans_action_purchase_date'] = $trans_action_date;
            $amotos_meta['trans_action_user_id'] = $user_id;
            $amotos_meta['trans_action_payment_type'] = $payment_type;
            $amotos_meta['trans_action_payment_method'] = $payment_method;
            $amotos_meta['trans_payment_id'] = $payment_id;
            $amotos_meta['trans_payer_id'] = $payer_id;
            $amotos_meta['trans_action_message'] = $message;
            $args = array(
                'post_title'	=> 'Action',
                'post_status'	=> 'publish',
                'post_type'     => 'trans_action'
            );
            $trans_action_id =  wp_insert_post( $args );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_user_id', $user_id );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_item_id', $item_id );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_price', $total_money );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_date', $trans_action_date );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_payment_type', $payment_type );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_payment_method', $payment_method );
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_payment_status', $paid);
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_payment_id', $payment_id);
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_payer_id', $payer_id);
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_status', $status);
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_message', $message);
            update_post_meta( $trans_action_id, AMOTOS_METABOX_PREFIX. 'trans_action_meta', $amotos_meta );

            $update_post = array(
                'ID'         => $trans_action_id,
                'post_title' => 'Action '.$trans_action_id,
            );
            wp_update_post( $update_post );
            return $trans_action_id;
        }

        /**
         * get_trans_action_meta
         * @param $post_id
         * @param bool|false $field
         * @return array|bool|mixed
         */
        public function get_trans_action_meta($post_id, $field = false)
        {
            $defaults = array(
                'trans_action_item_id' => '',
                'trans_action_item_price' => '',
                'trans_action_purchase_date' => '',
                'trans_action_user_id' => '',
                'trans_action_payment_type' => '',
                'trans_action_payment_method' => '',
                'trans_payment_id' => '',
                'trans_payer_id' => '',
            );
            $meta = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'trans_action_meta', true);
            $meta = wp_parse_args((array)$meta, $defaults);

            if ($field) {
                if (isset($meta[$field])) {
                    return $meta[$field];
                } else {
                    return false;
                }
            }
            return $meta;
        }
    }
}