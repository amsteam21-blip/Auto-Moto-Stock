String.prototype.capitalize = function(){
    return this.replace( /((^|\s)+)(.)/g , function(m,p1,p2,p3){ return p1+p3.toUpperCase();
    } );
};

var SQUICK = SQUICK || {};

(function($) {
    "use strict";

    var $document = $(document);

    // Control Helper
    SQUICK.helper = {
        isCheckBox: function($control) {
            return $control.is(':checkbox');
        },
        isRadio: function($control) {
            return $control.is(':radio');
        },
        isText: function($control) {
            return ($control.is('input') || $control.is('textarea')) && !this.isCheckBox($control) && !this.isRadio($control);
        },
        isSelect: function($control) {
            return $control.is('select');
        },
        changeCloneNameIndex: function ($element, name, isInPanel, isInRepeater, panelIndex, repeaterIndex, cloneIndex, isOwnerClone) {
            var isInWidget = $element.closest('.widget-content').length > 0,
                widgetPrefix = '';

            if (isInWidget) {
                widgetPrefix = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8){ return p1 + p2 + p3 + p4;});
                name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8){ return p6 + p8;});
            }

            if (isInPanel && isInRepeater) {
                if (panelIndex >= 0) {
                    if (repeaterIndex >= 0) {
                        if (isOwnerClone) {
                            name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p12,p13,p14,p15,p16,p17){ return p1+p2+panelIndex+p4+p5+p6+p7+p8+repeaterIndex+p10+p11+p12+p13+p14+cloneIndex+p16+p17});
                        }
                        else {
                            name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11){ return p1+p2+panelIndex+p4+p5+p6+p7+p8+repeaterIndex+p10+p11});
                        }
                    }
                    else if (isOwnerClone) {
                        name =  name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p12,p13,p14){ return p1+p2+panelIndex+p4+p5+p6+p7+p8+p9+p10+p11+cloneIndex+p13+p14});
                    }
                    else {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5){ return p1+p2+cloneIndex+p4+p5});
                    }

                }
                else if (repeaterIndex >= 0) {
                    if (isOwnerClone) {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p12,p13,p14){ return p1+p2+p3+p4+p5+repeaterIndex+p7+p8+p9+p10+p11+cloneIndex+p13+p14});
                    }
                    else {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8){ return p1+p2+p3+p4+p5+cloneIndex+p7+p8});
                    }

                }
                else {
                    name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11){ return p1+p2+p3+p4+p5+p6+p7+p8+cloneIndex+p10+p11});
                }
            }
            else if (isInPanel || isInRepeater) {
                if (panelIndex >= 0) {
                    if (isOwnerClone) {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11){ return p1+p2+panelIndex+p4+p5+p6+p7+p8+cloneIndex+p10+p11});
                    }
                    else {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5){ return p1+p2+cloneIndex+p4+p5});
                    }
                }
                else if (repeaterIndex >= 0) {
                    if (isOwnerClone) {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11){ return p1+p2+repeaterIndex+p4+p5+p6+p7+p8+cloneIndex+p10+p11});
                    }
                    else {
                        name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5){ return p1+p2+cloneIndex+p4+p5});
                    }
                }
                else {
                    name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5,p6,p7,p8){ return p1+p2+p3+p4+p5+cloneIndex+p7+p8});
                }
            }
            else {
                name = name.replace( /^([^\[]+)(\[)([^\]]+)(\])(.*)/g , function(m,p1,p2,p3,p4,p5){ return p1+p2+cloneIndex+p4+p5});
            }
            if (isInWidget) {
                name = widgetPrefix + '[' + name.replace( /^([^\[]+)(\[)(.*)/g , function(m,p1,p2,p3,p4,p5){ return p1 + ']' + p2 + p3});
            }
            return name;
        }
    };

    /**
     * Other Fields
     */
    SQUICK.fields = {
        createFieldObject: function(fieldType, $container) {
            if ((fieldType == null) || (fieldType == '')) {
                return null;
            }
            //SQUICK_TextClass
            var classFieldType = 'SQUICK_' + fieldType.capitalize() + 'Class';
            if (typeof(window[classFieldType ]) === "undefined") {
                return null;
            }
            return new window[classFieldType]($container);
        },

        init: function() {
            this.timeOutFunc(this.addCloneButtonClick);
            this.timeOutFunc(this.removeCloneButtonClick);
            this.timeOutFunc(this.sectionClick);
            this.timeOutFunc(this.sectionTitleClick);
            $(document).trigger('squick_before_init_fields');
            $('.squick-meta-box-wrap').each(function() {
                var $container = $(this);
                if (!$container.closest('#widgets-left').length) {
                    SQUICK.fields.initFields($container);
                }
            });
            $(document).trigger('squick_after_init_fields');
        },

        timeOutFunc: function (func) {
            setTimeout(func, 1);
        },
        initFields: function($container) {
            SQUICK.fields.onChangeFieldControl($container);
            $container.each(function () {
                SQUICK.required.processApplyField(this);
            });
            SQUICK.required.onChange($container);
            SQUICK.fields.makeCloneTemplate($container);
            SQUICK.fields.sortable($container);
            SQUICK.fields.changePanelTitleEvent($container);
            SQUICK.fields.stickySection($container);
            SQUICK.fields.initFieldsDetails($container);

	        $container.find('.squick-section-container').first().show();
            if ($container.find('.squick-sections li.active').length == 0) {
	            $container.find('.squick-sections li').first().addClass('active');
            }

            SQUICK.fields.initFieldJs($container.find('.squick-section-container').first());

            $container.find('.squick-meta-box-wrap-loading').addClass('in');
        },
        initFieldsDetails: function ($container) {
            var isInitNow = ($container.find('.squick-section-container').length == 0) || ($('.squick-term-meta-add-new').length != 0);
            $container.find('.squick-field-content-inner').each(function () {
                var $this = $(this),
                    $field = $this.closest('.squick-field'),
                    fieldType = $field.data('field-type'),
                    field = SQUICK.fields.createFieldObject(fieldType, $this);
                if (field != null) {
                    this.fieldJs = field;
                    var _fieldJs = this.fieldJs;
                    if (isInitNow) {
                        setTimeout(function () {
                            _fieldJs.init();
                            _fieldJs.initDone = true;
                        }, 1);

                    }
                }
            });
        },
        initFieldJs: function ($sectionContainer) {
            $sectionContainer.find('.squick-field-content-inner').each(function () {
                if (this.fieldJs != null) {
                    if (this.fieldJs.initDone == null) {
                        var that = this;
                        setTimeout(function() {
                            that.fieldJs.init();
                            that.fieldJs.initDone = true;
                        }, 1);

                    }
                }
            });
        },

        stickySection: function ($container) {
            var top = 32,
                $optionInner = $('.squick-theme-options-action-inner');
            if ($optionInner.length) {
                top += $optionInner.outerHeight();
            }
            $container.find('.squick-sections > ul').hcSticky({top: top});
        },

        changePanelTitleEvent: function ($container) {
            $container.find('.squick-field-panel-inner').each(function(index) {
                var $inner = $(this),
                    panelTitle = $inner.data('panel-title');
                $inner.data('panel-title-text', $inner.find('.squick-field-panel-title-text').html());
                if ((panelTitle != null) && (panelTitle !== '')) {
                    $inner.find('#' + panelTitle).find('[data-field-control]').on('change', function () {
                        SQUICK.fields.changePanelTitle(this);
                    });
                }

                if (index > 0) {
                    $inner.find('.squick-field-panel-content').hide();
                    $inner.closest('.squick-field-panel-clone-item').addClass('in');
                }
            });
            SQUICK.fields.initPanelTitle($container);
        },
        initPanelTitle: function($container) {
            $container.find('.squick-field-panel-inner').each(function() {
                var $inner = $(this),
                    panelTitle = $inner.data('panel-title');
                if ((panelTitle != null) && (panelTitle !== '')) {
                    $inner.find('#' + panelTitle).find('[data-field-control]').each(function () {
                        SQUICK.fields.changePanelTitle(this);
                    });
                }
            });
        },
        changePanelTitle: function (control) {
            var $this = $(control),
                $innerParent = $this.closest('.squick-field-panel-inner');
            if ($this.val() == '') {
                $innerParent.find('.squick-field-panel-title-text').html($innerParent.data('panel-title-text'));
            }
            else {
                $innerParent.find('.squick-field-panel-title-text').html($this.val());
            }
        },

        makeCloneTemplate: function ($container) {
            $container.find('.squick-field-clone-wrapper').each(function() {
                var $this = $(this),
                    $cloneItem = $this.find('> .squick-field-clone-item').last();
                if ($cloneItem.length > 0) {
                    $this.data('clone-template', $cloneItem[0].outerHTML);
                }
            });
        },
        sectionClick: function() {
            $($document).on('click', '.squick-sections a', function(event) {
	            var $this = $(this),
                    section = $this.attr('href');
	            if (section[0] !== '#') {
	                return;
                }
                event.preventDefault();

                var $container = $(event.target).closest('.squick-meta-box-wrap'),
                    $sectionContainer = $container.find(section);
                $this.closest('ul').find('li').removeClass('active');
                $this.parent().addClass('active');
                $container.find('.squick-section-container').hide();
                $sectionContainer.show();
                SQUICK.fields.initFieldJs($sectionContainer);
                $this.trigger('squick_section_changed');
                $container.find(section).find('.squick-map-canvas').each(function () {
                    if ((typeof (google) != "undefined") && (typeof (google.maps) != "undefined") && (typeof (google.maps.event) != "undefined") && (typeof (google.maps.event.trigger) != "undefined")) {
                        google.maps.event.trigger(this,'resize');
                    }
                });
            });
        },
        sectionTitleClick: function () {
            $(document).on('click', '.squick-section-title', function () {
                var $this = $(this);
                $this.toggleClass('in');
                $this.next().slideToggle();
                var $sectionContainer =  $this.closest('.squick-section-container');
                SQUICK.fields.initFieldJs($sectionContainer);
            });
        },

        removeCloneButtonClick: function() {
            $document.on('click', '.squick-clone-button-remove', function() {
                var $this = $(this),
                    $cloneWrapper = $this.closest('.squick-field-clone-wrapper');
                $this.closest('.squick-field-clone-item').remove();
                SQUICK.fields.reIndexCloneField($cloneWrapper);
            });
        },
        addCloneButtonClick: function() {
            $document.on('click', '.squick-clone-button-add', function() {
                var $this = $(this),
                    $cloneWrapper = $this.parent().find('> .squick-field-clone-wrapper'),
                    $elementClone = $($cloneWrapper.data('clone-template'));

                SQUICK.fields.changeNameBeforeClone($elementClone);
                $cloneWrapper.append($elementClone);

                //SQUICK.fields.clearFieldValue($elementClone);
                SQUICK.fields.reIndexCloneField($cloneWrapper);

                SQUICK.fields.restoreNameAfterClone($elementClone);

                SQUICK.fields.initFields($elementClone);
                SQUICK.fields.onChangeFieldControl($elementClone);
                SQUICK.required.onChange($elementClone);

                $elementClone.trigger('squick_add_clone_field');
                $elementClone.find('.squick-field').trigger('squick_check_required');
            });
        },
        changeNameBeforeClone: function ($elementClone) {
            $elementClone.find('[data-field-control]').each(function() {
                var $fieldControl = $(this),
                    name = $fieldControl.attr('name');
                if ((name != null) && (name.indexOf('____') !== 0)) {
                    $fieldControl.attr('name', '____' + name)
                }
            });
        },
        restoreNameAfterClone: function ($elementClone) {
            $elementClone.find('[data-field-control]').each(function() {
                var $fieldControl = $(this),
                    name = $fieldControl.attr('name');
                if ((name != null) && (name.indexOf('____') === 0)) {
                    name = name.substring(4);
                    $fieldControl.attr('name', name);
                }
            });
        },

        onChangeFieldControl: function ($element) {
            $element.find('[data-field-control]').on('change', function() {
                $(this).trigger('squick_field_control_changed');
            });

            $element.on('squick_field_control_changed', function (event) {
                var $this = $(event.target);
                if ($this.data('field-no-change')) {
                    $this.data('field-no-change', false);
                    return;
                }
                if ($this.data('field-set-value')) {
                    return;
                }
                var $field = $this.closest('.squick-field'),
                    fieldValue = SQUICK.fields.getValue($field);
                $field.data('field-value', fieldValue);
                $field.trigger('squick_check_required');
                $field.trigger('squick_check_preset');
                $field.trigger('squick_field_change');
            });

        },
        reIndexCloneField: function ($element) {
            $element.find('> .squick-field-clone-item').each(function(cloneIndex) {
                var $cloneItem = $(this);
                $cloneItem.data('clone-index', cloneIndex);

                $cloneItem.find('[data-field-control]').each(function() {
                    var $this = $(this),
                        name = $this.attr('name');
                    if ((name == null) && (name === '')) {
                        return;
                    }
                    var isOwnerClone = $this.closest('.squick-field').find('.squick-field-clone-item').length;

                    var $repeaterClone = $this.closest('.squick-field-repeater-clone-item'),
                        repeaterIndex = $repeaterClone.data('clone-index');
                    if ((repeaterIndex == null) || (repeaterIndex === '')) {
                        repeaterIndex = -1;
                    }

                    var $panelClone = $this.closest('.squick-field-panel-clone-item'),
                        panelIndex = $panelClone.data('clone-index');
                    if ((panelIndex == null) || (panelIndex === '')) {
                        panelIndex = -1;
                    }

                    var isInPanel = $this.closest('.squick-field-panel').length > 0,
                        isInRepeater = $this.closest('.squick-field-repeater').length > 0;
                    name = SQUICK.helper.changeCloneNameIndex($this, name, isInPanel, isInRepeater, panelIndex, repeaterIndex, cloneIndex, isOwnerClone);

                    $this.attr('name', name);
                });
            });
        },
        sortable: function($element) {
            $element.find('.squick-field-clone-wrapper' ).sortable({
                'items': '.squick-field-clone-item',
                placeholder: "squick-field-clone-sortable-placeholder",
                handle: '.squick-sortable-button,.squick-field-panel-title.squick-field-panel-title-sortable',
                cancel: '.squick-clone-button-remove,.squick-field-panel-title-toggle',
                update: function( event, ui ) {
                    var $wrapper = $(event.target);
                    SQUICK.fields.reIndexCloneField($wrapper);
                },
                stop: function (event, ui) {
                    var $textarea = ui.item.find('textarea.wp-editor-area');
                    $textarea.each(function(index, element) {
                        var editor, is_active;
                        editor = tinyMCE.EditorManager.get(element.id);
                        is_active = $(this).parents('.tmce-active').length;
                        if (editor && is_active) {
                            $(this).parent().find(' > .mce-container').remove();
                            $('#'+element.id).val(editor.getContent());
                            $('#'+element.id).show();
                            var init = tinyMCEPreInit.mceInit[element.id];
                            tinymce.init(init);
                        }


                    });
                }
            });
        },
        clearFieldValue: function($element) {
            $element.find('[data-field-control]').each(function() {
                var $this = $(this),
                    $field = $this.closest('.squick-field');
                $field.data('field-value', '');
                if (SQUICK.helper.isSelect($this)) {
                    $this.prop('selectedIndex', 0);
                }
                else if (SQUICK.helper.isCheckBox($this) || SQUICK.helper.isRadio($this)) {
                    $this.prop('checked', false);
                }
                else {
                    $this.val('');
                }
            });
        },
        getValue: function ($field) {
            var fieldValue = '';
            if ($field.find('.squick-field-clone-item').length > 0) {
                fieldValue = [];
                $field.find('.squick-field-clone-item').each(function() {
                    var $contentInner = $(this).find('> .squick-field-content-inner');
                    fieldValue.push($contentInner[0].fieldJs != null ? $contentInner[0].fieldJs.getValue() : '');
                });
            }
            else {
                var $contentInner = $field.find('.squick-field-content-inner');
                if ($contentInner.length) {
                    if ($contentInner[0].fieldJs != null) {
                        fieldValue = $contentInner[0].fieldJs.getValue();
                    }
                }
            }
            return fieldValue;
        }
    };

    SQUICK.group = {
        init: function () {
            this.toggleGroup();
        },
        toggleGroup: function () {
            $document.on('click', '.squick-field-group-title', function(event) {
                var $this = $(event.target),
                    $group = $this.closest('.squick-field-group');
                $group.toggleClass('in');
                $group.find('> .squick-field-group-content').slideToggle(function () {
	                $(this).closest('.squick-meta-box-wrap').find('.squick-sections > ul').hcSticky('refresh');
                });
            });
        }
    };
    SQUICK.panel = {
        init: function () {
            this.togglePanel();
        },
        togglePanel: function () {
            $document.on('click', '.squick-field-panel-title', function(event) {
                var $this = $(event.target),
                    $panelClone = $this.closest('.squick-field-panel-clone-item');
                if ($this.closest('.squick-clone-button-remove').length) {
                    return;
                }
                $this.closest('.squick-field-panel-clone').find('.squick-field-panel-clone-item').each(function () {
                    if (($panelClone.length == 0) || (this != $panelClone[0])) {
                        $(this).find('.squick-field-panel-content').slideUp();
                        $(this).addClass('in');
                    }
                });

                if ($panelClone.length) {
                    $panelClone.toggleClass('in');
                    $panelClone.find('.squick-field-panel-content').slideToggle();
                }
                else {
                    var $panel = $this.closest('.squick-field-panel');
                    $panel.toggleClass('in');
                    $panel.find('.squick-field-panel-content').slideToggle();
                }
            });
        },
        sortable: function() {

        }
    };

    /**
     * Process required field
     */
    SQUICK.required = {
        processApplyField: function(container) {
            var applyFieldRequired = [];
            $(container).find('.squick-field[data-required]').each(function() {
                var $this = $(this),
                    required = $this.data('required'),
                    fieldId = $this.attr('id'),
                    i, j, requiredChild, requiredGrandChild,
                    _name, _op, _value;
                if ($.isArray(required[0])) {
                    for (i = 0; i < required.length; i++) {
                        requiredChild = required[i];
                        if ($.isArray(requiredChild[0])) {
                            for (j = 0; j < requiredChild.length; j++) {
                                requiredGrandChild = requiredChild[j];
                                _name = requiredGrandChild[0];
                                _op = requiredGrandChild[1];
                                _value = requiredGrandChild[2];

                                if (_name.indexOf('[') != -1) {
                                    _name = _name.replace(/\[.*/i,'');
                                }

                                if (typeof (applyFieldRequired[_name]) === "undefined") {
                                    applyFieldRequired[_name] = [];
                                }
                                if (applyFieldRequired[_name].indexOf(fieldId) === -1) {
                                    applyFieldRequired[_name].push(fieldId);
                                }

                                if (_op[0] === '&') {
                                    if (typeof (applyFieldRequired[_value]) === "undefined") {
                                        applyFieldRequired[_value] = [];
                                    }
                                    if (applyFieldRequired[_value].indexOf(fieldId) === -1) {
                                        applyFieldRequired[_value].push(fieldId);
                                    }
                                }
                            }
                        }
                        else {
                            _name = requiredChild[0];
                            _op = requiredChild[1];
                            _value = requiredChild[2];

                            if (_name.indexOf('[') != -1) {
                                _name = _name.replace(/\[.*/i,'');
                            }

                            if (typeof (applyFieldRequired[_name]) === "undefined") {
                                applyFieldRequired[_name] = [];
                            }
                            if (applyFieldRequired[_name].indexOf(fieldId) === -1) {
                                applyFieldRequired[_name].push(fieldId);
                            }
                            if (_op[0] === '&') {
                                if (typeof (applyFieldRequired[_value]) === "undefined") {
                                    applyFieldRequired[_value] = [];
                                }
                                if (applyFieldRequired[_value].indexOf(fieldId) === -1) {
                                    applyFieldRequired[_value].push(fieldId);
                                }
                            }
                        }
                    }
                }
                else {
                    _name = required[0];
                    _op = required[1];
                    _value = required[2];

                    if (_name.indexOf('[') != -1) {
                        _name = _name.replace(/\[.*/i,'');
                    }

                    if (typeof (applyFieldRequired[_name]) === "undefined") {
                        applyFieldRequired[_name] = [];
                    }
                    if (applyFieldRequired[_name].indexOf(fieldId) === -1) {
                        applyFieldRequired[_name].push(fieldId);
                    }
                    if (_op[0] === '&') {
                        if (typeof (applyFieldRequired[_value]) === "undefined") {
                            applyFieldRequired[_value] = [];
                        }
                        if (applyFieldRequired[_value].indexOf(fieldId) === -1) {
                            applyFieldRequired[_value].push(fieldId);
                        }
                    }
                }
            });
            container.applyFieldRequired = applyFieldRequired;
        },
        onChange: function($container) {
            $container.find('.squick-field').on('squick_check_required', SQUICK.required.onChangeEvent);
        },
        onChangeEvent: function (event) {
            if (this != event.target) {
                return;
            }
            var $this = $(this),
                $container = $this.closest('.squick-meta-box-wrap'),
                applyFieldRequired = $container[0].applyFieldRequired,
                fieldId = $this.attr('id'),
                $panelInner = $this.closest('.squick-field-panel-inner');
            if (applyFieldRequired == null) {
                return;
            }
            if (typeof ($this.data('field-value')) == "undefined") {
                return;
            }
            if (typeof (applyFieldRequired[fieldId]) === "undefined") {
                return;
            }
            for (var i = 0; i < applyFieldRequired[fieldId].length; i++) {
                if ($panelInner.length) {
                    SQUICK.required.toggleField($panelInner.find('[id="' + applyFieldRequired[fieldId][i] + '"]'), $panelInner, $container);
                }
                else {
                    SQUICK.required.toggleField($container.find('[id="' + applyFieldRequired[fieldId][i] + '"]'), $panelInner, $container);
                }

            }
        },
        toggleField: function($field, $panelInner, $container) {
            var required = $field.data('required'),
                isVisible = true;
            if (!$.isArray(required[0])) {
                isVisible = SQUICK.required.processField(required, $panelInner, $container);
            }
            else {
                isVisible = SQUICK.required.andCondition(required, $panelInner, $container);
            }
            if (isVisible) {
                $field.slideDown();
            }
            else {
                $field.slideUp();
            }
        },
        andCondition: function(required, $panelInner, $container) {
            var requiredChild, i;
            for (i = 0; i < required.length; i++) {
                requiredChild = required[i];
                if (!$.isArray(requiredChild[0])) {
                    if (!SQUICK.required.processField(requiredChild, $panelInner, $container))
                    {
                        return false;
                    }
                }
                else {
                    if (!SQUICK.required.orCondition(requiredChild, $panelInner, $container)) {
                        return false;
                    }
                }
            }
            return true;
        },
        orCondition: function(required, $panelInner, $container) {
            var requiredChild, i;
            for (i = 0; i < required.length; i++) {
                requiredChild = required[i];
                if (SQUICK.required.processField(requiredChild, $panelInner, $container)) {
                    return true;
                }
            }
            return false;
        },
        processField: function(required, $panelInner, $container) {
            var _field = required[0],
                _op = required[1],
                _val = required[2],
                fieldVal,
                _field_key = '';
            if (_field.indexOf('[') != -1) {
                var _field_temp = _field.replace(/\[.*/i,'');
                _field_key = _field.substring(_field_temp.length);
                _field_key = _field_key.substr(1, _field_key.length - 2);
                _field = _field_temp;
            }

            if ($panelInner.length) {
                fieldVal = $panelInner.find('[id="' + _field + '"]').data('field-value');
            }
            else {
                fieldVal = $container.find('[id="' + _field + '"]').data('field-value');
            }


            if ((_field_key !== '') && (typeof (fieldVal[_field_key]) !== "undefined")) {
                fieldVal = fieldVal[_field_key];
            }

            if (_op.substr(0, 1) === '&') {
                if ($panelInner.length) {
                    _val = $panelInner.find('#' + _val).data('field-value');
                }
                else {
                    _val = $('#' + _val).data('field-value');
                }

            }

            // _op: =, !=, in, not in, contain, not contain
            // _op start with "&": reference to field (_val)
            switch (_op) {
                case '=':
                case '&=':
                    return _val == fieldVal;
                case  '!=':
                case  '&!=':
                    return _val != fieldVal;
                case  'in':
                case  '&in':
                    return (_val == fieldVal) || $.isArray(_val) && (_val.indexOf(fieldVal) != -1);
                case  'not in':
                case  '&not in':
                    return (!$.isArray(_val) && (_val != fieldVal)) || (_val.indexOf(fieldVal) == -1);
                case  'contain':
                case  '&contain':
                    return (_val == fieldVal)
                        || ($.isArray(fieldVal) && (fieldVal.indexOf(_val) != -1))
                        || ((typeof(fieldVal) === "object" ) && ((fieldVal != null) && (_val in fieldVal)));
                case  'not contain':
                case  '&not contain':
                    return (fieldVal== null)
                        || ($.isArray(fieldVal) && (fieldVal.indexOf(_val) == -1))
                        || ((typeof (fieldVal) === 'object') && !(_val in fieldVal))
                        || (!$.isArray(fieldVal) && (typeof (fieldVal) !== 'object') && (fieldVal != _val)) ;

            }
            return false;
        }
    };
    SQUICK.preset = {
        init: function () {
            this.onCheckPreset();
        },
        onCheckPreset: function () {
            $(document).on('squick_check_preset', '.squick-field', function(event) {
                if (this != event.target) {
                    return;
                }
                var $this = $(this),
                    $panel = $this.closest('.squick-field-panel-inner');

                if ($panel.length === 0) {
                    $panel = $('.squick-meta-box-wrap');
                }

                if (typeof ($this.data('field-value')) == "undefined") {
                    return;
                }
                var dataPreset = $this.data('preset');
                if (typeof (dataPreset) == "undefined") {
                    return;
                }
                var fieldValue = $this.data('field-value'),
                    i, j, _op, _value, _fields;
                for (i = 0; i < dataPreset.length; i++) {
                    _op = dataPreset[i]['op'];
                    _value = dataPreset[i]['value'];
                    _fields = dataPreset[i]['fields'];
                    if (((_op === '=') && (_value == fieldValue))
                        || ((_op === '!=') && (_value != fieldValue))) {
                        for (j = 0; j < _fields.length; j++) {
                            var $field =  $panel.find('#' + _fields[j][0]),
                                $control = $field.find('[data-field-control]');
                            if (($field.length > 0) && ($field[0] != this)) {
                                if (typeof (_fields[j][1]) == 'object') {
                                    for (var obj_field in _fields[j][1]) {
                                        $field.find('[name$="[' + obj_field + ']"]').val(_fields[j][1][obj_field]);
                                    }
                                }
                                else {
                                    if (SQUICK.helper.isRadio($control) || SQUICK.helper.isCheckBox($control)) {
                                        $field.find('[data-field-control][value="' + _fields[j][1] + '"]').prop('checked', true);
                                    }
                                    else {
                                        $control.val(_fields[j][1]);
                                    }

                                }
                            }
                            $control.trigger('squick_preset_change', _fields[j][1]);
                            $control.trigger('change');
                        }
                        break;

                    }
                }
            });
        }
    };

    SQUICK.onReady = {
        init: function() {
            SQUICK.fields.init();
            SQUICK.group.init();
            SQUICK.panel.init();
            SQUICK.preset.init();
            $('.squick-field').trigger('squick_check_required');
        }
    };
    SQUICK.onResize = {
        init: function() {
        }
    };
    $document.ready(SQUICK.onReady.init);
    $(window).resize(SQUICK.onResize.init);
})(jQuery);