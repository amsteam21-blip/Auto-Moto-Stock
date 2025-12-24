<script type="text/html" id="tmpl-squick-icons-popup">
	<div id="squick-popup-icon-target" class="squick-popup-icon-wrapper-hide">
		<div id="squick-popup-icon-wrapper" class="squick-popup-icon-wrapper stu-popup-container">
			<div class="stu-popup">
				<h4 class="stu-popup-header">
					<strong><?php esc_html_e( 'Select An Icon', 'auto-moto-stock' ); ?></strong>
				</h4>
				<div class="stu-popup-body squick-popup-icon-content">
					<div class="squick-popup-icon-left">
						<div class="squick-popup-icon-search">
							<input type="text" placeholder="<?php esc_attr_e( 'Search Icon...', 'auto-moto-stock' ); ?>"/>
						</div>
						<div class="squick-popup-icon-font">
							<select>
								<# _.each(data, function(item, index) { #>
								<option value="{{index}}">{{item.label}}</option>
								<# }); #>
							</select>
						</div>
						<div class="squick-popup-icon-font-link">
							<div class="squick-popup-icon-font-link-inner">
								<# _.each(data, function(item, fontId) { #>
								<div class="squick-popup-icon-group-link" data-font-id="{{fontId}}">
									<ul>
										<# if (item.iconGroup.length > 1) { #>
										<li><a data-id="" href="#"><?php esc_html_e( 'All Icons', 'auto-moto-stock' ); ?></a>
											<span>({{item.total}})</span>
										</li>
										<# } #>
										<# _.each(item.iconGroup, function(group) { #>
										<li><a data-id="{{group.id}}" href="#">{{group.title}}</a> <span>({{group.icons.length}})</span>
										</li>
										<# }); #>
									</ul>
								</div>
								<# }); #>
							</div>
						</div>
					</div>
					<div class="squick-popup-icon-right">
						<div class="squick-popup-icon-listing">
							<# _.each(data, function(item, fontId) { #>
							<div class="squick-popup-icon-group-section" data-font-id="{{fontId}}">
								<h4 class="squick-popup-icon-group-title"
								    data-msg-all="<?php esc_attr_e( 'All Icons', 'auto-moto-stock' ); ?>"
								    data-msg-search="<?php esc_attr_e( 'Search result for "{0}"', 'auto-moto-stock' ); ?>"><?php esc_html_e( 'All Icons', 'auto-moto-stock' ); ?></h4>
								<ul></ul>
								<div class="squick-popup-icon-group-load-more">
									<button type="button"
									        class="button button-primary"><?php esc_html_e( 'Load more...', 'auto-moto-stock' ) ?></button>
								</div>
							</div>
							<# }); #>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>