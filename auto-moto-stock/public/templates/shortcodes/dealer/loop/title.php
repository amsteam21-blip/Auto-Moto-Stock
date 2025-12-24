<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $dealer WP_Term
 */
?>
<h2 class="amotos__loop-dealer-title">
    <a href="<?php echo esc_url( get_term_link( $dealer->slug, 'dealer' ) ); ?>" title="<?php echo esc_attr( $dealer->name ); ?>"><?php echo esc_html( $dealer->name ); ?></a>
</h2>
