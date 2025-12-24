<?php
/**
 * @var bool $is_exists_section
 */
?>		<div class="squick-theme-options-footer squick-clearfix">
			<div class="squick-theme-options-action">
				<button class="button squick-theme-options-import" type="button"><?php esc_html_e('Import/Export', 'auto-moto-stock'); ?></button>
				<?php if ($is_exists_section): ?>
					<button class="button squick-theme-options-reset-section" type="button"><?php esc_html_e('Reset Section', 'auto-moto-stock'); ?></button>
				<?php endif;?>
				<button class="button squick-theme-options-reset-options" type="button"><?php esc_html_e('Reset Options', 'auto-moto-stock'); ?></button>
			</div>
		</div>
	</form><!-- /.squick-theme-options-form -->
</div><!-- /.squick-theme-options-wrapper -->
