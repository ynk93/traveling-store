<?php
/**
 * @author   SAU/CAL
 * @category Class
 * @package  Woocommerce_Bluesnap_Gateway/Classes
 * @version  1.3.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle all woo addons integrations.
 * - Subscriptions.
 *
 * Class WC_Bluesnap_Gateway_Addons
 */
class WC_Bluesnap_Gateway_Addons extends WC_Bluesnap_Gateway {

	const ORDER_ONDEMAND_WALLET_ID = '_bluesnap_ondemand_subscription_id';

	/**
	 * WC_Bluesnap_Addons constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Subscriptions related hooks.
		if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
			// Filters
			add_filter( 'wc_' . $this->id . '_save_payment_method', array( $this, 'force_save_payment_method' ), 1, 2 );
			add_filter( 'wc_gateway_bluesnap_payment_request_cart_item_line_items', array( $this, 'add_subscription_free_trial' ), 10, 3 );
			add_filter( 'wc_gateway_bluesnap_payment_request_items', array( $this, 'add_recurring_totals' ), 10, 2 );
			add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription' ) );
		}

		// PreOrders hooks.
		if ( class_exists( 'WC_Pre_Orders_Order' ) ) {
			add_action( 'wc_pre_orders_process_pre_order_completion_payment_' . $this->id, array( $this, 'scheduled_pre_order_payment' ) );
			add_filter( 'wc_gateway_bluesnap_payment_request_items', array( $this, 'add_pre_order_line' ), 10, 2 );
			add_filter( 'wc_gateway_bluesnap_payment_request_calculated_total', array( $this, 'remove_pre_order_from_total' ), 10 );
		}

		add_filter( 'wc_gateway_bluesnap_payment_request_apple_pay_version_required', array( $this, 'bump_apple_pay_version' ) );
		add_filter( 'wc_' . $this->id . '_hide_save_payment_method_checkbox', array( $this, 'maybe_hide_save_checkbox' ) );
	}

	/**
	 * It will process the payment depending on its type.
	 *
	 * @param int $order_id
	 * @param string $transaction_type
	 * @param null|string $override_total
	 * @param bool $payment_complete
	 *
	 * @return array
	 * @throws Exception
	 */
	public function process_payment( $order_id, $transaction_type = self::AUTH_AND_CAPTURE, $override_total = null, $payment_complete = true ) {
		if ( $this->has_subscription( $order_id ) ) {
			return $this->process_subscription( $order_id );
		} elseif ( $this->has_pre_order( $order_id ) ) {
			return $this->process_pre_order( $order_id );
		}
		// Fallback to normal process payment.
		return parent::process_payment( $order_id );
	}

	protected function get_adapted_payload_for_ondemand_wallet( $order ) {
		$payload = $this->fetch_transaction_payment_method_payload( $order );
		switch ( $payload->type ) {
			case 'new_card':
				if ( empty( $payload->payload['vaultedShopperId'] ) ) {
					$payload->payload['payerInfo'] = $payload->payer_info;
				}
				unset( $payload->payload['cardHolderInfo'] );
				$payload->payload['paymentSource'] = array(
					'creditCardInfo' => array(
						'pfToken' => $payload->payload['pfToken'],
						'billingContactInfo' => $payload->billing_info,
					),
				);
				unset( $payload->payload['pfToken'] );
				break;
			case 'token':
				$payload->payload['paymentSource'] = array(
					'creditCardInfo' => array(
						'creditCard' => $payload->payload['creditCard'],
						'billingContactInfo' => $payload->billing_info,
					),
				);
				unset( $payload->payload['creditCard'] );
				break;
			default:
				$payload = apply_filters( 'wc_gateway_bluesnap_get_adapted_payload_for_ondemand_wallet_' . $payload->type, $payload );
				$payload = apply_filters( 'wc_gateway_bluesnap_get_adapted_payload_for_ondemand_wallet', $payload );
				break;
		}
		return $payload;
	}

