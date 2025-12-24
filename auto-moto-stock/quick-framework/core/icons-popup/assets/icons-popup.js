/**
 * ICON POPUP AddOns
 *
 * @type {SQUICK_ICON_POPUP|*|{}}
 */
var SQUICK_ICON_POPUP = SQUICK_ICON_POPUP || {};

(function ($) {
	"use strict";

	SQUICK_ICON_POPUP = {
		_$popup: [],
		_callback: function () {},
		_currentFontId: 0,
		_fonts: [],
		_currentSection: '',
		_currentIcon: '',

		init: function () {
			SQUICK_ICON_POPUP._$popup = $('#squick-popup-icon-wrapper');

			if (SQUICK_ICON_POPUP._$popup.length === 0) {
				$.ajax({
					url: SQUICK_POPUP_DATA.ajaxUrl,
					type: 'post',
					data: {
						action: 'squick_get_font_icons',
						_wpnonce: SQUICK_POPUP_DATA.nonce
					},
					success: function (res) {
						var data = JSON.parse(res),
							template = wp.template('squick-icons-popup'),
							fontName,
							groupIndex,
							groupLength,
							iconsIndex,
							iconsLength,
							iconKey;
						SQUICK_ICON_POPUP._fonts = data;

						if ($('#tmpl-squick-icons-popup').length == 0) {
							return;
						}

						for (fontName in data) {
							if (SQUICK_ICON_POPUP._currentFontId === 0) {
								SQUICK_ICON_POPUP._currentFontId = fontName;
							}
							var data_all = {};
							data[fontName]['groups'] = [];

							groupLength = data[fontName].iconGroup.length;

							for (groupIndex = 0; groupIndex < groupLength; groupIndex++) {

								iconsLength = data[fontName].iconGroup[groupIndex].icons.length;
								data[fontName]['groups'][data[fontName].iconGroup[groupIndex]['id']] = iconKey = data[fontName].iconGroup[groupIndex].icons;

								for (iconsIndex = 0; iconsIndex < iconsLength; iconsIndex++) {
									iconKey = data[fontName].iconGroup[groupIndex].icons[iconsIndex];
									data_all[iconKey] = true;

								}
							}

							data[fontName]['groups'][''] = [];
							for (var icon in data_all) {
								data[fontName]['groups'][''].push(icon);
							}
						}

						var html = template(data);
						$('body').append(html);

						for (fontName in data) {
							delete SQUICK_ICON_POPUP._fonts[fontName].iconGroup;
							delete SQUICK_ICON_POPUP._fonts[fontName].label;
							delete SQUICK_ICON_POPUP._fonts[fontName].total;
						}
						SQUICK_ICON_POPUP._fonts = data;


						SQUICK_ICON_POPUP._$popup = $('#squick-popup-icon-wrapper');

						SQUICK_ICON_POPUP.settingPopup();
						SQUICK_ICON_POPUP.popupListener();
					}
				});
			}

			SQUICK_ICON_POPUP.svg_icon();
			$('body').on('squick_field_control_changed',function (e) {
				SQUICK_ICON_POPUP.svg_icon();
			});

		},
		settingPopup: function() {
			var $fontLinkInner = this._$popup.find('.squick-popup-icon-font-link-inner'),
				$groupLink = $fontLinkInner.find('.squick-popup-icon-group-link'),
				$sectionGroup = this._$popup.find('.squick-popup-icon-group-section'),
				$iconsListing = this._$popup.find('.squick-popup-icon-listing');

			$fontLinkInner.each(function (index, el) {
				el.__scrollBar = new PerfectScrollbar(el, {
					wheelSpeed: 0.5,
					suppressScrollX: true
				});
			});

			$iconsListing.each(function (index, el) {
				el.__scrollBar = new PerfectScrollbar(el, {
					wheelSpeed: 0.5,
					suppressScrollX: true
				});
			});

			$groupLink.css('display', 'none');
			$groupLink.first().css('display', 'block');

			$sectionGroup.css('display', 'none');
			$sectionGroup.first().css('display', 'block');

		},
		popupListener: function() {
			var $searchField = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-search > input'),
				$sectionLinkItem = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-group-link a'),
				$groupTitle = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-group-title'),
				$selectFontField = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-font > select');

			/**
			 * Search icon
			 */
			$searchField.on('keyup', function () {
				var filter = $(this).val().toLowerCase();

				SQUICK_ICON_POPUP._currentSection = '';

				if (filter === '') {
					$groupTitle.text($groupTitle.data('msg-all'));
				} else {
					$groupTitle.text($groupTitle.data('msg-search').replace('{0}', filter));
				}

				var icons_match = [];
				if (SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId] && SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups']) {
					icons_match = SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups'][''].filter(function (s) {
						return s.indexOf(filter) !== -1;
					});
				}

				SQUICK_ICON_POPUP.bindListFont(icons_match, false);

				/**
				 * Update Scroll Bar
				 */
				SQUICK_ICON_POPUP.updateListingScroll();
			});

			/**
			 * Filter icon by group
			 */
			$sectionLinkItem.on('click', function() {
				var $this = $(this),
					idSection = $this.data('id');

				SQUICK_ICON_POPUP._currentSection = idSection;

				$groupTitle.text($this.text());
				$searchField.val('');
				var icons_match = [];
				if (SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId] && SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups']) {
					icons_match = SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups'][idSection];
				}
				SQUICK_ICON_POPUP.bindListFont(icons_match, false);
				/**
				 * Update Scroll Bar
				 */
				SQUICK_ICON_POPUP.updateListingScroll();
			});

			/**
			 * Change font icon
			 */
			$selectFontField.on('change', function() {
				var $fontLinkInner = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-font-link-inner'),
					$groupLink = $fontLinkInner.find('.squick-popup-icon-group-link'),
					$sectionGroup = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-group-section'),
					$searchField = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-search > input');

				SQUICK_ICON_POPUP._currentFontId = $(this).val();
				SQUICK_ICON_POPUP._currentSection = '';

				$groupLink.fadeOut();
				$sectionGroup.fadeOut();

				$groupLink.each(function() {
					var $this = $(this);
					if ($this.data('font-id') === SQUICK_ICON_POPUP._currentFontId) {
						$this.fadeIn(function() {
							SQUICK_ICON_POPUP.updateLinkScroll();
						});

					}
				});

				$sectionGroup.each(function() {
					var $this = $(this);
					if ($this.data('font-id') === SQUICK_ICON_POPUP._currentFontId) {
						$this.fadeIn(function() {
							SQUICK_ICON_POPUP.updateListingScroll();
						});

					}
				});

				$searchField.val('');
				$searchField.trigger('keyup');
			});

			/**
			 * Load more
			 */

			this.iconLoadMore();

		},
		iconLoadMore: function () {
			var $load_more = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-group-load-more');
			$(document).on("click", '.stu-popup-wrap .squick-popup-icon-group-load-more button', function (e) {
				e.preventDefault();
				var $this = $(this),
					$currentFont = $this.closest('.squick-popup-icon-group-section'),
					keySearch = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-search > input').val(),
					total = $currentFont.find(' > ul > li').length;

				if (keySearch !== '') {
					var icons_match = SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups'][''].filter(function (s, i) {
						return (i >= total) && (s.indexOf(keySearch) !== -1);
					});
					SQUICK_ICON_POPUP.bindListFont(icons_match, true);
				} else {
					icons_match = SQUICK_ICON_POPUP._fonts[SQUICK_ICON_POPUP._currentFontId]['groups'][SQUICK_ICON_POPUP._currentSection].filter(function (s, i) {
						return (i >= total);
					});
					SQUICK_ICON_POPUP.bindListFont(icons_match, true);
				}
			});
		},
		iconClickEvent: function () {
			$(document).on("click", '.squick-popup-icon-group-section i', function (e) {
				e.preventDefault();
				$(this).closest('.stu-popup-wrap').trigger('stu-popup-close');
				SQUICK_ICON_POPUP._callback($(this).attr('class'));
			});
		},
		updateLinkScroll: function() {
			$('.squick-popup-icon-font-link-inner').each(function (index, el) {
				if (el.__scrollBar) {
					el.__scrollBar.update();
				}
			});
		},
		updateListingScroll: function() {
			$('.squick-popup-icon-listing').each(function (index, el) {
				if (el.__scrollBar) {
					el.__scrollBar.update();
				}
			});
		},
		open: function (icon, callback) {
			var $searchField = SQUICK_ICON_POPUP._$popup.find('.squick-popup-icon-search > input');
			SQUICK_ICON_POPUP._currentIcon = icon;
			$searchField.val('');
			$searchField.trigger('keyup');

			if (typeof (callback) === "function") {
				SQUICK_ICON_POPUP._callback = callback;
			}

			STUtils.popup.show({
				target: '#squick-popup-icon-target',
				type: 'target',
				callback: function () {
					SQUICK_ICON_POPUP.updateLinkScroll();
					SQUICK_ICON_POPUP.updateListingScroll();
				},
			});
		},
		close: function() {

		},
		bindListFont: function (arr, append) {
			var $currentFont = this._$popup.find('.squick-popup-icon-group-section[data-font-id="' + this._currentFontId + '"]'),
				$loadMore = $currentFont.find(' > .squick-popup-icon-group-load-more > button');
			var html = '';
			var count = 0;
			for (var i in arr) {
				count++;
				if (count > 160) {
					break;
				}
				if (arr[i] === this._currentIcon) {
					html += '<li title="' + arr[i] + '" class="active"><i class="' + arr[i] + '"></i></li>';
				}
				else {
					html += '<li title="' + arr[i] + '"><i class="' + arr[i] + '"></i></li>';
				}
			}
			var $html = $(html);

			SQUICK_ICON_POPUP.svg_icon($html);


			if (append) {
				$currentFont.find(' > ul').append($html);
			}
			else {
				$currentFont.find(' > ul').html($html);
			}

			this.iconClickEvent();


			if (count > 160) {
				$loadMore.show();
			}
			else {
				$loadMore.hide();
			}

		},
		svg_icon: function ($wrap) {
			if (typeof $wrap === "undefined") {
				$wrap = $('body');
			}

			$wrap.find('.svg-icon').each(function () {
				var $this = $(this),
					_class = $this.attr('class'),
					id = _class.replace('svg-icon svg-icon-',''),
					_html = '<svg class="' + _class + '" aria-hidden="true" role="img"> <use href="#'+ id +'" xlink:href="#'+ id +'"></use> </svg>';
				$this.html(_html);
			});
		}
	};
	$(document).ready(function () {
		SQUICK_ICON_POPUP.init();
	});
})(jQuery);