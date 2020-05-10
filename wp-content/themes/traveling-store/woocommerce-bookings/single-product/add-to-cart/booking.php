<?php
/**
 * Booking product add to cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/single-product/add-to-cart.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.10.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$nonce = wp_create_nonce( 'find-booked-day-blocks' );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>


<noscript><?php esc_html_e( 'Your browser must support JavaScript in order to make a booking.', 'woocommerce-bookings' ); ?></noscript>

<form class="cart productCardRightSide" method="post" enctype='multipart/form-data' data-nonce="<?php echo esc_attr( $nonce ); ?>">

    <div class="sideBarHead">
        <div class="h5"><?php _e('Расчет стоимости', 'traveling-store'); ?></div>
    </div>

	<div id="wc-bookings-booking-form" class="wc-bookings-booking-form" style="display:none">

		<?php do_action( 'woocommerce_before_booking_form' ); ?>

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

                <div class="card-param-picker child-num-picker">

                    <div class="input">
                        <div class="result">
							<?php _e('Укажите количество детей', 'traveling-store') ?>
                        </div>
                        <button class="arrowIcon"></button>
                    </div>

                    <div class="childrensData card-picker-drop">

	                    <?php $booking_form->output(); ?>

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
                <div class="value">
                    <div class="wc-bookings-booking-cost" style="display:none" data-raw-price=""></div>
                </div>
            </div>
            <a href="#" class="productPriceAddToBasket">
                <span><?php _e('Добавить в корзину', 'traveling-store'); ?></span>
            </a>
        </div>

	</div>

	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( is_callable( array( $product, 'get_id' ) ) ? $product->get_id() : $product->id ); ?>" class="wc-booking-product-id" />

	<button type="submit" class="wc-bookings-booking-form-button single_add_to_cart_button button alt disabled" style="display:none"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
