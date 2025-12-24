<script type="text/html" id="tmpl-squick-select-popup">
	<div id="squick-popup-select-target">
		<div class="squick-popup-select-wrapper stu-popup-container">
			<div class="stu-popup squick-popup-select-content"
			<# if (data.popup_width) { #>
			style="width: {{data.popup_width}}"
			<# } #>>
				<h4 class="stu-popup-header">
					<strong>{{data.title}}</strong>
				</h4>
				<div class="stu-popup-body squick-popup-select-listing">
					<div class="squick-row">
						<# for (var item_key in data.options) { #>
						<div class="squick-col squick-col-{{12/(data.items)}}">
							<div class="squick-popup-select-item" data-value="{{item_key}}">
								<img src="{{ data.options[item_key].img}}"
								     data-thumb="{{ data.options[item_key].thumb}}"
								     alt="{{ data.options[item_key].label}}">
								<div class="squick-popup-select-item-footer">
									<span class="name">{{data.options[item_key].label}}</span>
									<span class="current"><?php esc_html_e('Current','auto-moto-stock') ?></span>
								</div>
							</div>
						</div>
						<# }; #>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>