<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! is_user_logged_in() ) {
	return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

	return;
}
global $current_user;
wp_get_current_user();
$user_id             = $current_user->ID;
$user_login          = $current_user->user_login;
$user_firstname      = get_the_author_meta( 'first_name', $user_id );
$user_lastname       = get_the_author_meta( 'last_name', $user_id );
$user_email          = get_the_author_meta( 'user_email', $user_id );
$user_mobile_number  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_mobile_number', $user_id );
$user_fax_number     = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_fax_number', $user_id );
$user_company        = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_company', $user_id );
$user_licenses       = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_licenses', $user_id );
$user_office_number  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_office_number', $user_id );
$user_office_address = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_office_address', $user_id );
$user_des            = get_the_author_meta( 'description', $user_id );
$user_facebook_url   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_facebook_url', $user_id );
$user_twitter_url    = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_twitter_url', $user_id );
$user_linkedin_url   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_linkedin_url', $user_id );
$user_pinterest_url  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_pinterest_url', $user_id );
$user_instagram_url  = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_instagram_url', $user_id );
$user_youtube_url    = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_youtube_url', $user_id );
$user_vimeo_url      = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_vimeo_url', $user_id );
$user_skype          = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_skype', $user_id );
$user_website_url    = get_the_author_meta( 'user_url', $user_id );

$user_position       = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_position', $user_id );
$user_custom_picture = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_custom_picture', $user_id );
$author_picture_id   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_picture_id', $user_id );
$no_avatar_src       = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
$width               = get_option( 'thumbnail_size_w' );
$height              = get_option( 'thumbnail_size_h' );
$default_avatar      = amotos_get_option( 'default_user_avatar', '' );
if ( $default_avatar != '' ) {
	if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
		$resize = amotos_image_resize_url( $default_avatar['url'], $width, $height, true );
		if ( $resize != null && is_array( $resize ) ) {
			$no_avatar_src = $resize['url'];
		}
	}
}
$user_as_manager                = amotos_get_option( 'user_as_manager', 1 );
$enable_submit_car_via_frontend = amotos_get_option( 'enable_submit_car_via_frontend', 1 );
$is_manager = amotos_is_manager();
$is_manager_pending = amotos_is_manager_pending();

