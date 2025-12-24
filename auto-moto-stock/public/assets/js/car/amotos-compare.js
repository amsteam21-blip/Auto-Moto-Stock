var AMOTOS_Compare = AMOTOS_Compare || {};
(function ($) {
	'use strict';
	if (typeof amotos_compare_vars !== "undefined") {
		var ajax_url = amotos_compare_vars.ajax_url,
			compare_button_url = amotos_compare_vars.compare_button_url,
			alert_title = amotos_compare_vars.alert_title,
			alert_message = amotos_compare_vars.alert_message,
			alert_not_found = amotos_compare_vars.alert_not_found,
			compare_listings = $('#compare-listings'),
			item = $('.compare-car', '#compare-cars-listings').length;
	}
	AMOTOS_Compare = {
		init: function () {
			this.register_event_compare();
			this.compare_car();
			this.open_compare();
			this.close_compare();
			this.compare_listing();
		},
		register_event_compare: function () {
			$(document).on('click','a.compare-car', function (e) {
				if (!$(this).hasClass('on-handle')) {
					e.preventDefault();
					var $this = $(this).addClass('on-handle'),
						car_inner = $this.closest('.car-inner').addClass('car-active-hover'),
						car_id = $this.data('car-id');
					$('.listing-btn').removeClass('hidden');

					if (item == 4) {
						if ($this.children().hasClass('plus')) {
							item--;
							$this.find('i.fa-minus').removeClass('fa-minus').addClass('fa-spinner fa-spin');
						}
						else {
							AMOTOS.popup_alert('fa fa-check-squaere-o', alert_title, alert_message);
						}
					}
					else {
						if (!($this.children().hasClass('plus'))) {
							item++;
							$this.find('i.fa-plus').removeClass('fa-plus').addClass('fa-spinner fa-spin minus');
						}
						else {
							item--;
							$this.find('i.fa-minus').removeClass('fa-minus').addClass('fa-spinner fa-spin');
						}
					}

					$.ajax ({
						url: ajax_url,
						method: 'post',
						data: {
							action: 'amotos_compare_add_remove_car_ajax',
							car_id: car_id
						},
						success: function (html) {
							if (($this.children().hasClass('minus'))) {
								$this.find('i.minus').removeClass('fa-spinner fa-spin minus').addClass('fa-minus plus');
							} else {
								$this.find('i.fa-spinner').removeClass('fa-spinner fa-spin plus').addClass('fa-plus');
							}
							$('div#compare-cars-listings').replaceWith(html);
							AMOTOS_Compare.compare_listing();
							if (item == 0) {
								$('.listing-btn').addClass('hidden');
								AMOTOS_Compare.close_compare();
							} else {
								AMOTOS_Compare.open_compare();
							}
							$this.removeClass('on-handle');
							car_inner.removeClass('car-active-hover');
						}
					});
				}
			});
		},
		compare_listing: function () {
			$('.listing-btn').off('click').on('click', function () {
				if (compare_listings.hasClass('listing-open')) {
					compare_listings.removeClass('listing-open');
					$('.listing-btn').find('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-left');
				} else {
					compare_listings.addClass('listing-open');
					$('.listing-btn').find('i.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-right');
				}
			});
		},
		open_compare: function () {
			compare_listings.addClass('listing-open');
			$('.listing-btn').find('i.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-right');
		},
		close_compare: function () {
			if (compare_listings.hasClass('listing-open')) {
				compare_listings.removeClass('listing-open');
				$('.listing-btn').find('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-left');
			}
		},
		compare_car: function () {
			if (compare_listings.length == 1) {
				$('div.compare-car').each(function () {
					var car_id = $(this).attr('data-car-id'),
						car = $("a[data-car-id='" + car_id + "']");
					$('i.fa-plus', car).removeClass('fa-plus').addClass('fa-minus plus');
				});

				AMOTOS_Compare.compare_listing();

				if ($('.compare-car').length > 0) {
					// Add, update Element compare to listing
					var handle = true;

					AMOTOS_Compare.register_event_compare(item);
					// Delete element from compare listing
					var $handle = true;
					$(document).on('click', '#compare-cars-listings .compare-car-remove', function (e) {
						e.preventDefault();
						if($handle) {
							$handle = false;
							var $this = $(this),
								car_id = $this.parent().attr('data-car-id'),
								car = $("a[data-car-id='" + car_id + "']");
							$this.parent().addClass('remove');
							$('i.plus', car).removeClass('fa-minus plus').addClass('fa-plus');

							item--;
							if (item == 0) {
								$('#compare-cars-listings').addClass('hidden');
								$('.listing-btn').addClass('hidden');
								AMOTOS_Compare.close_compare();
							}
							$.ajax({
								url: ajax_url,
								method: 'post',
								data: {
									action: 'amotos_compare_add_remove_car_ajax',
									car_id: car_id
								},
								success: function (html) {
									$('div#compare-cars-listings').replaceWith(html);
									AMOTOS_Compare.compare_listing();
									if (item == 0) {
										$('.listing-btn').addClass('hidden');
										AMOTOS_Compare.close_compare();
									} else {
										AMOTOS_Compare.open_compare();
									}
									$handle = true;
								},
								error: function () {
									$handle = true;
								}
							});
						}
					});

					// Go to Page Compare
					$(document).on('click', '.compare-cars-button', function () {
						if (compare_button_url != "") {
							window.location.href = compare_button_url;
						} else {
							alert(alert_not_found);
						}
						return false;
					});
				}
			}
		}
	};
	$(document).ready(function () {
		AMOTOS_Compare.init();
	});
})(jQuery);