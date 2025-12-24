<?php
$index = 0;
?>
<div class="squick-fonts-wrapper wrap" data-nonce="<?php echo esc_attr(wp_create_nonce('squick_font_management')) ?>">
    <div class="squick-font-header">
        <h1><?php esc_html_e('Fonts Management', 'auto-moto-stock'); ?></h1>
    </div>
    <div class="squick-font-content">
        <div class="squick-font-listing">
            <h4 class="squick-title">
                <span><?php esc_html_e('Available Fonts', 'auto-moto-stock'); ?></span>
                <input id="search_fonts" type="text" placeholder="<?php esc_attr_e('Search fonts...', 'auto-moto-stock'); ?>"/>
            </h4>
            <div class="squick-font-listing-inner">
                <ul class="squick-font-type squick-clearfix">
                    <?php foreach (SQUICK_Core_Fonts::getInstance()->getFontSources() as $key => $value): ?>
                        <?php if ($index): ?>
                            <li><a href="#" data-ref="<?php echo esc_attr($key); ?>"><?php echo esc_html($value) ?></a></li>
                        <?php else:; ?>
                            <li class="active"><a href="#" data-ref="<?php echo esc_attr($key); ?>"><?php echo esc_html($value) ?></a></li>
                        <?php endif;?>
                        <?php $index++; ?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="squick-font-active">
            <h4 class="squick-title"><?php esc_html_e('Active Fonts', 'auto-moto-stock'); ?> <button class="button squick-reset-active-fonts" type="button"><i class="dashicons dashicons-image-rotate"></i> <?php esc_html_e('Reset Fonts', 'auto-moto-stock'); ?></button></h4>
            <div class="squick-font-active-listing">

            </div>
        </div>
    </div>
</div>