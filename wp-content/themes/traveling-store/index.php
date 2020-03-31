<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Home page
 */
wp_head(); ?>
<body>
<div class="wrapper">

    <?php get_header(); ?>

    <section class="mainSection">
        <div class="swiper-container mainSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="content">
                        <div class="slideContent">
                            <div class="h1 slideTitle">
                                Traveling Store
                            </div>
                            <div class="slideDescription">
                                <?php _e('Ваш путеводитель по Турции! Экскурсии, рестораны, шопинг, VIP экскурсии и многое другое!
                                Откройте для себя Турцию всместе с <span class="bold">Traveling Store!</span>', 'traveling-store'); ?>
                            </div>
                        </div>
                        <div class="tourSlidePicBlock">
                            <img src="./images/sliderPics/image%203.png" alt="" class="src">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content">
                        <div class="slideContent">
                            <div class="h1 slideTitle">
                                Traveling Store
                            </div>
                            <div class="slideDescription">
                                <?php _e('Ваш путеводитель по Турции! Экскурсии, рестораны, шопинг, VIP экскурсии и многое другое!
                                Откройте для себя Турцию всместе с <span class="bold">Traveling Store!</span>', 'traveling-store'); ?>
                            </div>
                        </div>
                        <div class="tourSlidePicBlock">
                            <img src="./images/sliderPics/image%202.png" alt="" class="src">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content">
                        <div class="slideContent">
                            <div class="h1 slideTitle">
                                Traveling Store
                            </div>
                            <div class="slideDescription">
                                <?php _e('Ваш путеводитель по Турции! Экскурсии, рестораны, шопинг, VIP экскурсии и многое другое!
                                Откройте для себя Турцию всместе с <span class="bold">Traveling Store!</span>', 'traveling-store'); ?>
                            </div>
                        </div>
                        <div class="tourSlidePicBlock">
                            <img src="./images/sliderPics/image%201.png" alt="" class="src">
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-buttons-wrap">
                <div class="swiper-button-left"></div>
                <div class="swiper-button-right"></div>
            </div>
        </div>
    </section>
    <section class="nthInRowSection popularProductsSection">
        <div class="content">
            <div class="titleWrap withButtonTitleWrap">
                <div class="h3"><?php _e('Популярные туры', 'traveling-store'); ?></div>
                <a href="#" class="withTitleButton"><?php _e('Все туры', 'traveling-store'); ?></a>
            </div>
            <div class="cardsRow">
                <div class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/toursPic/image%204.png">
                    </span>
                    <span class="cardInfo">
                        <span class="productInfoRows">
                            <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Регион','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Кеков</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Дни проведения','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Вт,Ср,Пт</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Время','traveling-store'); ?></span>
                                <span class="productInfoRowValue">14:00-18:00</span>
                            </span>
                        </span>
                        <span class="priceRow">
                            <span class="priceBlock">
                                <span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
                                <span class="priceValue">$100</span>
                            </span>
                            <a href="#" class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></a>
                        </span>
                    </span>
                </div>
                <div class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/toursPic/image%205.png">
                    </span>
                    <span class="cardInfo">
                        <span class="productInfoRows">
                            <span class="cardName h5">Храм Святого Николая</span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Регион','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Кеков</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Дни проведения','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Вт,Ср,Пт</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Время','traveling-store'); ?></span>
                                <span class="productInfoRowValue">14:00-18:00</span>
                            </span>
                        </span>
                        <span class="priceRow">
                            <span class="priceBlock">
                                <span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
                                <span class="priceValue">$100</span>
                            </span>
                            <a href="#" class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></a>
                        </span>
                    </span>
                </div>
                <div class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/toursPic/image%206.png">
                    </span>
                    <span class="cardInfo">
                        <span class="productInfoRows">
                            <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Регион','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Кеков</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Дни проведения','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Вт,Ср,Пт</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Время','traveling-store'); ?></span>
                                <span class="productInfoRowValue">14:00-18:00</span>
                            </span>
                        </span>
                        <span class="priceRow">
                            <span class="priceBlock">
                                <span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
                                <span class="priceValue">$100</span>
                            </span>
                            <a href="#" class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></a>
                        </span>
                    </span>
                </div>
                <div class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/toursPic/image%207.png">
                    </span>
                    <span class="cardInfo">
                        <span class="productInfoRows">
                             <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Регион','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Кеков</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Дни проведения','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Вт,Ср,Пт</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Время','traveling-store'); ?></span>
                                <span class="productInfoRowValue">14:00-18:00</span>
                            </span>
                        </span>

                        <span class="priceRow">
                            <span class="priceBlock">
                                <span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
                                <span class="priceValue">$100</span>
                            </span>
                            <a href="#" class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></a>
                        </span>
                    </span>
                </div>
                <div class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/toursPic/image%208.png">
                    </span>
                    <span class="cardInfo">
                        <span class="productInfoRows">
                            <span class="cardName h5">Храм Святого Николая и остров Кекова. Храм Святого Николая и остров Кекова.</span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Регион','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Кеков</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Дни проведения','traveling-store'); ?></span>
                                <span class="productInfoRowValue">Вт,Ср,Пт</span>
                            </span>
                            <span class="productInfoRow">
                                <span class="productInfoRowLabel"><?php _e('Время','traveling-store'); ?></span>
                                <span class="productInfoRowValue">14:00-18:00</span>
                            </span>
                        </span>
                        <span class="priceRow">
                            <span class="priceBlock">
                                <span class="priceLabel"><?php _e('Цена от:', 'traveling-store'); ?></span>
                                <span class="priceValue">$100</span>
                            </span>
                            <a href="#" class="opencardButton"><?php _e('Подробнее', 'traveling-store'); ?></a>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="nthInRowSection partnersSection">
        <div class="content">
            <div class="titleWrap withButtonTitleWrap">
                <div class="h3"><?php _e('Реклама от партнеров', 'traveling-store'); ?></div>
                    <a href="#" class="withTitleButton"><?php _e('стать партнером', 'traveling-store'); ?></a>
                </div>
                <div class="cardsRow">
                    <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/icons/partners/uniqa.png" class="uniqaLogo">
                    </span>
                        <span class="cardInfo">
                        <span class="cardName"><?php _e('Туристическая страховка', 'traveling-store'); ?></span>
                    </span>
                    </a>
                    <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/icons/partners/turkish-airlines%201.png" class="tuAirLogo">
                    </span>
                        <span class="cardInfo">
                        <span class="cardName"><?php _e('Авиаперелеты', 'traveling-store'); ?></span>
                    </span>
                    </a>
                    <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/icons/partners/terrace.png" class="terraceLogo">
                    </span>
                        <span class="cardInfo">
                        <span class="cardName">Terrace hotels</span>
                    </span>
                    </a>
                    <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/icons/partners/rixos.svg" class="rixosLogo">
                    </span>
                        <span class="cardInfo">
                        <span class="cardName">rixos hotels</span>
                    </span>
                    </a>
                    <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/icons/partners/seraser.png" class="seraserLogo">
                    </span>
                        <span class="cardInfo">
                        <span class="cardName"><?php _e('sereser ресторан', 'traveling-store'); ?></span>
                    </span>
                    </a>
                </div>
            </div>
    </section>
    <section class="nthInRowSection categoriesSection">
        <div class="content">
            <div class="titleWrap">
                <div class="h3"><?php _e('Категории', 'traveling-store'); ?></div>
            </div>
            <div class="cardsRow">
                <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/categoriesPics/img.png" alt="">
                    </span>
                    <span class="cardInfo">
                        <span class="h5 cardName"><?php _e('Шопинг', 'traveling-store'); ?></span>
                        <span class="p cardDescription">
                            <?php _e(' Что привезти из Турции, где покупать сувениры и модные бренды. Рынки, аутлеты, знаменитые торговые центры Турции. Советы экспертов и отзывы туристов о шоппинге в Турции на «Тонкостях туризма».', 'traveling-store'); ?>
                        </span>
                    </span>
                </a>
                <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/categoriesPics/image_2020-03-22_20-23-54.png">
                    </span>
                    <span class="cardInfo">
                        <span class="h5 cardName"><?php _e('Рестораны', 'traveling-store'); ?></span>
                        <span class="p cardDescription">
                            <?php _e(' Писать про превосходный сервис и отменную еду этих заведений просто нет смысла - это лучшие турецкие рестораны. Поэтому расскажу про особенности каждого. Узнайте, на какие рестораны стоит обратить свое внимание!', 'traveling-store'); ?>
                        </span>
                    </span>
                </a>
                <a href="#" class="card">
                    <span class="cardPic">
                        <img src="./images/uploads/categoriesPics/img%20(2).png" alt="">
                    </span>
                    <span class="cardInfo">
                        <span class="h5 cardName">VIP</span>
                        <span class="p cardDescription">
                            <?php _e('Существует стереотип, что Турция – это страна бюджетного отдыха, но это не совсем так. Безупречно, тут без проблем можно недорого отдохнуть, при этом совсем не чувствуя себя чем-то  обделенным.', 'traveling-store'); ?>
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </section>
    <section class="aboutUsSection">
        <div class="aboutUsSectionContent">
            <div class="orangeMark"><?php _e('о нас', 'traveling-store'); ?></div>
            <div class="h1">Traveling Store</div>
            <div class="p">
                <?php _e('Краткое информация о фирме, турах, преимуществах c дальнейшем переходом на страницу с более детальной
                информацией.', 'traveling-store'); ?>
            </div>
            <a href="#" class="toAboutUsButton">
                подробнее
            </a>
        </div>
        <div class="aboutUsBg img-parallax" data-speed="-1.25"></div>
    </section>
    <section class="nthInRowSection ourPreferencesSection">
        <div class="content">
            <div class="titleWrap centered">
                <div class="h3"><?php _e('Наши преимущества', 'traveling-store'); ?></div>
            </div>
            <div class="iconsRow">
                <div class="iconBlock">
                    <div class="iconPic supportIcon"></div>
                    <div class="iconText"><?php _e('Поддержка 24/7', 'traveling-store'); ?></div>
                </div>
                <div class="iconBlock">
                    <div class="iconPic mooneyIcon"></div>
                    <div class="iconText"><?php _e('Без переплат', 'traveling-store'); ?></div>
                </div>
                <div class="iconBlock">
                    <div class="iconPic cardIcon"></div>
                    <div class="iconText"><?php _e('Онлайн оплата', 'traveling-store'); ?></div>
                </div>
            </div>
        </div>
    </section>
    <section class="reviewsSection">
        <div class="content">
            <div class="leftSide">
                <div class="reviewsTitle h3"><?php _e('Отзывы клиентов', 'traveling-store'); ?></div>
                <div class="reviewsDescription p">
                    <?php _e('Traveling Store помог подобрать и организовать множество туров и экскурсий. Наши клиенты знают, что
                    путешествие по турции с Traveling Store
                    сэкономит их время и деньги!', 'traveling-store'); ?>
                </div>
            </div>
            <div class="rightSide">
                <div class="reviewBlock">
                    <div class="reviewHead">
                        <div class="avatarPic">
                            <img src="./images/uploads/avatars/avatar.png">
                        </div>
                        <div class="reviewTitle">
                            <div class="reviewAuthorName">
                                Наташа Смирнова
                            </div>
                            <div class="reviewDate p">
                                20.12.2019
                            </div>
                        </div>
                    </div>
                    <div class="reviewText p">
                        <?php _e('Traveling Store помог подобрать и организовать множество туров и экскурсий. Наши клиенты знают,
                        что путешествие по турции с Traveling Store
                        сэкономит их время и деньги!', 'traveling-store'); ?>
                    </div>
                </div>
                <div class="reviewBlock">
                    <div class="reviewHead">
                        <div class="avatarPic">
                            <img src="./images/uploads/avatars/avatar%20(1).png">
                        </div>
                        <div class="reviewTitle">
                            <div class="reviewAuthorName">
                                Алексей Билоус
                            </div>
                            <div class="reviewDate p">
                                20.12.2019
                            </div>
                        </div>
                    </div>
                    <div class="reviewText p">
                        <?php _e('Traveling Store помог подобрать и организовать множество туров и экскурсий. Наши клиенты знают,
                        что путешествие по турции с Traveling Store
                        сэкономит их время и деньги!', 'traveling-store'); ?>
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