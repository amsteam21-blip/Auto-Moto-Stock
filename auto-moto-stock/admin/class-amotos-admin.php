<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    if (! class_exists('AMOTOS_Admin')) {
        /**
         * Class AMOTOS_Admin
         */
        class AMOTOS_Admin
        {
            /**
             * Check if it is a vehicle edit page.
             * @return bool
             */
            public function is_amotos_admin()
            {
                if (is_admin()) {
                    global $pagenow;
                    if (in_array($pagenow, ['edit.php', 'post.php', 'post-new.php', 'edit-tags.php'])) {
                        global $post_type;
                        if (('car' == $post_type) || ('manager' == $post_type) || ('package' == $post_type) || ('user_package' == $post_type) || ('invoice' == $post_type)) {
                            return true;
                        }
                    }

                    $_page = isset($_GET[ 'page' ]) ? amotos_clean(wp_unslash($_GET[ 'page' ])) : '';
                    if (($pagenow === 'admin.php') && ($_page === 'amotos_options')) {
                        return true;
                    }
                }

                return false;
            }

            /**
             * Register the stylesheets for the admin area.
             */
            public function enqueue_styles()
            {
                $min_suffix = amotos_get_option('enable_min_css', 0) == 1 ? '.min' : '';
                wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'admin-css', AMOTOS_PLUGIN_URL . 'admin/assets/css/admin' . $min_suffix . '.css', [], AMOTOS_PLUGIN_VER, 'all');
                $_page = isset($_GET[ 'page' ]) ? amotos_clean(wp_unslash($_GET[ 'page' ])) : '';
                if ((($_page == 'amotos_setup') || ($_page == 'amotos_welcome'))) {
                    wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'setup_css', AMOTOS_PLUGIN_URL . 'admin/assets/css/setup' . $min_suffix . '.css', ['dashicons'], AMOTOS_PLUGIN_VER, 'all');
                }
                if ($this->is_amotos_admin()) {
                    $enable_filter_location = amotos_get_option('enable_filter_location', 0);
                    if ($enable_filter_location == 1) {
                        //select2
                        wp_enqueue_style('select2', AMOTOS_PLUGIN_URL . 'public/assets/packages/select2/css/select2.min.css', [], '4.0.6-rc.1', 'all');
                    }
                }
            }

            /**
             * Register the JavaScript for the admin area.
             */
            public function enqueue_scripts()
            {
                $min_suffix = amotos_get_option('enable_min_js', 0) == 1 ? '.min' : '';
                wp_enqueue_script('jquery-tipTip', AMOTOS_PLUGIN_URL . 'admin/assets/js/jquery-tiptip/jquery.tipTip' . $min_suffix . '.js', [
                    'jquery',
                    'jquery-ui-sortable',
                ], '1.3', true);
                if ($this->is_amotos_admin()) {
                    $enable_filter_location = amotos_get_option('enable_filter_location', 0);
                    if ($enable_filter_location == 1) {
                        wp_enqueue_script('select2', AMOTOS_PLUGIN_URL . 'public/assets/packages/select2/js/select2.full.min.js', ['jquery'], '4.0.6-rc.1', true);
                    }
                    wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'admin-js', AMOTOS_PLUGIN_URL . 'admin/assets/js/amotos-admin' . $min_suffix . '.js', ['jquery'], AMOTOS_PLUGIN_VER, true);
                    wp_localize_script(AMOTOS_PLUGIN_PREFIX . 'admin-js', 'amotos_admin_vars',
                        [
                            'ajax_url'               => AMOTOS_AJAX_URL,
                            'enable_filter_location' => $enable_filter_location,
                        ]
                    );
                }
            }

            /**
             * Get default directory image
             *
             * @param $args
             *
             * @return array
             */
            public function image_nearby_places_default_dir($args)
            {
                return [
                    'url' => AMOTOS_PLUGIN_URL . 'admin/assets/images/nearby-places/',
                    'dir' => AMOTOS_PLUGIN_DIR . 'admin/assets/images/nearby-places/',
                ];
            }

            public function image_car_makers_default_dir($args)
            {
                return [
                    'url' => AMOTOS_PLUGIN_URL . 'admin/assets/images/car-makers-svg/',
                    'dir' => AMOTOS_PLUGIN_DIR . 'admin/assets/images/car-makers-svg/',
                ];
            }

            public function image_car_types_default_dir($args)
            {
                return [
                    'url' => AMOTOS_PLUGIN_URL . 'admin/assets/images/car-types-png/',
                    'dir' => AMOTOS_PLUGIN_DIR . 'admin/assets/images/car-types-png/',
                ];
            }

            /**
             * Get user package capabilities
             * @return mixed
             */
            private function get_user_package_capabilities()
            {
                $caps = [
                    'create_posts' => 'do_not_allow',
                    'edit_post'    => 'edit_user_packages',
                    'delete_posts' => 'delete_user_packages',
                ];

                return apply_filters('get_user_package_capabilities', $caps);
            }

            /**
             * Get invoice capabilities
             * @return mixed
             */
            private function get_invoice_capabilities()
            {
                $caps = [
                    'create_posts' => 'do_not_allow',
                    'edit_post'    => 'edit_invoices',
                    'delete_posts' => 'delete_invoices',
                ];

                return apply_filters('get_invoice_capabilities', $caps);
            }

            /**
             * Get transaction capabalities
             */
            private function get_trans_action_capabilities()
            {
                $caps = [
                    'create_posts' => 'do_not_allow',
                    'edit_post'    => 'edit_trans_actions',
                    'delete_posts' => 'delete_trans_actions',
                ];

                return apply_filters('get_trans_action_capabilities', $caps);
            }

            /**
             * Register vehicle post status
             */
            public function register_post_status()
            {
                register_post_status('expired', [
                    'label'                     => _x('Expired', 'post status', 'auto-moto-stock'),
                    'public'                    => true,
                    'protected'                 => true,
                    'exclude_from_search'       => true,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    /* translators: %s: number of post expired */
                    'label_count'               => _n_noop('Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'auto-moto-stock'),
                ]);
                register_post_status('hidden', [
                    'label'                     => _x('Hidden', 'post status', 'auto-moto-stock'),
                    'public'                    => true,
                    'protected'                 => true,
                    'exclude_from_search'       => true,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    /* translators: %s: number of post hidden */
                    'label_count'               => _n_noop('Hidden <span class="count">(%s)</span>', 'Hidden <span class="count">(%s)</span>', 'auto-moto-stock'),
                ]);
            }

            /**
             * Register post_type
             *
             * @param $post_types
             *
             * @return mixed
             */
            public function register_post_type($post_types)
            {
                $post_types[ 'car' ] = apply_filters('amotos_register_post_type_car', [
                    'label'           => esc_html__('AMS Vehicles', 'auto-moto-stock'),
                    'singular_name'   => esc_html__('AMS Vehicles ', 'auto-moto-stock'),
                    'rewrite'         => [
                        'slug' => apply_filters('amotos_car_slug', 'car'),
                    ],
                    'supports'        => [
                        'title',
                        'editor',
                        'author',
                        'thumbnail',
                        'revisions',
                        'page-attributes',
                        'comments',
                    ],
                    'menu_icon'       => plugins_url('admin/assets/images/admin-icon.png', AMOTOS_PLUGIN_FILE),
                    'can_export'      => true,
                    'capability_type' => 'car',
                    'map_meta_cap'    => true,
                ]);
                $post_types[ 'location' ] = apply_filters('amotos_register_post_type_location', [
                    'label'               => esc_html__('AMS Location', 'auto-moto-stock'),
                    'singular_name'       => esc_html__('AMS Location', 'auto-moto-stock'),
                    'rewrite'             => [
                        'slug' => apply_filters('amotos_location_slug', 'location'),
                    ],
                    'supports'            => ['title', 'excerpt'],
                    'menu_icon'           => 'dashicons-location',
                    'can_export'          => true,
                    'capability_type'     => 'location',
                    'map_meta_cap'        => true,
                    'exclude_from_search' => true,
                    'public'              => false,
                ]);
                $post_types[ 'manager' ] = apply_filters('amotos_register_post_type_manager', [
                    'label'           => esc_html__('AMS Staff', 'auto-moto-stock'),
                    'singular_name'   => esc_html__('AMS Manager', 'auto-moto-stock'),
                    'rewrite'         => [
                        'slug' => apply_filters('amotos_manager_slug', 'manager'),
                    ],
                    'supports'        => [
                        'title',
                        'editor',
                        'thumbnail',
                        'page-attributes',
                        'revisions',
                        'comments',
                    ],
                    'menu_icon'       => 'dashicons-groups',
                    'can_export'      => true,
                    'capability_type' => 'manager',
                    'map_meta_cap'    => true,
                ]);
                $post_types[ 'package' ] = apply_filters('amotos_register_post_type_package', [
                    'label'               => esc_html__('AMS Packages', 'auto-moto-stock'),
                    'singular_name'       => esc_html__('AMS Package', 'auto-moto-stock'),
                    'rewrite'             => [
                        'slug' => apply_filters('amotos_package_slug', 'package'),
                    ],
                    'supports'            => ['title'],
                    'menu_icon'           => 'dashicons-editor-table',
                    'capability_type'     => 'package',
                    'map_meta_cap'        => true,
                    'exclude_from_search' => true,
                    'public'              => false,
                ]);
                $post_types[ 'user_package' ] = apply_filters('amotos_register_post_type_user_package', [
                    'label'               => esc_html__('AMS User Pack', 'auto-moto-stock'),
                    'singular_name'       => esc_html__('AMS User Pack', 'auto-moto-stock'),
                    'rewrite'             => [
                        'slug' => apply_filters('amotos_user_package_slug', 'user_package'),
                    ],
                    'supports'            => ['title', 'excerpt'],
                    'menu_icon'           => 'dashicons-money',
                    'can_export'          => true,
                    //'capabilities'        => $this->get_user_package_capabilities(),
                    'capability_type'     => 'user_package',
                    'map_meta_cap'        => true,
                    'exclude_from_search' => true,
                    'public'              => false,
                ]);
                $post_types[ 'invoice' ] = apply_filters('amotos_register_post_type_invoice', [
                    'label'               => esc_html__('AMS Invoices', 'auto-moto-stock'),
                    'singular_name'       => esc_html__('AMS Invoice', 'auto-moto-stock'),
                    'rewrite'             => [
                        'slug' => apply_filters('amotos_invoice_slug', 'invoice'),
                    ],
                    'supports'            => ['title', 'excerpt'],
                    'menu_icon'           => 'dashicons-list-view',
                    //'capabilities'        => $this->get_invoice_capabilities(),
                    'capability_type'     => 'invoice',
                    'map_meta_cap'        => true,
                    'exclude_from_search' => true,
                    'public'              => false,
                ]);
                $post_types[ 'trans_action' ] = apply_filters('amotos_register_post_type_trans_action', [
                    'label'               => esc_html__('AMS Transaction', 'auto-moto-stock'),
                    'singular_name'       => esc_html__('AMS Transaction', 'auto-moto-stock'),
                    'rewrite'             => [
                        'slug' => apply_filters('amotos_trans_action_slug', 'trans_action'),
                    ],
                    'supports'            => ['title', 'excerpt'],
                    'menu_icon'           => 'dashicons-text-page',
                    'can_export'          => true,
                    //'capabilities'        => $this->get_trans_action_capabilities(),
                    'capability_type'     => 'trans_action',
                    'map_meta_cap'        => true,
                    'exclude_from_search' => true,
                    'public'              => false,
                ]);

                return apply_filters('amotos_register_post_type', $post_types);
            }

            /**
             * Additional Fields
             */
            public function additional_details_field($meta_prefix)
            {
                if (! class_exists('SQUICK_QuickFramework')) {
                    return [
                        'id' => "{$meta_prefix}additional_stylings",
                        'title'    => esc_html__('Additional Fields:', 'auto-moto-stock'),
                        'type'     => 'custom',
                        'default'  => [],
                        'template' => AMOTOS_PLUGIN_DIR . '/admin/templates/additional-details-field.php',
                    ];
                }

                return [
                    'id' => "{$meta_prefix}additional_stylings",
                    'type'    => 'repeater',
                    'title'   => esc_html__('Additional Fields:', 'auto-moto-stock'),
                    'col'     => '6',
                    'sort'    => true,
                    'fields'  => [
                        [
                            'id' => "{$meta_prefix}additional_styling_title",
                            'title'   => esc_html__('Title:', 'auto-moto-stock'),
                            'desc'    => esc_html__('Enter title', 'auto-moto-stock'),
                            'type'    => 'text',
                            'default' => '',
                            'col'     => '5',
                        ],
                        [
                            'id' => "{$meta_prefix}additional_styling_value",
                            'title'   => esc_html__('Value', 'auto-moto-stock'),
                            'desc'    => esc_html__('Enter value', 'auto-moto-stock'),
                            'type'    => 'text',
                            'default' => '',
                            'col'     => '7',
                        ],
                    ],
                ];

            }

            /**
             * Register meta boxes
             *
             * @param $configs
             *
             * @return mixed
             */
            public function register_meta_boxes($configs)
            {
                $meta_prefix               = AMOTOS_METABOX_PREFIX;
                $measurement_units_mileage = amotos_get_measurement_units_mileage();
                $measurement_units_power   = amotos_get_measurement_units_power();
                $measurement_units_volume  = amotos_get_measurement_units_volume();
                $dec_point                 = amotos_get_price_decimal_separator();
                $format_number             = '^[0-9]+([' . $dec_point . '][0-9]+)?$';
                $price_unit                = [];
                $enable_price_unit         = amotos_get_option('enable_price_unit', '1');
                $price_short_col           = '6';
                if ($enable_price_unit == '1') {
                    $price_short_col = '3';
                    $price_unit      = [
                        'id' => "{$meta_prefix}car_price_unit",
                        'title'    => esc_html__('Price Unit', 'auto-moto-stock'),
                        'type'     => 'button_set',
                        'options'  => [
                            '1'       => esc_html__('None', 'auto-moto-stock'),
                            '1000'    => esc_html__('Thousand', 'auto-moto-stock'),
                            '1000000' => esc_html__('Million', 'auto-moto-stock'),
                        ],
                        'default'  => '1',
                        'col'      => '9',
                        'required' => ["{$meta_prefix}car_price_on_call", '=', '0'],
                    ];
                }

                $render_additional_fields = amotos_render_additional_fields();
                $additional_fields        = [];
                if (count($render_additional_fields) > 0) {
                    $additional_fields = [
                        [
                            'id' => "{$meta_prefix}additional_fields_tab",
                            'title'  => esc_html__('Additional Fields', 'auto-moto-stock'),
                            'icon'   => 'dashicons dashicons-welcome-add-page',
                            'fields' => $render_additional_fields,
                        ],
                    ];
                }

                /**
                 * Meta Boxes Config
                 */
                $configs[ 'car_meta_boxes' ] = apply_filters('amotos_register_meta_boxes_car', [
                    'name'      => esc_html__('Vehicle Information', 'auto-moto-stock'),
                    'post_type' => ['car'],
                    'layout'    => 'full',
                    'section'   => array_merge(
                        apply_filters('amotos_register_meta_boxes_car_top', []),
                        apply_filters('amotos_register_meta_boxes_car_main',
                            array_merge(
                                // Basic Information
                                [
                                    [
                                        'id' => "{$meta_prefix}basic_tab",
                                        'title'  => esc_html__('Basic Info', 'auto-moto-stock'),
                                        'icon'   => 'dashicons dashicons-dashboard',
                                        'fields' => [
                                            [
                                                'type'         => 'row',
                                                'col'          => '6',
                                                'fields'       => [
                                                    [
                                                        'id' => "{$meta_prefix}car_price_short",
                                                        'title'   => esc_html__('Price', 'auto-moto-stock'),
                                                        /* translators: %s: decimal point*/
                                                        'desc'    => sprintf(esc_html__('Example: 1745%s25', 'auto-moto-stock'), $dec_point),
                                                        'type'    => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'format_value' => 'amotos_format_localized_price',
                                                        'default'      => '',
                                                        'col'          => $price_short_col,
                                                        'required'     => [
                                                            "{$meta_prefix}car_price_on_call",
                                                            '=',
                                                            '0',
                                                        ],
                                                    ],
                                                    $price_unit,
                                                ],
                                            ],
                                            [
                                                'type'   => 'row',
                                                'col'    => '4',
                                                'fields' => [
                                                    [
                                                        'id' => "{$meta_prefix}car_price_prefix",
                                                        'title'    => esc_html__('Before Price Label', 'auto-moto-stock'),
                                                        'desc'     => esc_html__('Example: Start From', 'auto-moto-stock'),
                                                        'type'     => 'text',
                                                        'default'  => '',
                                                        'required' => [
                                                            "{$meta_prefix}car_price_on_call",
                                                            '=',
                                                            '0',
                                                        ],
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_price_postfix",
                                                        'title'    => esc_html__('After Price Label', 'auto-moto-stock'),
                                                        'desc'     => esc_html__('Example: Per Month', 'auto-moto-stock'),
                                                        'type'     => 'text',
                                                        'default'  => '',
                                                        'required' => [
                                                            "{$meta_prefix}car_price_on_call",
                                                            '=',
                                                            '0',
                                                        ],
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_price_on_call",
                                                        'title'     => esc_html__('Price on Call ?', 'auto-moto-stock'),
                                                        'type'      => 'button_set',
                                                        'options'   => [
                                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                                        ],
                                                        'default'   => '0',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type' => 'divide',
                                            ],
                                            [
                                                'type'    => 'row',
                                                'col'     => '4',
                                                'fields'  => [
                                                    [
                                                        'id' => "{$meta_prefix}car_year",
                                                        'title'   => esc_html__('Year Vehicle', 'auto-moto-stock'),
                                                        'type'    => 'text',
                                                        'default' => '',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_owners",
                                                        'title'   => esc_html__('Owners', 'auto-moto-stock'),
                                                        'desc'    => esc_html__('Example Value: 2', 'auto-moto-stock'),
                                                        'type'    => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_identity",
                                                        'title' => esc_html__('Vehicle ID', 'auto-moto-stock'),
                                                        'desc' => esc_html__('Vehicle ID will help to search vehicle directly (default=postId)', 'auto-moto-stock'),
                                                        'type' => 'text',
                                                        'default' => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type' => 'divide',
                                            ],
                                            $this->additional_details_field($meta_prefix),
                                            [
                                                'type' => 'divide',
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_featured",
                                                'title' => esc_html__('Mark as featured?', 'auto-moto-stock'),
                                                'type' => 'button_set',
                                                'options' => [
                                                    '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                    '0' => esc_html__('No', 'auto-moto-stock'),
                                                ],
                                                'default' => '0',
                                            ],
                                        ],
                                    ],
                                ],

                                // Technical Data
                                [
                                    [
                                        'id' => "{$meta_prefix}technical_tab",
                                        'title'   => esc_html__('Technical Data', 'auto-moto-stock'),
                                        'icon'    => 'dashicons dashicons-admin-generic',
                                        'fields'  => [
                                            [
                                                'type'    => 'row',
                                                'col'     => '4',
                                                'fields'  => [
                                                    [
                                                        'id' => "{$meta_prefix}car_mileage",
                                                        /* translators: %s: measurement units mileage */
                                                        'title'   => wp_kses_post(sprintf(__('Mileage (%s)', 'auto-moto-stock'), $measurement_units_mileage)),
                                                        'desc'    => esc_html__('Example Value: 200', 'auto-moto-stock'),
                                                        'type'    => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_power",
                                                        /* translators: %s: measurement units power */
                                                        'title'   => wp_kses_post(sprintf(__('Power (%s)', 'auto-moto-stock'), $measurement_units_power)),
                                                        'desc'    => esc_html__('Example Value: 2000', 'auto-moto-stock'),
                                                        'type'    => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_volume",
                                                        /* translators: %s: measurement units volume */
                                                        'title' => wp_kses_post(sprintf(__('Cubic Capacity (%s)', 'auto-moto-stock'), $measurement_units_volume)),
                                                        'desc' => esc_html__('Example Value: 2000', 'auto-moto-stock'),
                                                        'type' => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type'    => 'row',
                                                'col'     => '6',
                                                'fields'  => [
                                                    [
                                                        'id' => "{$meta_prefix}car_doors",
                                                        'title'   => esc_html__('Doors', 'auto-moto-stock'),
                                                        'desc'    => esc_html__('Example Value: 6', 'auto-moto-stock'),
                                                        'type'    => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_seats",
                                                        'title' => esc_html__('Seats', 'auto-moto-stock'),
                                                        'desc' => esc_html__('Example Value: 4', 'auto-moto-stock'),
                                                        'type' => 'text',
                                                        'pattern' => "{$format_number}",
                                                        'default' => '',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type' => 'divide',
                                            ],
                                        ],
                                    ],
                                ],
                                $additional_fields,
                                [
                                    "{$meta_prefix}location_tab" => [
                                        'id' => "{$meta_prefix}location_tab",
                                        'title' => esc_html__('Location', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-location',
                                        'fields' => [
                                            [
                                                'type'   => 'row',
                                                'col'    => '6',
                                                'fields' => [
                                                    [
                                                        'id' => "{$meta_prefix}car_address",
                                                        'title' => esc_html__('Vehicle Address', 'auto-moto-stock'),
                                                        'desc'  => esc_html__('Full Address', 'auto-moto-stock'),
                                                        'type'  => 'text',
                                                    ],
                                                    [
                                                        'id' => "{$meta_prefix}car_zip",
                                                        'title'  => esc_html__('Zip', 'auto-moto-stock'),
                                                        'type'   => 'text',
                                                    ],
                                                ],
                                            ],
                                            "{$meta_prefix}car_location" => [
                                                'id' => "{$meta_prefix}car_location",
                                                'title' => esc_html__('Vehicle Location at Google Map', 'auto-moto-stock'),
                                                'desc' => esc_html__('Drag the google map marker to point your vehicle location. You can also use the address field above to search for your vehicle', 'auto-moto-stock'),
                                                'type' => 'map',
                                                'js_options' => [
                                                    'zoom' => absint(amotos_get_option('googlemap_zoom_level', 8)),
                                                ],
                                                'default' => amotos_get_option('googlemap_coordinate_default', '37.773972, -122.431297'),
                                                'address_field' => "{$meta_prefix}car_address",
                                            ],
                                        ],
                                    ],

                                    [
                                        'id' => "{$meta_prefix}gallery_tab",
                                        'title' => esc_html__('Gallery Images', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-format-gallery',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}car_images",
                                                'title' => esc_html__('Vehicle Gallery Images', 'auto-moto-stock'),
                                                'type'  => 'gallery',
                                            ],
                                        ],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}documents_tab",
                                        'title' => esc_html__('File Attachments', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-media-default',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}car_attachments",
                                                'title' => esc_html__('File Attachments', 'auto-moto-stock'),
                                                'type'  => 'file',
                                            ],
                                        ],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}video_tab",
                                        'title' => esc_html__('Vehicle Video', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-video-alt3',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}car_video_url",
                                                'title' => esc_html__('Video URL', 'auto-moto-stock'),
                                                'desc'  => esc_html__('Input only URL. YouTube, Vimeo', 'auto-moto-stock'),
                                                'type'  => 'text',
                                                'col'   => 12,
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_video_image",
                                                'title' => esc_html__('Video Image', 'auto-moto-stock'),
                                                'type' => 'gallery',
                                                'col' => 12,
                                            ],
                                        ],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}virtual_360_tab",
                                        'title' => esc_html__('Virtual 360', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-format-image',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}car_virtual_360_type",
                                                'title'   => esc_html__('Virtual 360', 'auto-moto-stock'),
                                                'type'    => 'button_set',
                                                'options' => [
                                                    '1' => esc_html__('Embeded code', 'auto-moto-stock'),
                                                    '0' => esc_html__('Upload image', 'auto-moto-stock'),
                                                ],
                                                'default' => '0',
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_virtual_360",
                                                'title' => esc_html__('Enter virtual 360 embeded code', 'auto-moto-stock'),
                                                'type' => 'textarea',
                                                'default' => '',
                                                'required' => ["{$meta_prefix}car_virtual_360_type", '=', '1'],
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_image_360",
                                                'title' => esc_html__('Vehicle Image 360', 'auto-moto-stock'),
                                                'type' => 'image',
                                                'required' => ["{$meta_prefix}car_virtual_360_type", '=', '0'],
                                            ],
                                        ],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}manager_tab",
                                        'title' => esc_html__('Manager', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-admin-users',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}manager_display_option",
                                                'title'   => esc_html__('What to display in contact information box ?', 'auto-moto-stock'),
                                                'type'    => 'radio',
                                                'options' => [
                                                    'author_info'  => esc_html__('Author information', 'auto-moto-stock'),
                                                    'manager_info' => esc_html__('Manager Information. (Select the manager below)', 'auto-moto-stock'),
                                                    'other_info'   => esc_html__('Other contact', 'auto-moto-stock'),
                                                    'no'           => esc_html__('Hide contact information', 'auto-moto-stock'),
                                                ],
                                                'default' => 'manager_info',
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_manager",
                                                'title' => esc_html__('Manager:', 'auto-moto-stock'),
                                                'type' => 'selectize',
                                                'multiple' => false,
                                                'data' => 'manager',
                                                'data_args' => [
                                                    'numberposts' => -1,
                                                ],
                                                'required' => [
                                                    "{$meta_prefix}manager_display_option",
                                                    '=',
                                                    'manager_info',
                                                ],
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_other_contact_name",
                                                'title' => esc_html__('Other contact Name', 'auto-moto-stock'),
                                                'type' => 'text',
                                                'default' => '',
                                                'required' => [
                                                    "{$meta_prefix}manager_display_option",
                                                    '=',
                                                    'other_info',
                                                ],
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_other_contact_mail",
                                                'title' => esc_html__('Other contact Email', 'auto-moto-stock'),
                                                'type' => 'text',
                                                'default' => '',
                                                'required' => [
                                                    "{$meta_prefix}manager_display_option",
                                                    '=',
                                                    'other_info',
                                                ],
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_other_contact_phone",
                                                'title' => esc_html__('Other contact Phone', 'auto-moto-stock'),
                                                'type' => 'text',
                                                'default' => '',
                                                'required' => [
                                                    "{$meta_prefix}manager_display_option",
                                                    '=',
                                                    'other_info',
                                                ],
                                            ],
                                            [
                                                'id' => "{$meta_prefix}car_other_contact_description",
                                                'title' => esc_html__('Other contact more info', 'auto-moto-stock'),
                                                'type' => 'textarea',
                                                'default' => '',
                                                'required' => [
                                                    "{$meta_prefix}manager_display_option",
                                                    '=',
                                                    'other_info',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}private_note_tab",
                                        'title' => esc_html__('Private Note', 'auto-moto-stock'),
                                        'icon' => 'dashicons dashicons-testimonial',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}private_note",
                                                'title' => esc_html__('Private Note', 'auto-moto-stock'),
                                                'desc'  => esc_html__('Create a private note for this vehicle, it will not be displayed to public', 'auto-moto-stock'),
                                                'type'  => 'textarea',
                                            ],
                                        ],
                                    ],
                                ]
                            )
                        ),
                        apply_filters('amotos_register_meta_boxes_car_bottom', [])
                    ),
                ]);
                $configs[ 'manager_meta_boxes' ] = apply_filters('amotos_register_meta_boxes_manager', [
                    'name'      => esc_html__('Manager Information', 'auto-moto-stock'),
                    'post_type' => ['manager'],
                    'layout'    => 'full',
                    'section'   => array_merge(
                        apply_filters('amotos_register_meta_boxes_manager_top', []),
                        apply_filters('amotos_register_meta_boxes_manager_main', [
                            [
                                'id' => "{$meta_prefix}manager_general_tab",
                                'title'  => esc_html__('Basic Infomation', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-businessman',
                                'fields' => [
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'id' => "{$meta_prefix}manager_description",
                                                'title' => esc_html__('Description', 'auto-moto-stock'),
                                                'type'  => 'editor',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'       => 'row',
                                        'col'        => '6',
                                        'fields'     => [
                                            [
                                                'id' => "{$meta_prefix}manager_position",
                                                'title' => esc_html__('Position', 'auto-moto-stock'),
                                                'type'  => 'text',
                                            ],

                                            [
                                                'id' => "{$meta_prefix}manager_email",
                                                'title'      => esc_html__('Email', 'auto-moto-stock'),
                                                'type'       => 'text',
                                                'input_type' => 'email',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => esc_html__('Mobile Number', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_mobile_number",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => esc_html__('Fax Number', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_fax_number",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Company Name', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_company",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Office Number', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_office_number",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Office Address', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_office_address",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Website', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_website_url",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Licenses', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_licenses",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Skype', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_skype",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'id' => "{$meta_prefix}manager_social_profiles_tab",
                                'title' => esc_html__('Social Profiles', 'auto-moto-stock'),
                                'icon' => 'dashicons dashicons-share',
                                'fields' => [
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Facebook URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_facebook_url",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Twitter URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_twitter_url",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],

                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Pinterest URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_pinterest_url",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Instagram URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_instagram_url",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Vimeo URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_vimeo_url",
                                                'type' => 'text',
                                            ],
                                            [
                                                'title' => __('Youtube URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_youtube_url",
                                                'type'   => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('LinkedIn URL', 'auto-moto-stock'),
                                                'id'    => "{$meta_prefix}manager_linkedin_url",
                                                'type' => 'text',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                        ),
                        apply_filters('amotos_register_meta_boxes_manager_bottom', [])
                    ),
                ]);
                $configs[ 'package_meta_boxes' ] = apply_filters('amotos_register_meta_boxes_package', [
                    'name'      => esc_html__('Package Setting', 'auto-moto-stock'),
                    'post_type' => ['package'],
                    'layout'    => 'full',
                    'fields'    => array_merge(
                        apply_filters('amotos_register_meta_boxes_package_top', []),
                        apply_filters('amotos_register_meta_boxes_package_main', [
                            [
                                'type'     => 'row',
                                'col'      => '4',
                                'fields'   => [
                                    [
                                        'id' => "{$meta_prefix}package_free",
                                        'title'   => esc_html__('Free package', 'auto-moto-stock'),
                                        'type'    => 'button_set',
                                        'options' => [
                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                        ],
                                        'default' => '0',
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_price",
                                        'title'    => esc_html__('Package Price', 'auto-moto-stock'),
                                        'type'     => 'text',
                                        'required' => ["{$meta_prefix}package_free", '=', '0'],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'divide',
                            ],
                            [
                                'type'     => 'row',
                                'col'      => '4',
                                'fields'   => [
                                    [
                                        'id' => "{$meta_prefix}package_unlimited_time",
                                        'title'   => esc_html__('Unlimited time', 'auto-moto-stock'),
                                        'type'    => 'button_set',
                                        'options' => [
                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                        ],
                                        'default' => '0',
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_time_unit",
                                        'title'    => esc_html__('Time Unit', 'auto-moto-stock'),
                                        'type'     => 'button_set',
                                        'options'  => [
                                            'Day'   => esc_html__('Day', 'auto-moto-stock'),
                                            'Week'  => esc_html__('Week', 'auto-moto-stock'),
                                            'Month' => esc_html__('Month', 'auto-moto-stock'),
                                            'Year'  => esc_html__('Year', 'auto-moto-stock'),
                                        ],
                                        'default'  => 'Day',
                                        'required' => ["{$meta_prefix}package_unlimited_time", '=', '0'],
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_period",
                                        'title'    => esc_html__('Number Of "Time Unit"', 'auto-moto-stock'),
                                        'type'     => 'text',
                                        'default'  => '1',
                                        'pattern'  => '[0-9]*',
                                        'required' => ["{$meta_prefix}package_unlimited_time", '=', '0'],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'divide',
                            ],
                            [
                                'type'     => 'row',
                                'col'      => '4',
                                'fields'   => [
                                    [
                                        'id' => "{$meta_prefix}package_unlimited_listing",
                                        'title'   => esc_html__('Unlimited listings', 'auto-moto-stock'),
                                        'type'    => 'button_set',
                                        'options' => [
                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                        ],
                                        'default' => '0',
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_number_listings",
                                        'title'    => esc_html__('Number Listings', 'auto-moto-stock'),
                                        'type'     => 'text',
                                        'default'  => '',
                                        'pattern'  => '[0-9]*',
                                        'required' => ["{$meta_prefix}package_unlimited_listing", '=', '0'],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'divide',
                            ],
                            [
                                'type'    => 'row',
                                'col'     => '4',
                                'fields'  => [
                                    [
                                        'id' => "{$meta_prefix}package_number_featured",
                                        'title'   => esc_html__('Number Featured Listings', 'auto-moto-stock'),
                                        'type'    => 'text',
                                        'default' => '',
                                        'pattern' => '[0-9]*',
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_order_display",
                                        'title'   => esc_html__('Order Number Display Via Frontend', 'auto-moto-stock'),
                                        'type'    => 'text',
                                        'default' => '1',
                                        'pattern' => '[0-9]*',
                                    ],
                                ],
                            ],
                            [
                                'type'    => 'row',
                                'col'     => '4',
                                'fields'  => [
                                    [
                                        'id' => "{$meta_prefix}package_featured",
                                        'title'   => esc_html__('Is Featured?', 'auto-moto-stock'),
                                        'type'    => 'button_set',
                                        'options' => [
                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                        ],
                                        'default' => '0',
                                    ],
                                    [
                                        'id' => "{$meta_prefix}package_visible",
                                        'title'   => esc_html__('Is Visible?', 'auto-moto-stock'),
                                        'type'    => 'button_set',
                                        'options' => [
                                            '1' => esc_html__('Yes', 'auto-moto-stock'),
                                            '0' => esc_html__('No', 'auto-moto-stock'),
                                        ],
                                        'default' => '1',
                                    ],
                                ],
                            ],
                        ]),
                        apply_filters('amotos_register_meta_boxes_package_bottom', [])
                    ),
                ]);

                return apply_filters('amotos_register_meta_boxes', $configs);
            }

            /**
             * Register taxonomy
             *
             * @param $taxonomies
             *
             * @return mixed
             */
            public function register_taxonomy($taxonomies)
            {
                $taxonomies[ 'car-type' ] = apply_filters('amotos_register_taxonomy_car_type', [
                    'post_type'     => 'car',
                    'hierarchical'  => true,
                    'label'         => esc_html__('Vehicle Type', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Vehicle Type', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_type_slug', 'car-type'),
                    ],
                ]);
                $taxonomies[ 'car-styling' ] = apply_filters('amotos_register_taxonomy_car_styling', [
                    'post_type'     => 'car',
                    'hierarchical'  => true,
                    'label'         => esc_html__('Styling', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Styling', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_styling_slug', 'car-styling'),
                    ],
                ]);
                $taxonomies[ 'car-status' ] = apply_filters('amotos_register_taxonomy_car_status', [
                    'post_type'     => 'car',
                    'hierarchical'  => true,
                    'label'         => esc_html__('Status', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Status', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_status_slug', 'car-status'),
                    ],
                ]);
                $taxonomies[ 'car-label' ] = apply_filters('amotos_register_taxonomy_car_label', [
                    'post_type'     => 'car',
                    'hierarchical'  => true,
                    'label'         => esc_html__('Label', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Label', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_label_slug', 'car-label'),
                    ],
                ]);
                $taxonomies[ 'car-state' ] = apply_filters('amotos_register_taxonomy_car_state', [
                    'post_type'     => 'car',
                    'show_in_menu'  => false,
                    'hierarchical'  => false,
                    'meta_box_cb'   => [$this, 'taxonomy_select_meta_box'],
                    'label'         => esc_html__('Province/State', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Province/State', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_state_slug', 'car-state'),
                    ],
                ]);
                $taxonomies[ 'car-city' ] = apply_filters('amotos_register_taxonomy_car_city', [
                    'post_type'     => 'car',
                    'hierarchical'  => false,
                    'show_in_menu'  => false,
                    'meta_box_cb'   => [$this, 'taxonomy_select_meta_box'],
                    'label'         => esc_html__('City/Town', 'auto-moto-stock'),
                    'singular_name' => esc_html__('City/Town', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_city_slug', 'car-city'),
                    ],
                ]);
                $taxonomies[ 'car-neighborhood' ] = apply_filters('amotos_register_taxonomy_car_neighborhood', [
                    'post_type'     => 'car',
                    'hierarchical'  => false,
                    'show_in_menu'  => false,
                    'meta_box_cb'   => [$this, 'taxonomy_select_meta_box'],
                    'label'         => esc_html__('Neighborhood', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Neighborhood', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_car_neighborhood_slug', 'car-neighborhood'),
                    ],
                ]);
                $taxonomies[ 'dealer' ] = apply_filters('amotos_register_taxonomy_car_dealer', [
                    'post_type'     => 'manager',
                    'hierarchical'  => true,
                    'label'         => esc_html__('Dealer', 'auto-moto-stock'),
                    'singular_name' => esc_html__('Dealer', 'auto-moto-stock'),
                    'rewrite'       => [
                        'slug' => apply_filters('amotos_dealer_slug', 'dealer'),
                    ],
                ]);

                return apply_filters('amotos_register_taxonomy', $taxonomies);
            }

            /**
             * Remove taxonomy parent category
             */
            public function remove_taxonomy_parent_category()
            {
                $_taxonomy = isset($_GET[ 'taxonomy' ]) ? amotos_clean(wp_unslash($_GET[ 'taxonomy' ])) : '';
                if (! in_array($_taxonomy, ['car-type', 'car-status', 'car-label'])) {
                    return;
                }
                $screen = get_current_screen();

                if ('edit-tags' == $screen->base) {
                    $parent = "$('label[for=parent]').parent()";
                } elseif ('term' == $screen->base) {
                    $parent = "$('label[for=parent]').parent().parent()";
                }
            ?>

			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					<?php echo esc_js($parent); ?>.
					remove();
				});
			</script>

			<?php

                        }

                        /**
                         * Taxonomy select metabox
                         */
                        public function taxonomy_select_meta_box($post, $box)
                        {
                            $defaults = ['taxonomy' => 'category'];

                            if (! isset($box[ 'args' ]) || ! is_array($box[ 'args' ])) {
                                $args = [];
                            } else {
                                $args = $box[ 'args' ];
                            }
                            $taxonomy = '';
                            extract(wp_parse_args($args, $defaults));
                            $tax          = get_taxonomy($taxonomy);
                            $selected     = wp_get_object_terms($post->ID, $taxonomy, ['fields' => 'ids']);
                            $hierarchical = $tax->hierarchical;
                        ?>
			<div id="taxonomy-<?php echo esc_attr($taxonomy); ?>" class="selectdiv amotos-car-select-meta-box-wrap">
				<?php if (current_user_can($tax->cap->edit_terms)): ?>
					<?php
                        $class = 'widefat';
                                    if ($taxonomy == 'car-state') {
                                        $class .= ' amotos-car-state-ajax';
                                    } elseif ($taxonomy == 'car-city') {
                                        $class .= ' amotos-car-city-ajax';
                                    } elseif (($taxonomy == 'car-neighborhood')) {
                                        $class .= ' amotos-car-neighborhood-ajax';
                                    }
                                    if ($hierarchical) {
                                        wp_dropdown_categories([
                                            'taxonomy'        => $taxonomy,
                                            'class'           => $class,
                                            'hide_empty'      => false,
                                            'name'            => "tax_input[$taxonomy][]",
                                            'selected'        => count($selected) >= 1 ? $selected[ 0 ] : '',
                                            'orderby'         => 'name',
                                            'hierarchical'    => false,
                                            'show_option_all' => esc_html__('None', 'auto-moto-stock'),
                                        ]);
                                    } else {
                                    ?>
						<select name="<?php echo esc_attr("tax_input[$taxonomy][]"); ?>"
						        class="<?php echo esc_attr($class); ?>"
						        data-selected="<?php echo esc_attr(amotos_get_taxonomy_slug_by_post_id($post->ID, $taxonomy)); ?>">
							<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
							<?php
                                $terms = get_categories(
                                                    [
                                                        'taxonomy'   => $taxonomy,
                                                        'orderby'    => 'name',
                                                        'order'      => 'ASC',
                                                        'hide_empty' => false,
                                                        'parent'     => 0,
                                                    ]
                                                );
                                            foreach ($terms as $term): ?>
								<option
										value="<?php echo esc_attr($term->slug); ?>"<?php echo selected($term->term_id, count($selected) >= 1 ? $selected[ 0 ] : ''); ?>><?php echo esc_html($term->name); ?></option>
							<?php endforeach; ?>
						</select>
						<?php
                            }
                                    ?>
				<?php endif; ?>
			</div>
			<?php
                }

                        /**
                         * Register term_meta
                         *
                         * @param $configs
                         *
                         * @return mixed
                         */
                        public function register_term_meta($configs)
                        {
                            $countries                      = amotos_get_selected_countries();
                            $default_country                = amotos_get_option('default_country', 'US');
                            $configs[ 'car-type-settings' ] = apply_filters('amotos_register_term_meta_car_type', [
                                'name'     => esc_html__('Taxonomy Setting', 'auto-moto-stock'),
                                'layout'   => 'horizontal',
                                'taxonomy' => ['car-type'],
                                'fields'   => [
                                    [
                                        'id'      => 'car_type_icon',
                                        'title'   => esc_html__('Icon image', 'auto-moto-stock'),
                                        'desc'    => esc_html__('Icon display on map', 'auto-moto-stock'),
                                        'type'    => 'image',
                                        'default' => '',
                                    ],
                                ],
                            ]);

                            $configs[ 'car-state-settings' ] = apply_filters('amotos_register_term_meta_car_state', [
                                'name'     => '',
                                'layout'   => 'horizontal',
                                'taxonomy' => ['car-state'],
                                'fields'   => [
                                    [
                                        'id'      => 'car_state_country',
                                        'title'   => esc_html__('Country', 'auto-moto-stock'),
                                        'default' => $default_country,
                                        'type'    => 'select',
                                        'options' => $countries,
                                    ],
                                ],
                            ]);
                            $configs[ 'car-label-settings' ] = apply_filters('amotos_register_term_meta_car_label', [
                                'name'     => '',
                                'layout'   => 'horizontal',
                                'taxonomy' => ['car-label'],
                                'fields'   => [
                                    [
                                        'id'       => 'car_label_color',
                                        'title'    => esc_html__('Background Color', 'auto-moto-stock'),
                                        'subtitle' => esc_html__('Set background color for label', 'auto-moto-stock'),
                                        'type'     => 'color',
                                        'default'  => '#888',
                                    ],
                                ],
                            ]);
                            $configs[ 'car-status-settings' ] = apply_filters('amotos_register_term_meta_car_status', [
                                'name'     => '',
                                'layout'   => 'horizontal',
                                'taxonomy' => ['car-status'],
                                'fields'   => [
                                    [
                                        'id'       => 'car_status_color',
                                        'title'    => esc_html__('Background Color', 'auto-moto-stock'),
                                        'subtitle' => esc_html__('Set background color for label status', 'auto-moto-stock'),
                                        'type'     => 'color',
                                        'default'  => '#888',
                                    ],
                                    [
                                        'title'    => __('Order Number', 'auto-moto-stock'),
                                        'subtitle' => esc_html__('The number to set orderby', 'auto-moto-stock'),
                                        'id'       => "car_status_order_number",
                                        'type'     => 'text',
                                        'default'  => '1',
                                        'pattern'  => '[0-9]*',
                                    ],
                                ],
                            ]);
                            $configs[ 'dealer-settings' ] = apply_filters('amotos_register_term_meta_dealer', [
                                'name'     => '',
                                'layout'   => 'horizontal',
                                'taxonomy' => ['dealer'],
                                'fields'   => [
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'id'    => 'dealer_des',
                                                'title' => esc_html__('Content', 'auto-moto-stock'),
                                                'type'  => 'editor',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'id'    => 'dealer_logo',
                                                'title' => esc_html__('Logo', 'auto-moto-stock'),
                                                'type'  => 'image',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'title' => __('Address', 'auto-moto-stock'),
                                                'id'    => "dealer_address",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'title' => __('Licenses', 'auto-moto-stock'),
                                                'id'    => "dealer_licenses",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '12',
                                        'fields' => [
                                            [
                                                'id'    => 'dealer_map_address',
                                                'title' => esc_html__('Google Map Address', 'auto-moto-stock'),
                                                'type'  => 'map',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'id'         => "dealer_email",
                                                'title'      => esc_html__('Email', 'auto-moto-stock'),
                                                'type'       => 'text',
                                                'input_type' => 'email',
                                            ],
                                            [
                                                'title' => __('Mobile Number', 'auto-moto-stock'),
                                                'id'    => "dealer_mobile_number",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Fax Number', 'auto-moto-stock'),
                                                'id'    => "dealer_fax_number",
                                                'type'  => 'text',
                                            ],
                                            [
                                                'title' => __('Office Number', 'auto-moto-stock'),
                                                'id'    => "dealer_office_number",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Website', 'auto-moto-stock'),
                                                'id'    => "dealer_website_url",
                                                'type'  => 'text',
                                            ],
                                            [
                                                'title' => __('Vimeo URL', 'auto-moto-stock'),
                                                'id'    => "dealer_vimeo_url",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Facebook URL', 'auto-moto-stock'),
                                                'id'    => "dealer_facebook_url",
                                                'type'  => 'text',
                                            ],
                                            [
                                                'title' => __('Twitter URL', 'auto-moto-stock'),
                                                'id'    => "dealer_twitter_url",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],

                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Pinterest URL', 'auto-moto-stock'),
                                                'id'    => "dealer_pinterest_url",
                                                'type'  => 'text',
                                            ],
                                            [
                                                'title' => __('Instagram URL', 'auto-moto-stock'),
                                                'id'    => "dealer_instagram_url",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('Skype', 'auto-moto-stock'),
                                                'id'    => "dealer_skype",
                                                'type'  => 'text',
                                            ],
                                            [
                                                'title' => __('Youtube URL', 'auto-moto-stock'),
                                                'id'    => "dealer_youtube_url",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                    [
                                        'type'   => 'row',
                                        'col'    => '6',
                                        'fields' => [
                                            [
                                                'title' => __('LinkedIn URL', 'auto-moto-stock'),
                                                'id'    => "dealer_linkedin_url",
                                                'type'  => 'text',
                                            ],
                                        ],
                                    ],
                                ],
                            ]);

                            return apply_filters('amotos_register_term_meta', $configs);
                        }

                        /**
                         * Register options config
                         *
                         * @param $configs
                         *
                         * @return mixed
                         */
                        public function register_options_config($configs)
                        {
                            $cities     = [];
                            $all_cities = get_categories([
                                'taxonomy'   => 'car-city',
                                'hide_empty' => 0,
                                'orderby'    => 'ASC',
                            ]);
                            if (is_array($all_cities)) {
                                $cities[ '' ] = esc_html__('None', 'auto-moto-stock');
                                foreach ($all_cities as $city) {
                                    $cities[ $city->slug ] = $city->name;
                                }
                            }
                            $configs[ AMOTOS_OPTIONS_NAME ] = [
                                'layout'      => 'horizontal',
                                'page_title'  => esc_html__('AMS Options', 'auto-moto-stock'),
                                'menu_title'  => esc_html__('AMS Options', 'auto-moto-stock'),
                                'option_name' => AMOTOS_OPTIONS_NAME,
                                'permission'  => 'edit_theme_options',
                                'section'     => array_merge(
                                    apply_filters('amotos_register_options_config_top', []),
                                    apply_filters('amotos_register_options_config_main', [
                                        'amotos_general_option'               => $this->general_option($cities),
                                        'amotos_setup_page_option'            => $this->setup_page_option(),
                                        'amotos_url_slugs_option'             => $this->url_slugs_option(),
                                        'amotos_price_format_option'          => $this->price_format_option(),
                                        'amotos_login_register_option'        => $this->login_register_option(),
                                        'amotos_car_option'                   => $this->car_option(),
                                        'amotos_additional_fields_option'     => $this->additional_fields_option(),
                                        'amotos_search_option'                => $this->search_option(),
                                        'amotos_payment_option'               => $this->payment_option(),
                                        'amotos_payment_complete_option'      => $this->payment_complete_option(),
                                        'amotos_invoices_option'              => $this->invoices_option(),
                                        'amotos_compare_option'               => $this->compare_option(),
                                        'amotos_favorite_option'              => $this->favorite_option(),
                                        'amotos_social_share_option'          => $this->social_share_option(),
                                        'amotos_print_option'                 => $this->print_option(),
                                        'amotos_nearby_places_option'         => $this->nearby_places_option(),
                                        'amotos_walk_score_option'            => $this->walk_score_option(),
                                        'amotos_google_map_directions_option' => $this->google_map_directions_option(),
                                        'amotos_comments_reviews_option'      => $this->comments_reviews_option(),
                                        'amotos_google_map_option'            => $this->google_map_option(),
                                        'amotos_captcha_option'               => $this->captcha_option(),
                                        'amotos_car_page_option'              => $this->car_page_option(),
                                        'amotos_archive_manager'              => $this->manager_page_option(),
                                        'amotos_dealer_page_option'           => $this->dealer_page_option(),
                                        'amotos_email_management_option'      => $this->email_management_option(),
                                    ]),
                                    apply_filters('amotos_register_options_config_bottom', [])
                                ),
                            ];

                            return apply_filters('amotos_register_options_config', $configs);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function general_option($cities = [])
                        {
                            $date_languages = [
                                'af'    => 'Afrikaans',
                                'ar'    => 'Arabic',
                                'ar-DZ' => 'Algerian',
                                'az'    => 'Azerbaijani',
                                'be'    => 'Belarusian',
                                'bg'    => 'Bulgarian',
                                'bs'    => 'Bosnian',
                                'ca'    => 'Catalan',
                                'cs'    => 'Czech',
                                'cy-GB' => 'Welsh/UK',
                                'da'    => 'Danish',
                                'de'    => 'German',
                                'el'    => 'Greek',
                                'en-AU' => 'English/Australia',
                                'en-GB' => 'English/UK',
                                'en-NZ' => 'English/New Zealand',
                                'eo'    => 'Esperanto',
                                'es'    => 'Spanish',
                                'et'    => 'Estonian',
                                'eu'    => 'Karrikas-ek',
                                'fa'    => 'Persian',
                                'fi'    => 'Finnish',
                                'fo'    => 'Faroese',
                                'fr'    => 'French',
                                'fr-CA' => 'Canadian-French',
                                'fr-CH' => 'Swiss-French',
                                'gl'    => 'Galician',
                                'he'    => 'Hebrew',
                                'hi'    => 'Hindi',
                                'hr'    => 'Croatian',
                                'hu'    => 'Hungarian',
                                'hy'    => 'Armenian',
                                'id'    => 'Indonesian',
                                'ic'    => 'Icelandic',
                                'it'    => 'Italian',
                                'it-CH' => 'Italian-CH',
                                'ja'    => 'Japanese',
                                'ka'    => 'Georgian',
                                'kk'    => 'Kazakh',
                                'km'    => 'Khmer',
                                'ko'    => 'Korean',
                                'ky'    => 'Kyrgyz',
                                'lb'    => 'Luxembourgish',
                                'lt'    => 'Lithuanian',
                                'lv'    => 'Latvian',
                                'mk'    => 'Macedonian',
                                'ml'    => 'Malayalam',
                                'ms'    => 'Malaysian',
                                'nb'    => 'Norwegian',
                                'nl'    => 'Dutch',
                                'nl-BE' => 'Dutch-Belgium',
                                'nn'    => 'Norwegian-Nynorsk',
                                'no'    => 'Norwegian',
                                'pl'    => 'Polish',
                                'pt'    => 'Portuguese',
                                'pt-BR' => 'Brazilian',
                                'rm'    => 'Romansh',
                                'ro'    => 'Romanian',
                                'ru'    => 'Russian',
                                'sk'    => 'Slovak',
                                'sl'    => 'Slovenian',
                                'sq'    => 'Albanian',
                                'sr'    => 'Serbian',
                                'sr-SR' => 'Serbian-i18n',
                                'sv'    => 'Swedish',
                                'ta'    => 'Tamil',
                                'th'    => 'Thai',
                                'tj'    => 'Tajiki',
                                'tr'    => 'Turkish',
                                'uk'    => 'Ukrainian',
                                'vi'    => 'Vietnamese',
                                'zh-CN' => 'Chinese',
                                'zh-HK' => 'Chinese-Hong-Kong',
                                'zh-TW' => 'Chinese Taiwan',
                            ];

                            return apply_filters('amotos_register_option_general', [
                                'id'     => 'amotos_general_option',
                                'title'  => esc_html__('General', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-menu-alt3',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_general_top', []),
                                    apply_filters('amotos_register_option_general_main', [
                                        [
                                            'id'       => 'default_country',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Country', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Select country', 'auto-moto-stock'),
                                            'options'  => amotos_get_selected_countries(),
                                            'default'  => 'US',
                                        ],
                                        [
                                            'id'       => 'default_city',
                                            'type'     => 'select',
                                            'title'    => esc_html__('City/Town', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Select city', 'auto-moto-stock'),
                                            'options'  => $cities,
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'enable_filter_location',
                                            'type'     => 'button_set',
                                            'title'    => esc_html__('Enable Filter Location', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Filter Country, State, City, Neighborhood on Search form and Submit page', 'auto-moto-stock'),
                                            'desc'     => '',
                                            'options'  => [
                                                '1' => esc_html__('On', 'auto-moto-stock'),
                                                '0' => esc_html__('Off', 'auto-moto-stock'),
                                            ],
                                            'default'  => 0,
                                        ],
                                        [
                                            'id'      => 'date_language',
                                            'type'    => 'select',
                                            'title'   => esc_html__('Language for datepicker', 'auto-moto-stock'),
                                            'options' => $date_languages,
                                            'default' => 'en-GB',
                                        ],
                                        [
                                            'id'       => 'measurement_units_mileage',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Measurement units for Mileage', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Choose units for Mileage', 'auto-moto-stock'),
                                            'options'  => [
                                                'Mi'     => esc_html__('Miles (Mi)', 'auto-moto-stock'),
                                                'Km'     => esc_html__('Kilometers (Km)', 'auto-moto-stock'),
                                                'custom' => esc_html__('Custom Units', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'Mi',
                                        ],
                                        [
                                            'id'       => 'custom_measurement_units_mileage',
                                            'type'     => 'text',
                                            'required' => ['measurement_units_mileage', '=', 'custom'],
                                            'title'    => esc_html__('Custom Units', 'auto-moto-stock'),
                                            'default'  => 'Mi',
                                        ],
                                        [
                                            'id'       => 'measurement_units_power',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Measurement units for Power', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Choose units for Power', 'auto-moto-stock'),
                                            'options'  => [
                                                'Hp'     => esc_html__('Horse Power (Hp)', 'auto-moto-stock'),
                                                'kW'     => esc_html__('KiloWatt (kW)', 'auto-moto-stock'),
                                                'custom' => esc_html__('Custom Units', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'Hp',
                                        ],
                                        [
                                            'id'       => 'custom_measurement_units_power',
                                            'type'     => 'text',
                                            'required' => ['measurement_units_power', '=', 'custom'],
                                            'title'    => esc_html__('Custom Units', 'auto-moto-stock'),
                                            'default'  => 'Hp',
                                        ],
                                        [
                                            'id'       => 'measurement_units_volume',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Measurement units for Cubic Capacity', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Choose units for Cubic Capacity', 'auto-moto-stock'),
                                            'options'  => [
                                                'CID'    => esc_html__('Cubic Inch (CID)', 'auto-moto-stock'),
                                                'cm3'    => esc_html__('Cubic Centimeter (cm3)', 'auto-moto-stock'),
                                                'custom' => esc_html__('Custom Units', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'CID',
                                        ],
                                        [
                                            'id'       => 'custom_measurement_units_volume',
                                            'type'     => 'text',
                                            'required' => ['measurement_units_volume', '=', 'custom'],
                                            'title'    => esc_html__('Custom Units', 'auto-moto-stock'),
                                            'default'  => 'CID',
                                        ],
                                        [
                                            'id'     => 'amotos_other_options',
                                            'title'  => esc_html__('Other Options', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'       => 'enable_rtl_mode',
                                                    'type'     => 'button_set',
                                                    'title'    => esc_html__('Enable RTL mode', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enable/Disable RTL mode', 'auto-moto-stock'),
                                                    'desc'     => '',
                                                    'options'  => [
                                                        '1' => esc_html__('On', 'auto-moto-stock'),
                                                        '0' => esc_html__('Off', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => 0,
                                                ],
                                                [
                                                    'id'       => 'enable_min_js',
                                                    'title'    => esc_html__('Enable Mini File JS', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enable/Disable Mini File JS', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('On', 'auto-moto-stock'),
                                                        '0' => esc_html__('Off', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                ],
                                                [
                                                    'id'       => 'enable_min_css',
                                                    'title'    => esc_html__('Enable Mini File CSS', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enable/Disable Mini File CSS', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('On', 'auto-moto-stock'),
                                                        '0' => esc_html__('Off', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                ],
                                                [
                                                    'id'       => 'cdn_bootstrap_js',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('CDN Bootstrap Script', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Url CDN Bootstrap Script', 'auto-moto-stock'),
                                                    'desc'     => '',
                                                    'default'  => '',
                                                ],
                                                [
                                                    'id'       => 'cdn_bootstrap_css',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('CDN Bootstrap Stylesheet', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Url CDN Bootstrap Stylesheet', 'auto-moto-stock'),
                                                    'desc'     => '',
                                                    'default'  => '',
                                                ],
                                                [
                                                    'id'       => 'cdn_font_awesome',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('CDN Font Awesome', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Url CDN Font Awesome', 'auto-moto-stock'),
                                                    'desc'     => '',
                                                    'default'  => '',
                                                ],
                                                [
                                                    'id'       => 'enable_add_shortcode_tool',
                                                    'title'    => esc_html__('Enable Add Shortcode Tool', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enable/Disable Add Shortcode Tool For Editor', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('On', 'auto-moto-stock'),
                                                        '0' => esc_html__('Off', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_general_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function setup_page_option()
                        {
                            $page_setup_config = AMOTOS_Admin_Setup::get_page_setup_config();
                            $config            = [];
                            foreach ($page_setup_config as $k => $v) {
                                $config[  ] = [
                                    'id' => "amotos_{$k}_page_id",
                                    'title'     => $v[ 'title' ],
                                    'type'      => 'select',
                                    'data'      => 'page',
                                    'data_args' => [
                                        'numberposts' => -1,
                                    ],
                                ];
                            }
                            $config = apply_filters('amotos_register_option_setup_page_main', $config);

                            return apply_filters('amotos_register_option_setup_page', [
                                'id'     => 'amotos_setup_page_option',
                                'title'  => esc_html__('Setup Page', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-text-page',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_setup_page_top', []),
                                    $config,
                                    apply_filters('amotos_register_option_setup_page_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function url_slugs_option()
                        {
                            return apply_filters('amotos_register_option_url_slugs', [
                                'id'     => 'amotos_url_slugs_option',
                                'title'  => esc_html__('URL Slug', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-admin-links',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_url_slugs_top', []),
                                    apply_filters('amotos_register_option_url_slugs_main', [
                                        [
                                            'id'      => 'car_url_slug',
                                            'title'   => esc_html__('Vehicle Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car',
                                        ],
                                        [
                                            'id'      => 'car_type_url_slug',
                                            'title'   => esc_html__('Type Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-type',
                                        ],
                                        [
                                            'id'      => 'car_status_url_slug',
                                            'title'   => esc_html__('Status Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-status',
                                        ],
                                        [
                                            'id'      => 'car_styling_url_slug',
                                            'title'   => esc_html__('Styling Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-styling',
                                        ],
                                        [
                                            'id'      => 'car_label_url_slug',
                                            'title'   => esc_html__('Label Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-label',
                                        ],
                                        [
                                            'id'      => 'car_state_url_slug',
                                            'title'   => esc_html__('Province/State Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-state',
                                        ],
                                        [
                                            'id'      => 'car_city_url_slug',
                                            'title'   => esc_html__('City Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-city',
                                        ],
                                        [
                                            'id'      => 'car_neighborhood_url_slug',
                                            'title'   => esc_html__('Neighborhood Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'car-neighborhood',
                                        ],
                                        [
                                            'id'      => 'manager_url_slug',
                                            'title'   => esc_html__('Manager Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'manager',
                                        ],
                                        [
                                            'id'      => 'dealer_url_slug',
                                            'title'   => esc_html__('Dealer Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'dealer',
                                        ],
                                        [
                                            'id'      => 'author_url_slug',
                                            'title'   => esc_html__('Author Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'author',
                                        ],
                                        [
                                            'id'      => 'package_url_slug',
                                            'title'   => esc_html__('Package Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'package',
                                        ],
                                        [
                                            'id'      => 'user_package_url_slug',
                                            'title'   => esc_html__('Manager Packages Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'user_package',
                                        ],
                                        [
                                            'id'      => 'invoice_url_slug',
                                            'title'   => esc_html__('Invoice Slug', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'invoice',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_url_slugs_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function price_format_option()
                        {
                            return apply_filters('amotos_register_option_price_format', [
                                'id'     => 'amotos_price_format_option',
                                'title'  => esc_html__('Price Format', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-money-alt',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_price_format_top', []),
                                    apply_filters('amotos_register_option_price_format_main', [
                                        [
                                            'id'       => 'enable_price_unit',
                                            'title'    => esc_html__('Enable Price Unit', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Price Unit: "Thousand, Million" on Vehicle Submit form via backend and frontend', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                        ],
                                        [
                                            'id'       => 'thousand_text',
                                            'title'    => esc_html__('Thousand Text', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('K or Thousand', 'auto-moto-stock'),
                                            'type'     => 'text',
                                            'default'  => 'K',
                                            'required' => ['enable_price_unit', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'million_text',
                                            'title'    => esc_html__('Million Text', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('M or Million', 'auto-moto-stock'),
                                            'type'     => 'text',
                                            'default'  => 'M',
                                            'required' => ['enable_price_unit', '=', '1'],
                                        ],
                                        [
                                            'id'      => 'currency_sign',
                                            'title'   => esc_html__('Currency Sign', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => '$',
                                        ],
                                        [
                                            'id'      => 'currency_position',
                                            'title'   => esc_html__('Currency Sign Position', 'auto-moto-stock'),
                                            'type'    => 'select',
                                            'options' => [
                                                'before' => esc_html__('Before ($15,000)', 'auto-moto-stock'),
                                                'after'  => esc_html__('After (15,000$)', 'auto-moto-stock'),
                                            ],
                                            'default' => 'before',
                                        ],
                                        [
                                            'id'       => 'thousand_separator',
                                            'title'    => esc_html__('Thousand Separator', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('This sets the thousand separator of displayed prices.', 'auto-moto-stock'),
                                            'type'     => 'text',
                                            'default'  => ',',
                                        ],
                                        [
                                            'id'       => 'decimal_separator',
                                            'title'    => esc_html__('Decimal Separator', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('This sets the decimal separator of displayed prices.', 'auto-moto-stock'),
                                            'type'     => 'text',
                                            'default'  => '.',
                                        ],
                                        [
                                            'id'         => 'number_of_decimals',
                                            'title'      => esc_html__('Number of decimals', 'auto-moto-stock'),
                                            'subtitle'   => esc_html__('This sets the number of decimal points shown in displayed prices.', 'auto-moto-stock'),
                                            'type'       => 'text',
                                            'input_type' => 'number',
                                            'default'    => '0',
                                        ],
                                        [
                                            'id'      => 'empty_price_text',
                                            'title'   => esc_html__('Price on Call Text', 'auto-moto-stock'),
                                            'type'    => 'text',
                                            'default' => 'Price on call',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_price_format_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function login_register_option()
                        {
                            return apply_filters('amotos_register_option_login_register', [
                                'id'     => 'amotos_login_register_option',
                                'title'  => esc_html__('User & Manager', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-admin-network',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_login_register_top', []),
                                    apply_filters('amotos_register_option_login_register_main', [
                                        [
                                            'id'       => 'enable_submit_car_via_frontend',
                                            'title'    => esc_html__('Allow to submit vehicle via frontend?', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('If "no", only via backend', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                        ],
                                        [
                                            'id'       => 'user_can_submit',
                                            'title'    => esc_html__('All User can submit vehicle?', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('If "no", only manager', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                            'required' => ['enable_submit_car_via_frontend', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'user_as_manager',
                                            'title'    => esc_html__('User can register as manager?', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                            'required' => ['enable_submit_car_via_frontend', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'auto_approved_manager',
                                            'title'    => esc_html__('Automatically approved after user register as manager?', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                            'required' => ['user_as_manager', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'enable_password',
                                            'title'    => esc_html__('Users can type Password on registration form?', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('If "no", users will get an auto generated password via email', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                        ],
                                        [
                                            'id'        => 'register_terms_condition',
                                            'title'     => esc_html__('Register Terms & Conditions', 'auto-moto-stock'),
                                            'subtitle'  => esc_html__('Select terms & conditions page', 'auto-moto-stock'),
                                            'type'      => 'select',
                                            'data'      => 'page',
                                            'data_args' => [
                                                'numberposts' => -1,
                                            ],
                                        ],
                                        [
                                            'id'        => 'become_manager_terms_condition',
                                            'title'     => esc_html__('Become an manager Terms & Conditions', 'auto-moto-stock'),
                                            'subtitle'  => esc_html__('Select terms & conditions page', 'auto-moto-stock'),
                                            'type'      => 'select',
                                            'data'      => 'page',
                                            'data_args' => [
                                                'numberposts' => -1,
                                            ],
                                            'required'  => ['user_as_manager', '=', '1'],
                                        ],
                                        [
                                            'id'      => 'enable_register_tab',
                                            'title'   => esc_html__('Enable Register tab on Login & Register popup?', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                        [
                                            'id'       => 'default_user_avatar',
                                            'type'     => 'image',
                                            'url'      => true,
                                            'title'    => esc_html__('Default User Avatar', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Display this if no user avatar', 'auto-moto-stock'),
                                            'default'  => AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png',
                                        ],
                                        [
                                            'id'     => 'section_user_info_hide_fields',
                                            'title'  => esc_html__('Hide User Information Fields', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'           => 'hide_user_info_fields',
                                                    'type'         => 'checkbox_list',
                                                    'title'        => esc_html__('Hide User Information Fields', 'auto-moto-stock'),
                                                    'subtitle'     => esc_html__('Choose which fields you want to hide on My Profile page?', 'auto-moto-stock'),
                                                    'options'      => [
                                                        'user_company'        => esc_html__('Company (For Manager)', 'auto-moto-stock'),
                                                        'user_position'       => esc_html__('Position (For Manager)', 'auto-moto-stock'),
                                                        'user_office_number'  => esc_html__('Office Number (For Manager)', 'auto-moto-stock'),
                                                        'user_office_address' => esc_html__('Office Address (For Manager)', 'auto-moto-stock'),
                                                        'user_licenses'       => esc_html__('Licenses (For Manager)', 'auto-moto-stock'),
                                                        'user_fax_number'     => esc_html__('Fax', 'auto-moto-stock'),
                                                        'user_website_url'    => esc_html__('Website URL', 'auto-moto-stock'),
                                                        'user_skype'          => esc_html__('Skype', 'auto-moto-stock'),
                                                        'user_facebook_url'   => esc_html__('Facebook URL', 'auto-moto-stock'),
                                                        'user_twitter_url'    => esc_html__('Twitter URL', 'auto-moto-stock'),
                                                        'user_linkedin_url'   => esc_html__('Linkedin URL', 'auto-moto-stock'),
                                                        'user_instagram_url'  => esc_html__('Instagram URL', 'auto-moto-stock'),
                                                        'user_pinterest_url'  => esc_html__('Pinterest URL', 'auto-moto-stock'),
                                                        'user_youtube_url'    => esc_html__('Youtube URL', 'auto-moto-stock'),
                                                        'user_vimeo_url'      => esc_html__('Vimeo URL', 'auto-moto-stock'),
                                                    ],
                                                    'value_inline' => false,
                                                    'default'      => [],
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_login_register_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function car_option()
                        {
                            return apply_filters('amotos_register_option_car', [
                                'id'     => 'amotos_car_option',
                                'title'  => esc_html__('Vehicles', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-car',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_car_top', []),
                                    apply_filters('amotos_register_option_car_main', [
                                        [
                                            'id'     => 'section_car_main_option',
                                            'title'  => esc_html__('Main Options', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'auto_publish',
                                                    'title'   => esc_html__('Automatically publish the submitted vehicle?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'      => 'auto_publish_edited',
                                                    'title'   => esc_html__('Automatically publish the edited vehicle?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'      => 'auto_approve_request_publish',
                                                    'title'   => esc_html__('Automatically approve Reactivating vehicle request?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '0',
                                                ],
                                                [
                                                    'id'      => 'car_form_sections',
                                                    'type'    => 'sortable',
                                                    'title'   => esc_html__('Submission Form Layout Manager', 'auto-moto-stock'),
                                                    'desc'    => esc_html__('Drag and drop layout manager, to quickly organize your vehicle submission form layout', 'auto-moto-stock'),
                                                    'options' => amotos_get_car_form_section_config(),
                                                    'default' => amotos_get_car_form_section_config_default(),
                                                ],
                                                [
                                                    'id'       => 'location_dropdowns',
                                                    'title'    => esc_html__('Show dropdowns for Vehicle Location?', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Neighborhood, City, Province/State, Country?', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                    'required' => ['car_form_sections', 'contain', 'location'],
                                                ],
                                                [
                                                    'id'       => 'max_car_images',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Maximum Images', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Number of images allowed for single vehicle', 'auto-moto-stock'),
                                                    'default'  => '10',
                                                    'required' => [
                                                        [
                                                            ['car_form_sections', 'contain', 'media'],
                                                            ['car_form_sections', 'contain'],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'image_max_file_size',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Maximum File Size', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Upload image file size. For example 10kb, 500kb, 1mb, 10m, 100mb', 'auto-moto-stock'),
                                                    'default'  => '1000kb',
                                                    'required' => [
                                                        [
                                                            ['car_form_sections', 'contain', 'media'],
                                                            ['car_form_sections', 'contain'],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'max_car_attachments',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Maximum Attachments', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Number of attachments allowed for single vehicle', 'auto-moto-stock'),
                                                    'default'  => '2',
                                                    'required' => [
                                                        [
                                                            ['car_form_sections', 'contain', 'media'],
                                                            ['car_form_sections', 'contain'],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'attachment_max_file_size',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Maximum File Size', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Upload attachment file size. For example 10kb, 500kb, 1mb, 10m, 100mb', 'auto-moto-stock'),
                                                    'default'  => '1000kb',
                                                    'required' => [
                                                        [
                                                            ['car_form_sections', 'contain', 'media'],
                                                            ['car_form_sections', 'contain'],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'attachment_file_type',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('File Type', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Only words separated by commas. Ex: pdf,txt,doc,docx', 'auto-moto-stock'),
                                                    'default'  => 'pdf,txt,doc,docx',
                                                    'required' => [
                                                        [
                                                            ['car_form_sections', 'contain', 'media'],
                                                            ['car_form_sections', 'contain'],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'default_car_image',
                                                    'type'     => 'image',
                                                    'url'      => true,
                                                    'title'    => esc_html__('Default Vehicle Image', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Display this if no vehicle image', 'auto-moto-stock'),
                                                    'default'  => AMOTOS_PLUGIN_URL . 'public/assets/images/map-marker-icon.png',
                                                ],
                                                [
                                                    'id'      => 'featured_toplist',
                                                    'title'   => esc_html__('Show featured vehicles at the top of the list?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'     => 'section_car_hide_fields',
                                            'title'  => esc_html__('Hide Submit Form Fields', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'           => 'hide_car_fields',
                                                    'type'         => 'checkbox_list',
                                                    'title'        => esc_html__('Hide Submit Form Fields', 'auto-moto-stock'),
                                                    'subtitle'     => esc_html__('Choose which fields you want to hide on New Vehicle page?', 'auto-moto-stock'),
                                                    'options'      => [
                                                        'car_identity'       => esc_html__('Vehicle ID', 'auto-moto-stock'),
                                                        'car_des'            => esc_html__('Description', 'auto-moto-stock'),
                                                        // Basic info
                                                        'car_type'           => esc_html__('Type', 'auto-moto-stock'),
                                                        'car_year'           => esc_html__('Year Vehicle', 'auto-moto-stock'),
                                                        'car_owners'         => esc_html__('Owners', 'auto-moto-stock'),
                                                        'car_styling'        => esc_html__('Styling', 'auto-moto-stock'),
                                                        'car_status'         => esc_html__('Status', 'auto-moto-stock'),
                                                        'car_label'          => esc_html__('Label', 'auto-moto-stock'),
                                                        // Price
                                                        'car_price'          => esc_html__('Price', 'auto-moto-stock'),
                                                        'car_price_prefix'   => esc_html__('Before Price Label', 'auto-moto-stock'),
                                                        'car_price_postfix'  => esc_html__('After Price Label', 'auto-moto-stock'),
                                                        'car_price_on_call'  => esc_html__('Price on Call', 'auto-moto-stock'),
                                                        // Tachnical data
                                                        'car_mileage'        => esc_html__('Mileage', 'auto-moto-stock'),
                                                        'car_power'          => esc_html__('Power', 'auto-moto-stock'),
                                                        'car_volume'         => esc_html__('Cubic Capacity', 'auto-moto-stock'),
                                                        'car_doors'          => esc_html__('Doors', 'auto-moto-stock'),
                                                        'car_seats'          => esc_html__('Seats', 'auto-moto-stock'),
                                                        // Files
                                                        'car_attachments'    => esc_html__('File Attachments', 'auto-moto-stock'),
                                                        'car_video_url'      => esc_html__('Video Url', 'auto-moto-stock'),
                                                        'car_image_360'      => esc_html__('Image 360', 'auto-moto-stock'),
                                                        // Additional details
                                                        'additional_details' => esc_html__('Additional Details', 'auto-moto-stock'),
                                                        // Location
                                                        'car_map_address'    => esc_html__('Map Address', 'auto-moto-stock'),
                                                        'country'            => esc_html__('Country', 'auto-moto-stock'),
                                                        'state'              => esc_html__('Province/State', 'auto-moto-stock'),
                                                        'city'               => esc_html__('City/Town', 'auto-moto-stock'),
                                                        'neighborhood'       => esc_html__('Neighborhood', 'auto-moto-stock'),
                                                        'postal_code'        => esc_html__('Postal code', 'auto-moto-stock'),
                                                        // Contact
                                                        'author_info'        => esc_html__('My profile information', 'auto-moto-stock'),
                                                        'other_info'         => esc_html__('Other contact', 'auto-moto-stock'),
                                                        'private_note'       => esc_html__('Private Note', 'auto-moto-stock'),
                                                    ],
                                                    'value_inline' => false,
                                                    'default'      => [],
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'     => 'section_car_required_fields',
                                            'title'  => esc_html__('Required Fields', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'           => 'required_fields',
                                                    'title'        => esc_html__('Required Fields', 'auto-moto-stock'),
                                                    'type'         => 'checkbox_list',
                                                    'options'      => $this->get_car_required_fields(),
                                                    'value_inline' => false,
                                                    'default'      => [
                                                        'car_title',
                                                        'car_type',
                                                        'car_price',
                                                        'car_map_address',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_car_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function additional_fields_option()
                        {
                            return apply_filters('amotos_register_option_additional_fields', [
                                'id'     => 'amotos_additional_fields_option',
                                'title'  => esc_html__('Additional Fields', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-welcome-add-page',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_additional_fields_top', []),
                                    apply_filters('amotos_register_option_additional_fields_main', [
                                        [
                                            'id'          => "additional_fields",
                                            'type'        => 'panel',
                                            'title'       => esc_html__('Vehicle Additional Field', 'auto-moto-stock'),
                                            'sort'        => true,
                                            'panel_title' => 'label',
                                            'fields'      => [
                                                [
                                                    'title'    => esc_html__('Label', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Add your own custom field name', 'auto-moto-stock'),
                                                    'id'       => "label",
                                                    'type'     => 'text',
                                                    'default'  => '',
                                                ],
                                                [
                                                    'title'       => esc_html__('ID', 'auto-moto-stock'),
                                                    'id'          => "id",
                                                    'type'        => 'text',
                                                    'placeholder' => esc_html__('Enter field ID', 'auto-moto-stock'),
                                                    'desc'        => esc_html__('ID values cannot be changed after being set!', 'auto-moto-stock'),
                                                    'default'     => '',
                                                ],
                                                [
                                                    'title'   => esc_html__('Field Type', 'auto-moto-stock'),
                                                    'id'      => "field_type",
                                                    'type'    => 'select',
                                                    'default' => 'text',
                                                    'options' => [
                                                        'text'          => esc_html__('Text', 'auto-moto-stock'),
                                                        'textarea'      => esc_html__('Text Multiple Line', 'auto-moto-stock'),
                                                        'select'        => esc_html__('Select', 'auto-moto-stock'),
                                                        'checkbox_list' => esc_html__('Checkbox List', 'auto-moto-stock'),
                                                        'radio'         => esc_html__('Radio', 'auto-moto-stock'),
                                                    ],
                                                ],
                                                [
                                                    'title'    => esc_html__('Options Value', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Input each per line', 'auto-moto-stock'),
                                                    'id'       => "select_choices",
                                                    'type'     => 'textarea',
                                                    'default'  => '',
                                                    'required' => [
                                                        "additional_fields_field_type",
                                                        'in',
                                                        ['checkbox_list', 'radio', 'select'],
                                                    ],
                                                ],
                                                [
                                                    'id'      => 'is_search',
                                                    'type'    => 'button_set',
                                                    'title'   => esc_html__('Make available for searches?', 'auto-moto-stock'),
                                                    'options' => [
                                                        'on'  => esc_html__('On', 'auto-moto-stock'),
                                                        'off' => esc_html__('Off', 'auto-moto-stock'),
                                                    ],
                                                    'default' => 'off',
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_additional_fields_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function search_option()
                        {
                            return apply_filters('amotos_register_option_search', [
                                'id'     => 'amotos_search_option',
                                'title'  => esc_html__('Search', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-search',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_search_top', []),
                                    apply_filters('amotos_register_option_search_main', [
                                        [
                                            'id'     => 'section_search_field_option',
                                            'title'  => esc_html__('Show / Hide / Arrange Search Fields', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'search_fields',
                                                    'type'    => 'sortable',
                                                    'title'   => esc_html__('Search Fields', 'auto-moto-stock'),
                                                    'desc'    => esc_html__('Drag and drop layout manager, to quickly organize your form search layout', 'auto-moto-stock'),
                                                    'options' => amotos_get_search_form_fields_config(),
                                                    'default' => amotos_get_search_form_fields_config_default(),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'     => 'section_search_form_option',
                                            'title'  => esc_html__('Search Form Options', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'keyword_field',
                                                    'type'    => 'select',
                                                    'title'   => esc_html__('Keyword Field', 'auto-moto-stock'),
                                                    'desc'    => esc_html__('Select the search criteria for the keyword field', 'auto-moto-stock'),
                                                    'options' => [
                                                        'veh_title'             => esc_html__('Vehicle Title or Content', 'auto-moto-stock'),
                                                        'veh_address'           => esc_html__('Vehicle address, street, zip or vehicle ID', 'auto-moto-stock'),
                                                        'veh_city_state_county' => esc_html__('Search State, City', 'auto-moto-stock'),
                                                    ],
                                                    'default' => 'veh_address',
                                                ],
                                                [
                                                    'id'     => 'section_search_form_price_field_option',
                                                    'title'  => esc_html__('Price Field', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'    => 'amotos_car_price_dropdown_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Price Dropdown Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'      => 'enable_price_number_short_scale',
                                                            'title'   => esc_html__('Enable Price Number in Short Scale on Search Field', 'auto-moto-stock'),
                                                            'type'    => 'button_set',
                                                            'options' => [
                                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                                            ],
                                                            'default' => '0',
                                                        ],

                                                        [
                                                            'id'       => 'car_price_dropdown_min',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Minimum Prices List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                                        ],
                                                        [
                                                            'id'       => 'car_price_dropdown_max',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Maximum Prices List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                                        ],

                                                        [
                                                            'id'     => 'car_price_dropdown_search_field',
                                                            'title'  => esc_html__('Price Field', 'auto-moto-stock'),
                                                            'type'   => 'panel',
                                                            'sort'   => false,
                                                            'fields' => [
                                                                [
                                                                    'type'   => 'row',
                                                                    'col'    => '12',
                                                                    'fields' => [
                                                                        [
                                                                            'id'        => 'car_price_dropdown_car_status',
                                                                            'title'     => esc_html__('Vehicle Status', 'auto-moto-stock'),
                                                                            'type'      => 'select',
                                                                            'data'      => 'taxonomy',
                                                                            'data_args' => [
                                                                                'taxonomy'   => 'car-status',
                                                                                'hide_empty' => 0,
                                                                                'orderby'    => 'ASC',
                                                                            ],
                                                                        ],
                                                                        [
                                                                            'id'       => 'car_price_dropdown_min',
                                                                            'title'    => esc_html__('Minimum Price', 'auto-moto-stock'),
                                                                            'subtitle' => esc_html__('Only comma separated numbers. Ex: 0,100,300,500,700,900', 'auto-moto-stock'),
                                                                            'type'     => 'text',
                                                                        ],
                                                                        [
                                                                            'id'       => 'car_price_dropdown_max',
                                                                            'title'    => esc_html__('Maximum Price', 'auto-moto-stock'),
                                                                            'subtitle' => esc_html__('Only comma separated numbers. Ex: 200,400,600,800,1000,1200', 'auto-moto-stock'),
                                                                            'type'     => 'text',
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        [
                                                            'id'    => 'amotos_car_price_slider_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Vehicle Price Slider Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'      => 'car_price_slider_min',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Minimum Price', 'auto-moto-stock'),
                                                            'default' => '200',
                                                        ],
                                                        [
                                                            'id'      => 'car_price_slider_max',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Maximum Price', 'auto-moto-stock'),
                                                            'default' => '2500000',
                                                        ],
                                                        [
                                                            'id'     => 'car_price_slider_search_field',
                                                            'title'  => esc_html__('Price Field', 'auto-moto-stock'),
                                                            'type'   => 'panel',
                                                            'sort'   => false,
                                                            'fields' => [
                                                                [
                                                                    'type'   => 'row',
                                                                    'col'    => '12',
                                                                    'fields' => [
                                                                        [
                                                                            'id'        => 'car_price_slider_car_status',
                                                                            'title'     => esc_html__('Vehicle Status', 'auto-moto-stock'),
                                                                            'type'      => 'select',
                                                                            'data'      => 'taxonomy',
                                                                            'data_args' => [
                                                                                'taxonomy'   => 'car-status',
                                                                                'hide_empty' => 0,
                                                                                'orderby'    => 'ASC',
                                                                            ],
                                                                        ],
                                                                        [
                                                                            'id'       => 'car_price_slider_min',
                                                                            'title'    => esc_html__('Minimum Price', 'auto-moto-stock'),
                                                                            'subtitle' => esc_html__('Enter Minimum Price. Ex: 200', 'auto-moto-stock'),
                                                                            'type'     => 'text',
                                                                        ],
                                                                        [
                                                                            'id'       => 'car_price_slider_max',
                                                                            'title'    => esc_html__('Maximum Price', 'auto-moto-stock'),
                                                                            'subtitle' => esc_html__('Enter Maximum Price. Ex: 200000', 'auto-moto-stock'),
                                                                            'type'     => 'text',
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'section_search_form_mileage_field_option',
                                                    'title'  => esc_html__('Mileage Field', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'    => 'amotos_car_mileage_dropdown_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Mileage Dropdown Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'       => 'car_mileage_dropdown_min',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Min Mileage List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                                        ],
                                                        [
                                                            'id'       => 'car_mileage_dropdown_max',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Max Mileage List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                                        ],
                                                        [
                                                            'id'    => 'amotos_car_mileage_slider_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Mileage Slider Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'      => 'car_mileage_slider_min',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Min Mileage', 'auto-moto-stock'),
                                                            'default' => '10',
                                                        ],
                                                        [
                                                            'id'      => 'car_mileage_slider_max',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Max Mileage', 'auto-moto-stock'),
                                                            'default' => '1000',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'section_search_form_power_field_option',
                                                    'title'  => esc_html__('Power Field', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'    => 'amotos_car_power_dropdown_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Power Dropdown Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'       => 'car_power_dropdown_min',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Min Power List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                                        ],
                                                        [
                                                            'id'       => 'car_power_dropdown_max',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Max Power List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                                        ],
                                                        [
                                                            'id'    => 'amotos_car_power_slider_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Power Slider Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'      => 'car_power_slider_min',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Min Power', 'auto-moto-stock'),
                                                            'default' => '10',
                                                        ],
                                                        [
                                                            'id'      => 'car_power_slider_max',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Max Power', 'auto-moto-stock'),
                                                            'default' => '1000',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'section_search_form_volume_field_option',
                                                    'title'  => esc_html__('Cubic Capacity Field', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'    => 'amotos_car_volume_dropdown_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Cubic Capacity Dropdown Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'       => 'car_volume_dropdown_min',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Min Cubic Capacity List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                                        ],
                                                        [
                                                            'id'       => 'car_volume_dropdown_max',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Max Cubic Capacity List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                                        ],
                                                        [
                                                            'id'    => 'amotos_car_volume_slider_search_field',
                                                            'type'  => 'info',
                                                            'style' => 'info',
                                                            'title' => esc_html__('Cubic Capacity Slider Value', 'auto-moto-stock'),
                                                        ],
                                                        [
                                                            'id'      => 'car_volume_slider_min',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Min Cubic Capacity', 'auto-moto-stock'),
                                                            'default' => '10',
                                                        ],
                                                        [
                                                            'id'      => 'car_volume_slider_max',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Max Cubic Capacity', 'auto-moto-stock'),
                                                            'default' => '1000',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'section_search_form_other_field_option',
                                                    'title'  => esc_html__('Other Fields', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'       => 'doors_list',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Doors List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '1,2,3,4,5,6,7,8,9,10',
                                                        ],
                                                        [
                                                            'id'       => 'seats_list',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Seats List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '1,2,3,4,5,6,7,8,9,10',
                                                        ],
                                                        [
                                                            'id'       => 'owners_list',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Owners List', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Only comma separated numbers', 'auto-moto-stock'),
                                                            'default'  => '1,2,3,4,5,6,7,8,9,10',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        /**
                                         *  Search Page
                                         */
                                        [
                                            'id'     => 'section_search_page_option',
                                            'title'  => esc_html__('Advanced Search Page Options', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'enable_advanced_search_form',
                                                    'title'   => esc_html__('Search Form', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                        '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'      => 'enable_advanced_search_status_tab',
                                                    'title'   => esc_html__('Status Tab', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                        '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'advanced_search_price_field_layout',
                                                    'title'    => esc_html__('Price Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_advanced_search_form', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'advanced_search_mileage_field_layout',
                                                    'title'    => esc_html__('Mileage Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_advanced_search_form', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'advanced_search_power_field_layout',
                                                    'title'    => esc_html__('Power Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_advanced_search_form', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'advanced_search_volume_field_layout',
                                                    'title'    => esc_html__('Cubic Capacity Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_advanced_search_form', '=', '1'],
                                                ],
                                                [
                                                    'id'      => 'enable_saved_search',
                                                    'title'   => esc_html__('Saved Search', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                        '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'    => 'amotos_search_car_layout',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Layout Search Result', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'search_car_layout_style',
                                                    'type'    => 'button_set',
                                                    'title'   => esc_html__('Layout Style', 'auto-moto-stock'),
                                                    'options' => [
                                                        'car-grid' => esc_html__('Grid', 'auto-moto-stock'),
                                                        'car-list' => esc_html__('List', 'auto-moto-stock'),
                                                    ],
                                                    'default' => 'car-grid',
                                                ],
                                                [
                                                    'id'       => 'search_car_items_amount',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Items Amount', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enter number for items amount vehicle show in search page', 'auto-moto-stock'),
                                                    'default'  => '12',
                                                ],
                                                [
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 330x180 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                    'id'       => 'search_car_image_size',
                                                    'default'  => amotos_get_loop_car_image_size_default(),
                                                ],
                                                [
                                                    'id'       => 'search_car_columns',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Columns', 'auto-moto-stock'),
                                                    'options'  => [
                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '3',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],

                                                [
                                                    'id'       => 'search_car_columns_gap',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Columns Gap', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Select columns gap between vehicles for page search', 'auto-moto-stock'),
                                                    'options'  => [
                                                        'col-gap-0'  => esc_html__('0px', 'auto-moto-stock'),
                                                        'col-gap-10' => esc_html__('10px', 'auto-moto-stock'),
                                                        'col-gap-20' => esc_html__('20px', 'auto-moto-stock'),
                                                        'col-gap-30' => esc_html__('30px', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => 'col-gap-0',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],

                                                /*RESPONSIVE*/
                                                [
                                                    'id'       => 'search_car_items_md',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Items Desktop Small', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                    'options'  => [
                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '3',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'search_car_items_sm',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Items Tablet', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                    'options'  => [
                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '2',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'search_car_items_xs',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Items Tablet Small', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                    'options'  => [
                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],
                                                [
                                                    'id'       => 'search_car_items_mb',
                                                    'type'     => 'select',
                                                    'title'    => esc_html__('Items Mobile', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                    'options'  => [
                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '1',
                                                    'required' => [
                                                        'search_car_layout_style',
                                                        'in',
                                                        ['car-grid'],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_search_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function payment_option()
                        {
                            return apply_filters('amotos_register_option_payment', [
                                'id'     => 'amotos_payment_option',
                                'title'  => esc_html__('Payment & Submission', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-cart',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_payment_top', []),
                                    apply_filters('amotos_register_option_payment_main', [
                                        [
                                            'id'       => 'paid_submission_type',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Paid Submission Type', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'options'  => [
                                                'no'          => esc_html__('Free Submit', 'auto-moto-stock'),
                                                'per_listing' => esc_html__('Pay Per Listing', 'auto-moto-stock'),
                                                'per_package' => esc_html__('Pay Per Package', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'no',
                                        ],
                                        [
                                            'id'       => 'price_per_listing',
                                            'type'     => 'text',
                                            'required' => ['paid_submission_type', '=', 'per_listing'],
                                            'title'    => esc_html__('Price Per Listing Submission', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('0 as Free Submit', 'auto-moto-stock'),
                                            'default'  => '0',
                                        ],
                                        [
                                            'id'       => 'price_featured_listing',
                                            'type'     => 'text',
                                            'required' => ['paid_submission_type', '=', 'per_listing'],
                                            'title'    => esc_html__('Price To Make Listing Featured', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('0 as Free', 'auto-moto-stock'),
                                            'default'  => '0',
                                        ],

                                        [
                                            'id'       => 'per_listing_expire_days',
                                            'title'    => esc_html__('Expire Days', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Enable set single listing expire days', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                            'required' => ['paid_submission_type', '=', 'per_listing'],
                                        ],
                                        [
                                            'id'       => 'number_expire_days',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Number of Expire Days', 'auto-moto-stock'),
                                            'default'  => '30',
                                            'required' => [
                                                ['per_listing_expire_days', '=', '1'],
                                                ['paid_submission_type', '=', 'per_listing'],
                                            ],
                                        ],
                                        [
                                            'id'        => 'payment_terms_condition',
                                            'title'     => esc_html__('Terms & Conditions', 'auto-moto-stock'),
                                            'subtitle'  => esc_html__('Select terms & conditions page', 'auto-moto-stock'),
                                            'type'      => 'select',
                                            'data'      => 'page',
                                            'data_args' => [
                                                'numberposts' => -1,
                                            ],
                                        ],
                                        [
                                            'id'       => 'currency_code',
                                            'type'     => 'text',
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                            'title'    => esc_html__('Currency Code', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Provide the currency code that you want to use. Ex. USD', 'auto-moto-stock'),
                                            'default'  => 'USD',
                                        ],
                                        [
                                            'id'       => 'amotos_paypal',
                                            'type'     => 'info',
                                            'style'    => 'info',
                                            'title'    => esc_html__('PayPal Setting', 'auto-moto-stock'),
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'enable_paypal',
                                            'title'    => esc_html__('Enable PayPal', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'paypal_api',
                                            'type'     => 'select',
                                            'required' => [
                                                ['enable_paypal', '=', '1'],
                                                ['paid_submission_type', '!=', 'no'],
                                            ],
                                            'title'    => esc_html__('PayPal Api', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Sandbox = test API. LIVE = real payments API', 'auto-moto-stock'),
                                            'desc'     => esc_html__('Update PayPal settings according to API type selection', 'auto-moto-stock'),
                                            'options'  => [
                                                'sandbox' => esc_html__('Sandbox', 'auto-moto-stock'),
                                                'live'    => esc_html__('Live', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'sandbox',
                                        ],
                                        [
                                            'id'       => 'paypal_client_id',
                                            'type'     => 'text',
                                            'required' => [
                                                ['enable_paypal', '=', '1'],
                                                ['paid_submission_type', '!=', 'no'],
                                            ],
                                            'title'    => esc_html__('PayPal Client ID', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'paypal_client_secret_key',
                                            'type'     => 'text',
                                            'required' => [
                                                ['enable_paypal', '=', '1'],
                                                ['paid_submission_type', '!=', 'no'],
                                            ],
                                            'title'    => esc_html__('PayPal Client Secret Key', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'default'  => '',
                                        ],

                                        [
                                            'id'       => 'amotos_stripe',
                                            'type'     => 'info',
                                            'style'    => 'info',
                                            'title'    => esc_html__('Stripe Setting', 'auto-moto-stock'),
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'enable_stripe',
                                            'title'    => esc_html__('Enable Stripe', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'stripe_secret_key',
                                            'type'     => 'text',
                                            'required' => [
                                                ['enable_stripe', '=', '1'],
                                                ['paid_submission_type', '!=', 'no'],
                                            ],
                                            'title'    => esc_html__('Stripe Secret Key', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Info is taken from your account at https://dashboard.stripe.com/login', 'auto-moto-stock'),
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'stripe_publishable_key',
                                            'type'     => 'text',
                                            'required' => [
                                                ['enable_stripe', '=', '1'],
                                                ['paid_submission_type', '!=', 'no'],
                                            ],
                                            'title'    => esc_html__('Stripe Publishable Key', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Info is taken from your account at https://dashboard.stripe.com/login', 'auto-moto-stock'),
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'amotos_wire_transfer',
                                            'type'     => 'info',
                                            'style'    => 'info',
                                            'title'    => esc_html__('Wire Transfer Setting', 'auto-moto-stock'),
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'enable_wire_transfer',
                                            'title'    => esc_html__('Enable Wire Transfer', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                            'required' => ['paid_submission_type', '!=', 'no'],
                                        ],
                                        [
                                            'id'       => 'wire_transfer_info',
                                            'type'     => 'editor',
                                            'title'    => esc_html__('Wire Transfer Information', 'auto-moto-stock'),
                                            'required' => ['enable_wire_transfer', '=', '1'],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_payment_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function payment_complete_option()
                        {
                            return apply_filters('amotos_register_option_payment_complete', [
                                'id'     => 'amotos_payment_complete_option',
                                'title'  => esc_html__('Payment Complete', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-money',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_payment_complete_top', []),
                                    apply_filters('amotos_register_option_payment_complete_main', [
                                        [
                                            'id'    => 'amotos_thankyou',
                                            'type'  => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Note after payment via PayPal or Stripe', 'auto-moto-stock'),
                                        ],
                                        [
                                            'id'      => 'thankyou_title',
                                            'type'    => 'text',
                                            'title'   => esc_html__('Title', 'auto-moto-stock'),
                                            'default' => esc_html__('Thank you for your purchase', 'auto-moto-stock'),
                                        ],
                                        [
                                            'id'      => 'thankyou_content',
                                            'title'   => esc_html__('Content', 'auto-moto-stock'),
                                            'type'    => 'editor',
                                            'default' => '',
                                        ],
                                        [
                                            'id'    => 'amotos_thankyou_wire_transfer',
                                            'type'  => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Note after payment via Wire Transfer', 'auto-moto-stock'),
                                        ],
                                        [
                                            'id'      => 'thankyou_title_wire_transfer',
                                            'type'    => 'text',
                                            'title'   => esc_html__('Title', 'auto-moto-stock'),
                                            'default' => esc_html__('Thank you for your purchase', 'auto-moto-stock'),
                                        ],
                                        [
                                            'id'      => 'thankyou_content_wire_transfer',
                                            'title'   => esc_html__('Content', 'auto-moto-stock'),
                                            'type'    => 'editor',
                                            'default' => esc_html__('Make your payment directly into our bank account. Please use your Order ID as payment reference', 'auto-moto-stock'),
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_payment_complete_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function invoices_option()
                        {
                            return apply_filters('amotos_register_option_invoices', [
                                'id'     => 'amotos_invoices_option',
                                'title'  => esc_html__('Invoices', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-clipboard',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_invoices_top', []),
                                    apply_filters('amotos_register_option_invoices_main', [
                                        [
                                            'id'      => 'company_name',
                                            'type'    => 'text',
                                            'title'   => esc_html__('Company Name', 'auto-moto-stock'),
                                            'default' => '',
                                        ],
                                        [
                                            'id'      => 'company_address',
                                            'type'    => 'textarea',
                                            'title'   => esc_html__('Company Address', 'auto-moto-stock'),
                                            'default' => '',
                                        ],
                                        [
                                            'id'      => 'company_phone',
                                            'type'    => 'text',
                                            'title'   => esc_html__('Company Phone', 'auto-moto-stock'),
                                            'default' => '',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_invoices_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function favorite_option()
                        {
                            return apply_filters('amotos_register_option_favorite', [
                                'id'     => 'amotos_favorite_option',
                                'title'  => esc_html__('Favorite', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-heart',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_favorite_top', []),
                                    apply_filters('amotos_register_option_favorite_main', [
                                        [
                                            'id'      => 'enable_favorite_car',
                                            'title'   => esc_html__('Enable Favorite Vehicles', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_favorite_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function social_share_option()
                        {
                            return apply_filters('amotos_register_option_social_share', [
                                'id'     => 'amotos_social_share_option',
                                'title'  => esc_html__('Social Share', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-share',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_social_share_top', []),
                                    apply_filters('amotos_register_option_social_share_main', [
                                        [
                                            'id'      => 'enable_social_share',
                                            'title'   => esc_html__('Enable Social Share', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '0',
                                        ],
                                        [
                                            'title'        => esc_html__('Social Share', 'auto-moto-stock'),
                                            'id'           => 'social_sharing',
                                            'type'         => 'checkbox_list',
                                            'value_inline' => false,
                                            'subtitle'     => esc_html__('Show Social Share in single vehicle', 'auto-moto-stock'),

                                            // Must provide key => value pairs for multi checkbox options
                                            'options'      => [
                                                'facebook'  => esc_html__('Facebook', 'auto-moto-stock'),
                                                'twitter'   => esc_html__('Twitter', 'auto-moto-stock'),
                                                'linkedin'  => esc_html__('Linkedin', 'auto-moto-stock'),
                                                'tumblr'    => esc_html__('Tumblr', 'auto-moto-stock'),
                                                'pinterest' => esc_html__('Pinterest', 'auto-moto-stock'),
                                                'whatsup'   => esc_html__('WhatsApp', 'auto-moto-stock'),
                                            ],

                                            // See how default has changed? you also don't need to specify opts that are 0.
                                            'default'      => [
                                                'facebook'  => '1',
                                                'twitter'   => '1',
                                                'linkedin'  => '1',
                                                'tumblr'    => '1',
                                                'pinterest' => '1',
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_social_share_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function print_option()
                        {
                            return apply_filters('amotos_register_option_print', [
                                'id'     => 'amotos_print_option',
                                'title'  => esc_html__('Print', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-media-document',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_print_top', []),
                                    apply_filters('amotos_register_option_print_main', [
                                        [
                                            'id'      => 'enable_print_car',
                                            'title'   => esc_html__('Enable Print Vehicle', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                        [
                                            'id'      => 'enable_print_invoice',
                                            'title'   => esc_html__('Enable Print Invoice', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                        [
                                            'id'       => 'print_logo',
                                            'type'     => 'image',
                                            'url'      => true,
                                            'title'    => esc_html__('Print Logo', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Upload logo for Print pages', 'auto-moto-stock'),
                                            'default'  => '',
                                        ],
                                        [
                                            'type'     => 'text',
                                            'title'    => esc_html__('Print Logo Size', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Enter print logo size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 100x100, 200x100, 200x200 (Not Include Unit, Space))', 'auto-moto-stock'),
                                            'id'       => 'print_logo_size',
                                            'default'  => '200x100',
                                            'required' => ['print_logo[id]', '!=', ''],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_print_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function nearby_places_option()
                        {
                            return apply_filters('amotos_register_option_nearby_places', [
                                'id'     => 'amotos_nearby_places_option',
                                'title'  => esc_html__('Nearby Places', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-location-alt',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_nearby_places_top', []),
                                    apply_filters('amotos_register_option_nearby_places_main', [
                                        [
                                            'id'       => 'enable_nearby_places',
                                            'title'    => esc_html__('Nearby Places', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Enable Nearby Places on single vehicle page?', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                        ],
                                        [
                                            'id'       => 'nearby_places_rank_by',
                                            'title'    => esc_html__('Rank by', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Select options', 'auto-moto-stock'),
                                            'type'     => 'select',
                                            'options'  => [
                                                "default"  => esc_html__('Prominence', 'auto-moto-stock'),
                                                "distance" => esc_html__('Distance', 'auto-moto-stock'),
                                            ],
                                            'required' => ['enable_nearby_places', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'nearby_places_radius',
                                            'title'    => esc_html__('Radius', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Radius', 'auto-moto-stock'),
                                            'desc'     => esc_html__('Enter radius (meter)', 'auto-moto-stock'),
                                            'type'     => 'text',
                                            'default'  => '5000',
                                            'required' => [
                                                ['enable_nearby_places', '=', '1'],
                                            ],
                                        ],
                                        [
                                            'id'       => 'set_map_height',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Set Map Height', 'auto-moto-stock'),
                                            'default'  => '475',
                                            'required' => ['enable_nearby_places', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'nearby_places_distance_in',
                                            'title'    => esc_html__('Nearby places distance in', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Select options', 'auto-moto-stock'),
                                            'type'     => 'select',
                                            'options'  => [
                                                "m"  => esc_html__('Meter', 'auto-moto-stock'),
                                                "km" => esc_html__('Km', 'auto-moto-stock'),
                                                "mi" => esc_html__('Mile', 'auto-moto-stock'),
                                            ],
                                            'required' => ['enable_nearby_places', '=', '1'],
                                        ],
                                        [
                                            'id'       => 'nearby_places_field',
                                            'title'    => esc_html__('Nearby Places Field', 'auto-moto-stock'),
                                            'type'     => 'panel',
                                            'sort'     => false,
                                            'fields'   => [
                                                [
                                                    'type'   => 'row',
                                                    'col'    => '12',
                                                    'fields' => [
                                                        [
                                                            'id'       => 'nearby_places_select_field_type',
                                                            'title'    => esc_html__('Type Place', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Select options', 'auto-moto-stock'),
                                                            'type'     => 'select',
                                                            'options'  => [
                                                                "accounting"             => esc_html__('Accounting', 'auto-moto-stock'),
                                                                "airport"                => esc_html__('Airport', 'auto-moto-stock'),
                                                                "amusement_park"         => esc_html__('Amusement Park', 'auto-moto-stock'),
                                                                "aquarium"               => esc_html__('Aquarium', 'auto-moto-stock'),
                                                                "atm"                    => esc_html__('Atm', 'auto-moto-stock'),
                                                                "bakery"                 => esc_html__('Bakery', 'auto-moto-stock'),
                                                                "bank"                   => esc_html__('Bank', 'auto-moto-stock'),
                                                                "bar"                    => esc_html__('Bar', 'auto-moto-stock'),
                                                                "beauty_salon"           => esc_html__('Beauty Salon', 'auto-moto-stock'),
                                                                "bicycle_store"          => esc_html__('Bicycle Store', 'auto-moto-stock'),
                                                                "book_store"             => esc_html__('Book Store', 'auto-moto-stock'),
                                                                "bowling_alley"          => esc_html__('Bowling Alley', 'auto-moto-stock'),
                                                                "bus_station"            => esc_html__('Bus Station', 'auto-moto-stock'),
                                                                "cafe"                   => esc_html__('Cafe', 'auto-moto-stock'),
                                                                "campground"             => esc_html__('Campground', 'auto-moto-stock'),
                                                                "car_rental"             => esc_html__('Car Rental', 'auto-moto-stock'),
                                                                "car_repair"             => esc_html__('Car Repair', 'auto-moto-stock'),
                                                                "car_wash"               => esc_html__('Car Wash', 'auto-moto-stock'),
                                                                "casino"                 => esc_html__('Casino', 'auto-moto-stock'),
                                                                "cemetery"               => esc_html__('Cemetery', 'auto-moto-stock'),
                                                                "church"                 => esc_html__('Church', 'auto-moto-stock'),
                                                                "city_hall"              => esc_html__('City Center', 'auto-moto-stock'),
                                                                "clothing_store"         => esc_html__('Clothing Store', 'auto-moto-stock'),
                                                                "convenience_store"      => esc_html__('Convenience Store', 'auto-moto-stock'),
                                                                "courthouse"             => esc_html__('Courthouse', 'auto-moto-stock'),
                                                                "dentist"                => esc_html__('Dentist', 'auto-moto-stock'),
                                                                "department_store"       => esc_html__('Department Store', 'auto-moto-stock'),
                                                                "doctor"                 => esc_html__('Doctor', 'auto-moto-stock'),
                                                                "electrician"            => esc_html__('Electrician', 'auto-moto-stock'),
                                                                "electronics_store"      => esc_html__('Electronics Store', 'auto-moto-stock'),
                                                                "embassy"                => esc_html__('Embassy', 'auto-moto-stock'),
                                                                "finance"                => esc_html__('Finance', 'auto-moto-stock'),
                                                                "fire_station"           => esc_html__('Fire Station', 'auto-moto-stock'),
                                                                "florist"                => esc_html__('Florist', 'auto-moto-stock'),
                                                                "gas_station"            => esc_html__('Gas Station', 'auto-moto-stock'),
                                                                "grocery_or_supermarket" => esc_html__('Grocery', 'auto-moto-stock'),
                                                                "gym"                    => esc_html__('Gym', 'auto-moto-stock'),
                                                                "hair_care"              => esc_html__('Hair Care', 'auto-moto-stock'),
                                                                "hardware_store"         => esc_html__('Hardware Store', 'auto-moto-stock'),
                                                                "home_goods_store"       => esc_html__('Home Goods Store', 'auto-moto-stock'),
                                                                "hospital"               => esc_html__('Hospital', 'auto-moto-stock'),
                                                                "jewelry_store"          => esc_html__('Jewelry Store', 'auto-moto-stock'),
                                                                "laundry"                => esc_html__('Laundry', 'auto-moto-stock'),
                                                                "lawyer"                 => esc_html__('Lawyer', 'auto-moto-stock'),
                                                                "library"                => esc_html__('Library', 'auto-moto-stock'),
                                                                "lodging"                => esc_html__('Lodging', 'auto-moto-stock'),
                                                                "movie_theater"          => esc_html__('Movie Theater', 'auto-moto-stock'),
                                                                "moving_company"         => esc_html__('Moving Company', 'auto-moto-stock'),
                                                                "night_club"             => esc_html__('Night Club', 'auto-moto-stock'),
                                                                "park"                   => esc_html__('Park', 'auto-moto-stock'),
                                                                "pharmacy"               => esc_html__('Pharmacy', 'auto-moto-stock'),
                                                                "plumber"                => esc_html__('Plumber', 'auto-moto-stock'),
                                                                "police"                 => esc_html__('Police', 'auto-moto-stock'),
                                                                "post_office"            => esc_html__('Post Office', 'auto-moto-stock'),
                                                                "restaurant"             => esc_html__('Restaurant', 'auto-moto-stock'),
                                                                "school"                 => esc_html__('School', 'auto-moto-stock'),
                                                                "shopping_mall"          => esc_html__('Shopping Mall', 'auto-moto-stock'),
                                                                "spa"                    => esc_html__('Spa', 'auto-moto-stock'),
                                                                "stadium"                => esc_html__('Stadium', 'auto-moto-stock'),
                                                                "storage"                => esc_html__('Storage', 'auto-moto-stock'),
                                                                "store"                  => esc_html__('Store', 'auto-moto-stock'),
                                                                "subway_station"         => esc_html__('Subway Station', 'auto-moto-stock'),
                                                                "synagogue"              => esc_html__('Synagogue', 'auto-moto-stock'),
                                                                "taxi_stand"             => esc_html__('Taxi Stand', 'auto-moto-stock'),
                                                                "train_station"          => esc_html__('Train Station', 'auto-moto-stock'),
                                                                "travel_agency"          => esc_html__('Travel Agency', 'auto-moto-stock'),
                                                                "university"             => esc_html__('University', 'auto-moto-stock'),
                                                                "veterinary_care"        => esc_html__('Veterinary Care', 'auto-moto-stock'),
                                                                "zoo"                    => esc_html__('Zoo', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => 'gas_station',
                                                        ],
                                                        [
                                                            'id'          => 'nearby_places_field_label',
                                                            'title'       => esc_html__('Label Place', 'auto-moto-stock'),
                                                            'subtitle'    => esc_html__('Enter label place', 'auto-moto-stock'),
                                                            'type'        => 'text',
                                                            'default'     => 'Gas Station',
                                                            'panel_title' => true,
                                                        ],
                                                        [
                                                            'id'                 => 'nearby_places_field_icon',
                                                            'title'              => esc_html__('Image Icon Place', 'auto-moto-stock'),
                                                            'subtitle'           => esc_html__('Image field default options', 'auto-moto-stock'),
                                                            'type'               => 'image',
                                                            'images_select_text' => esc_html__('Select Nearbey places Images', 'auto-moto-stock'),
                                                            'default'            => AMOTOS_PLUGIN_URL . 'public/assets/images/gas-station-icon.png',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            'required' => ['enable_nearby_places', '=', '1'],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_nearby_places_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function walk_score_option()
                        {
                            return apply_filters('amotos_register_option_walk_score', [
                                'id'     => 'amotos_walk_score_option',
                                'title'  => esc_html__('Walk Score', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-location',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_walk_score_top', []),
                                    apply_filters('amotos_register_option_walk_score_main', [
                                        [
                                            'id'       => 'enable_walk_score',
                                            'title'    => esc_html__('Enable Walk Score', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Enable Walk Score on single vehicle page?', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                        ],
                                        [
                                            'id'       => 'walk_score_api_key',
                                            'type'     => 'text',
                                            'required' => ['enable_walk_score', '=', '1'],
                                            'title'    => esc_html__('Walk Score API Key', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'default'  => '',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_walk_score_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function google_map_directions_option()
                        {
                            return apply_filters('amotos_register_option_google_map_directions', [
                                'id'     => 'amotos_google_map_directions_option',
                                'title'  => esc_html__('Map Directions', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-redo',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_google_map_directions_top', []),
                                    apply_filters('amotos_register_option_google_map_directions_main', [
                                        [
                                            'id'      => 'enable_map_directions',
                                            'title'   => esc_html__('Enable Google Map Directions', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                        [
                                            'id'       => 'map_directions_distance_units',
                                            'type'     => 'select',
                                            'title'    => esc_html__('Distance Units', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'options'  => [
                                                'metre'     => esc_html__('Metre', 'auto-moto-stock'),
                                                'kilometre' => esc_html__('Kilometre', 'auto-moto-stock'),
                                                'mile'      => esc_html__('Mile', 'auto-moto-stock'),
                                            ],
                                            'default'  => 'no',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_google_map_directions_bottom', [])
                                ),
                            ]);
                        }

                        private function comments_reviews_option()
                        {
                            return apply_filters('amotos_register_option_comments_reviews', [
                                'id'     => 'amotos_comments_reviews_option',
                                'title'  => esc_html__('Comments & Reviews', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-admin-comments',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_comments_reviews_top', []),
                                    apply_filters('amotos_register_option_comments_reviews_main', [
                                        [
                                            'id'     => 'section_comments_reviews_car',
                                            'title'  => esc_html__('Vehicles', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'enable_comments_reviews_car',
                                                    'title'   => esc_html__('Enable Comments & Reviews For Vehicle', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '0' => esc_html__('Hide', 'auto-moto-stock'),
                                                        '1' => esc_html__('Comments Only', 'auto-moto-stock'),
                                                        '2' => esc_html__('Ratings & Reviews', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'review_car_approved_by_admin',
                                                    'title'    => esc_html__('Ratings & Reviews Approved by Admin?', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_comments_reviews_car', '=', ['2']],
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'     => 'section_comments_reviews_manager',
                                            'title'  => esc_html__('Manager', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'enable_comments_reviews_manager',
                                                    'title'   => esc_html__('Enable Comments & Reviews For Manager', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '0' => esc_html__('Hide', 'auto-moto-stock'),
                                                        '1' => esc_html__('Comments Only', 'auto-moto-stock'),
                                                        '2' => esc_html__('Ratings & Reviews', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '0',
                                                ],
                                                [
                                                    'id'       => 'review_manager_approved_by_admin',
                                                    'title'    => esc_html__('Ratings & Reviews Approved by Admin?', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_comments_reviews_manager', '=', ['2']],
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_comments_reviews_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function compare_option()
                        {
                            return apply_filters('amotos_register_option_compare', [
                                'id'     => 'amotos_compare_option',
                                'title'  => esc_html__('Compare', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-list-view',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_compare_top', []),
                                    apply_filters('amotos_register_option_compare_main', [
                                        [
                                            'id'      => 'enable_compare_cars',
                                            'title'   => esc_html__('Enable Compare Vehicles', 'auto-moto-stock'),
                                            'type'    => 'button_set',
                                            'options' => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default' => '1',
                                        ],
                                        [
                                            'id'           => 'hide_compare_fields',
                                            'title'        => esc_html__('Hide Compare Fields', 'auto-moto-stock'),
                                            'subtitle'     => esc_html__('Choose which fields you want to hide when compare vehicles?', 'auto-moto-stock'),
                                            'type'         => 'checkbox_list',
                                            'options'      => [
                                                'car_type'    => esc_html__('Type', 'auto-moto-stock'),
                                                'car_price'   => esc_html__('Price', 'auto-moto-stock'),
                                                'car_status'  => esc_html__('Status', 'auto-moto-stock'),
                                                'car_label'   => esc_html__('Label', 'auto-moto-stock'),
                                                'car_year'    => esc_html__('Year Vehicle', 'auto-moto-stock'),
                                                'car_mileage' => esc_html__('Mileage', 'auto-moto-stock'),
                                                'car_power'   => esc_html__('Power', 'auto-moto-stock'),
                                                'car_volume'  => esc_html__('Cubic Capacity', 'auto-moto-stock'),
                                                'car_owners'  => esc_html__('Owners', 'auto-moto-stock'),
                                                'car_doors'   => esc_html__('Doors', 'auto-moto-stock'),
                                                'car_seats'   => esc_html__('Seats', 'auto-moto-stock'),
                                            ],
                                            'value_inline' => false,
                                            'default'      => [],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_compare_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function google_map_option()
                        {
                            $allowed_html = [
                                'i'    => [
                                    'class' => [],
                                ],
                                'span' => [
                                    'class' => [],
                                ],
                                'a'    => [
                                    'href'   => [],
                                    'title'  => [],
                                    'target' => [],
                                ],
                            ];

                            return apply_filters('amotos_register_option_google_map', [
                                'id'     => 'amotos_google_map_option',
                                'title'  => esc_html__('Google Map', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-admin-site',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_google_map_top', []),
                                    apply_filters('amotos_register_option_google_map_main', [
                                        [
                                            'id'       => 'googlemap_ssl',
                                            'title'    => esc_html__('Google Maps SSL', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Use google maps with ssl', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '0',
                                        ],
                                        [
                                            'id'       => 'googlemap_api_key',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Google Maps API KEY', 'auto-moto-stock'),
                                            'desc'     => wp_kses(__('We strongly encourage you to get an APIs Console key and post the code in Theme Options. You can get it from <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">here</a>', 'auto-moto-stock'), $allowed_html),
                                            'subtitle' => esc_html__('Enter your google maps api key', 'auto-moto-stock'),
                                            'default'  => 'AIzaSyCLyuWY0RUhv7GxftSyI8Ka1VbeU7CTDls',
                                        ],
                                        [
                                            'id'      => 'googlemap_autocomplete_types',
                                            'title'   => esc_html__('Autocomplete returns results for', 'auto-moto-stock'),
                                            'desc'    => esc_html__('Determine what to search for using autocomplete.', 'auto-moto-stock'),
                                            'type'    => 'select',
                                            'options' => amotos_get_google_map_autocomplete_types(),
                                            'default' => 'geocode',
                                        ],
                                        [
                                            'id'         => 'googlemap_zoom_level',
                                            'type'       => 'slider',
                                            'title'      => esc_html__('Default Map Zoom', 'auto-moto-stock'),
                                            'js_options' => [
                                                'step' => 1,
                                                'min'  => 1,
                                                'max'  => 20,
                                            ],
                                            'default'    => '12',
                                        ],
                                        [
                                            'id'       => 'googlemap_pin_cluster',
                                            'title'    => esc_html__('Pin Cluster', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Use pin cluster on google map', 'auto-moto-stock'),
                                            'type'     => 'button_set',
                                            'options'  => [
                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                            ],
                                            'default'  => '1',
                                        ],
                                        [
                                            'id'       => 'googlemap_skin',
                                            'title'    => esc_html__('Google Map Skins', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Select skin for google map', 'auto-moto-stock'),
                                            'type'     => 'select',
                                            'options'  => amotos_get_google_map_skins(),
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'googlemap_style',
                                            'type'     => 'ace_editor',
                                            'title'    => esc_html__('Style for Google Map', 'auto-moto-stock'),
                                            /* translators: %1$s, %2$s is replaced with "string" */
                                            'subtitle' => sprintf(__('Use %1$s https://snazzymaps.com/ %2$s to create styles', 'auto-moto-stock'),
                                                '<a href="https://snazzymaps.com/" target="_blank">',
                                                '</a>'
                                            ),
                                            'default'  => '',
                                            'required' => ['googlemap_skin', '=', 'custom'],
                                        ],
                                        [
                                            'id'      => 'marker_icon',
                                            'type'    => 'image',
                                            'url'     => true,
                                            'title'   => esc_html__('Map Marker Icon', 'auto-moto-stock'),
                                            'default' => AMOTOS_PLUGIN_URL . 'public/assets/images/map-marker-icon.png',
                                        ],
                                        [
                                            'id'      => 'cluster_icon',
                                            'type'    => 'image',
                                            'url'     => true,
                                            'title'   => esc_html__('Map Cluster Icon', 'auto-moto-stock'),
                                            'default' => AMOTOS_PLUGIN_URL . 'public/assets/images/map-cluster-icon.png',
                                        ],
                                        [
                                            'id'       => 'googlemap_coordinate_default',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Default Coordinate', 'auto-moto-stock'),
                                            'desc'     => esc_html__('Example: 37.773972, -122.431297', 'auto-moto-stock'),
                                            'subtitle' => esc_html__('Enter your default coordinate when add new vehicle', 'auto-moto-stock'),
                                            'default'  => '',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_google_map_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         * https://perishablepress.com/integrating-google-no-captcha-recaptcha-wordpress-forms/
                         */
                        private function captcha_option()
                        {
                            return apply_filters('amotos_register_option_captcha', [
                                'id'     => 'amotos_captcha_option',
                                'title'  => esc_html__('Google Captcha', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-lock',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_captcha_top', []),
                                    apply_filters('amotos_register_option_captcha_main', [
                                        [
                                            'id'           => 'enable_captcha',
                                            'title'        => esc_html__('Google Captcha', 'auto-moto-stock'),
                                            /* translators: %1$s, %2$s is replaced with "string" */
                                            'subtitle'     => sprintf(__('Enable Google Captcha to submit forms. To get reCAPTCHA site key and secret key for your website by %1$s signing up here %2$s', 'auto-moto-stock'),
                                                '<a href="https://www.google.com/recaptcha/admin" target="_blank">',
                                                '</a>'),
                                            'type'         => 'checkbox_list',
                                            'options'      => [
                                                'login'           => esc_html__('Login', 'auto-moto-stock'),
                                                'register'        => esc_html__('Register', 'auto-moto-stock'),
                                                'reset_password'  => esc_html__('Reset Password', 'auto-moto-stock'),
                                                'contact_manager' => esc_html__('Contact Manager', 'auto-moto-stock'),
                                                'contact_dealer'  => esc_html__('Contact Dealer', 'auto-moto-stock'),
                                            ],
                                            'value_inline' => false,
                                            'default'      => [],
                                        ],
                                        [
                                            'id'       => 'captcha_site_key',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Site Key', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'default'  => '',
                                        ],
                                        [
                                            'id'       => 'captcha_secret_key',
                                            'type'     => 'text',
                                            'title'    => esc_html__('Secret Key', 'auto-moto-stock'),
                                            'subtitle' => '',
                                            'default'  => '',
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_captcha_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * Vehicle page option
                         * @return mixed
                         */
                        private function car_page_option()
                        {
                            return apply_filters('amotos_register_option_car_page', [
                                'id'     => 'amotos_car_page_option',
                                'title'  => esc_html__('Vehicle Page', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-car',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_car_page_top', []),
                                    apply_filters('amotos_register_option_car_page_main', [
                                        apply_filters('amotos_register_option_car_page_main_archive', [
                                            'id'     => 'amotos_car_archive',
                                            'title'  => esc_html__('Archive Vehicle', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'enable_archive_search_form',
                                                    'title'   => esc_html__('Enable Search Form', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Enabled', 'auto-moto-stock'),
                                                        '0' => esc_html__('Disabled', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '0',
                                                ],
                                                [
                                                    'id'           => 'hide_archive_search_fields',
                                                    'type'         => 'checkbox_list',
                                                    'title'        => esc_html__('Hide Advanced Search Fields', 'auto-moto-stock'),
                                                    'subtitle'     => esc_html__('Choose which fields you want to hide on advanced search page?', 'auto-moto-stock'),
                                                    'options'      => amotos_get_search_form_fields_config(),
                                                    'value_inline' => false,
                                                    'default'      => [
                                                        'car_country',
                                                        'car_state',
                                                        'car_neighborhood',
                                                        'car_label',
                                                        'car_doors',
                                                        'car_seats',
                                                    ],
                                                    'required'     => ['enable_archive_search_form', '=', ['1']],
                                                ],
                                                [
                                                    'id'       => 'archive_search_price_field_layout',
                                                    'title'    => esc_html__('Vehicle Price Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_archive_search_form', '=', ['1']],
                                                ],
                                                [
                                                    'id'       => 'archive_search_mileage_field_layout',
                                                    'title'    => esc_html__('Mileage Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_archive_search_form', '=', ['1']],
                                                ],
                                                [
                                                    'id'       => 'archive_search_power_field_layout',
                                                    'title'    => esc_html__('Power Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_archive_search_form', '=', ['1']],
                                                ],
                                                [
                                                    'id'       => 'archive_search_volume_field_layout',
                                                    'title'    => esc_html__('Cubic Capacity Field Layout', 'auto-moto-stock'),
                                                    'type'     => 'button_set',
                                                    'options'  => [
                                                        '0' => esc_html__('Dropdown', 'auto-moto-stock'),
                                                        '1' => esc_html__('Slider', 'auto-moto-stock'),
                                                    ],
                                                    'default'  => '0',
                                                    'required' => ['enable_archive_search_form', '=', ['1']],
                                                ],
                                                [
                                                    'id'     => 'section_archive_page_option',
                                                    'title'  => esc_html__('Page Options', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'      => 'archive_car_layout_style',
                                                            'type'    => 'button_set',
                                                            'title'   => esc_html__('Layout Style', 'auto-moto-stock'),
                                                            'default' => 'car-grid',
                                                            'options' => [
                                                                'car-grid' => esc_html__('Grid', 'auto-moto-stock'),
                                                                'car-list' => esc_html__('List', 'auto-moto-stock'),
                                                            ],
                                                        ],
                                                        [
                                                            'id'      => 'archive_car_items_amount',
                                                            'type'    => 'text',
                                                            'title'   => esc_html__('Items Amount', 'auto-moto-stock'),
                                                            'default' => 15,
                                                            'pattern' => '[0-9]*',
                                                        ],
                                                        [
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 330x180 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_image_size',
                                                            'default'  => amotos_get_loop_car_image_size_default(),
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Columns', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_columns',
                                                            'options'  => [
                                                                '2' => '2',
                                                                '3' => '3',
                                                                '4' => '4',
                                                                '5' => '5',
                                                                '6' => '6',
                                                            ],
                                                            'default'  => '3',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                '=',
                                                                ['car-grid'],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Columns Gap', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_columns_gap',
                                                            'options'  => [
                                                                'col-gap-0'  => '0px',
                                                                'col-gap-10' => '10px',
                                                                'col-gap-20' => '20px',
                                                                'col-gap-30' => '30px',
                                                            ],
                                                            'default'  => 'col-gap-30',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                '=',
                                                                ['car-grid'],
                                                            ],
                                                        ],

                                                        /* Responsive */
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Items Desktop Small', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_items_md',
                                                            'subtitle' => esc_html__('Browser Width < 1199', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '2' => '2',
                                                                '3' => '3',
                                                                '4' => '4',
                                                                '5' => '5',
                                                                '6' => '6',
                                                            ],
                                                            'default'  => '3',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                'in',
                                                                ['car-grid'],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Items Tablet', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_items_sm',
                                                            'subtitle' => esc_html__('Browser Width < 992', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '2' => '2',
                                                                '3' => '3',
                                                                '4' => '4',
                                                                '5' => '5',
                                                                '6' => '6',
                                                            ],
                                                            'default'  => '2',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                'in',
                                                                ['car-grid'],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Items Tablet Small', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_items_xs',
                                                            'subtitle' => esc_html__('Browser Width < 768', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => '1',
                                                                '2' => '2',
                                                                '3' => '3',
                                                                '4' => '4',
                                                                '5' => '5',
                                                                '6' => '6',
                                                            ],
                                                            'default'  => '1',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                'in',
                                                                ['car-grid'],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Items Mobile', 'auto-moto-stock'),
                                                            'id'       => 'archive_car_items_mb',
                                                            'subtitle' => esc_html__('Browser Width < 480', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => '1',
                                                                '2' => '2',
                                                                '3' => '3',
                                                                '4' => '4',
                                                                '5' => '5',
                                                                '6' => '6',
                                                            ],
                                                            'default'  => '1',
                                                            'required' => [
                                                                'archive_car_layout_style',
                                                                'in',
                                                                ['car-grid'],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ]),
                                        apply_filters('amotos_register_option_car_page_main_single', [
                                            'id'     => 'amotos_car_single',
                                            'title'  => esc_html__('Single Vehicle', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'      => 'hide_contact_information_if_not_login',
                                                    'title'   => esc_html__('Hide Contact Information if user not login', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '0',
                                                ],
                                                [
                                                    'id'      => 'hide_empty_stylings',
                                                    'title'   => esc_html__('Hide the empty stylings on the single vehicle page?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'      => 'enable_create_date',
                                                    'title'   => esc_html__('Show Create Date', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'      => 'enable_views_count',
                                                    'title'   => esc_html__('Show Views Count', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                            ],
                                        ]),
                                    ]),
                                    apply_filters('amotos_register_option_car_page_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * Manager page option
                         * @return mixed
                         */
                        private function manager_page_option()
                        {
                            return apply_filters('amotos_register_option_manager_page', [
                                'id'     => 'amotos_manager_page_option',
                                'title'  => esc_html__('Manager Page', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-businessman',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_manager_page_top', []),
                                    apply_filters('amotos_register_option_manager_page_main', [
                                        apply_filters('amotos_register_option_manager_page_main_archive', [
                                            'id'     => 'amotos_archive_manager',
                                            'title'  => esc_html__('Archive Manager', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'type'      => 'selectize',
                                                    'title'     => esc_html__('Dealer', 'auto-moto-stock'),
                                                    'id'        => 'manager_dealer',
                                                    'data'      => 'taxonomy',
                                                    'data_args' => [
                                                        'taxonomy' => 'dealer',
                                                        'args'     => ['hide_empty' => 0],
                                                    ],
                                                    'multiple'  => true,
                                                    'subtitle'  => esc_html__('Enter dealer by names to narrow output', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'archive_manager_layout_style',
                                                    'type'    => 'button_set',
                                                    'title'   => esc_html__('Layout Style', 'auto-moto-stock'),
                                                    'default' => 'manager-grid',
                                                    'options' => [
                                                        'manager-grid' => esc_html__('Grid', 'auto-moto-stock'),
                                                        'manager-list' => esc_html__('List', 'auto-moto-stock'),
                                                    ],
                                                ],
                                                [
                                                    'id'      => 'archive_manager_item_amount',
                                                    'type'    => 'text',
                                                    'title'   => esc_html__('Items Amount', 'auto-moto-stock'),
                                                    'default' => 12,
                                                    'pattern' => '[0-9]*',
                                                ],
                                                [
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                    'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                    'id'       => 'archive_manager_image_size',
                                                    'default'  => '270x340',
                                                ],
                                                [
                                                    'id'             => 'archive_manager_columns',
                                                    'title'          => esc_html__('Columns', 'auto-moto-stock'),
                                                    'type'           => 'group',
                                                    'toggle_default' => false,
                                                    'required'       => [
                                                        'archive_manager_layout_style',
                                                        '=',
                                                        ['manager-grid'],
                                                    ],
                                                    'fields'         => [
                                                        [
                                                            'id'       => 'archive_manager_column_lg',
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Column Desktop', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Browser Width >= 1199px', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => esc_html__('1', 'auto-moto-stock'),
                                                                '2' => esc_html__('2', 'auto-moto-stock'),
                                                                '3' => esc_html__('3', 'auto-moto-stock'),
                                                                '4' => esc_html__('4', 'auto-moto-stock'),
                                                                '5' => esc_html__('5', 'auto-moto-stock'),
                                                                '6' => esc_html__('6', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => '4',
                                                        ],
                                                        [
                                                            'id'       => 'archive_manager_column_md',
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Column Desktop Small', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => esc_html__('1', 'auto-moto-stock'),
                                                                '2' => esc_html__('2', 'auto-moto-stock'),
                                                                '3' => esc_html__('3', 'auto-moto-stock'),
                                                                '4' => esc_html__('4', 'auto-moto-stock'),
                                                                '5' => esc_html__('5', 'auto-moto-stock'),
                                                                '6' => esc_html__('6', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => '3',
                                                        ],
                                                        [
                                                            'id'       => 'archive_manager_column_sm',
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Column Tablet', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => esc_html__('1', 'auto-moto-stock'),
                                                                '2' => esc_html__('2', 'auto-moto-stock'),
                                                                '3' => esc_html__('3', 'auto-moto-stock'),
                                                                '4' => esc_html__('4', 'auto-moto-stock'),
                                                                '5' => esc_html__('5', 'auto-moto-stock'),
                                                                '6' => esc_html__('6', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => '2',
                                                        ],
                                                        [
                                                            'id'       => 'archive_manager_column_xs',
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Column Tablet Small', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => esc_html__('1', 'auto-moto-stock'),
                                                                '2' => esc_html__('2', 'auto-moto-stock'),
                                                                '3' => esc_html__('3', 'auto-moto-stock'),
                                                                '4' => esc_html__('4', 'auto-moto-stock'),
                                                                '5' => esc_html__('5', 'auto-moto-stock'),
                                                                '6' => esc_html__('6', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => '2',
                                                        ],
                                                        [
                                                            'id'       => 'archive_manager_column_mb',
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Column Mobile', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                            'options'  => [
                                                                '1' => esc_html__('1', 'auto-moto-stock'),
                                                                '2' => esc_html__('2', 'auto-moto-stock'),
                                                                '3' => esc_html__('3', 'auto-moto-stock'),
                                                                '4' => esc_html__('4', 'auto-moto-stock'),
                                                                '5' => esc_html__('5', 'auto-moto-stock'),
                                                                '6' => esc_html__('6', 'auto-moto-stock'),
                                                            ],
                                                            'default'  => '1',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ]),
                                        apply_filters('amotos_register_option_manager_page_main_single', [
                                            'id'     => 'amotos_single_manager',
                                            'title'  => esc_html__('Single Manager', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'     => 'amotos_car_of_manager',
                                                    'title'  => esc_html__('Vehicles of Manager', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'      => 'enable_car_of_manager',
                                                            'title'   => esc_html__('Show Vehicles of Manager', 'auto-moto-stock'),
                                                            'type'    => 'button_set',
                                                            'options' => [
                                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                                            ],
                                                            'default' => '1',
                                                        ],
                                                        [
                                                            'id'       => 'car_of_manager_layout_style',
                                                            'type'     => 'button_set',
                                                            'title'    => esc_html__('Layout Style', 'auto-moto-stock'),
                                                            'default'  => 'car-grid',
                                                            'options'  => [
                                                                'car-grid' => esc_html__('Grid', 'auto-moto-stock'),
                                                                'car-list' => esc_html__('List', 'auto-moto-stock'),
                                                            ],
                                                            'required' => ['enable_car_of_manager', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'       => 'car_of_manager_items_amount',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Items Amount', 'auto-moto-stock'),
                                                            'default'  => 6,
                                                            'pattern'  => '[0-9]*',
                                                            'required' => ['enable_car_of_manager', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'       => 'car_of_manager_image_size',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                            'default'  => amotos_get_loop_car_image_size_default(),
                                                            'required' => [
                                                                ['enable_car_of_manager', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'type'    => 'select',
                                                            'title'   => esc_html__('Columns Gap', 'auto-moto-stock'),
                                                            'id'      => 'car_of_manager_columns_gap',
                                                            'default' => 'col-gap-30',
                                                            'options' => [
                                                                'col-gap-0'  => '0px',
                                                                'col-gap-10' => '10px',
                                                                'col-gap-20' => '20px',
                                                                'col-gap-30' => '30px',
                                                            ],
                                                        ],
                                                        [
                                                            'id'           => 'car_of_manager_show_paging',
                                                            'title'        => esc_html__('Show Paging', 'auto-moto-stock'),
                                                            'type'         => 'checkbox_list',
                                                            'options'      => [
                                                                'show_paging_car_of_manager' => esc_html__('Yes', 'auto-moto-stock'),
                                                            ],
                                                            'value_inline' => false,
                                                            'default'      => [],
                                                            'required'     => ['enable_car_of_manager', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'             => 'car_of_manager_columns',
                                                            'title'          => esc_html__('Columns', 'auto-moto-stock'),
                                                            'type'           => 'group',
                                                            'toggle_default' => false,
                                                            'required'       => [
                                                                [
                                                                    'car_of_manager_layout_style',
                                                                    '=',
                                                                    ['car-grid'],
                                                                ],
                                                                ['enable_car_of_manager', '=', ['1']],
                                                            ],
                                                            'fields'         => [
                                                                [
                                                                    'id'       => 'car_of_manager_column_lg',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_manager_column_md',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_manager_column_sm',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_manager_column_xs',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_manager_column_mb',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Mobile', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '1',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'amotos_other_manager',
                                                    'title'  => esc_html__('Other Staff', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'      => 'enable_other_manager',
                                                            'title'   => esc_html__('Show Other Staff', 'auto-moto-stock'),
                                                            'type'    => 'button_set',
                                                            'options' => [
                                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                                            ],
                                                            'default' => '1',
                                                        ],
                                                        [
                                                            'id'       => 'other_manager_layout_style',
                                                            'type'     => 'button_set',
                                                            'title'    => esc_html__('Layout Style', 'auto-moto-stock'),
                                                            'default'  => 'manager-slider',
                                                            'options'  => [
                                                                'manager-slider' => esc_html__('Carousel', 'auto-moto-stock'),
                                                                'manager-grid'   => esc_html__('Grid', 'auto-moto-stock'),
                                                                'manager-list'   => esc_html__('List', 'auto-moto-stock'),
                                                            ],
                                                            'required' => ['enable_other_manager', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'       => 'other_staff_item_amount',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Items Amount', 'auto-moto-stock'),
                                                            'default'  => 12,
                                                            'required' => ['enable_other_manager', '=', ['1']],
                                                        ],
                                                        [
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                            'id'       => 'other_manager_image_size',
                                                            'default'  => '270x340',
                                                            'required' => [
                                                                ['enable_other_manager', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'id'           => 'other_manager_show_paging',
                                                            'title'        => esc_html__('Show Paging', 'auto-moto-stock'),
                                                            'type'         => 'checkbox_list',
                                                            'options'      => [
                                                                'show_paging_other_manager' => esc_html__('Yes', 'auto-moto-stock'),
                                                            ],
                                                            'value_inline' => false,
                                                            'default'      => [],
                                                            'required'     => [
                                                                [
                                                                    'other_manager_layout_style',
                                                                    'in',
                                                                    ['manager-grid', 'manager-list'],
                                                                ],
                                                                ['enable_other_manager', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'id'             => 'other_manager_columns',
                                                            'type'           => 'group',
                                                            'title'          => esc_html__('Columns', 'auto-moto-stock'),
                                                            'toggle_default' => false,
                                                            'required'       => [
                                                                [
                                                                    'other_manager_layout_style',
                                                                    'in',
                                                                    ['manager-grid', 'manager-slider'],
                                                                ],
                                                                ['enable_other_manager', '=', ['1']],
                                                            ],
                                                            'fields'         => [
                                                                [
                                                                    'id'       => 'other_manager_column_lg',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '4',
                                                                ],
                                                                [
                                                                    'id'       => 'other_manager_column_md',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'other_manager_column_sm',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'other_manager_column_xs',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'other_manager_column_mb',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Mobile', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '1',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ]),
                                    ]),
                                    apply_filters('amotos_register_option_manager_page_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * Dealer page option
                         * @return mixed
                         */
                        private function dealer_page_option()
                        {
                            return apply_filters('amotos_register_option_dealer_page', [
                                'id'     => 'amotos_dealer_page_option',
                                'title'  => esc_html__('Dealer Page', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-groups',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_dealer_page_top', []),
                                    apply_filters('amotos_register_option_dealer_page_main', [
                                        [
                                            'id'     => 'amotos_single_dealer',
                                            'title'  => esc_html__('Single Dealer', 'auto-moto-stock'),
                                            'type'   => 'group',
                                            'fields' => [
                                                [
                                                    'id'     => 'amotos_car_of_dealer',
                                                    'title'  => esc_html__('Vehicles of Dealer', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'      => 'enable_car_of_dealer',
                                                            'title'   => esc_html__('Show Vehicles of Dealer', 'auto-moto-stock'),
                                                            'type'    => 'button_set',
                                                            'options' => [
                                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                                            ],
                                                            'default' => '1',
                                                        ],
                                                        [
                                                            'id'       => 'car_of_dealer_layout_style',
                                                            'type'     => 'button_set',
                                                            'title'    => esc_html__('Layout Style', 'auto-moto-stock'),
                                                            'default'  => 'car-grid',
                                                            'options'  => [
                                                                'car-grid' => esc_html__('Grid', 'auto-moto-stock'),
                                                                'car-list' => esc_html__('List', 'auto-moto-stock'),
                                                            ],
                                                            'required' => ['enable_car_of_dealer', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'           => 'car_of_dealer_show_paging',
                                                            'title'        => esc_html__('Show Paging', 'auto-moto-stock'),
                                                            'type'         => 'checkbox_list',
                                                            'options'      => [
                                                                'show_paging_car_of_dealer' => esc_html__('Yes', 'auto-moto-stock'),
                                                            ],
                                                            'value_inline' => false,
                                                            'default'      => [],
                                                            'required'     => ['enable_car_of_dealer', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'       => 'car_of_dealer_items_amount',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Items Amount', 'auto-moto-stock'),
                                                            'default'  => 6,
                                                            'pattern'  => '[0-9]*',
                                                            'required' => [
                                                                ['enable_car_of_dealer', '=', ['1']],
                                                                [
                                                                    'car_of_dealer_show_paging',
                                                                    'contain',
                                                                    'show_paging_car_of_dealer',
                                                                ],
                                                            ],
                                                        ],
                                                        [
                                                            'id'       => 'car_of_dealer_image_size',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                            'default'  => amotos_get_loop_car_image_size_default(),
                                                            'required' => [
                                                                ['enable_car_of_dealer', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'select',
                                                            'title'    => esc_html__('Columns Gap', 'auto-moto-stock'),
                                                            'id'       => 'car_of_dealer_columns_gap',
                                                            'default'  => 'col-gap-30',
                                                            'options'  => [
                                                                'col-gap-0'  => '0px',
                                                                'col-gap-10' => '10px',
                                                                'col-gap-20' => '20px',
                                                                'col-gap-30' => '30px',
                                                            ],
                                                            'required' => [
                                                                [
                                                                    'car_of_dealer_layout_style',
                                                                    'in',
                                                                    ['car-grid'],
                                                                ],
                                                                ['enable_car_of_dealer', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'id'             => 'car_of_dealer_columns',
                                                            'title'          => esc_html__('Columns', 'auto-moto-stock'),
                                                            'type'           => 'group',
                                                            'toggle_default' => false,
                                                            'required'       => [
                                                                [
                                                                    'car_of_dealer_layout_style',
                                                                    'in',
                                                                    ['car-grid'],
                                                                ],
                                                                ['enable_car_of_dealer', '=', '1'],
                                                            ],
                                                            'fields'         => [
                                                                [
                                                                    'id'       => 'car_of_dealer_column_lg',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_dealer_column_md',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_dealer_column_sm',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_dealer_column_xs',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'car_of_dealer_column_mb',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Mobile', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '1',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'id'     => 'amotos_manager_of_dealer',
                                                    'title'  => esc_html__('Staff of Dealer', 'auto-moto-stock'),
                                                    'type'   => 'group',
                                                    'fields' => [
                                                        [
                                                            'id'      => 'enable_staff_of_dealer',
                                                            'title'   => esc_html__('Show Staff of Dealer', 'auto-moto-stock'),
                                                            'type'    => 'button_set',
                                                            'options' => [
                                                                '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                                '0' => esc_html__('No', 'auto-moto-stock'),
                                                            ],
                                                            'default' => '1',
                                                        ],
                                                        [
                                                            'id'       => 'staff_of_dealer_layout_style',
                                                            'type'     => 'button_set',
                                                            'title'    => esc_html__('Layout Style', 'auto-moto-stock'),
                                                            'default'  => 'manager-slider',
                                                            'options'  => [
                                                                'manager-slider' => esc_html__('Carousel', 'auto-moto-stock'),
                                                                'manager-grid'   => esc_html__('Grid', 'auto-moto-stock'),
                                                                'manager-list'   => esc_html__('List', 'auto-moto-stock'),
                                                            ],
                                                            'required' => ['enable_staff_of_dealer', '=', ['1']],
                                                        ],
                                                        [
                                                            'id'           => 'staff_of_dealer_show_paging',
                                                            'title'        => esc_html__('Show Paging', 'auto-moto-stock'),
                                                            'type'         => 'checkbox_list',
                                                            'options'      => [
                                                                'show_paging_staff_of_dealer' => esc_html__('Yes', 'auto-moto-stock'),
                                                            ],
                                                            'value_inline' => false,
                                                            'default'      => [],
                                                            'required'     => [
                                                                ['staff_of_dealer_layout_style', '!=', ['manager-slider']],
                                                                ['enable_staff_of_dealer', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'id'       => 'staff_of_dealer_item_amount',
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Items Amount', 'auto-moto-stock'),
                                                            'default'  => 12,
                                                            'required' => [
                                                                ['enable_staff_of_dealer', '=', ['1']],
                                                                [
                                                                    'staff_of_dealer_show_paging',
                                                                    'contain',
                                                                    'show_paging_staff_of_dealer',
                                                                ],
                                                            ],
                                                        ],
                                                        [
                                                            'type'     => 'text',
                                                            'title'    => esc_html__('Image Size', 'auto-moto-stock'),
                                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'auto-moto-stock'),
                                                            'id'       => 'staff_of_dealer_image_size',
                                                            'default'  => '270x340',
                                                            'required' => [
                                                                ['enable_staff_of_dealer', '=', ['1']],
                                                            ],
                                                        ],
                                                        [
                                                            'id'             => 'staff_of_dealer_columns',
                                                            'type'           => 'group',
                                                            'title'          => esc_html__('Columns', 'auto-moto-stock'),
                                                            'toggle_default' => false,
                                                            'required'       => [
                                                                [
                                                                    'staff_of_dealer_layout_style',
                                                                    'in',
                                                                    ['manager-grid', 'manager-slider'],
                                                                ],
                                                                ['enable_staff_of_dealer', '=', ['1']],
                                                            ],
                                                            'fields'         => [
                                                                [
                                                                    'id'       => 'staff_of_dealer_column_lg',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '4',
                                                                ],
                                                                [
                                                                    'id'       => 'staff_of_dealer_column_md',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Desktop Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '3',
                                                                ],
                                                                [
                                                                    'id'       => 'staff_of_dealer_column_sm',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 992px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'staff_of_dealer_column_xs',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Tablet Small', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 768px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '2',
                                                                ],
                                                                [
                                                                    'id'       => 'staff_of_dealer_column_mb',
                                                                    'type'     => 'select',
                                                                    'title'    => esc_html__('Column Mobile', 'auto-moto-stock'),
                                                                    'subtitle' => esc_html__('Browser Width < 480px', 'auto-moto-stock'),
                                                                    'options'  => [
                                                                        '1' => esc_html__('1', 'auto-moto-stock'),
                                                                        '2' => esc_html__('2', 'auto-moto-stock'),
                                                                        '3' => esc_html__('3', 'auto-moto-stock'),
                                                                        '4' => esc_html__('4', 'auto-moto-stock'),
                                                                        '5' => esc_html__('5', 'auto-moto-stock'),
                                                                        '6' => esc_html__('6', 'auto-moto-stock'),
                                                                    ],
                                                                    'default'  => '1',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_dealer_page_bottom', [])
                                ),
                            ]);
                        }

                        /**
                         * @return mixed|void
                         */
                        private function email_management_option()
                        {
                            return apply_filters('amotos_register_option_email_management', [
                                'id'     => 'amotos_email_management_option',
                                'title'  => esc_html__('Email Management', 'auto-moto-stock'),
                                'icon'   => 'dashicons dashicons-email-alt',
                                'fields' => array_merge(
                                    apply_filters('amotos_register_option_email_management_top', []),
                                    apply_filters('amotos_register_option_email_management_main', [
                                        [
                                            'id'             => 'email-new-user',
                                            'title'          => esc_html__('New Registered User', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_register_user',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_register_user_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_register_user',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your username and password on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_register_user_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_register_user',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'required' => ['mail_register_user_enable', '=', '1'],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    /* translators: 1: site URL, 2: username, 3: user password */
                                                    'default' => esc_html__(
    "Hi there,\n\nWelcome to %1\$website_url! You can login now using the credentials below:\n\nUsername: %2\$user_login_register\nPassword: %3\$user_pass_register\n\nIf you have any problems, please contact us.\n\nThank you!",
    'auto-moto-stock'
),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_register_user',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_register_user_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_register_user',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New User Registration', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_register_user_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_register_user',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'required' => ['admin_mail_register_user_enable', '=', '1'],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    /* translators: 1: site URL, 2: username, 3: user email */
                                                    'default' => esc_html__(
    "New user registration on %1\$website_url.\n\nUsername: %2\$user_login_register\n\nE-mail: %3\$user_email_register",
    'auto-moto-stock'
),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-approved-manager',
                                            'title'          => esc_html__('Approved Manager', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_approved_manager',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_approved_manager_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_approved_manager',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your manager account approved', 'auto-moto-stock'),
                                                    'required' => ['mail_approved_manager_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_approved_manager',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_approved_manager_enable', '=', '1'],
                                                    'default'  => esc_html__("Hi there,
Your manager account on %website_url has been approved.
Manager Name:%manager_name
Manager Url: %manager_url", 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_approved_manager',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_approved_manager_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_approved_manager',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Somebody register as manager', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_approved_manager_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_approved_manager',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_approved_manager_enable', '=', '1'],
                                                    'default'  => esc_html__('We received a request register as manager on  %website_url !
Please follow the instructions below to approve manager as soon as possible.
Manager Name:%manager_name
Manager Url: %manager_url', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-activated-package',
                                            'title'          => esc_html__('Activated Package', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_activated_package',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_activated_package_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_activated_package',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your purchase was activated', 'auto-moto-stock'),
                                                    'required' => ['mail_activated_package_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_activated_package',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_activated_package_enable', '=', '1'],
                                                    'default'  => esc_html__("Hi there,
Welcome to %website_url and thank you for purchasing a plan with us. We are excited you have chosen %website_name . %website_name is a great place to advertise and search vehicles.
You plan on  %website_url activated! You can now list your vehicles according to you plan.", 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],

                                        [
                                            'id'             => 'email-activated-listing',
                                            'title'          => esc_html__('Activated Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_activated_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_activated_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_activated_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your purchase was activated', 'auto-moto-stock'),
                                                    'required' => ['mail_activated_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_activated_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_activated_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi there,Your purchase on %website_url is activated! You should go and check it out.', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-approved-listing',
                                            'title'          => esc_html__('Approved Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_approved_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_approved_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_approved_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your listing approved', 'auto-moto-stock'),
                                                    'required' => ['mail_approved_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_approved_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_approved_listing_enable', '=', '1'],
                                                    'default'  => esc_html__("Hi there,
Your listing on %website_url has been approved.

Listing Title:%listing_title
Listing Url: %listing_url", 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-expired-listing',
                                            'title'          => esc_html__('Expired Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_expired_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_expired_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_expired_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your listing expired', 'auto-moto-stock'),
                                                    'required' => ['mail_expired_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_expired_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'required' => ['mail_expired_listing_enable', '=', '1'],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'default'  => esc_html__("Hi,
Your listing on %website_url has been expired.

Listing Title:%listing_title
Listing Url: %listing_url", 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-new-wire-transfer',
                                            'title'          => esc_html__('New Wire Transfer', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_new_wire_transfer',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_new_wire_transfer_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_new_wire_transfer',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('You ordered a new Wire Transfer', 'auto-moto-stock'),
                                                    'required' => ['mail_new_wire_transfer_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_new_wire_transfer',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_new_wire_transfer_enable', '=', '1'],
                                                    'default'  => esc_html__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting vehicles as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price.', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_new_wire_transfer',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_new_wire_transfer_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_new_wire_transfer',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Somebody ordered a new Wire Transfer', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_wire_transfer_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_new_wire_transfer',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_wire_transfer_enable', '=', '1'],
                                                    'default'  => esc_html__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting vehicles as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price.', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-paid-perlisting',
                                            'title'          => esc_html__('Paid Submission Per Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_paid_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_paid_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_paid_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your new listing on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_paid_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_paid_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_paid_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have submitted new listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_paid_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_paid_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_paid_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New paid submission on %website_url', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_paid_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_paid_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_paid_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have a new paid submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-featured-perlisting',
                                            'title'          => esc_html__('Featured Submission Per Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_featured_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_featured_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_featured_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New featured upgrade on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_featured_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_featured_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_featured_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have a new featured submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_featured_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_featured_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_featured_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New featured submission on %website_url', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_featured_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_featured_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_featured_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have a new featured submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-new-submission-listing',
                                            'title'          => esc_html__('New Submission Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_new_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_new_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_new_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your new listing on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_new_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_new_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_new_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have submitted new listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_new_submission_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_new_submission_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_new_submission_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New submission on %website_url', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_submission_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_new_submission_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_submission_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have a new submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-new-modification-listing',
                                            'title'          => esc_html__('New Modification Listing', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_user_mail_new_modification_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_new_modification_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_new_modification_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your new modification listing on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_new_modification_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_new_modification_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_new_modification_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have edited listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'    => 'amotos_admin_mail_new_modification_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_new_modification_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_new_modification_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('New modification on %website_url', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_modification_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_new_modification_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_new_modification_listing_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have a new modification on %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-expired-listing',
                                            'title'          => esc_html__('Resend For Approval', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_admin_mail_relist_listing',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('Admin Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'admin_mail_relist_listing_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_admin_mail_relist_listing',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Expired Listing sent for approval on %website_url', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_relist_listing_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'admin_mail_relist_listing',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['admin_mail_relist_listing_enable', '=', '1'],
                                                    /* translators: 1: submission URL, 2: submission title */
                                                    'default' => esc_html__(
    "Hi,\n\nA user has relist a new vehicle on %1\$submission_url! You should go and check it out.\n\nThis is the vehicle title: %2\$submission_title.",
    'auto-moto-stock'
),
                                                ],
                                            ],
                                        ],
                                        [
                                            'id'             => 'email-matching-saved-search',
                                            'title'          => esc_html__('Matching Submission With Saved Searches', 'auto-moto-stock'),
                                            'type'           => 'group',
                                            'toggle_default' => false,
                                            'fields'         => [
                                                [
                                                    'id'    => 'amotos_matching_saved_search',
                                                    'type'  => 'info',
                                                    'style' => 'info',
                                                    'title' => esc_html__('User Email', 'auto-moto-stock'),
                                                ],
                                                [
                                                    'id'      => 'mail_matching_saved_search_enable',
                                                    'title'   => esc_html__('Enable Send Mail?', 'auto-moto-stock'),
                                                    'type'    => 'button_set',
                                                    'options' => [
                                                        '1' => esc_html__('Yes', 'auto-moto-stock'),
                                                        '0' => esc_html__('No', 'auto-moto-stock'),
                                                    ],
                                                    'default' => '1',
                                                ],
                                                [
                                                    'id'       => 'subject_mail_matching_saved_search',
                                                    'type'     => 'text',
                                                    'title'    => esc_html__('Subject', 'auto-moto-stock'),
                                                    'default'  => esc_html__('Your new listing matching with your saved searches on %website_url', 'auto-moto-stock'),
                                                    'required' => ['mail_matching_saved_search_enable', '=', '1'],
                                                ],
                                                [
                                                    'id'       => 'mail_matching_saved_search',
                                                    'type'     => 'editor',
                                                    'args'     => [
                                                        'media_buttons' => true,
                                                        'quicktags'     => true,
                                                    ],
                                                    'title'    => esc_html__('Content', 'auto-moto-stock'),
                                                    'required' => ['mail_matching_saved_search_enable', '=', '1'],
                                                    'default'  => esc_html__('Hi,
You have new listings on %website_url matching with your saved searches:
%listings
If you do not wish to be notified anymore please login your dashboard and delete the saved search
Thank you!', 'auto-moto-stock'),
                                                ],
                                            ],
                                        ],
                                    ]),
                                    apply_filters('amotos_register_option_email_management_bottom', [])
                                ),
                            ]);
                        }

                        public function add_meta_car_status_order_number($term_id, $tt_id)
                        {
                            if (! isset($_POST[ 'car_status_order_number' ])) {
                                add_term_meta($term_id, 'car_status_order_number', 1);
                            }
                        }

                        public function squick_save_metabox_meta_field_keys($meta_field_keys, $post_id, $current_post_type)
                        {
                            if ($current_post_type === 'car') {
                                $meta_field_keys[ 'auto_moto_additional_styling_title' ] = [
                                    'type'        => 'text',
                                    'empty_value' => '',
                                ];
                                $meta_field_keys[ 'auto_moto_additional_styling_value' ] = [
                                    'type'        => 'text',
                                    'empty_value' => '',
                                ];
                            }

                            return $meta_field_keys;
                        }

                        public function get_car_required_fields()
                        {
                            $required_fields = [
                                'car_title'         => esc_html__('Title', 'auto-moto-stock'),
                                'car_type'          => esc_html__('Type', 'auto-moto-stock'),
                                'car_styling'       => esc_html__('Styling', 'auto-moto-stock'),
                                'car_status'        => esc_html__('Status', 'auto-moto-stock'),
                                'car_label'         => esc_html__('Label', 'auto-moto-stock'),
                                'car_price'         => esc_html__('Price', 'auto-moto-stock'),
                                'car_price_prefix'  => esc_html__('Before Price Label', 'auto-moto-stock'),
                                'car_price_postfix' => esc_html__('After Price Label', 'auto-moto-stock'),
                                'car_year'          => esc_html__('Year Vehicle', 'auto-moto-stock'),
                                'car_owners'        => esc_html__('Owners', 'auto-moto-stock'),
                                'car_mileage'       => esc_html__('Mileage', 'auto-moto-stock'),
                                'car_power'         => esc_html__('Power', 'auto-moto-stock'),
                                'car_volume'        => esc_html__('Cubic Capacity', 'auto-moto-stock'),
                                'car_doors'         => esc_html__('Doors', 'auto-moto-stock'),
                                'car_seats'         => esc_html__('Seats', 'auto-moto-stock'),
                                'car_map_address'   => esc_html__('Address', 'auto-moto-stock'),
                                'country'           => esc_html__('Country', 'auto-moto-stock'),
                                'state'             => esc_html__('Province/State', 'auto-moto-stock'),
                                'city'              => esc_html__('City/Town', 'auto-moto-stock'),
                                'neighborhood'      => esc_html__('Neighborhood', 'auto-moto-stock'),
                                'postal_code'       => esc_html__('Postal code', 'auto-moto-stock'),
                            ];
                            $meta_prefix       = AMOTOS_METABOX_PREFIX;
                            $additional_fields = amotos_get_option('additional_fields');
                            if ($additional_fields && is_array($additional_fields)) {
                                foreach ($additional_fields as $k => $field) {
                                    $id = isset($field[ 'id' ]) && ! empty($field[ 'id' ])
                                        ? $field[ 'id' ]
                                        : sanitize_title($field[ 'label' ]);

                                    if (in_array($id, [
                                        'car_price_short',
                                        'car_price_prefix',
                                        'car_price_postfix',
                                        'car_price_on_call',
                                        'car_mileage',
                                        'car_power',
                                        'car_volume',
                                        'car_doors',
                                        'car_seats',
                                        'car_owners',
                                        'car_year',
                                        'car_identity',
                                        'additional_stylings',
                                        'car_featured',
                                        'car_address',
                                        'car_zip',
                                        'car_location',
                                        'car_images',
                                        'car_attachments',
                                        'car_video_url',
                                        'car_video_image',
                                        'car_virtual_360_type',
                                        'car_virtual_360',
                                        'car_image_360',
                                        'manager_display_option',
                                        'car_manager',
                                        'car_other_contact_name',
                                        'car_other_contact_mail',
                                        'car_other_contact_phone',
                                        'car_other_contact_description',
                                        'private_note',
                                    ])) {
                                        $id = 'additional_detail__' . $id;
                                    }
                                    $id = $meta_prefix . $id;

                                    $required_fields[ $id ] = $field[ 'label' ];

                                }
                            }

                            return apply_filters('amotos_get_car_required_fields', $required_fields);

                        }

                    }
            }
