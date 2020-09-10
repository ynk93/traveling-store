<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';

//do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<tr>
    <td>
        <?php
        echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            $order,
            array(
                'show_sku' => $sent_to_admin,
                'show_image' => false,
                'image_size' => array(32, 32),
                'plain_text' => $plain_text,
                'sent_to_admin' => $sent_to_admin,
            )
        );
        ?>

        <table class="mainDataTable" style="width: 100%;table-layout: fixed;" cellpadding="0" cellspacing="0">
            <tbody>

            <tr class="mainRow">
                <?php $item_totals = $order->get_order_item_totals(); ?>

                <td style="width: 25%; padding: 0 20px">
                    <span style="padding: 0;font-weight: bold"><?php echo wp_kses_post($item_totals['cart_subtotal']['label']); ?></span>
                </td>
                <td style="width: 25%; padding: 0 20px">
                    <span style="padding: 0; white-space: nowrap"><?php echo wp_kses_post($item_totals['cart_subtotal']['value']); ?></span>
                </td>
                <td style="width: 25%; padding: 0 20px;">
                    <span style="padding: 0; font-weight: bold;"><?php echo wp_kses_post($item_totals['payment_method']['label']); ?></span>
                </td>
                <td style="width: 25%; padding: 0 15px; border-right: 1px solid #ebebeb;">
                    <span style="padding: 0; white-space: nowrap"><?php echo wp_kses_post($item_totals['payment_method']['value']); ?></span>
                </td>

            </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table class="mainDataTable" style="width: 100%;table-layout: fixed;" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="width: 50%; padding: 0 20px">
                    <span style="padding: 0; font-weight: bold"><?php echo wp_kses_post($item_totals['order_total']['label']); ?></span>
                </td>
                <td style="width: 50%; padding: 0 20px; border-right: 1px solid #ebebeb;">
                    <span style="padding: 0; white-space: nowrap"><?php echo wp_kses_post($item_totals['order_total']['value']); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table style="width: 100%; border-spacing: 0; border-top: 1px solid #ebebeb; padding-top: 20px">
            <tbody>
            <tr>
                <td style="padding: 24px 20px;">
                        <span class="bottomTextWrap"
                              style="border: 0; display: block; padding: 0">
                            <span class="bold"
                                  style="display: block;margin-bottom: 8px; font-weight: bold; color: #000;border: 0;"><?php _e('Attention!', 'traveling-store'); ?></span>
                            <span class="bold"
                                  style="display: block;margin-bottom: 8px; font-weight: bold; color: #000;border: 0;"><?php _e('Transportation for the tour should be expected on the street before entering the hotel, at the barrier.', 'traveling-store'); ?></span>
                            <span style="display: block;color:#7e7e7e;font-size:14px;line-height:20px;margin:0;border: 0;"><?php _e('In case of payment for the tour by bus, prepare the necessary amount', 'traveling-store'); ?></span>
                            <span style="display: block; color:#7e7e7e;font-size:14px;line-height:20px;margin:0;margin-top:8px;border: 0;"><?php _e('In case of refusal in less than 24 hours, 50% of the cost of the excursion is withheld, on the day of the trip the company withholds the full cost.', 'traveling-store'); ?></span>
                        </span>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>
