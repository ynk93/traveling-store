<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles Bluesnap payment method.
 *
 * @extends WC_Bluesnap_Gateway
 *
 * @since 1.0.0
 */
class WC_Bluesnap_Gateway extends WC_Abstract_Bluesnap_Payment_Gateway {

	const TOKEN_DURATION   = 45 * MINUTE_IN_SECONDS;
	const AUTH_AND_CAPTURE = 'AUTH_CAPTURE';
	const AUTH_ONLY        = 'AUTH_ONLY';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id           = WC_BLUESNAP_GATEWAY_ID;
		$this->method_title = __( 'BlueSnap', 'woocommerce-bluesnap-gateway' );

		$this->method_description = __( 'BlueSnap Payment Gateway', 'woocommerce-bluesnap-gateway' );
		$this->supports           = array(
			'products',
			'refunds',
			'tokenization',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change_admin',
			'pre-orders',
		);

		$this->fraud_id = $this->get_fraud_id();
		if ( empty( $this->fraud_id ) ) {
			$this->fraud_id = $this->set_fraud_id();
		}

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Needed for direct payment gateway
		$this->has_fields = true;
		$this->rendered_fraud_iframe = false;

		$this->title           = $this->get_option( 'title' );
		$this->description     = $this->get_option( 'description' );
		$this->enabled         = $this->get_option( 'enabled' );
		$this->soft_descriptor = $this->get_option( 'soft_descriptor' );
		$this->testmode        = ( ! empty( $this->get_option( 'testmode' ) && 'yes' === $this->get_option( 'testmode' ) ) ) ? true : false;
		$this->api_username    = ! empty( $this->get_option( 'api_username' ) ) ? $this->get_option( 'api_username' ) : '';
		$this->api_password    = ! empty( $this->get_option( 'api_password' ) ) ? $this->get_option( 'api_password' ) : '';
		$this->logging         = ( ! empty( $this->get_option( 'logging' ) ) && 'yes' === $this->get_option( 'logging' ) ) ? true : false;
		$this->_3D_secure      = ( ! empty( $this->get_option( '_3D_secure' ) && 'yes' === $this->get_option( '_3D_secure' ) ) ) ? true : false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$this->saved_cards     = ( ! empty( $this->get_option( 'saved_cards' ) && 'yes' === $this->get_option( 'saved_cards' ) ) ) ? true : false;

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ), 1 );
		add_filter( 'woocommerce_bluesnap_gateway_enqueue_scripts', array( $this, 'enqueue_payment_frontend_script' ) );
		add_filter( 'woocommerce_bluesnap_gateway_general_params', array( $this, 'localize_payment_frontend_script' ) );
		add_filter( 'woocommerce_save_settings_checkout_' . $this->id, array( $this, 'filter_before_save' ) );
		add_action( 'wc_gateway_bluesnap_new_card_payment_success', array( $this, 'save_payment_method_to_account' ), 10, 3 );

		if ( is_checkout() || is_add_payment_method_page() || is_checkout_pay_page() ) {
			$this->tokenization_script();
		}

		WC_Bluesnap_Token::get_instance( $this->id );
	}

	/**
	 * Initialize Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = require WC_Bluesnap()->plugin_path() . '/includes/admin/bluesnap-settings.php';
	}

	/**
	 * Enqueue Hosted Payment fields JS library only when needed.
	 */
	public function payment_scripts() {

		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_add_payment_method_page() && ! is_checkout_pay_page() ) {
			return;
		}

		if ( 'no' == $this->enabled ) {
			return;
		}

		wp_enqueue_script( 'hpf_bluesnap', WC_Bluesnap_API::get_hosted_payment_js_url(), false, WC_Bluesnap_API::get_hosted_payment_js_version(), true );
	}

	/**
	 * If gateway is not active, don't enqueue frontend script.
	 * @param $arg
	 *
	 * @return array
	 */
	public function enqueue_payment_frontend_script( $arg ) {
		if ( 'no' == $this->enabled ) {
			return array();
		}
		return $arg;
	}

	/**
	 * Filter to localize data on WC frontend JS.
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function localize_payment_frontend_script( $data ) {
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_add_payment_method_page() && ! is_checkout_pay_page() ) {
			return $data;
		}

		if ( 'no' == $this->enabled ) {
			return $data;
		}

		$data['token']              = self::get_hosted_payment_field_token();
		$data['generic_card_url']   = WC_Bluesnap()->images_url( 'generic-card.png' );
		$data['errors']             = $this->return_js_error_codes();
		$data['wc_ajax_url']        = WC_AJAX::get_endpoint( '%%endpoint%%' );
		$data['domain']             = WC_Bluesnap_API::get_domain();
		$data['_3d_secure']         = ( is_add_payment_method_page() ) ? 0 : (int) $this->_3D_secure; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$data['total_amount']       = bluesnap_format_decimal( WC()->cart->get_total( false ) );
		$data['currency']           = get_woocommerce_currency();
		$data['billing_first_name'] = WC()->customer->get_billing_first_name( 'db' );
		$data['billing_last_name']  = WC()->customer->get_billing_last_name( 'db' );
		$data['billing_country']    = WC()->customer->get_billing_country( 'db' );
		$data['billing_state']      = WC()->customer->get_billing_state( 'db' );
		$data['billing_city']       = WC()->customer->get_billing_city( 'db' );
		$data['billing_address_1']  = WC()->customer->get_billing_address_1( 'db' );
		$data['billing_address_2']  = WC()->customer->get_billing_address_2( 'db' );
		$data['billing_postcode']   = WC()->customer->get_billing_postcode( 'db' );
		$data['billing_email']      = WC()->customer->get_billing_email( 'db' );
		$data['is_sandbox']         = (int) WC_Bluesnap_API::is_sandbox();

		return $data;
	}

	/**
	 * Checks if 3D is active on bluesnap end, if so, allows 3D in our integration, otherwise deactivate.
	 *
	 * @param $is_post
	 *
	 * @return bool
	 */
	public function filter_before_save( $is_post ) {
		if ( $is_post ) {
			if ( ! empty( $_POST['woocommerce_bluesnap_soft_descriptor'] ) ) { // WPCS: CSRF ok.
				$re = '/[^a-z0-9\ \&\,\.\-\#]/mi';
				$_POST['woocommerce_bluesnap_soft_descriptor'] = preg_replace( $re, '', $_POST['woocommerce_bluesnap_soft_descriptor'] ); // WPCS: CSRF ok.
				if ( strlen( $_POST['woocommerce_bluesnap_soft_descriptor'] ) > 20 ) { // WPCS: CSRF ok.
					$_POST['woocommerce_bluesnap_soft_descriptor'] = substr( $_POST['woocommerce_bluesnap_soft_descriptor'], 0, 20 ); // WPCS: CSRF ok.
					WC_Admin_Settings::add_error( __( 'The Soft Descriptor setting was trimmed to 20 characters.', 'woocommerce-bluesnap-gateway' ) );
				}
			} else {
				unset( $_POST['woocommerce_bluesnap_enabled'] );
				WC_Admin_Settings::add_error( __( 'Soft descriptor is required. Disabling the gateway.', 'woocommerce-bluesnap-gateway' ) );
			}
		}
		return $is_post;
	}

	/**
	 * Get gateway icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		//return apply_filters( 'woocommerce_gateway_icon', 'BlueSnap', $this->id );
	}

	/**
	 * Validate required Checkout fields: Card Holder Name and Surname (Space-separated)
	 * BlueSnap confirmation from credential Submission.
	 * If payment is done through payment token, just validate if token index exists.
	 *
	 * @return bool
	 */
	public function validate_fields() {

		if ( $this->supports( 'tokenization' ) && $this->saved_cards && $this->get_id_saved_payment_token_selected() ) {
			return true;
		}

		$errors = new WP_Error();

		$card_info = ! empty( $_POST['bluesnap_card_info'] ) ? json_decode( stripslashes_deep( $_POST['bluesnap_card_info'] ), true ) : array(); // WPCS: CSRF ok.

		if (
			empty( $card_info ) ||
			empty( $card_info['ccType'] ) ||
			empty( $card_info['last4Digits'] ) ||
			empty( $card_info['issuingCountry'] ) ||
			empty(
				$card_info['exp']
			) ) {
			$errors->add( 'card_info_invalid', __( 'Card information is invalid.', 'woocommerce-bluesnap-gateway' ) );

		}

		$errors = apply_filters( 'wc_gateway_bluesnap_validate_fields', $errors );

		$errors_messages = $errors->get_error_messages();
		if ( ! empty( $errors_messages ) ) {
			foreach ( $errors_messages as $message ) {
				wc_add_notice( $message, 'error' );
			}
			return false;
		}

		return true;
	}

	public function add_order_success_note( $order, $txid ) {
		/* translators: transaction id */
		$message = sprintf( __( 'Bluesnap transaction complete (Transaction ID: %s)', 'woocommerce-bluesnap-gateway' ), $txid );
		$order->add_order_note( $message );
	}

	protected function get_payer_info_from_source( $customer_source, $type = 'cardHolderInfo' ) {
		$address1key = 'cardHolderInfo' == $type ? 'address' : 'address1';
		$data = array(
			'firstName'   => $customer_source->get_billing_first_name( 'db' ),
			'lastName'    => $customer_source->get_billing_last_name( 'db' ),
			'email'       => $customer_source->get_billing_email( 'db' ),
			'country'     => $customer_source->get_billing_country( 'db' ),
			'state'       => '',
			$address1key  => $customer_source->get_billing_address_1( 'db' ),
			'address2'    => $customer_source->get_billing_address_2( 'db' ),
			'city'        => $customer_source->get_billing_city( 'db' ),
			'zip'         => $customer_source->get_billing_postcode( 'db' ),
		);
		if ( in_array( $data['country'], array( 'US', 'CA' ) ) ) {
			$data['state'] = $customer_source->get_billing_state( 'db' );
		}
		return $data;
	}

	protected function fetch_transaction_new_card_payload( $customer_source, $ret = null ) {
		if ( is_null( $ret ) ) {
			$ret = $this->get_base_payload_object( $customer_source );
		} else {
			$ret = (object) $ret;
		}
		$ret->type       = 'new_card';
		$ret->pf_token   = self::get_hosted_payment_field_token( true );
		$ret->card_info  = json_decode( stripslashes_deep( $_POST['bluesnap_card_info'] ), true ); // WPCS: CSRF ok.
		$ret->payload    = array_merge(
			$ret->payload,
			array(
				'cardHolderInfo' => $ret->payer_info,
				'pfToken'        => $ret->pf_token,
				'storeCard'      => false,
			)
		);
		$ret->saveable   = true; // can be saved as new payment method
		return $ret;
	}

	private function get_base_payload_object( $customer_source ) {
		$user_id = 0;
		if ( is_a( $customer_source, 'WC_Customer' ) ) {
			$user_id = $customer_source->get_id();
		} elseif ( is_a( $customer_source, 'WC_Order' ) ) {
			$user_id = $customer_source->get_customer_id( 'db' );
		}

		$vaulted_shopper = new WC_Bluesnap_Shopper( $user_id );

		$ret          = (object) array(
			'type'       => 'unknown',
			'payload'    => array(),
			'shopper_id' => $vaulted_shopper->get_bluesnap_shopper_id(),
			'shopper'    => $vaulted_shopper,
			'saveable'   => false,
		);
		$ret->payer_info = $this->get_payer_info_from_source( $customer_source );
		$ret->billing_info = $this->get_payer_info_from_source( $customer_source, 'billingContactInfo' );
		$ret->payload = array(
			'vaultedShopperId' => $ret->shopper_id,
		);

		if ( $this->get_fraud_id() ) {
			$ret->payload['transactionFraudInfo'] = array(
				'fraudSessionId' => $this->get_fraud_id( true ),
				'shopperIpAddress' => WC_Geolocation::get_ip_address(),
			);
		}

		if ( ! empty( $this->soft_descriptor ) ) {
			$ret->payload['softDescriptor'] = $this->soft_descriptor;
		}
		return $ret;
	}

	protected function fetch_transaction_payment_method_payload( $order ) {
		$ret = $this->get_base_payload_object( $order );

		$ret = apply_filters( 'wc_gateway_bluesnap_transaction_payment_method_payload', $ret, $order );
		if ( 'unknown' === $ret->type ) {
			// If payment saved token
			$payment_method_token = $this->get_id_saved_payment_token_selected();
			if ( $payment_method_token ) {
				$token = WC_Bluesnap_Token::set_wc_token( $payment_method_token );

				$ret->type      = 'token';
				$ret->card_info = array(
					'last4Digits' => $token->get_last4(),
					'ccType'      => $token->get_card_type(),
					'exp'         => $token->get_exp(),
				);
				$ret->payload   = array_merge(
					$ret->payload,
					array(
						'creditCard' => array(
							'cardLastFourDigits' => $token->get_last4(),
							'cardType'           => $token->get_card_type(),
						),
					)
				);
				$ret->token     = $payment_method_token;
			} else {
				$ret = $this->fetch_transaction_new_card_payload( $order, $ret );
			}
		}

		return $ret;
	}

	protected function save_payment_method_selected() {
		return isset( $_POST[ 'wc-' . $this->id . '-new-payment-method' ] ) && ( $_POST[ 'wc-' . $this->id . '-new-payment-method' ] ); // WPCS: CSRF ok.
	}

	protected function should_force_save_payment_method( $order ) {
		return apply_filters( 'wc_' . $this->id . '_save_payment_method', false, $order->get_id() );
	}

	public function save_payment_method_to_account( $order, $payment_method_data, $transaction ) {
		$card_not_saved_text = __( 'We could not save your credit card, try next time.', 'woocommerce-bluesnap-gateway' );
		try {
			// If customer wants to save its card or it is a subscription.
			if ( $this->is_save_card_available() ) {
				if ( $this->save_payment_method_selected() || $this->should_force_save_payment_method( $order ) ) {
					$saved_wc_token = $this->save_payment_method( $transaction );
					if ( ! $saved_wc_token ) {
						wc_add_notice( $card_not_saved_text, 'error' );
						return;
					}

					do_action( 'wc_gateway_bluesnap_save_payment_method_success', $transaction['vaultedShopperId'], $saved_wc_token, $order->get_id() );
				}
			}
		} catch ( WC_Bluesnap_API_Exception $e ) {
			throw new Exception( $card_not_saved_text . ' ' . $e->getMessage() );
		}
	}

	/**
	 * Process Payment.
	 * First of all and most important is to process the payment.
	 * Second if needed, save payment token card.
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

		try {
			$order  = wc_get_order( $order_id );
			$amount = ( isset( $override_total ) && is_numeric( $override_total ) ) ? (float) $override_total : $order->get_total();

			$payload = array(
				'amount'                => bluesnap_format_decimal( $amount, $order->get_currency() ),
				'currency'              => $order->get_currency(),
				'merchantTransactionId' => $order_id,
				'cardTransactionType'   => $transaction_type,
			);

			$attempt_payments = apply_filters( 'wc_gateway_bluesnap_alternate_payment', true, $order, $payload, $this );

			if ( $attempt_payments && ! $order->get_date_paid( 'edit' ) ) {
				$payment_method_data = $this->fetch_transaction_payment_method_payload( $order );
				if ( is_null( $payment_method_data ) || 'unknown' === $payment_method_data->type ) {
					throw new Exception( __( 'Something went wrong with your payment method selected.', 'woocommerce-bluesnap-gateway' ) );
				}

				$payload     = array_merge( $payload, $payment_method_data->payload );
				if ( $payment_method_data->saveable && $this->is_save_card_available() ) {
					if ( $this->save_payment_method_selected() || $this->should_force_save_payment_method( $order ) ) {
						$payload['storeCard'] = true;
					}
				}
				$transaction = WC_Bluesnap_API::create_transaction( $payload );

				// Set bluesnap shopper id as soon as we have it available
				$shopper = new WC_Bluesnap_Shopper();
				if ( ! $shopper->get_bluesnap_shopper_id() ) {
					$shopper->set_bluesnap_shopper_id( $transaction['vaultedShopperId'] );
				}

				do_action( 'wc_gateway_bluesnap_' . $payment_method_data->type . '_payment_success', $order, $payment_method_data, $transaction );

				if ( $payment_complete ) {
					$this->add_order_success_note( $order, $transaction['transactionId'] );
					$order->payment_complete( $transaction['transactionId'] );
				}
			}

			self::hpf_clean_transaction_token_session();
			do_action( 'wc_gateway_bluesnap_process_payment_success', $order );

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
	 * @param $transaction
	 *
	 * @return bool
	 * @throws WC_Bluesnap_API_Exception
	 */
	public function save_payment_method( $transaction ) {
		$shopper            = new WC_Bluesnap_Shopper();
		$vaulted_shopper_id = $shopper->get_bluesnap_shopper_id();
		$payment_method_token = WC_Bluesnap_Token::set_payment_token_from_transaction( $transaction, $vaulted_shopper_id );
		return $payment_method_token;
	}

	/**
	 * Refund process.
	 *
	 * @param int $order_id
	 * @param null $amount
	 * @param string $reason
	 *
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() ) {
			return false;
		}

		try {
			$reason = REFUND_REASON_PREFIX . $reason;
			if ( bluesnap_format_decimal( $order->get_total( 'db' ) ) == bluesnap_format_decimal( $amount, $order->get_currency() ) ) {
				$amount = null;
			}
			do_action( 'wc_gateway_bluesnap_before_refund', $order, $order->get_transaction_id() );
			WC_Bluesnap_API::create_refund( $order->get_transaction_id(), $reason, $amount, $order->get_currency() );
			return true;
		} catch ( WC_Bluesnap_API_Exception $e ) {

			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			do_action( 'wc_gateway_bluesnap_process_refund_error', $e, $order );
			return new WP_Error( 'refund_error', __( 'An error occurred during the refund request. Review the WooCommerce logs for specific details about the error. If the logs don\'t resolve the issue, contact BlueSnap Merchant Support for assistance.', 'woocommerce-bluesnap-gateway' ) );
		}
	}

	private function adapt_vaulted_shopper_payload( $card_data ) {
		$payload = $card_data->payload;
		$payload = array_merge(
			$payload,
			array(
				'paymentSources' => array(
					'creditCardInfo' => array(
						array(
							'pfToken' => $payload['pfToken'],
							'billingContactInfo' => $card_data->billing_info,
						),
					),
				),
			),
			$payload['cardHolderInfo']
		);

		unset( $payload['vaultedShopperId'] );
		unset( $payload['pfToken'] );
		unset( $payload['cardHolderInfo'] );

		return $payload;
	}

	public function handle_add_posted_shopper_method( $card_data ) {
		$vaulted_shopper_id = $card_data->shopper_id;
		if ( $vaulted_shopper_id ) {
			// update
			$vaulted_shopper = WC_Bluesnap_API::update_vaulted_shopper(
				$vaulted_shopper_id,
				$this->adapt_vaulted_shopper_payload( $card_data )
			);
		} else {
			//new vaulted
			$vaulted_shopper = WC_Bluesnap_API::create_vaulted_shopper(
				$this->adapt_vaulted_shopper_payload( $card_data )
			);
			$card_data->shopper->set_bluesnap_shopper_id( $vaulted_shopper['vaultedShopperId'] );
		}

		return WC_Bluesnap_Token::set_payment_token_from_vaulted_shopper(
			$vaulted_shopper,
			$card_data->card_info['last4Digits'],
			$card_data->card_info['ccType']
		);
	}

	/**
	 * Add Payment Method hook on My account.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function add_payment_method() {
		if ( ! wp_verify_nonce( $_POST['woocommerce-add-payment-method-nonce'], 'woocommerce-add-payment-method' ) ) {
			return array(
				'result'   => 'failure',
				'redirect' => wc_get_endpoint_url( 'payment-methods' ),
			);
		}

		$card_data = $this->fetch_transaction_new_card_payload( WC()->customer );

		if ( ! WC_Bluesnap_Token::is_cc_type_supported( $card_data->card_info['ccType'] ) ) {
			wc_add_notice( __( 'Credit Card type not supported on Add Payment Methods.', 'woocommerce-bluesnap-gateway' ), 'error' );
			return array(
				'result'   => 'failure',
				'redirect' => wc_get_endpoint_url( 'payment-methods' ),
			);
		}

		try {
			$this->handle_add_posted_shopper_method( $card_data );
			self::hpf_clean_transaction_token_session();
			return array(
				'result'   => 'success',
				'redirect' => wc_get_endpoint_url( 'payment-methods' ),
			);

		} catch ( WC_Bluesnap_API_Exception $e ) {
			self::hpf_clean_transaction_token_session();
			WC()->session->set( 'refresh_totals', true ); // this triggers refresh of the checkout area
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			wc_add_notice( __( 'Authorization has failed for this transaction. Please try again or contact your bank for assistance', 'woocommerce-bluesnap-gateway' ), 'error' );
			do_action( 'wc_gateway_bluesnap_add_payment_method_error', $e );
			return array(
				'result'   => 'failure',
				'redirect' => wc_get_endpoint_url( 'payment-methods' ),
			);
		}
	}

	public function render_fraud_kount_iframe() {
		if ( $this->rendered_fraud_iframe ) {
			return;
		}
		$this->rendered_fraud_iframe = true;
		woocommerce_bluesnap_gateway_get_template(
			'fraud-kount-iframe.php',
			array(
				'domain'   => WC_Bluesnap_API::get_domain(),
				'fraud_id' => $this->get_fraud_id(),
				'developer_id' => $this->get_option( 'merchant_id' ),
			)
		);
	}

	/**
	 * Payment Form on checkout page
	 */
	public function payment_fields() {
		$this->render_fraud_kount_iframe();

		if ( ! empty( $this->description ) ) {
			echo apply_filters( 'wc_bluesnap_description', wpautop( wp_kses_post( $this->description ) ), $this->id ); // WPCS: XSS ok, sanitization ok.
		}

		$display_tokenization = $this->is_save_card_available();

		if ( $display_tokenization ) {
			$this->saved_payment_methods();
		}

		woocommerce_bluesnap_gateway_get_template( 'payment-fields-bluesnap.php', array( 'gateway' => $this ) );

		if ( ! apply_filters( 'wc_' . $this->id . '_hide_save_payment_method_checkbox', ! $display_tokenization ) && ! is_add_payment_method_page() ) {
			$this->save_payment_method_checkbox();
		}
	}

	/**
	 * Gets Hosted Payment Field Token either from Session (cached) or from BlueSnap API.
	 * If Token is expired, refresh it.
	 *
	 * @return null|string
	 */
	public static function get_hosted_payment_field_token( $clear = false ) {
		$expiration = WC()->session->get( 'hpf_token_expiration' );

		if ( time() < $expiration ) {
			$token = WC()->session->get( 'hpf_token' );
			if ( $clear ) {
				self::hpf_clean_transaction_token_session();
			}
			return $token;
		}

		$token = null;
		try {
			$token = WC_Bluesnap_API::request_hosted_field_token();
			WC()->session->set( 'hpf_token', $token );
			WC()->session->set( 'hpf_token_expiration', time() + self::TOKEN_DURATION );
		} catch ( WC_Bluesnap_API_Exception $e ) {
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			wc_add_notice( __( 'Error creating BlueSnap token', 'woocommerce-bluesnap-gateway' ), 'error' );
		}
		return $token;
	}

	/**
	 * Set Token expiration in the past to force creation of new token if needed.
	 */
	public static function hpf_clean_transaction_token_session() {
		WC()->session->set( 'hpf_token_expiration', time() - self::TOKEN_DURATION );
	}

	/**
	 * Linking transaction id order to BlueSnap.
	 *
	 * @param WC_Order $order
	 *
	 * @return string
	 */
	public function get_transaction_url( $order ) {
		if ( WC_Bluesnap_API::is_sandbox() ) {
			$this->view_transaction_url = 'https://sandbox.bluesnap.com/jsp/order_locator_info.jsp?invoiceId=%s';
		} else {
			$this->view_transaction_url = 'https://bluesnap.com/jsp/order_locator_info.jsp?invoiceId=%s';
		}
		return parent::get_transaction_url( $order );
	}

	/**
	 * Error codes returned by JS library.
	 * @return array
	 */
	protected function return_js_error_codes() {
		return WC_Bluesnap_Errors::get_hpf_errors();
	}

	/**
	 * @return bool
	 */
	public function is_save_card_available() {
		return $this->supports( 'tokenization' )
			   && is_checkout()
			   && $this->saved_cards;
	}

	/**
	 * If payment is coming from a saved payment token, it will give the index.
	 * @return int|false
	 */
	protected function get_id_saved_payment_token_selected() {
		return ( isset( $_POST['wc-bluesnap-payment-token'] ) ) ? filter_var( $_POST['wc-bluesnap-payment-token'], FILTER_SANITIZE_NUMBER_INT ) : false; // WPCS: CSRF ok.
	}

	public function get_fraud_id( $clean = false ) {
		if ( ! WC()->session ) {
			return;
		}

		$fraud_id = WC()->session->bluesnap_fraud_id;
		if ( $clean ) {
			unset( WC()->session->bluesnap_fraud_id );
		}
		return $fraud_id;
	}

	public function set_fraud_id() {
		if ( ! WC()->session ) {
			return;
		}

		$fraud_id = md5( uniqid( '', true ) );
		WC()->session->bluesnap_fraud_id = $fraud_id;
		return $fraud_id;
	}

	public function can_refund_order( $order ) {
		$can = parent::can_refund_order( $order );
		if ( ! $can ) {
			return $can;
		}

		if ( 0 >= $order->get_total() - $order->get_total_refunded() ) {
			return false;
		}

		return $can;
	}

}
