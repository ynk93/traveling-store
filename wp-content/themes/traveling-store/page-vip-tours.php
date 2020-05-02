<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: VIP tours page
 */

$vip = get_field("vip_content");

wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>


    <section class="innerPageSection textPageSection">
        <div class="content">

            <div class="textPageContent">

                <?php foreach ( $vip as $vip_item ) :

                    $vip_item_layout = $vip_item["acf_fc_layout"];
                    switch ($vip_item_layout) {
                        case "text_and_image":
                            include( locate_template( 'template-parts/vip/text_and_image.php' ) );
                            break;
                        case "image_and_text":
                            include( locate_template( 'template-parts/vip/image_and_text.php' ) );
                            break;
                    }

                endforeach; ?>

            </div>

        </div>
    </section>

    <section class="nthInRowSection popularProductsSection">
        <div class="content">
            <div class="titleWrap withButtonTitleWrap">
                <div class="h3"><?php _e('VIP туры', 'traveling-store'); ?></div>
                <a href="#" class="withTitleButton">
                    <?php _e('Все VIP туры', 'traveling-store'); ?></a>
            </div>
            <div class="forMobileSwiperWrap">
                <div class="cardsRow">
                    <div class="card">
                            <span class="cardHead">
                                <span class="cardPic">
                                    <img src="./images/uploads/toursPic/image%204.png">
                                </span>
                                 <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            </span>
                        <span class="cardInfo">
                                <span class="productInfoRows">
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Регион', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Кеков</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Дни проведения', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Время', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">14:00-18:00</span>
                                    </span>
                                </span>
                                <span class="priceRow">
                                    <span class="priceBlock">
                                        <span class="priceLabel">
        <?php _e('Цена от:', 'traveling-store'); ?></span>
                                        <span class="priceValue">$100</span>
                                    </span>
                                    <a href="#" class="opencardButton">
        <?php _e('Подробнее', 'traveling-store'); ?></a>
                                </span>
                            </span>
                    </div>
                    <div class="card">
                                <span class="cardHead">
                                    <span class="cardPic">
                                        <img src="./images/uploads/toursPic/image%205.png">
                                    </span>
                                    <span class="cardName h5">Храм Святого Николая</span>
                                </span>
                        <span class="cardInfo">
                                <span class="productInfoRows">
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Регион', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Кеков</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Дни проведения', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Время', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">14:00-18:00</span>
                                    </span>
                                </span>
                                <span class="priceRow">
                                    <span class="priceBlock">
                                        <span class="priceLabel">
        <?php _e('Цена от:', 'traveling-store'); ?></span>
                                        <span class="priceValue">$100</span>
                                    </span>
                                    <a href="#" class="opencardButton">
        <?php _e('Подробнее', 'traveling-store'); ?></a>
                                </span>
                            </span>
                    </div>
                    <div class="card">
                            <span class="cardHead">
                                <span class="cardPic">
                                    <img src="./images/uploads/toursPic/image%206.png">
                                </span>
                                <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            </span>
                        <span class="cardInfo">
                                <span class="productInfoRows">
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Регион', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Кеков</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Дни проведения', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Время', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">14:00-18:00</span>
                                    </span>
                                </span>
                                <span class="priceRow">
                                    <span class="priceBlock">
                                        <span class="priceLabel">
        <?php _e('Цена от:', 'traveling-store'); ?></span>
                                        <span class="priceValue">$100</span>
                                    </span>
                                    <a href="#" class="opencardButton">
        <?php _e('Подробнее', 'traveling-store'); ?></a>
                                </span>
                            </span>
                    </div>
                    <div class="card">
                            <span class="cardHead">
                                <span class="cardPic">
                                    <img src="./images/uploads/toursPic/image%207.png">
                                </span>
                                 <span class="cardName h5">Храм Святого Николая и остров Кекова.</span>
                            </span>
                        <span class="cardInfo">
                                <span class="productInfoRows">
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Регион', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Кеков</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Дни проведения', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Время', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">14:00-18:00</span>
                                    </span>
                                </span>

                                <span class="priceRow">
                                    <span class="priceBlock">
                                        <span class="priceLabel">
        <?php _e('Цена от:', 'traveling-store'); ?></span>
                                        <span class="priceValue">$100</span>
                                    </span>
                                    <a href="#" class="opencardButton">
        <?php _e('Подробнее', 'traveling-store'); ?></a>
                                </span>
                            </span>
                    </div>
                    <div class="card">
                               <span class="cardHead">
                                    <span class="cardPic">
                                        <img src="./images/uploads/toursPic/image%208.png">
                                    </span>
                                   <span class="cardName h5">Храм Святого Николая и остров Кекова. Храм Святого Николая и остров Кекова.</span>
                               </span>
                        <span class="cardInfo">
                                <span class="productInfoRows">
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Регион', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Кеков</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Дни проведения', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                    </span>
                                    <span class="productInfoRow">
                                        <span class="productInfoRowLabel">
        <?php _e('Время', 'traveling-store'); ?></span>
                                        <span class="productInfoRowValue">14:00-18:00</span>
                                    </span>
                                </span>
                                <span class="priceRow">
                                    <span class="priceBlock">
                                        <span class="priceLabel">
        <?php _e('Цена от:', 'traveling-store'); ?></span>
                                        <span class="priceValue">$100</span>
                                    </span>
                                    <a href="#" class="opencardButton">
        <?php _e('Подробнее', 'traveling-store'); ?></a>
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