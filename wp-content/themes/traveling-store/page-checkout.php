<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: Checkout page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>

    <section class="innerPageSection checkoutPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">-->
                <div class="h2"><?php _e('Оформление заказа', 'traveling-store'); ?></div>
            </div>
            <div class="checkoutPageContent">
                <div class="checkoutLeftSide">
                    <div class="checkoutContent">
                        <div class="inputRowWrap">
                            <div class="inputLabel">
                                Взрослый 1
                            </div>
                            <div class="inputRow twoInputs">
                                <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                            </div>
                        </div>
                        <div class="inputRowWrap">
                            <div class="inputLabel">
                                Взрослый 2
                            </div>
                            <div class="inputRow twoInputs">
                                <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                            </div>
                        </div>
                        <div class="inputRowWrap">
                            <div class="inputLabel">
                                Ребенок 1
                            </div>
                            <div class="inputRow twoInputs">
                                <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rightSideSideBar">
                    <div class="checkoutSideBarHead">
                        <div class="basketSideBarHeadRow">
                            <span class="label"><?php _e('Всего (1 позиция)', 'traveling-store'); ?></span>
                            <span class="value confirmationPrice">$120</span>
                        </div>
                        <div class="greenSideBarLabel">
                            Без дополнительных сборов и комиссий.
                        </div>
                    </div>
                    <div class="basketItem">
                        <div class="itemPic">
                            <img src="images/uploads/toursPic/image%204.png">
                        </div>
                        <div class="itemInfo">
                            <div class="itemName">
                                Экскурсия в собор Святой Софии (Стамбул)
                            </div>
                            <div class="infoBlock">
                                <div class="label">Дата и время</div>
                                <div class="value">
                                    <span class="date">20 марта 2020</span>
                                    <span class="time">12:00</span>
                                </div>
                            </div>
                            <div class="infoBlock">
                                <div class="label">Язык</div>
                                <div class="value">английский</div>
                            </div>
                        </div>
                    </div>
                    <div class="sideBarBottom">
                        <div class="basketSideBarHeadRow">
                            <span class="label"><?php _e('К оплате', 'traveling-store'); ?></span>
                            <span class="value confirmationPrice">$80</span>
                        </div>
                        <a href="#" class="confirmOrderButton">
                            <span>
                                Подтвердить заказ
                            </span>
                        </a>
                        <div class="p">
                            Оформляя заказ, вы тем самым принимаете <a href="#">Общие условия и положения</a>.
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
