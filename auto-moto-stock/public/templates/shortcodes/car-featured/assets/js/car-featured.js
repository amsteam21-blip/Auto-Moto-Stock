var AMOTOS_car_featured = AMOTOS_car_featured || {};
(function ($) {
	"use strict";
	var checkFilter = true,
		isRTL = $('body').hasClass('rtl');
	AMOTOS_car_featured = {
		vars: {
			filter_id: '',
			car_items: []
		},
		init: function () {
			this.filterCarousel();
			this.resize();
			AMOTOS.select_term();
			this.cityFilterScrollBar();
			var $carSyncWrap = $('.car-sync-content-wrap');
			$carSyncWrap.each(function () {
				var $this = $(this);
				AMOTOS_car_featured.syncCarCarousel($this);
			});
			this.calcPaddingTopBottom();
			setTimeout( this.calcPaddingTopBottom,300 );
			setTimeout( this.calcPaddingTopBottom,1000 );
		},
		filterCarousel: function () {
			var car_owl_filter = $('.amotos-car-featured [data-filter-type="carousel"]');
			car_owl_filter.each(function () {
				var objectClick = $('a', $(this));
				AMOTOS_car_featured.executeFilter(objectClick);
			});
		},
		executeFilter: function (objectClick) {
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
						AMOTOS_car_featured.vars.filter_id = thisObject.parent().data('filter_id');
						thisObject.parent().find('.active-filter').removeClass('active-filter');
						thisObject.addClass('active-filter');
						amotos_car.css('height', amotos_car.outerHeight());
						if (typeof AMOTOS_car_featured.vars.car_items[dataFilter + '-' + AMOTOS_car_featured.vars.filter_id] == 'undefined') {
							thisObject.css('width', thisObject.outerWidth());
							var $ajax_url = objectClick.closest('.filter-wrap').data('admin-url');
							$.ajax({
								url: $ajax_url,
								data: {
									action: 'amotos_car_featured_fillter_city_ajax',
									layout_style: thisObject.parent().data('layout_style'),
									car_type: thisObject.parent().data('car_type'),
									car_status: thisObject.parent().data('car_status'),
									car_styling: thisObject.parent().data('car_styling'),
									car_cities : thisObject.parent().data('car_cities'),
									car_state: thisObject.parent().data('car_state'),
									car_neighborhood : thisObject.parent().data('car_neighborhood'),
									car_label : thisObject.parent().data('car_label'),
									color_scheme: thisObject.parent().data('color_scheme'),
									item_amount : thisObject.parent().data('item_amount'),
									image_size: thisObject.parent().data('image_size'),
									include_heading: thisObject.parent().data('include_heading'),
									heading_sub_title : thisObject.parent().data('heading_sub_title'),
									heading_title : thisObject.parent().data('heading_title'),
									heading_text_align : thisObject.parent().data('heading_text_align'),
									car_city: thisObject.data('filter'),
									el_class: thisObject.parent().data('el_class')
								},
								success: function (html) {
									var $newElems = $('.car-item', html);
									AMOTOS_car_featured.vars.car_items[dataFilter + '-' + AMOTOS_car_featured.vars.filter_id] = html;

									$car_content.css('opacity', 0);
									$car_content.trigger('destroy.owl.carousel');
									$car_content.html($newElems);
									$car_content.css('opacity', 1);
									$car_content.imagesLoaded(function () {
										AMOTOS.set_item_effect($newElems, 'hide');
										AMOTOS_Carousel.owlCarousel();
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
							var old_data = AMOTOS_car_featured.vars.car_items[dataFilter + '-' + AMOTOS_car_featured.vars.filter_id],
								$newElems = $('.car-item', old_data);
							$car_content.css('opacity', 0);
							$car_content.trigger('destroy.owl.carousel');
							$car_content.html($newElems);
							AMOTOS.set_item_effect($newElems, 'hide');
							$car_content.css('opacity', 1);
							AMOTOS_Carousel.owlCarousel();
							$car_content.imagesLoaded(function () {
								$newElems = $('.car-item', $car_content);
								AMOTOS.set_item_effect($newElems, 'show');
								setTimeout(function(){
									amotos_car.css('height','auto');
								}, 200);
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
				AMOTOS_car_featured.executeResize();
			});
			$(window).on('orientationchange', function () {
				AMOTOS_car_featured.executeResize();
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
			AMOTOS_car_featured.cityFilterScrollBar();
			var $carSyncWrap = $('.car-sync-content-wrap');
			$carSyncWrap.each(function () {
				AMOTOS_car_featured.syncCarCarousel($(this));
			});
			AMOTOS_car_featured.calcPaddingTopBottom();
		},
		cityFilterScrollBar: function () {
			$('.car-filter-content', '.car-cities-filter').each(function () {
				var $this = $(this);
				if ($this.outerHeight() > 530 ) {
					$this.css('height', '530px');
					$this.css('overflow-y', 'auto');

					if (typeof(PerfectScrollbar) !== 'undefined') {
						new PerfectScrollbar(this, {
							wheelSpeed: 0.5,
							suppressScrollX: true
						});
					}
				} else {
					$this.css('height', 'auto');
				}
			});
		},
		syncCarCarousel: function($carSyncWrap){
			var $sliderMain = $carSyncWrap.find('.car-content-carousel'),
				$sliderThumb = $carSyncWrap.find('.car-image-carousel');
			$sliderMain.owlCarousel({
				items: 1,
				nav: false,
				navElement : 'div',
				dots:false,
				loop: false,
				smartSpeed: 500,
				rtl: isRTL
			}).on('changed.owl.carousel', syncPosition);

			$sliderThumb.on('initialized.owl.carousel', function () {
				$sliderThumb.find(".owl-item").eq(0).addClass("current");
			}).owlCarousel({
				items : 1,
				nav:true,
				navElement : 'div',
				navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
				dots: false,
				rtl: isRTL,
				margin: 0
			}).on('changed.owl.carousel', syncPosition2);

			function syncPosition(el){
				//if you set loop to false, you have to restore this next line
				var current = el.item.index;

				$sliderThumb
					.find(".owl-item")
					.removeClass("current")
					.eq(current)
					.addClass("current");
				var onscreen = $sliderThumb.find('.owl-item.active').length - 1;
				var start = $sliderThumb.find('.owl-item.active').first().index();
				var end = $sliderThumb.find('.owl-item.active').last().index();

				if (current > end) {
					$sliderThumb.data('owl.carousel').to(current, 500, true);
				}
				if (current < start) {
					$sliderThumb.data('owl.carousel').to(current - onscreen, 500, true);
				}
			}

			function syncPosition2(el) {
				var number = el.item.index;
				$sliderMain.data('owl.carousel').to(number, 500, true);
			}


			setTimeout(function (){
				window.dispatchEvent(new Event('resize'));
			},500);
		},
		calcPaddingTopBottom: function () {
			return;
			$('.main-content-inner', '.car-sync-carousel').each(function () {
				var $this = $(this),
					$thisHeight = $this.height(),
					$parentHeight = $this.parent().next('.car-image-content').children().outerHeight(),
					$differenceHeight = parseInt($parentHeight) - parseInt($thisHeight);
				if ($differenceHeight > 0 && window.matchMedia('(min-width: 1200px)').matches) {
					$this.css({
						'padding-top': $differenceHeight / 2 + 'px',
						'padding-bottom': $differenceHeight / 2 + 'px'
					});
				} else {
					$this.css({
						'padding-top': '',
						'padding-bottom': ''
					});
				}
			});
		}
	};
	$(document).ready(function () {
		if (!$('body').hasClass('elementor-editor-active')) {
			AMOTOS_car_featured.init();
		}
	});
})(jQuery);