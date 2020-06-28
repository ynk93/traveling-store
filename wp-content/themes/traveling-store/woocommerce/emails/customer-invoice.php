<?php
/**
 * Customer invoice email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>
                        <span class="mailHead">
                            <?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), '<span class="bold userName">' . esc_html( $order->get_billing_first_name() ) . '</span>' ); ?>
                            <br>
                            <?php _e('Thank you for using the Traveling Store service!', 'traveling-store'); ?>
                            <br>
	                        <?php _e('You can download and print your ticket by clicking on', 'traveling-store'); ?>
                            <a class="textLink" href="#" target="_blank"><?php _e('this link.', 'traveling-store'); ?></a>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table cellpadding="0" class="logoRowTable">
                <tbody>
                <tr>
                    <td>
                        <a class="logo" href="http://traveling-store.com/"></a>
                    </td>
                    <td>
                        <span class="ticketNumWrap">
                            <span>
                                <?php _e('Invoice', 'traveling-store'); ?>
                            </span>
                            <span class="ticketNumber">
                                <?php echo '#' . $order->get_id(); ?>
                            </span>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

<?php
/**
 * Hook for the woocommerce_email_order_details.
 *
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for the woocommerce_email_order_meta.
 *
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
