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

$product_attributes = $product->get_attributes();

$excursion_program = get_field('excursion_program');
$what_is_included = get_field('what_is_included');
$important_information = get_field('important_information');

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

            <div class="productCardPic" style="overflow: hidden;">

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

	                    <?php if ( $product_attributes ) : ?>

                            <div class="leftSide">
                                <div class="sideBarHead">
                                    <div class="h5"><?php _e('About event', 'traveling-store'); ?></div>
                                </div>
                                <div class="infoBlockRows">

                                    <?php foreach ( $product_attributes as $product_attribute ) : ?>

                                        <span class="productInfoRow">
                                            <span class="productInfoRowLabel">
                                                <?php echo wc_attribute_label($product_attribute->get_name(), $product); ?>
                                            </span>
                                            <span class="productInfoRowValue">

                                                <?php $options = $product_attribute->get_terms();

                                                $options_count = count( $options );
                                                $i             = 0;

                                                foreach ( $options as $option ) {

                                                    $i ++;
                                                    $separator = '';

                                                    if ( $options_count !== $i ) {
                                                        $separator = ', ';
                                                        }

                                                        echo $option->name . $separator;

                                                    } ?>

                                            </span>
                                        </span>

                                    <?php endforeach; ?>

                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="rightSide">
                            <div class="infoBlockContent">

                                <?php echo woocommerce_template_single_excerpt(); ?>

                            </div>
                        </div>
                    </div>

                    <?php if ( $excursion_program ) : ?>

                        <div class="infoBlock">
                            <div class="infoBlockContent">
                                <?php echo $excursion_program; ?>
                            </div>
                        </div>

                    <?php endif; ?>

	                <?php if ( $what_is_included ) : ?>

                        <div class="infoBlock">
                            <div class="infoBlockContent">
				                <?php echo $what_is_included; ?>
                            </div>
                        </div>

	                <?php endif; ?>

	                <?php if ( $important_information ) : ?>

                        <div class="infoBlock">
                            <div class="infoBlockContent">
				                <?php echo $important_information; ?>
                            </div>
                        </div>

	                <?php endif; ?>

                </div>
            </div>

        </div>

        <div class="productCardRightSide">
            <div class="sideBarHead">
                <div class="h5"><?php _e('Расчет стоимости', 'traveling-store'); ?></div>
            </div>
            <div class="productCardSideBarTourParams">

	            <?php woocommerce_template_single_add_to_cart(); ?>

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

                <?php woocommerce_template_single_price(); ?>

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
