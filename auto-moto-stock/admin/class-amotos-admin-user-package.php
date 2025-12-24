<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    if (! class_exists('AMOTOS_Admin_User_Package')) {
        /**
         * Class AMOTOS_Admin_User_Package
         */
        class AMOTOS_Admin_User_Package
        {
            /**
             * Register custom columns
             *
             * @param $columns
             *
             * @return array
             */
            public function register_custom_column_titles($columns)
            {
                $columns[ 'cb' ]            = "<input type=\"checkbox\" />";
                $columns[ 'title' ]         = esc_html__('Title', 'auto-moto-stock');
                $columns[ 'user_id' ]       = esc_html__('Buyer', 'auto-moto-stock');
                $columns[ 'package' ]       = esc_html__('Package', 'auto-moto-stock');
                $columns[ 'num_listings' ]  = esc_html__('Number Listings', 'auto-moto-stock');
                $columns[ 'num_featured' ]  = esc_html__('Number Featured', 'auto-moto-stock');
                $columns[ 'activate_date' ] = esc_html__('Activate Date', 'auto-moto-stock');
                $columns[ 'expire_date' ]   = esc_html__('Expiry Date', 'auto-moto-stock');
                $new_columns                = [];
                $custom_order               = [
                    'cb',
                    'title',
                    'user_id',
                    'package',
                    'num_listings',
                    'num_featured',
                    'activate_date',
                    'expire_date',
                ];
                foreach ($custom_order as $colname) {
                    $new_columns[ $colname ] = $columns[ $colname ];
                }

                return $new_columns;
            }

            /**
             * Display custom column for manager package
             *
             * @param $column
             */
            public function display_custom_column($column)
            {
                global $post;
                $postID                              = $post->ID;
                $package_user_id                     = get_post_meta($postID, AMOTOS_METABOX_PREFIX . 'package_user_id', true);
                $package_id                          = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_id', true);
                $package_available_listings          = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true);
                $package_featured_available_listings = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true);
                $package_activate_date               = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_activate_date', true);
                $package_name                        = get_the_title($package_id);
                $user_info                           = get_userdata($package_user_id);
                $amotos_package                         = new AMOTOS_Package();
                $expired_date                        = $amotos_package->get_expired_date($package_id, $package_user_id);
                switch ($column) {
                    case 'user_id':
                        if ($user_info) {
                            echo esc_html($user_info->display_name);
                        }
                        break;

                    case 'package':
                        echo esc_html($package_name);
                        break;

                    case 'num_listings':
                        if ($package_available_listings == -1) {
                            esc_html_e('Unlimited', 'auto-moto-stock');
                        } else {
                            echo esc_html($package_available_listings);
                        }
                        break;
                    case 'num_featured':
                        echo esc_html($package_featured_available_listings);
                        break;

                    case 'activate_date':
                        echo esc_html($package_activate_date);
                        break;

                    case 'expire_date':
                        echo esc_html($expired_date);
                        break;
                }
            }

            /**
             * Modify manager package slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_user_package_slug($existing_slug)
            {
                $user_package_url_slug = amotos_get_option('user_package_url_slug');
                if ($user_package_url_slug) {
                    return $user_package_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Filter restrict manage user package
             */
            public function filter_restrict_manage_user_package()
            {
                global $typenow;
                $post_type    = 'user_package';
                $package_user = isset($_GET[ 'package_user' ]) ? amotos_clean(wp_unslash($_GET[ 'package_user' ])) : '';
                if ($typenow == $post_type) {
                ?>
				<input type="text" placeholder="<?php esc_attr_e('Buyer', 'auto-moto-stock'); ?>"
				       name="package_user" value="<?php echo esc_attr($package_user); ?>">
			<?php }
                        }

                        /**
                         * User package filter
                         *
                         * @param $query
                         */
                        public function user_package_filter($query)
                        {
                            global $pagenow;
                            $post_type  = 'user_package';
                            $q_vars     = &$query->query_vars;
                            $filter_arr = [];
                            if ($pagenow == 'edit.php' && isset($q_vars[ 'post_type' ]) && $q_vars[ 'post_type' ] == $post_type) {
                                $package_user = isset($_GET[ 'package_user' ]) ? amotos_clean(wp_unslash($_GET[ 'package_user' ])) : '';
                                if ($package_user !== '') {
                                    $user    = get_user_by('login', $package_user);
                                    $user_id = -1;
                                    if ($user) {
                                        $user_id = $user->ID;
                                    }
                                    $filter_arr[  ] = [
                                        'key'     => AMOTOS_METABOX_PREFIX . 'package_user_id',
                                        'value'   => $user_id,
                                        'compare' => 'IN',
                                    ];
                                }
                                if (! empty($filter_arr)) {
                                    $q_vars[ 'meta_query' ] = $filter_arr;
                                }
                            }
                        }

                        /**
                         * @param $actions
                         * @param $post
                         *
                         * @return mixed
                         */
                        public function modify_list_row_actions($actions, $post)
                        {
                            // Check for your post type.
                            if ($post->post_type == 'user_package') {
                                unset($actions[ 'view' ]);
                            }

                            return $actions;
                        }

                        public function delete_user_package($post_ID)
                        {
                            $user_id = get_post_meta($post_ID, AMOTOS_METABOX_PREFIX . 'package_user_id', true);
                            delete_post_meta($post_ID, AMOTOS_METABOX_PREFIX . 'package_user_id');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_number_featured');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_activate_date');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_id');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'package_key');
                            delete_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'free_package');
                        }
                }
            }