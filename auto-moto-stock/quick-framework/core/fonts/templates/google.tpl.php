<script type="text/html" id="tmpl-squick-google-fonts">
    <div class="squick-font-container" id="google_fonts" style="display: block">
        <ul class="squick-font-categories squick-clearfix">
            <# _.each(data.fonts.categories, function(item, index) { #>
                <# if (index == 0) {#>
                    <li class="active" data-ref="{{item.name}}"><a href="#">{{item.name}} ({{item.count}})</a></li>
                    <#} else { #>
                        <li data-ref="{{item.name}}"><a href="#">{{item.name}} ({{item.count}})</a></li>
                        <#}#>
                            <# }); #>
        </ul>
        <div class="squick-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="squick-font-item" data-category="{{item.category}}" data-name="{{item.family}}" style="display: none">
                    <div class="squick-font-item-name">{{item.family}}</div>
                    <div class="squick-font-item-action">
                        <a href="https://www.google.com/fonts/specimen/{{item.family.replace(' ','+')}}" target="_blank"
                           title="<?php esc_attr_e('Preview font', 'auto-moto-stock'); ?>"
                           class="squick-font-item-action-preview"><i class="dashicons dashicons-visibility"></i></a>
                        <#if (item.using) {#>
                            <a href="#" class="squick-font-item-action-add" data-type="google"
                               title="<?php esc_attr_e('Font activated', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="squick-font-item-action-add" data-type="google"
                                   title="<?php esc_attr_e('Use this font', 'auto-moto-stock'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>

	                    <a href="#" class="squick-font-item-change-font"
	                       data-type="google"
	                       title="<?php echo esc_attr__('Replace Font', 'auto-moto-stock') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
    </div>
</script>