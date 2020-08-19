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

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';

foreach ($items as $item_id => $item) :
    $product = $item->get_product();
    $sku = '';
    $purchase_note = '';
    $image = '';

    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
        continue;
    }

    if (is_object($product)) {
        $sku = $product->get_sku();
        $purchase_note = $product->get_purchase_note();
        $image = $product->get_image($image_size);
    }

    $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id($item_id);
    $booking = new WC_Booking($booking_ids[0]);

    $get_local_time = wc_should_convert_timezone($booking);

    if (strtotime('midnight', $booking->get_start()) === strtotime('midnight', $booking->get_end())) {
        $booking_date = sprintf('%1$s', $booking->get_start_date(null, null, $get_local_time));
    } else {
        $booking_date = sprintf('%1$s - %2$s', $booking->get_start_date(null, null, $get_local_time), $booking->get_end_date(null, null, $get_local_time));
    }

//	var_dump($item);
//
//	die;

    ?>

    <table cellpadding="0" style="padding: 10px 0;width: 100%;border-top: 1px solid #EBEBEB;">
        <tbody>
        <tr>
            <td>
                <a href="https://traveling-store.com/" title="Traveling Store" style="display: block;
    width: 155px;
    height: 55px;
    background: url('https://traveling-store.com/wp-content/themes/traveling-store/images/mailTemplates/logo.png') no-repeat center/contain;"></a>
            </td>
            <td>
                        <span style="padding-right: 20px;display: block; text-align: right; margin-left: auto;">
                            <span style="display: block;padding: 0;">
                                <?php _e('Билет №', 'traveling-store'); ?>
                            </span>
                            <span style="color: #F87F27;margin-top: 4px;font-weight: bold;">
                                <?php echo '#' . $order->get_id(); ?>
                            </span>
                        </span>
            </td>
        </tr>
        </tbody>
    </table>
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
                    <?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false)); ?>
                </span>
            </td>
            <td>
                <span style="font-weight: bold">Начало / Start</span>
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
                <span><?php echo wp_kses_post($booking_date); ?></span>
            </td>
            <td>
                <span style="font-weight: bold">Всего / Total</span>
            </td>
            <td>
                <span style="vertical-align: middle; display: block; padding: 0 20px; color: #000;">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    <?php echo wp_kses_post($order->get_subtotal($item)); ?>
                    </span>
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
                <span><?php echo wp_kses_post($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></span>
            </td>
            <td>
                <span style="font-weight: bold">Задаток / Deposit</span>
            </td>
            <td>
                <span style="vertical-align: middle; display: block; padding: 0 20px; color: #000;">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    50
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Отель / Hotel
                        </span>
            </td>
            <td>
                <span><?php echo wp_kses_post($order->get_billing_address_1()); ?></span>
            </td>
            <td>
                <span style="font-weight: bold">Остаток / Balance</span>
            </td>
            <td>
                <span style="vertical-align: middle; display: block; padding: 0 20px; color: #000;">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    <?php echo wp_kses_post($order->get_subtotal($item) - 50); ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td>
                        <span>
                            Номер / Room
                        </span>
            </td>
            <td>
                <span><?php echo wp_kses_post($order->get_billing_address_2()); ?></span>
            </td>
            <td>
                <span style="font-weight: bold">Замечания / Note</span>
            </td>
            <td><?php echo wp_kses_post(wpautop(do_shortcode($purchase_note))); ?></td>
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
                <span style="font-weight: bold">Дети / Children</span>
            </td>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td><span class="innerSpan">
                                <span class="orderedValue"
                                      style="font-weight: normal; padding: 0; display: inline;">1</span>
                                <span class="innerSpanDescription"
                                      style="color: #7E7E7E; font-weight: 300;padding: 0; display: inline;">(0-3 лет / 0-3 years)</span>
                            </span></td>
                    </tr>
                    <tr>
                        <td>
                            <span class="innerSpan">
                                <span class="orderedValue bold"
                                      style="font-weight: normal;padding: 0; display: inline;">1</span>
                                <span class="innerSpanDescription"
                                      style="color: #7E7E7E; font-weight: 300;padding: 0; display: inline;">(4-6 лет / 4-6 years)</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="innerSpan">
                                <span class="orderedValue bold"
                                      style="font-weight: normal;padding: 0; display: inline;">1</span>
                                <span class="innerSpanDescription"
                                      style="color: #7E7E7E; font-weight: 300;padding: 0; display: inline;">(7-12 лет / 7-12 years)</span>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

<?php endforeach; ?>
