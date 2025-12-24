<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'AMOTOS_Query' ) ) {
	class AMOTOS_Query {
		private static $_instance;
		private $parameters = [];

		private $_atts = [];

		private $_query_args = [];

		public static function get_instance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			// meta query
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_address' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_owner' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_seat' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_door' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_price' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_mileage' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_power' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_volume' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_country' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_identity' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_featured' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_custom_fields' ) );
			add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_user' ) );
            add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_car_search_ajax' ) );
            add_filter( 'amotos_car_query_meta_query', array( $this, 'get_meta_query_advanced_search' ) );

			// tax query
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_type' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_status' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_label' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_city' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_state' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_neighborhood' ) );
			add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_styling' ) );
            add_filter( 'amotos_car_query_tax_query', array( $this, 'get_tax_query_car_search_ajax' ) );
		}

		public function reset_parameter() {
			$this->parameters = [];
		}

		public function set_parameter( $parameter ) {
			$this->parameters[] = $parameter;
		}

		public function get_parameters() {
			return $this->parameters;
		}

		public function set_atts($atts = array()) {
			$this->_atts = wp_parse_args($atts, array(
				'keyword' => '',
				'title' => '',
				'address' => '',
				'type' => '',
				'city' => '',
				'status' => '',
				'owners' => '',
				'seats' => '',
				'doors' => '',
				'min-mileage' => '',
				'max-mileage' => '',
				'min-price' => '',
				'max-price' => '',
				'state' => '',
				'country' => '',
				'neighborhood' => '',
				'label' => '',
				'min-power' => '',
				'max-power' => '',
				'min-volume' => '',
				'max-volume' => '',
				'car_identity' => '',
				'other_stylings' => '',
				'item_amount' => '',
				'paged' => 1,
				'sortby' => '',
				'user_id' => '',
				'author_id' => '',
				'manager_id' => '',
				'featured' => FALSE
			));
		}

		public function get_car_query_args($atts = array(), $query_args = array()) {
			$this->reset_parameter();
			$this->set_atts($atts);
			$item_amount = isset($_REQUEST['item_amount']) ? amotos_clean(wp_unslash($_REQUEST['item_amount'])) : $this->_atts['item_amount'];
			$paged   =  get_query_var( 'page' ) ? intval( get_query_var( 'page' ) ) : (get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : $this->_atts['paged']);
			$sortby = isset( $_REQUEST['sortby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['sortby'] ) ) : $this->_atts['sortby'];

			$this->_query_args = wp_parse_args($query_args,[
				'post_type'      => 'car',
				'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
				'paged' => $paged,
				'post_status'    => 'publish',
				'orderby'        => [
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				],
			]);

			if (in_array($sortby,['a_price','d_price','a_date','d_date','featured','most_viewed'])) {
				if ( $sortby == 'a_price' ) {
					$this->_query_args['orderby']  = 'meta_value_num';
					$this->_query_args['meta_key'] = AMOTOS_METABOX_PREFIX . 'car_price';
					$this->_query_args['order']    = 'ASC';
				} else if ( $sortby == 'd_price' ) {
					$this->_query_args['orderby']  = 'meta_value_num';
					$this->_query_args['meta_key'] = AMOTOS_METABOX_PREFIX . 'car_price';
					$this->_query_args['order']    = 'DESC';
				} else if ( $sortby == 'featured' ) {
					$this->_query_args['amotos_orderby_featured'] = TRUE;
				} else if ( $sortby == 'most_viewed' ) {
					$this->_query_args['amotos_orderby_viewed'] = TRUE;
				} else if ( $sortby == 'a_date' ) {
					$this->_query_args['orderby'] = 'date';
					$this->_query_args['order']   = 'ASC';
				} else if ( $sortby == 'd_date' ) {
					$this->_query_args['orderby'] = 'date';
					$this->_query_args['order']   = 'DESC';
				}
			} else {
				$featured_toplist = amotos_get_option('featured_toplist', 1);
				if($featured_toplist !=0 )
				{
					$this->_query_args['amotos_orderby_featured'] = true;
				}
			}

			$meta_query         = $this->get_meta_query();
			$tax_query          = $this->get_tax_query();
			if ( count( $meta_query ) > 1 ) {
				$meta_query['relation'] = 'AND';
			}
			if ( count( $tax_query ) > 1 ) {
				$tax_query['relation'] = 'AND';
			}

			$keyword = isset($_REQUEST['keyword']) ? amotos_clean(wp_unslash($_REQUEST['keyword']))  : $this->_atts['keyword'];
			$keyword_meta_query = $keyword_tax_query = '';
			if ( ! empty( $keyword ) ) {
				$keyword_field = amotos_get_option( 'keyword_field', 'veh_address' );
				if ( $keyword_field === 'veh_address' ) {
					$keyword_meta_query = $this->get_meta_query_keyword( $keyword );
				} elseif ( $keyword_field === 'veh_city_state_county' ) {
					$keyword_tax_query = $this->get_tax_query_keyword( $keyword );
				} else {
					$this->_query_args['s'] = $keyword;
					/* translators: %s: parameter keyword */
                    $this->set_parameter( wp_kses_post(sprintf( __( 'Keyword: <strong>%s</strong>', 'auto-moto-stock' ), $keyword )));
				}

			}

            $title = isset($_REQUEST['title']) ? amotos_clean(wp_unslash($_REQUEST['title']))  : $this->_atts['title'];
            if (!empty($title)) {
                $this->_query_args['s'] = $title;
                /* translators: %s: parameter title */
                $this->set_parameter( wp_kses_post(sprintf( __( 'Title: <strong>%s</strong>', 'auto-moto-stock' ), $title )));
            }

			$_meta_query = $this->_query_args['meta_query'] ?? '';
			$this->_query_args['meta_query'] = array(
				'relation' => 'AND'
			);

			if (!empty($meta_query)) {
				$this->_query_args['meta_query'][] = $meta_query;
			}


			if (!empty($keyword_meta_query)) {
				$this->_query_args['meta_query'][] = $keyword_meta_query;
			}

			if (!empty($_meta_query)) {
				$this->_query_args['meta_query'][] = $_meta_query;
			}

			$_tax_query = $this->_query_args['tax_query'] ?? '';
			$this->_query_args['tax_query'] = array(
				'relation' => 'AND'
			);

			if (!empty($tax_query)) {
				$this->_query_args['tax_query'][] = $tax_query;
			}

			if (!empty($keyword_tax_query)) {
				$this->_query_args['tax_query'][] = $keyword_tax_query;
			}

			if (!empty($_tax_query)) {
				$this->_query_args['tax_query'][] = $_tax_query;
			}
			return apply_filters('amotos_get_car_query_args',$this->_query_args);
		}

		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}

			return array_filter( apply_filters( 'amotos_car_query_meta_query', $meta_query, $this ) );
		}

		public function get_tax_query( $tax_query = array()) {
			if ( ! is_array( $tax_query ) ) {
				$tax_query = array(
					'relation' => 'AND',
				);
			}

			return array_filter( apply_filters( 'amotos_car_query_tax_query', $tax_query, $this ) );
		}

		public function get_meta_query_keyword( $keyword ) {
			return [
				'relation' => 'OR',
				[
					'key'     => AMOTOS_METABOX_PREFIX . 'car_address',
					'value'   => $keyword,
					'type'    => 'CHAR',
					'compare' => 'LIKE',
				],
				[
					'key'     => AMOTOS_METABOX_PREFIX . 'car_zip',
					'value'   => $keyword,
					'type'    => 'CHAR',
					'compare' => 'LIKE',
				],
				[
					'key'     => AMOTOS_METABOX_PREFIX . 'car_identity',
					'value'   => $keyword,
					'type'    => 'CHAR',
					'compare' => '=',
				],
			];
		}

		public function get_tax_query_keyword( $keyword ) {
			$taxlocation[] = sanitize_title( $keyword );
			return [
				'relation' => 'OR',
				[
					'taxonomy' => 'car-state',
					'field'    => 'slug',
					'terms'    => $taxlocation,
				],
				[
					'taxonomy' => 'car-city',
					'field'    => 'slug',
					'terms'    => $taxlocation,
				],
				[
					'taxonomy' => 'car-neighborhood',
					'field'    => 'slug',
					'terms'    => $taxlocation,
				],
			];
		}

		public function get_meta_query_address($meta_query) {
			$address = isset($_REQUEST['address']) ? amotos_clean(wp_unslash($_REQUEST['address']))  : $this->_atts['address'];
			if (!empty($address)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_address',
					'value' => $address,
					'type' => 'CHAR',
					'compare' => 'LIKE',
				);
                /* translators: %s: parameter address */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Keyword: <strong>%s</strong>', 'auto-moto-stock' ), $address )));
			}
			return $meta_query;
		}

		public function get_meta_query_owner( $meta_query ) {
			$owners = isset($_REQUEST['owners']) ? amotos_clean(wp_unslash($_REQUEST['owners']))  : $this->_atts['owners'];
			if (!empty($owners)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_owners',
					'value' => $owners,
					'type' => 'CHAR',
					'compare' => '=',
				);
                /* translators: %s: parameter owners */
				$this->set_parameter(wp_kses_post(sprintf( __( 'Owner: <strong>%s</strong>', 'auto-moto-stock' ), $owners )));
			}
			return $meta_query;
		}

		public function get_meta_query_seat( $meta_query ) {
			$seats = isset($_REQUEST['seats']) ? amotos_clean(wp_unslash($_REQUEST['seats']))  : $this->_atts['seats'];
			if (!empty($seats)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_seats',
					'value' => $seats,
					'type' => 'CHAR',
					'compare' => '=',
				);
                /* translators: %s: parameter seats */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Seat: <strong>%s</strong>', 'auto-moto-stock' ), $seats )) );
			}
			return $meta_query;
		}

		public function get_meta_query_door( $meta_query ) {
			$doors = isset($_REQUEST['doors']) ? amotos_clean(wp_unslash($_REQUEST['doors']))  : $this->_atts['doors'];
			if (!empty($doors)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_doors',
					'value' => $doors,
					'type' => 'CHAR',
					'compare' => '=',
				);
                /* translators: %s: parameter doors */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Door: <strong>%s</strong>', 'auto-moto-stock' ), $doors )) );
			}
			return $meta_query;
		}

		public function get_meta_query_price( $meta_query ) {
			$min_price = isset($_REQUEST['min-price']) ? amotos_clean(wp_unslash($_REQUEST['min-price']))  : $this->_atts['min-price'];
			$max_price = isset($_REQUEST['max-price']) ? amotos_clean(wp_unslash($_REQUEST['max-price']))  : $this->_atts['max-price'];
			$car_price_query_args = $this->get_car_price_query_args($min_price, $max_price);
			if (!empty($car_price_query_args)) {
				$meta_query[] = $car_price_query_args;
			}
			return $meta_query;
		}

		public function get_car_price_query_args($min_price, $max_price){
			$query_args = [];
			if (!empty($min_price) && !empty($max_price)) {
				$min_price = doubleval(amotos_clean_double_val($min_price));
				$max_price = doubleval(amotos_clean_double_val($max_price));

				if (($min_price >= 0) && ($max_price >= $min_price)) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_price',
						'value'   => [ $min_price, $max_price ],
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN',
					];
                    /* translators: %1$s: min price; %2$s: max price */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Price: <strong>%1$s - %2$s</strong>', 'auto-moto-stock' ), $min_price, $max_price )));
				}
			} else if (!empty($min_price)) {
				$min_price = doubleval(amotos_clean_double_val($min_price));
				if ($min_price >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_price',
						'value'   => $min_price,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					];
                    /* translators: %s: parameter min price */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Min Price: <strong>%s</strong>', 'auto-moto-stock' ), $min_price )));
				}
			} else if (!empty($max_price)) {
				$max_price = doubleval(amotos_clean_double_val($max_price));
				if ($max_price >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_price',
						'value'   => $max_price,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					];
                    /* translators: %s: parameter max price */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Max Price: <strong>%s</strong>', 'auto-moto-stock' ), $max_price )) );
				}
			}
			return $query_args;
		}

		// Meta Query mileage
		public function get_meta_query_mileage( $meta_query ) {
			$min_mileage = isset($_REQUEST['min-mileage']) ? amotos_clean(wp_unslash($_REQUEST['min-mileage']))  : $this->_atts['min-mileage'];
			$max_mileage = isset($_REQUEST['max-mileage']) ? amotos_clean(wp_unslash($_REQUEST['max-mileage']))  : $this->_atts['max-mileage'];
			$car_mileage_query_args = $this->get_car_mileage_query_args($min_mileage,$max_mileage);
			if (!empty($car_mileage_query_args)) {
				$meta_query[] = $car_mileage_query_args;
			}
			return $meta_query;
		}
		// Vehicle mileage
		public function get_car_mileage_query_args($min_mileage, $max_mileage) {
			$query_args = [];
			if (!empty($min_mileage) && !empty($max_mileage)) {
				$min_mileage = intval($min_mileage);
				$max_mileage = intval($max_mileage);

				if (($min_mileage >= 0)  && ($max_mileage >= $min_mileage)) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_mileage',
						'value'   => [ $min_mileage, $max_mileage ],
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN',
					];
                    /* translators: %1$s: min mileage; %2$s: max mileage */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Mileage: <strong>%1$s - %2$s</strong>', 'auto-moto-stock' ), $min_mileage, $max_mileage )));
				}

			} else if (!empty($max_mileage)) {
				$max_mileage = intval($max_mileage);
				if ($max_mileage >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_mileage',
						'value'   => $max_mileage,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					];
                    /* translators: %s: parameter max mileage */
					$this->set_parameter(wp_kses_post(sprintf( __( 'Max Mileage: <strong> %s</strong>', 'auto-moto-stock' ), $max_mileage ) ));
				}
			} else if (!empty($min_mileage)) {
				$min_mileage = intval($min_mileage);
				if ($min_mileage >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_mileage',
						'value'   => $min_mileage,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					];
                    /* translators: %s: parameter min mileage */
					$this->set_parameter(wp_kses_post(sprintf( __( 'Min Mileage: <strong> %s</strong>', 'auto-moto-stock' ), $min_mileage ) ));
				}
			}
			return $query_args;
		}

		// Meta Query power
		public function get_meta_query_power( $meta_query ) {
			$min_power = isset($_REQUEST['min-power']) ? amotos_clean(wp_unslash($_REQUEST['min-power']))  : $this->_atts['min-power'];
			$max_power = isset($_REQUEST['max-power']) ? amotos_clean(wp_unslash($_REQUEST['max-power'])) : $this->_atts['max-power'];
			$car_power_query_args = $this->get_car_power_query_args($min_power,$max_power);
			if (!empty($car_power_query_args)) {
				$meta_query[] = $car_power_query_args;
			}
			return $meta_query;
		}
		// Vehicle power
		public function get_car_power_query_args($min_power, $max_power) {
			$query_args = [];
			if (!empty($min_power) && !empty($max_power)) {
				$min_power = intval($min_power);
				$max_power = intval($max_power);

				if (($min_power >= 0)  && ($max_power >= $min_power)) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_power',
						'value'   => [ $min_power, $max_power ],
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN',
					];
                    /* translators: %1$s: min power, %2$s: max power */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Power: <strong>%1$s - %2$s</strong>', 'auto-moto-stock' ), $min_power, $max_power )));
				}

			} else if (!empty($max_power)) {
				$max_power = intval($max_power);
				if ($max_power >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_power',
						'value'   => $max_power,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					];
                    /* translators: %s: parameter max power */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Max Power: <strong>%s</strong>', 'auto-moto-stock' ), $max_power )));
				}
			} else if (!empty($min_power)) {
				$min_power = intval($min_power);
				if ($min_power >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_power',
						'value'   => $min_power,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					];
                    /* translators: %s: parameter min power*/
					$this->set_parameter(wp_kses_post(sprintf( __( 'Min Power: <strong>%s</strong>', 'auto-moto-stock' ), $min_power )));
				}
			}
			return $query_args;
		}

		// Meta Query volume
		public function get_meta_query_volume( $meta_query ) {
			$min_volume = isset($_REQUEST['min-volume']) ? amotos_clean(wp_unslash($_REQUEST['min-volume']))  : $this->_atts['min-volume'];
			$max_volume = isset($_REQUEST['max-volume']) ? amotos_clean(wp_unslash($_REQUEST['max-volume'])) : $this->_atts['max-volume'];
			$car_volume_query_args = $this->get_car_volume_query_args($min_volume,$max_volume);
			if (!empty($car_volume_query_args)) {
				$meta_query[] = $car_volume_query_args;
			}
			return $meta_query;
		}
		// Vehicle volume
		public function get_car_volume_query_args($min_volume, $max_volume) {
			$query_args = [];
			if (!empty($min_volume) && !empty($max_volume)) {
				$min_volume = intval($min_volume);
				$max_volume = intval($max_volume);

				if (($min_volume >= 0)  && ($max_volume >= $min_volume)) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_volume',
						'value'   => [ $min_volume, $max_volume ],
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN',
					];
                    /* translators: %1$s: min volume, %2$s: max volume */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Cubic Capacity: <strong>%1$s - %2$s</strong>', 'auto-moto-stock' ), $min_volume, $max_volume )));
				}

			} else if (!empty($max_volume)) {
				$max_volume = intval($max_volume);
				if ($max_volume >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_volume',
						'value'   => $max_volume,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					];
                    /* translators: %s: parameter max volume */
					$this->set_parameter( wp_kses_post(sprintf( __( 'Max Cubic Capacity: <strong>%s</strong>', 'auto-moto-stock' ), $max_volume )));
				}
			} else if (!empty($min_volume)) {
				$min_volume = intval($min_volume);
				if ($min_volume >= 0) {
					$query_args = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_volume',
						'value'   => $min_volume,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					];
                    /* translators: %s: parameter min volume*/
					$this->set_parameter(wp_kses_post(sprintf( __( 'Min Cubic Capacity: <strong>%s</strong>', 'auto-moto-stock' ), $min_volume )));
				}
			}
			return $query_args;
		}

		public function get_meta_query_country( $meta_query ) {
			$country = isset($_REQUEST['country']) ? amotos_clean(wp_unslash($_REQUEST['country']))  : $this->_atts['country'];
			if (!empty($country)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_country',
					'value' => $country,
					'type' => 'CHAR',
					'compare' => '=',
				);
                /* translators: %s: parameter country */
				$this->set_parameter(wp_kses_post(sprintf( __( 'Country: <strong>%s</strong>', 'auto-moto-stock' ), $country ) ));
			}
			return $meta_query;
		}

		public function get_meta_query_identity( $meta_query ) {
			$car_identity = isset($_REQUEST['car_identity']) ? amotos_clean(wp_unslash($_REQUEST['car_identity']))  : $this->_atts['car_identity'];
			if ( ! empty( $car_identity ) ) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX. 'car_identity',
					'value' => $car_identity,
					'type' => 'CHAR',
					'compare' => '=',
				);
                /* translators: %s: parameter vehicle identity */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Vehicle ID: <strong>%s</strong>', 'auto-moto-stock' ), $car_identity ) ));
			}

			return $meta_query;
		}

		public function get_meta_query_featured( $meta_query ) {
			$car_featured = isset($_REQUEST['featured']) ? amotos_clean(wp_unslash($_REQUEST['featured']))  : $this->_atts['featured'];
			if (  filter_var($car_featured, FILTER_VALIDATE_BOOLEAN)) {
				$meta_query[] = array(
					'key' => AMOTOS_METABOX_PREFIX . 'car_featured',
					'value' => true,
					'compare' => '=',
				);
			}
			return $meta_query;
		}

		public function get_meta_query_custom_fields($meta_query) {
			$additional_fields = amotos_get_search_additional_fields();
			foreach ($additional_fields as $id => $title) {
				$field = amotos_get_search_additional_field($id);
				if ($field === false) {
					continue;
				}
				$field_type = isset($field['field_type']) ? $field['field_type'] : 'text';
				$field_value = isset($_REQUEST[$id]) ? amotos_clean( wp_unslash( $_REQUEST[$id] ) ) : '';
				if (!empty($field_value)) {
					if ($field_type === 'checkbox_list') {
						$meta_query[]      = array(
							'key'     => AMOTOS_METABOX_PREFIX . $id,
							'value'   => $field_value,
							'type'    => 'CHAR',
							'compare' => 'LIKE',
						);
					} else {
						$meta_query[]      = array(
							'key'     => AMOTOS_METABOX_PREFIX . $id,
							'value'   => $field_value,
							'type'    => 'CHAR',
							'compare' => '=',
						);
					}
                    /* translators: %1$s: title of additional field, %2$s: value of additional field */
					$this->set_parameter( sprintf(__( '%1$s: <strong>%2$s</strong>', 'auto-moto-stock' ) ,$title , $field_value ) );
				}
			}

			return $meta_query;
		}

		public function get_meta_query_user($meta_query) {
			$user_id = isset($_REQUEST['user_id']) ? amotos_clean(wp_unslash($_REQUEST['user_id'])) : $this->_atts['user_id'];
			$manager_id = isset($_REQUEST['manager_id']) ? amotos_clean(wp_unslash($_REQUEST['manager_id'])) : $this->_atts['manager_id'];
			$author_id =  isset($_REQUEST['author_id']) ? amotos_clean(wp_unslash($_REQUEST['author_id'])) : $this->_atts['author_id'];
			if (!empty($user_id)) {
				$author_id = $user_id;
				$manager_id = get_user_meta($author_id,AMOTOS_METABOX_PREFIX . 'author_manager_id', TRUE);
			}

			if (!empty($manager_id) && empty($author_id)) {
				$author_id = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', TRUE);
			}

			if (!empty($author_id) && !empty($manager_id)) {
				$meta_query[] = array(
					'relation' => 'OR',
					array(
						'key' => AMOTOS_METABOX_PREFIX . 'car_manager',
                        'value' => explode(',', $manager_id),
                        'compare' => 'IN'
					),
					array(
						'key' => AMOTOS_METABOX_PREFIX . 'car_author',
                        'value' => explode(',', $author_id),
                        'compare' => 'IN'
					)
				);
			} else {
				if (!empty($author_id)) {
					$this->_query_args['author'] = $author_id;
				} else if (!empty($manager_id)) {
					$meta_query[] = [
						'key'     => AMOTOS_METABOX_PREFIX . 'car_manager',
                        'value' => explode(',', $manager_id),
                        'compare' => 'IN'
					];
				}
			}
			return $meta_query;
		}

		public function get_tax_query_type( $tax_query ) {
			$type = isset( $_REQUEST['type'] ) ?  wp_unslash( $_REQUEST['type'] )  : $this->_atts['type'];
			if ( ! empty( $type ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-type',
					'field'    => 'slug',
					'terms'    => $type
				);

				if (is_array($type)) {
					$type = implode( ', ', $type );
				}
                /* translators: %s: parameter vehicle type */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Vehicle Type: <strong>%s</strong>', 'auto-moto-stock' ), $type )));
			}

			return $tax_query;
		}

		public function get_tax_query_status( $tax_query ) {
			$status = isset( $_REQUEST['status'] ) ?  wp_unslash( $_REQUEST['status'] )  : $this->_atts['status'];
			if ( ! empty( $status ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-status',
					'field'    => 'slug',
					'terms'    => $status
				);

				if (is_array($status)) {
					$status = implode( ', ', $status );
				}
                /* translators: %s: parameter vehicle status */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Status: <strong>%s</strong>', 'auto-moto-stock' ), $status )));
			}

			return $tax_query;
		}

		public function get_tax_query_label( $tax_query ) {
			$label = isset( $_REQUEST['label'] ) ?  wp_unslash( $_REQUEST['label'] )  : $this->_atts['label'];
			if ( ! empty( $label ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-label',
					'field'    => 'slug',
					'terms'    => $label
				);

				if (is_array($label)) {
					$label = implode( ', ', $label );
				}
                /* translators: %s: parameter vehicle label */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Label: <strong>%s</strong>', 'auto-moto-stock' ), $label )));
			}

			return $tax_query;
		}

		public function get_tax_query_city( $tax_query ) {
			$city = isset( $_REQUEST['city'] ) ?  wp_unslash( $_REQUEST['city'] )  : $this->_atts['city'];
			if ( ! empty( $city ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-city',
					'field'    => 'slug',
					'terms'    => $city
				);

				if (is_array($city)) {
					$city = implode( ', ', $city );
				}
                /* translators: %s: parameter city */
				$this->set_parameter( wp_kses_post(sprintf( __( 'City: <strong>%s</strong>', 'auto-moto-stock' ), $city )));
			}

			return $tax_query;
		}

		public function get_tax_query_state( $tax_query ) {
			$state = isset( $_REQUEST['state'] ) ?  wp_unslash( $_REQUEST['state'] )  : $this->_atts['state'];
			if ( ! empty( $state ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-state',
					'field'    => 'slug',
					'terms'    => $state
				);

				if (is_array($state)) {
					$state = implode( ', ', $state );
				}
                /* translators: %s: parameter state */
				$this->set_parameter( wp_kses_post(sprintf( __( 'State: <strong>%s</strong>', 'auto-moto-stock' ), $state )));
			}

			return $tax_query;
		}

		public function get_tax_query_neighborhood( $tax_query ) {
			$neighborhood = isset( $_REQUEST['neighborhood'] ) ?  wp_unslash( $_REQUEST['neighborhood'] )  : $this->_atts['neighborhood'];
			if ( ! empty( $neighborhood ) ) {
				$tax_query[] = array(
					'taxonomy' => 'car-neighborhood',
					'field'    => 'slug',
					'terms'    => $neighborhood
				);

				if (is_array($neighborhood)) {
					$neighborhood = implode( ', ', $neighborhood );
				}
                /* translators: %s: parameter neighborhood */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Neighborhood: <strong>%s</strong>', 'auto-moto-stock' ), $neighborhood )));
			}

			return $tax_query;
		}

		public function get_tax_query_styling( $tax_query ) {
			$stylings = isset( $_REQUEST['other_stylings'] ) ?  wp_unslash( $_REQUEST['other_stylings'] )  : $this->_atts['other_stylings'];
			if ( ! empty( $stylings )) {
                if (!is_array($stylings)) {
                    $stylings = explode(';',$stylings);
                }
				$tax_query[] = array(
					'taxonomy' => 'car-styling',
					'field'    => 'slug',
					'terms'    => $stylings
				);
                /* translators: %s: parameter vehicle styling */
				$this->set_parameter( wp_kses_post(sprintf( __( 'Styling: <strong>%s</strong>', 'auto-moto-stock' ), implode( ', ', $stylings ) ))  );
			}
			return $tax_query;
		}

        public function get_meta_query_car_search_ajax($meta_query) {
            $meta_query = apply_filters('amotos_car_search_ajax_meta_query_args',$meta_query);
            return $meta_query;
        }

        public function get_meta_query_advanced_search($meta_query) {
            $meta_query = apply_filters('amotos_amotos_advanced_search_meta_query_args',$meta_query);
            return $meta_query;
        }

        public function get_tax_query_car_search_ajax($tax_query) {
            $tax_query = apply_filters('amotos_car_search_ajax_tax_query_args',$tax_query);
            return $tax_query;
        }
	}
	AMOTOS_Query::get_instance()->init();
}