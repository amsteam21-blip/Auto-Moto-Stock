<?php
/**
 * @var $form
 * @var $action
 * @var $car_id
 * @var $submit_button_text
 * @var $step
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    return amotos_get_template_html('global/access-denied.php', array('type' => 'not_login'));

    return;
}

$allow_submit = amotos_allow_submit();
if (!$allow_submit) {
    return amotos_get_template_html('global/access-denied.php', array('type' => 'not_allow_submit'));

    return;
}
global $car_data, $car_meta_data, $hide_car_fields, $current_user;
$hide_car_fields = amotos_get_option('hide_car_fields', array());
if (!is_array($hide_car_fields)) {
    $hide_car_fields = array();
}
if ($form == 'edit-car') {
    $car_data = get_post($car_id);
    $car_meta_data = get_post_custom($car_data->ID);
} else {
    $paid_submission_type = amotos_get_option('paid_submission_type', 'no');
    if ($paid_submission_type == 'per_package') {
        wp_get_current_user();
        $user_id = $current_user->ID;
        $amotos_profile = new AMOTOS_Profile();
        $check_package = $amotos_profile->user_package_available($user_id);
        $select_packages_link = amotos_get_permalink('packages');
        if ($check_package == 0) {
            echo '<div class="amotos-message alert alert-warning" role="alert">' . esc_html__('You are not yet subscribed to a listing! Before you can list a Vehicle, you must select a listing package. Click the button below to select a listing package.', 'auto-moto-stock') . ' </div>
                   <a class="btn btn-default" href="' . esc_url($select_packages_link) . '">' . esc_html__('Get a Listing Package', 'auto-moto-stock') . '</a>';
            return;
        } elseif ($check_package == -1) {
            echo '<div class="amotos-message alert alert-warning" role="alert">' . esc_html__('Your current listing package has expired! Please click the button below to select a new listing package.', 'auto-moto-stock') . '</div>
                   <a class="btn btn-default" href="' . esc_url($select_packages_link) . '">' . esc_html__('Upgrade Listing Package', 'auto-moto-stock') . '</a>';
            return;
        } elseif ($check_package == -2) {
            echo '<div class="amotos-message alert alert-warning" role="alert">' . esc_html__('Your current listing package doesn\'t allow you to publish any more vehicles! Please click the button below to select a new listing package.', 'auto-moto-stock') . '</div>
                   <a class="btn btn-default" href="' . esc_url($select_packages_link) . '">' . esc_html__('Upgrade Listing Package', 'auto-moto-stock') . '</a>';
            return;
        }
    }
}
wp_enqueue_script('plupload');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-geocomplete');
wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'car_steps');
?>
<section class="amotos-car-multi-step">
    <?php
    $layout = amotos_get_option('car_form_sections', array(/*'title_des',*/'basic_info', 'tech_data', 'stylings', 'location', /*'type',*/ 'price', /*'details',*/ 'media', 'contact'));
    if (!in_array("private_note", $hide_car_fields)){
        $layout['private_note']='private_note';
    }
    unset($layout['sort_order']);
    $keys= array_keys($layout);
    $total=count($keys);
    ?>
    <div class="amotos-steps">
        <?php
        $i=0;$step_name='';
        foreach ($layout as $value):
            $i++;
            switch ($value) {
                /*case 'title_des':
                    $step_name=esc_html__('Title & Description', 'auto-moto-stock');
                    break;*/
                case 'basic_info':
                    $step_name=esc_html__('Basic Info', 'auto-moto-stock');
                    break;
                case 'tech_data':
                    $step_name=esc_html__('Technical Data', 'auto-moto-stock');
                    break;
                case 'stylings':
                    $step_name=esc_html__('Styling', 'auto-moto-stock');
                    break;
                case 'location':
                    $step_name=esc_html__('Location', 'auto-moto-stock');
                    break;
                /*case 'type':
                    $step_name=esc_html__('Type', 'auto-moto-stock');
                    break;*/
                case 'price':
                    $step_name=esc_html__('Price', 'auto-moto-stock');
                    break;
                /*case 'details':
                    $step_name=esc_html__('Details', 'auto-moto-stock');
                    break;*/
                case 'media':
                    $step_name=esc_html__('Media Files', 'auto-moto-stock');
                    break;
                case 'contact':
                    $step_name=esc_html__('Contact', 'auto-moto-stock');
                    break;
                case 'private_note':
                    $step_name=esc_html__('Private Note', 'auto-moto-stock');
                    break;
            }
            ?>
            <button class="amotos-btn-arrow<?php if($i==1) echo ' active'; ?>" type="button" disabled><?php echo esc_html($step_name); ?></button>
        <?php endforeach;?>
    </div>
    <form action="<?php echo esc_url($action); ?>" method="post" id="submit_car_form" class="car-manage-form"
          enctype="multipart/form-data">
        <?php do_action('amotos_before_submit_car');
        foreach ($layout as $value) {
            $index = array_search($value,$keys);
            $prev_key = $next_key= '';
            if($index>0)
            {
                $prev_key = $keys[$index-1];
            }
            if($index<$total-1){
                $next_key = $keys[$index+1];
            }
            ?>
            <fieldset tabindex="-1" id="step-<?php echo esc_attr($value); ?>">
                <?php
                amotos_get_template('car/' . $form . '/'.$value.'.php');?>
                <div class="amotos-step-nav">
                <?php
                if($prev_key!=''):?>
                    <button class="amotos-btn-prev" aria-controls="step-<?php echo esc_attr($prev_key); ?>"
                        type="button" title="<?php esc_attr_e('Previous', 'auto-moto-stock') ?>"><i class="fa fa-angle-left"></i><span><?php esc_html_e('Previous', 'auto-moto-stock') ?></span></button>
                <?php endif; ?>
                    <button class="amotos-btn-edit" type="button" title="<?php esc_attr_e('Show All Fields', 'auto-moto-stock') ?>"><?php esc_html_e('Show All', 'auto-moto-stock') ?></button>
                <?php if($next_key!=''):?>
                    <button class="amotos-btn-next" aria-controls="step-<?php echo esc_attr($next_key); ?>"
                        type="button" title="<?php esc_attr_e('Next', 'auto-moto-stock') ?>"><span><?php esc_html_e('Next', 'auto-moto-stock') ?></span><i class="fa fa-angle-right"></i></button>
                <?php else:?>
                    <input type="submit" name="submit_car" class="button btn-submit-car"
                           value="<?php echo esc_attr($submit_button_text); ?>"/>
                <?php endif;?>
                </div>
            </fieldset>
            <?php
        }
        do_action('amotos_after_submit_car'); ?>
        <?php wp_nonce_field('amotos_submit_car_action', 'amotos_submit_car_nonce_field'); ?>
        <input type="hidden" name="car_form" value="<?php echo esc_attr($form); ?>"/>
        <input type="hidden" name="car_action" value="<?php echo esc_attr($action) ?>"/>
        <input type="hidden" name="car_id" value="<?php echo esc_attr($car_id); ?>"/>
        <input type="hidden" name="step" value="<?php echo esc_attr($step); ?>"/>
    </form>
</section>