	public function create_ondemand_wallet( $order_id, $force_amount = null, $scheduled = false ) {
		$order = wc_get_order( $order_id );
		try {
			$payload = $this->get_adapted_payload_for_ondemand_wallet( $order );

			$create_payload = array_merge(
				array(
					'amount'   => bluesnap_format_decimal( ! is_null( $force_amount ) ? $force_amount : $order->get_total(), $order->get_currency() ),
					'currency' => $order->get_currency(),
					'merchantTransactionId' => $order_id,
				),
				$payload->payload
			);

			if ( $scheduled ) {
				$create_payload['scheduled'] = (bool) $scheduled;
			}

			$subscription = WC_Bluesnap_API::create_ondemand_subscription( $create_payload );

			if ( isset( $payload->saveable ) && $payload->saveable ) {
				if ( ! $payload->shopper_id ) {
					$payload->shopper_id = $subscription['vaultedShopperId'];
					$payload->shopper->set_bluesnap_shopper_id( $payload->shopper_id );
				}
				WC_Bluesnap_Token::create_wc_token( $subscription['paymentSource']['creditCardInfo']['creditCard'] );
			}

			$this->save_related_addon_order_info( $order, $subscription['subscriptionId'] );

			if ( $subscription['amount'] > 0 ) {
				$this->add_order_success_note( $order, $subscription['transactionId'] );
			}
			$order->payment_complete( $subscription['transactionId'] );
		} catch ( Exception $e ) {
			self::hpf_clean_transaction_token_session();
			WC()->session->set( 'refresh_totals', true ); // this triggers refresh of the checkout area
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			wc_add_notice( $e->getMessage(), 'error' );

			do_action( 'wc_gateway_bluesnap_process_payment_error', $e, $order );
			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}

		// It is a success anyways, since the order at this point is completed.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);

	}

	/**
	 * It process a subscription.
	 * For free trials subscriptions, we need to create or update vaulted shopper with its cc (from ptoken) ourselves.
	 * This Bluesnap limitation happens with prepaid (0$ orders) too.
	 *
	 * @param $order_id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function process_subscription( $order_id ) {
		if ( $this->is_subs_change_payment() ) {
			return $this->change_or_add_payment_method( $order_id );
		}

		$order = wc_get_order( $order_id );

		if ( ! $order->get_meta( self::ORDER_ONDEMAND_WALLET_ID ) ) {
			$subs = wcs_get_subscriptions_for_order( $order, array( 'order_type' => array( 'renewal', 'switch' ) ) );
			if ( ! empty( $subs ) ) {
				$sub_id = reset( array_keys( $subs ) );
				if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $sub_id ) ) {
					$sub = wc_get_order( $sub_id );
					$order->update_meta_data( self::ORDER_ONDEMAND_WALLET_ID, $sub->get_meta( self::ORDER_ONDEMAND_WALLET_ID ) );
					$order->save();
				}
			}
		}

		if ( $order->get_meta( self::ORDER_ONDEMAND_WALLET_ID ) ) {
			return $this->change_or_add_payment_method( $order_id, true );
		} else {
			return $this->create_ondemand_wallet( $order_id, null, true );
		}
	}

	/**
	 * It process a preorder.
	 * If charged upfront, normal flow, otherwise process as preorder.
	 * For used tokens, it AUTH_ONLY with 0 dollars value, for new cards, we save card ourselves (edge case from Bluensap)
	 *
	 * @param $order_id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function process_pre_order( $order_id ) {
		if ( WC_Pre_Orders_Order::order_requires_payment_tokenization( $order_id ) ) {

			$response = $this->create_ondemand_wallet( $order_id, 0 );

			if ( 'success' === $response['result'] ) {
				// Remove from cart
				WC()->cart->empty_cart();
				// Mark order as preordered
				WC_Pre_Orders_Order::mark_order_as_pre_ordered( $order_id );
			}

			return $response;
		}
		// Preorder charged upfront or order used "pay later" gateway
		// and now is a normal order needing payment, normal process.
		return parent::process_payment( $order_id );
	}

	/**
	 * It will add/change payment method to vaulted shopper (updated or created) from the payload.
	 * When a subscription changes its method to be payed.
	 * When a subscription (with free trial) or prepaid (0 value), cc needs to be saved to vaulted shopper.
	 *
	 * @param $order_id
	 *
	 * @return array
	 */
	protected function change_or_add_payment_method( $order_id, $do_payment = false ) {
		$order = wc_get_order( $order_id );
		try {
			$payload     = $this->get_adapted_payload_for_ondemand_wallet( $order );
			unset( $payload->payload['softDescriptor'] );
			$transaction = WC_Bluesnap_API::update_subscription(
				$order->get_meta( self::ORDER_ONDEMAND_WALLET_ID ),
				$payload->payload
			);

			if ( $do_payment ) {
				$this->process_addon_payment( $order );
			}

			if ( isset( $payload->saveable ) && $payload->saveable ) {
				WC_Bluesnap_Token::create_wc_token( $transaction['paymentSource']['creditCardInfo']['creditCard'] );
			}

			self::hpf_clean_transaction_token_session();
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		} catch ( Exception $e ) {
			self::hpf_clean_transaction_token_session();
			WC()->session->set( 'refresh_totals', true ); // this triggers refresh of the checkout area
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			wc_add_notice( $e->getMessage(), 'error' );

			return array(
				'result'   => 'failure',
				'redirect' => $this->get_return_url( $order ),
			);
		}
	}

