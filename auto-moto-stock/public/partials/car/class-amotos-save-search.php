<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Save_Search')) {
    /**
     * Class AMOTOS_Search
     */
    class AMOTOS_Save_Search
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

        public static function create_table_save_search()
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name         = $wpdb->prefix . 'amotos_save_search';
            $sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
              title longtext DEFAULT '' NOT NULL,
              params longtext DEFAULT '' NOT NULL,
			  user_id mediumint(9) NOT NULL,
			  email longtext DEFAULT '' NOT NULL,
			  url longtext DEFAULT '' NOT NULL,
			  query longtext NOT NULL,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  PRIMARY KEY  (id)
			) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        function save_search_ajax() {

            $nonce = isset($_REQUEST['amotos_save_search_ajax']) ? amotos_clean(wp_unslash($_REQUEST['amotos_save_search_ajax'])) : '';
            if( !wp_verify_nonce( $nonce, 'amotos_save_search_nonce_field' ) ) {
                echo wp_json_encode(array(
                    'success' => false,
                    'message' => esc_html__("Permission error!", 'auto-moto-stock'),
                ));
                wp_die();
            }
            global $wpdb, $current_user;
            wp_get_current_user();
            $query  =  isset($_REQUEST['amotos_query']) ? amotos_clean(wp_unslash($_REQUEST['amotos_query'])) : '';
            $table_name         = $wpdb->prefix . 'amotos_save_search';
            $url  = isset($_REQUEST['amotos_url']) ? sanitize_url(wp_unslash($_REQUEST['amotos_url'])) : '';
            $title  = isset($_REQUEST['amotos_title']) ?  amotos_clean(wp_unslash($_REQUEST['amotos_title'])) : '';
            $params  = isset($_REQUEST['amotos_params']) ?  amotos_clean(wp_unslash($_REQUEST['amotos_params'])) : '';
            $wpdb->insert(
                $table_name,
                array(
                    'title'     => $title,
                    'params'    => $params,
                    'user_id'   => $current_user->ID,
                    'email'     => $current_user->user_email,
                    'url'       => $url,
                    'query'     => $query,
                    'time'      => current_time( 'mysql' ),
                ),
                array(
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            echo wp_json_encode( array( 'success' => true, 'msg' => esc_html__('Save successfully', 'auto-moto-stock') ) );
            wp_die();
        }
        public function get_total_save_search(){
            $user_id = get_current_user_id();
            global $wpdb;
            $results       = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}amotos_save_search WHERE user_id = %d", $user_id), OBJECT );
            return count($results);
        }
    }
}