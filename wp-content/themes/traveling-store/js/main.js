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


    $(document).on('click', '.date-picker .input', function (e) {
        var $me = $(this),
            $parent = $me.parents('.date-picker');
        $parent.toggleClass('open');
    });


    $(".calendar").on("change", function () {
        var $me = $(this),
            $selected = $me.val(),
            $parent = $me.parents('.date-picker');

        $parent.addClass('datePicked');
        $parent.toggleClass('open');
        $parent.find('.result').children('span').html($selected);
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


        mainPageAboutUsImgParallax();
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

    function initMobileProductsSwiper() {
        var mobileSwiperWrap = $('.forMobileSwiperWrap');
        mobileSwiperWrap.addClass('swiper-container');
        mobileSwiperWrap.children().addClass('swiper-wrapper');
        mobileSwiperWrap.find('.card').addClass('swiper-slide');

        var mobileProductsSwiper = new Swiper('.swiper-container.forMobileSwiperWrap', {
            slidesPerView: 2,
            spaceBetween: 20,
            breakpoints: {
                767: {
                    slidesPerView: 1,
                    spaceBetween: 0
                }
            }
        });
    }

    $(document).on("click", '.sideBarBlock .sideBarHead', function (e) {
        e.preventDefault();

        $(this).parents('.sideBarBlock').toggleClass('opened');

        $(this).siblings('.sideBarBody').slideToggle();

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

    function calculateBottomLinePosition(object) {
        var bottomLineMargin = object.offset().left - object.parents('.productCardRow').offset().left;

        object.siblings('.bottomLine').css({
            width: object.width(),
            left: bottomLineMargin
        });
    }
});