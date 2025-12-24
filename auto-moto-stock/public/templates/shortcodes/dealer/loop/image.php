<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $dealer WP_Term
 * @var $image
 */
list( $width, $height ) = getimagesize( $image );
?>
<div class="dealer-avatar amotos__loop-dealer-avatar">
    <a href="<?php echo esc_url( get_term_link($dealer, 'dealer' ) ); ?>" title="<?php echo esc_attr( $dealer->name ); ?>">
        <img width="<?php echo esc_attr( $width ) ?>"
             height="<?php echo esc_attr( $height ) ?>"
             src="<?php echo esc_url( $image ) ?>"
             alt="<?php echo esc_attr( $dealer->name ); ?>"
             title="<?php echo esc_attr( $dealer->name ); ?>">
    </a>
</div>
