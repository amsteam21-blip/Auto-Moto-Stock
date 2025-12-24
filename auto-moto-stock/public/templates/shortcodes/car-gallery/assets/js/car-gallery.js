(function ($) {
	"use strict";
	var checkFilter = true;
	var AMOTOS_car_gallery = {
		vars: {
			filter_id: '',
			car_items: []
		},
		init: function () {
			this.filterCarousel();
			this.filterRow();
			this.resize();
			AMOTOS.select_term();
		},
		filterCarousel: function () {
			var car_owl_filter = $('.amotos-car-gallery [data-filter-type="carousel"]');
			car_owl_filter.each(function () {
				var objectClick = $('a', $(this));
				AMOTOS_car_gallery.executeFilter('filterCarousel', objectClick);
			});
		},
		filterRow: function () {
			var car_filter = $('.amotos-car-gallery [data-filter-type="filter"]'),
				itemSelector = car_filter.data('item'),
				isRTL = $('body').hasClass('rtl');
			if(typeof itemSelector == 'undefined') itemSelector = '.car-item';
			$('[data-layout="fitRows"]').each(function () {
				var $this = $(this);
				$this.imagesLoaded(function () {
					$this.isotope({
						itemSelector: itemSelector,
						layoutMode: 'fitRows',
						isOriginLeft: !isRTL,
						transitionDuration: '0.8s'
					}).isotope('layout');
				});
			});
			$(document).on('vc-tab-clicked', function (event, $current_tab) {
				$('[data-layout="fitRows"]', $current_tab).each(function () {
					$(this).isotope('layout');
				});
			});
			$(car_filter).each(function () {
				if($(this).data('filter-style') == 'filter-isotope') {
					$('a', $(this)).on('click', function (e) {
						e.preventDefault();
						var filterId = $(this).parent().attr('data-filter_id'),
							$car_content = $('.car-content[data-filter_id="'+filterId+'"]'),
							check = true;
						if ($(this).hasClass('active-filter')) {
							check = false;
						}
						if (checkFilter && check) {
							var filterValue = $(this).attr('data-filter');
							$car_content.isotope({filter: filterValue});
							$(this).parent().children('a').css('cursor', 'pointer');
							$(this).parent().children('a').removeClass('active-filter');
							$(this).addClass('active-filter');
							$(this).css('cursor', 'not-allowed');
							var select_filter = $(this).parent().next().children('select');
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + filterValue + '"]').attr('selected', 'selected');
							if(select_filter.val() != filterValue) {
								select_filter.selectize()[0].selectize.setValue(filterValue);
							}
						}
					});
				} else {
					var objectClick = $('a', $(this));
					AMOTOS_car_gallery.executeFilter('filterRow', objectClick);
				}
			});
		},
		executeFilter: function ($filter_style, objectClick) {
			objectClick.on('click', function (event) {
				event.preventDefault();
				var thisObject = $(this),
					filterId = thisObject.parent().attr('data-filter_id'),
					$car_content = $('.car-content[data-filter_id="'+filterId+'"]'),
					amotos_car = $car_content.parent();
				if (thisObject.hasClass('active-filter')) {
					thisObject.css('cursor', 'not-allowed');
					return false;
				} else {
					thisObject.parent().children('a').css('cursor', 'wait');
					if (checkFilter) {
						checkFilter = false;
						var dataFilter = thisObject.data('filter'),
							select_filter = thisObject.parent().next().children('select');
						AMOTOS_car_gallery.vars.filter_id = thisObject.parent().data('filter_id');
						thisObject.parent().find('.active-filter').removeClass('active-filter');
						thisObject.addClass('active-filter');
						amotos_car.css('height', amotos_car.outerHeight());
						if (typeof AMOTOS_car_gallery.vars.car_items[dataFilter + '-' + AMOTOS_car_gallery.vars.filter_id] == 'undefined') {
							thisObject.css('width', thisObject.outerWidth());
							var car_type = thisObject.data('filter');
							if('*' === car_type) {
                                car_type = '';
							}
							var $ajax_url = objectClick.closest('.filter-inner').data('admin-url');
							$.ajax({
								url: $ajax_url,
								data: {
									action: 'amotos_car_gallery_fillter_ajax',
									is_carousel: thisObject.parent().data('is-carousel'),
									columns_gap: thisObject.parent().data('columns-gap'),
									columns: thisObject.parent().data('columns'),
									car_type: car_type,
									item_amount: thisObject.parent().data('item-amount'),
									image_size: thisObject.parent().data('image-size'),
									color_scheme: thisObject.parent().data('color_scheme')
								},
								success: function (html) {
									var $newElems = $('.car-item', html);
									AMOTOS_car_gallery.vars.car_items[dataFilter + '-' + AMOTOS_car_gallery.vars.filter_id] = html;

									$car_content.css('opacity', 0);
									if($filter_style == 'filterRow') {
										$car_content.isotope('destroy');
									} else {
										$car_content.trigger('destroy.owl.carousel');
									}
									$car_content.html($newElems);
									if($filter_style == 'filterRow') {
										AMOTOS.set_item_effect($newElems, 'hide');
									}
									$car_content.css('opacity', 1);
									$car_content.imagesLoaded(function () {
										if($filter_style == 'filterCarousel') {
											AMOTOS.set_item_effect($newElems, 'hide');
											AMOTOS_Carousel.owlCarousel();
										} else {
											AMOTOS_car_gallery.filterRow();
										}
										$newElems = $('.car-item', $car_content);
										AMOTOS.set_item_effect($newElems, 'show');
										setTimeout(function(){
											amotos_car.css('height','auto');
										}, 200);
									});
									setTimeout(function () {
										thisObject.css('width', 'auto');
									},100);
									checkFilter = true;
									select_filter.removeAttr('disabled');
									select_filter.children('option').removeAttr('selected');
									select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
									
									thisObject.parent().children('a').css('cursor', 'pointer');
									thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
								},
								error: function () {
									checkFilter = true;
								}
							});
						} else {
							var old_data = AMOTOS_car_gallery.vars.car_items[dataFilter + '-' + AMOTOS_car_gallery.vars.filter_id];
							var $newElems = $('.car-item', old_data);
							if($filter_style == 'filterRow') {
								$car_content.isotope('destroy');
							}
							$car_content.css('opacity', 0);
							if($filter_style == 'filterCarousel') {
								$car_content.trigger('destroy.owl.carousel');
							}
							$car_content.html($newElems);
							AMOTOS.set_item_effect($newElems, 'hide');
							$car_content.css('opacity', 1);
							if($filter_style == 'filterCarousel') {
								AMOTOS_Carousel.owlCarousel();
							}
							$car_content.imagesLoaded(function () {
								$newElems = $('.car-item', $car_content);
								AMOTOS.set_item_effect($newElems, 'show');
								setTimeout(function(){
									amotos_car.css('height','auto');
								}, 200);
								if($filter_style == 'filterRow') {
									AMOTOS_car_gallery.filterRow();
								}
							});
							checkFilter = true;
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
							thisObject.parent().children('a').css('cursor', 'pointer');
							thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
						}
					}
				}
			});
		},
		resize: function () {
			$(window).resize(function () {
				AMOTOS_car_gallery.executeResize();
			});
			$(window).on('orientationchange', function () {
				AMOTOS_car_gallery.executeResize();
			});
		},
		executeResize: function () {
			$('.car-content.owl-carousel').each(function () {
				var container = $(this);
				setTimeout(function () {
					var $items = $('.car-item', container);
					AMOTOS.set_item_effect($items, 'show');
				}, 500);
			});
		}
	};
	$(document).ready(function () {
		if (!$('body').hasClass('elementor-editor-active')) {
			AMOTOS_car_gallery.init();
		}
	});
})(jQuery);