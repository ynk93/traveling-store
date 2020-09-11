<?php
/**
 * The template for displaying the list of bookings in the summary for customers.
 * It is used in:
 * - templates/order/booking-display.php
 * - templates/order/admin/booking-display.php
 * It will display in four places:
 * - After checkout,
 * - In the order confirmation email, and
 * - When customer reviews order in My Account > Orders,
 * - When reviewing a customer order in the admin area.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/order/booking-summary-list.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.10.8
 * @since   1.10.8
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<tr>

    <?php
    if ($product && $product->has_persons()) {
        if ($product->has_person_types()) {
            $person_types = $product->get_person_types();
            $person_counts = $booking->get_person_counts();
            $child_count = 0;
            $grouped_person_types = array(
                'adult' => array(),
                'children' => array()
            );

            if (!empty($person_types) && is_array($person_types)) {

                foreach ($person_types as $person_type) {

                    if ($person_type->get_name() === 'Adult' || $person_type->get_name() === 'Взрослые') {
                        $grouped_person_types['adult'] = $person_type;
                    } else {
                        $grouped_person_types['children'][$child_count] = $person_type;
                        $child_count++;
                    }

                }

                if (count($grouped_person_types['adult']) !== 0) : ?>

                    <td style="width: 25%">
                            <span style="color: #000;">
                                Взрослые / Adults
                            </span>
                    </td>

                    <td style=" border-left: 1px solid #ebebeb; width: 25%">
                            <span>
                                <?php echo !empty( $person_counts[$person_type->get_id()] ) ? $person_counts[$person_type->get_id()] : 0; ?>
                            </span>
                        <?php if ( !empty( $person_counts[$person_type->get_id()] ) ) : ?>
                        <span class="innerSpanPrice">
                                x <?php echo $person_type->get_cost() !== 0 ? $person_type->get_cost() . ' $' : 'Бесплатно'; ?>
                            </span>
                        <?php endif; ?>
                    </td>

                <?php endif;

                if (count($grouped_person_types['children']) !== 0) : ?>

                    <td style=" border-left: 1px solid #ebebeb; width: 25%">
                        <span style="font-weight: bold; color: #000;">Дети / Children</span>
                    </td>

                    <td style=" border-left: 1px solid #ebebeb;width: 25%">
                        <table style="border: 0;">
                            <tbody>

                            <?php
                            foreach ($grouped_person_types['children'] as $person_type) {

                                if ( empty($person_counts[$person_type->get_id()]) ) {
                                    continue;
                                } ?>

                                <tr>
                                    <td style="border: 0;">
                                                <span class="innerSpan">
                                                    <span class="orderedValue"
                                                          style="font-weight: normal; padding: 0; display: inline; color: #000;">
                                                        <?php echo $person_type->get_name(); ?>
                                                    </span>
                                                    <span class="innerSpanDescription"
                                                          style="color: #7E7E7E; font-weight: 300;padding: 0; display: inline;">
                                                        <?php echo $person_counts[$person_type->get_id()]; ?>
                                                    </span>
                                                    <span class="innerSpanPrice">
                                                        x <?php echo $person_type->get_cost() !== 0 ? $person_type->get_cost() . ' $' : 'Бесплатно'; ?>
                                                    </span>
                                                </span>
                                    </td>
                                </tr>


                                <?php
                            }
                            ?>

                            </tbody>
                        </table>
                    </td>

                <?php endif;
            }
        } else {
            ?>
            <td>
                    <span>
                        <?php
                        /* translators: 1: person count */
                        echo esc_html(sprintf(__('%d Persons', 'woocommerce-bookings'), array_sum($booking->get_person_counts())));
                        ?>
                    </span>
            </td>
            <?php
        }
    }
    ?>

</tr>
