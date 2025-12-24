<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<script type="text/template" id="tmpl-amotos__map_popup_template">
	<div class="amotos__map-popup">
		<div class="amotos__map-popup-thumb">
			<a href="{{{data.url}}}" target="_blank" title="{{{data.title}}}">
				{{{data.thumb}}}
			</a>
		</div>
		<div class="amotos__map-popup-content">
			<h5 class="amotos__map-popup-title">
				<a href="{{{data.url}}}" target="_blank">{{{data.title}}}</a>
			</h5>
            {{{data.price}}}
			<span class="amotos__map-popup-address">
                <i class="fa fa-map-marker"></i> {{{data.address}}}
            </span>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-amotos__map_popup_simple_template">
    <div class="amotos__map-popup">
        {{{data.content}}}
    </div>
</script>

<script type="text/template" id="tmpl-amotos__nearby_place_item_template">
    <div class="amotos__nearby-place-item">
        <div class="amotos__nearby-place-item-content"><span class="amotos__name">{{{data.name}}}</span><span class="amotos__dot"></span><span class="amotos__distant">{{{data.distant}}} {{{data.unit}}}</span></div>
        <div class="amotos__nearby-place-item-type">{{{data.type}}}</div>
    </div>
</script>






