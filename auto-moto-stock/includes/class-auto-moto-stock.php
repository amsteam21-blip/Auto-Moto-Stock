<?php

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('Auto_Moto_Stock')) {
    /**
     * The core plugin class
     * Class Auto_Moto_Stock
     */
    class Auto_Moto_Stock
    {
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         */
        protected $loader;
        protected $forms;
        /**
         * Instance variable for singleton pattern
         */
        private static $instance = null;
        /**
         * Return class instance
         * @return Auto_Moto_Stock|null
         */
        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        /**
         * Define the core functionality of the plugin
         */
        private function __construct()
        {
            $this->include_library();
            $this->set_locale();
            $this->admin_hooks();
            $this->public_hooks();
        }
        /**
         * Load the required dependencies for this plugin
         */
        private function include_library()
        {
            include_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-autoloader.php';
            if (!is_admin()) {
                // wp_handle_upload
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                // wp_generate_attachment_metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // image_add_caption
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                // submit_button
                require_once(ABSPATH . 'wp-admin/includes/template.php');
            }
            // add_screen_option
            require_once(ABSPATH . 'wp-admin/includes/screen.php');
            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-loader.php';
            $this->loader = new AMOTOS_Loader();
            require_once AMOTOS_PLUGIN_DIR . 'includes/amotos-core-functions.php';
	        require_once AMOTOS_PLUGIN_DIR . 'includes/amotos-formatting-functions.php';
            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-i18n.php';
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-updater.php';
            require_once AMOTOS_PLUGIN_DIR . 'public/class-amotos-public.php';

            /**
             * The class include all Shortcodes
             */
            require_once AMOTOS_PLUGIN_DIR . 'includes/vc-params/amotos-vc-params.php';
            include_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-shortcodes.php';
            /**
             * The class defining Widget
             */
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-widgets.php';
            //class-amotos-shortcode-my-cars
            require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/class-amotos-vcmap.php';
            if(amotos_get_option('enable_add_shortcode_tool', '1')=='1')
            {
                require_once AMOTOS_PLUGIN_DIR . 'includes/insert-shortcode/class-amotos-insert-shortcode.php';
            }
            /**
             * The class responsible for defining all actions that occur in the public-facing side of the site. 
             */
            require_once AMOTOS_PLUGIN_DIR . 'includes/forms/class-amotos-forms.php';
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-schedule.php';
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-captcha.php';
            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-background-emailer.php';
            global $amotos_background_emailer;
            $amotos_background_emailer= new AMOTOS_Background_Emailer();
            $this->forms = new AMOTOS_Forms();

            require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-query.php';
	        require_once AMOTOS_PLUGIN_DIR . 'includes/functions/functions.php';
            require_once AMOTOS_PLUGIN_DIR . 'includes/map/map.php';

			do_action('amotos_include_library');
        }

        /**
         * Define the locale for this plugin for internationalization.
         */
        private function set_locale()
        {
            $plugin_i18n = new AMOTOS_i18n();
            $plugin_i18n->set_domain( AMOTOS_PLUGIN_NAME );
            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }

        /**
         * Register all of the hooks related to the admin area functionality
         */
        private function admin_hooks()
        {
            add_action( 'init', array( 'AMOTOS_Shortcodes', 'init' ) );
            $plugin_updater = new AMOTOS_Updater();
            $this->loader->add_action('admin_init', $plugin_updater, 'updater');

            $plugin_texts= new AMOTOS_Admin_Texts();
            $this->loader->add_action('current_screen', $plugin_texts, 'add_hooks');

            $plugin_admin = new AMOTOS_Admin();

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('init', $plugin_admin, 'register_post_status');
            // Countries
            $admin_location = new AMOTOS_Admin_Location();
            $this->loader->add_action('admin_menu', $admin_location, 'countries_create_menu');
            $this->loader->add_action('admin_init', $admin_location, 'countries_register_setting');
            // Provice/State
            $this->loader->add_action('car-city_add_form_fields',  $admin_location, 'add_form_fields_car_city', 10, 2 );
            $this->loader->add_action('created_car-city',  $admin_location, 'save_car_city_meta', 10, 2 );
            $this->loader->add_action('car-city_edit_form_fields',  $admin_location, 'edit_form_fields_car_city', 10, 2 );
            $this->loader->add_action('edited_car-city',  $admin_location, 'update_car_city_meta', 10, 2 );
            $this->loader->add_filter('manage_edit-car-city_columns', $admin_location,  'add_columns_car_city');
            $this->loader->add_filter('manage_car-city_custom_column', $admin_location,  'add_columns_car_city_content', 10, 3 );
            $this->loader->add_filter('manage_edit-car-city_sortable_columns',  $admin_location, 'add_columns_car_city_sortable' );
            // City
            $this->loader->add_action('car-neighborhood_add_form_fields',  $admin_location, 'add_form_fields_car_neighborhood', 10, 2 );
            $this->loader->add_action('created_car-neighborhood',  $admin_location, 'save_car_neighborhood_meta', 10, 2 );
            $this->loader->add_action('car-neighborhood_edit_form_fields',  $admin_location, 'edit_form_fields_car_neighborhood', 10, 2 );
            $this->loader->add_action('edited_car-neighborhood',  $admin_location, 'update_car_neighborhood_meta', 10, 2 );
            $this->loader->add_filter('manage_edit-car-neighborhood_columns', $admin_location,  'add_columns_car_neighborhood');
            $this->loader->add_filter('manage_car-neighborhood_custom_column', $admin_location,  'add_columns_car_neighborhood_content', 10, 3 );
            $this->loader->add_filter('manage_edit-car-neighborhood_sortable_columns',  $admin_location, 'add_columns_car_neighborhood_sortable' );

            $this->loader->add_action('created_car-status',$plugin_admin,'add_meta_car_status_order_number',10,2);

            // Widgets
            $widgets = new AMOTOS_Widgets();
            $this->loader->add_action('widgets_init', $widgets, 'register_widgets');

            $this->loader->add_filter('squick_register_post_type', $plugin_admin, 'register_post_type');
            $this->loader->add_filter('squick_meta_box_config', $plugin_admin, 'register_meta_boxes');
            $this->loader->add_filter('squick_register_taxonomy', $plugin_admin, 'register_taxonomy');
            $this->loader->add_action('admin_head-edit-tags.php', $plugin_admin, 'remove_taxonomy_parent_category');
            $this->loader->add_action('admin_head-term.php', $plugin_admin, 'remove_taxonomy_parent_category');
            $this->loader->add_filter('squick_term_meta_config', $plugin_admin, 'register_term_meta');
            $this->loader->add_filter('squick_option_config', $plugin_admin, 'register_options_config');
            $this->loader->add_filter('squick_image_default_dir', $plugin_admin, 'image_default_dir');

            // Vehicle Post Type
            $admin_car = new AMOTOS_Admin_Car();
            $this->loader->add_action('restrict_manage_posts', $admin_car, 'filter_restrict_manage_car');
            $this->loader->add_filter('parse_query', $admin_car, 'car_filter');
            $this->loader->add_filter('pre_get_posts', $admin_car, 'post_types_admin_order');
            $this->loader->add_action('admin_init', $admin_car, 'approve_car');
            $this->loader->add_action('admin_init', $admin_car, 'expire_car');
            $this->loader->add_action('admin_init', $admin_car, 'hidden_car');
            $this->loader->add_action('admin_init', $admin_car, 'show_car');
	        $this->loader->add_action('wp_ajax_amotos_admin_featured_car', $admin_car, 'featured');

            // Filters to modify URL slugs
            $this->loader->add_filter('amotos_car_slug', $admin_car, 'modify_car_slug');
            $this->loader->add_filter('amotos_car_type_slug', $admin_car, 'modify_car_type_slug');
            $this->loader->add_filter('amotos_car_status_slug', $admin_car, 'modify_car_status_slug');
            $this->loader->add_filter('amotos_car_styling_slug', $admin_car, 'modify_car_styling_slug');
            $this->loader->add_filter('amotos_car_city_slug', $admin_car, 'modify_car_city_slug');
            $this->loader->add_filter('amotos_car_neighborhood_slug', $admin_car, 'modify_car_neighborhood_slug');
            $this->loader->add_filter('amotos_car_state_slug', $admin_car, 'modify_car_state_slug');
            $this->loader->add_filter('amotos_car_label_slug', $admin_car, 'modify_car_label_slug');

            // Manager Post Type
            $admin_manager = new AMOTOS_Admin_Manager();
            $this->loader->add_filter('amotos_manager_slug', $admin_manager, 'modify_manager_slug');
            $this->loader->add_filter('amotos_dealer_slug', $admin_manager, 'modify_dealer_slug');
            $this->loader->add_filter('init', $admin_manager, 'modify_author_slug');

            $this->loader->add_action('restrict_manage_posts', $admin_manager, 'filter_restrict_manage_manager');
            $this->loader->add_filter('parse_query', $admin_manager, 'manager_filter');
            $this->loader->add_filter('pre_get_posts', $admin_manager, 'post_types_admin_order');

            $this->loader->add_action('save_post', $admin_manager, 'save_manager_meta', 20, 2);
            $this->loader->add_action('admin_init', $admin_manager, 'approve_manager');

            // Package Post Type
            $admin_package = new AMOTOS_Admin_Package();
            $this->loader->add_filter('amotos_package_slug', $admin_package, 'modify_package_slug');

            // Manager Packages Post Type
            $admin_user_package = new AMOTOS_Admin_User_Package();
            $this->loader->add_filter('amotos_user_package_slug', $admin_user_package, 'modify_user_package_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_user_package, 'filter_restrict_manage_user_package');
            $this->loader->add_filter('parse_query', $admin_user_package, 'user_package_filter');
	        $this->loader->add_action('before_delete_post',$admin_user_package,'delete_user_package');

            // Invoice Post Type
            $admin_invoice = new AMOTOS_Admin_Invoice();
            $this->loader->add_filter('amotos_invoice_slug', $admin_invoice, 'modify_invoice_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_invoice, 'filter_restrict_manage_invoice');
            $this->loader->add_filter('parse_query', $admin_invoice, 'invoice_filter');

            // Transaction Post Type
            $admin_trans_action = new AMOTOS_Admin_Trans_Action();
            $this->loader->add_filter('amotos_trans_action_slug', $admin_trans_action, 'modify_trans_action_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_trans_action, 'filter_restrict_manage_trans_action');
            $this->loader->add_filter('parse_query', $admin_trans_action, 'trans_action_filter');

            if (is_admin()) {
                global $pagenow;
                $setup_page = new AMOTOS_Admin_Setup();
                $this->loader->add_action('admin_menu', $setup_page, 'admin_menu', 12);
                $this->loader->add_action('admin_init', $setup_page, 'redirect');
                $post_type = isset($_GET['post_type']) ? sanitize_text_field(wp_unslash($_GET['post_type'])) : '';

                // vehicle custom columns
                add_filter( 'manage_car_posts_columns', array( $admin_car, 'register_custom_column_titles' ) );
                add_action( 'manage_car_posts_custom_column', array($admin_car,'display_custom_column'), 2 );
                add_filter('manage_edit-car_sortable_columns', array($admin_car, 'sortable_columns'));
                add_filter( 'post_row_actions', array( $admin_car, 'modify_list_row_actions' ), 100, 2 );
                if (($pagenow == 'edit.php') && ($post_type == 'car')) {
                    $this->loader->add_filter('request', $admin_car, 'column_orderby');
                }

                // manager custom columns
                add_filter( 'manage_manager_posts_columns', array( $admin_manager, 'register_custom_column_titles' ) );
                add_action( 'manage_manager_posts_custom_column', array($admin_manager,'display_custom_column'), 2 );
                add_filter( 'post_row_actions', array( $admin_manager, 'modify_list_row_actions' ), 100, 2 );

                // package custom columns
                add_filter( 'manage_package_posts_columns', array( $admin_package, 'register_custom_column_titles' ) );
                add_action( 'manage_package_posts_custom_column', array($admin_package,'display_custom_column'), 2 );
                add_filter( 'post_row_actions', array( $admin_package, 'modify_list_row_actions' ), 100, 2 );

                // manager package custom columns
                add_filter( 'manage_user_package_posts_columns', array( $admin_user_package, 'register_custom_column_titles' ) );
                add_action( 'manage_user_package_posts_custom_column', array($admin_user_package,'display_custom_column'), 2 );
                add_filter( 'post_row_actions', array( $admin_user_package, 'modify_list_row_actions' ), 100, 2 );

                // Invoice custom columns
                add_filter( 'manage_invoice_posts_columns', array( $admin_invoice, 'register_custom_column_titles' ) );
                add_action( 'manage_invoice_posts_custom_column', array($admin_invoice,'display_custom_column'), 2 );
                add_filter( 'post_row_actions', array( $admin_invoice, 'modify_list_row_actions' ), 100, 2 );
                add_filter( 'manage_edit-invoice_sortable_columns', array( $admin_invoice, 'sortable_columns' ));
                if (($pagenow == 'edit.php' ) && ($post_type == 'invoice')) {
                    add_filter( 'request', array( $admin_invoice, 'column_orderby' ));
                }
                // Transaction custom columns
                add_filter( 'manage_trans_action_posts_columns', array( $admin_trans_action, 'register_custom_column_titles' ) );
                add_action( 'manage_trans_action_posts_custom_column', array($admin_trans_action,'display_custom_column'), 2 );
                add_filter( 'post_row_actions', array( $admin_trans_action, 'modify_list_row_actions' ), 100, 2 );
                add_filter( 'manage_edit-trans_action_sortable_columns', array( $admin_trans_action, 'sortable_columns' ));
                if (($pagenow == 'edit.php')  && ($post_type == 'trans_action')) {
                    add_filter( 'request', array( $admin_trans_action, 'column_orderby' ));
                }
                $setup_metaboxes = new AMOTOS_Admin_Setup_Metaboxes();
                $this->loader->add_action('load-post.php', $setup_metaboxes, 'meta_boxes_setup');
                $this->loader->add_action('load-post-new.php', $setup_metaboxes, 'meta_boxes_setup');
            } 


            $vc_map = new AMOTOS_Vc_map();
            $this->loader->add_action('vc_before_init', $vc_map, 'register_vc_map');

            // AMOTOS DESCRIPTION
	        add_filter( 'amotos_description', 'wptexturize' );
	        add_filter( 'amotos_description', 'convert_smilies' );
	        add_filter( 'amotos_description', 'convert_chars' );
	        add_filter( 'amotos_description', 'wpautop' );
	        add_filter( 'amotos_description', 'shortcode_unautop' );
	        add_filter( 'amotos_description', 'prepend_attachment' );
	        add_filter( 'amotos_description', 'do_shortcode', 11 ); // After wpautop().

	        add_filter('squick_save_metabox_meta_field_keys', array($plugin_admin, 'squick_save_metabox_meta_field_keys'), 10, 3);
        }
        /**
         * Register all of the hooks related to the public-facing functionality
         */
        private function public_hooks()
        {
            $this->loader->add_action('init', $this, 'do_output_buffer');
            $plugin_public = AMOTOS_Public::getInstance();

            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
	        $this->loader->add_action('wp_footer', $plugin_public, 'print_tmpl_template');
            $this->loader->add_action('init', $plugin_public, 'register_assets');
	        $this->loader->add_action('init', $plugin_public, 'register_assets_google_map',8);
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
            $this->loader->add_filter('template_include', $plugin_public, 'template_loader');
            $this->loader->add_action('pre_get_posts', $plugin_public, 'set_posts_per_page');

            $profile = AMOTOS_Profile::getInstance();
            $this->loader->add_filter('show_user_profile', $profile, 'custom_user_profile_fields');
            $this->loader->add_filter('edit_user_profile', $profile, 'custom_user_profile_fields');
            $this->loader->add_action('profile_update', $profile, 'profile_update');
            $this->loader->add_action('edit_user_profile_update', $profile, 'update_custom_user_profile_fields');
            $this->loader->add_action('personal_options_update', $profile, 'update_custom_user_profile_fields');

            $this->loader->add_action('wp_ajax_amotos_profile_image_upload_ajax', $profile, 'profile_image_upload_ajax');

            $this->loader->add_action('wp_ajax_amotos_update_profile_ajax', $profile, 'update_profile_ajax');

            $this->loader->add_action('wp_ajax_amotos_change_password_ajax', $profile, 'change_password_ajax');

            $this->loader->add_action('wp_ajax_amotos_register_user_as_manager_ajax', $profile, 'register_user_as_manager_ajax');

            $this->loader->add_action('wp_ajax_amotos_leave_manager_ajax', $profile, 'leave_manager_ajax');

            $login_register = AMOTOS_Login_Register::getInstance();
            $this->loader->add_action('init', $login_register, 'hide_admin_bar', 9);
            $this->loader->add_action('admin_init', $login_register, 'restrict_admin_access');
            $this->loader->add_action('wp_footer', $login_register, 'login_register_modal');
            $this->loader->add_action('wp_ajax_amotos_login_ajax', $login_register, 'login_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_login_ajax', $login_register, 'login_ajax');

            $this->loader->add_action('wp_ajax_amotos_register_ajax', $login_register, 'register_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_register_ajax', $login_register, 'register_ajax');

            $this->loader->add_action('wp_ajax_amotos_reset_password_ajax', $login_register, 'reset_password_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_reset_password_ajax', $login_register, 'reset_password_ajax');
	        $this->loader->add_action('template_redirect', $login_register, 'redirect_is_login');

            // Shortcodes
            $shortcodes= AMOTOS_Shortcodes::getInstance();
            $this->loader->add_action('wp', $shortcodes, 'shortcode_car_action_handler');
            $this->loader->add_action('amotos_my_cars_content_edit', $shortcodes, 'edit_car');
            $this->loader->add_action('wp_ajax_amotos_car_gallery_fillter_ajax', $shortcodes, 'car_gallery_fillter_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_gallery_fillter_ajax', $shortcodes, 'car_gallery_fillter_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_featured_fillter_city_ajax', $shortcodes, 'car_featured_fillter_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_featured_fillter_city_ajax', $shortcodes, 'car_featured_fillter_city_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_paging_ajax', $shortcodes, 'car_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_paging_ajax', $shortcodes, 'car_paging_ajax');

            $this->loader->add_action('wp_ajax_amotos_manager_paging_ajax', $shortcodes, 'manager_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_manager_paging_ajax', $shortcodes, 'manager_paging_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_set_session_view_as_ajax', $shortcodes, 'car_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_set_session_view_as_ajax', $shortcodes, 'car_set_session_view_as_ajax');

            $this->loader->add_action('wp_ajax_amotos_manager_set_session_view_as_ajax', $shortcodes, 'manager_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_manager_set_session_view_as_ajax', $shortcodes, 'manager_set_session_view_as_ajax');

            $car = AMOTOS_Car::getInstance();
            $this->loader->add_action('wp_ajax_amotos_car_img_upload_ajax', $car, 'car_img_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_img_upload_ajax', $car, 'car_img_upload_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_attachment_upload_ajax', $car, 'car_attachment_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_attachment_upload_ajax', $car, 'car_attachment_upload_ajax');

            $this->loader->add_action('wp_ajax_amotos_remove_car_attachment_ajax', $car, 'remove_car_attachment_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_remove_car_attachment_ajax', $car, 'remove_car_attachment_ajax');
            $this->loader->add_filter('amotos_submit_car', $car, 'submit_car');
            $this->loader->add_action('wp_ajax_amotos_contact_manager_ajax', $car, 'contact_manager_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_contact_manager_ajax', $car, 'contact_manager_ajax');
            $this->loader->add_action('wp_ajax_car_print_ajax', $car, 'car_print_ajax');
            $this->loader->add_action('wp_ajax_nopriv_car_print_ajax', $car, 'car_print_ajax');
            $this->loader->add_action('before_delete_post', $car, 'delete_car_attachments');
            $this->loader->add_action('template_redirect', $car, 'set_views_counter',9999);

            $this->loader->add_action('wp_ajax_amotos_get_states_by_country_ajax', $car, 'get_states_by_country_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_get_states_by_country_ajax', $car, 'get_states_by_country_ajax');

            $this->loader->add_action('wp_ajax_amotos_get_cities_by_state_ajax', $car, 'get_cities_by_state_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_get_cities_by_state_ajax', $car, 'get_cities_by_state_ajax');

            $this->loader->add_action('wp_ajax_amotos_get_neighborhoods_by_city_ajax', $car, 'get_neighborhoods_by_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_get_neighborhoods_by_city_ajax', $car, 'get_neighborhoods_by_city_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_submit_review_ajax', $car, 'submit_review_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_submit_review_ajax', $car, 'submit_review_ajax');

            $this->loader->add_action( 'amotos_car_rating_meta',$car, 'rating_meta_filter', 4, 9 );
            $this->loader->add_action('deleted_comment', $car, 'delete_review',10,1);
            $this->loader->add_action('transition_comment_status', $car, 'approve_review', 10, 3);
            //favorites
            $this->loader->add_action('wp_ajax_amotos_favorite_ajax', $car, 'favorite_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_favorite_ajax', $car, 'favorite_ajax');

            //view gallery
            $this->loader->add_action('wp_ajax_amotos_view_gallery_ajax', $car, 'view_gallery_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_view_gallery_ajax', $car, 'view_gallery_ajax');

            $invoice= AMOTOS_Invoice::getInstance();
            $this->loader->add_action('wp_ajax_amotos_invoice_print_ajax', $invoice, 'invoice_print_ajax');

            //compare
            $compare = AMOTOS_Compare::getInstance();
            $this->loader->add_action('wp_logout', $compare, 'close_session');
            $this->loader->add_action('amotos_show_compare', $compare, 'show_compare_listings', 5);

            $this->loader->add_action('wp_ajax_amotos_compare_add_remove_car_ajax', $compare, 'compare_add_remove_car_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_compare_add_remove_car_ajax', $compare, 'compare_add_remove_car_ajax');

            $this->loader->add_action('wp_footer', $compare, 'template_compare_listing');

            $this->loader->add_action('init', $this->forms, 'load_posted_form');

            $payment = AMOTOS_Payment::getInstance();
            $this->loader->add_action('wp_ajax_amotos_paypal_payment_per_listing_ajax', $payment, 'paypal_payment_per_listing_ajax');
            $this->loader->add_action('wp_ajax_amotos_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');

            $this->loader->add_action('wp_ajax_amotos_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');

            $this->loader->add_action('wp_ajax_amotos_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');

            $this->loader->add_action('wp_ajax_amotos_free_package_ajax', $payment, 'free_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_free_package_ajax', $payment, 'free_package_ajax');

	        $search = AMOTOS_Search::getInstance();
            $this->loader->add_action('wp_ajax_amotos_car_search_ajax', $search, 'amotos_car_search_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_search_ajax', $search, 'amotos_car_search_ajax');

            $this->loader->add_action('wp_ajax_amotos_car_search_map_ajax', $search, 'amotos_car_search_map_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_car_search_map_ajax', $search, 'amotos_car_search_map_ajax');

            // Price
            $this->loader->add_action('wp_ajax_amotos_ajax_change_price_on_status_change', $search, 'amotos_ajax_change_price_on_status_change');
            $this->loader->add_action('wp_ajax_nopriv_amotos_ajax_change_price_on_status_change', $search, 'amotos_ajax_change_price_on_status_change');

            $save_search= AMOTOS_Save_Search::getInstance();
            $this->loader->add_action('wp_ajax_amotos_save_search_ajax', $save_search, 'save_search_ajax');

            $schedule = AMOTOS_Schedule::getInstance();
            $this->loader->add_action('init', $schedule, 'scheduled_hook');
            $this->loader->add_action('amotos_per_listing_check_expire', $schedule, 'per_listing_check_expire');
            $this->loader->add_action('amotos_saved_search_check_result', $schedule, 'saved_search_check_result');

            $captcha= AMOTOS_Captcha::getInstance();
            $this->loader->add_action('wp_footer', $captcha, 'render_recaptcha');
            $this->loader->add_action('amotos_verify_recaptcha', $captcha, 'verify_recaptcha');
            $this->loader->add_action('amotos_generate_form_recaptcha', $captcha, 'form_recaptcha');

	        if (amotos_enable_captcha('login')) {
		        $this->loader->add_action( 'login_form', $captcha, 'form_recaptcha' );
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_action('authenticate', $captcha, 'verify_recaptcha_wp_login', 999, 3 );
	        }

	        if (amotos_enable_captcha('reset_password')) {
		        $this->loader->add_action('lostpassword_form', $captcha, 'form_recaptcha');
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_action( 'lostpassword_post', $captcha, 'verify_recaptcha_wp_lostpassword' );
	        }

	        if (amotos_enable_captcha('register')) {
		        $this->loader->add_action( 'register_form', $captcha, 'form_recaptcha' );
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_filter( 'registration_errors', $captcha, 'verify_recaptcha_wp_registration',10,3 );
	        }

            $manager= AMOTOS_Manager::getInstance();
            $this->loader->add_action('wp_ajax_amotos_manager_submit_review_ajax', $manager, 'submit_review_ajax');
            $this->loader->add_action('wp_ajax_nopriv_amotos_manager_submit_review_ajax', $manager, 'submit_review_ajax');

	        $this->loader->add_action('amotos_manager_rating_meta',$manager,'rating_meta_filter',4,9);
            $this->loader->add_action('deleted_comment', $manager, 'delete_review',10,1);
            $this->loader->add_action('transition_comment_status', $manager, 'approve_review', 10, 3);
            add_action('admin_init',array($this,'set_current_lang_ajax'));
            add_filter('body_class',array($this,'body_class'));
        }

        public function body_class($classes) {
            $is_rtl  = amotos_is_rtl();
            if ($is_rtl) {
                $classes[] = 'rtl';
            }
            return $classes;
        }

        public function set_current_lang_ajax() {
	        if ( isset( $_GET[ 'amotos_wpml_lang' ] ) ) {
		        do_action( 'wpml_switch_language', sanitize_text_field(wp_unslash($_GET[ 'amotos_wpml_lang' ] )) ); // switch the content language
	        }
        }

        /**
         * Run the loader to execute all of the hooks with WordPress
         */
        public function run()
        {
	        add_filter( 'squick_loader_framework', array( $this, 'loader_framework' ) );
	        include_once AMOTOS_PLUGIN_DIR . 'quick-framework/init.php';
	        add_action('squick_after_setup_framework',array($this,'after_setup_framework'));

        }

	    public function loader_framework($frameworks) {
		    $frameworks[] = array(
			    'version' => '1.0.0',
			    'path' => AMOTOS_PLUGIN_DIR . 'quick-framework/',
			    'uri' => AMOTOS_PLUGIN_URL . 'quick-framework/',
			    'plugin_file' => AMOTOS_PLUGIN_FILE,
		    );
		    return $frameworks;
	    }

        public function after_setup_framework() {
	        $this->loader->run();
        }

        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         */
        public function get_loader()
        {
            return $this->loader;
        }

        /**
         * do_output_buffer
         */
        function do_output_buffer()
        {
            ob_start();
        }

        /**
         * Get forms
         * @return mixed
         */
        public function get_forms()
        {
            return $this->forms;
        }

        /**
         * Get template path
         * @return mixed
         */
        public function template_path()
        {
            return apply_filters('amotos_template_path', 'amotos-templates/');
        }
    }
}
if(!function_exists('AMOTOS'))
{
    function AMOTOS() {
        return Auto_Moto_Stock::get_instance();
    }
}
// Global for backwards compatibility.
$GLOBALS['Auto_Moto_Stock'] = AMOTOS();
