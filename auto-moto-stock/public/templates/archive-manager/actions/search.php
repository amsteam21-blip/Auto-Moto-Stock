<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$keyword = isset($_GET['manager_name']) ? amotos_clean(wp_unslash($_GET['manager_name'])) : '';
?>
<div class="amotos__apa-item amotos__apa-search">
    <form method="get" action="<?php echo esc_url(get_post_type_archive_link( 'manager' )) ; ?>">
        <div class="input-group">
            <input type="search" class="form-control" value="<?php echo esc_attr($keyword)?>" name="manager_name" placeholder="<?php echo esc_attr__( 'Search...', 'auto-moto-stock' ); ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
</div>
