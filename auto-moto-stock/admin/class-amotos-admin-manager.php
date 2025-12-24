<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (! class_exists('AMOTOS_Admin_Manager')) {
    /**
     * Class AMOTOS_Admin_Manager
     */
    class AMOTOS_Admin_Manager
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
            $columns[ 'cb' ]     = "<input type=\"checkbox\" />";
            $columns[ 'thumb' ]  = esc_html__('Avatar', 'auto-moto-stock');
            $columns[ 'title' ]  = esc_html__('Name', 'auto-moto-stock');
            $columns[ 'email' ]  = esc_html__('Email', 'auto-moto-stock');
            $columns[ 'mobile' ] = esc_html__('Mobile', 'auto-moto-stock');
            $columns[ 'dealer' ] = esc_html__('Dealer', 'auto-moto-stock');
            $new_columns         = [];
            $custom_order        = ['cb', 'thumb', 'title', 'email', 'mobile', 'dealer', 'date'];
            foreach ($custom_order as $colname) {
                $new_columns[ $colname ] = $columns[ $colname ];
            }

            return $new_columns;
        }

        /**
         * Display custom column for staff
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
                            'class' => '',
                        ]);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'email':
                    $email = get_post_meta(get_the_ID(), AMOTOS_METABOX_PREFIX . 'manager_email', true);

                    if (! empty($email)) {
                        echo esc_html($email);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'mobile':
                    $phone = get_post_meta(get_the_ID(), AMOTOS_METABOX_PREFIX . 'manager_mobile_number', true);

                    if (! empty($phone)) {
                        echo esc_html($phone);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'dealer':
                    $cate         = amotos_admin_taxonomy_terms($post->ID, 'dealer', 'manager');
                    $allowed_html = [
                        'a' => [
                            'href'   => [],
                            'title'  => [],
                            'target' => [],
                        ],
                    ];
                    if (! empty($cate)) {
                        echo wp_kses($cate, $allowed_html);
                    } else {
                        echo '&ndash;';
                    }
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
            if ($post->post_type == 'manager') {
                if (in_array($post->post_status, ['pending']) && current_user_can('publish_staff', $post->ID)) {
                    $actions[ 'manager-approve' ] = '<a href="' . wp_nonce_url(add_query_arg('approve_manager', $post->ID), 'approve_manager') . '">' . esc_html__('Approve', 'auto-moto-stock') . '</a>';
                }
            }

            return $actions;
        }

        /**
         * Modify manager slug
         *
         * @param $existing_slug
         *
         * @return string
         */
        public function modify_manager_slug($existing_slug)
        {
            $manager_url_slug = amotos_get_option('manager_url_slug');
            if ($manager_url_slug) {
                return $manager_url_slug;
            }

            return $existing_slug;
        }

        /**
         * @param $existing_slug
         *
         * @return string
         */
        public function modify_dealer_slug($existing_slug)
        {
            $dealer_url_slug = amotos_get_option('dealer_url_slug');
            if ($dealer_url_slug) {
                return $dealer_url_slug;
            }

            return $existing_slug;
        }

        /**
         * Modify author slug
         */
        public function modify_author_slug()
        {
            $author_url_slug = amotos_get_option('author_url_slug');
            if ($author_url_slug) {
                global $wp_rewrite;
                $wp_rewrite->author_base = $author_url_slug;
            }
        }

        /**
         * Save manager meta
         *
         * @param $post_id
         * @param $post
         */
        /*public function save_manager_meta($post_id, $post)
        {
            if (! is_object($post) || ! isset($post->post_type)) {
                return;
            }
            if ('manager' != $post->post_type) {
                return;
            }

            if (! isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_email' ])) {
                return;
            }

            $user_as_manager = amotos_get_option('user_as_manager', 1);
            if ($user_as_manager) {
                $manager_description    = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_description' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_description' ])) : '';*/

    public function save_manager_meta( $post_id, $post ) {

    // 1. Базовые проверки
    if ( ! is_object( $post ) || ! isset( $post->post_type ) ) {
        return;
    }

    if ( 'manager' !== $post->post_type ) {
        return;
    }

    // 2. Проверка nonce (ДО любого $_POST, кроме самого nonce)
    
    $nonce = isset( $_POST[ AMOTOS_METABOX_PREFIX . 'nonce' ] )
        ? sanitize_text_field(wp_unslash( $_POST[ AMOTOS_METABOX_PREFIX . 'nonce' ] ) )
        : '';

    if (
        empty( $nonce ) ||
        ! wp_verify_nonce( $nonce, 'amotos_save_manager_meta' )
    ) {
        return;
    }

    // 3. Capability
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // 4. Теперь можно безопасно читать остальные $_POST

    if ( ! isset( $_POST[ AMOTOS_METABOX_PREFIX . 'manager_email' ] ) ) {
        return;
    }

        $manager_email = sanitize_email( wp_unslash( $_POST[ AMOTOS_METABOX_PREFIX . 'manager_email' ] ) );

        $user_as_manager = amotos_get_option( 'user_as_manager', 1 );

            if ( $user_as_manager ) {
                $manager_description = isset( $_POST[ AMOTOS_METABOX_PREFIX . 'manager_description' ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ AMOTOS_METABOX_PREFIX . 'manager_description' ] ) ) : '';
                $manager_position       = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_position' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_position' ])) : '';
                $manager_email          = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_email' ]) ? sanitize_email(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_email' ])) : '';
                $manager_mobile_number  = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_mobile_number' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_mobile_number' ])) : '';
                $manager_fax_number     = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_fax_number' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_fax_number' ])) : '';
                $manager_company        = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_company' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_company' ])) : '';
                $manager_licenses       = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_licenses' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_licenses' ])) : '';
                $manager_office_number  = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_office_number' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_office_number' ])) : '';
                $manager_office_address = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_office_address' ]) ? sanitize_textarea_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_office_address' ])) : '';
                $manager_facebook_url   = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_facebook_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_facebook_url' ])) : '';
                $manager_twitter_url    = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_twitter_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_twitter_url' ])) : '';
                $manager_linkedin_url   = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_linkedin_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_linkedin_url' ])) : '';
                $manager_pinterest_url  = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_pinterest_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_pinterest_url' ])) : '';
                $manager_instagram_url  = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_instagram_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_instagram_url' ])) : '';
                $manager_skype          = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_skype' ]) ? sanitize_text_field(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_skype' ])) : '';
                $manager_youtube_url    = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_youtube_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_youtube_url' ])) : '';
                $manager_vimeo_url      = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_vimeo_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_vimeo_url' ])) : '';
                $manager_website_url    = isset($_POST[ AMOTOS_METABOX_PREFIX . 'manager_website_url' ]) ? sanitize_url(wp_unslash($_POST[ AMOTOS_METABOX_PREFIX . 'manager_website_url' ])) : '';

                $image_id = get_post_thumbnail_id($post_id);
                $full_img = wp_get_attachment_image_src($image_id, 'full');
                $user_id  = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', true);
                update_user_meta($user_id, 'aim', '/' . $full_img[ 0 ] . '/');
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_position', $manager_position);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_company', $manager_company);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_licenses', $manager_licenses);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_number', $manager_office_number);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_fax_number', $manager_fax_number);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_mobile_number', $manager_mobile_number);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_skype', $manager_skype);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_office_address', $manager_office_address);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_custom_picture', $full_img[ 0 ]);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_facebook_url', $manager_facebook_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_twitter_url', $manager_twitter_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $manager_linkedin_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $manager_vimeo_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_youtube_url', $manager_youtube_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $manager_pinterest_url);
                update_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'author_instagram_url', $manager_instagram_url);

                if (! empty($manager_description)) {
                    $args = [
                        'ID'          => $user_id,
                        'description' => $manager_description,
                    ];
                    wp_update_user($args);
                }
                if (! empty($manager_website_url)) {
                    $args = [
                        'ID'       => $user_id,
                        'user_url' => $manager_website_url,
                    ];
                    wp_update_user($args);
                }
                if (! email_exists($manager_email)) {
                    $args = [
                        'ID'         => $user_id,
                        'user_email' => $manager_email,
                    ];
                    wp_update_user($args);
                }
            }
        }

        /**
         * Approve Manager
         */
        public function approve_manager() {

    // 1. ID (GET → int)
    $approve_manager = isset( $_GET['approve_manager'] )
        ? absint( $_GET['approve_manager'] )
        : 0;

    // 2. Nonce (GET, без sanitize_text_field)
    $nonce = isset( $_GET['_wpnonce'] )
        ? sanitize_text_field(wp_unslash( $_GET['_wpnonce'] ) )
        : '';

    // 3. Проверки
    if (
        ! $approve_manager ||
        empty( $nonce ) ||
        ! wp_verify_nonce( $nonce, 'approve_manager' ) ||
        ! current_user_can( 'publish_post', $approve_manager )
    ) {
        return;
    }

    

        /*public function approve_manager()
        {

            $approve_manager = isset($_GET[ 'approve_manager' ]) ? absint(amotos_clean(wp_unslash($_GET[ 'approve_manager' ]))) : '';
            $_wpnonce        = isset($_REQUEST[ '_wpnonce' ]) ? amotos_clean(wp_unslash($_REQUEST[ '_wpnonce' ])) : '';
            if ($approve_manager !== '' && wp_verify_nonce($_wpnonce, 'approve_manager') && current_user_can('publish_post', $approve_manager))*/ {
                $listing_data = [
                    'ID'          => $approve_manager,
                    'post_status' => 'publish',
                ];
                wp_update_post($listing_data);

                $author_id  = get_post_field('post_author', $approve_manager);
                $user       = get_user_by('id', $author_id);
                $user_email = $user->user_email;

                $args = [
                    'manager_name' => get_the_title($approve_manager),
                    'manager_url'  => get_permalink($approve_manager),
                ];
                amotos_send_email($user_email, 'mail_approved_manager', $args);
                wp_safe_redirect(remove_query_arg('approve_manager', add_query_arg('approve_manager', $approve_manager, admin_url('edit.php?post_type=manager'))));
                
                exit;
            }
        }

        /**
         * Filter restrict manage manager
         */
        public function filter_restrict_manage_manager()
        {
            global $typenow;
            $post_type = 'manager';
            if ($typenow == $post_type) {
                $taxonomy      = 'dealer';
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $selected      = isset($_GET[ $taxonomy ]) ? sanitize_text_field(wp_unslash($_GET[ $taxonomy ])) : '';
                $info_taxonomy = get_taxonomy($taxonomy);
                wp_dropdown_categories([
                    /* translators: %s:  label of taxonomy */
                    'show_option_all' => sprintf(esc_html__('All %s', 'auto-moto-stock'), $info_taxonomy->label),
                    'taxonomy'        => $taxonomy,
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => false,
                ]);
            }
        }

        /**
         * Manager filter
         *
         * @param $query
         */
        public function manager_filter($query)
        {
            global $pagenow;
            $post_type = 'manager';
            $q_vars    = &$query->query_vars;
            if ($pagenow == 'edit.php' && isset($q_vars[ 'post_type' ]) && $q_vars[ 'post_type' ] == $post_type) {
                $taxonomy = 'dealer';
                if (isset($q_vars[ $taxonomy ]) && is_numeric($q_vars[ $taxonomy ]) && $q_vars[ $taxonomy ] != 0) {
                    $term                = get_term_by('id', $q_vars[ $taxonomy ], $taxonomy);
                    $q_vars[ $taxonomy ] = $term->slug;
                }
            }
        }

        /***
		 * @param $query
		 */
        public function post_types_admin_order($query)
        {
            if (is_admin()) {
                $post_type = isset($query->query[ 'post_type' ]) ? $query->query[ 'post_type' ] : '';
                if ($post_type == 'manager') {
                    if ($query->get('orderby') == '') {
                        $query->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
                    }
                }
            }
        }
    }
}
