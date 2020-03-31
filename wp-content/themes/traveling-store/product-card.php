<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: About page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>
    <section class="productCardSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="innerPageBreadCrumbs">
                    <span class="breadCrumb"><?php _e('Туры', 'traveling-store'); ?></span>
                    <span class="breadCrumb"><?php _e('Анталия', 'traveling-store'); ?></span>
                    <span class="breadCrumb"><?php _e('Экскурсия в собор Святой Софии', 'traveling-store'); ?></span>
                </div>
                <div class="h2"><?php _e('Экскурсия в собор Святой Софии', 'traveling-store'); ?></div>
            </div>
            <div class="productCardRow">
                <div class="productCardLeftSide">
                    <div class="productCardPic">
                        <img src="images/sliderPics/image%201.png">
                    </div>

                    <div class="productCardInfoArea">
                        <div class="productCardInfoHead">
                            <a href="#" class="active"><?php _e('Описание','traveling-store'); ?></a>
                            <a href="#"><?php _e('Программа экскурсии','traveling-store'); ?></a>
                            <a href="#"><?php _e('Что включено','traveling-store'); ?></a>
                            <a href="#"><?php _e('Важная информация','traveling-store'); ?></a>
                        </div>
                        <div class="productCardInfoBlocks"></div>
                    </div>
                </div>
                <div class="productCardRightSide">
                    <div class="sideBarHead">
                        <div class="h5"><?php _e('Расчет стоимости', 'traveling-store'); ?></div>
                    </div>
                </div>
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
    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>