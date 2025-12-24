<script type="text/html" id="tmpl-squick-active-fonts">
    <div class="squick-font-active-container" id="active_fonts" style="display: block">
        <form action="<?php echo esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=squick_save_active_font'), 'squick_font_management', '_nonce')) ?>" method="post">
            <div class="squick-font-active-items">
                <# _.each(data.fonts.items, function(item, index) { console.log(item); #>
                    <div class="squick-font-active-item" data-name="{{item.family}}">
                        <div class="squick-font-active-item-header">
                            <h4>{{typeof(item.name) == 'undefined' ? item.family : item.name}}</h4>
	                        <a href="#" class="squick-font-item-change-font"
	                           data-type="{{item.kind === 'webfonts#webfont' ? 'google' : item.kind}}"
	                           title="<?php echo esc_attr__('Replace Font', 'auto-moto-stock') ?>">
		                        <i class="dashicons dashicons-randomize"></i>
	                        </a>
                            <a href="#" class="squick-font-active-item-remove" title="<?php esc_attr_e('Remove font!', 'auto-moto-stock'); ?>">
                                <i class="dashicons dashicons-no-alt"></i>
                            </a>
                        </div>
                        <div class="squick-font-active-content">
                            <div class="squick-font-active-preview" style="font-family: {{SQUICK_Fonts.getFontFamily(item.family)}}">
                                <p class="squick-font-active-preview-title">
                                    <?php esc_html_e('Welcome to font preview!', 'auto-moto-stock'); ?>
                                </p>
                                <p class="squick-font-active-preview-text">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890‘?’“!”(%)[#]{@}/&<-+÷×=>®©$€£¥¢:;,.*</p>
                            </div>
                            <input type="hidden" value="{{item.kind}}" name="font[{{index}}][kind]"/>
                            <div class="squick-row">
                                <div class="squick-variant">
                                    <h5><?php esc_html_e('Variants', 'auto-moto-stock'); ?></h5>
                                    <div class="squick-clearfix">
                                        <# _.each(item.default_variants, function(v, vIndex) { #>
                                            <# if (item.variants.indexOf(v) != -1) {#>
                                                <label><input name="font[{{index}}][variants][]" type="checkbox" value="{{v}}" checked="checked" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                <#} else {#>
                                                    <label><input name="font[{{index}}][variants][]" type="checkbox" value="{{v}}" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                    <#}#>
                                                        <# }); #>
                                    </div>
                                </div>
                                <div class="squick-subset">
                                    <h5><?php esc_html_e('Subsets', 'auto-moto-stock'); ?></h5>
                                    <div class="squick-clearfix">
                                        <# _.each(item.default_subsets, function(v, vIndex) { #>
                                            <# if (item.subsets.indexOf(v) != -1) { #>
                                                <label><input name="font[{{index}}][subsets][]" type="checkbox" value="{{v}}" checked="checked" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                <#} else {#>
                                                    <label><input name="font[{{index}}][subsets][]" type="checkbox" value="{{v}}" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                    <#}#>
                                                        <# }); #>
                                    </div>
                                </div>
                            </div>
                            <div class="squick-row squick-font-selector">
                                <h5><?php esc_html_e('Selector apply:', 'auto-moto-stock'); ?></h5>
                                <input name="font[{{index}}][selector]" type="text" value="{{item.selector}}"/>
                            </div>
                        </div>
                    </div>
                    <# }); #>
            </div>
            <div class="squick-save-active-font">
                <button class="button button-primary" type="submit"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Save Changes', 'auto-moto-stock'); ?></button>

            </div>
        </form>
    </div>
</script>