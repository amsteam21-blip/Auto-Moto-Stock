<?php
    if (! defined('ABSPATH')) {
        exit;
    }
    if (! class_exists('AMOTOS_Admin_Setup_Metaboxes')) {
        /**
         * Class AMOTOS_Admin_Setup_Metaboxes
         */
        class AMOTOS_Admin_Setup_Metaboxes
        {
            /**
             * Meta boxes setup
             */
            public function meta_boxes_setup()
            {
                global $typenow;
                if ($typenow == 'user_package') {
                    add_action('add_meta_boxes', [$this, 'render_user_package_meta_boxes']);
                }
                if ($typenow == 'invoice') {
                    add_action('add_meta_boxes', [$this, 'render_invoice_meta_boxes']);
                    add_action('save_post', [$this, 'save_invoices_metaboxes'], 20, 2);
                }
                if ($typenow == 'trans_action') {
                    add_action('add_meta_boxes', [$this, 'render_trans_action_meta_boxes']);
                }
                if ($typenow == 'car') {
                    add_action('add_meta_boxes', [$this, 'render_car_meta_boxes']);
                    add_action('save_post', [$this, 'save_car_metaboxes'], 20, 2);
                }
            }

            /**
             * Render manager package meta boxes
             */
            public function render_user_package_meta_boxes()
            {
                add_meta_box(
                    AMOTOS_METABOX_PREFIX . 'user_package_metaboxes',
                    esc_html__('Package Details', 'auto-moto-stock'),
                    [$this, 'user_package_meta'],
                    ['user_package'],
                    'normal',
                    'default'
                );
            }

            /**
             * Manager package meta
             *
             * @param $object
             */
            public function user_package_meta($object)
            {
                $postID                  = $object->ID;
                $package_user_id         = get_post_meta($postID, AMOTOS_METABOX_PREFIX . 'package_user_id', true);
                $package_id              = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_id', true);
                $package_number_listings = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true);
                $package_number_featured = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true);
                $package_activate_date   = get_user_meta($package_user_id, AMOTOS_METABOX_PREFIX . 'package_activate_date', true);
                $package_name            = get_the_title($package_id);
                $user_info               = get_userdata($package_user_id);
                $amotos_package             = new AMOTOS_Package();
                $expired_date            = $amotos_package->get_expired_date($package_id, $package_user_id);
            ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label><?php esc_html_e('Buyer:', 'auto-moto-stock'); ?></label></th>
						<td><strong><?php if ($user_info) {
                                                    echo esc_html($user_info->display_name);
                                                }?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e('Package:', 'auto-moto-stock'); ?></label></th>
						<td><strong><?php echo esc_html($package_name); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e('Number Listings:', 'auto-moto-stock'); ?></label>
						</th>
						<td><strong><?php echo esc_html($package_number_listings); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_html_e('Number Featured Listings:', 'auto-moto-stock'); ?></label>
						</th>
						<td><strong><?php echo esc_html($package_number_featured); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e('Activate Date:', 'auto-moto-stock'); ?></label>
						</th>
						<td><strong><?php echo esc_html($package_activate_date); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e('Expire Date:', 'auto-moto-stock'); ?></label>
						</th>
						<td><strong><?php echo esc_html($expired_date); ?></strong>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
                }

                        /**
                         * Render invoice meta boxes
                         */
                        public function render_invoice_meta_boxes()
                        {
                            add_meta_box(
                                AMOTOS_METABOX_PREFIX . 'invoice_metaboxes',
                                esc_html__('Invoice Details', 'auto-moto-stock'),
                                [$this, 'invoice_meta'],
                                ['invoice'],
                                'normal',
                                'default'
                            );

                            add_meta_box(
                                AMOTOS_METABOX_PREFIX . 'invoice_payment_status',
                                esc_html__('Payment Status', 'auto-moto-stock'),
                                [$this, 'invoice_payment_status'],
                                ['invoice'],
                                'side',
                                'high'
                            );
                        }

                        /**
                         * Invoice meta
                         *
                         * @param $object
                         */
                        public function invoice_meta($object)
                        {
                            $amotos_invoice = new AMOTOS_Invoice();
                            $amotos_meta    = $amotos_invoice->get_invoice_meta($object->ID);
                        ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e('Invoice ID:', 'auto-moto-stock'); ?></th>
						<td><strong><?php echo esc_html(intval($object->ID)); ?></strong></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Payment Method:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_method($amotos_meta[ 'invoice_payment_method' ])); ?>
							</strong>
						</td>
					</tr>
					<?php if (($amotos_meta[ 'invoice_payment_method' ] == 'Stripe') || ($amotos_meta[ 'invoice_payment_method' ] == 'Paypal')): ?>
						<tr>
							<th scope="row"><?php esc_html_e('Payment ID (PayPal,Stripe):', 'auto-moto-stock'); ?></th>
							<td>
								<strong>
									<?php echo esc_html($amotos_meta[ 'trans_payment_id' ]); ?>
								</strong>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('Payer ID (PayPal,Stripe):', 'auto-moto-stock'); ?></th>
							<td>
								<strong>
									<?php echo esc_html($amotos_meta[ 'trans_payer_id' ]); ?>
								</strong>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<th scope="row"><?php esc_html_e('Payment Type:', 'auto-moto-stock'); ?></th>
						<td>
							<strong><?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_type($amotos_meta[ 'invoice_payment_type' ])); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php

                                                        if ($amotos_meta[ 'invoice_payment_type' ] == 'Package') {
                                                            esc_html_e('Package ID:', 'auto-moto-stock');
                                                        } else {
                                                            esc_html_e('Vehicle ID:', 'auto-moto-stock');
                                                        }
                                                    ?>
						</th>
						<td>
							<strong><?php echo esc_html($amotos_meta[ 'invoice_item_id' ]); ?></strong>
							<?php
                                if ($amotos_meta[ 'invoice_payment_type' ] == 'Package') {
                                            ?>
								<a href="<?php echo esc_url(get_edit_post_link($amotos_meta[ 'invoice_item_id' ])) ?>"><?php esc_html_e('(Edit)', 'auto-moto-stock'); ?></a>
								<?php
                                    } else {
                                                    if (current_user_can('read_car', $amotos_meta[ 'invoice_item_id' ])) {
                                                    ?>
									<a href="<?php echo esc_url(get_permalink($amotos_meta[ 'invoice_item_id' ])) ?>"><?php esc_html_e('(View)', 'auto-moto-stock'); ?></a>
									<?php
                                        }
                                                        if (current_user_can('edit_car', $amotos_meta[ 'invoice_item_id' ])) {
                                                        ?>
									<a href="<?php echo esc_url(get_edit_post_link($amotos_meta[ 'invoice_item_id' ])) ?>"><?php esc_html_e('(Edit)', 'auto-moto-stock'); ?></a>
									<?php
                                        }
                                                    }
                                                ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Item Price:', 'auto-moto-stock'); ?></th>
						<td>
							<strong><?php
                                        $item_price = amotos_get_format_money($amotos_meta[ 'invoice_item_price' ]);
                                                echo wp_kses_post($item_price);
                                                ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Purchase Date:', 'auto-moto-stock'); ?>
						</th>
						<td>
							<strong><?php echo esc_html($amotos_meta[ 'invoice_purchase_date' ]); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Name:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php
                                    $user_info = get_userdata($amotos_meta[ 'invoice_user_id' ]);
                                                if (current_user_can('edit_users') && $user_info) {
                                                    echo '<a href="' . esc_url(get_edit_user_link($amotos_meta[ 'invoice_user_id' ])) . '">' . esc_html($user_info->display_name) . '</a>';
                                                } else {
                                                    if ($user_info) {
                                                        echo esc_html($user_info->display_name);
                                                    }
                                                }
                                            ?>
							</strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Mobile:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php
                                    $manager_mobile_number = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_mobile_number', $amotos_meta[ 'invoice_user_id' ]);
                                                echo esc_html($manager_mobile_number);
                                            ?>
							</strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Email:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php if ($user_info) {
                                                    echo esc_html($user_info->user_email);
                                            }?>
							</strong>
						</td>
					</tr>
					<?php do_action('amotos_admin_invoice_meta', $amotos_meta)?>
				</tbody>
			</table>
			<?php
                }

                        /**
                         * Render invoice meta boxes
                         */
                        public function render_trans_action_meta_boxes()
                        {
                            add_meta_box(
                                AMOTOS_METABOX_PREFIX . 'trans_action_metaboxes',
                                esc_html__('Transaction Details', 'auto-moto-stock'),
                                [$this, 'trans_action_meta'],
                                ['trans_action'],
                                'normal',
                                'default'
                            );
                        }

                        /**
                         * Invoice meta
                         *
                         * @param $object
                         */
                        public function trans_action_meta($object)
                        {
                            $amotos_trans_action = new AMOTOS_Trans_Action();
                            $amotos_meta         = $amotos_trans_action->get_trans_action_meta($object->ID);
                        ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e('Transaction Status:', 'auto-moto-stock'); ?></th>
						<td><strong><?php
                                        $trans_action_status = get_post_meta($object->ID, AMOTOS_METABOX_PREFIX . 'trans_action_status', true);
                                                    if ($trans_action_status == 1) {
                                                        esc_html_e('Succeeded', 'auto-moto-stock');
                                                    } else {
                                                        esc_html_e('Failed', 'auto-moto-stock');
                                                }
                                                ?></strong></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Action ID:', 'auto-moto-stock'); ?></th>
						<td><strong><?php echo intval($object->ID); ?></strong></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Payment Method:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_method($amotos_meta[ 'trans_action_payment_method' ])); ?>
							</strong>
						</td>
					</tr>
					<?php if (($amotos_meta[ 'trans_action_payment_method' ] == 'Stripe') || ($amotos_meta[ 'trans_action_payment_method' ] == 'Paypal')): ?>
						<tr>
							<th scope="row"><?php esc_html_e('Payment ID (PayPal,Stripe):', 'auto-moto-stock'); ?></th>
							<td>
								<strong>
									<?php echo esc_html($amotos_meta[ 'trans_payment_id' ]); ?>
								</strong>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('Payer ID (PayPal,Stripe):', 'auto-moto-stock'); ?></th>
							<td>
								<strong>
									<?php echo esc_html($amotos_meta[ 'trans_payer_id' ]); ?>
								</strong>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<th scope="row"><?php esc_html_e('Payment Type:', 'auto-moto-stock'); ?></th>
						<td>
							<strong><?php echo esc_html(AMOTOS_Invoice::get_invoice_payment_type($amotos_meta[ 'trans_action_payment_type' ])); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php

                                                        if ($amotos_meta[ 'trans_action_payment_type' ] == 'Package') {
                                                            esc_html_e('Package ID:', 'auto-moto-stock');
                                                        } else {
                                                            esc_html_e('Vehicle ID:', 'auto-moto-stock');
                                                        }
                                                    ?>
						</th>
						<td>
							<strong><?php echo esc_html($amotos_meta[ 'trans_action_item_id' ]); ?></strong>
							<?php
                                if ($amotos_meta[ 'trans_action_payment_type' ] == 'Package') {
                                            ?>
								<a href="<?php echo esc_url(get_edit_post_link($amotos_meta[ 'trans_action_item_id' ])) ?>"><?php esc_html_e('(Edit)', 'auto-moto-stock'); ?></a>
								<?php
                                    } else {
                                                    if (current_user_can('read_car', $amotos_meta[ 'trans_action_item_id' ])) {
                                                    ?>
									<a href="<?php echo esc_url(get_permalink($amotos_meta[ 'trans_action_item_id' ])) ?>"><?php esc_html_e('(View)', 'auto-moto-stock'); ?></a>
									<?php
                                        }
                                                        if (current_user_can('edit_car', $amotos_meta[ 'trans_action_item_id' ])) {
                                                        ?>
									<a href="<?php echo esc_url(get_edit_post_link($amotos_meta[ 'trans_action_item_id' ])) ?>"><?php esc_html_e('(Edit)', 'auto-moto-stock'); ?></a>
									<?php
                                        }
                                                    }
                                                ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Item Price:', 'auto-moto-stock'); ?></th>
						<td>
							<strong><?php
                                        $item_price = amotos_get_format_money($amotos_meta[ 'trans_action_item_price' ]);
                                                echo wp_kses_post($item_price);
                                                ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Purchase Date:', 'auto-moto-stock'); ?>
						</th>
						<td>
							<strong><?php echo esc_html($amotos_meta[ 'trans_action_purchase_date' ]); ?></strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Name:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php
                                    $user_info = get_userdata($amotos_meta[ 'trans_action_user_id' ]);
                                                if ($user_info) {
                                                    if (current_user_can('edit_users')) {
                                                        echo '<a href="' . esc_url(get_edit_user_link($amotos_meta[ 'trans_action_user_id' ])) . '">' . esc_html($user_info->display_name) . '</a>';
                                                    } else {
                                                        echo esc_html($user_info->display_name);
                                                    }
                                                }
                                            ?>
							</strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Mobile:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php
                                    $manager_mobile_number = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_mobile_number', $amotos_meta[ 'trans_action_user_id' ]);
                                                echo esc_html($manager_mobile_number);
                                            ?>
							</strong>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Buyer Email:', 'auto-moto-stock'); ?></th>
						<td>
							<strong>
								<?php
                                    if ($user_info) {
                                                    echo esc_html($user_info->user_email);
                                                }
                                            ?>
							</strong>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
                }

                        /**
                         * Invoice payment status
                         *
                         * @param $object
                         */
                        public function invoice_payment_status($object)
                        {
                            wp_nonce_field('amotos_invoice', 'amotos_invoice_nonce');
                            $payment_status = get_post_meta($object->ID, AMOTOS_METABOX_PREFIX . 'invoice_payment_status', true);
                        ?>
			<div class="amotos_meta_control custom_sidebar_js">
				<?php
                    if ($payment_status == 0) {
                                    echo '<span class="amotos-label-red notice inline notice-warning notice-alt">' . esc_html__('Not Paid', 'auto-moto-stock') . '</span>';
                                } else {
                                    echo '<span class="amotos-label-blue notice inline notice-success notice-alt">' . esc_html__('Paid', 'auto-moto-stock') . '</span>';
                                }
                                if ($payment_status == 0) {
                                ?>
					<div class="amotos-set-item-paid">
						<input type="checkbox" id="amotos[amotos_payment_status]" name="amotos[amotos_payment_status]"
						       value="0"/>
						<label class="amotos-label-blue"
						       for="amotos[amotos_payment_status]"><?php esc_html_e('Set item paid', 'auto-moto-stock'); ?></label>
					</div>
				<?php }
                            ?>
			</div>
			<?php
                }

                        /**
                         * Save invoices metaboxes
                         *
                         * @param $post_id
                         * @param $post
                         *
                         * @return bool
                         */
                        /*public function save_invoices_metaboxes($post_id, $post)
                        {

                            // $post_id and $post are required
                            if (empty($post_id) || empty($post)) {
                                return false;
                            }

                            // Dont' save meta boxes for revisions or autosaves.
                            if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
                                return false;
                            }

                            if (! isset($_POST[ 'amotos_invoice_nonce' ]) || ! wp_verify_nonce(amotos_clean(wp_unslash($_POST[ 'amotos_invoice_nonce' ])), 'amotos_invoice')) {
                                return false;
                            }*/
