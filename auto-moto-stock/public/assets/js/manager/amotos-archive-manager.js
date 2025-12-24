(function ($) {
	'use strict';
	$(document).ready(function () {
		function amotos_archive_manager() {
			$('span', '.archive-manager-action .view-as').each(function() {
				var $this = $(this);
				if(window.location.href.indexOf("view_as") > -1 ){
					if(window.location.href.indexOf("view_as="+$this.data('view-as')) > -1) {
						$this.addClass('active');
					}
				} else {
					if($('.amotos-manager', '.amotos-archive-manager').hasClass($this.data('view-as'))) {
						$this.addClass('active');
					}
				}
				var handle = true;
				$this.on('click', function(event){
					var $view = $(this),
						$view_as = $view.data('view-as'),
						$manager_list = $view.closest('.amotos-archive-manager').find('.amotos-manager'),
						$ajax_url = $view.closest('.view-as').data('admin-url');
					if($view.hasClass('active') || !handle) {
						event.preventDefault();
						return false;
					} else {
						$view.closest('.view-as').find('span').removeClass('active');
						$view.addClass('active');
						$manager_list.fadeOut();
						setTimeout(function () {
							if ($view_as == 'manager-list') {
								$manager_list.removeClass('manager-grid').addClass('manager-list list-1-column');
							} else {
								$manager_list.removeClass('manager-list list-1-column').addClass('manager-grid');
							}
							$manager_list.fadeIn('slow');
						}, 400);
						$.ajax({
							url: $ajax_url,
							data: {
								action: 'amotos_manager_set_session_view_as_ajax',
								view_as: $view_as
							},
							success: function () {
								handle = true;
							},
							error: function () {
								handle = true;
							}
						});
					}
				});
			});
		}
		amotos_archive_manager();
		function amotos_archive_manager_paging_control() {
			$('.paging-navigation', '.amotos-archive-manager').each(function () {
				var $this = $(this);
				if($this.find('a.next').length === 0) {
					$this.addClass('next-disable');
				} else {
					$this.removeClass('next-disable');
				}
			});
		}
		amotos_archive_manager_paging_control();
	});
})(jQuery);