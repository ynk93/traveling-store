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

    $(window).scroll(function () {
        if ($(this).scrollTop() > 12) {
            $('.wrapper').addClass('whiteHeader');
        } else {
            $('.wrapper').removeClass('whiteHeader');
        }


        parallaxImg();
    });

    var img = $('.img-parallax');
    var imgParent = img.parent();

    function parallaxImg() {
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

});