<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $extra_class
 * @var $rating
 * @var $rating_data
 * @var $total_reviews
 * @var $type // manager | car
 * @var $comments
 * @var $my_rating
 * @var $my_comment
 */
$wrapper_classes = array(
    'amotos__reviews',
    "{$type}-reviews"
);

if (isset($extra_class)) {
    $wrapper_classes[] = $extra_class;
}

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('Ratings & Reviews', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element">
        <div class="aggregate-rating" data-score="<?php echo esc_attr(round($rating, 2)) ; ?>" itemscope itemtype="<?php echo esc_attr(amotos_server_protocol()); ?>schema.org/AggregateRating">
            <div class="ratings-summary">
                <span class="ratings-average" itemveh="ratingValue"><?php echo esc_attr(round($rating, 2)) ; ?></span>
                <input class="amotos__start-rating" readonly="readonly" value="<?php echo esc_attr($rating); ?>" type="number" data-size="sm">
                <span class="ratings-count" itemveh="reviewCount">
                    <?php
                        /* translators: %s: Number of reviews. */
                        echo esc_html(sprintf( _n( '%s Review', '%s Reviews', $total_reviews, 'auto-moto-stock' ), $total_reviews ));
                    ?>
                </span>
            </div>
            <div class="overall-rating">
                <ul class="reviews-box">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php $percent = ($total_reviews > 0) && isset($rating_data[$i]) ? round(( $rating_data[$i] / $total_reviews ) * 100, 2) : 0;?>
                        <li>
                            <span class="label"><?php echo esc_html($i); ?></span>
                            <span class="item-list">
                                <span style="width: <?php echo esc_attr($percent)?>%"></span>
                            </span>
                            <span class="label percent"><?php echo esc_html($percent); ?>%</span>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <h4 class="reviews-count">
            <?php
                /* translators: %s: Number of reviews. */
                echo esc_html(sprintf( _n( '%s Review', '%s Reviews', $total_reviews, 'auto-moto-stock' ), $total_reviews ));
            ?>
        </h4>
        <?php if (count($comments) > 0): ?>
            <ul class="reviews-list">
                <?php foreach ($comments as $comment): ?>
                <?php
                    $user_custom_picture = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_custom_picture', $comment->user_id);
                    $author_picture_id = get_the_author_meta(AMOTOS_METABOX_PREFIX . 'author_picture_id', $comment->user_id);
                    $author_picture_id = intval($author_picture_id);
                    $no_avatar_src = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
                    $width = 80;
                    $height = 80;
                    $default_avatar = amotos_get_option('default_user_avatar', '');
                    if ($default_avatar != '') {
                        if (is_array($default_avatar) && $default_avatar['url'] != '') {
                            $resize = amotos_image_resize_url($default_avatar['url'], $width, $height, true);
                            if ($resize != null && is_array($resize)) {
                                $no_avatar_src = $resize['url'];
                            }
                        }
                    }
                    $user_link = get_author_posts_url($comment->user_id);
                    if ($author_picture_id) {
                        $avatar_src = amotos_image_resize_id($author_picture_id, $width, $height);
                    }

                    if (empty($avatar_src)) {
                        $avatar_src = $user_custom_picture;
                    }

                    $author_display_name = get_the_author_meta( 'display_name', $comment->user_id );

                    ?>
                    <li class="media" itemscope itemtype="<?php echo esc_attr(amotos_server_protocol()); ?>schema.org/Review">
                        <div class="media-left" itemveh="author" itemscope itemtype="<?php echo esc_attr(amotos_server_protocol()); ?>schema.org/Person">
                            <figure>
                                <a href="<?php echo esc_url($user_link)?>">
                                    <img src="<?php echo esc_url($avatar_src)?>" onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';" alt="<?php echo esc_attr($author_display_name)?>">
                                </a>
                            </figure>
                        </div>
                        <div class="media-body" itemveh="reviewBody">
                            <h4 class="media-heading"><a href="<?php echo esc_url( $user_link ); ?>"><?php echo esc_html($author_display_name)?></a></h4>
                            <div class="review-date-rating">
                                <span class="review-date"><i class="fa fa-calendar"></i><?php echo esc_html(amotos_get_comment_time($comment->comment_id)); ?></span>
                                <input class="amotos__start-rating" readonly="readonly" value="<?php echo esc_attr($comment->meta_value); ?>" type="number" data-size="xs">
                            </div>
                            <p class="review-content"> <?php echo esc_html($comment->comment_content); ?> </p>
                            <?php if ( $comment->comment_approved == 0 ) :?>
                                <span class="waiting-for-approval"> <?php esc_html_e( 'Waiting for approval', 'auto-moto-stock' ); ?> </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="add-new-review">
            <?php if (!is_user_logged_in()): ?>
                <h4 class="review-title">
                    <a href="#" class="login-for-review" data-toggle="modal" data-target="#amotos_signin_modal"><?php echo esc_html__('Login for Review', 'auto-moto-stock') ?></a>
                </h4>
            <?php else: ?>
                <h4 class="review-title"><?php echo esc_html__( 'Write a Review', 'auto-moto-stock' ); ?> </h4>
                <form action="post" action="#" novalidate>
                    <div class="form-group">
                        <label class="sr-only" for="<?php echo esc_attr($type) ?>_rating"> <?php esc_html_e('Write a Review', 'auto-moto-stock'); ?> </label>
                        <input class="amotos__start-rating" id="<?php echo esc_attr($type) ?>_rating" name="rating"  value="<?php echo esc_attr($my_rating); ?>" type="number" data-size="xs">
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <textarea required="required" class="form-control" rows="5" name="message" placeholder="<?php esc_attr_e('Your review', 'auto-moto-stock'); ?>"><?php echo esc_textarea($my_comment)?></textarea>
                    </div>
                    <button type="submit" class="amotos__btn-submit-rating btn btn-default"><?php esc_html_e('Submit Review', 'auto-moto-stock'); ?></button>
                    <?php wp_nonce_field('amotos_submit_review_ajax_nonce', 'amotos_security_submit_review'); ?>
                    <?php if ($type === 'manager'): ?>
                        <input type="hidden" name="action" value="amotos_manager_submit_review_ajax">
                        <input type="hidden" name="manager_id" value="<?php the_ID(); ?>">
                    <?php endif; ?>

                    <?php if ($type === 'car'): ?>
                        <input type="hidden" name="action" value="amotos_car_submit_review_ajax">
                        <input type="hidden" name="car_id" value="<?php the_ID(); ?>">
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
