/**
 * @name Multi-step form - WIP
 * @description Prototype for basic multi-step form
 * @deps jQuery, jQuery Validate
 */
(function ($) {
    'use strict';
    /**
     * @name Multi-step form - WIP
     * @description Prototype for basic multi-step form
     * @deps jQuery, jQuery Validate
     */

    var app = {

        init: function(){
            this.cacheDOM();
            this.setupAria();
            this.nextButton();
            this.prevButton();
            this.validateForm();
            this.editForm();
            this.killEnterKey();
            this.handleStepClicks();
        },

        cacheDOM: function(){
            this.$formParent = $(".amotos-car-multi-step");
            this.$form = this.$formParent.find("form");
            this.$formStepParents = this.$form.find("fieldset");
            this.$nextButton = this.$form.find(".amotos-btn-next");
            this.$prevButton = this.$form.find(".amotos-btn-prev");
            this.$editButton = this.$form.find(".amotos-btn-edit");
            this.$stepsParent = $(".amotos-steps");
            this.$steps = this.$stepsParent.find("button");
        },

        htmlClasses: {
            activeClass: "active",
            hiddenClass: "hidden",
            visibleClass: "visible",
            editFormClass: "edit-form",
            animatedVisibleClass: "animated fadeIn",
            animatedHiddenClass: "animated fadeOut",
            animatingClass: "animating"
        },

        setupAria: function(){

            // set first parent to visible
            this.$formStepParents.eq(0).attr("aria-hidden",false);

            // set all other parents to hidden
            this.$formStepParents.not(":first").attr("aria-hidden",true);

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        nextButton: function(){

            this.$nextButton.on("click", function(e){

                e.preventDefault();

                // grab current step and next step parent
                var $this = $(this),
                    currentParent = $this.closest("fieldset"),
                    nextParent = currentParent.next();

                // if the form is valid hide current step
                // trigger next step
                if(app.checkForValidForm()){
                    currentParent.removeClass(app.htmlClasses.visibleClass);
                    app.showNextStep(currentParent, nextParent);
                }

            });
        },

        prevButton: function(){

            this.$prevButton.on("click", function(e){

                e.preventDefault();

                // grab current step parent and previous parent
                var $this = $(this),
                    currentParent = $this.closest("fieldset"),
                    prevParent = currentParent.prev();

                // hide current step and show previous step
                // no need to validate form here
                currentParent.removeClass(app.htmlClasses.visibleClass);
                app.showPrevStep(currentParent, prevParent);

            });
        },

        showNextStep: function(currentParent,nextParent){

            // hide previous parent
            currentParent
                .addClass(app.htmlClasses.hiddenClass)
                .attr("aria-hidden",true);

            // show next parent
            nextParent
                .removeClass(app.htmlClasses.hiddenClass)
                .addClass(app.htmlClasses.visibleClass)
                .attr("aria-hidden",false);

            // focus first input on next parent
            nextParent.focus();

            // activate appropriate step
            app.handleState(nextParent.index());

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        showPrevStep: function(currentParent,prevParent){

            // hide previous parent
            currentParent
                .addClass(app.htmlClasses.hiddenClass)
                .attr("aria-hidden",true);

            // show next parent
            prevParent
                .removeClass(app.htmlClasses.hiddenClass)
                .addClass(app.htmlClasses.visibleClass)
                .attr("aria-hidden",false);

            // send focus to first input on next parent
            prevParent.focus();

            // activate appropriate step
            app.handleState(prevParent.index());

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        handleAriaExpanded: function(){

            /*
             Loop each next/prev button
             Check to see if the parent it controls is visible
             Handle aria-expanded on buttons
             */
            $.each(this.$nextButton, function(idx,item){
                var controls = $(item).attr("aria-controls");
                if($("#"+controls).attr("aria-hidden") == "true"){
                    $(item).attr("aria-expanded",false);
                }else{
                    $(item).attr("aria-expanded",true);
                }
            });

            $.each(this.$prevButton, function(idx,item){
                var controls = $(item).attr("aria-controls");
                if($("#"+controls).attr("aria-hidden") == "true"){
                    $(item).attr("aria-expanded",false);
                }else{
                    $(item).attr("aria-expanded",true);
                }
            });

        },
        checkFieldRequired: function (field_required) {
            return (field_required == 1);
        },
        validateForm: function(){
            // jquery validate form validation
            var car_title = amotos_car_steps_vars.car_title,
                car_price_short = amotos_car_steps_vars.car_price,
                car_type = amotos_car_steps_vars.car_type,
                car_status = amotos_car_steps_vars.car_status,
                car_label = amotos_car_steps_vars.car_label,
                car_price_prefix = amotos_car_steps_vars.car_price_prefix,
                car_price_postfix = amotos_car_steps_vars.car_price_postfix,
                car_doors = amotos_car_steps_vars.car_doors,
                car_seats = amotos_car_steps_vars.car_seats,
                car_owners = amotos_car_steps_vars.car_owners,
                car_mileage = amotos_car_steps_vars.car_mileage,
                car_power = amotos_car_steps_vars.car_power,
                car_volume = amotos_car_steps_vars.car_volume,
                car_year = amotos_car_steps_vars.car_year,
                car_address = amotos_car_steps_vars.car_address,
                car_country = amotos_car_steps_vars.car_country,
                state = amotos_car_steps_vars.state,
                city = amotos_car_steps_vars.city,
                neighborhood = amotos_car_steps_vars.neighborhood,
                postal_code = amotos_car_steps_vars.postal_code;

            this.$form.validate({
                ignore: ":hidden", // any children of hidden desc are ignored
                errorElement: "div", // wrap error elements in span not label
                invalidHandler: function(event, validator){ // add aria-invalid to el with error
                    $.each(validator.errorList, function(idx,item){
                        if(idx === 0){
                            $(item.element).focus(); // send focus to first el with error
                        }
                        $(item.element).attr("aria-invalid",true); // add invalid aria
                    })
                },
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        elem.parent().find('.select2-container').addClass(errorClass).removeClass(validClass);
                    } else {
                        elem.addClass(errorClass).removeClass(validClass);
                        //elem.closest('.form-group').addClass(errorClass);
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        elem.parent().find('.select2-container').removeClass(errorClass).addClass(validClass);
                    } else {
                        elem.removeClass(errorClass).addClass(validClass);
                        //elem.closest('.form-group').removeClass(errorClass).addClass(validClass);
                    }
                },
                rules: {
                    car_title: {
                        required: this.checkFieldRequired(car_title)
                    },
                    car_price_short: {
                        required: this.checkFieldRequired(car_price_short),
                       // number: true
                    },
                    'car_type[]': {
                        required: this.checkFieldRequired(car_type)
                    },
                    'car_status[]': {
                        required: this.checkFieldRequired(car_status)
                    },
                    'car_label[]': {
                        required: this.checkFieldRequired(car_label)
                    },
                    car_price_prefix: {
                        required: this.checkFieldRequired(car_price_prefix)
                    },
                    car_price_postfix: {
                        required: this.checkFieldRequired(car_price_postfix)
                    },
                    car_mileage: {
                        required: this.checkFieldRequired(car_mileage),
                        number: true
                    },
                    car_power: {
                        required: this.checkFieldRequired(car_power),
                        number: true
                    },
                    car_volume: {
                        required: this.checkFieldRequired(car_volume),
                        number: true
                    },
                    car_doors: {
                        required: this.checkFieldRequired(car_doors),
                        number: true
                    },
                    car_seats: {
                        required: this.checkFieldRequired(car_seats),
                        number: true
                    },
                    car_owners: {
                        required: this.checkFieldRequired(car_owners),
                        number: true
                    },
                    car_year: {
                        required: this.checkFieldRequired(car_year),
                        number: true
                    },
                    car_map_address: {
                        required: this.checkFieldRequired(car_address)
                    },
                    car_country : {
                        required: this.checkFieldRequired(car_country)
                    },
                    administrative_area_level_1: {
                        required: this.checkFieldRequired(state)
                    },
                    locality: {
                        required: this.checkFieldRequired(city)
                    },
                    neighborhood: {
                        required: this.checkFieldRequired(neighborhood)
                    },
                    postal_code: {
                        required: this.checkFieldRequired(postal_code)
                    }
                },
                messages: {
                    car_title: "",
                    car_des: "",
                    car_price_short: "",
                    car_doors: "",
                    car_seats: "",
                    car_owners: "",
                    car_mileage: "",
                    car_map_address: "",
                    'car_type[]': "",
                    'car_status[]': "",
                    'car_label[]': "",
                    car_price_prefix: "",
                    car_price_postfix: "",
                    car_power: "",
                    car_volume: "",
                    car_year: "",
                    car_country: '',
                    administrative_area_level_1: '',
                    locality : '',
                    neighborhood: '',
                    postal_code : ''
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
            $(document).on('select2:select select2:unselect','#car_type,#car_status,#car_label', function (arg) {
                var elem = $(arg.target);
                if (elem.val() === null) {
                    elem.parent().find('.select2-container').addClass('error');
                } else {
                    elem.parent().find('.select2-container').removeClass('error');
                }
            });

            $('[data-toggle="amotos-car-additional-field-required"]').each(function () {
                var name = $(this).attr('data-name');
                if (name === undefined) {
                    name = $(this).attr('name');
                }
                var $element = $('[name="'+ name +'"]');
                if ($element.length > 0) {
                    $element.rules('add',{
                        required: true,
                        messages: {
                            required: '',
                        }
                    });
                }
            });
        },

        checkForValidForm: function(){
            if(this.$form.valid()){
                return true;
            }
        },

        handleState: function(step){

            this.$steps.eq(step).prevAll().removeAttr("disabled");
            this.$steps.eq(step).addClass(app.htmlClasses.activeClass);

            // restart scenario
            if(step === 0){
                this.$steps
                    .removeClass(app.htmlClasses.activeClass)
                    .attr("disabled","disabled");
                this.$steps.eq(0).addClass(app.htmlClasses.activeClass)
            }

        },

        editForm: function(){
            var $formParent = this.$formParent,
                $formStepParents = this.$formStepParents,
                $stepsParent = this.$stepsParent;

            this.$editButton.on("click",function(){
                $formParent.toggleClass(app.htmlClasses.editFormClass);
                $formStepParents.attr("aria-hidden",false);
                $formStepParents.eq(0).find("input").eq(0).focus();
                app.handleAriaExpanded();
            });
        },

        killEnterKey: function(){
            $(document).on("keypress", ":input:not(textarea,button)", function(event) {
                return event.keyCode != 13;
            });
        },

        handleStepClicks: function(){

            var $stepTriggers = this.$steps,
                $stepParents = this.$formStepParents;

            $stepTriggers.on("click", function(e){

                e.preventDefault();

                var btnClickedIndex = $(this).index();

                // kill active state for items after step trigger
                $stepTriggers.nextAll()
                    .removeClass(app.htmlClasses.activeClass)
                    .attr("disabled",true);

                // activate button clicked
                $(this)
                    .addClass(app.htmlClasses.activeClass)
                    .attr("disabled",false);

                // hide all step parents
                $stepParents
                    .removeClass(app.htmlClasses.visibleClass)
                    .addClass(app.htmlClasses.hiddenClass)
                    .attr("aria-hidden",true);

                // show step that matches index of button
                $stepParents.eq(btnClickedIndex)
                    .removeClass(app.htmlClasses.hiddenClass)
                    .addClass(app.htmlClasses.visibleClass)
                    .attr("aria-hidden",false)
                    .focus();

            });
        }
    };
    if ($(".amotos-car-multi-step").length > 0) {
        app.init();
    }
})(jQuery);