<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

    <?php woocommerce_template_single_title(); ?>

    <div class="productCardRow">

        <div class="productCardLeftSide">

            <div class="productCardPic">

                <?php woocommerce_show_product_images(); ?>

            </div>

            <div class="productCardInfoArea">
                <div class="productCardInfoHead">
                    <a href="#" class="active"><?php _e('Описание', 'traveling-store'); ?></a>
                    <a href="#"><?php _e('Программа экскурсии', 'traveling-store'); ?></a>
                    <a href="#"><?php _e('Что включено', 'traveling-store'); ?></a>
                    <a href="#"><?php _e('Важная информация', 'traveling-store'); ?></a>
                    <div class="bottomLine"></div>
                </div>
                <div class="productCardInfoBlocks">
                    <div class="infoBlock active">
                        <div class="leftSide">
                            <div class="sideBarHead">
                                <div class="h5"><?php _e('О мероприятии', 'traveling-store'); ?></div>
                            </div>
                            <div class="infoBlockRows">
                                        <span class="productInfoRow">
                                            <span class="productInfoRowLabel"><?php _e('Регион', 'traveling-store'); ?></span>
                                            <span class="productInfoRowValue">Стамбул</span>
                                        </span>
                                <span class="productInfoRow">
                                            <span class="productInfoRowLabel"><?php _e('Дни проведения', 'traveling-store'); ?></span>
                                            <span class="productInfoRowValue">Вт,Ср,Пт</span>
                                        </span>
                                <span class="productInfoRow">
                                            <span class="productInfoRowLabel"><?php _e('Время', 'traveling-store'); ?></span>
                                            <span class="productInfoRowValue">14:00-18:00</span>
                                        </span>
                                <span class="productInfoRow">
                                            <span class="productInfoRowLabel"><?php _e('Длительность', 'traveling-store'); ?></span>
                                            <span class="productInfoRowValue">~2 часа</span>
                                        </span>
                                <span class="productInfoRow">
                                            <span class="productInfoRowLabel"><?php _e('Язык', 'traveling-store'); ?></span>
                                            <span class="productInfoRowValue">
                                                Русский, Английский, Турецкий
                                            </span>
                                        </span>
                            </div>
                        </div>
                        <div class="rightSide">
                            <div class="infoBlockContent">
                                <div class="p semibold">
									<?php _e('В центре исторической части Стамбула вы найдете собор Святой Софии, который
                                            является
                                            символом величия страны и демонстрирует великолепие византийского зодчества.','traveling-store'); ?>
                                </div>
                                <div class="p">
									<?php _e('Сегодня собор является одним из наиболее знаменитых музеев мира, экспозиции
                                            которого
                                            поражают своим великолепием и повествуют интересную историю страны и ее
                                            народа.
                                            На
                                            месте современного собора постоянно возводились культовые сооружения,
                                            которые по
                                            различным причинам постоянно разрушались.','traveling-store'); ?>
                                </div>
                                <div class="p">
									<?php _e('Строительство собора было начато в 994 году, именно с этой даты начинается
                                            официальная история современного собора. Император Византии Юстиниан решил
                                            продемонстрировать всему миру все величие и богатство страны, ну и конечно,
                                            увековечить свое имя. На строительстве трудились более десяти тысяч зодчих,
                                            со
                                            всего
                                            мира завозились самые лучшие и самые дорогие материалы.','traveling-store'); ?>
                                </div>
                                <div class="p">
									<?php _e('В отделке стен зодчие использовали уникальный розовый, белый и зеленый
                                            мрамор.
                                            Переходы оформлены резными фризами, которые покрыты слоем золота. В
                                            убранстве
                                            много
                                            слоновой кости, украшений из жемчуга, инкрустированных драгоценных камней.
                                            Как
                                            гласит легенда, император планировал стены из превосходного мрамора покрыть
                                            слоем
                                            золота. Остановило Юстиниана только предсказание, согласно которому собору
                                            грозил
                                            захват и разграбление.','traveling-store'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="infoBlock">
                        <div class="infoBlockContent">
                            <div class="p">
								<?php _e('Сегодня собор является одним из наиболее знаменитых музеев мира, экспозиции
                                            которого
                                            поражают своим великолепием и повествуют интересную историю страны и ее
                                            народа.
                                            На
                                            месте современного собора постоянно возводились культовые сооружения,
                                            которые по
                                            различным причинам постоянно разрушались.','traveling-store'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="infoBlock">
                        <div class="infoBlockContent">
                            <div class="p">
								<?php _e('Строительство собора было начато в 994 году, именно с этой даты начинается
                                            официальная история современного собора. Император Византии Юстиниан решил
                                            продемонстрировать всему миру все величие и богатство страны, ну и конечно,
                                            увековечить свое имя. На строительстве трудились более десяти тысяч зодчих,
                                            со
                                            всего
                                            мира завозились самые лучшие и самые дорогие материалы.','traveling-store'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="infoBlock">
                        <div class="infoBlockContent">
                            <div class="p">
								<?php _e('В отделке стен зодчие использовали уникальный розовый, белый и зеленый
                                            мрамор.
                                            Переходы оформлены резными фризами, которые покрыты слоем золота. В
                                            убранстве
                                            много
                                            слоновой кости, украшений из жемчуга, инкрустированных драгоценных камней.
                                            Как
                                            гласит легенда, император планировал стены из превосходного мрамора покрыть
                                            слоем
                                            золота. Остановило Юстиниана только предсказание, согласно которому собору
                                            грозил
                                            захват и разграбление.','traveling-store'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="productCardRightSide">
            <div class="sideBarHead">
                <div class="h5"><?php _e('Расчет стоимости', 'traveling-store'); ?></div>
            </div>
            <div class="productCardSideBarTourParams">
                <div class="productCardParamWrap">
                    <div class="card-param-picker date-picker">
                        <div class="input">
                            <div class="result"><?php _e('Выберите дату', 'traveling-store') ?><span></span>
                            </div>
                            <button class="calendarIcon"></button>
                        </div>
                        <div class="calendar card-picker-drop"></div>
                    </div>
                </div>
                <div class="productCardParamWrap">
                    <div class="card-param-picker time-picker">
                        <div class="input">
                            <div class="result">
                                <select name="timeSelector" id="">
                                    <option value="9-12">09:00 - 12:00</option>
                                    <option value="9-12">13:00 - 15:00</option>
                                </select>
                            </div>
                            <button class="arrowIcon"></button>
                        </div>
                    </div>
                </div>
                <div class="productCardParamWrap">
                    <div class="card-param-picker adults-num-picker">
                        <div class="input">
                            <div class="result">
                                <select name="adultPersonsNumPicker" id="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                            </div>
                            <button class="arrowIcon"></button>
                        </div>
                    </div>
                </div>
                <div class="productCardParamWrap">
                    <div class="card-param-picker child-num-picker">
                        <div class="input">
                            <div class="result">
								<?php _e('Укажите количество детей', 'traveling-store') ?>
                            </div>
                            <button class="arrowIcon"></button>
                        </div>
                        <div class="childrensData card-picker-drop">
                            <div class="row">
                                <div class="chbRowLabel">
									<?php _e('Дети (до 3х лет)', 'traveling-store'); ?>
                                </div>
                                <div class="counterInputElement">
                                    <a href="#" class="counterInputButton counterInputDecreaseButton"></a>
                                    <input type="number" value="0" name="childUntil3">
                                    <a href="#" class="counterInputButton counterInputIncreaseButton"></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="chbRowLabel">
									<?php _e('Дети (4-6 лет)', 'traveling-store'); ?>
                                </div>
                                <div class="counterInputElement">
                                    <a href="#" class="counterInputButton counterInputDecreaseButton"></a>
                                    <input type="number" value="0" name="childBtwn4and6">
                                    <a href="#" class="counterInputButton counterInputIncreaseButton"></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="chbRowLabel">
									<?php _e('Дети (7-12 лет)', 'traveling-store'); ?>
                                </div>
                                <div class="counterInputElement">
                                    <a href="#" class="counterInputButton counterInputDecreaseButton"></a>
                                    <input type="number" value="0" name="childBtwn7and12">
                                    <a href="#" class="counterInputButton counterInputIncreaseButton"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="productCardParamWrap">
                    <div class="card-param-picker lang-picker">
                        <div class="input">
                            <div class="result">
                                <select name="langPicker" id="">
                                    <option value="rus">Русский</option>
                                    <option value="eng">Английский</option>
                                </select>
                            </div>
                            <button class="arrowIcon"></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="productCardPriceWrap">
                <div class="productPriceRow">
                    <div class="label"><?php _e('Общая стоимость', 'traveling-store'); ?></div>
                    <div class="value">$120</div>
                </div>
                <a href="#" class="productPriceAddToBasket">
                    <span><?php _e('Добавить в корзину', 'traveling-store'); ?></span>
                </a>
            </div>
        </div>

    </div>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	//do_action( 'woocommerce_before_single_product_summary' );
	?>

    <?php
	    /**
	     * Hook: woocommerce_single_product_summary.
	     *
	     * @hooked woocommerce_template_single_title - 5
	     * @hooked woocommerce_template_single_rating - 10
	     * @hooked woocommerce_template_single_price - 10
	     * @hooked woocommerce_template_single_excerpt - 20
	     * @hooked woocommerce_template_single_add_to_cart - 30
	     * @hooked woocommerce_template_single_meta - 40
	     * @hooked woocommerce_template_single_sharing - 50
	     * @hooked WC_Structured_Data::generate_product_data() - 60
	     */
	    //do_action( 'woocommerce_single_product_summary' );
    ?>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	//do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
