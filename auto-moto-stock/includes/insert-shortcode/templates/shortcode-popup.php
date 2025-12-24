<?php
/**
 * @var $amotos_shortcodes array
 */
?>
<div id="amotos-input-shortcode-wrap" style="display: none">
    <div id="amotos-input-shortcode">
        <div class="stu-popup-container" style="--stu-popup-width: 910px; --stu-content-min-height: 40vh">
            <div class="shortcode-content stu-popup">
                <div id="amotos-sc-header" class="stu-popup-header" style="padding-right: 60px">
                    <strong><?php echo esc_html__( 'AMS Shortcodes', 'auto-moto-stock' ) ?></strong>
                    <select id="amotos-shortcodes"
                            data-placeholder="<?php echo esc_attr__( "Choose a shortcode", 'auto-moto-stock' ) ?>">
                        <option></option>
		                <?php foreach ($amotos_shortcodes as $shortcode => $options): ?>
                            <option value="<?php echo esc_attr($shortcode) ?>"><?php echo esc_html($options['title']) ?></option>
		                <?php endforeach; ?>
                    </select>
                </div>
                <div class="stu-popup-body">
	                <?php foreach ($amotos_shortcodes as $shortcode => $options): ?>
                        <div class="shortcode-options" id="options-<?php echo esc_attr($shortcode) ?>" data-name="<?php echo esc_attr($shortcode) ?>" data-type="<?php echo esc_attr($options['type']) ?>">
			                <?php if ( ! empty( $options['attr'] ) ): $index = 0; ?>
				                <?php foreach ( $options['attr'] as $name => $attr_option ): ?>
					                <?php if ($index++ % 2 == 0): ?>
                                        <div class="two-option-wrap">
					                <?php endif; ?>
					                <?php amotos_get_admin_template('includes/insert-shortcode/templates/option-element.php', array(
						                'name' => $name,
						                'attr_option' => $attr_option
					                )); ?>
					                <?php if ($index % 2 == 0 || ($index >= count($options['attr'])) ): ?>
                                        </div>
                                        <div class="clearfix"></div>
					                <?php endif; ?>
				                <?php endforeach; ?>
			                <?php endif; ?>
                        </div>
	                <?php endforeach; ?>
                    <a class="btn" id="insert-shortcode"><?php echo esc_html__( 'Insert Shortcode', 'auto-moto-stock' ) ?></a>
                </div>
            </div>
        </div>
    </div>
</div>