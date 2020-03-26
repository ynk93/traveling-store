<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Contact page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>
    <section class="innerPageSection contactUsPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"> <?php _e('Контакты', 'traveling-store'); ?></div>
            </div>
            <div class="textPageContent">
                <div class="contactUsContent">
                    <div class="p">
                        <?php _e('<span class="bold">Traveling Store</span> - это многоканальный контактный центр, который занимается обслуживанием туристических объектов по всей Турции, информационной консультацией туристов, приемом и обработкой заявок, подтверждением брони
                        и консультационным сопровождением до окончания тура.', 'traveling-store'); ?>
                    </div>
                    <div class="p">
                        <?php _e('Наши консультанты готовы ответить на все Ваши вопросы <span class="bold">24/7.</span>', 'traveling-store'); ?>
                    </div>

                    <div class="iconsRow">
                        <a href="mailto: ex@gmail.com" class="iconBlock">
                            <div class="iconPic mailIcon"></div>
                            <div class="iconText"><?php _e('ex@gmail.com', 'traveling-store'); ?></div>
                        </a>
                        <a href="tel: 8 067 01 02 013" class="iconBlock">
                            <div class="iconPic phonesIcon"></div>
                            <div class="iconText"><?php _e('8 067 01 02 013', 'traveling-store'); ?></div>
                        </a>
                        <div class="iconBlock">
                            <div class="iconPic messengersIcon"></div>
                            <div class="iconText"><?php _e('Messengers', 'traveling-store'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>