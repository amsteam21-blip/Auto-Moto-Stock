<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }
    if (! class_exists('AMOTOS_Admin_Location')) {
        /**
         * Class AMOTOS_Admin
         */
        class AMOTOS_Admin_Location
        {
            /*
		 * Countries settings
		 */
            public function countries_create_menu()
            {
                add_submenu_page(
                    'edit.php?post_type=location',
                    esc_html__('Country', 'auto-moto-stock'),
                    esc_html__('Country', 'auto-moto-stock'),
                    'manage_auto_moto',
                    'countries_settings',
                    [$this, 'countries_settings_page']);
                add_submenu_page(
                    'edit.php?post_type=location',
                    esc_html__('Privince/State', 'auto-moto-stock'),
                    esc_html__('Province/State', 'auto-moto-stock'),
                    'manage_auto_moto',
                    'edit-tags.php?taxonomy=car-state&post_type=location');

                add_submenu_page(
                    'edit.php?post_type=location',
                    esc_html__('City/Town', 'auto-moto-stock'),
                    esc_html__('City/Town', 'auto-moto-stock'),
                    'manage_auto_moto',
                    'edit-tags.php?taxonomy=car-city&post_type=location');

                add_submenu_page(
                    'edit.php?post_type=location',
                    esc_html__('Neighborhood', 'auto-moto-stock'),
                    esc_html__('Neighborhood', 'auto-moto-stock'),
                    'manage_auto_moto',
                    'edit-tags.php?taxonomy=car-neighborhood&post_type=location');
            }

            public function countries_register_setting()
            {
                register_setting('countries-settings-group', 'country_list');
            }

            public function countries_settings_page()
        {?>
			<div class="wrap amotos-countries-settings">
				<h1><?php esc_html_e('Countries', 'auto-moto-stock'); ?></h1>
				<p><?php esc_html_e('Choose Country', 'auto-moto-stock'); ?></p>
				<form method="post" action="options.php">
					<?php settings_fields('countries-settings-group'); ?>
					<?php do_settings_sections('countries-settings-group'); ?>
					<?php
                        $countries_selected = get_option('country_list');
                                    $countries          = amotos_get_countries();
                                    foreach ($countries as $key => $value):
                                ?>
						<div class="form-group">
							<input type="checkbox" name="country_list[]"							                                             <?php if ($countries_selected) {
                                                                                         echo in_array($key, $countries_selected) ? 'checked' : '';
                                                                                     }?> value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>"/>
							<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
						</div>
					<?php endforeach; ?>
					<?php submit_button(); ?>
				</form>
			</div>
		<?php }

                    public function add_form_fields_car_city($taxonomy)
                    {
                        $default_country = amotos_get_option('default_country', 'US');
                        wp_nonce_field('amotos_car_city_meta', 'amotos_car_city_meta_nonce');
                        $countries = amotos_get_selected_countries();
                    ?>
			<div id="car-country" class="form-field term-group selectdiv amotos-car-select-meta-box-wrap">
				<label for="car_city_country"><?php esc_html_e('Country', 'auto-moto-stock'); ?></label>
				<select id="car_city_country" name="car_city_country" class="postform amotos-car-country-ajax">
                    <?php foreach ($countries as $k => $v): ?>
                        <option<?php selected($k, $default_country)?> value="<?php echo esc_attr($k) ?>"><?php echo esc_attr($v) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div id="car-state" class="form-field term-group selectdiv amotos-car-select-meta-box-wrap">
				<label for="car_city_state"><?php esc_html_e('Province/State', 'auto-moto-stock'); ?></label>
				<select id="car_city_state" name="car_city_state" data-slug="0"
				        class="postform amotos-car-state-ajax">
					<option selected value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
					<?php
                        $terms = get_categories(
                                        [
                                            'taxonomy'   => 'car-state',
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false,
                                            'parent'     => 0,
                                        ]
                                    );
                                ?>
                    <?php foreach ($terms as $term): ?>
                        <option  value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<?php
                }                        
                       public function save_car_city_meta( $term_id, $tt_id ) {

                       $nonce = sanitize_text_field(wp_unslash( $_POST['amotos_car_city_meta_nonce'] ?? '' )); 

                       if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'amotos_car_city_meta' ) ) 
                        { 
                          return; 
                        }
                            if (isset($_POST[ 'car_city_country' ]) && ! empty($_POST[ 'car_city_country' ])) {
                                $car_city_country = sanitize_title(wp_unslash($_POST[ 'car_city_country' ]));
                                add_term_meta($term_id, 'car_city_country', strtoupper($car_city_country), true);
                            }
                            if (isset($_POST[ 'car_city_state' ]) && ! empty($_POST[ 'car_city_state' ])) {
                                $car_city_state = sanitize_title(wp_unslash($_POST[ 'car_city_state' ]));
                                add_term_meta($term_id, 'car_city_state', $car_city_state, true);
                            }
                        }

                        public function edit_form_fields_car_city($term, $taxonomy)
                        {
                            $car_city_country = get_term_meta($term->term_id, 'car_city_country', true);
                            $car_city_state   = get_term_meta($term->term_id, 'car_city_state', true);
                            wp_nonce_field('amotos_car_city_meta', 'amotos_car_city_meta_nonce');
                            $countries = amotos_get_selected_countries();
                        ?>
			<tr id="car-country" class="form-field term-group-wrap amotos-car-select-meta-box-wrap">
				<th scope="row"><label
							for="car_city_country"><?php esc_html_e('Country', 'auto-moto-stock'); ?></label>
				</th>
				<td><select class="postform amotos-car-country-ajax" id="car_city_country"
				            name="car_city_country">
                        <?php foreach ($countries as $k => $v): ?>
                            <option<?php selected($k, $car_city_country)?> value="<?php echo esc_attr($k) ?>"><?php echo esc_attr($v) ?></option>
                        <?php endforeach; ?>
					</select></td>
			</tr>
			<tr id="car-state" class="form-field term-group-wrap amotos-car-select-meta-box-wrap">
				<th scope="row"><label
							for="car_city_state"><?php esc_html_e('Province/State', 'auto-moto-stock'); ?></label>
				</th>
				<td><select data-selected="<?php echo esc_attr($car_city_state); ?>" data-slug="0"
				            class="postform amotos-car-state-ajax" id="car_city_state"
				            name="car_city_state">
						<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
						<?php
                            $terms = get_categories(
                                            [
                                                'taxonomy'   => 'car-state',
                                                'orderby'    => 'name',
                                                'order'      => 'ASC',
                                                'hide_empty' => false,
                                                'parent'     => 0,
                                            ]
                                        );
                                    ?>
                        <?php foreach ($terms as $term): ?>
                            <option<?php selected($term->term_id, $car_city_state)?> value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                        <?php endforeach; ?>
					</select>
                </td>
			</tr>
			<?php
                }

                        public function update_car_city_meta($term_id, $tt_id)
                        {
                            $nonce = sanitize_text_field(wp_unslash( $_POST['amotos_car_city_meta_nonce'] ?? '' ));

                            if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'amotos_car_city_meta' ) ) 
                            { 
                            return; 
                            }
                            if (isset($_POST[ 'car_city_country' ]) && ! empty($_POST[ 'car_city_country' ])) {
                                $car_city_country = sanitize_title(wp_unslash($_POST[ 'car_city_country' ]));
                                update_term_meta($term_id, 'car_city_country', strtoupper($car_city_country));
                            }
                            if (isset($_POST[ 'car_city_state' ]) && ! empty($_POST[ 'car_city_state' ])) {
                                $car_city_state = sanitize_title(wp_unslash($_POST[ 'car_city_state' ]));
                                update_term_meta($term_id, 'car_city_state', $car_city_state);
                            }
                        }

                        public function add_columns_car_city($columns)
                        {
                            $columns[ 'cb' ]             = "<input type=\"checkbox\" />";
                            $columns[ 'name' ]           = esc_html__('Name', 'auto-moto-stock');
                            $columns[ 'description' ]    = esc_html__('Description', 'auto-moto-stock');
                            $columns[ 'slug' ]           = esc_html__('Slug', 'auto-moto-stock');
                            $columns[ 'car_city_state' ] = esc_html__('Province/State', 'auto-moto-stock');
                            $columns[ 'posts' ]          = esc_html__('Count', 'auto-moto-stock');
                            $new_columns                 = [];
                            $custom_order                = [
                                'cb',
                                'name',
                                'description',
                                'slug',
                                'car_city_state',
                                'posts',
                            ];
                            foreach ($custom_order as $colname) {
                                $new_columns[ $colname ] = $columns[ $colname ];
                            }

                            return $new_columns;
                        }

                        public function add_columns_car_city_content($content, $column_name, $term_id)
                        {

                            if ($column_name !== 'car_city_state') {
                                return $content;
                            }
                            $term_id               = absint($term_id);
                            $car_city_state_tax_id = get_term_meta($term_id, 'car_city_state', true);
                            if (! empty($car_city_state_tax_id)) {
                                $car_city_state = get_term($car_city_state_tax_id);
                                if (! empty($car_city_state) && isset($car_city_state->name)) {
                                    $content .= esc_html($car_city_state->name);
                                }
                            }

                            return $content;
                        }

                        public function add_columns_car_city_sortable($sortable)
                        {
                            $sortable[ 'car_city_state' ] = 'car_city_state';

                            return $sortable;
                        }

                        // Vehicle neighborhood
                        public function add_form_fields_car_neighborhood($taxonomy)
                        {
                            $default_country = amotos_get_option('default_country', 'US');
                            $countries       = amotos_get_selected_countries();
                            wp_nonce_field('amotos_car_neighborhood_meta', 'amotos_car_neighborhood_meta_nonce');
                        ?>
			<div id="car-country" class="form-field term-group selectdiv amotos-car-select-meta-box-wrap">
				<label for="car_neighborhood_country"><?php esc_html_e('Country', 'auto-moto-stock'); ?></label>
				<select id="car_neighborhood_country" name="car_neighborhood_country"
				        class="postform amotos-car-country-ajax">
                    <?php foreach ($countries as $k => $v): ?>
                        <option<?php selected($k, $default_country)?> value="<?php echo esc_attr($k) ?>"><?php echo esc_attr($v) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div id="car-state" class="form-field term-group selectdiv amotos-car-select-meta-box-wrap">
				<label for="car_neighborhood_state"><?php esc_html_e('Province/State', 'auto-moto-stock'); ?></label>
				<select id="car_neighborhood_state" name="car_neighborhood_state" data-slug="0"
				        class="postform amotos-car-state-ajax">
					<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
					<?php
                        $terms_state = get_categories(
                                        [
                                            'taxonomy'   => 'car-state',
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false,
                                            'parent'     => 0,
                                        ]
                                    );
                                ?>
                    <?php foreach ($terms_state as $term): ?>
                        <option  value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div id="car-city" class="form-field term-group selectdiv amotos-car-select-meta-box-wrap">
				<label for="car_neighborhood_city"><?php esc_html_e('City', 'auto-moto-stock'); ?></label>
				<select id="car_neighborhood_city" name="car_neighborhood_city" data-slug="0"
				        class="postform amotos-car-city-ajax">
					<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
					<?php
                        $terms_city = get_categories(
                                        [
                                            'taxonomy'   => 'car-city',
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false,
                                            'parent'     => 0,
                                        ]
                                    );
                                ?>
                    <?php foreach ($terms_city as $term): ?>
                        <option  value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<?php
                }
                        public function save_car_neighborhood_meta($term_id, $tt_id)
                        {
                            $nonce = sanitize_text_field(wp_unslash( $_POST['amotos_car_neighborhood_meta_nonce'] ?? '' ));
                            
                            if (empty($nonce) || ! wp_verify_nonce( $nonce, 'amotos_car_neighborhood_meta')) {
                                return;
                            }

                            if (isset($_POST[ 'car_neighborhood_country' ]) && ! empty($_POST[ 'car_neighborhood_country' ])) {
                                $car_neighborhood_country = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_country' ]));
                                add_term_meta($term_id, 'car_neighborhood_country', strtoupper($car_neighborhood_country), true);
                            }
                            if (isset($_POST[ 'car_neighborhood_state' ]) && ! empty($_POST[ 'car_neighborhood_state' ])) {
                                $car_neighborhood_state = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_state' ]));
                                add_term_meta($term_id, 'car_neighborhood_state', $car_neighborhood_state, true);
                            }
                            if (isset($_POST[ 'car_neighborhood_city' ]) && ! empty($_POST[ 'car_neighborhood_city' ])) {
                                $car_neighborhood_city = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_city' ]));
                                add_term_meta($term_id, 'car_neighborhood_city', $car_neighborhood_city, true);
                            }
                        }

                        public function edit_form_fields_car_neighborhood($term, $taxonomy)
                        {
                            $car_neighborhood_country = get_term_meta($term->term_id, 'car_neighborhood_country', true);
                            $car_neighborhood_state   = get_term_meta($term->term_id, 'car_neighborhood_state', true);
                            $car_neighborhood_city    = get_term_meta($term->term_id, 'car_neighborhood_city', true);
                            $countries                = amotos_get_selected_countries();
                            wp_nonce_field('amotos_car_neighborhood_meta', 'amotos_car_neighborhood_meta_nonce');
                        ?>
			<tr id="car-country" class="form-field term-group-wrap amotos-car-select-meta-box-wrap">
				<th scope="row"><label
							for="car_neighborhood_country"><?php esc_html_e('Country', 'auto-moto-stock'); ?></label>
				</th>
				<td><select class="postform amotos-car-country-ajax" id="car_neighborhood_country"
				            name="car_neighborhood_country">
                        <?php foreach ($countries as $k => $v): ?>
                            <option<?php selected($k, $car_neighborhood_country)?> value="<?php echo esc_attr($k) ?>"><?php echo esc_attr($v) ?></option>
                        <?php endforeach; ?>

					</select></td>
			</tr>
			<tr id="car-state" class="form-field term-group-wrap amotos-car-select-meta-box-wrap">
				<th scope="row"><label
							for="car_neighborhood_state"><?php esc_html_e('Province/State', 'auto-moto-stock'); ?></label>
				</th>
				<td><select data-selected="<?php echo esc_attr($car_neighborhood_state); ?>" data-slug="0"
				            class="postform amotos-car-state-ajax" id="car_neighborhood_state"
				            name="car_neighborhood_state">
						<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
						<?php
                            $terms_state = get_categories(
                                            [
                                                'taxonomy'   => 'car-state',
                                                'orderby'    => 'name',
                                                'order'      => 'ASC',
                                                'hide_empty' => false,
                                                'parent'     => 0,
                                            ]
                                        );
                                    ?>
                        <?php foreach ($terms_state as $term): ?>
                            <option<?php selected($term->term_id, $car_neighborhood_state)?> value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                        <?php endforeach; ?>
					</select></td>
			</tr>
			<tr id="car-city" class="form-field term-group-wrap amotos-car-select-meta-box-wrap">
				<th scope="row"><label
							for="car_neighborhood_city"><?php esc_html_e('City', 'auto-moto-stock'); ?></label>
				</th>
				<td><select data-selected="<?php echo esc_attr($car_neighborhood_city); ?>" data-slug="0"
				            class="postform amotos-car-city-ajax" id="car_neighborhood_city"
				            name="car_neighborhood_city">
						<option value=""><?php esc_html_e('None', 'auto-moto-stock'); ?></option>
						<?php
                            $terms_city = get_categories(
                                            [
                                                'taxonomy'   => 'car-city',
                                                'orderby'    => 'name',
                                                'order'      => 'ASC',
                                                'hide_empty' => false,
                                                'parent'     => 0,
                                            ]
                                        );
                                    ?>
                        <?php foreach ($terms_city as $term): ?>
                            <option<?php selected($term->term_id, $car_neighborhood_city)?> value="<?php echo esc_attr($term->term_id) ?>"><?php echo esc_attr($term->name) ?></option>
                        <?php endforeach; ?>
					</select></td>
			</tr>
			<?php
                }
                        /*public function update_car_neighborhood_meta($term_id, $tt_id)
                        {
                            if (empty($_POST[ 'amotos_car_neighborhood_meta_nonce' ]) || ! wp_verify_nonce(wp_unslash($_POST[ 'amotos_car_neighborhood_meta_nonce' ]), 'amotos_car_neighborhood_meta')) {
                                return;
                            }*/
                        public function update_car_neighborhood_meta($term_id, $tt_id)
                        {
                            $nonce = sanitize_text_field(wp_unslash( $_POST['amotos_car_neighborhood_meta_nonce'] ?? '' ));

                            if (empty($nonce) || ! wp_verify_nonce($nonce, 'amotos_car_neighborhood_meta')) {
                                return;
                            }
                            if (isset($_POST[ 'car_neighborhood_country' ]) && ! empty($_POST[ 'car_neighborhood_country' ])) {
                                $car_neighborhood_country = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_country' ]));
                                update_term_meta($term_id, 'car_neighborhood_country', strtoupper($car_neighborhood_country));
                            }
                            if (isset($_POST[ 'car_neighborhood_state' ]) && ! empty($_POST[ 'car_neighborhood_state' ])) {
                                $car_neighborhood_state = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_state' ]));
                                update_term_meta($term_id, 'car_neighborhood_state', $car_neighborhood_state);
                            }
                            if (isset($_POST[ 'car_neighborhood_city' ]) && ! empty($_POST[ 'car_neighborhood_city' ])) {
                                $car_neighborhood_city = sanitize_title(wp_unslash($_POST[ 'car_neighborhood_city' ]));
                                update_term_meta($term_id, 'car_neighborhood_city', $car_neighborhood_city);
                            }
                        }

                        public function add_columns_car_neighborhood($columns)
                        {
                            $columns[ 'cb' ]                    = "<input type=\"checkbox\" />";
                            $columns[ 'name' ]                  = esc_html__('Name', 'auto-moto-stock');
                            $columns[ 'description' ]           = esc_html__('Description', 'auto-moto-stock');
                            $columns[ 'slug' ]                  = esc_html__('Slug', 'auto-moto-stock');
                            $columns[ 'car_neighborhood_city' ] = esc_html__('City', 'auto-moto-stock');
                            $columns[ 'posts' ]                 = esc_html__('Count', 'auto-moto-stock');
                            $new_columns                        = [];
                            $custom_order                       = [
                                'cb',
                                'name',
                                'description',
                                'slug',
                                'car_neighborhood_city',
                                'posts',
                            ];
                            foreach ($custom_order as $colname) {
                                $new_columns[ $colname ] = $columns[ $colname ];
                            }

                            return $new_columns;
                        }

                        public function add_columns_car_neighborhood_content($content, $column_name, $term_id)
                        {

                            if ($column_name !== 'car_neighborhood_city') {
                                return $content;
                            }
                            $term_id                      = absint($term_id);
                            $car_neighborhood_city_tax_id = get_term_meta($term_id, 'car_neighborhood_city', true);
                            if (! empty($car_neighborhood_city_tax_id)) {
                                $car_neighborhood_city = get_term($car_neighborhood_city_tax_id);
                                if (! empty($car_neighborhood_city) && isset($car_neighborhood_city->name)) {
                                    $content .= esc_html($car_neighborhood_city->name);
                                }
                            }

                            return $content;
                        }

                        public function add_columns_car_neighborhood_sortable($sortable)
                        {
                            $sortable[ 'car_neighborhood_city' ] = 'car_neighborhood_city';

                            return $sortable;
                        }
                }
            }