<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="rightSideSideBar cart_totals">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

    <div class="basketSideBarHead">
        <span class="label"><?php _e('Total', 'traveling-store'); ?></span>

	    <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

        <span class="value confirmationPrice">
            <?php wc_cart_totals_order_total_html(); ?>
        </span>

	    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

    </div>
    <a href="/checkout/" class="confirmOrderButton" target="_self">
        <span><?php _e('Checkout', 'traveling-store'); ?></span>
    </a>
    <a href="/tours/" class="continueShoppingButton" target="_self">
        <span><?php _e('Continue searching', 'traveling-store'); ?></span>
    </a>
    <div class="sideBarInfoBlock">
        <div class="sideBarInfoTitle"><?php _e('Best price guaranteed', 'traveling-store'); ?></div>
        <div class="sideBarInfoText">
			<?php _e('Наш сервис даёт возможность туристическому объекту-исполнителю, предоставить услугу
                            туристам, минуя посредников, агентств и т. п. Что в разы сокращает стоимость туристических
                            услуг.', 'traveling-store'); ?>
        </div>
    </div>


	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
