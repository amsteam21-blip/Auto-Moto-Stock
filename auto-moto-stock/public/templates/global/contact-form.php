<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $email
 * @var $enable_captcha
 * @var $extend_class
 */
$wrapper_classes = array(
    'amotos__contact-form'
);

if (isset($extend_class)) {
    $wrapper_classes[] = $extend_class;
}

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__('Contact', 'auto-moto-stock'); ?></h2>
    </div>
    <form class="needs-validation amotos__form" novalidate>
        <div class="form-group">
            <label for="sender_name"><?php echo esc_html__('Full Name', 'auto-moto-stock'); ?></label>
            <input class="form-control" id="sender_name" required name="sender_name" type="text" placeholder="<?php echo esc_attr__('Full Name', 'auto-moto-stock'); ?>">
            <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Name!', 'auto-moto-stock'); ?> </div>
        </div>
        <div class="form-group">
            <label for="sender_phone"><?php echo esc_html__('Phone Number', 'auto-moto-stock'); ?></label>
            <input class="form-control" id="sender_phone" required name="sender_phone" type="text" placeholder="<?php echo esc_attr__('Phone Number', 'auto-moto-stock'); ?>">
            <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Phone!', 'auto-moto-stock'); ?> </div>
        </div>
        <div class="form-group">
            <label for="sender_email"><?php echo esc_html__('Email Address', 'auto-moto-stock'); ?></label>
            <input class="form-control" id="sender_email" required name="sender_email" type="email" placeholder="<?php echo esc_attr__('Email Address', 'auto-moto-stock'); ?>">
            <div class="invalid-feedback"> <?php echo esc_html__('Please enter your valid Email!', 'auto-moto-stock'); ?> </div>
        </div>
        <div class="form-group">
            <label for="sender_msg"><?php echo esc_html__('Message', 'auto-moto-stock'); ?></label>
            <textarea class="form-control" id="sender_msg" name="sender_msg" rows="5" placeholder="<?php esc_attr_e('Message', 'auto-moto-stock'); ?>" required></textarea>
            <div class="invalid-feedback"> <?php echo esc_html__('Please enter your Message!', 'auto-moto-stock'); ?> </div>
        </div>
        <?php if ($enable_captcha): ?>
            <div class="form-group">
                <?php  do_action('amotos_generate_form_recaptcha'); ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block amotos__btn-submit-contact-form"><?php echo esc_html__('Submit Request', 'auto-moto-stock'); ?></button>
        </div>
        <input type="hidden" name="target_email" value="<?php echo esc_attr($email); ?>">
        <input type="hidden" name="action" id="contact_manager_action" value="amotos_contact_manager_ajax">
        <?php wp_nonce_field('amotos_contact_manager_ajax_nonce', 'amotos_security_contact_manager'); ?>
        <div class="amotos__message"></div>
    </form>
</div>
