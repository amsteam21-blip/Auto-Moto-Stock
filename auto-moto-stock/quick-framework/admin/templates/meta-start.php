<?php
/**
 * @var $list_section
 * @var $current_preset
 */
?>
<div class="squick-meta-box-wrap">
	<div class="squick-meta-box-wrap-inner">
		<?php
		SQUICK()->helper()->getTemplate('admin/templates/meta-section', array(
			'list_section' => $list_section,
			'current_preset' => $current_preset
		));
		?>
		<div class="squick-meta-box-fields-wrapper">
			<div class="squick-meta-box-fields">