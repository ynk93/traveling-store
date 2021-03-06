$(document).ready(function () {
    $('select').niceSelect();

    var mainSwiper = new Swiper('.swiper-container.mainSwiper', {
        speed: 1000,
        watchSlidesProgress: true,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"><span class="innerBullet"></span></span>';
            }
        },
        breakpoints: {
            768: {
                effect: 'fade',
            }
        },
        navigation: {
            nextEl: '.swiper-button-right',
            prevEl: '.swiper-button-left',
        },
    });

    var aboutUsSwiper = new Swiper('.swiper-container.aboutUsSwiper', {
        speed: 500,
        slidesPerView: 'auto',
        spaceBetween: 20,
        freeMode: true,
        pagination: {
            el: '.swiper-pagination',
            type: 'progressbar',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });

    var productCardPicsSwiper = new Swiper('.swiper-container.productCardPicsSwiper', {
        speed: 500,
        watchOverflow: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });

    var reviewsSwiper = new Swiper('.swiper-container.reviewsSwiper', {
        speed: 500,
        watchOverflow: true,
        slidesPerView: 2,
        spaceBetween: 20,
        breakpoints: {
            1180: {
                slidesPerView: 1
            }
        }
    });

    var tourDayIndexesEnabled = [0, 0, 1, 1, 0, 1, 0];

    $(".calendar").datepicker({
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        autoHide: true,
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        beforeShowDay: function (date) {
            var day = date.getDay();

            if (day == 0 && tourDayIndexesEnabled[0] == 1) {
                return [true];
            }
            if (day == 1 && tourDayIndexesEnabled[1] == 1) {
                return [true];
            }
            if (day == 2 && tourDayIndexesEnabled[2] == 1) {
                return [true];
            }
            if (day == 3 && tourDayIndexesEnabled[3] == 1) {
                return [true];
            }
            if (day == 4 && tourDayIndexesEnabled[4] == 1) {
                return [true];
            }
            if (day == 5 && tourDayIndexesEnabled[5] == 1) {
                return [true];
            }
            if (day == 6 && tourDayIndexesEnabled[6] == 1) {
                return [true];
            }
            return [false];
        }
    });

    $(".wc-bookings-date-picker-date-fields input").on("change", function () {
        $(this).parent().addClass('showFields');
    });

    if ($('.card-picker-drop .wc-bookings-date-picker').length) {

        $('.productCardParamWrap.bookingCalendar').append($('.wc-bookings-date-picker'));

    }

    $('select[name=adultPersonsNumPicker]').siblings('.nice-select').find('.current').append('<span class="resultLabel">(взрослый)</span>')

    $(document).on('click', '.card-param-picker .input', function (e) {
        var $me = $(this),
            $parent = $me.parents('.card-param-picker');

        $(this).parents('.productCardParamWrap').siblings().find('.card-param-picker.open').removeClass('open');

        $parent.toggleClass('open');
        $parent.find('.picker.calendar').slideToggle();
    });

    $(document).on('change', 'select[name=adultPersonsNumPicker]', function () {
        if (this.value > 1) {
            $(this).siblings('.nice-select').find('.current').append('<span class="resultLabel">(взрослых)</span>');
        } else {
            $(this).siblings('.nice-select').find('.current').append('<span class="resultLabel">(взрослый)</span>');
        }
    });

    $(".calendar").on("change", function () {
        var $me = $(this),
            $selected = $me.val(),
            $parent = $me.parents('.card-param-picker');

        $parent.addClass('datePicked');
        $parent.toggleClass('open');
        $parent.find('.result').children('span').html($selected);
    });

    $(document).on('click', '.hamburgerWrap', function (e) {
        e.preventDefault();

        $(this).parents('header.header').toggleClass('showMenu');
        $('body').toggleClass('fixed');

        return false;
    });

    $(document).on('click', '.checkoutLeftSide .chbWrap .container', function (e) {
        e.preventDefault();

        $(this).toggleClass('checked');
        $(this).find('input').trigger('click');

        return false;
    });

    $('.popup-with-zoom-anim').magnificPopup({
        type: 'inline',

        fixedContentPos: false,
        fixedBgPos: true,

        overflowY: 'auto',

        closeBtnInside: true,
        preloader: false,

        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in'
    });

    $(window).scroll(function () {
        var scrollTreshold;
        if ($(window).width() >= 1024) {
            scrollTreshold = 12;
        } else {
            scrollTreshold = 0;
        }

        if ($(this).scrollTop() > scrollTreshold) {
            $('.wrapper').addClass('whiteHeader');
        } else {
            $('.wrapper').removeClass('whiteHeader');
        }


        if ($(".img-parallax").length) {
            mainPageAboutUsImgParallax();
        }
    });

    var img = $('.img-parallax');
    var imgParent = img.parent();

    function mainPageAboutUsImgParallax() {
        var speed = img.data('speed');
        var imgY = imgParent.offset().top;
        var winY = $(this).scrollTop();
        var winH = $(this).height();
        var parentH = imgParent.innerHeight();


        // The next pixel to show on screen
        var winBottom = winY + winH;

        // If block is shown on screen
        if (winBottom > imgY && winY < imgY + parentH) {
            // Number of pixels shown after block appear
            var imgBottom = ((winBottom - imgY) * speed);
            // Max number of pixels until block disappear
            var imgTop = winH + parentH;
            // Porcentage between start showing until disappearing
            var imgPercent = ((imgBottom / imgTop) * 100) + (50 - (speed * 50));
        }

        img.css({
            top: imgPercent + '%',
            transform: 'translate(-50%, -' + imgPercent + '%)'
        });
    }

    if ($(window).width() <= 1023) {
        initMobileProductsSwiper();
    }

    if ($(window).width() >= 1024) {
        if ($(document).scrollTop() > 12) {
            $('.wrapper').addClass('whiteHeader');
        }
    } else {
        if ($(this).scrollTop() > 0) {
            $('.wrapper').addClass('whiteHeader');
        }
    }

    $(window).resize(function () {
        if ($(window).width() <= 1023) {
            initMobileProductsSwiper();
        } else {
            destroyProductsSwiper();
        }

        if ($(window).width() >= 1024) {
            if ($(document).scrollTop() > 12) {
                $('.wrapper').addClass('whiteHeader');
            }
        } else {
            if ($(this).scrollTop() > 0) {
                $('.wrapper').addClass('whiteHeader');
            }
        }
    });

    var partnersSwiper = new Swiper('.swiper-container.partnersSwiper', {
        slidesPerView: 5,
        speed: 500,
        spaceBetween: 20,
        breakpoints: {
            768: {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                slideToClickedSlide: true,
                slidesPerView: 1
            }
        }
    });

    var categoriesSwiper = new Swiper('.swiper-container.categoriesSwiper', {
        slidesPerView: 3,
        speed: 500,
        spaceBetween: 20,
        breakpoints: {
            768: {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                slideToClickedSlide: true,
                slidesPerView: 1
            }
        }
    });

    $('.popularProductsSwiper').find('.card').addClass('swiper-slide');

    var popuplarProductsSwiper = new Swiper('.swiper-container.popularProductsSwiper', {
        slidesPerView: 5,
        spaceBetween: 20,
        breakpoints: {
            1023: {
                slidesPerView: 2,
            },
            767: {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                slidesPerView: 1
            }
        }
    });


    function initMobileProductsSwiper() {
        var mobileSwiperWrap = $('.forMobileSwiperWrap');
        mobileSwiperWrap.addClass('swiper-container');
        mobileSwiperWrap.children().addClass('swiper-wrapper');
        mobileSwiperWrap.find('.card').addClass('swiper-slide');

        mobileSwiperWrap.append('<div class="swiper-pagination"></div>');

        mobileProductsSwiper = new Swiper('.swiper-container.forMobileSwiperWrap', {
            slidesPerView: 2,
            spaceBetween: 20,
            breakpoints: {
                767: {
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'bullets',
                    },
                    slidesPerView: 1
                }
            }
        });
    }

    function destroyProductsSwiper() {
        var mobileSwiperWrap = $('.forMobileSwiperWrap');
        mobileSwiperWrap.removeClass('swiper-container');
        mobileSwiperWrap.children().removeClass('swiper-wrapper');
        mobileSwiperWrap.find('.card').removeClass('swiper-slide');

        mobileProductsSwiper.destroy(true, true);
    }

    $(document).on('click', '.counterInputButton', function (e) {
        e.preventDefault();

        var weightInput = $(this).siblings('input'),
            currValue = weightInput.val(),
            step = 1,
            newValue;

        if ($(this).hasClass('counterInputDecreaseButton')) {
            newValue = parseFloat(currValue) - parseFloat(step);
        } else {
            newValue = parseFloat(currValue) + parseFloat(step);
        }

        if (newValue < 0) {
            newValue = 0;
        }

        newValue = parseInt(newValue);

        weightInput.val(newValue);
        weightInput.trigger("change");

    });


    function updateChbResult(parent) {
        var pickerRowData = '<span class="innerResult">';

        var inputRows = parent.find('.row');

        inputRows.each(function (index) {
            var label = $(this).find('.chbRowLabel').text();
            label = label.replace(/:/g, '');
            var inputLabel = '<span class="resultLabel">' + label + '</span>';
            var inputValue = $(this).find('input').val()

            var inputStr = inputValue + ' ' + inputLabel;

            if (index !== (inputRows.length - 1)) {
                inputStr += ', '
            }

            pickerRowData += inputStr
        });

        pickerRowData += '</span>'

        $('.child-num-picker').find('.result').html(pickerRowData);
    }

    if ($('.childrensData').length) {
        updateChbResult($('.childrensData'));
    }

    $(document).on('change', '.childrensData .counterInputElement input', function () {
        updateChbResult($(this).parents('.childrensData'));
    });

    $(document).on('click', '.infoBlock .sideBarHead', function (e) {
        e.preventDefault();

        $(this).siblings('.infoBlockRows').slideToggle();
        $(this).parents('.leftSide').toggleClass('open');

        return false;
    });

    $(document).on('click', '.productCardRightSide .sideBarHead', function () {
        $(this).parents('.productCardSection').removeClass('openSettings');
        $('body').removeClass('fixed');
    });

    $(document).on("click", '.sideBarBlock .sideBarHead', function (e) {
        e.preventDefault();

        $(this).parents('.sideBarBlock').toggleClass('opened');

        $(this).siblings('.sideBarBody').slideToggle();

        return false;
    });

    $(document).on('click', '.productCardSettingsTrigger', function (e) {
        e.preventDefault();

        $(this).parents('.productCardSection').addClass('openSettings');
        $('body').addClass('fixed');

        return false;
    });

    $(document).on('click', '.productCardInfoHead a', function (e) {
        e.preventDefault();

        var self = $(this);

        self.siblings('a').removeClass('active');

        self.addClass('active');

        $(this).parents('.productCardInfoArea').find('.infoBlock').removeClass('active');
        $(this).parents('.productCardInfoArea').find('.infoBlock').eq(self.index()).addClass('active');

        calculateBottomLinePosition(self);

        return false;
    });

    $(document).on('click', '.shareRow a.button', function (e) {
        e.preventDefault();
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
            return false;
        }
    });

    function calculateBottomLinePosition(object) {
        var bottomLineMargin = object.offset().left - object.parents('.productCardRow').offset().left;

        object.siblings('.bottomLine').css({
            width: object.width(),
            left: bottomLineMargin
        });
    }

    $(document).on('change', '.checkoutContent .paymentTypeWrap input[type=radio]', function () {
        $('.paymentTypeWrap').find('.radioContent').slideUp();
        $(this).parents('.radioWrap').find('.radioContent').slideToggle();
    });

    function openSuccessOrderPopup() { // get the class name in arguments here
        $.magnificPopup.open({
            items: {
                src: '#successOrderPopup',
            },
            type: 'inline',
            midClick: true,
            removalDelay: 100,
            mainClass: 'my-mfp-zoom-in',
            callbacks: {
                open: function () {
                    $('body').addClass('fixed');

                },
                close: function () {
                    $('body').removeClass('fixed');
                    window.location = '/';
                }
            }
        });
    }

    if ($('#successOrderPopup').hasClass('active')) {
        openSuccessOrderPopup();
    }

    $(document).on('click', '.checkout-persons-wrap .cart_item .title-row', function () {

        const $_this = $(this);
        const $_item = $_this.parents('.cart_item');
        const $_persons = $_item.find('.persons');

        if (!$_item.hasClass('active')) {

            $_persons.slideDown(600, function () {
                $_item.addClass('active');
            });

        } else {

            $_persons.slideUp(600, function () {
                $_item.removeClass('active');
            });

        }

    });

});

function test() {

    var data = {
        'action': 'test'
    };

    $.ajax({
        url: '/wp-admin/admin-ajax.php',
        data: data,
        type: 'GET',
        success: function (data) {
            console.log(data);
        }
    });

}