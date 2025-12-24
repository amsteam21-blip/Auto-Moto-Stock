<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
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
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Contact', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <?php if ($display_type !== 'other_info'): ?>
            <div class="amotos__contact-manager-info row">
                <div class="amotos__manager-image col-6">
                    <a title="<?php echo esc_attr($name)?>" href="<?php echo esc_url($link)?>">
                        <img
                            src="<?php echo esc_url($avatar) ?>"
                            onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                            alt="<?php echo esc_attr($name) ?>"
                            title="<?php echo esc_attr($name) ?>">
                    </a>
                </div>
                <div class="amotos__manager-content col-6">
                    <h4 class="amotos__manager-name"><a href="<?php echo esc_url($link)?>" title="<?php echo esc_attr($name)?>"><?php echo esc_html($name)?></a></h4>
                    <?php if (!empty($position)): ?>
                        <p class="amotos__manager-position m-0"><?php echo esc_html($position)?></p>
                    <?php endif; ?>
                    <div class="amotos__single-manager-social">
                        <?php if (!empty($facebook)): ?>
                            <div>
                                <i class="fa fa-facebook"></i> <span><?php echo esc_url($facebook)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($twitter)): ?>
                            <div>
                                <i class="fa fa-twitter"></i> <span><?php echo esc_url($twitter)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($skype)): ?>
                            <div>
                                <i class="fa fa-skype"></i> <span><?php echo esc_html($skype)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($linkedin)): ?>
                            <div>
                                <i class="fa fa-linkedin"></i> <span><?php echo esc_url($linkedin)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($pinterest)): ?>
                            <div>
                                <i class="fa fa-pinterest"></i> <span><?php echo esc_url($pinterest)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($instagram)): ?>
                            <div>
                                <i class="fa fa-instagram"></i> <span><?php echo esc_url($instagram)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($youtube)): ?>
                            <div>
                                <i class="fa fa-youtube-play"></i> <span><?php echo esc_url($youtube)?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($vimeo)): ?>
                            <div>
                                <i class="fa fa-vimeo"></i> <span><?php echo esc_url($vimeo)?></span>
                            </div>
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
    </div>
</div>
