<?php
/**
 * Grouped product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/grouped.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart grouped_form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

    <div class="productCardRightSide">
        <div class="sideBarHead">
            <div class="h5"><?php _e('Расчет стоимости', 'traveling-store'); ?></div>
        </div>
        <div class="productCardSideBarTourParams">

            <div class="productCardParamWrap">
                <div class="card-param-picker date-picker">
                    <div class="input">
                        <div class="result">
                            <?php _e('Выберите дату', 'traveling-store') ?>
                            <span></span>
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
							<?php _e('Укажите количество персон', 'traveling-store') ?>
                        </div>
                        <button class="arrowIcon"></button>
                    </div>
                    <div class="woocommerce-grouped-product-list group_table childrensData card-picker-drop">

	                    <?php
		                    $quantites_required      = false;
		                    $previous_post           = $post;
		                    $grouped_product_columns = apply_filters( 'woocommerce_grouped_product_columns', array(
			                    'label',
		                        'quantity',
		                    ), $product );

		                    foreach ( $grouped_products as $grouped_product_child ) {
			                    $post_object        = get_post( $grouped_product_child->get_id() );
			                    $quantites_required = $quantites_required || ( $grouped_product_child->is_purchasable() && ! $grouped_product_child->has_options() );
			                    $post               = $post_object; // WPCS: override ok.
			                    setup_postdata( $post );

			                    echo '<div id="product-' . esc_attr( $grouped_product_child->get_id() ) . '" class="woocommerce-grouped-product-list-item ' . esc_attr( implode( ' ', wc_get_product_class( '', $grouped_product_child ) ) ) . ' row">';

			                    // Output columns for each product.
			                    foreach ( $grouped_product_columns as $column_id ) {
				                    do_action( 'woocommerce_grouped_product_list_before_' . $column_id, $grouped_product_child );

				                    switch ( $column_id ) {
					                    case 'quantity':
						                    ob_start();

						                    if ( ! $grouped_product_child->is_purchasable() || $grouped_product_child->has_options() || ! $grouped_product_child->is_in_stock() ) {
							                    woocommerce_template_loop_add_to_cart();
						                    } elseif ( $grouped_product_child->is_sold_individually() ) {
							                    echo '<input type="checkbox" name="' . esc_attr( 'quantity[' . $grouped_product_child->get_id() . ']' ) . '" value="1" class="wc-grouped-product-add-to-cart-checkbox" />';
						                    } else {
							                    do_action( 'woocommerce_before_add_to_cart_quantity' );

							                    woocommerce_quantity_input( array(
								                    'input_name'  => 'quantity[' . $grouped_product_child->get_id() . ']',
								                    'input_value' => isset( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ? wc_stock_amount( wc_clean( wp_unslash( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ) ) : 0, // WPCS: CSRF ok, input var okay, sanitization ok.
								                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $grouped_product_child ),
								                    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product_child->get_max_purchase_quantity(), $grouped_product_child ),
							                    ) );

							                    do_action( 'woocommerce_after_add_to_cart_quantity' );
						                    }

						                    $value = ob_get_clean();
						                    break;
					                    case 'label':
					                        $parent_product_name = $product->get_name() . ': ';
					                        $group_product_name = $grouped_product_child->get_name();

					                        $label = str_replace($parent_product_name, "", "$group_product_name");

						                    $value  = '<div class="chbRowLabel">';
						                    $value .= $label;
						                    $value .= '</div>';
						                    break;
					                    default:
						                    $value = '';
						                    break;
				                    }

				                    echo apply_filters( 'woocommerce_grouped_product_list_column_' . $column_id, $value, $grouped_product_child ); // WPCS: XSS ok.

				                    do_action( 'woocommerce_grouped_product_list_after_' . $column_id, $grouped_product_child );
			                    }

			                    echo '</div>';
		                    }
		                    $post = $previous_post; // WPCS: override ok.
		                    setup_postdata( $post );
	                    ?>

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

            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />


	        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                <button type="submit" class="single_add_to_cart_button button alt productPriceAddToBasket">
                    <?php _e('Добавить в корзину', 'traveling-store'); ?>
                </button>

	        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
