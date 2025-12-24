<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $car_data,$hide_car_fields,$car_meta_data;
$user_id = get_current_user_id();

?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Media Files', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-media">
        <label class="media-gallery-title"><?php esc_html_e( 'Photo Gallery', 'auto-moto-stock' ); ?></label>
        <div class="amotos-car-gallery">
            <div class="media-gallery">
	            <div id="car_gallery_thumbs_container" class="row">
		            <?php
		            $car_images = get_post_meta( $car_data->ID,AMOTOS_METABOX_PREFIX. 'car_images', true );
		            $car_images = explode('|', $car_images);
		            $featured_image_id = get_post_thumbnail_id( $car_data->ID );
		            if($featured_image_id) {
			            $car_images[] = $featured_image_id;
		            }
		            $car_images = array_unique($car_images);


		            if( !empty($car_images[0])) {
			            foreach ($car_images as $attach_id) {
				            $is_featured_image = ($featured_image_id == $attach_id);
				            $featured_icon = ($is_featured_image) ? 'fa-star' : 'fa-star-o';

                            $delete_nonce = wp_create_nonce("AMOTOS_Delete_Car_Attachment_{$user_id}_{$attach_id}_{$car_data->ID}");

				            echo '<div class="col-sm-2 media-thumb-wrap __thumb">';
				            echo '<figure class="media-thumb">';
				            echo wp_get_attachment_image($attach_id, 'thumbnail');
				            echo '<div class="media-item-actions">';
				            echo '<a class="icon icon-delete" data-nonce="' . esc_attr($delete_nonce) . '" data-car-id="' . esc_attr(intval($car_data->ID))  . '" data-attachment-id="' . esc_attr(intval($attach_id))  . '" href="javascript:void(0)">';
				            echo '<i class="fa fa-trash-o"></i>';
				            echo '</a>';
				            echo '<a class="icon icon-fav icon-featured" data-car-id="' . esc_attr(intval($car_data->ID))  . '" data-attachment-id="' . esc_attr(intval($attach_id))  . '" href="javascript:void(0)">';
				            echo '<i class="fa ' . esc_attr($featured_icon) . '"></i>';
				            echo '</a>';
				            echo '<input type="hidden" class="car_image_ids" name="car_image_ids[]" value="' . esc_attr(intval($attach_id))  . '">';
				            echo '<span style="display: none;" class="icon icon-loader">';
				            echo '<i class="fa fa-spinner fa-spin"></i>';
				            echo '</span>';
				            echo '</div>';
				            if ($is_featured_image) {
					            echo '<input type="hidden" class="featured-image-id" name="featured_image_id" value="' . esc_attr(intval($attach_id))  . '">';
				            }
				            echo '</figure>';
				            echo '</div>';
			            }
		            }
		            ?>
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
		            <?php
		            $car_attachment_arg =  get_post_meta( $car_data->ID,AMOTOS_METABOX_PREFIX. 'car_attachments', false );
		            $car_attachments=(isset($car_attachment_arg) && is_array($car_attachment_arg) && count( $car_attachment_arg ) > 0)? $car_attachment_arg[0]: '';
		            $car_attachments = explode('|', $car_attachments);
		            $car_attachments = array_unique($car_attachments);
		            if($car_attachment_arg && !empty($car_attachments[0])) {
			            foreach ($car_attachments as $attach_id) {
				            $attach_url = wp_get_attachment_url( $attach_id );
				            $file_type          = wp_check_filetype( $attach_url);
				            $file_type_name = isset( $file_type['ext'] ) ? $file_type['ext'] : '';
				            $thumb_url = AMOTOS_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type_name . '.png';
				            $file_name          = basename($attach_url);

				            $delete_nonce = wp_create_nonce("AMOTOS_Delete_Car_Attachment_{$user_id}_{$attach_id}_{$car_data->ID}");

				            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 media-thumb-wrap __thumb">';
				            echo '<figure class="media-thumb">';
				            echo '<img src="'. esc_url($thumb_url)  .'">';
				            echo '<a href="'. esc_url($attach_url) .'">'. esc_html($file_name)  .'</a>';
				            echo '<div class="media-item-actions">';
				            echo '<a class="icon icon-delete" data-nonce="' . esc_attr($delete_nonce) . '" data-car-id="' . esc_attr(intval($car_data->ID))  . '" data-attachment-id="' . esc_attr(intval($attach_id))  . '" href="javascript:void(0)">';
				            echo '<i class="fa fa-trash-o"></i>';
				            echo '</a>';
				            echo '<input type="hidden" class="car_attachment_ids" name="car_attachment_ids[]" value="' . esc_attr(intval($attach_id))  . '">';
				            echo '<span style="display: none;" class="icon icon-loader">';
				            echo '<i class="fa fa-spinner fa-spin"></i>';
				            echo '</span>';
				            echo '</div>';
				            echo '</figure>';
				            echo '</div>';
			            }
		            }
		            ?>
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
                    echo wp_kses_post( sprintf(__('Allowed Extensions: <span class="attachment-file-type">%s</span>','auto-moto-stock'),$attachment_file_type));
                    ?></p>
            </div>
            <div id="amotos_attachments_errors_log"></div>
        </div>
        <?php endif; ?>
        <div class="car-media-other row">
            <?php if (!in_array("car_video_url", $hide_car_fields)):?>
                <div class="car-video-url col-sm-6">
                    <label for="car_video_url"><?php esc_html_e('Video URL', 'auto-moto-stock'); ?></label>
                    <input type="text" class="form-control" name="car_video_url" id="car_video_url"
                           placeholder="<?php esc_attr_e('YouTube, Vimeo', 'auto-moto-stock'); ?>"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_video_url'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_video_url'][0]);
                           } ?>">
                </div>
            <?php endif; ?>
            <?php if (!in_array("car_image_360", $hide_car_fields)) :
                $car_image_360_arr = get_post_meta( $car_data->ID,AMOTOS_METABOX_PREFIX. 'car_image_360', false );
                $car_image_360_id=(isset($car_image_360_arr) && is_array($car_image_360_arr) && count( $car_image_360_arr ) > 0)? $car_image_360_arr[0]['id']: '';
                $car_image_360_url=(isset($car_image_360_arr) && is_array($car_image_360_arr) && count( $car_image_360_arr ) > 0)? $car_image_360_arr[0]['url']: '';
            ?>
            <div class="car-image-360 col-sm-6">
                <label for="image_360_url"><?php esc_html_e('Image 360', 'auto-moto-stock'); ?></label>
                <div id="amotos_image_360_plupload_container" class="file-upload-block">
                    <input
                        name="car_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="amotos_image_360_url form-control" value="<?php echo esc_url($car_image_360_url); ?>">
                    <button type="button" id="amotos_select_images_360" style="position: absolute" title="<?php esc_attr_e('Choose image','auto-moto-stock') ?>" class="amotos_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="amotos_image_360_id"
                           name="car_image_360_id"
                           value="<?php echo esc_attr($car_image_360_id); ?>" id="amotos_image_360_id"/>

                </div>
                <div id="amotos_image_360_errors_log"></div>
                <?php if(!empty($car_image_360_url)):?>
                <div id="amotos_car_image_360_view" data-plugin-url="<?php echo esc_url(AMOTOS_PLUGIN_URL); ?>">
                    <iframe width="100%" height="200" scrolling="no" allowfullscreen src="<?php echo esc_url(AMOTOS_PLUGIN_URL."public/assets/packages/vr-view/index.html?image=".esc_url($car_image_360_url)) ; ?>"></iframe>
                </div>
                <?php endif;?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>