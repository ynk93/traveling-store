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

    ?>

    <table cellpadding="0" class="itemsTable" style="padding: 10px 0;width: 100%;border-top: 1px solid #EBEBEB;">
        <tbody>
        <tr>
            <td>
                <img src="https://traveling-store.com/wp-content/themes/traveling-store/assets/images/mailTemplates/logo.png"
                     style="display: block;
    width: 155px;
    height: auto;
object-fit: contain;
margin-left: 10px;">
            </td>
            <td>
                        <span style="color:#7e7e7e;vertical-align:middle;padding-right:20px;display:block;text-align:right;margin-left:auto">
                            <span style="color:#7e7e7e;vertical-align:middle;display:block;padding:0;text-align: right">
                                <?php _e('Билет №', 'traveling-store'); ?>
                            </span>
                <span style="vertical-align:middle;color:#f87f27;margin-top:4px;font-weight:bold;text-align: right">
                                <?php echo '#' . $order->get_id(); ?>
                            </span>
                </span>
            </td>
        </tr>
        </tbody>
    </table>

    <table cellpadding="0" style="width: 100%;table-layout: fixed;" class="mainDataTable">
        <tbody>
        <tr>
            <td style="width: calc(100% / 4); padding: 0 20px">
                        <span style="font-weight: bold; padding: 0">
                            Экскурсия /
                            Excursion
                        </span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="padding: 0">
                    <?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false)); ?>
                </span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="font-weight: bold; padding: 0">Начало / Start</span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px; border-right: 1px solid #ebebeb">
                <span style="padding: 0">16:00</span>
            </td>
        </tr>
        <tr>
            <td style="width: calc(100% / 4); padding: 0 20px">
                        <span style="font-weight: bold; padding: 0">
                            Дата / Date
                        </span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="padding: 0"><?php echo wp_kses_post($booking_date); ?></span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="font-weight: bold; padding: 0">Всего / Total</span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px; border-right: 1px solid #ebebeb;">
                <span style="vertical-align: middle; display: block; padding: 0; color: #000;">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    <?php echo wp_kses_post($order->get_subtotal($item)); ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width: calc(100% / 4); padding: 0 20px">
                        <span style="font-weight: bold; padding: 0">
                            Имя / Name
                        </span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="padding: 0"><?php echo wp_kses_post($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="font-weight: bold; padding: 0">Задаток / Deposit</span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px; border-right: 1px solid #ebebeb;">
                <span style="vertical-align: middle; display: block; padding: 0; color: #000;">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    0
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width: calc(100% / 4); padding: 0 20px">
                        <span style="font-weight: bold; padding: 0">
                            Отель / Hotel
                        </span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="padding: 0"><?php echo wp_kses_post($order->get_billing_address_1()); ?></span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px">
                <span style="font-weight: bold; padding: 0">Остаток / Balance</span>
            </td>
            <td style="width: calc(100% / 4); padding: 0 20px; border-right: 1px solid #ebebeb;">
                <span style="vertical-align: middle; display: block; color: #000; padding: 0">
                    <span class="woocommerce-Price-amount amount" style="padding: 0; display: inline;">
                        <span class="woocommerce-Price-currencySymbol" style="padding: 0; display: inline;">$</span>
	                    <?php echo wp_kses_post($order->get_subtotal($item)); ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width: 25%; padding: 0 20px">
                        <span style="font-weight: bold; padding: 0">
                            Номер / Room
                        </span>
            </td>
            <td style="width: 25%; padding: 0 20px">
                <span style="padding: 0"><?php echo wp_kses_post($order->get_billing_address_2()); ?></span>
            </td>
            <td style="width: 25%; padding: 0 20px">
                <span style="font-weight: bold; padding: 0">Замечания / Note</span>
            </td>
            <td style="width: 25%; min-width: calc(100% / 4); padding: 0 20px; border-right: 1px solid #ebebeb"><?php echo wp_kses_post(wpautop(do_shortcode($purchase_note))); ?></td>
        </tr>
        </tbody>
    </table>
    <table class="bottomTable" style="
    table-layout: fixed;
    width: 100%;
    border-top: 1px solid #ebebeb;
    font-weight: bold;
    border-spacing: 0;
    border-right: 1px solid #ebebeb;" cellspacing="0" cellpadding="20">
        <tbody>
        <?php wc_bookings_get_summary_list($booking); ?>
        </tbody>
    </table>

<?php endforeach; ?>