wp_enqueue_script( 'plupload' );
wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'profile' );
$hide_user_info_fields = amotos_get_option( 'hide_user_info_fields', array() );
if ( ! is_array( $hide_user_info_fields ) ) {
	$hide_user_info_fields = array();
}
?>
<div class="row amotos-user-dashboard">
	<div class="col-lg-3 amotos-dashboard-sidebar">
		<?php amotos_get_template( 'global/dashboard-menu.php', array( 'cur_menu' => 'my_profile' ) ); ?>
	</div>
	<div class="col-lg-9 amotos-dashboard-content">
		<div class="amotos-my-profile">
			<div class="card amotos-card amotos-card-account-settings">
                <div class="card-header"><h5 class="card-title m-0"><?php echo esc_html__('Account Settings', 'auto-moto-stock'); ?></h5></div>
				<div class="card-body profile-wrap update-profile">
					<form action="#" class="amotos-update-profile">
						<div class="row">
							<?php
							if ( $enable_submit_car_via_frontend == 1 ) {
								$message = '';
								if ( ! $is_manager ) {
									if ( $user_as_manager == 1 ) {
										if ($is_manager_pending) {
											$message = esc_html__( 'Your account need to be approved by admin to become an manager, if you want to return to normal account, you must click the button below', 'auto-moto-stock' );
										} else {
											$become_manager_terms_condition = amotos_get_option( 'become_manager_terms_condition' );
                                            /* translators: %s: link of page become manager terms condition. */
											$message                      = sprintf( __( 'If you want to become an manager, please read our <a class="accent-color" target="_blank" href="%s">Terms & Conditions</a> first', 'auto-moto-stock' ), get_permalink( $become_manager_terms_condition ) );
										}
									}
								} else {
									$message = esc_html__( 'Your current account type is set to manager, if you want to remove your manager account, and return to normal account, you must click the button below', 'auto-moto-stock' );
								}
								if ( $is_manager || $is_manager_pending || $user_as_manager == 1 ):?>
									<div class="col-sm-12">
										<div class="jumbotron amotos-account-manager">
											<h4><?php esc_html_e( 'Manager Account', 'auto-moto-stock' ); ?></h4>
											<p><?php echo wp_kses_post( $message ); ?></p>
											<?php if ( ! $is_manager && !$is_manager_pending ): ?>
												<?php wp_nonce_field( 'amotos_become_manager_ajax_nonce', 'amotos_security_become_manager' ); ?>
												<button type="button" class="btn btn-primary"
												        id="amotos_user_as_manager"><?php esc_html_e( 'Become an Manager', 'auto-moto-stock' ); ?></button>

											<?php else: ?>
												<?php wp_nonce_field( 'amotos_leave_manager_ajax_nonce', 'amotos_security_leave_manager' ); ?>
												<button type="button" class="btn btn-primary"
												        id="amotos_leave_manager"><?php esc_html_e( 'Remove Manager Account', 'auto-moto-stock' ); ?></button>
											<?php endif; ?>
										</div>
									</div>
								<?php endif;
							} ?>
							<div class="col-sm-6 amotos-profile-avatar">
								<div id="user-profile-img">
									<div class="profile-thumb">
										<?php
										if ( ! empty( $author_picture_id ) ) {
											$author_picture_id = intval( $author_picture_id );
											if ( $author_picture_id ) {
												$avatar_src = amotos_image_resize_id( $author_picture_id, $width, $height, true );
												?>
												<img width="<?php echo esc_attr( $width ) ?>"
												     height="<?php echo esc_attr( $height ) ?>" id="profile-image"
												     src="<?php echo esc_url( $avatar_src ); ?>"
												     onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
												     alt="<?php esc_attr_e( 'User Avatar', 'auto-moto-stock' ) ?>">
												<input type="hidden" class="profile-pic-id" id="profile-pic-id"
												       name="profile-pic-id"
												       value="<?php echo esc_attr( $author_picture_id ); ?>"/>
												<?php
											}
										} else {
											?>
											<img width="<?php echo esc_attr( $width ) ?>"
											     height="<?php echo esc_attr( $height ) ?>" id="profile-image"
											     src="<?php echo esc_url( $user_custom_picture ); ?>"
											     onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
											     alt="<?php esc_attr_e( 'User Avatar', 'auto-moto-stock' ) ?>">
											<?php
										}
										?>
									</div>
								</div>

								<div class="profile-img-controls">
									<div id="errors_log"></div>
								</div>
								<div id="amotos_profile_plupload_container">
									<button type="button" id="amotos_select_profile_image"
									        class="btn btn-primary"><?php esc_html_e( 'Update Profile Picture', 'auto-moto-stock' ); ?></button>
								</div>
							</div>
							<div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                            for="user_firstname"><?php esc_html_e( 'First Name', 'auto-moto-stock' ); ?></label>
                                    <input type="text" name="user_firstname" id="user_firstname"
                                           class="form-control"
                                           value="<?php echo esc_attr( $user_firstname ); ?>">
                                </div>
                                <div class="form-group">
                                    <label
                                            for="user_lastname"><?php esc_html_e( 'Last Name', 'auto-moto-stock' ); ?></label>
                                    <input type="text" name="user_lastname" id="user_lastname"
                                           class="form-control"
                                           value="<?php echo esc_attr( $user_lastname ); ?>">
                                </div>
                                <div class="form-group">
                                    <label
                                            for="user_email"><?php esc_html_e( 'Email', 'auto-moto-stock' ); ?></label>
                                    <input type="text" name="user_email" id="user_email" class="form-control"
                                           value="<?php echo esc_attr( $user_email ); ?>">
                                </div>
                                <div class="form-group">
                                    <label
                                            for="user_mobile_number"><?php esc_html_e( 'Mobile', 'auto-moto-stock' ); ?></label>
                                    <input type="text" id="user_mobile_number" name="user_mobile_number"
                                           class="form-control"
                                           value="<?php echo esc_attr( $user_mobile_number ); ?>">
                                </div>
							</div>
						</div>
						<div class="form-group">
							<label for="user_des"><?php esc_html_e( 'About me', 'auto-moto-stock' ); ?></label>
							<textarea id="user_des" name="user_des" class="form-control"
							          rows="5"><?php echo esc_attr( $user_des ); ?></textarea>
						</div>
						<div class="row">
							<?php if ( amotos_is_manager() ): ?>
								<?php if ( ! in_array( "user_company", $hide_user_info_fields ) ): ?>
									<div class="col-lg-4  col-sm-6">
										<div class="form-group">
											<label
													for="user_company"><?php esc_html_e( 'Company', 'auto-moto-stock' ); ?></label>
											<input type="text" id="user_company" name="user_company"
											       class="form-control"
											       value="<?php echo esc_attr( $user_company ); ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ( ! in_array( "user_position", $hide_user_info_fields ) ): ?>
									<div class="col-lg-4  col-sm-6">
										<div class="form-group">
											<label
													for="user_position"><?php esc_html_e( 'Position', 'auto-moto-stock' ); ?></label>
											<input type="text" id="user_position" name="user_position"
											       value="<?php echo esc_attr( $user_position ); ?>"
											       class="form-control">
										</div>
									</div>
								<?php endif; ?>
								<?php if ( ! in_array( "user_office_number", $hide_user_info_fields ) ): ?>
									<div class="col-lg-4  col-sm-6">
										<div class="form-group">
											<label
													for="user_office_number"><?php esc_html_e( 'Office Number', 'auto-moto-stock' ); ?></label>
											<input type="text" id="user_office_number" name="user_office_number"
											       class="form-control"
											       value="<?php echo esc_attr( $user_office_number ); ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ( ! in_array( "user_office_address", $hide_user_info_fields ) ): ?>
									<div class="col-lg-4  col-sm-6">
										<div class="form-group">
											<label
													for="user_office_address"><?php esc_html_e( 'Office Address', 'auto-moto-stock' ); ?></label>
											<input type="text" id="user_office_address" name="user_office_address"
											       class="form-control"
											       value="<?php echo esc_attr( $user_office_address ); ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ( ! in_array( "user_licenses", $hide_user_info_fields ) ): ?>
									<div class="col-lg-4  col-sm-6">
										<div class="form-group">
											<label
													for="user_licenses"><?php esc_html_e( 'Licenses', 'auto-moto-stock' ); ?></label>
											<input type="text" id="user_licenses" name="user_licenses"
											       class="form-control"
											       value="<?php echo esc_attr( $user_licenses ); ?>">
										</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ( ! in_array( "user_fax_number", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_fax_number"><?php esc_html_e( 'Fax', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_fax_number" name="user_fax_number"
										       class="form-control"
										       value="<?php echo esc_attr( $user_fax_number ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_website_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_website_url"><?php esc_html_e( 'Website URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_website_url" name="user_website_url"
										       class="form-control"
										       value="<?php echo esc_url( $user_website_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_skype", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_skype"><?php esc_html_e( 'Skype', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_skype" name="user_skype" class="form-control"
										       value="<?php echo esc_attr( $user_skype ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_facebook_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_facebook_url"><?php esc_html_e( 'Facebook URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_facebook_url" name="user_facebook_url"
										       value="<?php echo esc_attr( $user_facebook_url ); ?>"
										       class="form-control">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_twitter_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_twitter_url"><?php esc_html_e( 'Twitter URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_twitter_url" name="user_twitter_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_twitter_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_linkedin_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_linkedin_url"><?php esc_html_e( 'Linkedin URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_linkedin_url" name="user_linkedin_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_linkedin_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_instagram_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_instagram_url"><?php esc_html_e( 'Instagram URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_instagram_url" name="user_instagram_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_instagram_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_pinterest_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_pinterest_url"><?php esc_html_e( 'Pinterest URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_pinterest_url" name="user_pinterest_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_pinterest_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_youtube_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_youtube_url"><?php esc_html_e( 'Youtube URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_youtube_url" name="user_youtube_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_youtube_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
							<?php if ( ! in_array( "user_vimeo_url", $hide_user_info_fields ) ): ?>
								<div class="col-lg-4  col-sm-6">
									<div class="form-group">
										<label
												for="user_vimeo_url"><?php esc_html_e( 'Vimeo URL', 'auto-moto-stock' ); ?></label>
										<input type="text" id="user_vimeo_url" name="user_vimeo_url"
										       class="form-control"
										       value="<?php echo esc_attr( $user_vimeo_url ); ?>">
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php wp_nonce_field( 'amotos_update_profile_ajax_nonce', 'amotos_security_update_profile' ); ?>
						<button type="button" class="btn btn-primary d-inline-block"
						        id="amotos_update_profile"><?php esc_html_e( 'Update Profile', 'auto-moto-stock' ); ?></button>
					</form>
				</div>
			</div>
			<div class="card amotos-card amotos-card-change-password mt-4">
                <div class="card-header"><h5 class="card-title m-0"><?php echo esc_html__('Change password', 'auto-moto-stock'); ?></h5></div>
				<div class="card-body profile-wrap change-password">
					<form action="#" class="amotos-change-password">
						<div id="password_reset_msgs" class="amotos_messages message"></div>
						<div class="row">
							<div class="col-lg-4  col-sm-6">
								<div class="form-group">
									<label
											for="oldpass"><?php esc_html_e( 'Old Password', 'auto-moto-stock' ); ?></label>
									<input id="oldpass" value="" class="form-control" name="oldpass" type="password">
								</div>
							</div>
							<div class="col-lg-4  col-sm-6">
								<div class="form-group">
									<label
											for="newpass"><?php esc_html_e( 'New Password ', 'auto-moto-stock' ); ?></label>
									<input id="newpass" value="" class="form-control" name="newpass" type="password">
								</div>
							</div>
							<div class="col-lg-4  col-sm-6">
								<div class="form-group">
									<label
											for="confirmpass"><?php esc_html_e( 'Confirm Password', 'auto-moto-stock' ); ?></label>
									<input id="confirmpass" value="" class="form-control" name="confirmpass"
									       type="password">
								</div>
							</div>

						</div>
						<?php wp_nonce_field( 'amotos_change_password_ajax_nonce', 'amotos_security_change_password' ); ?>
						<button type="button" class="btn btn-primary d-inline-block"
						        id="amotos_change_pass"><?php esc_html_e( 'Update Password', 'auto-moto-stock' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>