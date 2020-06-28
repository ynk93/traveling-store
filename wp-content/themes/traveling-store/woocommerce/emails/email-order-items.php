<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';

foreach ( $items as $item_id => $item ) :
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( $image_size );
	}

	?>

    <table cellpadding="0" class="mainDataTable">
        <tbody>
        <tr>
            <td>
                        <span>
                            Экскурсия /
                            Excursion
                        </span>
            </td>
            <td>
                <span>
                    <?php echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) ); ?>
                </span>
            </td>
            <td>
                <span>Начало / Start</span>
            </td>
            <td>
                <span>16:00</span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Дата / Date
                        </span>
            </td>
            <td>
                <span>12.06.2020</span>
            </td>
            <td>
                <span>Всего / Total</span>
            </td>
            <td>
                <span>
                    <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                </span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Имя / Name
                        </span>
            </td>
            <td>
                <span>Джейн Вилсон</span>
            </td>
            <td>
                <span>Задаток / Deposit</span>
            </td>
            <td>
                <span>$50</span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Отель / Hotel
                        </span>
            </td>
            <td>
                <span>Grand Park Kemer</span>
            </td>
            <td>
                <span>Остаток / Balance</span>
            </td>
            <td>
                <span>$50</span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Номер / Room
                        </span>
            </td>
            <td>
                <span>3332</span>
            </td>
            <td>
                <span>Замечания / Note</span>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>
                        <span>
                            Взрослые / Adults
                        </span>
            </td>
            <td>
                <span>2</span>
            </td>
            <td>
                <span>Дети / Children</span>
            </td>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td><span class="innerSpan"><span class="orderedValue bold">1</span> <span
                                        class="innerSpanDescription">(0-3 лет / 0-3 years)</span> </span></td>
                    </tr>
                    <tr>
                        <td><span class="innerSpan"><span class="orderedValue bold">1</span> <span
                                        class="innerSpanDescription">(4-6 лет / 4-6 years)</span> </span></td>
                    </tr>
                    <tr>
                        <td><span class="innerSpan"><span class="orderedValue bold">1</span> <span
                                        class="innerSpanDescription">(7-12 лет / 7-12 years)</span> </span></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

<?php endforeach; ?>
