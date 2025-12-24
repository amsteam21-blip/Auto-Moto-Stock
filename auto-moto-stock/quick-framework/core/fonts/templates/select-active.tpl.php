<script type="text/html" id="tmpl-squick-popup-change-font">
	<div class="squick-popup-wrap">
		<div class="squick-popup-inner squick-popup-change-font" data-msg-confirm="<?php echo esc_attr__('Are you sure to change the font from "{1}" to "{2}?"', 'auto-moto-stock') ?>">
			<div class="squick-popup-header">
				<h4><?php echo esc_html__('Select font to replace','auto-moto-stock') ?></h4>
				<span class="squick-popup-close">×</span>
			</div>
			<div class="squick-popup-content">
				<# _.each(data, function(item, index) { #>
				<div class="squick-change-font-item">
					<span>{{item.family}}</span>
					<button type="button" class="button button-secondary button-small" data-name="{{item.family}}"><?php echo esc_html__('Change', 'auto-moto-stock') ?></button>
				</div>
				<# }); #>
			</div>
		</div>
	</div>
</script>