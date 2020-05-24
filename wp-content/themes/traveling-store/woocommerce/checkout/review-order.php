<?php
	/**
	 * Review order table
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce/Templates
	 * @version 3.8.0
	 */

	defined( 'ABSPATH' ) || exit;

	$cart = WC()->cart;

?>

<div class="checkoutSideBarHead">
    <div class="basketSideBarHeadRow">
        <span class="label">
            <?php _e( 'Subtotal', 'traveling-store' );
            echo ' (products ' . $cart->get_cart_contents_count() . ')'; ?>
        </span>
        <span class="value confirmationPrice">
            <?php wc_cart_totals_subtotal_html(); ?>
        </span>
    </div>
    <div class="greenSideBarLabel">
		<?php _e( 'No additional fees and commissions.', 'traveling-store' ); ?>
    </div>
</div>

<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		$item_data = array();
		$booking_data = apply_filters( 'woocommerce_get_item_data', array(), $cart_item );

		foreach ( $booking_data as $booking_data_item ) {

			if ( $booking_data_item['name'] == 'Booking Date' ) {

				$t = strtotime($booking_data_item['value']);
				$date = date('d M Y', $t);

				$item_data['date'] = $date;

			} else {

				$item_data['person_types'][$booking_data_item['name']] = $booking_data_item['value'];

			}
		}

		foreach ( $_product->get_attributes() as $product_attribute ) {

			$attribute_name = wc_attribute_label($product_attribute->get_name());
			$attribute_terms = $product_attribute->get_terms();

			$i = 0;

			foreach ( $attribute_terms as $attribute_term ) {

				$i++;

				if ( $i !== count($attribute_terms) ) {
					$term_name = $attribute_term->name . ', ';
				} else {
					$term_name = $attribute_term->name;
				}

				$item_data['attributes'][$attribute_name] .= $term_name;

			}

		}

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
            <div class="basketItem">

                <div class="itemPic">
	                <?php
		                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

		                if ( ! $product_permalink ) {
			                echo $thumbnail; // PHPCS: XSS ok.
		                } else {
			                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
		                }
	                ?>
                </div>

                <div class="itemInfo">

                    <div class="itemName product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
			            <?php
				            if ( ! $product_permalink ) {
					            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
				            } else {
					            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
				            }

				            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

				            // Meta data.
				            //echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

				            // Backorder notification.
				            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
					            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
				            }
			            ?>
                    </div>

		            <?php if ( !empty($item_data['date']) ) : ?>

                        <div class="infoBlock">
                            <div class="label">
					            <?php _e('Date and time', 'traveling-tour'); ?>
                            </div>
                            <div class="value">
                                <span class="date"><?php echo $item_data['date']; ?></span>

					            <?php if ( !empty($item_data['attributes']['Time']) ) : ?>
                                    <span class="time"><?php echo $item_data['attributes']['Time']; ?></span>
					            <?php endif; ?>

                            </div>
                        </div>

		            <?php endif; ?>

		            <?php if ( ! empty( $item_data['attributes'] ) ) :
			            foreach ( $item_data['attributes'] as $attribute_name => $attribute_value ) :
				            if ( $attribute_name !== 'Time' ) : ?>

                                <div class="infoBlock">
                                    <div class="label">
							            <?php echo $attribute_name; ?>
                                    </div>
                                    <div class="value">
							            <?php echo $attribute_value; ?>
                                    </div>
                                </div>

				            <?php endif;
			            endforeach;
		            endif; ?>

                </div>

            </div>
			<?php
		}
	}

	do_action( 'woocommerce_review_order_after_cart_contents' );
?>

<div class="sideBarBottom">

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

    <div class="basketSideBarHeadRow">
        <span class="label"><?php _e( 'Total', 'traveling-store' ); ?></span>
        <span class="value confirmationPrice">
            <?php wc_cart_totals_order_total_html(); ?>
        </span>
    </div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    <button type="submit" class="confirmOrderButton">
        <span>
            <?php _e( 'Confirm the order', 'traveling-store' ); ?>
        </span>
    </button>
    <div class="p">
		<?php _e( 'By placing an order, you hereby accept the General ' ); ?> <a href="<?php echo get_site_url() . '/privacy-policy/'; ?>"><?php _e('Terms and Conditions', 'traveling-store'); ?></a>.
    </div>

</div>