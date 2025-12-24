<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// account
require_once AMOTOS_PLUGIN_DIR . 'public/partials/account/class-amotos-profile.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/account/class-amotos-login-register.php';
// manager
require_once AMOTOS_PLUGIN_DIR . 'public/partials/mahager/class-amotos-manager.php';
// vehicle
require_once AMOTOS_PLUGIN_DIR . 'public/partials/car/class-amotos-car.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/car/class-amotos-search.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/car/class-amotos-save-search.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/car/class-amotos-compare.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/payment/class-amotos-payment.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/payment/class-amotos-trans-action.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/package/class-amotos-package.php';
require_once AMOTOS_PLUGIN_DIR . 'public/partials/invoice/class-amotos-invoice.php';