	/**
	 * Process Renewal subscriptions process. WC called.
	 *
	 * @param $amount_to_charge
	 * @param WC_Order $renewal_order
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		try {
			$this->process_addon_payment( $renewal_order );

			WC_Subscriptions_Manager::process_subscription_payments_on_order( $renewal_order );
			do_action( 'wc_bluesnap_scheduled_subscription_success', $amount_to_charge, $renewal_order );
		} catch ( WC_Bluesnap_API_Exception $e ) {
			WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $renewal_order );
			$renewal_order->add_order_note( __( 'Error processing scheduled_subscription_payment. Reason: ', 'woocommerce-bluesnap-gateway' ) . $e->getMessage() );
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			do_action( 'wc_bluesnap_scheduled_subscription_failure', $amount_to_charge, $renewal_order );
		}
	}

	/**
	 * Hooked when a subscription gets cancelled.
	 *
	 * @param WC_Subscription $subscription
	 */
	public function cancel_subscription( $subscription ) {
		WC_Bluesnap_API::update_subscription(
			$subscription->get_meta( self::ORDER_ONDEMAND_WALLET_ID ),
			array(
				'status' => 'CANCELED',
			)
		);
	}

	/**
	 * Process Preorder when it's time. WC called.
	 *
	 * @param WC_Order $order
	 */
	public function scheduled_pre_order_payment( $order ) {
		try {
			$this->process_addon_payment( $order );
			WC_Bluesnap_API::update_subscription(
				$order->get_meta( self::ORDER_ONDEMAND_WALLET_ID ),
				array(
					'status' => 'CANCELED',
				)
			);

			do_action( 'wc_bluesnap_scheduled_preorder_success', $order );
		} catch ( WC_Bluesnap_API_Exception $e ) {
			$order_note = __( 'Error processing preorder payment. Reason: ', 'woocommerce-bluesnap-gateway' ) . $e->getMessage();
			$order->update_status( 'failed', $order_note );
			WC_Bluesnap_Logger::log( $order_note, 'error' );
			do_action( 'wc_bluesnap_scheduled_preorder_failure', $order );
		}
	}

	/**
	 * Wrapper to process payment for different addons needs.
	 *
	 * @param WC_Order $order
	 * @param $amount_to_charge
	 *
	 * @return array
	 * @throws WC_Bluesnap_API_Exception
	 */
	protected function process_addon_payment( $order ) {
		$transaction = WC_Bluesnap_API::create_ondemand_subscription_charge(
			$order->get_meta( self::ORDER_ONDEMAND_WALLET_ID ),
			array(
				'amount'   => bluesnap_format_decimal( $order->get_total(), $order->get_currency() ),
				'currency' => $order->get_currency(),
				'merchantTransactionId' => $order->get_id(),
			)
		);

		if ( $transaction['amount'] > 0 ) {
			$this->add_order_success_note( $order, $transaction['transactionId'] );
			$order->payment_complete( $transaction['transactionId'] );
		}

		return $transaction;
	}

