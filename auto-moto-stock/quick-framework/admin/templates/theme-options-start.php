<?php
/**
 * @var string $option_name
 * @var string $version
 * @var string $page
 * @var string $current_preset
 * @var string $page_title
 * @var bool $preset
 */
$theme = wp_get_theme();
if (!$preset) {
	$current_preset = '';
}
SQUICK()->adminThemeOption()->is_theme_option_page = true;
SQUICK()->adminThemeOption()->current_preset = $current_preset;
SQUICK()->adminThemeOption()->current_page = $page;
$preset_listing = SQUICK()->adminThemeOption()->getPresetOptionKeys($option_name);
?>
<div class="squick-theme-options-page-loading">
	<div class="loader"></div>
</div>
<div class="wrap"><h2 style="display: none"></h2></div>
<div class="squick-theme-options-wrapper wrap" style="display: block">
	<form action="<?php echo esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=squick_save_options'), 'squick_save_options')) ?>"
          method="post" enctype="multipart/form-data" class="squick-theme-options-form">
        <?php wp_nonce_field('squick_theme_options_management') ?>
		<input type="hidden" id="_current_page" name="_current_page" value="<?php echo esc_attr($page); ?>" />
		<input type="hidden" id="_current_preset" name="_current_preset" value="<?php echo esc_attr($current_preset); ?>" />
		<?php if (SQUICK()->adminThemeOption()->current_section !== ''): ?>
			<input type="hidden" id="_current_section" name="_current_section" value="<?php echo esc_attr(SQUICK()->adminThemeOption()->current_section) ?>">
		<?php endif; ?>
		<div class="squick-theme-options-header-wrapper">
			<div class="squick-theme-options-header squick-clearfix">
				<div class="squick-theme-options-title">
					<h1>
						<?php echo esc_html($page_title) ?>
						<span><?php esc_html_e('version', 'auto-moto-stock'); ?> <?php echo esc_html($version); ?></span>
					</h1>
					<?php if (!empty($desc)): ?>
						<p><?php echo wp_kses_post($desc) ?></p>
					<?php endif; ?>
				</div>
				<?php if ($preset && !empty($current_preset)): ?>
					<div class="squick-preset-action">
						<button type="button" class="button button-success squick-preset-action-make-default"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Make Default Options', 'auto-moto-stock'); ?></button>
						<button type="button" class="button button-danger squick-preset-action-delete"><i class="dashicons dashicons-no-alt"></i> <?php esc_html_e('Delete Preset', 'auto-moto-stock'); ?></button>
						<a href="<?php echo esc_url(home_url('/?_squick_preset=' . $current_preset)); ?>" target="_blank" class="button"><i class="dashicons dashicons-visibility"></i> <?php esc_html_e('Preview', 'auto-moto-stock'); ?></a>
					</div>
				<?php endif;?>
			</div>
		</div>
		<div class="squick-theme-options-action-wrapper">
			<div class="squick-theme-options-action-inner squick-clearfix">
				<?php if ($preset): ?>
					<div class="squick-theme-options-preset">
						<div class="squick-theme-options-preset-select">
							<div>
								<?php esc_html_e('Select Preset Options...', 'auto-moto-stock'); ?>
								<i class="dashicons dashicons-arrow-down"></i>
							</div>
							<ul>
								<li data-preset=""><?php esc_html_e('Default Options', 'auto-moto-stock'); ?></li>
								<?php foreach ($preset_listing as $preset_name=> $preset_title): ?>
									<li data-preset="<?php echo esc_attr($preset_name); ?>"><?php echo esc_html($preset_title); ?></li>
								<?php endforeach;?>
							</ul>
						</div>
						<button type="button" class="button button-primary squick-theme-options-preset-create"><i class="dashicons dashicons-plus"></i> <?php esc_html_e('Create Preset Options', 'auto-moto-stock'); ?></button>
					</div>
				<?php endif;?>
				<div class="squick-theme-options-action">
					<button class="button button-success squick-theme-options-save-options" type="submit" name="squick_save_option"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Save Options', 'auto-moto-stock'); ?></button>
				</div>
			</div>
		</div>