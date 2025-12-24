<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $car
 * @var $action
 */
do_action('amotos_car_submitted_content_before', sanitize_title($car->post_status), $car);
?>
    <div class="car-submitted-content">
        <div class="amotos-message alert alert-success" role="alert">
            <?php
            switch ($car->post_status) :
                case 'publish' :
                    if($action=='new')
                    {
                        /* translators: %s: link of Vehicle. */
                        echo wp_kses_post(sprintf(__('<strong>Success!</strong> Your Vehicle was submitted successfully. To view your Vehicle listing <a class="accent-color" href="%s">click here</a>.', 'auto-moto-stock'),get_permalink($car->ID)));
                    }
                    else
                    {
                        /* translators: %s: link of Vehicle. */
                        echo wp_kses_post(sprintf(__('<strong>Success!</strong> Your changes have been saved. To view your Vehicle listing <a class="accent-color" href="%s">click here</a>.', 'auto-moto-stock'),get_permalink($car->ID)));
                    }
                    break;
                case 'pending' :
                    if($action=='new')
                    {
                        echo wp_kses_post(sprintf(__('<strong>Success!</strong> Your Vehicle was submitted successfully. Once approved, your listing will be visible on the site.', 'auto-moto-stock'), get_permalink($car->ID)));
                    }
                    else{
                        echo  wp_kses_post(__('<strong>Success!</strong> Your changes have been saved. Once approved, your listing will be visible on the site.', 'auto-moto-stock'));
                    }
                    break;
                default :
                    do_action('amotos_car_submitted_content_' . str_replace('-', '_', sanitize_title($car->post_status)), $car);
                    break;
            endswitch;
            ?></div>
    </div>
<?php
do_action('amotos_car_submitted_content_after', sanitize_title($car->post_status), $car);