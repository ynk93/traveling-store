<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$fields = $checkout->get_checkout_fields( 'billing' );

?>

<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

<div class="peoplesDataWrap">

    <div class="fakeInputRowWrap">
        <div class="inputLabel">
			<?php _e('Group members', 'traveling-store'); ?>
        </div>
        <div class="innerLabel">
            <div class="p"><?php _e('Enter the first name and last name of all members of the excursion group.', 'traveling-store'); ?></div>
        </div>
    </div>

    <div class="inputRowWrap">
        <div class="inputLabel">
			<?php _e('Взрослый 1', 'traveling-store'); ?>
        </div>
        <div class="inputRow twoInputs">
            <div class="inputWrap">
                <?php woocommerce_form_field( 'billing_first_name', $fields['billing_first_name'], $checkout->get_value( 'billing_first_name' ) ); ?>
            </div>
            <div class="inputWrap">
	            <?php woocommerce_form_field( 'billing_last_name', $fields['billing_last_name'], $checkout->get_value( 'billing_last_name' ) ); ?>
            </div>
        </div>
    </div>

    <div class="inputRowWrap">
        <div class="inputLabel">
			<?php _e('Взрослый 2', 'traveling-store'); ?>
        </div>
        <div class="inputRow twoInputs">
            <div class="inputWrap"><input type="text" placeholder="Имя"></div>
            <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
        </div>
    </div>

    <div class="inputRowWrap">
        <div class="inputLabel">
			<?php _e('Ребенок 1', 'traveling-store'); ?>
        </div>
        <div class="inputRow twoInputs">
            <div class="inputWrap"><input type="text" placeholder="Имя"></div>
            <div class="inputWrap"><input type="text" placeholder="Фамилия"></div>
        </div>
    </div>

</div>

<div class="placeDataWrap">

    <div class="inputRowWrap">
        <div class="inputRow threeInputs">
            <div class="inputWrap">
	            <?php
		            $current_field                           = 'billing_state';
		            $fields[ $current_field ]['placeholder'] = 'Region';
		            woocommerce_form_field( $current_field, $fields[ $current_field ], $checkout->get_value( $current_field ) );
	            ?>
            </div>
            <div class="inputWrap">
	            <?php
		            $current_field                           = 'billing_address_1';
		            $fields[ $current_field ]['placeholder'] = 'Hotel name';
		            woocommerce_form_field( $current_field, $fields[ $current_field ], $checkout->get_value( $current_field ) );
	            ?>
            </div>
            <div class="inputWrap">
	            <?php
		            $current_field                           = 'billing_address_2';
		            $fields[ $current_field ]['placeholder'] = 'Room number';
		            woocommerce_form_field( $current_field, $fields[ $current_field ], $checkout->get_value( $current_field ) );
	            ?>
            </div>
        </div>
    </div>

    <div class="inputRowWrap">
        <div class="inputRow twoInputs">
            <div class="inputWrap">
	            <?php
		            $current_field                           = 'billing_email';
		            $fields[ $current_field ]['placeholder'] = 'E-mail';
		            woocommerce_form_field( $current_field, $fields[ $current_field ], $checkout->get_value( $current_field ) );
	            ?>
            </div>
            <div class="inputWrap">
	            <?php
		            $current_field                           = 'billing_phone';
		            $fields[ $current_field ]['placeholder'] = 'Phone number: (+___) __-__-__-___';
		            woocommerce_form_field( $current_field, $fields[ $current_field ], $checkout->get_value( $current_field ) );
	            ?>
            </div>
        </div>
    </div>

</div>

<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>