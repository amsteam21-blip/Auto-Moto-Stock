var SQUICK_THEME_OPTION;
(function($) {
	"use strict";

	SQUICK_THEME_OPTION = {
		_isSavingData: false,
		init: function() {
			this.headerSize();
			this.affixHeader();
			this.backupListener();
			this.checkFieldChange();
			this.saveOptions();
			this.resetOptions();
			this.resetSection();
			this.windowResize();
			this.sectionClickedEvent();
			this.presetProcess();
			this.optionMessage();
			this.deletePreset();
			this.makeDefaultOptions();
		},
		optionMessage: function () {
			var $warningMsg = $('<div class="squick-theme-options-message squick-warning">' + SQUICK_META_DATA.msgSaveWarning  + '</div>'),
				$successMsg = $('<div class="squick-theme-options-message squick-success">' + SQUICK_META_DATA.msgSaveSuccess  + '</div>');;
			if ($('.squick-theme-options-preset').length == 0) {
				$('.squick-theme-options-action-inner').prepend($warningMsg).prepend($successMsg);
			}
			else {
				$('.squick-meta-box-fields').prepend($warningMsg).prepend($successMsg);
			}

		},
		headerSize: function () {
			var $wrapper = $('.squick-theme-options-action-wrapper'),
				$header = $wrapper.find('.squick-theme-options-action-inner');
			$header.outerWidth($wrapper.width());
		},
		affixHeader: function() {
			$(window).scroll(function () {
				var $wrapper = $('.squick-theme-options-action-wrapper'),
					scrollTop = $(window).scrollTop(),
					wrapperTop = $wrapper.offset().top;
				if (scrollTop > wrapperTop - 32) {
					$wrapper.addClass('squick-affix');
				}
				else {
					$wrapper.removeClass('squick-affix');
				}
			});
		},
		windowResize: function() {
			$(window).resize(function() {
				SQUICK_THEME_OPTION.affixHeader();
				SQUICK_THEME_OPTION.headerSize();
			});
		},
		sectionClickedEvent: function () {
			$(document).on('squick_section_changed', function () {
				SQUICK_THEME_OPTION.affixHeader();
				SQUICK_THEME_OPTION.headerSize();
			});
		},


		backupListener: function() {
			$(document).on('click','.squick-theme-options-import', function () {
				var _current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val(),
					_nonce = $('#_wpnonce').val();

				STUtils.popup.show({
					src: SQUICK_META_DATA.ajaxUrl + '?action=squick_import_popup&_current_page=' + _current_page + '&_current_preset=' + _current_preset + '&_wpnonce=' + _nonce,
					type: 'ajax',
					callback: function (el) {
						SQUICK_THEME_OPTION.importData(el);
						SQUICK_THEME_OPTION.exportData(el);
					},
				});
			});
		},

		importData: function ($container) {
			$container.find('.squick-theme-options-backup-import button').on('click', function () {
				var $this = $(this),
					dataImport = $this.parent().find('textarea').val();

				if (dataImport == '') {
					return;
				}
				if (!confirm(SQUICK_META_DATA.msgConfirmImportData)) {
					return;
				}
				if ($this.data('importing')) {
					return;
				}

				var $wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val();
				$this.data('importing', true);
				$this.html('<i class="fa fa-spin fa-spinner"></i> ' + $this.data('importing-text'));

				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl,
					data: {
						_wpnonce: wpnonce,
						_current_page: _current_page,
						_current_preset: _current_preset,
						action: 'squick_import_theme_options',
						backup_data: dataImport
					},
					type: 'post',
					success: function(res) {
						$this.data('importing', false);
						$this.html($this.data('import-text'));
						if (res == 1) {
							alert(SQUICK_META_DATA.msgImportDone);
							window.location.reload();
						}
						else {
							alert(SQUICK_META_DATA.msgImportError);
						}
					}
				});
			});
		},
		exportData: function ($container) {
			$container.find('.squick-theme-options-backup-export button').on('click', function () {
				var $wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val();
				window.open(SQUICK_META_DATA.ajaxUrl + '?action=squick_export_theme_options&_wpnonce=' + wpnonce+'&_current_page=' + _current_page + '&_current_preset=' + _current_preset,'_blank');
			});
		},

		checkFieldChange: function () {
			$(document).on('squick_field_change', '.squick-field', function() {
				var $warningMsg = $('.squick-theme-options-message.squick-warning'),
					$successMsg = $('.squick-theme-options-message.squick-success');
				if ($successMsg.is(":visible")) {
					$successMsg.slideUp(function() {
						if (!$warningMsg.is(":visible")) {
							$warningMsg.slideDown();
						}
					});
				}
				else {
					if (!$warningMsg.is(":visible")) {
						$warningMsg.slideDown();
					}
				}

				window.onbeforeunload = SQUICK_THEME_OPTION.confirmWhenPageExit;
			});
		},
		confirmWhenPageExit: function(event) {
			if(!event) event = window.event;
			event.cancelBubble = true;
			event.returnValue = '';

			if (event.stopPropagation) {
				event.stopPropagation();
				event.preventDefault();
			}
		},

		/**
		 * Reset theme options in section
		 *
		 * Done: reload page
		 * Error: message error
		 */
		resetSection: function () {
			$(document).on('click', '.squick-theme-options-reset-section', function() {
				if (SQUICK_THEME_OPTION._isSavingData) {
					return;
				}
				if (!confirm(SQUICK_META_DATA.msgConfirmResetSection)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val(),
					currentSection = $('.squick-sections').find('li.active').data('id');
				SQUICK_THEME_OPTION.showLoading('reset_section');
				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl,
					data: {
						_wpnonce: wpnonce,
						_current_page: _current_page,
						_current_preset: _current_preset,
						action: 'squick_reset_section_options',
						section: currentSection
					},
					type: 'post',
					success: function(res) {
						SQUICK_THEME_OPTION.hideLoading('reset_section');
						if (res == 1) {
							alert(SQUICK_META_DATA.msgResetSectionDone);
							window.location.reload();
						}
						else {
							alert(SQUICK_META_DATA.msgResetSectionError);
						}
					}
				});
			});
		},

		/**
		 * Reset theme options
		 *
		 * Done: reload page
		 * Error: message error
		 */
		resetOptions: function () {
			$(document).on('click', '.squick-theme-options-reset-options', function() {
				if (SQUICK_THEME_OPTION._isSavingData) {
					return;
				}
				if (!confirm(SQUICK_META_DATA.msgConfirmResetOptions)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val();

				SQUICK_THEME_OPTION.showLoading('reset_option');
				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl,
					data: {
						_wpnonce: wpnonce,
						_current_page: _current_page,
						_current_preset: _current_preset,
						action: 'squick_reset_theme_options'
					},
					type: 'post',
					success: function(res) {
						SQUICK_THEME_OPTION.hideLoading('reset_option');
						if (res == 1) {
							alert(SQUICK_META_DATA.msgResetOptionsDone);
							window.location.reload();
						}
						else {
							alert(SQUICK_META_DATA.msgResetOptionsError);
						}
					}
				});
			});
		},
		saveOptions: function () {

			$(window).bind('keydown', function(event) {
				if (event.ctrlKey || event.metaKey) {
					if('s' === String.fromCharCode(event.which).toLowerCase()) {
						event.preventDefault();
						$('.squick-theme-options-save-options', '.squick-theme-options-page').trigger('click');
						return false;
					}
				}
			});

			$('.squick-theme-options-form').ajaxForm({
				beforeSubmit: function() {
					if (SQUICK_THEME_OPTION._isSavingData) {
						return false;
					}
					SQUICK_THEME_OPTION.showLoading('save');
				},
				success: function (res) {
					window.onbeforeunload = null;
					SQUICK_THEME_OPTION.hideLoading('save');
					if (res.success) {
						var $warningMsg = $('.squick-theme-options-message.squick-warning'),
							$successMsg = $('.squick-theme-options-message.squick-success');
						if ($warningMsg.is(":visible")) {
							$warningMsg.slideUp(function() {
								$successMsg.slideDown();
							});
						}
						else {
							$successMsg.slideDown();
						}
						$(document).trigger('squick_save_option_success');
					}
				}
			});
		},
		deletePreset: function () {
			$(document).on('click', '.squick-preset-action-delete', function() {
				if (SQUICK_THEME_OPTION._isSavingData) {
					return;
				}
				if (!confirm(SQUICK_META_DATA.msgConfirmDeletePreset)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val();

				SQUICK_THEME_OPTION.showLoading('delete_preset');
				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl,
					data: {
						_wpnonce: wpnonce,
						_current_page: _current_page,
						_current_preset: _current_preset,
						action: 'squick_delete_preset'
					},
					type: 'post',
					success: function(res) {
						SQUICK_THEME_OPTION.hideLoading('delete_preset');
						if (res == 1) {
							$('.squick-theme-options-preset-select li').first().trigger('click');
						}
						else {
							alert(SQUICK_META_DATA.msgDeletePresetError);
						}
					}
				});
			});
		},
		makeDefaultOptions: function () {
			$(document).on('click', '.squick-preset-action-make-default', function() {
				if (SQUICK_THEME_OPTION._isSavingData) {
					return;
				}
				if (!confirm(SQUICK_META_DATA.msgConfirmMakeDefaultOptions)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					_current_preset = $('#_current_preset').val();

				SQUICK_THEME_OPTION.showLoading('make_default_options');
				$.ajax({
					url: SQUICK_META_DATA.ajaxUrl,
					data: {
						_wpnonce: wpnonce,
						_current_page: _current_page,
						_current_preset: _current_preset,
						action: 'squick_make_default_options'
					},
					type: 'post',
					success: function(res) {
						SQUICK_THEME_OPTION.hideLoading('make_default_options');
						if (res == 1) {
							$('.squick-theme-options-preset-select li').first().trigger('click');
						}
						else {
							alert(SQUICK_META_DATA.msgMakeDefaultOptionsError);
						}
					}
				});
			});
		},
		showLoading: function(type) {
			var $wrap = $('.squick-meta-box-wrap'),
				$button;
			$wrap.addClass('in');
			SQUICK_THEME_OPTION._isSavingData = true;

			switch (type) {
				case 'save': {
					$button = $('.squick-theme-options-save-options');
					$button.data('button-text', $button.html());
					$button.html('<i class="fa fa-spin fa-spinner"></i> ' + SQUICK_META_DATA.msgSavingOptions);
					break;
				}
				case 'reset_option': {
					$button = $('.squick-theme-options-reset-options');
					$button.data('button-text', $button.html());
					$button.html('<i class="fa fa-spin fa-spinner"></i> ' + SQUICK_META_DATA.msgResettingOptions);
					break;
				}
				case 'reset_section': {
					$button = $('.squick-theme-options-reset-section');
					$button.data('button-text', $button.html());
					$button.html('<i class="fa fa-spin fa-spinner"></i> ' + SQUICK_META_DATA.msgResettingSection);
					break;
				}
				case 'delete_preset': {
					$button = $('.squick-preset-action-delete');
					$button.data('button-text', $button.html());
					$button.html('<i class="fa fa-spin fa-spinner"></i> ' + SQUICK_META_DATA.msgDeletingPreset);
					break;
				}
				case 'make_default_options': {
					$button = $('.squick-preset-action-make-default');
					$button.data('button-text', $button.html());
					$button.html('<i class="fa fa-spin fa-spinner"></i> ' + SQUICK_META_DATA.msgMakingDefaultOptions);
					break;
				}
			}
		},
		hideLoading: function (type) {
			var $wrap = $('.squick-meta-box-wrap'),
				$button;
			$wrap.removeClass('in');

			SQUICK_THEME_OPTION._isSavingData = false;

			switch (type) {
				case 'save': {
					$button = $('.squick-theme-options-save-options');
					$button.html($button.data('button-text'));
					break;
				}
				case 'reset_option': {
					$button = $('.squick-theme-options-reset-options');
					$button.html($button.data('button-text'));
					break;
				}
				case 'reset_section': {
					$button = $('.squick-theme-options-reset-section');
					$button.html($button.data('button-text'));
					break;
				}
				case 'delete_preset': {
					$button = $('.squick-preset-action-delete');
					$button.html($button.data('button-text'));
					break;
				}
				case 'make_default_options': {
					$button = $('.squick-preset-action-make-default');
					$button.html($button.data('button-text'));
					break;
				}


			}
		},
		presetProcess: function () {
			$(document).on('click', function(event) {
				if ($(event.target).closest('.squick-theme-options-preset-select').length == 0) {
					$('.squick-theme-options-preset-select').removeClass('in');
				}
			});
			$(document).on('click', '.squick-theme-options-preset-create', function () {

				STUtils.popup.show({
					target: '#squick_options_preset_popup_wrapper',
					type: 'target',
					callback: function () {
						var $inputPreset = $('.squick-theme-options-preset-popup-content input');
						$inputPreset.val('');
						setTimeout(function () {
							$inputPreset.focus();
						}, 200);
					},
				});
			});
			$(document).on('click', '.squick-theme-options-preset-select', function () {
				$(this).toggleClass('in');
			});
			$('.squick-theme-options-preset-popup-content button').on('click', function () {
				var _preset_title = $('.squick-theme-options-preset-popup-content input').val()
				if (_preset_title != '') {
					var $this = $(this);
					if ($this.hasClass('squick-preset-creating')) {
						return;
					}
					$this.addClass('squick-preset-creating');

					var $wpnonce = $('#_wpnonce'),
						wpnonce = $wpnonce.val(),
						_current_page = $('#_current_page').val(),
						_current_preset = $('#_current_preset').val();

					$.ajax({
						url: SQUICK_META_DATA.ajaxUrl,
						data: {
							_wpnonce: wpnonce,
							_current_page: _current_page,
							_current_preset: _current_preset,
							_preset_title: _preset_title,
							action: 'squick_create_preset_options'
						},
						type: 'post',
						success: function(res) {
							$this.removeClass('squick-preset-creating');

							STUtils.popup.close();

							var $wrapperRes = $(res);
							$('.squick-theme-options-page').html($wrapperRes);

							$wrapperRes.find('.squick-meta-box-wrap').each(function() {
								SQUICK.fields.initFields($(this));
							});
							SQUICK_THEME_OPTION.saveOptions();
							SQUICK_THEME_OPTION.headerSize();
							SQUICK_THEME_OPTION.optionMessage();
							$(document).trigger('squick_section_changed');
							$('.squick-field').trigger('squick_check_required');
							$('.squick-field').trigger('squick_check_preset');
							_current_preset = $('#_current_preset').val();
							SQUICK_THEME_OPTION.changeLocation(_current_preset);
						},
						error: function () {
							$this.removeClass('squick-preset-creating');
						}
					});
				}
			});

			$(document).on('click', '.squick-theme-options-preset-select li', function () {
				if ($('.squick-theme-options-message.squick-warning').is(':visible')) {
					if (!confirm(SQUICK_META_DATA.msgPreventChangeData)) {
						return;
					}
				}
				var $this = $(this);
				if ($this.hasClass('squick-preset-creating')) {
					return;
				}

				var _current_preset = $('#_current_preset').val(),
					_view_preset = $this.data('preset');
				if (_current_preset != _view_preset) {
					$this.addClass('squick-preset-creating');
					$('.squick-theme-options-page').addClass('in');
					SQUICK_THEME_OPTION.getPresetOptions(_view_preset, $this);
				}
			});
		},
		getPresetOptions: function (_current_preset, $this) {
			var _current_page = $('#_current_page').val();
			$.ajax({
				url: SQUICK_META_DATA.ajaxUrl,
				data: {
					_current_page: _current_page,
					_current_preset: _current_preset,
					action: 'squick_ajax_theme_options',
					_wpnonce: $('#_wpnonce').val(),
				},
				type: 'post',
				success: function(res) {
					window.onbeforeunload = null;
					if ($this != null) {
						$this.removeClass('squick-preset-creating');
					}
					$('.squick-theme-options-page').removeClass('in');

					var $wrapperRes = $(res);
					$('.squick-theme-options-page').html($wrapperRes);


					$wrapperRes.find('.squick-meta-box-wrap').each(function() {
						SQUICK.fields.initFields($(this));
					});
					SQUICK_THEME_OPTION.headerSize();
					SQUICK_THEME_OPTION.saveOptions();
					SQUICK_THEME_OPTION.optionMessage();
					$(document).trigger('squick_section_changed');

					$('.squick-field').trigger('squick_check_required');
					//$('.squick-field').trigger('squick_check_preset');
					SQUICK_THEME_OPTION.changeLocation(_current_preset);
				},
				error: function () {
					if ($this != null) {
						$this.removeClass('squick-preset-creating');
					}
					$('.squick-theme-options-page').removeClass('in');
				}
			});
		},
		changeLocation: function (presetName) {
			var currentUrl = window.location.href;
			if (presetName == '') {
				currentUrl = currentUrl.replace(/(&_squick_preset=)([^&]*)/g, function(m, p1, p2) {return ''});
			}
			else {
				if (currentUrl.match(/(&_squick_preset=)([^&]*)/g)) {
					currentUrl = currentUrl.replace(/(&_squick_preset=)([^&]*)/g, function(m, p1, p2) {return p1+presetName});
				}
				else {
					currentUrl += '&_squick_preset=' + presetName;
				}
			}
			window.history.pushState('','',currentUrl);
		}
	}
	$(document).ready(function() {
		SQUICK_THEME_OPTION.init();
	});
})(jQuery);