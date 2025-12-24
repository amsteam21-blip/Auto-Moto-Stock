(function($){
	$(document).ready(function () {
		$('.amotos-insert-shortcode-button').on('click',function(){
			AMOTOS_POPUP.required_element();
			AMOTOS_POPUP.reset_fileds();
			STUtils.popup.show({
				target: '#amotos-input-shortcode-wrap',
				type: 'target',
				callback: function () {

				}
			});
		});
	});
})(jQuery);
