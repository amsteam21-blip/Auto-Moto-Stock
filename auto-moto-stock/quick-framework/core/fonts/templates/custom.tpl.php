<script type="text/html" id="tmpl-squick-custom-fonts">
    <div class="squick-font-container" id="custom_fonts">
	    <div id="squick-custom-font-popup" class="stu-popup-wrapper">
		    <div class="stu-popup-container squick-custom-font-popup">
			    <div class="stu-popup">
				    <h4 class="stu-popup-header">
					    <strong><?php esc_html_e( 'Add Custom Font', 'auto-moto-stock' ); ?></strong>
				    </h4>
				    <div class="stu-popup-body">
					    <form action="<?php echo esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=squick_upload_fonts'), 'squick_font_management', '_nonce')) ?>" method="post" enctype="multipart/form-data">
						    <div>
							    <label><?php esc_html_e('Font name:', 'auto-moto-stock'); ?></label>
							    <input type="text" name="name" required=""/>
						    </div>
						    <div>
							    <label><?php esc_html_e('Fonts files (zip):', 'auto-moto-stock'); ?></label>
							    <input type="file" name="file_font" required="" accept="application/zip"/>
							    <p><?php esc_html_e('File zip contains stylesheet.css and font files (accept: .woff, .eot, .svg, .ttf)', 'auto-moto-stock'); ?></p>
						    </div>
						    <div>
							    <button type="submit" class="button button-primary squick-custom-font"><?php esc_html_e('Add Custom Font', 'auto-moto-stock'); ?></button>
						    </div>
					    </form>
				    </div>
			    </div>
		    </div>
	    </div>
        <div class="squick-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="squick-font-item" data-name="{{item.family}}">
                    <div class="squick-font-item-name">{{typeof(item.name) == 'undefined' ? item.family : item.name}}</div>
                    <div class="squick-font-item-action">
	                    <#if (!item.is_default) {#>
	                    <a href="#" class="squick-font-item-action-delete" title="<?php esc_attr_e('Delete custom font', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-no-alt"></i></a>
	                    <# } #>
                        <#if (item.using) {#>
                            <a href="#" class="squick-font-item-action-add" data-type="custom"
                               title="<?php esc_attr_e('Font activated', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="squick-font-item-action-add" data-type="custom"
                                   title="<?php esc_attr_e('Use this font', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>
	                    <a href="#" class="squick-font-item-change-font"
	                       data-type="custom"
	                       title="<?php echo esc_attr__('Replace Font', 'auto-moto-stock') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
        <div class="squick-add-custom-font">
            <button class="button button-primary" type="button"><i class="dashicons dashicons-plus-alt2"></i> <?php esc_html_e('Add Custom Fonts', 'auto-moto-stock'); ?></button>
        </div>
    </div>
</script>