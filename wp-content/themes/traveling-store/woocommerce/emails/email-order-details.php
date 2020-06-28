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

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

//do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<tr>
    <td>
	    <?php
		    echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			    $order,
			    array(
				    'show_sku'      => $sent_to_admin,
				    'show_image'    => false,
				    'image_size'    => array( 32, 32 ),
				    'plain_text'    => $plain_text,
				    'sent_to_admin' => $sent_to_admin,
			    )
		    );
	    ?>

        <table class="mainDataTable">
            <tbody>
                <tr class="mainRow">
                    <?php
                        $item_totals = $order->get_order_item_totals();

                        if ( $item_totals ) {
                            $i = 0;
                            foreach ( $item_totals as $total ) {
                                $i++;
                                ?>
                                <td>
                                    <span><?php echo wp_kses_post( $total['label'] ); ?></span>
                                </td>
                                <td>
                                    <span><?php echo wp_kses_post( $total['value'] ); ?></span>
                                </td>
                                <?php
                            }
                        }
                    ?>
                </tr>
            </tbody>
        </table>
    </td>
</tr>

<tr>
    <td>
        <table>
            <tbody>
            <tr>
                <td>
                        <span class="bottomTextWrap">
                            <span class="bold"><?php _e('Attention!', 'traveling-store'); ?></span>
                            <span class="bold"><?php _e('Transportation for the tour should be expected on the street before entering the hotel, at the barrier.', 'traveling-store'); ?></span>
                            <p><?php _e('In case of payment for the tour by bus, prepare the necessary amount', 'traveling-store'); ?></p>
                            <p><?php _e('In case of refusal in less than 24 hours, 50% of the cost of the excursion is withheld, on the day of the trip the company withholds the full cost.', 'traveling-store'); ?></p>
                        </span>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
