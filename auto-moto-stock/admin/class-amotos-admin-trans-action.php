<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    if (! class_exists('AMOTOS_Admin_Trans_Action')) {
        /**
         * Class AMOTOS_Admin_Trans_Action
         */
        class AMOTOS_Admin_Trans_Action
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
                $columns[ 'cb' ]                          = "<input type=\"checkbox\" />";
                $columns[ 'title' ]                       = esc_html__('Action', 'auto-moto-stock');
                $columns[ 'trans_action_payment_method' ] = esc_html__('Payment Method', 'auto-moto-stock');
                $columns[ 'trans_action_payment_type' ]   = esc_html__('Payment Type', 'auto-moto-stock');
                $columns[ 'trans_action_price' ]          = esc_html__('Money', 'auto-moto-stock');
                $columns[ 'trans_action_user_id' ]        = esc_html__('Buyer', 'auto-moto-stock');
                $columns[ 'trans_action_status' ]         = esc_html__('Status', 'auto-moto-stock');
                $columns[ 'date' ]                        = esc_html__('Date', 'auto-moto-stock');
                $new_columns                              = [];
                $custom_order                             = [
                    'cb',
                    'title',
                    'trans_action_payment_method',
                    'trans_action_payment_type',
                    'trans_action_price',
                    'trans_action_user_id',
                    'trans_action_status',
                    'date',
                ];
                foreach ($custom_order as $colname) {
                    $new_columns[ $colname ] = $columns[ $colname ];
                }

                return $new_columns;
            }

            /**
             * Sortable columns
             *
             * @param $columns
             *
             * @return mixed
             */
            public function sortable_columns($columns)
            {
                $columns[ 'title' ]                       = 'title';
                $columns[ 'trans_action_payment_method' ] = 'trans_action_payment_method';
                $columns[ 'trans_action_payment_type' ]   = 'trans_action_payment_type';
                $columns[ 'trans_action_price' ]          = 'trans_action_price';
                $columns[ 'trans_action_status' ]         = 'trans_action_status';
                $columns[ 'date' ]                        = 'date';

                return $columns;
            }

            /**
             * @param $vars
             *
             * @return array
             */
            public function column_orderby($vars)
            {
                if (! is_admin()) {
                    return $vars;
                }
                if (isset($vars[ 'orderby' ]) && 'trans_action_payment_method' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'trans_action_payment_method',
                        'orderby'  => 'meta_value',
                    ]);
                }
                if (isset($vars[ 'orderby' ]) && 'trans_action_payment_type' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'trans_action_payment_type',
                        'orderby'  => 'meta_value',
                    ]);
                }
                if (isset($vars[ 'orderby' ]) && 'trans_action_price' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'trans_action_price',
                        'orderby'  => 'meta_value_num',
                    ]);
                }
                if (isset($vars[ 'orderby' ]) && 'trans_action_status' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'trans_action_status',
                        'orderby'  => 'meta_value_num',
                    ]);
                }

                return $vars;
            }

            /**
             * Display custom column for transaction
             *
             * @param $column
             */
            public function display_custom_column($column)
            {
                global $post;
                $trans_action_meta = get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'trans_action_meta', true);
                switch ($column) {
                    case 'trans_action_payment_method':
                        echo esc_html(AMOTOS_Invoice::get_invoice_payment_method($trans_action_meta[ 'trans_action_payment_method' ]));
                        break;
                    case 'trans_action_payment_type':
                        echo esc_html(AMOTOS_Invoice::get_invoice_payment_type($trans_action_meta[ 'trans_action_payment_type' ]));
                        break;
                    case 'trans_action_price':
                        echo esc_html($trans_action_meta[ 'trans_action_item_price' ]);
                        break;
                    case 'trans_action_user_id':
                        $user_info = get_userdata($trans_action_meta[ 'trans_action_user_id' ]);
                        if ($user_info) {
                            echo esc_html($user_info->display_name);
                        }
                        break;
                    case 'trans_action_status':
                        $trans_action_status = get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'trans_action_status', true);
                        if ($trans_action_status == 1) {
                            echo '<span class="amotos-label-blue">' . esc_html__('Succeeded', 'auto-moto-stock') . '</span>';
                        } else {
                            echo '<span class="amotos-label-red">' . esc_html__('Failed', 'auto-moto-stock') . '</span>';
                        }
                        break;
                }
            }

            /**
             * Modify trans_action slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_trans_action_slug($existing_slug)
            {
                $trans_action_url_slug = amotos_get_option('trans_action_url_slug');
                if ($trans_action_url_slug) {
                    return $trans_action_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Filter restrict manage invoice
             */
            public function filter_restrict_manage_trans_action()
            {
                global $typenow;
                $post_type = 'trans_action';

                if ($typenow == $post_type) {
                    //Invoice Status
                    $values = [
                        ''          => esc_html__('All Status', 'auto-moto-stock'),
                        'succeeded' => esc_html__('Succeeded', 'auto-moto-stock'),
                        'failed'    => esc_html__('Failed', 'auto-moto-stock'),
                    ];
                    $current_v = isset($_GET[ 'trans_action_status' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_status' ])) : '';
                ?>
				<select name="trans_action_status">
					<?php foreach ($values as $k => $v): ?>
						<option value="<?php echo esc_attr($k) ?>"<?php selected($k, $current_v)?>><?php echo esc_html($v) ?></option>
					<?php endforeach; ?>
					?>
				</select>
				<?php
                    //Payment method
                                    $values = [
                                        ''              => esc_html__('All Payments', 'auto-moto-stock'),
                                        'Paypal'        => esc_html__('PayPal', 'auto-moto-stock'),
                                        'Stripe'        => esc_html__('Stripe', 'auto-moto-stock'),
                                        'Wire_Transfer' => esc_html__('Wire Transfer', 'auto-moto-stock'),
                                        'Free_Package'  => esc_html__('Free Package', 'auto-moto-stock'),
                                    ];
                                    $current_v = isset($_GET[ 'trans_action_payment_method' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_payment_method' ])) : '';
                                ?>
				<select name="trans_action_payment_method">
					<?php foreach ($values as $k => $v): ?>
						<option value="<?php echo esc_attr($k) ?>"<?php selected($k, $current_v)?>><?php echo esc_html($v) ?></option>
					<?php endforeach; ?>

				</select>
				<?php
                    //Payment type
                                    $values = [
                                        ''                      => esc_html__('All Payment Types', 'auto-moto-stock'),
                                        'Package'               => esc_html__('Package', 'auto-moto-stock'),
                                        'Listing'               => esc_html__('Listing', 'auto-moto-stock'),
                                        'Upgrade_To_Featured'   => esc_html__('Upgrade to Featured', 'auto-moto-stock'),
                                        'Listing_With_Featured' => esc_html__('Listing with Featured', 'auto-moto-stock'),
                                    ];
                                    $current_v = isset($_GET[ 'trans_action_payment_type' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_payment_type' ])) : '';
                                ?>
				<select name="trans_action_payment_type">
					<?php foreach ($values as $k => $v): ?>
						<option value="<?php echo esc_attr($k) ?>"<?php selected($k, $current_v)?>><?php echo esc_html($v) ?></option>
					<?php endforeach; ?>

				</select>
                <?php
                    $trans_action_user = isset($_GET[ 'trans_action_user' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_user' ])) : '';
                                ?>
				<input type="text" placeholder="<?php esc_attr_e('Buyer', 'auto-moto-stock'); ?>"
				       name="trans_action_user" value="<?php echo esc_attr($trans_action_user); ?>">
			<?php }
                        }

                        /**
                         * Invoice filter
                         *
                         * @param $query
                         */
                        public function trans_action_filter($query)
                        {
                            global $pagenow;
                            $post_type  = 'trans_action';
                            $q_vars     = &$query->query_vars;
                            $filter_arr = [];
                            if ($pagenow == 'edit.php' && isset($q_vars[ 'post_type' ]) && $q_vars[ 'post_type' ] == $post_type) {
                                $trans_action_user = isset($_GET[ 'trans_action_user' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_user' ])) : '';
                                if ($trans_action_user !== '') {
                                    $user    = get_user_by('login', $trans_action_user);
                                    $user_id = -1;
                                    if ($user) {
                                        $user_id = $user->ID;
                                    }
                                    $filter_arr[  ] = [
                                        'key'     => AMOTOS_METABOX_PREFIX . 'trans_action_user_id',
                                        'value'   => $user_id,
                                        'compare' => 'IN',
                                    ];
                                }

                                $_trans_action_status = isset($_GET[ 'trans_action_status' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_status' ])) : '';

                                if ($_trans_action_status !== '') {
                                    $trans_action_status = 0;
                                    if ($_trans_action_status == 'succeeded') {
                                        $trans_action_status = 1;
                                    }
                                    $filter_arr[  ] = [
                                        'key'     => AMOTOS_METABOX_PREFIX . 'trans_action_status',
                                        'value'   => $trans_action_status,
                                        'compare' => '=',
                                    ];
                                }

                                $trans_action_payment_method = isset($_GET[ 'trans_action_payment_method' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_payment_method' ])) : '';

                                if ($trans_action_payment_method !== '') {
                                    $filter_arr[  ] = [
                                        'key'     => AMOTOS_METABOX_PREFIX . 'trans_action_payment_method',
                                        'value'   => $trans_action_payment_method,
                                        'compare' => '=',
                                    ];
                                }

                                $trans_action_payment_type = isset($_GET[ 'trans_action_payment_type' ]) ? amotos_clean(wp_unslash($_GET[ 'trans_action_payment_type' ])) : '';

                                if ($trans_action_payment_type !== '') {
                                    $filter_arr[  ] = [
                                        'key'     => AMOTOS_METABOX_PREFIX . 'trans_action_payment_type',
                                        'value'   => $trans_action_payment_type,
                                        'compare' => '=',
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
                            if ($post->post_type == 'trans_action') {
                                unset($actions[ 'view' ]);
                            }

                            return $actions;
                        }
                }
            }