<?php
    if (! defined('ABSPATH')) {
        exit;
    }
    if (! class_exists('AMOTOS_Admin_Setup')) {
        /**
         * Class AMOTOS_Admin_Setup
         */
        class AMOTOS_Admin_Setup
        {
            /**
             * Admin menu
             */
            public function admin_menu()
            {
                add_menu_page(
                    esc_html__('Auto Moto Stock', 'auto-moto-stock'),
                    esc_html__('Auto Moto Stock', 'auto-moto-stock'),
                    'manage_options',
                    'amotos_welcome',
                    [$this, 'menu_welcome_page_callback'],
                    'dashicons-car',
                    2
                );
                add_submenu_page(
                    'amotos_welcome',
                    esc_html__('Welcome', 'auto-moto-stock'),
                    esc_html__('Welcome', 'auto-moto-stock'),
                    'manage_options',
                    'amotos_welcome',
                    [$this, 'menu_welcome_page_callback']
                );
                add_submenu_page(
                    'amotos_welcome',
                    esc_html__('AMS Options', 'auto-moto-stock'),
                    esc_html__('AMS Options', 'auto-moto-stock'),
                    'manage_options',
                    'admin.php?page=amotos_options'
                );
                add_submenu_page(
                    'amotos_welcome',
                    esc_html__('Setup Page', 'auto-moto-stock'),
                    esc_html__('Setup Page', 'auto-moto-stock'),
                    'manage_options',
                    'amotos_setup',
                    [$this, 'setup_page']
                );
                if (apply_filters('amotos_show_extensions_page', true)) {
                    add_submenu_page(
                        'amotos_welcome',
                        esc_html__('AMS Extensions', 'auto-moto-stock'),
                        esc_html__('Extensions', 'auto-moto-stock'),
                        'manage_options',
                        'amotos_extensions',
                        [$this, 'extensions_page']);
                }
            }

            /**
             * Get list extensions
             */
            /*public function extensions_page()
            {
                if (isset($_GET[ 'page' ]) && 'amotos_extensions' === $_GET[ 'page' ]) {
                    wp_safe_redirect('http://plugins.auto-moto-stock.com/extensions/');

                    exit;
                }
            }*/
                public function extensions_page() { 
                    // We use it only for navigation/redirection, we do not process forms.
                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    $page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; 
                    if ( 'amotos_extensions' === $page ) { wp_safe_redirect( 'https://plugins.auto-moto-stock.com/extensions/' ); 
                    exit; } }

            public function menu_welcome_page_callback()
            {
            ?>
			<div class="wrap about-wrap">
				<h1>
                    <?php
                        /* translators: %s: plugin version */
                                    echo sprintf(esc_html__('Welcome to Auto Moto Stock %s', 'auto-moto-stock'), esc_html(AMOTOS_PLUGIN_VER));
                                ?>
                </h1>

				<div class="about-text">
					<?php esc_html_e('Auto Moto Stock is a plugin designed for websites. Suitable for car dealers, managers, private sellers. The plugin is fully functional. It is possible to view and track vehicles, built-in payment gateways, personal account and much more. With Auto Moto Stock, you can easily create an automotive website, find or sell a vehicle.', 'auto-moto-stock')?>
				</div>

				<div class="amotos-badge">
					<img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/logo.png'); ?>"
					     title="<?php esc_attr_e('Logo', 'auto-moto-stock')?>">
				</div>

				<div class="setup-section two-col has-2-columns is-fullwidth">
                    <div class="col column">
                        <h3><?php esc_html_e('Step 1: Create pages with the setup wizard', 'auto-moto-stock'); ?></h3>
                        <p>
                    <?php
                        esc_html_e("This setup wizard will help you get started with creating pages for submitting vehicles from the backend and frontend, vehicle management, profile management. Pages for packages, payment, login and registration, vehicles comparison/search and more...", 'auto-moto-stock');
                                ?>
                        </p>
                        <p>
                          <a href="<?php echo esc_url(admin_url('admin.php?page=amotos_setup')) ?>"
                             class="button button-primary"><?php esc_html_e('Setup pages', 'auto-moto-stock')?></a>
                        </p>
                    </div>
                    <div class="col column">
                           <img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/step-welcome/amotos-setup-page.png'); ?>" />
                    </div>
                </div>

                <div class="setup-section two-col has-2-columns is-fullwidth">
                    <div class="col column">
                            <img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/step-welcome/amotos-options.png'); ?>" />
                </div>
				<div class="col column">
                       <h3><?php esc_html_e('Step 2: Visit settings page', 'auto-moto-stock'); ?></h3>
                        <p>
                     <?php
                         esc_html_e("The main functions of the plugin are already included. Review the default settings and change them if necessary to suit your needs.", 'auto-moto-stock');
                                 ?>
                        </p>
                        <p>
                       <a href="<?php echo esc_url(admin_url('admin.php?page=amotos_options')) ?>"
            class="button button-primary"><?php esc_html_e('AMS Settings', 'auto-moto-stock')?></a>
        </p>
    </div>
</div>

<div class="setup-section two-col has-2-columns is-fullwidth">
    <div class="col column">

        <h3><?php esc_html_e('Step 3: Go to AMS plugin documentation page', 'auto-moto-stock'); ?></h3>
        <p>
            <?php
                esc_html_e("Read the documentation for the AMS plugin. Here all the steps for installing and configuring the plugin are describbed in detail. Here tuo will findinstructions on working with shortcodes, how to add new vehicle from the backend and frontend, register as an manager or dealer, payment and much more...", 'auto-moto-stock');
                        ?>
        </p>

        <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=amotos_setup')) ?>"
            class="button button-primary"><?php esc_html_e('Documentation', 'auto-moto-stock')?></a>
        </p>
    </div>

    <div class="col column">
        <img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/step-welcome/amotos-doc-page.png'); ?>" />
    </div>
</div>

<div class="setup-section two-col has-2-columns is-fullwidth">
    <div class="col column">
        <img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/step-welcome/amotos-video.png'); ?>" />
    </div>

    <div class="col column">
        <h3><?php esc_html_e('Step 4: Visit video tutorials page', 'auto-moto-stock'); ?></h3>

        <p>
            <?php
                esc_html_e('If you understand the video documentation, follow the link and watch the detailed video tutorials on our YouTube channel.', 'auto-moto-stock');
                        ?>
        </p>

        <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=amotos_options')) ?>"
            class="button button-primary"><?php esc_html_e('AMS Video Tutorials', 'auto-moto-stock')?></a>
        </p>
    </div>
</div>

<div class="setup-section two-col has-2-columns is-fullwidth">
    <div class="col column">

        <h3><?php esc_html_e('Step 5: Go to AMS plugin Support/Faq page', 'auto-moto-stock'); ?></h3>
        <p>
            <?php
                esc_html_e("If after all steps you still have questions visit our support page. You will surely find answers here or create a new ticket. We will solve the problem as quickly as possible.", 'auto-moto-stock');
                        ?>
        </p>

        <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=amotos_setup')) ?>"
            class="button button-primary"><?php esc_html_e('Support/FAQ', 'auto-moto-stock')?></a>
        </p>
    </div>

    <div class="col column">
        <img src="<?php echo esc_url(AMOTOS_PLUGIN_URL . 'admin/assets/images/step-welcome/amotos-faq-page.png'); ?>" />
    </div>
</div>
</div>
				<!--<a href="<?php                       //echo esc_url(admin_url( 'admin.php?page=amotos_setup' ))  ?>"
				   class="button button-primary"><?php   //esc_html_e( 'Setup page', 'auto-moto-stock' ) ?></a>
				<a href="<?php                           //echo esc_url(admin_url( 'admin.php?page=amotos_options' ))  ?>"
				   class="button button-secondary"><?php //esc_html_e( 'Settings', 'auto-moto-stock' ) ?></a>
				<div style="margin-top: 50px;">
					<iframe width="420" height="315"
					        src="https://www.youtube.com/embed/73Cahw3I7JM">
					</iframe>
				</div>
			</div>-->
			<?php
                }

                        /**
                         * Redirect the setup page on first activation
                         */
                        public function redirect()
                        {
                            // Bail if no activation redirect transient is set
                            if (! get_transient('_amotos_activation_redirect')) {
                                return;
                            }

                            if (! current_user_can('manage_options')) {
                                return;
                            }

                            // Delete the redirect transient
                            delete_transient('_amotos_activation_redirect');

                            // Bail if activating from network, or bulk, or within an iFrame
                            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter used only for flow control, not for form processing
                            $activate_multi = isset( $_GET['activate-multi'] ) ? sanitize_key( wp_unslash( $_GET['activate-multi'] ) ) : ''; if ( is_network_admin() || ! empty( $activate_multi ) || defined( 'IFRAME_REQUEST' ) ) { return; }

                            /*if ((isset($_GET[ 'action' ]) && 'upgrade-plugin' == amotos_clean(wp_unslash($_GET[ 'action' ]))) && (isset($_GET[ 'plugin' ]) && strstr(amotos_clean(wp_unslash($_GET[ 'plugin' ])), 'auto-moto-stock.php'))) {
                                return;
                            }*/
                            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter used only for flow control, not for form processing
                            $action = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : '';

                            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter used only for flow control, not for form processing
                           $plugin = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';

                           if ( 'upgrade-plugin' === $action && false !== strpos( $plugin, 'auto-moto-stock.php' ) ) 
                            {
                            return;
                            }

                            wp_safe_redirect(admin_url('admin.php?page=amotos_welcome'));

                            exit;
                        }

                        /**
                         * Create page on first activation
                         *
                         * @param $title
                         * @param $content
                         * @param $option
                         */
                        private function create_page($title, $content, $option)
                        {
                            $page_data = [
                                'post_status'    => 'publish',
                                'post_type'      => 'page',
                                'post_author'    => 1,
                                'post_name'      => sanitize_title($title),
                                'post_title'     => $title,
                                'post_content'   => $content,
                                'post_parent'    => 0,
                                'comment_status' => 'closed',
                            ];
                            $page_id = wp_insert_post($page_data);
                            if ($option) {
                                $config            = get_option(AMOTOS_OPTIONS_NAME);
                                $config[ $option ] = $page_id;
                                update_option(AMOTOS_OPTIONS_NAME, $config);
                            }
                        }

                        /**
                         * Output page setup
                         */
                        /*public function setup_page()
                        {
                            $pages_to_create = AMOTOS_Admin_Setup::get_page_setup_config();
                            $step            = ! empty($_GET[ 'step' ]) ? absint(wp_unslash($_GET[ 'step' ])) : 1;
                            if (3 === $step && ! empty($_POST)) {
                                if (! isset($_POST[ '_wpnonce' ]) || ! wp_verify_nonce($_POST[ '_wpnonce' ], 'amotos_setup_pages')) {
                                    return;
                                }*/
                        public function setup_page() 
                        { 
                            $pages_to_create = AMOTOS_Admin_Setup::get_page_setup_config(); 
                            $step = isset( $_GET['step'] ) ? absint( wp_unslash( $_GET['step'] ) ) : 1; 
                            if ( 3 === $step && ! empty( $_POST ) ) { if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'amotos_setup_pages' ) ) 
                                { 
                                    return; 
                                }

                                $create_pages = isset($_POST[ 'amotos-create-page' ]) ? sanitize_text_field(wp_unslash($_POST[ 'amotos-create-page' ])) : [];
                                $page_titles  = isset($_POST[ 'amotos-page-title' ]) ? sanitize_text_field(wp_unslash($_POST[ 'amotos-page-title' ])) : [];
                                foreach ($pages_to_create as $page => $v) {
                                    if (! isset($create_pages[ $page ]) || empty($page_titles[ $page ])) {
                                        continue;
                                    }
                                    $content = isset($v[ 'content' ]) ? $v[ 'content' ] : '';
                                    $this->create_page(sanitize_text_field($page_titles[ $page ]), $content, 'amotos_' . $page . '_page_id');
                                }
                            }
                        ?>
			<div class="amotos-setup-wrap">
				<h2><?php esc_html_e('Auto Moto Stock Setup', 'auto-moto-stock'); ?></h2>
				<ul class="amotos-setup-steps">
					<li class="<?php if ($step === 1) {
                                               echo 'amotos-setup-active-step';
                                           }?>"><?php esc_html_e('1. Introduction', 'auto-moto-stock'); ?></li>
					<li class="<?php if ($step === 2) {
                                               echo 'amotos-setup-active-step';
                                           }?>"><?php esc_html_e('2. Page Setup', 'auto-moto-stock'); ?></li>
					<li class="<?php if ($step === 3) {
                                               echo 'amotos-setup-active-step';
                                           }?>"><?php esc_html_e('3. Done', 'auto-moto-stock'); ?></li>
				</ul>

				<?php if (1 === $step): ?>

					<h3><?php esc_html_e('Setup Wizard Introduction', 'auto-moto-stock'); ?></h3>
					<p><?php echo wp_kses_post(__('Thanks for installing <em>Auto Moto Stock</em>!', 'auto-moto-stock')); ?></p>
					<p><?php esc_html_e('This setup wizard will help you get started by creating the pages for vehicle submission, vehicle management, profile management, listing vehicle, listing manager, packages, payment, login, register...', 'auto-moto-stock'); ?></p>
					<p><?php
                           /* translators: %1$s,%2$s is replaced with "string" */
                                       echo wp_kses_post(sprintf(__('If you want to skip the wizard and setup the pages and shortcodes yourself manually, the process is still relatively simple. Refer to the %1$sdocumentation%2$s for help.', 'auto-moto-stock'),
                                           '<a href="http://document.auto-moto-stock.com/auto-moto-stock">',
                                           '</a>'));
                                   ?>
                    </p>

					<p class="submit">
						<a href="<?php echo esc_url(add_query_arg('step', 2)); ?>"
						   class="button button-primary"><?php esc_html_e('Continue to page setup', 'auto-moto-stock'); ?></a>
						<a href="<?php echo esc_url(add_query_arg('skip-amotos-setup', 1, admin_url('admin.php?page=amotos_setup&step=3'))); ?>"
						   class="button"><?php esc_html_e('Skip setup. (Not Recommended)', 'auto-moto-stock'); ?></a>
					</p>

				<?php endif; ?>
				<?php if (2 === $step): ?>

					<h3><?php esc_html_e('Page Setup', 'auto-moto-stock'); ?></h3>

					<p><?php
                           /* translators: %1$s: open tag a link of WordPress shorcode; %2$s: close tag a; %3$s: open tag a link of WordPress page;  %3$s: open tag a link of document plugin Auto Moto Stock */
                                       echo wp_kses_post(sprintf(__('<em>Auto Moto Stock</em> includes %1$sshortcodes%2$s which can be used within your %3$spages%2$s to output content. These can be created for you below. For more information on the AMS shortcodes view the %4$sshortcode documentation%2$s.', 'auto-moto-stock'),
                                           '<a href="https://codex.wordpress.org/shortcode" title="What is a shortcode?" target="_blank" class="help-page-link">',
                                           '</a>',
                                           '<a href="http://codex.wordpress.org/Pages" target="_blank" class="help-page-link">',
                                       '<a href="http://document.auto-moto-stock.com/auto-moto-stock" target="_blank" class="help-page-link">')); ?>
                    </p>

					<form action="<?php echo esc_url(add_query_arg('step', 3)); ?>" method="post">
                        <?php wp_nonce_field('amotos_setup_pages')?>
						<table class="amotos-shortcodes widefat">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th><?php esc_html_e('Page Title', 'auto-moto-stock'); ?></th>
									<th><?php esc_html_e('Page Description', 'auto-moto-stock'); ?></th>
									<th><?php esc_html_e('Content Shortcode', 'auto-moto-stock'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pages_to_create as $k => $v): ?>
									<?php
                                        $title   = isset($v[ 'title' ]) ? $v[ 'title' ] : '';
                                                    $desc    = isset($v[ 'desc' ]) ? $v[ 'desc' ] : '';
                                                    $content = isset($v[ 'content' ]) ? $v[ 'content' ] : '';
                                                ?>
									<tr>
										<td><input type="checkbox" checked="checked"
										           name="amotos-create-page[<?php echo esc_attr($k) ?>]"/>
										</td>
										<td><input type="text" value="<?php echo esc_attr($title); ?>"
										           name="amotos-page-title[<?php echo esc_attr($k) ?>]"/></td>
										<td>
											<?php if (! empty($desc)): ?>
												<p><?php echo esc_html($desc) ?></p>
											<?php endif; ?>
										</td>
										<td>
											<?php if (! empty($content)): ?>
												<code><?php echo esc_html($v[ 'content' ]) ?></code>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4">
										<input type="submit" class="button button-primary"
										       value="<?php esc_html_e('Create selected pages', 'auto-moto-stock'); ?>"/>
										<a href="<?php echo esc_url(add_query_arg('step', 3)); ?>"
										   class="button"><?php esc_html_e('Skip this step', 'auto-moto-stock'); ?></a>
									</th>
								</tr>
							</tfoot>
						</table>
					</form>

				<?php endif; ?>
				<?php if (3 === $step): ?>

					<h3><?php esc_html_e('All Done!', 'auto-moto-stock'); ?></h3>

					<p><?php esc_html_e('Looks like you\'re all set to start using the plugin. In case you\'re wondering where to go next:', 'auto-moto-stock'); ?></p>

					<ul class="amotos-next-steps">
						<li>
							<a href="<?php echo esc_url(admin_url('themes.php?page=amotos_options')); ?>"><?php esc_html_e('AMS Settings', 'auto-moto-stock'); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url(admin_url('post-new.php?post_type=car')); ?>"><?php esc_html_e('Add a vehicle the back-end', 'auto-moto-stock'); ?></a>
						</li>
						<?php foreach ($pages_to_create as $k => $v): ?>
							<?php if ($permalink = amotos_get_permalink($k)): ?>
								<li>
									<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html(amotos_get_page_title($k)) ?></a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<?php
                }

                        public static function get_page_setup_config()
                        {
                            $config = apply_filters('amotos_page_setup_config', [
                                'submit_car'        => [
                                    'title'    => _x('New Vehicle', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to post vehicle to your website via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_submit_car]',
                                    'priority' => 10,
                                ],
                                'my_cars'           => [
                                    'title'    => _x('My Vehicles', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to manage and edit their own vehicle via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_my_cars]',
                                    'priority' => 20,
                                ],
                                'my_profile'        => [
                                    'title'    => _x('My Profile', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to view and edit their own profile via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_profile]',
                                    'priority' => 30,
                                ],
                                'my_invoices'       => [
                                    'title'    => _x('My Invoices', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to view their own invoice via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_my_invoices]',
                                    'priority' => 40,
                                ],
                                'my_favorites'      => [
                                    'title'    => _x('My Favorites', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to view their own favorites via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_my_favorites]',
                                    'priority' => 50,
                                ],
                                'my_save_search'    => [
                                    'title'    => _x('My Saved Search', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('Allows users to view their own "saved searches" via the front-end.', 'auto-moto-stock'),
                                    'content'  => '[amotos_my_save_search]',
                                    'priority' => 60,
                                ],
                                'packages'          => [
                                    'title'    => _x('Packages', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is packages page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_package]',
                                    'priority' => 70,
                                ],
                                'payment'           => [
                                    'title'    => _x('Payment Invoice', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is payment invoice page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_payment]',
                                    'priority' => 80,
                                ],
                                'payment_completed' => [
                                    'title'    => _x('Payment Completed', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is payment completed page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_payment_completed]',
                                    'priority' => 90,
                                ],
                                'login'             => [
                                    'title'    => _x('Login', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is login page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_login]',
                                    'priority' => 100,
                                ],
                                'register'          => [
                                    'title'    => _x('Register', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is register page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_register]',
                                    'priority' => 110,
                                ],
                                'compare'           => [
                                    'title'    => _x('Compare', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is compare page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_compare]',
                                    'priority' => 120,
                                ],
                                'advanced_search'   => [
                                    'title'    => _x('Advanced Search', 'page title', 'auto-moto-stock'),
                                    'desc'     => esc_html__('This is advanced search page.', 'auto-moto-stock'),
                                    'content'  => '[amotos_advanced_search]',
                                    'priority' => 130,
                                ],
                            ]);
                            uasort($config, 'amotos_sort_by_order_callback');

                            return $config;
                        }
                }
            }