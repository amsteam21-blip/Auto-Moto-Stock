(function ($) {
	"use strict";
	$(document).ready(function () {
		function amotos_manager_paging() {
			var handle = true;
			$('.paging-navigation', '.manager-paging-wrap').each(function () {
				$('a', $(this)).off('click').on('click', function (event) {
					event.preventDefault();
					if(handle) {
						handle = false;
						var $this = $(this);
						var href = $this.attr('href'),
							data_paged = AMOTOS.get_page_number_from_href(href),
							$wrapper = $this.closest('.amotos-manager-wrap'),
							$paging = $wrapper.find('.manager-paging-wrap'),
							manager_content = $wrapper.find('.amotos-manager'),
							manager_wrap = $this.closest('.amotos-manager-wrap');

						$.ajax({
							url: $paging.data('admin-url'),
							data: {
								action: 'amotos_manager_paging_ajax',
								layout: $paging.data('layout'),
								items: $paging.data('items'),
								item_amount: $paging.data('item-amount'),
								image_size: $paging.data('image-size'),
								show_paging: $paging.data('show-paging'),
								post_not_in: $paging.data('post-not-in'),
								paged: data_paged
							},
							success: function (html) {
								var $newElems = $('.manager-item', html),
									$newPaging = $('.manager-paging-wrap', html);

								manager_content.css('opacity', 0);

								manager_content.html($newElems);
								AMOTOS.set_item_effect($newElems, 'hide');
								var contentTop = manager_content.offset().top - 60;
								$('html,body').animate({scrollTop: +contentTop + 'px'}, 500);
								manager_content.css('opacity', 1);
								manager_content.imagesLoaded(function () {
									$newElems = $('.manager-item', manager_content);
									AMOTOS.set_item_effect($newElems, 'show');
									$paging.remove();
									$wrapper.append($newPaging);
									amotos_manager_paging();
									amotos_manager_paging_control();
								});
								handle = true;
							},
							error: function () {
								handle = true;
							}
						});
					}
				})
			});
		}
		amotos_manager_paging();
		function amotos_manager_paging_control() {
			$('.paging-navigation', '.amotos-manager').each(function () {
				var $this = $(this);
				if($this.find('a.next').length === 0) {
					$this.addClass('next-disable');
				} else {
					$this.removeClass('next-disable');
				}
			});
		}
	});
})(jQuery);