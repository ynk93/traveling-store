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

	            <?php do_action( 'woocommerce_checkout_billing' ); ?>

                <div class="contactTypeWrap">
                    <div class="inputRowWrap">
                        <div class="inputLabel">
							<?php _e('Choose a messenger to contact you:', 'traveling-store'); ?>
                        </div>
                        <div class="inputRow chbRow">

	                        <?php traveling_store_checkout_messenger_field( $checkout ); ?>

                        </div>
                    </div>
                </div>

                <div class="paymentTypeWrap">
                    <div class="inputRowWrap">

                        <div class="inputLabel">
							<?php _e('Choose a payment method:', 'traveling-store'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_place_payment'); ?>

                    </div>
                </div>

            </div>
        </div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

    <div class="rightSideSideBar">

	    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

    </div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
