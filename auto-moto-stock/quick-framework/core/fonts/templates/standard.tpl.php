<script type="text/html" id="tmpl-squick-standard-fonts">
    <div class="squick-font-container" id="standard_fonts">
        <div class="squick-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="squick-font-item" data-name="{{item.family}}">
                    <div class="squick-font-item-name">{{item.name}}</div>
                    <div class="squick-font-item-action">
                        <#if (item.using) {#>
                            <a href="#" class="squick-font-item-action-add" data-type="standard"
                               title="<?php esc_attr_e('Font activated', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="squick-font-item-action-add" data-type="standard"
                                   title="<?php esc_attr_e('Use this font', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>
	                    <a href="#" class="squick-font-item-change-font"
	                       data-type="standard"
	                       title="<?php echo esc_attr__('Replace Font', 'auto-moto-stock') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
    </div>
</script>