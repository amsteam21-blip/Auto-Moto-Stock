<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $is_login
 * @var $name
 * @var $link
 * @var $display_type
 * @var $email
 * @var $avatar
 * @var $position
 * @var $facebook
 * @var $twitter
 * @var $linkedin
 * @var $pinterest
 * @var $instagram
 * @var $skype
 * @var $youtube
 * @var $vimeo
 * @var $mobile
 * @var $office
 * @var $website
 * @var $no_avatar_src
 * @var $desc
 * @var $user_id
 * @var $manager_id
 */
$wrapper_classes = array(
    'single-car-element',
    'car-contact-manager',
    'amotos__single-car-element',
    'amotos__single-car-contact-manager'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_contact_manager_wrapper_classes',$wrapper_classes));

$enable_captcha =  amotos_enable_captcha('contact_manager');

$contact_forms_classes = array(
        'amotos__contact-form',
        'amotos__single-manager-contact-form'
);
if ($enable_captcha) {
    $contact_forms_classes[] = 'amotos__has-captcha';
}

$contact_forms_class = join(' ', $contact_forms_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Contact', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <?php if ($display_type !== 'other_info'): ?>
            <div class="amotos__contact-manager-info row">
                <div class="amotos__manager-image col-md-6">
                    <a title="<?php echo esc_attr($name)?>" href="<?php echo esc_url($link)?>">
                        <img
                                src="<?php echo esc_url($avatar) ?>"
                                onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                                alt="<?php echo esc_attr($name) ?>"
                                title="<?php echo esc_attr($name) ?>">
                    </a>
                </div>
                <div class="amotos__manager-content col-md-6">
                    <h4 class="amotos__manager-name"><a href="<?php echo esc_url($link)?>" title="<?php echo esc_attr($name)?>"><?php echo esc_html($name)?></a></h4>
                    <?php if (!empty($position)): ?>
                        <p class="amotos__manager-position m-0"><?php echo esc_html($position)?></p>
                    <?php endif; ?>
                    <div class="amotos__single-manager-social">
                        <?php if (!empty($facebook)): ?>
                            <a title="<?php echo esc_attr__('Facebook','auto-moto-stock'); ?>" href="<?php echo esc_url( $facebook ); ?>">
                                <i class="fa fa-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($twitter)): ?>
                            <a title="<?php echo esc_attr__('Twitter','auto-moto-stock'); ?>" href="<?php echo esc_url( $twitter ); ?>">
                                <i class="fa fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($skype)): ?>
                            <a title="<?php echo esc_attr__('Skype','auto-moto-stock'); ?>" href="skype:<?php echo esc_url( $skype ); ?>?chat">
                                <i class="fa fa-skype"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($linkedin)): ?>
                            <a title="<?php echo esc_attr__('Linkedin','auto-moto-stock'); ?>" href="<?php echo esc_url( $linkedin ); ?>">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($pinterest)): ?>
                            <a title="<?php echo esc_attr__('Pinterest','auto-moto-stock'); ?>" href="<?php echo esc_url( $pinterest ); ?>">
                                <i class="fa fa-pinterest"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($instagram)): ?>
                            <a title="<?php echo esc_attr__('Instagram','auto-moto-stock'); ?>" href="<?php echo esc_url( $instagram ); ?>">
                                <i class="fa fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($youtube)): ?>
                            <a title="<?php echo esc_attr__('Youtube','auto-moto-stock'); ?>" href="<?php echo esc_url( $youtube ); ?>">
                                <i class="fa fa-youtube-play"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($vimeo)): ?>
                            <a title="<?php echo esc_attr__('Vimeo','auto-moto-stock'); ?>" href="<?php echo esc_url( $vimeo ); ?>">
                                <i class="fa fa-vimeo"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="amotos__single-manager-contact-info">
                        <?php if ( ! empty( $office ) ): ?>
                            <div class="amotos__address">
                                <i class="fa fa-map-marker"></i>
                                <span><?php echo esc_html( $office ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $mobile ) ): ?>
                            <div class="amotos__mobile">
                                <i class="fa fa-phone"></i>
                                <span><?php echo esc_html( $mobile ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $email ) ): ?>
                            <div class="amotos__email">
                                <i class="fa fa-envelope"></i>
                                <span><?php echo esc_html( $email ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $website ) ): ?>
                            <div class="amotos__website">
                                <i class="fa fa-link"></i>
                                <a href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website ); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(!empty( $desc )): ?>
                        <div class="amotos_desc">
                            <p><?php echo wp_kses_post( $desc ); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php
                        $car_archive_link = get_post_type_archive_link('car');
                        if ($display_type === 'manager_info') {
                            $manager_car_link = add_query_arg('manager_id', $manager_id, $car_archive_link);
                        } else {
                            $manager_car_link = add_query_arg('user_id', $user_id, $car_archive_link);
                        }
                    ?>
                    <a class="btn btn-primary" href="<?php echo esc_url($manager_car_link) ?>" title="<?php echo esc_attr__( 'Other Vehicles', 'auto-moto-stock' ); ?>"><?php echo esc_html__( 'Other Vehicles', 'auto-moto-stock' ); ?></a>
                </div>
            </div>
        <?php else: ?>
        <div class="amotos__contact-manager-info">
            <div class="amotos__manager-content">
                <h4 class="amotos__manager-name"><a href="<?php echo esc_url($link)?>" title="<?php echo esc_attr($name)?>"><?php echo esc_html($name)?></a></h4>
                <div class="amotos__single-manager-contact-info">
                    <?php if ( ! empty( $mobile ) ): ?>
                        <div class="amotos__mobile">
                            <i class="fa fa-phone"></i>
                            <span><?php echo esc_html( $mobile ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ( ! empty( $email ) ): ?>
                        <div class="amotos__email">
                            <i class="fa fa-envelope"></i>
                            <span><?php echo esc_html( $email ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if(!empty( $desc )): ?>
                    <div class="amotos_desc">
                        <p><?php echo wp_kses_post( $desc ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($email)): ?>
            <div class="<?php echo esc_attr($contact_forms_class)?>">
                <form class="needs-validation amotos__form" novalidate>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sender_name"><?php echo esc_html__('Full Name', 'auto-moto-stock'); ?></label>
                                <input class="form-control" id="sender_name" required name="sender_name" type="text" placeholder="<?php echo esc_attr__('Full Name', 'auto-moto-stock'); ?>">
                                <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Name!', 'auto-moto-stock'); ?> </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sender_phone"><?php echo esc_html__('Phone Number', 'auto-moto-stock'); ?></label>
                                <input class="form-control" id="sender_phone" required name="sender_phone" type="text" placeholder="<?php echo esc_attr__('Phone Number', 'auto-moto-stock'); ?>">
                                <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Phone!', 'auto-moto-stock'); ?> </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sender_email"><?php echo esc_html__('Email Address', 'auto-moto-stock'); ?></label>
                                <input class="form-control" id="sender_email" required name="sender_email" type="email" placeholder="<?php echo esc_attr__('Email Address', 'auto-moto-stock'); ?>">
                                <div class="invalid-feedback"> <?php echo esc_html__('Please enter your valid Email!', 'auto-moto-stock'); ?> </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="sender_msg"><?php echo esc_html__('Message', 'auto-moto-stock'); ?></label>
                                 <textarea class="form-control" id="sender_msg" name="sender_msg" rows="4"
                                  placeholder="<?php echo esc_attr__( 'Message', 'auto-moto-stock' ); ?> *"><?php $title=get_the_title();/* translators: %s: title of Vehicle. */ echo sprintf(esc_html__( 'Hello, I am interested in [%s]', 'auto-moto-stock' ), esc_html($title)) ?></textarea>
                                <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Message!', 'auto-moto-stock'); ?> </div>
                            </div>
                        </div>
                        <?php if ($enable_captcha): ?>
                            <div class="col-sm-6">
                                <?php do_action('amotos_generate_form_recaptcha'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-sm-6 amotos__manager-contact-btn-wrap">
                            <button type="submit" class="btn btn-primary amotos__btn-submit-contact-form"><?php echo esc_html__('Submit Request', 'auto-moto-stock'); ?></button>
                        </div>
                        <div class="col-12">
                            <div class="amotos__message"></div>
                        </div>
                    </div>
                    <input type="hidden" name="action" id="contact_manager_with_car_url_action" value="amotos_contact_manager_ajax">
                    <input type="hidden" name="target_email" value="<?php echo esc_attr( $email ); ?>">
                    <input type="hidden" name="car_url" value="<?php echo esc_url(get_permalink()) ; ?>">
                    <?php wp_nonce_field('amotos_contact_manager_ajax_nonce', 'amotos_security_contact_manager'); ?>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
