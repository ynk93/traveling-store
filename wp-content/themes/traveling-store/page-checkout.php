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
                    <form class="checkoutContent">
                        <div class="peoplesDataWrap">
                            <div class="fakeInputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Состав группы', 'traveling-store'); ?>
                                </div>
                                <div class="innerLabel">
                                    <div class="p"><?php _e('Введите имя и фамилию всех членов экскурсионной группы.', 'traveling-store'); ?></div>
                                </div>
                            </div>
                            <div class="inputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Взрослый 1', 'traveling-store'); ?>
                                </div>
                                <div class="inputRow twoInputs">
                                    <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                                </div>
                            </div>
                            <div class="inputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Взрослый 2', 'traveling-store'); ?>
                                </div>
                                <div class="inputRow twoInputs">
                                    <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                                </div>
                            </div>
                            <div class="inputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Ребенок 1', 'traveling-store'); ?>
                                </div>
                                <div class="inputRow twoInputs">
                                    <div class="inputWrap"><input type="text" placeholder="Имя"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
                                </div>
                            </div>
                        </div>
                        <div class="placeDataWrap">
                            <div class="inputRowWrap">
                                <div class="inputRow threeInputs">
                                    <div class="inputWrap"><input type="text" placeholder="Ваш регион"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Название отеля"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Номер комнаты"></div>
                                </div>
                            </div>
                            <div class="inputRowWrap">
                                <div class="inputRow twoInputs">
                                    <div class="inputWrap"><input type="text" placeholder="E-mail"></div>
                                    <div class="inputWrap"><input type="text" placeholder="Номер телефона"></div>
                                </div>
                            </div>
                        </div>
                        <div class="contactTypeWrap">
                            <div class="inputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Выберете мессенджер для связи с Вами:', 'traveling-store'); ?>
                                </div>
                                <div class="inputRow chbRow">
                                    <div class="chbWrap">
                                        <label class="container">
                                            <input type="checkbox">
                                            <span class="checkmark"></span>
                                            <span class="chbLabel"><?php _e('WhatsApp', 'traveling-store'); ?></span>
                                        </label>
                                    </div>
                                    <div class="chbWrap">
                                        <label class="container">
                                            <input type="checkbox">
                                            <span class="checkmark"></span>
                                            <span class="chbLabel"><?php _e('Viber', 'traveling-store'); ?></span>
                                        </label>
                                    </div>
                                    <div class="chbWrap">
                                        <label class="container">
                                            <input type="checkbox">
                                            <span class="checkmark"></span>
                                            <span class="chbLabel"><?php _e('Telegram', 'traveling-store'); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="paymentTypeWrap">
                            <div class="inputRowWrap">
                                <div class="inputLabel">
                                    <?php _e('Выберете сособ оплаты:', 'traveling-store'); ?>
                                </div>
                                <div class="inputRow radioRow">
                                    <div class="chbWrap radioWrap">
                                        <label class="container">
                                            <input type="radio" name="paymentType">
                                            <span class="checkmark"></span>
                                            <span class="chbLabel"><?php _e('Оплата онлайн', 'traveling-store'); ?></span>
                                        </label>
                                        <div class="radioContent">
                                            <div class="p">
                                                <?php _e('Выбирая способ оплаты <span class="bold">“Оплата онлайн”</span> Вас
                                                перенаправит на страницу
                                                сервиса <span class="bold">LiqPay</span> для произведения оплаты онлайн
                                                с помощью банковской
                                                карты.', 'traveling-store'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chbWrap radioWrap">
                                        <label class="container">
                                            <input type="radio" name="paymentType">
                                            <span class="checkmark"></span>
                                            <span class="chbLabel"><?php _e('Оплатить на месте', 'traveling-store'); ?></span>
                                        </label>
                                        <div class="radioContent">
                                            <div class="p">
                                                <?php _e('Выбирая способ оплаты <span class="bold">“Оплатить на месте”</span> Вы
                                                оплатите экскурсию перед её началом.', 'traveling-store'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="rightSideSideBar">
                    <div class="checkoutSideBarHead">
                        <div class="basketSideBarHeadRow">
                            <span class="label"><?php _e('Всего (1 позиция)', 'traveling-store'); ?></span>
                            <span class="value confirmationPrice">$120</span>
                        </div>
                        <div class="greenSideBarLabel">
                            <?php _e('Без дополнительных сборов и комиссий.', 'traveling-store'); ?>
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
                                <?php _e('Подтвердить заказ', 'traveling-store'); ?>
                            </span>
                        </a>
                        <div class="p">
                            <?php _e('Оформляя заказ, вы тем самым принимаете <a href="#">Общие условия и положения</a>.', 'traveling-store'); ?>
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