	/**
	 * Usefull Addon meta information for a given order and its related. We save its corresponding token and vaulted shopper used.
	 * Used on subscriptions and preorders.
	 *
	 * @param int $vaulted_shopper_id
	 * @param int $wc_token_id
	 * @param int $order_id
	 */
	protected function save_related_addon_order_info( $order, $bluesnap_subscription_id ) {
		$order_id = $order->get_id();
		$orders   = array();

		if ( $this->has_subscription( $order_id ) ) {
			if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order_id ) ) {
				$orders = wcs_get_subscriptions_for_order( $order_id );
			} elseif ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order_id ) ) {
				$orders = wcs_get_subscriptions_for_renewal_order( $order_id );
			} elseif ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order_id ) ) {
				$order  = wc_get_order( $order_id );
				$orders = array( $order );
			}
			$orders[] = $order;
		} elseif ( $this->has_pre_order( $order_id ) ) {
			$order  = wc_get_order( $order_id );
			$orders = array( $order );
		}
		foreach ( $orders as $order ) {
			$order->update_meta_data( self::ORDER_ONDEMAND_WALLET_ID, $bluesnap_subscription_id );
			$order->save();
		}
	}

	protected function cart_contains_subscription() {
		if ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) {
			return true;
		}
		if ( function_exists( 'wcs_cart_contains_renewal' ) && wcs_cart_contains_renewal() ) {
			return true;
		}
		if ( function_exists( 'wcs_cart_contains_resubscribe' ) && wcs_cart_contains_resubscribe() ) {
			return true;
		}
		return false;
	}

	/**
	 * Hide save card checkbox, when cart contains subscription products it will save it regardless.
	 *
	 * @param $display_tokenization
	 *
	 * @return bool
	 */
	public function maybe_hide_save_checkbox( $display_tokenization ) {
		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			if ( $this->is_subs_change_payment() ) {
				return true;
			}
		} else {
			if ( $this->cart_contains_subscription() ) {
				return true;
			}
			if ( class_exists( 'WC_Pre_Orders_Cart' ) && WC_Pre_Orders_Cart::cart_contains_pre_order() && WC_Pre_Orders_Product::product_is_charged_upon_release( WC_Pre_Orders_Cart::get_pre_order_product() ) ) {
				return true;
			}
		}
		return $display_tokenization;
	}

	/**
	 * Checks if page is pay for order and change subs payment page.
	 *
	 * @return bool
	 */
	protected function is_subs_change_payment() {
		return ( isset( $_GET['pay_for_order'] ) && isset( $_GET['change_payment_method'] ) ); // WPCS: CSRF ok.
	}

	/**
	 * Is $order_id a subscription?
	 * @param  int  $order_id
	 * @return boolean
	 */
	protected function has_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * @param $default
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function force_save_payment_method( $default, $order_id ) {
		return ( $this->has_subscription( $order_id ) ) ? true : $default;
	}

	/**
	 * @param $order_id
	 *
	 * @return bool
	 */
	protected function has_pre_order( $order_id ) {
		return class_exists( 'WC_Pre_Orders_Order' ) && WC_Pre_Orders_Order::order_contains_pre_order( $order_id );
	}

	public function add_subscription_free_trial( $items, $item, $cart_item ) {
		$cart_item_key = $cart_item['key'];
		$_product      = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( ! WC_Subscriptions_Product::is_subscription( $cart_item['data'] ) ) {
			return $items;
		}

		$recurring_cart_key  = WC_Subscriptions_Cart::get_recurring_cart_key( $cart_item );
		$cart_item_recurring = WC()->cart->recurring_carts[ $recurring_cart_key ]->get_cart_item( $cart_item_key );

		$amount         = $cart_item_recurring['line_subtotal'];
		$quantity_label = 1 < $cart_item_recurring['quantity'] ? ' (x' . $cart_item_recurring['quantity'] . ')' : '';

		$product_name = version_compare( WC_VERSION, '3.0', '<' ) ? $cart_item_recurring['data']->post->post_title : $cart_item_recurring['data']->get_name();

		$items = array(
			array(
				'label'  => $product_name . $quantity_label,
				'amount' => bluesnap_format_decimal( $amount ),
			),
		);

		$trial_length = WC_Subscriptions_Product::get_trial_length( $_product );
		$trial_period = WC_Subscriptions_Product::get_trial_period( $_product );

		if ( 0 != $trial_length ) {
			$trial_string = wcs_get_subscription_trial_period_strings( $trial_length, $trial_period );
			// translators: 1$: trial length (e.g.: "with 4 months free trial")
			$subscription_string = sprintf( __( '- with %1$s free trial', 'woocommerce-bluesnap-gateway' ), $trial_string );

			$items[] = array(
				'label'  => $subscription_string,
				'amount' => bluesnap_format_decimal( - $amount ),
			);
		}

		$sign_up_fee = (float) WC_Subscriptions_Product::get_sign_up_fee( $_product );

		if ( 0 != $sign_up_fee ) {
			$items[] = array(
				'label'  => $sign_up_fee > 0 ? __( '- Sign Up Fee', 'woocommerce-bluesnap-gateway' ) : __( '- Sign Up Discount', 'woocommerce-bluesnap-gateway' ),
				'amount' => bluesnap_format_decimal( $sign_up_fee ),
			);
		}

		return $items;
	}

	public function add_pre_order_line( $items, $order_total ) {

		if ( ! ( class_exists( 'WC_Pre_Orders_Order' ) && WC_Pre_Orders_Cart::cart_contains_pre_order() && WC_Pre_Orders_Product::product_is_charged_upon_release( WC_Pre_Orders_Cart::get_pre_order_product() ) ) ) {
			return $items;
		}

		$_product = WC_Pre_Orders_Cart::get_pre_order_product();

		$availaibility = WC_Pre_Orders_Product::get_localized_availability_date( WC_Pre_Orders_Cart::get_pre_order_product() );

		$items[] = array(
			// translators: 1$: date string (e.g.: "to be paid November 22")
			'label'  => sprintf( __( '- to be paid %1$s', 'woocommerce-bluesnap-gateway' ), $availaibility ),
			'amount' => bluesnap_format_decimal( - (float) $order_total ),
		);

		return $items;
	}

	public function remove_pre_order_from_total( $order_total ) {
		if ( class_exists( 'WC_Pre_Orders_Order' ) && WC_Pre_Orders_Cart::cart_contains_pre_order() && WC_Pre_Orders_Product::product_is_charged_upon_release( WC_Pre_Orders_Cart::get_pre_order_product() ) ) {
			return 0;
		}
		return $order_total;
	}

	public function bump_apple_pay_version( $version ) {
		if ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) {
			$total = WC()->cart->get_total( false );
			if ( 0 === (int) $total ) { // total to be paid now is 0
				return 4;
			}
		}
		if ( class_exists( 'WC_Pre_Orders_Order' ) && WC_Pre_Orders_Cart::cart_contains_pre_order() && WC_Pre_Orders_Product::product_is_charged_upon_release( WC_Pre_Orders_Cart::get_pre_order_product() ) ) {
			return 4;
		}
		return $version;
	}

	public function add_recurring_totals( $items, $order_total ) {
		if ( ! ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) ) {
			return $items;
		}
		if ( empty( WC()->cart->recurring_carts ) ) {
			return $items;
		}

		$items[] = array(
			// translators: 1$: date string (e.g.: "to be paid November 22")
			'label'  => __( 'Recurring totals', 'woocommerce-bluesnap-gateway' ),
			'amount' => bluesnap_format_decimal( 0 ),
		);

		foreach ( WC()->cart->recurring_carts as $key => $cart ) {
			if ( 0 === $cart->next_payment_date ) {
				continue;
			}

			$first_renewal_date = date_i18n( wc_date_format(), wcs_date_to_time( get_date_from_gmt( $cart->next_payment_date ) ) );
			// translators: placeholder is a date
			$order_total_html = sprintf( __( 'First renewal: %s', 'woocommerce-bluesnap-gateway' ), $first_renewal_date );

			$string = trim( wcs_cart_price_string( '', $cart ) );
			if ( '/' == substr( $string, 0, 1 ) ) {
				$string = __( 'Every', 'woocommerce-bluesnap-gateway' ) . substr( $string, 1 );
			} else {
				$string = ucfirst( $string );
			}
			$string = esc_html( $string );

			$order_total_html = $string . ' - ' . $order_total_html;

			$items[] = array(
				// translators: 1$: date string (e.g.: "to be paid November 22")
				'label'  => '- ' . $order_total_html,
				'amount' => bluesnap_format_decimal( $cart->get_total( 'db' ) ),
			);
		}

		return $items;
	}
}
