<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Shortcode' ) ) {
	/**
	 * AMOTOS_Shortcode_Manager class.
	 */
	class AMOTOS_Shortcode {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->include_system_shortcode();
			$this->register_public_shortcode();
		}

		/**
		 * Include system shortcode
		 */
		public function include_system_shortcode() {
			require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/system/class-amotos-shortcode-account.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/system/class-amotos-shortcode-car.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/system/class-amotos-shortcode-package.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/system/class-amotos-shortcode-payment.php';
			require_once AMOTOS_PLUGIN_DIR . 'includes/shortcodes/system/class-amotos-shortcode-invoice.php';
		}

		/**
		 * Register shortcode
		 */
		public function register_public_shortcode() {
			add_shortcode( 'amotos_car', array( $this, 'car_shortcode' ) );
			add_shortcode( 'amotos_car_carousel', array( $this, 'car_carousel_shortcode' ) );
			add_shortcode( 'amotos_car_slider', array( $this, 'car_slider_shortcode' ) );
			add_shortcode( 'amotos_car_gallery', array( $this, 'car_gallery_shortcode' ) );
			add_shortcode( 'amotos_car_featured', array( $this, 'car_featured_shortcode' ) );
			add_shortcode( 'amotos_car_type', array( $this, 'car_type_shortcode' ) );
			add_shortcode( 'amotos_car_search', array( $this, 'car_search_shortcode' ) );
			add_shortcode( 'amotos_car_search_map', array( $this, 'car_search_map_shortcode' ) );
			add_shortcode( 'amotos_car_advanced_search', array( $this, 'car_advanced_search_shortcode' ) );
			add_shortcode( 'amotos_car_mini_search', array( $this, 'car_mini_search_shortcode' ) );
			add_shortcode( 'amotos_car_map', array( $this, 'car_map_shortcode' ) );
			add_shortcode( 'amotos_manager', array( $this, 'manager_shortcode' ) );
			add_shortcode( 'amotos_dealer', array( $this, 'dealer_shortcode' ) );
		}

		/**
		 * Vehicle Gallery
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_gallery_shortcode( $atts ) {
			$filter_style = isset( $atts['filter_style'] ) ? $atts['filter_style'] : 'filter-isotope';

			if ( $filter_style == 'filter-isotope' ) {
				wp_enqueue_script( 'isotope' );
			}

			wp_enqueue_script( 'imageLoaded' );
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'car_gallery' );
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');


			return amotos_get_template_html( 'shortcodes/car-gallery/car-gallery.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Carousel with Left Navigation
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_carousel_shortcode( $atts ) {

			return amotos_get_template_html( 'shortcodes/car-carousel/car-carousel.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Slider
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_slider_shortcode( $atts ) {
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');
			return amotos_get_template_html( 'shortcodes/car-slider/car-slider.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Search
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_search_shortcode( $atts ) {
			$search_styles     = isset( $atts['search_styles'] ) ? $atts['search_styles'] : 'style-default';
			$map_search_enable = isset( $atts['map_search_enable'] ) ? $atts['map_search_enable'] : '';

			if ( $search_styles === 'style-vertical' || $search_styles === 'style-absolute' ) {
				$map_search_enable = 'true';
			}

			if ( $map_search_enable == 'true' ) {
				wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'search_js_map' );
			} else {
				wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'search_js' );
			}

			return amotos_get_template_html( 'shortcodes/car-search/car-search.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Search Map
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_search_map_shortcode( $atts ) {
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'search_map' );
			return amotos_get_template_html( 'shortcodes/car-search-map/car-search-map.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Full Search
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_advanced_search_shortcode( $atts ) {
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'advanced_search_js' );
			return amotos_get_template_html( 'shortcodes/car-advanced-search/car-advanced-search.php', array( 'atts' => $atts ) );
		}

		/**
		 * Mini Search
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_mini_search_shortcode( $atts ) {
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'mini_search_js');
			return amotos_get_template_html( 'shortcodes/car-mini-search/car-mini-search.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Featured
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_featured_shortcode( $atts ) {
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'car_featured' );

			return amotos_get_template_html( 'shortcodes/car-featured/car-featured.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Type
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_type_shortcode( $atts ) {
			return amotos_get_template_html( 'shortcodes/car-type/car-type.php', array( 'atts' => $atts ) );
		}

		/**
		 * Vehicle Shortcode
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_shortcode( $atts ) {
            wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'owl_carousel');
			return amotos_get_template_html( 'shortcodes/car/car.php', array( 'atts' => $atts ) );
		}

		/**
		 * Manager shortcode
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function manager_shortcode( $atts ) {
			return amotos_get_template_html( 'shortcodes/manager/manager.php', array( 'atts' => $atts ) );
		}

		/**
		 * Dealer shortcode
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function dealer_shortcode( $atts ) {
			return amotos_get_template_html( 'shortcodes/dealer/dealer.php', array( 'atts' => $atts ) );
		}

		/**
		 * Googlemap Vehicle
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function car_map_shortcode( $atts ) {
			return amotos_get_template_html( 'shortcodes/car-map/car-map.php', array( 'atts' => $atts ) );
		}

		/**
		 * Filter Ajax callback
		 */
		public function car_gallery_fillter_ajax() {
			$car_type = isset( $_REQUEST['car_type'] ) ? str_replace( '.', '', amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) ) : '';
			$is_carousel   = isset( $_REQUEST['is_carousel'] ) ? amotos_clean( wp_unslash( $_REQUEST['is_carousel'] ) ) : '';
			$columns_gap   = isset( $_REQUEST['columns_gap'] ) ? amotos_clean(wp_unslash( $_REQUEST['columns_gap'] ))  : 'col-gap-30';
			$columns       = isset( $_REQUEST['columns'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['columns'] ))  ) : 4;
			$item_amount   = isset( $_REQUEST['item_amount'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['item_amount'] ))  ) : 10;
			$image_size    = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
			$color_scheme  = isset( $_REQUEST['color_scheme'] ) ? amotos_clean( wp_unslash( $_REQUEST['color_scheme'] ) ) : '';
			return amotos_do_shortcode( 'amotos_car_gallery', array(
				'is_carousel'     => $is_carousel,
				'color_scheme'    => $color_scheme,
				'columns'         => $columns,
				'item_amount'     => $item_amount,
				'image_size'      => $image_size,
				'columns_gap'     => $columns_gap,
				'category_filter' => "true",
				'car_type'        => $car_type
			) );

			wp_die();
		}

		/**
		 * Filter City Ajax callback
		 */
		public function car_featured_fillter_city_ajax() {
			$car_city         = isset( $_REQUEST['car_city'] ) ? str_replace( '.', '', amotos_clean( wp_unslash( $_REQUEST['car_city'] ) ) ) : '';
			$layout_style          = isset( $_REQUEST['layout_style'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout_style'] ) ) : '';
			$car_type         = isset( $_REQUEST['car_type'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) : '';
			$car_status       = isset( $_REQUEST['car_status'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_status'] ) ) : '';
			$car_styling      = isset( $_REQUEST['car_styling'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_styling'] ) ) : '';
			$car_cities       = isset( $_REQUEST['car_cities'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_cities'] ) ) : '';
			$car_state        = isset( $_REQUEST['car_state'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_state'] ) ) : '';
			$car_neighborhood = isset( $_REQUEST['car_neighborhood'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_neighborhood'] ) ) : '';
			$car_label        = isset( $_REQUEST['car_label'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_label'] ) ) : '';
			$color_scheme          = isset( $_REQUEST['color_scheme'] ) ? amotos_clean( wp_unslash( $_REQUEST['color_scheme'] ) ) : '';
			$item_amount           = isset( $_REQUEST['item_amount'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['item_amount'] ))  ) : 10;
			$image_size            = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
			$include_heading       = isset( $_REQUEST['include_heading'] ) ? amotos_clean( wp_unslash( $_REQUEST['include_heading'] ) ) : '';
			$heading_sub_title     = isset( $_REQUEST['heading_sub_title'] ) ? amotos_clean( wp_unslash( $_REQUEST['heading_sub_title'] ) ) : '';
			$heading_title         = isset( $_REQUEST['heading_title'] ) ? amotos_clean( wp_unslash( $_REQUEST['heading_title'] ) ) : '';
			$heading_text_align = isset( $_REQUEST['heading_text_align'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['heading_text_align'] ) ) : '';
			return amotos_do_shortcode( 'amotos_car_featured', array(
				'layout_style'          => $layout_style,
				'car_type'         => $car_type,
				'car_status'       => $car_status,
				'car_styling'      => $car_styling,
				'car_cities'       => $car_cities,
				'car_state'        => $car_state,
				'car_neighborhood' => $car_neighborhood,
				'car_label'        => $car_label,
				'color_scheme'          => $color_scheme,
				'item_amount'           => $item_amount,
				'image_size2'           => $image_size,
				'include_heading'       => $include_heading,
				'heading_sub_title'     => $heading_sub_title,
				'heading_title'         => $heading_title,
				'heading_text_align'    => $heading_text_align,
				'car_city'         => $car_city
			) );
			wp_die();
		}

		/**
		 * Vehicle paging
		 */
		public function car_paging_ajax() {
			$paged         = isset( $_REQUEST['paged'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['paged'] ))  ) : 1;
			$layout        = isset( $_REQUEST['layout'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout'] ) ) : '';
			$items_amount  = isset( $_REQUEST['items_amount'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['items_amount'] ))  ) : 10;
			$columns       = isset( $_REQUEST['columns'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['columns'] ) ) ) : 4;
			$image_size    = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
			$columns_gap   = isset( $_REQUEST['columns_gap'] ) ? amotos_clean(wp_unslash( $_REQUEST['columns_gap'] )) : 'col-gap-30';
			$view_all_link = isset( $_REQUEST['view_all_link'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_all_link'] ) ) : '';

			$car_type         = isset( $_REQUEST['car_type'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) : '';
			$car_status       = isset( $_REQUEST['car_status'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_status'] ) ) : '';
			$car_styling      = isset( $_REQUEST['car_styling'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_styling'] ) ) : '';
			$car_city         = isset( $_REQUEST['car_city'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_city'] ) ) : '';
			$car_state        = isset( $_REQUEST['car_state'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_state'] ) ) : '';
			$car_neighborhood = isset( $_REQUEST['car_neighborhood'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_neighborhood'] ) ) : '';
			$car_label        = isset( $_REQUEST['car_label'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_label'] ) ) : '';
			$car_featured     = isset( $_REQUEST['car_featured'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_featured'] ) ) : '';

			$author_id = isset( $_REQUEST['author_id'] ) ? amotos_clean( wp_unslash( $_REQUEST['author_id'] ) ) : '';
			$manager_id  = isset( $_REQUEST['manager_id'] ) ? amotos_clean( wp_unslash( $_REQUEST['manager_id'] ) ) : '';
			return amotos_do_shortcode( 'amotos_car', array(
				'item_amount'           => $items_amount,
				'layout_style'          => $layout,
				'view_all_link'         => $view_all_link,
				'show_paging'           => "true",
				'columns'               => $columns,
				'image_size'            => $image_size,
				'columns_gap'           => $columns_gap,
				'paged'                 => $paged,
				'car_type'         => $car_type,
				'car_status'       => $car_status,
				'car_styling'      => $car_styling,
				'car_city'         => $car_city,
				'car_state'        => $car_state,
				'car_neighborhood' => $car_neighborhood,
				'car_label'        => $car_label,
				'car_featured'     => $car_featured,
				'author_id'             => $author_id,
				'manager_id'              => $manager_id
			) );
			wp_die();
		}

		/**
		 * Manager paging
		 */
		public function manager_paging_ajax() {
			$paged       = isset( $_REQUEST['paged'] ) ? absint( amotos_clean(wp_unslash( $_REQUEST['paged'] )) ) : 1;
			$layout      = isset( $_REQUEST['layout'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout'] ) ) : '';
			$item_amount = isset( $_REQUEST['item_amount'] ) ? absint( amotos_clean(wp_unslash( $_REQUEST['item_amount'] )) ) : 10;
			$items       = isset( $_REQUEST['items'] ) ? amotos_clean( wp_unslash( $_REQUEST['items'] ) ) : '';
			$image_size  = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
			$show_paging = isset( $_REQUEST['show_paging'] ) ? amotos_clean( wp_unslash( $_REQUEST['show_paging'] ) ) : '';
			$post_not_in = isset( $_REQUEST['post_not_in'] ) ? amotos_clean( wp_unslash( $_REQUEST['post_not_in'] ) ) : '';
			return amotos_do_shortcode( 'amotos_manager', array(
				'layout_style' => $layout,
				'item_amount'  => $item_amount,
				'items'        => $items,
				'image_size'   => $image_size,
				'paged'        => $paged,
				'show_paging'  => $show_paging,
				'post_not_in'  => $post_not_in
			) );
			wp_die();
		}

		public function car_set_session_view_as_ajax() {
			AMOTOS_Compare::open_session();
			$view_as = isset( $_REQUEST['view_as'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_as'] ) ) : '';
			if ( ! empty( $view_as ) && in_array( $view_as, array( 'car-list', 'car-grid' ) ) ) {
				$_SESSION['car_view_as'] = $view_as;
			}
		}

		public function manager_set_session_view_as_ajax() {
			$view_as = isset( $_REQUEST['view_as'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_as'] ) ) : '';
			if ( ! empty( $view_as ) && in_array( $view_as, array( 'manager-list', 'manager-grid' ) ) ) {
				$_SESSION['manager_view_as'] = $view_as;
			}
		}
	}
}
new AMOTOS_Shortcode();

