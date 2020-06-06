<div id="successOrderPopup" class="white-popup zoom-anim-dialog mfp-hide">
    <div class="popupContent">
        <div class="popupIcon"></div>
        <div class="h5">Ваш заказ получен!</div>
        <div class="p">
            Спасибо, мы получили Ваш заказ, в течение часа вам придет электронный ваучер на почту или WhatsApp /
            Viber!
        </div>
        <a href="#" class="continueShoppingButton">
            Продолжить просмотр
        </a>
    </div>
</div>
<footer class="footer">
    <div class="content">

        <div class="footerInnerRow">

            <?php $footer_navigation_menu = wp_nav_menu([
                'theme_location' => 'footer-navigation',
                'container' => false,
                'menu_class' => 'menu',
                'menu_id' => '',
                'echo' => false,
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
            ]);

            if ($footer_navigation_menu) {
                echo $footer_navigation_menu;
            }

            $contacts_footer_navigation_menu = wp_nav_menu([
                'theme_location' => 'contacts-footer-nav',
                'container' => false,
                'menu_class' => 'menu',
                'menu_id' => '',
                'echo' => false,
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
            ]);

            if ($contacts_footer_navigation_menu) : ?>

                <div class="contactLinks">

                    <?php echo $contacts_footer_navigation_menu ?>

                </div>

            <?php endif; ?>

        </div>

        <div class="footerInnerRow">
            <div class="leftSideWrap">
                <a href="#successOrderPopup" class="popup-with-zoom-anim turSabBlock">
                    <div class="turSabPic"></div>
                    <div class="turSabLicense p">License №12521</div>
                </a>
                <span class="copyRight p">
                <?php _e('Traveling Store 2020 ©. All rights reserved.', 'traveling-store'); ?>
            </span>
            </div>

            <?php $social_footer_navigation_menu = wp_nav_menu([
                'theme_location' => 'social-footer-nav',
                'container' => false,
                'menu_id' => '',
                'echo' => false,
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
            ]);

            if ($social_footer_navigation_menu) : ?>

                <div class="socialLinksWrap">

                    <?php echo $social_footer_navigation_menu; ?>

                </div>

            <?php endif; ?>

        </div>

    </div>
</footer>