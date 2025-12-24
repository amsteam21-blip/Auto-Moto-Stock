<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    if (! class_exists('AMOTOS_Admin_Car')) {
        /**
         * Class AMOTOS_Admin_Car
         */
        class AMOTOS_Admin_Car
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
                unset($columns[ 'tags' ]);
                $columns[ 'cb' ]        = "<input type=\"checkbox\" />";
                $columns[ 'thumb' ]     = esc_html__('Image', 'auto-moto-stock');
                $columns[ 'title' ]     = esc_html__('Title', 'auto-moto-stock');
                $columns[ 'type' ]      = esc_html__('Type', 'auto-moto-stock');
                $columns[ 'status' ]    = esc_html__('Status', 'auto-moto-stock');
                $columns[ 'price' ]     = esc_html__('Price', 'auto-moto-stock');
                $columns[ 'featured' ]  = '<span data-tip="' . esc_attr__('Featured?', 'auto-moto-stock') . '" class="tips dashicons dashicons-star-filled"></span>';
                $columns[ 'author' ]    = esc_html__('Author', 'auto-moto-stock');
                $columns[ 'viewcount' ] = esc_html__('View Count', 'auto-moto-stock');
                $new_columns            = [];
                $custom_order           = [
                    'cb',
                    'thumb',
                    'title',
                    'type',
                    'status',
                    'price',
                    'featured',
                    'author',
                    'viewcount',
                    'date',
                ];
                foreach ($custom_order as $colname) {
                    $new_columns[ $colname ] = $columns[ $colname ];
                }

                return $new_columns;
            }

            /**
             * Display custom column for vehicles
             *
             * @param $column
             */
            public function display_custom_column($column)
            {
                global $post;
                switch ($column) {
                    case 'thumb':
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('thumbnail', [
                                'class' => 'attachment-thumbnail attachment-thumbnail-small',
                            ]);
                        } else {
                            echo '&ndash;';
                        }
                        break;
                    case 'type':
                        echo wp_kses_post(amotos_admin_taxonomy_terms($post->ID, 'car-type', 'car'));
                        break;
                    case 'status':
                        echo wp_kses_post(amotos_admin_taxonomy_terms($post->ID, 'car-status', 'car'));
                        break;
                    case 'price':
                        $price = get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'car_price', true);
                        if (! empty($price)) {
                            echo esc_html($price);
                        } else {
                            echo '&ndash;';
                        }
                        break;
                    case 'featured':

                        $featured = get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'car_featured', true);
                        if ($featured == 1) {
                            $featured_text = esc_html__('Featured', 'auto-moto-stock');
                            $featured_icon = 'tips accent-color dashicons dashicons-star-filled';
                        } else {
                            $featured_text = esc_html__('Not Featured', 'auto-moto-stock');
                            $featured_icon = 'tips dashicons dashicons-star-empty';
                        }

                        $url = wp_nonce_url(admin_url('admin-ajax.php?action=amotos_admin_featured_car&id=' . $post->ID), 'amotos_featured_car');
                        echo '<a href="' . esc_url($url) . '"><i data-tip="' . esc_attr($featured_text) . '" class="' . esc_attr($featured_icon) . '"></i></a>';

                        break;
                    case 'author':
                        echo '<a href="' . esc_url(add_query_arg('author', $post->post_author)) . '">' . get_the_author() . '</a>';
                        break;
                    case 'viewcount':
                        $views = get_post_meta($post->ID, AMOTOS_METABOX_PREFIX . 'car_views_count', true);
                        echo esc_html(amotos_get_format_number($views));
                        break;
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
                if ($post->post_type == 'car') {
                    if (in_array($post->post_status, [
                        'pending',
                        'expired',
                    ]) && current_user_can('publish_transport', $post->ID)) {
                        $actions[ 'car-approve' ] = '<a href="' . wp_nonce_url(add_query_arg('approve_listing', $post->ID), 'approve_listing') . '">' . esc_html__('Approve', 'auto-moto-stock') . '</a>';
                    }
                    if (in_array($post->post_status, [
                        'publish',
                        'pending',
                    ]) && current_user_can('publish_transport', $post->ID)) {
                        $actions[ 'car-expired' ] = '<a href="' . wp_nonce_url(add_query_arg('expire_listing', $post->ID), 'expire_listing') . '">' . esc_html__('Expire', 'auto-moto-stock') . '</a>';
                    }
                    if (in_array($post->post_status, ['publish']) && current_user_can('publish_transport', $post->ID)) {
                        $actions[ 'car-hidden' ] = '<a href="' . wp_nonce_url(add_query_arg('hidden_listing', $post->ID), 'hidden_listing') . '">' . esc_html__('Hide', 'auto-moto-stock') . '</a>';
                    }
                    if (in_array($post->post_status, ['hidden']) && current_user_can('publish_transport', $post->ID)) {
                        $actions[ 'car-show' ] = '<a href="' . wp_nonce_url(add_query_arg('show_listing', $post->ID), 'show_listing') . '">' . esc_html__('Show', 'auto-moto-stock') . '</a>';
                    }
                }

                return $actions;
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
                $columns[ 'price' ]     = 'price';
                $columns[ 'featured' ]  = 'featured';
                $columns[ 'author' ]    = 'author';
                $columns[ 'post_date' ] = 'post_date';

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
                if (isset($vars[ 'orderby' ]) && 'price' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'car_price',
                        'orderby'  => 'meta_value_num',
                    ]);
                }
                if (isset($vars[ 'orderby' ]) && 'featured' == $vars[ 'orderby' ]) {
                    $vars = array_merge($vars, [
                        'meta_key' => AMOTOS_METABOX_PREFIX . 'car_featured',
                        'orderby'  => 'meta_value_num',
                    ]);
                }

                return $vars;
            }

            /**
             * Modify vehicle slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_slug($existing_slug)
            {
                $car_url_slug = amotos_get_option('car_url_slug');
                if ($car_url_slug) {
                    return $car_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle type slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_type_slug($existing_slug)
            {
                $car_type_url_slug = amotos_get_option('car_type_url_slug');
                if ($car_type_url_slug) {
                    return $car_type_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle status slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_status_slug($existing_slug)
            {
                $car_status_url_slug = amotos_get_option('car_status_url_slug');
                if ($car_status_url_slug) {
                    return $car_status_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle styling slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_styling_slug($existing_slug)
            {
                $car_styling_url_slug = amotos_get_option('car_styling_url_slug');
                if ($car_styling_url_slug) {
                    return $car_styling_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle city slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_city_slug($existing_slug)
            {
                $car_city_url_slug = amotos_get_option('car_city_url_slug');
                if ($car_city_url_slug) {
                    return $car_city_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle neighborhood slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_neighborhood_slug($existing_slug)
            {
                $car_neighborhood_url_slug = amotos_get_option('car_neighborhood_url_slug');
                if ($car_neighborhood_url_slug) {
                    return $car_neighborhood_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle state slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_state_slug($existing_slug)
            {
                $car_state_url_slug = amotos_get_option('car_state_url_slug');
                if ($car_state_url_slug) {
                    return $car_state_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Modify vehicle lable slug
             *
             * @param $existing_slug
             *
             * @return string
             */
            public function modify_car_label_slug($existing_slug)
            {
                $car_label_url_slug = amotos_get_option('car_label_url_slug');
                if ($car_label_url_slug) {
                    return $car_label_url_slug;
                }

                return $existing_slug;
            }

            /**
             * Approve vehicle
             */
            /*public function approve_car()
            {
                if (! empty($_GET[ 'approve_listing' ]) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST[ '_wpnonce' ])), 'approve_listing') && current_user_can('publish_post', $_GET[ 'approve_listing' ])) {
                    $post_id      = absint(amotos_clean(wp_unslash($_GET[ 'approve_listing' ])));
                    $listing_data = [
                        'ID'          => $post_id,
                        'post_status' => 'publish',
                    ];
                    wp_update_post($listing_data);

                    $author_id  = get_post_field('post_author', $post_id);
                    $user       = get_user_by('id', $author_id);
                    $user_email = $user->user_email;

                    $args = [
                        'listing_title' => get_the_title($post_id),
                        'listing_url'   => get_permalink($post_id),
                    ];
                    amotos_send_email($user_email, 'mail_approved_listing', $args);
                    wp_safe_redirect(remove_query_arg('approve_listing', add_query_arg('approve_listing', $post_id, admin_url('edit.php?post_type=car'))));

                    exit;
                }
            }*/

                public function approve_car() {

    // Получаем параметры безопасным способом
    $approve_listing = filter_input( INPUT_GET, 'approve_listing', FILTER_SANITIZE_NUMBER_INT );
    $nonce           = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    // Проверяем, что параметр существует
    if (
        ! empty( $approve_listing )
        && ! empty( $nonce )
        && wp_verify_nonce( $nonce, 'approve_listing' )
        && current_user_can( 'publish_post', absint( $approve_listing ) )
    ) {

        $post_id = absint( $approve_listing );

        $listing_data = [
            'ID'          => $post_id,
            'post_status' => 'publish',
        ];

        wp_update_post( $listing_data );

        $author_id  = get_post_field( 'post_author', $post_id );
        $user       = get_user_by( 'id', $author_id );
        $user_email = $user->user_email;

        $args = [
            'listing_title' => get_the_title( $post_id ),
            'listing_url'   => get_permalink( $post_id ),
        ];

        amotos_send_email( $user_email, 'mail_approved_listing', $args );

        wp_safe_redirect(
            remove_query_arg(
                'approve_listing',
                add_query_arg(
                    'approve_listing',
                    $post_id,
                    admin_url( 'edit.php?post_type=car' )
                )
            )
        );

        exit;
    }
}


            /**
             * Expire vehicle
             */
            /*public function expire_car()
            {
                if (! empty($_GET[ 'expire_listing' ]) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST[ '_wpnonce' ])), 'expire_listing') && current_user_can('publish_post', $_GET[ 'expire_listing' ])) {
                    $post_id      = absint(amotos_clean(wp_unslash($_GET[ 'expire_listing' ])));
                    $listing_data = [
                        'ID'          => $post_id,
                        'post_status' => 'expired',
                    ];
                    wp_update_post($listing_data);

                    $author_id  = get_post_field('post_author', $post_id);
                    $user       = get_user_by('id', $author_id);
                    $user_email = $user->user_email;

                    $args = [
                        'listing_title' => get_the_title($post_id),
                        'listing_url'   => get_permalink($post_id),
                    ];
                    amotos_send_email($user_email, 'mail_expired_listing', $args);

                    wp_safe_redirect(remove_query_arg('expire_listing', add_query_arg('expire_listing', $post_id, admin_url('edit.php?post_type=car'))));

                    exit;
                }
            }*/

                public function expire_car() {

    // Получаем ID объявления из GET
    $expire_listing = filter_input( INPUT_GET, 'expire_listing', FILTER_SANITIZE_NUMBER_INT );

    // Получаем nonce
    $nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    // Проверяем, что параметры существуют
    if (
        ! empty( $expire_listing )
        && ! empty( $nonce )
        && wp_verify_nonce( $nonce, 'expire_listing' )
        && current_user_can( 'publish_post', absint( $expire_listing ) )
    ) {

        $post_id = absint( $expire_listing );

        // Обновляем статус объявления
        $listing_data = [
            'ID'          => $post_id,
            'post_status' => 'expired',
        ];

        wp_update_post( $listing_data );

        // Получаем автора
        $author_id  = get_post_field( 'post_author', $post_id );
        $user       = get_user_by( 'id', $author_id );
        $user_email = $user->user_email;

        // Данные для письма
        $args = [
            'listing_title' => get_the_title( $post_id ),
            'listing_url'   => get_permalink( $post_id ),
        ];

        // Отправляем письмо
        amotos_send_email( $user_email, 'mail_expired_listing', $args );

        // Делаем безопасный редирект
        $redirect_url = add_query_arg(
            'expire_listing',
            $post_id,
            admin_url( 'edit.php?post_type=car' )
        );

        $redirect_url = remove_query_arg( 'expire_listing', $redirect_url );

        wp_safe_redirect( esc_url_raw( $redirect_url ) );
        exit;
    }
}

            /**
             * Hidden vehicle
             */
            /*public function hidden_car()
            {
                if (! empty($_GET[ 'hidden_listing' ]) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST[ '_wpnonce' ])), 'hidden_listing') && current_user_can('publish_post', $_GET[ 'hidden_listing' ])) {
                    $post_id      = absint(amotos_clean(wp_unslash($_GET[ 'hidden_listing' ])));
                    $listing_data = [
                        'ID'          => $post_id,
                        'post_status' => 'hidden',
                    ];
                    wp_update_post($listing_data);
                    wp_safe_redirect(remove_query_arg('hidden_listing', add_query_arg('hidden_listing', $post_id, admin_url('edit.php?post_type=car'))));

                    exit;
                }
            }*/
            public function hidden_car() {

    // Получаем ID объявления из GET
    $hidden_listing = filter_input( INPUT_GET, 'hidden_listing', FILTER_SANITIZE_NUMBER_INT );

    // Получаем nonce
    $nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    // Проверяем, что параметры существуют
    if (
        ! empty( $hidden_listing )
        && ! empty( $nonce )
        && wp_verify_nonce( $nonce, 'hidden_listing' )
        && current_user_can( 'publish_post', absint( $hidden_listing ) )
    ) {

        $post_id = absint( $hidden_listing );

        // Обновляем статус объявления
        $listing_data = [
            'ID'          => $post_id,
            'post_status' => 'hidden',
        ];

        wp_update_post( $listing_data );

        // Делаем безопасный редирект
        $redirect_url = admin_url( 'edit.php?post_type=car' );

        wp_safe_redirect( esc_url_raw( $redirect_url ) );
        exit;
    }
}


            /**
             * Show vehicle
             */
            /*public function show_car()
            {
                if (! empty($_GET[ 'show_listing' ]) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST[ '_wpnonce' ])), 'show_listing') && current_user_can('publish_post', $_GET[ 'show_listing' ])) {
                    $post_id      = absint(amotos_clean(wp_unslash($_GET[ 'show_listing' ])));
                    $listing_data = [
                        'ID'          => $post_id,
                        'post_status' => 'publish',
                    ];
                    wp_update_post($listing_data);
                    wp_safe_redirect(remove_query_arg('show_listing', add_query_arg('show_listing', $post_id, admin_url('edit.php?post_type=car'))));

                    exit;
                }
            }*/
                public function show_car() {

    // Получаем ID объявления из GET
    $show_listing = filter_input( INPUT_GET, 'show_listing', FILTER_SANITIZE_NUMBER_INT );

    // Получаем nonce
    $nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    // Проверяем, что параметры существуют
    if (
        ! empty( $show_listing )
        && ! empty( $nonce )
        && wp_verify_nonce( $nonce, 'show_listing' )
        && current_user_can( 'publish_post', absint( $show_listing ) )
    ) {

        $post_id = absint( $show_listing );

        // Обновляем статус объявления
        $listing_data = [
            'ID'          => $post_id,
            'post_status' => 'publish',
        ];

        wp_update_post( $listing_data );

        // Делаем безопасный редирект
        $redirect_url = admin_url( 'edit.php?post_type=car' );

        wp_safe_redirect( esc_url_raw( $redirect_url ) );
        exit;
    }
}

            /**
             * Filtering vehicle control limitation
             */
            /*public function filter_restrict_manage_car()
            {
                global $typenow;
                $post_type    = 'car';
                $car_author   = isset($_GET[ 'car_author' ]) ? amotos_clean(wp_unslash($_GET[ 'car_author' ])) : '';
                $car_identity = isset($_GET[ 'car_identity' ]) ? amotos_clean(wp_unslash($_GET[ 'car_identity' ])) : '';
                if ($typenow == $post_type) {
                    $taxonomy_arr = ['car-status', 'car-type'];
                    foreach ($taxonomy_arr as $taxonomy) {
                        $selected      = isset($_GET[ $taxonomy ]) ? $_GET[ $taxonomy ] : '';
                        $info_taxonomy = get_taxonomy($taxonomy);
                        wp_dropdown_categories([
                            /* translators: %s: taxonomy label */
                            /*'show_option_all' => sprintf(esc_html__('All %s', 'auto-moto-stock'), $info_taxonomy->label),
                            'taxonomy'        => $taxonomy,
                            'name'            => $taxonomy,
                            'orderby'         => 'name',
                            'selected'        => $selected,
                            'show_count'      => true,
                            'hide_empty'      => false,
                        ]);
                    }
                ?>
				<input type="text" placeholder="<?php esc_attr_e('Author', 'auto-moto-stock'); ?>"
				       name="car_author" value="<?php echo esc_attr($car_author); ?>">
				<input type="text" placeholder="<?php esc_attr_e('Vehicle ID', 'auto-moto-stock'); ?>"
				       name="car_identity" value="<?php echo esc_attr($car_identity); ?>">
				<?php
                    }
                            }*/
                    public function filter_restrict_manage_car() {
    global $typenow;

    $post_type = 'car';

    // Получаем параметры из GET
    $car_author   = filter_input( INPUT_GET, 'car_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    $car_identity = filter_input( INPUT_GET, 'car_identity', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    if ( $typenow === $post_type ) {

        $taxonomy_arr = [ 'car-status', 'car-type' ];

        foreach ( $taxonomy_arr as $taxonomy ) {

            $selected = filter_input( INPUT_GET, $taxonomy, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

            $info_taxonomy = get_taxonomy( $taxonomy );

            wp_dropdown_categories(
                [
                    'show_option_all' => sprintf(
                        /* translators: %s: taxonomy label */
                        esc_html__( 'All %s', 'auto-moto-stock' ),
                        esc_html( $info_taxonomy->label )
                    ),
                    'taxonomy'   => $taxonomy,
                    'name'       => $taxonomy,
                    'orderby'    => 'name',
                    'selected'   => $selected,
                    'show_count' => true,
                    'hide_empty' => false,
                ]
            );
        }
        ?>

        <input type="text"
               placeholder="<?php esc_attr_e( 'Author', 'auto-moto-stock' ); ?>"
               name="car_author"
               value="<?php echo esc_attr( $car_author ); ?>">

        <input type="text"
               placeholder="<?php esc_attr_e( 'Vehicle ID', 'auto-moto-stock' ); ?>"
               name="car_identity"
               value="<?php echo esc_attr( $car_identity ); ?>">

        <?php
    }
}

                            /**
                             * Vehicle filter
                             *
                             * @param $query
                             */
                            /*public function car_filter($query)
                            {
                                global $pagenow;
                                $post_type = 'car';
                                $q_vars    = &$query->query_vars;
                                if ($pagenow == 'edit.php' && isset($q_vars[ 'post_type' ]) && $q_vars[ 'post_type' ] == $post_type) {
                                    $taxonomy_arr = ['car-status', 'car-type'];
                                    foreach ($taxonomy_arr as $taxonomy) {
                                        if (isset($q_vars[ $taxonomy ]) && is_numeric($q_vars[ $taxonomy ]) && $q_vars[ $taxonomy ] != 0) {
                                            $term                = get_term_by('id', $q_vars[ $taxonomy ], $taxonomy);
                                            $q_vars[ $taxonomy ] = $term->slug;
                                        }
                                    }
                                    if (isset($_GET[ 'car_author' ]) && $_GET[ 'car_author' ] != '') {
                                        $q_vars[ 'author_name' ] = amotos_clean(wp_unslash($_GET[ 'car_author' ]));
                                    }
                                    if (isset($_GET[ 'car_identity' ]) && $_GET[ 'car_identity' ] != '') {
                                        $q_vars[ 'meta_key' ]     = AMOTOS_METABOX_PREFIX . 'car_identity';
                                        $q_vars[ 'meta_value' ]   = amotos_clean(wp_unslash($_GET[ 'car_identity' ]));
                                        $q_vars[ 'meta_compare' ] = '=';
                                    }
                                }
                            }*/
                                public function car_filter( $query ) {
    global $pagenow;

    $post_type = 'car';
    $q_vars    = &$query->query_vars;

    if (
        $pagenow === 'edit.php'
        && isset( $q_vars['post_type'] )
        && $q_vars['post_type'] === $post_type
    ) {

        // Обрабатываем таксономии
        $taxonomy_arr = [ 'car-status', 'car-type' ];

        foreach ( $taxonomy_arr as $taxonomy ) {

            $taxonomy_value = filter_input( INPUT_GET, $taxonomy, FILTER_SANITIZE_NUMBER_INT );

            if ( ! empty( $taxonomy_value ) && absint( $taxonomy_value ) !== 0 ) {
                $term = get_term_by( 'id', absint( $taxonomy_value ), $taxonomy );

                if ( $term && ! is_wp_error( $term ) ) {
                    $q_vars[ $taxonomy ] = $term->slug;
                }
            }
        }

        // Фильтр по автору
        $car_author = filter_input( INPUT_GET, 'car_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

        if ( ! empty( $car_author ) ) {
            $q_vars['author_name'] = sanitize_text_field( $car_author );
        }

        // Фильтр по идентификатору авто
        $car_identity = filter_input( INPUT_GET, 'car_identity', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

        if ( ! empty( $car_identity ) ) {
            $q_vars['meta_key']     = AMOTOS_METABOX_PREFIX . 'car_identity';
            $q_vars['meta_value']   = sanitize_text_field( $car_identity );
            $q_vars['meta_compare'] = '=';
        }
    }
}

                            /**
		                     * @param $query
		                     */
                            public function post_types_admin_order($query)
                            {
                                if (is_admin()) {
                                    $post_type = isset($query->query[ 'post_type' ]) ? $query->query[ 'post_type' ] : '';
                                    if ($post_type == 'car') {
                                        if ($query->get('orderby') == '') {
                                            $query->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
                                        }
                                    }
                                }
                            }

                            /*public function featured()
                            {
                                check_ajax_referer('amotos_featured_car');

                                

                                $is_featured = get_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true);
                                if ($is_featured == 1) {
                                    $is_featured = 0;
                                } else {
                                    $is_featured = 1;
                                }
                                update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', $is_featured);
                                if ($is_featured == 1) {
                                    update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time('mysql'));
                                }
                                wp_safe_redirect(wp_get_referer() ? remove_query_arg([
                                    'trashed',
                                    'untrashed',
                                    'deleted',
                                    'ids',
                                ], wp_get_referer()) : admin_url('edit.php?post_type=car'));
                                die();
                            }*/
                                public function featured() {

    // Проверка nonce
    check_ajax_referer( 'amotos_featured_car' );

    // Получаем ID из GET
    $car_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
    $car_id = absint( $car_id );

    if ( empty( $car_id ) ) {
        wp_die( esc_html__( 'Invalid car ID', 'auto-moto-stock' ) );
    }

    // Проверка прав
    if ( ! current_user_can( 'edit_post', $car_id ) ) {
        wp_die( esc_html__( 'You do not have permission to modify this car.', 'auto-moto-stock' ) );
    }

    // Получаем текущее значение
    $is_featured = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true );

    // Переключаем значение
    $is_featured = ( $is_featured == 1 ) ? 0 : 1;

    update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', $is_featured );

    if ( $is_featured === 1 ) {
        update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time( 'mysql' ) );
    }

    // Безопасный редирект
    $referer = wp_get_referer();

    if ( $referer ) {
        $redirect_url = remove_query_arg(
            [ 'trashed', 'untrashed', 'deleted', 'ids' ],
            $referer
        );
    } else {
        $redirect_url = admin_url( 'edit.php?post_type=car' );
    }

    wp_safe_redirect( esc_url_raw( $redirect_url ) );
    exit;
       }
    }
}