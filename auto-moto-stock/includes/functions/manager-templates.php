<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function amotos_template_manager_reviews() {
    $enable_comments_reviews_manager = amotos_get_option( 'enable_comments_reviews_manager', 0 );
    if ($enable_comments_reviews_manager == 2) {
        $rating = 0;
        $total_reviews = 0;
        $total_stars = 0;
        $my_rating = 0;
        $my_comment = '';
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $manager_id = get_the_ID();
        $rating_data = amotos_manager_get_rating($manager_id);

        $comments = amotos_manager_get_list_review($manager_id, $user_id);
        if ( $comments !== null ) {
            foreach ( $comments as $comment ) {
                if ( $comment->comment_approved == 1 ) {
                    $total_reviews++;
                    $total_stars += $comment->meta_value;
                }
            }
            if ( $total_reviews > 0 ) {
                $rating = ( $total_stars / $total_reviews );
            }
        }

        $my_review = amotos_manager_get_review_by_user_id($manager_id,$user_id);
        if ($my_review !== null) {
            $my_comment = $my_review->comment_content;
            $my_rating = $my_review->rate;
        }

        amotos_get_template('global/reviews.php',array(
            'extra_class' => 'single-manager-element amotos__single-manager-element',
            'rating' => $rating,
            'total_reviews' => $total_reviews,
            'rating_data' => $rating_data,
            'type' => 'manager',
            'comments' => $comments,
            'my_rating' => $my_rating,
            'my_comment' => $my_comment
        ));
    }
}

function amotos_template_archive_manager_heading($total_post = 0) {
    amotos_get_template( 'archive-manager/heading.php', array( 'total_post' => $total_post ) );
}

function amotos_template_archive_manager_action() {
    amotos_get_template( 'archive-manager/action.php');
}

function amotos_template_archive_manager_action_search() {
    amotos_get_template('archive-manager/actions/search.php');
}

function amotos_template_archive_manager_action_orderby() {
    $sort_by_list = amotos_manager_get_sort_by();
    amotos_get_template('archive-car/actions/orderby.php',array('sort_by_list' => $sort_by_list));
}

function amotos_template_archive_manager_action_switch_layout() {
    amotos_get_template('archive-car/actions/switch-layout.php',array('type' => 'manager'));
}

function amotos_template_single_manager_contact_form() {
    $manager_id = get_the_ID();
    $email = get_post_meta($manager_id,AMOTOS_METABOX_PREFIX . 'manager_email',true);
    if (empty($email)) {
        return;
    }
    $enable_captcha= amotos_enable_captcha('contact_manager');

    amotos_get_template('global/contact-form.php',array('email' =>  $email, 'enable_captcha' => $enable_captcha));
}



