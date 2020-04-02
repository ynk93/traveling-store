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
});