<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Shop page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>
    <section class="catalogSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php _e('Туры', 'traveling-store'); ?></div>
            </div>
            <div class="catalogSectionRow">
                <div class="catalogSideBarWrap">
                    <div class="sideBarBlock">
                        <div class="sideBarHead">
                            <div class="h5"><?php _e('По категориям','traveling-store'); ?></div>
                        </div>
                        <div class="sideBarBody">
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Показать все</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Экскурсии</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Рестораны</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Шопинг</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">VIP</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="sideBarBlock">
                        <div class="sideBarHead">
                            <div class="h5">По регионам</div>
                        </div>
                        <div class="sideBarBody">
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Показать все</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Анталия</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Кемер</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Фетхие</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Алания</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Мармарис</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Белек</span>
                                </label>
                            </div>
                            <div class="chbWrap">
                                <label class="container">
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                    <span class="chbLabel">Сиде</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cardsRow">
                    <div class="card">
                    <span class="cardPic">
                        <img src="images/uploads/toursPic/image%204.png">
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
                        <img src="images/uploads/toursPic/image%205.png">
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
                        <img src="images/uploads/toursPic/image%206.png">
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
                        <img src="images/uploads/toursPic/image%207.png">
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
                        <img src="images/uploads/toursPic/image%208.png">
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
                    <div class="card">
                    <span class="cardPic">
                        <img src="images/uploads/toursPic/image%204.png">
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
                        <img src="images/uploads/toursPic/image%205.png">
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
                        <img src="images/uploads/toursPic/image%206.png">
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
                        <img src="images/uploads/toursPic/image%207.png">
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
                        <img src="images/uploads/toursPic/image%208.png">
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
                </div>
            </div>
        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>