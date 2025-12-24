<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_car_fields;
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Media Files', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-media">
        <div class="amotos-car-gallery">
            <label class="media-gallery-title"><?php esc_html_e( 'Photo Gallery', 'auto-moto-stock' ); ?></label>
            <div class="media-gallery">
	            <div id="car_gallery_thumbs_container" class="row">
	            </div>
            </div>
            <div id="amotos_gallery_plupload_container" class="media-drag-drop">
                <h4>
                    <i class="fa fa-cloud-upload"></i> <?php esc_html_e('Drag and drop file here', 'auto-moto-stock'); ?>
                </h4>
                <h4><?php esc_html_e('or', 'auto-moto-stock'); ?></h4>
                <button type="button" id="amotos_select_gallery_images"
                        class="btn btn-primary"><?php esc_html_e('Select Images', 'auto-moto-stock'); ?></button>
            </div>
            <div id="amotos_gallery_errors_log"></div>
        </div>
        <?php if (!in_array("car_attachments", $hide_car_fields)): ?>
        <label class="media-attachments-title"><?php esc_html_e( 'File Attachments', 'auto-moto-stock' ); ?></label>
        <div class="amotos-car-attachments">
            <div class="media-attachments">
	            <div id="car_attachments_thumbs_container" class="row">
	            </div>
            </div>
            <div id="amotos_attachments_plupload_container" class="media-drag-drop">
                <h4>
                    <i class="fa fa-cloud-upload"></i> <?php esc_html_e('Drag and drop file here', 'auto-moto-stock'); ?>
                </h4>
                <h4><?php esc_html_e('or', 'auto-moto-stock'); ?></h4>
                <button type="button" id="amotos_select_file_attachments"
                        class="btn btn-primary"><?php esc_html_e('Select Files', 'auto-moto-stock'); ?></button>
                <p><?php
                    $attachment_file_type=amotos_get_option('attachment_file_type','pdf,txt,doc,docx');
                    /* translators: %s: attachment file type. */
                    echo  wp_kses_post(sprintf(__('Allowed Extensions: <span class="attachment-file-type">%s</span>','auto-moto-stock'),$attachment_file_type));
                    ?></p>
            </div>
            <div id="amotos_attachments_errors_log"></div>
        </div>
        <?php endif; ?>
        <div class="car-media-other row">
            <?php if (!in_array("car_video_url", $hide_car_fields)): ?>
            <div class="car-video-url col-sm-6">
                <label for="car_video_url"><?php esc_html_e('Video URL', 'auto-moto-stock'); ?></label>
                <input type="text" class="form-control" name="car_video_url" id="car_video_url"
                       placeholder="<?php esc_attr_e('YouTube, Vimeo', 'auto-moto-stock'); ?>">
            </div>
            <?php endif; ?>
            <?php if (!in_array("car_image_360", $hide_car_fields)) : ?>
            <div class="car-image-360 col-sm-6">
                <label for="image_360_url"><?php esc_html_e('Image 360', 'auto-moto-stock'); ?></label>
                <div id="amotos_image_360_plupload_container" class="file-upload-block">
                    <input
                        name="car_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="amotos_image_360_url form-control" value="">
                    <button type="button" id="amotos_select_images_360" style="position: absolute" title="<?php esc_attr_e('Choose image','auto-moto-stock') ?>" class="amotos_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="amotos_image_360_id"
                           name="car_image_360_id"
                           value="" id="amotos_image_360_id"/>
                </div>
                <div id="amotos_image_360_errors_log"></div>
                <div id="amotos_car_image_360_view" data-plugin-url="<?php echo esc_url(AMOTOS_PLUGIN_URL); ?>">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>