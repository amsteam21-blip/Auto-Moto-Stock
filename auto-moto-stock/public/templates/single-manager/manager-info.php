<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
$manager_id = get_the_ID();
$manager_post_meta_data = get_post_custom($manager_id);
$custom_manager_image_size_single = amotos_get_option('custom_manager_image_size_single', '270x340');
$manager_name = get_the_title();
$manager_position = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_position']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_position'][0] : '';

$manager_description = apply_filters('amotos_description',isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_description']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_description'][0] : '') ;
$manager_company = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_company']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_company'][0] : '';
$manager_licenses = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_licenses']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_licenses'][0] : '';
$manager_office_address = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_office_address']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_office_address'][0] : '';
$manager_mobile_number = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_mobile_number']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_mobile_number'][0] : '';
$manager_fax_number = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_fax_number']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_fax_number'][0] : '';
$manager_office_number = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_office_number']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_office_number'][0] : '';
$email = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_email']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_email'][0] : '';
$manager_website_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_website_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_website_url'][0] : '';

$manager_facebook_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_facebook_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_facebook_url'][0] : '';
$manager_twitter_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_twitter_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_twitter_url'][0] : '';
$manager_linkedin_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_linkedin_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_linkedin_url'][0] : '';
$manager_pinterest_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_pinterest_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_pinterest_url'][0] : '';
$manager_instagram_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_instagram_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_instagram_url'][0] : '';
$manager_skype = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_skype']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_skype'][0] : '';
$manager_youtube_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_youtube_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_youtube_url'][0] : '';
$manager_vimeo_url = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_vimeo_url']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_vimeo_url'][0] : '';

