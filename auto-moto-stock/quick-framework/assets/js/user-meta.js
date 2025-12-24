var SQUICK_USER_META;
(function($) {
	"use strict";

	SQUICK_USER_META = {
		init: function() {
			this.headerToggle();
		},
		headerToggle: function () {
			$(document).on('click','.squick-user-meta-header', function () {
				$(this).toggleClass('in');
				$(this).next().slideToggle();
			});
		}
	};
	$(document).ready(function() {
		SQUICK_USER_META.init();
	});
})(jQuery);