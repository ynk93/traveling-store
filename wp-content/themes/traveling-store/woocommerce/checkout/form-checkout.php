<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout checkoutPageContent" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

        <div class="checkoutLeftSide">
            <div class="checkoutContent">
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
            </div>
        </div>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	<?php //do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

    <div class="rightSideSideBar">

	    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

    </div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