$manager_user_id = isset($manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_user_id']) ? $manager_post_meta_data[AMOTOS_METABOX_PREFIX . 'manager_user_id'][0] : '';
$user = get_user_by('id', $manager_user_id);
if (empty($user)) {
    $manager_user_id = 0;
}
$amotos_car = new AMOTOS_Car();
$total_car = $amotos_car->get_total_cars_by_user($manager_id, $manager_user_id);

?>

<div class="single-manager-element manager-single amotos_single-manager-info amotos__single-manager-element">
    <div class="manager-single-inner row">
        <?php
        $avatar_id = get_post_thumbnail_id($manager_id);
        $avatar_src = '';
        $width = 270;
        $height = 340;
        $no_avatar_src = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
        $default_avatar = amotos_get_option('default_user_avatar', '');
        if (preg_match('/\d+x\d+/', $custom_manager_image_size_single)) {
            $image_size = explode('x', $custom_manager_image_size_single);
            $width = $image_size[0];
            $height = $image_size[1];
            $avatar_src = amotos_image_resize_id($avatar_id, $width, $height, true);
            if ($default_avatar != '') {
                if (is_array($default_avatar) && $default_avatar['url'] != '') {
                    $resize = amotos_image_resize_url($default_avatar['url'], $width, $height, true);
                    if ($resize != null && is_array($resize)) {
                        $no_avatar_src = $resize['url'];
                    }
                }
            }
        } else {
            if (!in_array($custom_manager_image_size_single, array('full', 'thumbnail'))) {
                $custom_manager_image_size_single = 'full';
            }
            $avatar_src = wp_get_attachment_image_src($avatar_id, $custom_manager_image_size_single);
            if ($avatar_src && !empty($avatar_src[0])) {
                $avatar_src = $avatar_src[0];
            }
            if (!empty($avatar_src)) {
                list($width, $height) = getimagesize($avatar_src);
            }
            if ($default_avatar != '') {
                if (is_array($default_avatar) && $default_avatar['url'] != '') {
                    $no_avatar_src = $default_avatar['url'];
                }
            }
        }
        ?>
        <div class="amotos__single-manager-avatar manager-avatar text-center col-lg-3">
            <img width="<?php echo esc_attr($width) ?>"
                 height="<?php echo esc_attr($height) ?>"
                 src="<?php echo esc_url($avatar_src) ?>"
                 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                 alt="<?php echo esc_attr($manager_name) ?>"
                 title="<?php echo esc_attr($manager_name) ?>">
            <?php if ($total_car > 0): ?>
                <?php
                    $car_archive_link = get_post_type_archive_link('car');
                    $manager_car_link = add_query_arg('manager_id',$manager_id,$car_archive_link);
                ?>
                <a class="btn btn-primary btn-block"
                   href="<?php echo esc_url($manager_car_link); ?>"
                   title="<?php echo esc_attr($manager_name) ?>"><?php esc_html_e('View All Vehicles', 'auto-moto-stock'); ?></a>
            <?php endif; ?>
        </div>
        <div class="manager-content col-lg-5">
            <div class="manager-content-top">
                <?php if (!empty($manager_name)): ?>
                    <h2 class="amotos__single-manager-title manager-title"><?php echo esc_html($manager_name) ?></h2>
                <?php endif; ?>
                <div class="amotos__single-manager-social manager-social">
                    <?php if (!empty($manager_facebook_url)): ?>
                        <a title="Facebook" href="<?php echo esc_url($manager_facebook_url); ?>">
                            <i class="fa fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_twitter_url)): ?>
                        <a title="Twitter" href="<?php echo esc_url($manager_twitter_url); ?>">
                            <i class="fa fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($email)): ?>
                        <a title="Email" href="mailto:<?php echo esc_attr($email); ?>">
                            <i class="fa fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_skype)): ?>
                        <a title="Skype" href="skype:<?php echo esc_url($manager_skype); ?>?call">
                            <i class="fa fa-skype"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_linkedin_url)): ?>
                        <a title="Linkedin" href="<?php echo esc_url($manager_linkedin_url); ?>">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_pinterest_url)): ?>
                        <a title="Pinterest" href="<?php echo esc_url($manager_pinterest_url); ?>">
                            <i class="fa fa-pinterest"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_instagram_url)): ?>
                        <a title="Instagram" href="<?php echo esc_url($manager_instagram_url); ?>">
                            <i class="fa fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_youtube_url)): ?>
                        <a title="Youtube" href="<?php echo esc_url($manager_youtube_url); ?>">
                            <i class="fa fa-youtube-play"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($manager_vimeo_url)): ?>
                        <a title="Vimeo" href="<?php echo esc_url($manager_vimeo_url); ?>">
                            <i class="fa fa-vimeo"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($manager_position)): ?>
                    <span class="amotos__single-manager-position manager-position"><?php echo esc_html($manager_position) ?></span>
                <?php endif; ?>
                <span class="amotos__single-manager-number-car manager-number-car">
					<?php /* translators: %s: Number of Vehicle Manager. */ echo wp_kses_post(sprintf(_n('%s Vehicle', '%s Vehicles', $total_car, 'auto-moto-stock'), amotos_get_format_number($total_car))); ?>
				</span>
            </div>
            <div class="amotos__single-manager-contact-info manager-contact manager-info">
                <?php if (!empty($manager_office_address)): ?>
                    <div><i class="fa fa-map-marker"></i><strong><?php esc_html_e('Address:', 'auto-moto-stock'); ?></strong>
							<span><?php echo esc_html($manager_office_address) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($email)): ?>
                    <div><i class="fa fa-envelope"></i><strong><?php esc_html_e('Email:', 'auto-moto-stock'); ?></strong>
                        <a style="display: inline;" href="mailto:<?php echo esc_attr($email) ?>"
                           title="<?php esc_attr_e('Website:', 'auto-moto-stock'); ?>">
								<span><?php echo esc_html($email) ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($manager_mobile_number)): ?>
                    <div>
                        <i class="fa fa-phone"></i><strong><?php esc_html_e('Phone:', 'auto-moto-stock'); ?></strong>
                        <span><?php echo esc_html($manager_mobile_number) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($manager_website_url)): ?>
                    <div>
                        <i class="fa fa-link"></i><strong><?php esc_html_e('Website:', 'auto-moto-stock'); ?></strong>
                        <a style="display: inline;" href="<?php echo esc_url($manager_website_url) ?>"
                           title="<?php esc_attr_e('Website:', 'auto-moto-stock'); ?>">
                            <span><?php echo esc_url($manager_website_url); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <hr class="mg-top-20">
                <?php the_terms($manager_id,'dealer','<div class="amotos__single-manager-dealer manager-dealer"><strong>'. esc_html__('Dealer:', 'auto-moto-stock') .'</strong> ','','</div>'); ?>

                <?php
                if (!empty($manager_company)): ?>
                    <div>
                        <strong><?php esc_html_e('Company:', 'auto-moto-stock'); ?></strong>
                        <span><?php echo esc_html($manager_company); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($manager_licenses)): ?>
                    <div>
                        <strong><?php esc_html_e('Licenses:', 'auto-moto-stock'); ?></strong>
                        <span><?php echo esc_html($manager_licenses); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($manager_office_number)): ?>
                    <div>
                        <strong><?php esc_html_e('Office Number:', 'auto-moto-stock'); ?></strong>
                        <span><?php echo esc_html($manager_office_number); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($manager_office_address)): ?>
                    <div>
                        <strong><?php esc_html_e('Office Address:', 'auto-moto-stock'); ?></strong>
                        <span><?php echo esc_html($manager_office_address); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="amotos__single-manager-contact-form contact-manager col-lg-4">
            <?php amotos_template_single_manager_contact_form(); ?>
        </div>
    </div>
    <?php if (!empty($manager_description)): ?>
        <div class="amotos__single-manager-description manager-description">
            <?php echo wp_kses_post($manager_description) ?>
        </div>
    <?php endif; ?>
</div>