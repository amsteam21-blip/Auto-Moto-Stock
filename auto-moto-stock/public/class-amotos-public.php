<?php

/**
 * The public-facing functionality of the plugin.
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Public')) {
    /**
     * The public-facing functionality of the plugin
     * Class AMOTOS_Public
     */
    class AMOTOS_Public
    {

	    /*
		 * Loader instances
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
         * Initialize the class and set its properties.
         */
        public function __construct()
        {
            require_once AMOTOS_PLUGIN_DIR . 'public/class-amotos-template-hooks.php';
        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         */
        public function enqueue_styles()
        {
            $min_suffix = amotos_get_option('enable_min_css', 0) == 1 ? '.min' : '';
            wp_enqueue_style('jquery-ui', AMOTOS_PLUGIN_URL . 'public/assets/packages/jquery-ui/jquery-ui.min.css', array(), '1.11.4', 'all');
            wp_enqueue_style('owl.carousel', AMOTOS_PLUGIN_URL . 'public/assets/packages/owl-carousel/assets/owl.carousel.min.css', array(), '2.3.4', 'all');
	        wp_enqueue_style('light-gallery', AMOTOS_PLUGIN_URL . 'public/assets/packages/light-gallery/css/lightgallery.min.css', array(), '1.2.18', 'all');
	        wp_register_style('select2_css', AMOTOS_PLUGIN_URL . 'public/assets/packages/select2/css/select2.min.css', array(), '4.0.6-rc.1', 'all');
            wp_register_style('star-rating', AMOTOS_PLUGIN_URL . 'public/assets/packages/star-rating/css/star-rating' . $min_suffix . '.css', array(), '4.1.3', 'all');
	        wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'main', AMOTOS_PLUGIN_URL . 'public/assets/scss/main/main' . $min_suffix . '.css', array('star-rating','select2_css'), AMOTOS_PLUGIN_VER, 'all');
            $is_rtl =  amotos_is_rtl();
            if ($is_rtl) {
                wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'main-rtl', AMOTOS_PLUGIN_URL . 'public/assets/scss/main-rtl/main-rtl' . $min_suffix . '.css', array(), AMOTOS_PLUGIN_VER, 'all');
            }

            // shortcode
            if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'car_print_ajax') {
                wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'car-print');
                $isRTL = sanitize_text_field(wp_unslash($_REQUEST['isRTL'] ?? false));
                if (filter_var($isRTL,FILTER_VALIDATE_BOOLEAN) ) {
                    wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'car-print-rtl');
                }
            }
        }

        /**
         * Register the sqripts for the public-facing side of the site.
         */
        public function enqueue_scripts()
        {
            $min_suffix = amotos_get_option('enable_min_js', 0) == 1 ? '.min' : '';
            wp_enqueue_style('bootstrap');
	        wp_enqueue_script('light-gallery', AMOTOS_PLUGIN_URL . 'public/assets/packages/light-gallery/js/lightgallery-all.min.js', array('jquery'), '1.2.18', true);
            wp_enqueue_script( 'moment' );
            /*wp_register_script('moment', AMOTOS_PLUGIN_URL . 'public/assets/packages/bootstrap/js/moment.min.js', array('jquery'), '2.11.1', true);*/
            wp_register_script('bootstrap-datetimepicker', AMOTOS_PLUGIN_URL . 'public/assets/packages/bootstrap/js/bootstrap-datetimepicker.min.js', array('jquery', 'moment'), '4.17.42', true);
	        wp_register_script('bootstrap-tabcollapse', AMOTOS_PLUGIN_URL . 'public/assets/packages/bootstrap-tabcollapse/bootstrap-tabcollapse.min.js', array('jquery'), '1.0', true);
            wp_enqueue_script('jquery-validate', AMOTOS_PLUGIN_URL . 'public/assets/js/jquery.validate.min.js', array('jquery'), '1.17.0', true);
            wp_register_script('jquery-geocomplete', AMOTOS_PLUGIN_URL . 'public/assets/js/jquery.geocomplete.min.js', array('jquery'), '1.7.0', true);
            wp_enqueue_script('imagesloaded', AMOTOS_PLUGIN_URL . 'public/assets/js/imagesloaded.pkgd.min.js', array('jquery'), '4.1.3', true);
            wp_register_script('touch-punch', AMOTOS_PLUGIN_URL . 'public/assets/packages/touch-punch/touch-punch.min.js', array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse' ), '0.2.3', true );

            $enable_filter_location = amotos_get_option('enable_filter_location', 0);

	        wp_register_script('select2_js', AMOTOS_PLUGIN_URL . 'public/assets/packages/select2/js/select2.full.min.js', array('jquery'), '4.0.6-rc.1', true);

            wp_enqueue_script('infobox', AMOTOS_PLUGIN_URL . 'public/assets/js/infobox.min.js', array('jquery', 'google-map'), '1.1.13', true);
            wp_enqueue_script('jquery-core');
            wp_enqueue_script('owl.carousel', AMOTOS_PLUGIN_URL . 'public/assets/packages/owl-carousel/owl.carousel.min.js', array('jquery'), '2.3.4', true);
            wp_register_script('star-rating', AMOTOS_PLUGIN_URL . 'public/assets/packages/star-rating/js/star-rating' . $min_suffix . '.js', array('jquery'), '4.1.3', true);

            $dec_point = amotos_get_price_decimal_separator();
            $thousands_sep = amotos_get_option('thousand_separator', ',');
	        $decimals = amotos_get_option('number_of_decimals', 0);
            $currency = amotos_get_option('currency_sign', esc_html__('$', 'auto-moto-stock'));
	        $currency_position = amotos_get_option('currency_position', 'before');
            if (empty($currency)) {
                $currency = esc_html__('$', 'auto-moto-stock');
            }

	        $amotos_main_vars = apply_filters('amotos_main_vars', array(
		        'ajax_url' => AMOTOS_AJAX_URL,
		        'confirm_yes_text' => esc_html__('Yes', 'auto-moto-stock'),
		        'confirm_no_text' => esc_html__('No', 'auto-moto-stock'),
		        'loading_text' => esc_html__('Processing, Please wait...', 'auto-moto-stock'),
		        'sending_text' => esc_html__('Sending email, Please wait...', 'auto-moto-stock'),
		        'decimals' => $decimals,
		        'dec_point' => $dec_point,
		        'thousands_sep' => $thousands_sep,
		        'currency' => $currency,
		        'currency_position' => $currency_position,
                'loan_amount_text' => esc_html__('Loan Amount','auto-moto-stock'),
                'years_text' => esc_html__('Year','auto-moto-stock'),
                'monthly_text' => esc_html__('Monthly','auto-moto-stock'),
                'bi_weekly_text' => esc_html__('Bi Weekly','auto-moto-stock'),
                'weekly_text' => esc_html__('Weekly','auto-moto-stock'),
	        ));

	        wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'main', AMOTOS_PLUGIN_URL . 'public/assets/js/amotos-main' . $min_suffix . '.js', array('jquery', 'wp-util', 'jquery-validate','jquery-ui-core','jquery-ui-slider','jquery-ui-dialog','jquery-ui-sortable','touch-punch','bootstrap','bootstrap-tabcollapse','star-rating'), AMOTOS_PLUGIN_VER, true);
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'main', 'amotos_main_vars', $amotos_main_vars);

            // Login
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'login', AMOTOS_PLUGIN_URL . 'public/assets/js/account/amotos-login' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'login', 'amotos_login_vars',
                array(
                    'ajax_url' => AMOTOS_AJAX_URL,
                    'loading' => esc_html__('Sending user info, please wait...', 'auto-moto-stock'),
                )
            );
            // Register
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'register', AMOTOS_PLUGIN_URL . 'public/assets/js/account/amotos-register' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'register', 'amotos_register_vars',
                array(
                    'ajax_url' => AMOTOS_AJAX_URL,
                    'loading' => esc_html__('Sending user info, please wait...', 'auto-moto-stock'),
                )
            );
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'compare', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-compare' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'compare', 'amotos_compare_vars',
                array(
                    'ajax_url' => AMOTOS_AJAX_URL,
                    'compare_button_url' => amotos_get_permalink('compare'),
                    'alert_title' => esc_html__('Information!', 'auto-moto-stock'),
                    'alert_message' => esc_html__('Only allowed to compare up to 4 vehicles!', 'auto-moto-stock'),
                    'alert_not_found' => esc_html__('Compare Page Not Found!', 'auto-moto-stock')
                )
            );

            // Profile
	        $profile_ajax_upload_url = add_query_arg( 'action', 'amotos_profile_image_upload_ajax', AMOTOS_AJAX_URL );
			$profile_ajax_upload_url = add_query_arg( 'nonce', wp_create_nonce('amotos_allow_upload_nonce'), $profile_ajax_upload_url );

            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'profile', AMOTOS_PLUGIN_URL . 'public/assets/js/account/amotos-profile' . $min_suffix . '.js', array('jquery', 'plupload', 'jquery-validate'), AMOTOS_PLUGIN_VER, true);
            $user_profile_data = array(
                'ajax_url' => AMOTOS_AJAX_URL,
                'ajax_upload_url' => $profile_ajax_upload_url,
                'file_type_title' => esc_html__('Valid file formats', 'auto-moto-stock'),
                'amotos_site_url' => site_url(),
                'confirm_become_manager_msg' => esc_html__('Are you sure you want to become an manager.', 'auto-moto-stock'),
                'confirm_leave_manager_msg' => esc_html__('Are you sure you want to leave manager account and comeback normal account.', 'auto-moto-stock'),
            );
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'profile', 'amotos_profile_vars', $user_profile_data);

            // Vehicle
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'car', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-car' . $min_suffix . '.js', array('jquery', 'plupload', 'jquery-ui-sortable', 'jquery-validate', 'jquery-geocomplete','select2_js'), AMOTOS_PLUGIN_VER, true);

            $googlemap_zoom_level = amotos_get_option('googlemap_zoom_level', '12');
            $google_map_style = amotos_get_option('googlemap_style', '');
            $map_icons_path_marker = AMOTOS_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
            $googlemap_default_country = amotos_get_option('default_country', 'US');
	        $googlemap_coordinate_default = amotos_get_option('googlemap_coordinate_default', '37.773972, -122.431297');
            $default_marker = amotos_get_option('marker_icon', '');
            if ($default_marker != '') {
                if (is_array($default_marker) && $default_marker['url'] != '') {
                    $map_icons_path_marker = $default_marker['url'];
                }
            }

            $car_upload_nonce = wp_create_nonce('car_allow_upload');
	        $car_ajax_upload_url = add_query_arg( 'action', 'amotos_car_img_upload_ajax', AMOTOS_AJAX_URL );
	        $car_ajax_upload_url = add_query_arg( 'nonce', $car_upload_nonce, $car_ajax_upload_url );

	        $car_attachment_ajax_upload_url = add_query_arg( 'action', 'amotos_car_attachment_upload_ajax', AMOTOS_AJAX_URL );
	        $car_attachment_ajax_upload_url = add_query_arg( 'nonce', $car_upload_nonce, $car_attachment_ajax_upload_url );

	        $amotos_car_vars = apply_filters('amotos_car_vars', array(
		        'ajax_url' => AMOTOS_AJAX_URL,
		        'ajax_upload_url' => $car_ajax_upload_url,
		        'ajax_upload_attachment_url' => $car_attachment_ajax_upload_url,
		        'googlemap_zoom_level' => $googlemap_zoom_level,
		        'google_map_style' => $google_map_style,
		        'googlemap_marker_icon' => $map_icons_path_marker,
		        'googlemap_default_country' => $googlemap_default_country,
		        'googlemap_coordinate_default' => $googlemap_coordinate_default,
		        'upload_nonce' => $car_upload_nonce,
		        'max_car_images' => amotos_get_option('max_car_images', '10'),
		        'image_max_file_size' => amotos_get_option('image_max_file_size', '1000kb'),
		        'max_car_attachments' => amotos_get_option('max_car_attachments', '2'),
		        'attachment_max_file_size' => amotos_get_option('attachment_max_file_size', '1000kb'),
		        'attachment_file_type' => amotos_get_option('attachment_file_type', 'pdf,txt,doc,docx'),
		        'amotos_metabox_prefix' => AMOTOS_METABOX_PREFIX,
		        'enable_filter_location'=>$enable_filter_location,
		        'localization' => array(
			        'no_more_than' => esc_html__('no more than','auto-moto-stock'),
			        'files' => esc_html__('file(s)','auto-moto-stock'),
			        'file_type_title' => esc_html__('Valid file formats', 'auto-moto-stock'),
		        ),
	        ));

            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'car', 'amotos_car_vars', $amotos_car_vars);
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'car_steps', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-car-steps' . $min_suffix . '.js', array('jquery', 'jquery-validate', AMOTOS_PLUGIN_PREFIX . 'car'), AMOTOS_PLUGIN_VER, true);
            $car_req_fields = amotos_get_option('required_fields', array('car_title', 'car_type', 'car_price', 'car_map_address'));
            if (!is_array($car_req_fields)) {
                $car_req_fields = array();
            }
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'car_steps', 'amotos_car_steps_vars', array(
                'car_title' => in_array("car_title", $car_req_fields),
                'car_type' => in_array("car_type", $car_req_fields),
                'car_status' => in_array("car_status", $car_req_fields),
                'car_label' => in_array("car_label", $car_req_fields),
                'car_price' => in_array("car_price", $car_req_fields),
                'car_price_prefix' => in_array("car_price_prefix", $car_req_fields),
                'car_price_postfix' => in_array("car_price_postfix", $car_req_fields),
                'car_doors' => in_array("car_doors", $car_req_fields),
                'car_seats' => in_array("car_seats", $car_req_fields),
                'car_owners' => in_array("car_owners", $car_req_fields),
                'car_mileage' => in_array("car_mileage", $car_req_fields),
                'car_power' => in_array("car_power", $car_req_fields),
                'car_volume' => in_array("car_volume", $car_req_fields),
                'car_year' => in_array("car_year", $car_req_fields),
                'car_address' => in_array("car_map_address", $car_req_fields),
                'car_country' => in_array("country", $car_req_fields),
                'state' => in_array("state", $car_req_fields),
                'city' => in_array("city", $car_req_fields),
	            'neighborhood' => in_array("neighborhood", $car_req_fields),
                'postal_code' => in_array("postal_code", $car_req_fields),
            ));

            // Payment
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'payment', AMOTOS_PLUGIN_URL . 'public/assets/js/payment/amotos-payment' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            $payment_data = array(
                'ajax_url' => AMOTOS_AJAX_URL,
                'processing_text' => esc_html__('Processing, Please wait...', 'auto-moto-stock')
            );
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'payment', 'amotos_payment_vars', $payment_data);

            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel', AMOTOS_PLUGIN_URL . 'public/assets/js/amotos-carousel' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');
            $enable_captcha = amotos_get_option('enable_captcha', array());
            if (is_array($enable_captcha) && count($enable_captcha) > 0) {

                $recaptcha_src = esc_url_raw(add_query_arg(array(
                    'render' => 'explicit',
                    'onload' => 'amotos_recaptcha_onload_callback'
                ), 'https://www.google.com/recaptcha/api.js'));

                // enqueue google reCAPTCHA API
                wp_register_script(
                    'amotos-google-recaptcha',
                    $recaptcha_src,
                    array(),
                    AMOTOS_PLUGIN_VER,
                    true
                );
            }

	        wp_register_script('isotope', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-gallery/assets/js/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
	        wp_register_script('imageLoaded', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-gallery/assets/js/imagesloaded.pkgd.min.js', array('jquery'), '4.1.4', true);
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'archive-car', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-archive-car' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);

            // shortcodes
	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'search_map', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-search-map/assets/js/car-search-map' . $min_suffix . '.js', array('jquery','google-map','markerclusterer','select2_js'), AMOTOS_PLUGIN_VER, true);

	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'search_js_map', AMOTOS_PLUGIN_URL.'public/templates/shortcodes/car-search/assets/js/car-search-map' . $min_suffix . '.js', array('jquery','google-map','markerclusterer','select2_js'), AMOTOS_PLUGIN_VER, true);
	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'search_js', AMOTOS_PLUGIN_URL.'public/templates/shortcodes/car-search/assets/js/car-search' . $min_suffix . '.js', array('jquery','select2_js'), AMOTOS_PLUGIN_VER, true);

	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'car_featured', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-featured/assets/js/car-featured' . $min_suffix . '.js', array('jquery', AMOTOS_PLUGIN_PREFIX . 'owl_carousel'), AMOTOS_PLUGIN_VER, true);

	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'advanced_search_js', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-advanced-search/assets/js/car-advanced-search' . $min_suffix . '.js', array('jquery','select2_js'), AMOTOS_PLUGIN_VER, true);

	        wp_register_script(AMOTOS_PLUGIN_PREFIX . 'car_gallery', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-gallery/assets/js/car-gallery' . $min_suffix . '.js', array('jquery','imageLoaded','isotope',AMOTOS_PLUGIN_PREFIX.'owl_carousel'), AMOTOS_PLUGIN_VER, true);

            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'mini_search_js', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/car-mini-search/assets/js/car-mini-search' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_register_script( AMOTOS_PLUGIN_PREFIX . 'manager', AMOTOS_PLUGIN_URL . 'public/templates/shortcodes/manager/assets/js/manager' . $min_suffix . '.js', array( 'jquery',AMOTOS_PLUGIN_PREFIX . 'owl_carousel' ), AMOTOS_PLUGIN_VER, true );
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'archive-manager', AMOTOS_PLUGIN_URL . 'public/assets/js/manager/amotos-archive-manager' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);

            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'single-car', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-single-car' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);
            wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'single-car', 'amotos_single_car_vars', array(
                'ajax_url' => AMOTOS_AJAX_URL,
                'localization' => array(
                    'print_window_title' => esc_html__('Vehicle Print Window', 'auto-moto-stock'),
                )
            ));
            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'nearby-places', AMOTOS_PLUGIN_URL . 'public/assets/js/car/amotos-nearby-places' . $min_suffix . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);

            if (is_singular('car')) {
                wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'single-car');
                wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'nearby-places');
            }

            if (is_post_type_archive('car') || is_page('cars') || $this->is_car_taxonomy()) {
                wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'archive-car');
            }
            if (is_post_type_archive('manager')) {
                wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'archive-manager');
            }
            if (is_singular('invoice')) {
                wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'amotos-invoice');
            }
        }

        public function register_assets() {
            $enable_min_css = amotos_get_option('enable_min_css', 0) == 1 ? '.min' : '';
            $enable_min_js = amotos_get_option('enable_min_js', 0) == 1 ? '.min' : '';
            wp_register_script('stripe-checkout','https://checkout.stripe.com/checkout.js',array(),null,true);

            $cdn_bootstrap_css = amotos_get_option('cdn_bootstrap_css', '');
            if (!empty($cdn_bootstrap_css)) {
                wp_register_style('bootstrap', $cdn_bootstrap_css);
            } else {
                $is_rtl = amotos_is_rtl();
                if ($is_rtl) {
                    wp_register_style('bootstrap', AMOTOS_PLUGIN_URL . 'public/assets/vendors/bootstrap/css/bootstrap-rtl.min.css',array(),'4.6.2');
                } else {
                    wp_register_style('bootstrap', AMOTOS_PLUGIN_URL . 'public/assets/vendors/bootstrap/css/bootstrap.min.css',array(),'4.6.2');
                }
            }

            $cdn_bootstrap_js = amotos_get_option('cdn_bootstrap_js', '');
            if (!empty($cdn_bootstrap_css)) {
                wp_register_script('bootstrap', $cdn_bootstrap_js, array('jquery'),null,true);
            } else {
                wp_register_script('bootstrap', AMOTOS_PLUGIN_URL . 'public/assets/vendors/bootstrap/js/bootstrap.bundle.min.js', array('jquery'),'4.6.2',true);
            }

            wp_register_style(AMOTOS_PLUGIN_PREFIX . 'car-print', AMOTOS_PLUGIN_URL . 'public/assets/scss/print/car' . $enable_min_css . '.css',array(),AMOTOS_PLUGIN_VER);
            wp_register_style(AMOTOS_PLUGIN_PREFIX . 'car-print-rtl', AMOTOS_PLUGIN_URL . 'public/assets/scss/print/car-rtl' . $enable_min_css . '.css',array(),AMOTOS_PLUGIN_VER);

            wp_register_script(AMOTOS_PLUGIN_PREFIX . 'amotos-invoice', AMOTOS_PLUGIN_URL . 'public/assets/js/invoice/amotos-invoice' . $enable_min_js . '.js', array('jquery'), AMOTOS_PLUGIN_VER, true);

            wp_register_script( AMOTOS_PLUGIN_PREFIX . 'taxonomy-dealer', AMOTOS_PLUGIN_URL . 'public/assets/js/manager/amotos-taxonomy-dealer' . $enable_min_js . '.js', array( 'jquery' ), AMOTOS_PLUGIN_VER, true );
        }

        public function register_assets_google_map() {
            //return;
	        $googlemap_ssl = amotos_get_option('googlemap_ssl', 0);
	        $googlemap_api_key = amotos_get_option('googlemap_api_key', 'AIzaSyCLyuWY0RUhv7GxftSyI8Ka1VbeU7CTDls');
	        $googlemap_pin_cluster = amotos_get_option('googlemap_pin_cluster', 1);
	        if (esc_html($googlemap_ssl) == 1 || is_ssl()) {
		        wp_register_script('google-map', 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key), array('jquery'), AMOTOS_PLUGIN_VER, true);
	        } else {
		        wp_register_script('google-map', 'http://maps.googleapis.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key), array('jquery'), AMOTOS_PLUGIN_VER, true);
	        }
	        if ($googlemap_pin_cluster != 0) {
		        wp_register_script('markerclusterer', AMOTOS_PLUGIN_URL . 'public/assets/js/markerclusterer.min.js', array('jquery', 'google-map'), '2.1.1', true);
	        }
        }

        /**
         * @return bool
         */
        function is_car_taxonomy()
        {
            return is_tax(get_object_taxonomies('car'));
        }

        /**
         * @return bool
         */
        function is_manager_taxonomy()
        {
            return is_tax(get_object_taxonomies('manager'));
        }

        /**
         * @param $template
         * @return string
         */
        public function template_loader($template)
        {

            $find = array();
            $file = '';

            if (is_embed()) {
                return $template;
            }

            if (is_single() && (get_post_type() == 'car' || get_post_type() == 'manager' || get_post_type() == 'invoice')) {
                if (get_post_type() == 'car') {
                    $file = 'single-car.php';
                }
                if (get_post_type() == 'manager') {
                    $file = 'single-manager.php';
                }
                if (get_post_type() == 'invoice') {
                    $file = 'single-invoice.php';
                }
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;

            } elseif ($this->is_car_taxonomy()) {

                $term = get_queried_object();

                if (is_tax('car-type') || is_tax('car-status') || is_tax('car-styling') || is_tax('car-city') || is_tax('car-state') || is_tax('car-label') || is_tax('car-neighborhood')) {
                    $file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $file = 'archive-car.php';
                }

                $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = AMOTOS()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = AMOTOS()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;

            } elseif ($this->is_manager_taxonomy()) {

                $term = get_queried_object();

                if (is_tax('dealer')) {
                    $file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $file = 'archive-manager.php';
                }

                $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = AMOTOS()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = AMOTOS()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;

            } elseif (is_post_type_archive('car') || is_page('cars')) {
                $file = 'archive-car.php';
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;

            } elseif (is_post_type_archive('manager') || is_page('staff')) {

                $file = 'archive-manager.php';
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;
            }
            /*elseif (is_author()) {
                $file = 'author.php';
                $find[] = $file;
                $find[] = AMOTOS()->template_path() . $file;
            }*/

            if ($file) {
                $template = locate_template(array_unique($find));
                if (!$template) {
                    $template = AMOTOS_PLUGIN_DIR . '/public/templates/' . $file;
                }
            }

            return $template;
        }

        /**
         * @param $query
         * @return mixed
         */
        public function set_posts_per_page($query)
        {
            global $wp_the_query;
            if ((!is_admin()) && ($query === $wp_the_query) && ($query->is_archive() || $query->is_tax())) {
                if (is_post_type_archive('manager')) {
                    $archive_manager_item_amount = amotos_get_option('archive_manager_item_amount', 12);
                    $query->set('posts_per_page', $archive_manager_item_amount);
                } elseif (is_post_type_archive('car') || is_tax('car-type') || is_tax('car-status') || is_tax('car-styling')
                    || is_tax('car-label') || is_tax('car-state') || is_tax('car-city') || is_tax('car-neighborhood')) {
                    $custom_car_items_amount = amotos_get_option('archive_car_items_amount', 6);
                    $query->set('posts_per_page', $custom_car_items_amount);
                }
            }
            return $query;
        }

        public function print_tmpl_template() {
	        amotos_get_template('global/tmpl-template.php');
        }
    }
}