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
    <section class="innerPageSection aboutUsPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"> <?php _e('О нас', 'traveling-store'); ?></div>
            </div>
            <div class="aboutUsPageRow">
                <?php $about = get_field('content');
                var_dump($about);
                ?>
                <div class="leftSide">
                    <div class="picSide aboutUsPic1">
                        <img src="<?php echo $about['image_and_text']['image']?>">
                    </div>
                </div>
                <div class="rightSide">
                    <div class="textSide">
                        <div class="aboutUsSideTitle">
                            Traveling Store
                        </div>
                        <div class="p">
                            <?php echo $about['image_and_text']['text']; ?>
                        </div>
                        <div class="p">
                            <?php echo $about['image_and_text']['text']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aboutUsPageRow swiperRow">
                <div class="leftSide">
                    <div class="textSide">
                        <div class="aboutUsSideTitle">
                             <?php _e('что мы предлагаем?', 'traveling-store'); ?>
                        </div>
                        <div class="p">
                             <?php _e('Мы предлогаем не только экскурсии по достопримечательностям Турции, но также мыготовы
                            предоставить Вам VIP услуги - трансфер от/до аэропорта, шофера, говорящего на вашем языке,
                            путешествия на яхте и многое другое. Если Вы впервые в турции и не знаете куда пойти за
                            покупками - мы готовы помочт Вам и с этим! Так же мы можем подсказать в какой ресторан Вам
                            отрпавится.', 'traveling-store'); ?>
                        </div>
                    </div>
                </div>
                <div class="rightSide swiperSide">
                    <div class="swiper-container aboutUsSwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="card">
                                    <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/img.png" alt="">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName"><?php _e('Шопинг', 'traveling-store'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card">
                                     <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/image_2020-03-22_20-23-54.png">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName"><?php _e('Рестораны', 'traveling-store'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card">
                                    <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/img%20(2).png" alt="">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName">VIP</span>
                                    </span>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card">
                                    <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/img.png" alt="">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName"><?php _e('Шопинг', 'traveling-store'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card">
                                     <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/image_2020-03-22_20-23-54.png">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName"><?php _e('Рестораны', 'traveling-store'); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card">
                                    <span class="cardPic">
                                        <img src="./images/uploads/categoriesPics/img%20(2).png" alt="">
                                    </span>
                                    <span class="cardInfo">
                                        <span class="h5 cardName">VIP</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-bottom-panel">
                            <div class="swiper-buttons-wrap">
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aboutUsPageRow">
                <div class="leftSide">
                    <div class="picSide aboutUsPic1">
                        <img src="images/uploads/aboutUs.png">
                    </div>
                </div>
                <div class="rightSide">
                    <div class="textSide">
                        <div class="aboutUsSideTitle">
                            Traveling Store
                        </div>
                        <div class="p">
                            <?php _e('Наша компания - это многоканальный контактный центр, который занимается обслуживанием
                            туристических объектов в Анталии, информационной консультацией туристов 24/7, приемом и
                            обработкой заявок, подтверждением брони и консультационным сопровождением до окончания тура.', 'traveling-store'); ?>
                        </div>
                        <div class="p">
                            <?php _e('Площадка принципа « Заказчик - Исполнитель» особенно актуальна для туристического региона
                            Анталии. Исключая череду посредников между туристом и исполнителем тура, сервис облегчает
                            организацию отдыха. А также повышает надежность и ответственность исполнителя тура, делая
                            его «публичным» на рынке услуг.', 'traveling-store'); ?>
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