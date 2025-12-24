<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Manager')) {
    /**
     * Class AMOTOS_Manager
     */
    class AMOTOS_Manager
    {
	    /*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }

        /**
         * submit review
         */
        public function submit_review_ajax()
        {
            check_ajax_referer('amotos_submit_review_ajax_nonce', 'amotos_security_submit_review');
            global $wpdb, $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user = get_user_by('id', $user_id);
            $manager_id = isset($_POST['manager_id']) ? absint(amotos_clean(wp_unslash($_POST['manager_id']))) : -1;
            $rating_value = isset($_POST['rating']) ? amotos_clean(wp_unslash($_POST['rating'])) : '';
            $message = isset($_POST['message']) ? wp_filter_post_kses($_POST['message']) : '';
            if (empty($message)) {
                wp_send_json_error(esc_html__('Message is Empty!','auto-moto-stock'));
            }
            $my_review = amotos_manager_get_review_by_user_id($manager_id,$user_id);
            $comment_approved = 1;
            $auto_publish_review_manager = amotos_get_option( 'review_manager_approved_by_admin',0 );
            if ($auto_publish_review_manager == 1) {
                $comment_approved = 0;
            }
            if ( $my_review === null ) {
            	$data = array();
                $user = $user->data;
                $data['comment_post_ID'] = $manager_id;
                $data['comment_content'] = $message;
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;
                $data['comment_author'] = $user->user_login;
                $data['comment_author_email'] = $user->user_email;
                $data['comment_author_url'] = $user->user_url;
                $data['user_id'] = $user_id;
                $comment_id = wp_insert_comment($data);

                add_comment_meta($comment_id, 'manager_rating', $rating_value);
                if ($comment_approved == 1) {
                    do_action('amotos_manager_rating_meta',$manager_id,$rating_value);
                }
            } else {
                $data = array();
                $data['comment_ID'] = $my_review->comment_ID;
                $data['comment_post_ID'] = $manager_id;
                $data['comment_content'] = $message;
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;

                wp_update_comment($data);
                update_comment_meta($my_review->comment_ID, 'manager_rating', $rating_value, $my_review->rate);
                if ($comment_approved == 1) {
	                do_action('amotos_manager_rating_meta',$manager_id,$rating_value,false,$my_review->rate);
                }
            }
            wp_send_json_success();
        }

        /**
         * @param $manager_id
         * @param $rating_value
         * @param bool|true $comment_exist
         * @param int $old_rating_value
         */
        public function rating_meta_filter($manager_id, $rating_value, $comment_exist = true, $old_rating_value = 0)
        {
            $manager_rating = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_rating', true);
            if ($comment_exist == true) {
	            if (is_array($manager_rating) && isset($manager_rating[$rating_value])) {
		            $manager_rating[$rating_value]++;
	            } else {
		            $manager_rating = array();
		            $manager_rating[1] = 0;
		            $manager_rating[2] = 0;
		            $manager_rating[3] = 0;
		            $manager_rating[4] = 0;
		            $manager_rating[5] = 0;
		            $manager_rating[$rating_value]++;
	            }
            } else {
                $manager_rating[$old_rating_value]--;
                $manager_rating[$rating_value]++;
            }
            update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_rating', $manager_rating);
        }

        /**
         * delete review
         * @param $comment_id
         */
        public function delete_review($comment_id)
        {
            global $wpdb;
            $rating_value = get_comment_meta($comment_id, 'manager_rating', true);
            if ($rating_value !== '') {
                $comments = $wpdb->get_row($wpdb->prepare("SELECT comment_post_ID as manager_ID FROM $wpdb->comments WHERE comment_ID = %d", $comment_id));
                if ($comments !== null) {
	                $manager_id = $comments->manager_ID;
	                $manager_rating = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_rating', true);
	                if (is_array($manager_rating) && isset($manager_rating[$rating_value])) {
		                $manager_rating[$rating_value]--;
		                update_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_rating', $manager_rating);
	                }
                }

            }
        }

        /**
         * approve review
         * @param $new_status
         * @param $old_status
         * @param $comment
         */
        public function approve_review($new_status, $old_status, $comment)
        {
            if ($old_status != $new_status) {
                $rating_value = get_comment_meta($comment->comment_ID, 'manager_rating', true);
                $manager_rating = get_post_meta($comment->comment_post_ID, AMOTOS_METABOX_PREFIX . 'manager_rating', true);
	            if (!is_array($manager_rating)) {
		            $manager_rating = Array();
		            $manager_rating[1] = 0;
		            $manager_rating[2] = 0;
		            $manager_rating[3] = 0;
		            $manager_rating[4] = 0;
		            $manager_rating[5] = 0;
	            }
                if (($rating_value !== '') && is_array($manager_rating) && isset($manager_rating[$rating_value])) {
	                if ($new_status == 'approved') {
		                $manager_rating[$rating_value]++;

	                } else {
		                $manager_rating[$rating_value]--;
	                }
	                if ($manager_rating[$rating_value] < 0) {
		                $manager_rating[$rating_value] = 0;
	                }
	                update_post_meta($comment->comment_post_ID, AMOTOS_METABOX_PREFIX . 'manager_rating', $manager_rating);
                }
            }
        }
    }
}