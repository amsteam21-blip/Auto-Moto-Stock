var AMOTOS_SINGLE_CAR = AMOTOS_SINGLE_CAR || {};
(function ($) {
    'use strict';

    var $body = $('body'),
        $window = $(window),
        $document = $(document),
        isRTL = $body.hasClass('rtl');

    AMOTOS_SINGLE_CAR = {
        init: function () {
            var $carGalleryWrap = $('.car-gallery-wrap');
            this.gallery($carGalleryWrap);
            this.print();
        },
        gallery: function ($wrapper){
            var $sliderMain = $wrapper.find('.single-car-image-main'),
                $sliderThumb = $wrapper.find('.single-car-image-thumb');

            $sliderMain.owlCarousel({
                items: 1,
                nav:true,
                navElement: 'div',
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                dots:false,
                loop: false,
                smartSpeed: 500,
                autoHeight: true,
                rtl: isRTL
            }).on('changed.owl.carousel', syncPosition);

            $sliderThumb.on('initialized.owl.carousel', function () {
                $sliderThumb.find(".owl-item").eq(0).addClass("current");
            }).owlCarousel({
                items : 5,
                navElement: 'div',
                nav: false,
                dots: false,
                rtl: isRTL,
                margin: 9,
                responsive: {
                    1200: {
                        items: 5
                    },
                    992 : {
                        items : 4
                    },
                    768 : {
                        items : 3
                    },
                    0 : {
                        items: 2
                    }
                }
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

            $sliderThumb.on("click", ".owl-item", function(e){
                e.preventDefault();
                if ($(this).hasClass('current')) return;
                var number = $(this).index();
                $sliderMain.data('owl.carousel').to(number, 500, true);
            });
        },
        print: function () {
            var self = this;
            $('#car-print').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    car_id = $this.data('car-id'),
                    ajax_url = $this.data('ajax-url'),
                    car_print_window = window.open('', amotos_single_car_vars.localization.print_window_title, 'scrollbars=0,menubar=0,resizable=1,width=991 ,height=800');
                $.ajax({
                    type: 'POST',
                    url: amotos_single_car_vars.ajax_url,
                    data: {
                        'action': 'car_print_ajax',
                        'car_id': car_id,
                        'isRTL': isRTL
                    },
                    success: function (html) {
                        car_print_window.document.write(html);
                        car_print_window.document.close();
                        car_print_window.focus();
                    }
                });
            });
        }
    };

    AMOTOS_SINGLE_CAR.MAP = {
        map: null,
        marker: null,
        autoComplete : null,
        directionsService: null,
        directionsDisplay: null,
        id: 'amotos__single_car_map',
        elements : {
            $wrap: null,
            $input : null,
            $total: null,
            $number: null,
            $btn: null,
        },
        lat: '',
        lng: '',
        init: function () {
            this.elements.$wrap = $('.amotos__single-car-map-directions');
            if (this.elements.$wrap.length === 0) {
                return;
            }
            this.elements.$input = this.elements.$wrap.find('.amotos__scmd-input');
            this.elements.$btn = this.elements.$wrap.find('.amotos__scmd-btn');
            this.elements.$total = this.elements.$wrap.find('.amotos__scmd-total');
            this.elements.$number = this.elements.$wrap.find('.amotos__scmd-number');
            this.setupMap();
            this.direction();
        },
        setupMap: function () {
            var self = this;
            var t = AMOTOS_MAP.getInstance(this.id);
            self.map = t.instance;

            self.autoComplete = new AMOTOS_MAP.Autocomplete(self.elements.$input[0]);
            self.autoComplete.change(function (e) {
                if (e) {
                    self.lat = e.latitude;
                    self.lng = e.longitude;
                }
            });

            if (self.map.markers) {
                self.marker = self.map.markers[0];
            }

            self.directionsService = new AMOTOS_MAP.DirectionsService();// new google.maps.DirectionsService;
            self.directionsDisplay = new AMOTOS_MAP.DirectionsRenderer();//new google.maps.DirectionsRenderer;

        },
        direction: function () {
            var self = this;
            self.elements.$btn.on('click',function (event){
               event.preventDefault();
               if (self.elements.$input.val() === '') {
                   self.marker.show();
                   self.directionsDisplay.clear();
                   self.elements.$total.hide();
                   return;
               }

                self.directionsDisplay.setMap(self.map);

                var request = {
                    origin: self.marker.getPosition(),
                    destination: new AMOTOS_MAP.LatLng(self.lat,self.lng),
                };

                self.directionsService.route(request, function(results) {
                    if (results) {
                        self.directionsDisplay.setDirections(results);
                        self.marker.hide();
                    }
                });

            });

            self.directionsDisplay.change(function (results) {
                self.getDistance(results);
            });
        },
        getDistance: function (total){
            var self = this;
            var unit = 'm';
            switch (amotos_map_vars.units) {
                case 'kilometre':
                    total = total / 1000;
                    unit = 'km';
                    break;
                case 'mile':
                    total = total * 0.000621371;
                    unit = 'mi';
                    break;
            }
            self.elements.$number.html(total + ' ' + unit);
            self.elements.$total.show();
        }

    };

    $(document).on('maps:loaded', function () {
        AMOTOS_SINGLE_CAR.MAP.init();
    });

    $(document).ready(function () {
        AMOTOS_SINGLE_CAR.init();
    });
})(jQuery);