public function save_invoices_metaboxes( $post_id, $post ) {

    // Проверяем корректность аргументов
    if ( empty( $post_id ) || empty( $post ) ) {
        return false;
    }

    // Не сохраняем для автосейвов и ревизий
    if (
        ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
        wp_is_post_revision( $post_id ) ||
        wp_is_post_autosave( $post_id )
    ) {
        return false;
    }

    // Проверяем nonce
    $nonce = sanitize_text_field(
        wp_unslash( $_POST['amotos_invoice_nonce'] ?? '' )
    );

    if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'amotos_invoice' ) ) {
        return false;
    }

                            if ($post->post_type == 'invoice' && isset($_POST[ 'amotos' ])) {
                                $post_type = get_post_type_object($post->post_type);
                                if (! current_user_can($post_type->cap->edit_post, $post_id)) {
                                    return false;
                                }
                                if (isset($_POST[ 'amotos' ][ 'amotos_payment_status' ])) {
                                    $amotos_invoice = new AMOTOS_Invoice();
                                    $amotos_meta    = $amotos_invoice->get_invoice_meta($post_id);
                                    $user_id     = $amotos_meta[ 'invoice_user_id' ];
                                    $user        = get_user_by('id', $user_id);
                                    $user_email  = $user->user_email;
                                    if ($amotos_meta[ 'invoice_payment_type' ] == 'Package') {
                                        $package_id  = $amotos_meta[ 'invoice_item_id' ];
                                        $amotos_package = new AMOTOS_Package();
                                        $amotos_package->insert_user_package($user_id, $package_id);
                                        update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'invoice_payment_status', 1);
                                        $args = [
                                            'invoice_no'  => $post_id,
                                            'total_price' => amotos_get_format_money($amotos_meta[ 'invoice_item_price' ]),
                                        ];
                                        amotos_send_email($user_email, 'mail_activated_package', $args);
                                    } else {
                                        $car_id = $amotos_meta[ 'invoice_item_id' ];
                                        if ($amotos_meta[ 'invoice_payment_type' ] == 'Listing') {
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'payment_status', 'paid');
                                            wp_update_post([
                                                'ID'            => $car_id,
                                                'post_status'   => 'publish',
                                                'post_date'     => current_time('mysql'),
                                                'post_date_gmt' => current_time('mysql'),
                                            ]);
                                            amotos_send_email($user_email, 'mail_activated_listing');
                                        } else if ($amotos_meta[ 'invoice_payment_type' ] == 'Upgrade_To_Featured') {
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 1);
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time('mysql'));
                                        } else if ($amotos_meta[ 'invoice_payment_type' ] == 'Listing_With_Featured') {
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'payment_status', 'paid');
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 1);
                                            update_post_meta($car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time('mysql'));
                                            wp_update_post([
                                                'ID'            => $car_id,
                                                'post_status'   => 'publish',
                                                'post_date'     => current_time('mysql'),
                                                'post_date_gmt' => current_time('mysql'),
                                            ]);
                                            amotos_send_email($user_email, 'mail_activated_listing');
                                        }
                                        update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'invoice_payment_status', 1);

                                    }
                                }
                            }

                            return true;
                        }

                        /**
                         * Save vehicle metaboxes
                         *
                         * @param $post_id
                         *
                         * @return bool
                         */
                        public function save_car_metaboxes($post_id, $post)
                        {

                            // $post_id and $post are required
                            if (empty($post_id) || empty($post)) {
                                return false;
                            }

                            // Dont' save meta boxes for revisions or autosaves.
                            if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
                                return false;
                            }

                            /*if (! isset($_POST[ 'amotos_car_meta_nonce' ]) || ! wp_verify_nonce(amotos_clean(wp_unslash($_POST[ 'amotos_car_meta_nonce' ])), 'amotos_car_meta')) {
                                return false;
                            }*/
                            // Проверяем nonce
                            $nonce = sanitize_text_field(
                            wp_unslash( $_POST['amotos_car_meta_nonce'] ?? '' ) );

                            if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'amotos_car_meta' ) ) {
                                 return false;
                            }

                            if (! is_admin()) {
                                return false;
                            }

                            $manager_display_option = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'manager_display_option', true);
                            if (isset($manager_display_option) && ('author_info' == $manager_display_option)) {
                                $post_author = get_post_field('post_author', $post_id);
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_author', $post_author);
                            } else {
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_author', '');
                            }
                            if ($manager_display_option != 'manager_info') {
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_manager', '');
                            }
                            $car_identity = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_identity', true);
                            if (empty($car_identity)) {
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_identity', $post_id);
                            }
                            $car_price_on_call = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_on_call', true);
                            if ($car_price_on_call == '1') {
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_short', '');
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price', '');
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', 1);
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_prefix', '');
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_postfix', '');
                            } else {
                                $enable_price_unit = amotos_get_option('enable_price_unit', '1');
                                if ($enable_price_unit == '0') {
                                    update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', 1);
                                }
                                $car_price_short = amotos_format_decimal(get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_short', true));
                                $car_price_unit  = get_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_unit', true);
                                if (! empty($car_price_short) && is_numeric($car_price_short)) {
                                    if (! empty($car_price_unit) && is_numeric($car_price_unit) && intval($car_price_unit) > 1) {
                                        $car_price = doubleval($car_price_short) * intval($car_price_unit);
                                    } else {
                                        $car_price = doubleval($car_price_short);
                                    }
                                } else {
                                    $car_price = '';
                                }
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price_short', $car_price_short);
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_price', $car_price);
                            }
                            if (isset($_POST[ 'amotos' ][ 'amotos_car_country' ])) {
                                $country = sanitize_text_field(wp_unslash($_POST[ 'amotos' ][ 'amotos_car_country' ]));
                                update_post_meta($post_id, AMOTOS_METABOX_PREFIX . 'car_country', $country);
                            }
                            return true;
                        }

                        /**
                         * Render vehicle paid meta boxes
                         */
                        public function render_car_meta_boxes()
                        {
                            add_meta_box(
                                AMOTOS_METABOX_PREFIX . 'car_country',
                                esc_html__('Country', 'auto-moto-stock'),
                                [$this, 'car_country'],
                                'car',
                                'side');

                            $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
                            if ($paid_submission_type == 'per_listing') {
                                add_meta_box(
                                    AMOTOS_METABOX_PREFIX . 'paid_submission',
                                    esc_html__('Paid Submission', 'auto-moto-stock'),
                                    [$this, 'paid_submission'],
                                    'car',
                                    'side',
                                    'high');
                            }
                        }

                        /**
                         * Render paid submission status
                         *
                         * @param $object
                         */
                        public function paid_submission($object)
                        {
                            $payment_status = get_post_meta($object->ID, AMOTOS_METABOX_PREFIX . 'payment_status', true);
                            if ($payment_status == 'paid') {
                                echo wp_kses_post(__('Payment Status: <span class="amotos-label-blue">Paid</span>', 'auto-moto-stock'));
                            } else {
                                $price_per_listing = amotos_get_option('price_per_listing', 0);
                                if ($price_per_listing > 0) {
                                    echo wp_kses_post(__('Payment Status: <span class="amotos-label-red">Not Paid</span>', 'auto-moto-stock'));
                                }
                            }
                            $amotos_admin_invoice = new AMOTOS_Admin_Invoice();
                        ?>
			<div class="amotos_meta_control custom_sidebar_js">
				<p><?php esc_html_e('View Invoice: ', 'auto-moto-stock');
                               $amotos_admin_invoice->get_invoices_by_car($object->ID); ?>
				</p>
			</div>
			<?php
                }

                        /**
                         * Render Country
                         *
                         * @param $object
                         */
                        public function car_country($object)
                        {
                            $car_country     = get_post_meta($object->ID, AMOTOS_METABOX_PREFIX . 'car_country', true);
                            $default_country = amotos_get_option('default_country', 'US');
                            if (empty($car_country)) {
                                $car_country = $default_country;
                            }
                        ?>
			<div id="car-country-<?php echo esc_attr($car_country); ?>"
			     class="selectdiv amotos-car-select-meta-box-wrap">
                <?php wp_nonce_field('amotos_car_meta', 'amotos_car_meta_nonce'); ?>
				<select id="amotos[amotos_car_country]" name="amotos[amotos_car_country]"
				        class="widefat amotos-car-country-ajax">
					<?php
                        $countries = amotos_get_selected_countries();
                                    foreach ($countries as $key => $country):
                                        echo '<option ' . selected($car_country, $key, false) . ' value="' . esc_attr($key) . '">' . esc_html($country) . '</option>';
                                    endforeach;
                                ?>
				</select>
			</div>
			<?php
                }
                }
            }