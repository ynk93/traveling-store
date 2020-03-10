<?php
/**
 * @author   Your Name or Your Company
 * @category Class
 * @package  Woocommerce_Bluesnap_Gateway/Classes
 * @version  1.3.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WC_Bluesnap_Apple_Pay
 */
class WC_Bluesnap_Apple_Pay {

	/**
	 * @var bool
	 */
	public $apple_pay;

	/**
	 * Cookie name.
	 */
	const APPLE_PAY_METHOD_TITLE = 'Apple Pay (BlueSnap)';

	/**
	 * Apple Simulator Tx Identifier.
	 */
	const APPLE_PAY_SIMULATOR_TX_IDENTIFIER = 'Simulated Identifier';

	/**
	 * Hook in ajax handlers.
	 */
	public function __construct() {
		$option = WC_Bluesnap()->get_option( 'apple_pay' );

		$this->apple_pay = ( ! empty( $option ) && 'yes' === $option ) ? true : false;

		if ( ! $this->apple_pay ) {
			return;
		}

		add_filter( 'woocommerce_bluesnap_gateway_enqueue_scripts', array( $this, 'add_apple_pay_js' ), 10, 2 );

		$this->add_ajax_events();

		// Force session to be set
		add_action( 'template_redirect', array( $this, 'set_session' ) );

		// Add Apple Pay Button to Cart
		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'display_payment_request_button_html' ), 1 );
		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'display_payment_request_button_separator_html' ), 2 );

		// Add Apple Pay Button to Checkout
		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'display_payment_request_button_html' ), 1 );
		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'display_payment_request_button_separator_html' ), 2 );

		add_filter( 'woocommerce_gateway_title', array( $this, 'filter_gateway_title' ), 10, 2 );
		add_filter( 'woocommerce_validate_postcode', array( $this, 'postal_code_validation' ), 10, 3 );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'add_order_meta' ), 10 );
		add_action( 'woocommerce_checkout_create_subscription', array( $this, 'add_order_meta' ), 10 );

		add_filter( 'wc_gateway_bluesnap_alternate_payment', array( $this, 'try_alternate_payment' ), 10, 4 );
		add_filter( 'wc_gateway_bluesnap_validate_fields', array( $this, 'validate_alternate_payment' ) );
		add_filter( 'wc_gateway_bluesnap_transaction_payment_method_payload', array( $this, 'payment_request_payment_payload' ), 10, 2 );
		add_filter( 'wc_gateway_bluesnap_get_adapted_payload_for_ondemand_wallet', array( $this, 'adapted_payload_for_ondemand_wallet' ) );
		add_filter( 'wc_gateway_bluesnap_payment_request_cart_compatible_apple_pay', array( $this, 'check_cart_compat' ) );

		add_action( 'woocommerce_login_form_end', array( $this, 'maybe_add_redirect_field' ) );
		add_action( 'woocommerce_register_form_end', array( $this, 'maybe_add_redirect_field' ) );
	}

	public function process_options() {
		if ( ! $this->apple_pay && isset( $_POST['woocommerce_bluesnap_apple_pay'] ) ) { // WPCS: CSRF ok.
			if ( ! is_ssl() ) {
				WC_Admin_Settings::add_error(
					__( 'All pages that include Apple Pay must be served over HTTPS. Your domain must have a valid SSL certificate.', 'woocommerce-bluesnap-gateway' )
				);
				unset( $_POST['woocommerce_bluesnap_apple_pay'] );
			} else {
				WC_Admin_Notices::add_custom_notice(
					'apple_pay_config',
					__( 'Apple Pay activation: You\'ll need to register all web domains that will display the Apple Pay button. Learn how: ', 'woocommerce-bluesnap-gateway' ) .
					__( '<a href="https://developers.bluesnap.com/docs/apple-pay#section-domain-verification" target="blank">here</a>', 'woocommerce-bluesnap-gateway' )
				);
			}
		}
	}

	/**
	 * Enqueue apple pay js only if it is active.
	 * @param $args
	 *
	 * @return array
	 */
	public function add_apple_pay_js( $args, $handler ) {
		$args['woocommerce-bluesnap-apple-pay'] = array(
			'src'  => $handler->localize_asset( 'js/frontend/woocommerce-bluesnap-apple-pay.js' ),
			'data' => array(
				'ajax_url'                  => WC_Bluesnap()->ajax_url(),
				'wc_ajax_url'               => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'merchant_id'               => WC_Bluesnap()->get_option( 'merchant_id' ),
				'version_required'          => $this->apple_pay_version_required(),
				'cart_compatible_apple_pay' => (int) apply_filters( 'wc_gateway_bluesnap_payment_request_cart_compatible_apple_pay', true ),
				'nonces'                     => array(
					'checkout'               => wp_create_nonce( 'woocommerce-process_checkout' ),
					'create_apple_wallet'    => wp_create_nonce( 'wc-gateway-bluesnap-ajax-create_apple_wallet' ),
					'get_shipping_options'   => wp_create_nonce( 'wc-gateway-bluesnap-ajax-get_shipping_options' ),
					'update_shipping_method' => wp_create_nonce( 'wc-gateway-bluesnap-ajax-update_shipping_method' ),
					'get_payment_request'    => wp_create_nonce( 'wc-gateway-bluesnap-ajax-get_payment_request' ),
				),
				'i18n'                      => array(
					'checkout_error' => esc_attr__( 'Error processing Apple Pay. Please try again.', 'woocommerce-bluesnap-gateway' ),
					'apple_pay'      => array(
						'not_compatible_with_cart'    => esc_attr__( 'Apple Pay is not available for you due to the contents of your cart being incompatible with it. Please attempt the standard checkout options.', 'woocommerce-bluesnap-gateway' ),
						'device_not_compat_with_cart' => esc_attr__( 'Apple Pay is not available for you due to the contents of your cart being incompatible with your OS version. Upgrade if possible, or attempt the standard checkout options.', 'woocommerce-bluesnap-gateway' ),
						'not_able_to_make_payments'   => esc_attr__( 'Apple Pay is not available for you because you don\'t have any payment methods set up. Please set it up, or attempt the standard checkout options.', 'woocommerce-bluesnap-gateway' ),
					),
				),
			),
		);
		return $args;
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public function add_ajax_events() {
		$ajax_events = array(
			'create_apple_wallet'    => true,
			'create_apple_payment'   => true,
			'get_payment_request'    => true,
			'get_shipping_options'   => true,
			'update_shipping_method' => true,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wc_ajax_bluesnap_' . $ajax_event, array( $this, 'ajax_' . $ajax_event ) );
		}
	}

	public function apple_pay_version_required() {
		WC()->payment_gateways();
		return apply_filters( 'wc_gateway_bluesnap_payment_request_apple_pay_version_required', 2 );
	}

	public function check_cart_compat( $ret ) {
		if ( ! $this->allowed_items_in_cart() ) {
			return false;
		}

		return $ret;
	}

	/**
	 * Apple Wallet creation.
	 */
	public function ajax_create_apple_wallet() {
		check_ajax_referer( 'wc-gateway-bluesnap-ajax-create_apple_wallet', 'security' );

		$validation_url = false;
		if ( isset( $_POST['validation_url'] ) && ! empty( $_POST['validation_url'] ) ) {  // WPCS: CSRF ok.
			$validation_url = esc_url_raw( $_POST['validation_url'] );
		}

		if ( $validation_url ) {
			try {
				$result = WC_Bluesnap_API::create_apple_wallet( $validation_url );
				wp_send_json_success( $result );
			} catch ( WC_Bluesnap_API_Exception $e ) {
				WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			}
		}
		wp_send_json_error();
	}

	public function ajax_create_apple_payment() {
		$payment_token = false;

		if ( WC()->cart->is_empty() ) {
			wp_send_json_error( __( 'Empty cart', 'woocommerce-bluesnap-gateway' ) );
		}

		if ( isset( $_POST['payment_token'] ) && ! empty( $_POST['payment_token'] ) ) {  // WPCS: CSRF ok.
			$payment_token = json_decode( base64_decode( $_POST['payment_token'] ), true ); // WPCS: CSRF ok.
			if ( ! isset( $payment_token['token'] ) ) {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}

		if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
			define( 'WOOCOMMERCE_CHECKOUT', true );
		}

		$this->normalize_posted_data_for_order( $payment_token );

		$_POST['decoded_payment_token'] = $payment_token;

		WC()->checkout()->process_checkout();

		die( 0 );

	}

	protected function payment_request_convert( $pr, $type ) {
		$ret = array();
		switch ( $type ) {
			case 'apple_pay':
				$ret += $pr['order_data'];
				$ret += array(
					'supportedNetworks'             => array( 'amex', 'discover', 'masterCard', 'visa' ),
					'merchantCapabilities'          => array( 'supports3DS' ),
					'requiredBillingContactFields'  => array( 'postalAddress', 'name' ),
					'requiredShippingContactFields' => $pr['shipping_required'] ? array( 'postalAddress', 'email', 'phone' ) : array( 'email', 'phone' ),
				);
		}

		return $ret;
	}

	public function ajax_get_payment_request() {
		check_ajax_referer( 'wc-gateway-bluesnap-ajax-get_payment_request', 'security' );

		if ( ! is_a( WC()->cart, 'WC_Cart' ) ) {
			wp_send_json_error();
		}

		// Set mandatory payment details.
		$data = array(
			'shipping_required' => WC()->cart->needs_shipping(),
			'order_data'        => array(
				'currencyCode' => get_woocommerce_currency(),
				'countryCode'  => substr( WC()->countries->get_base_country(), 0, 2 ),
			),
		);

		$data['order_data'] += $this->build_display_items( false );

		wp_send_json_success( $this->payment_request_convert( $data, $_POST['payment_request_type'] ) );
	}

	/**
	 * Get shipping options.
	 *
	 * @see WC_Cart::get_shipping_packages().
	 * @see WC_Shipping::calculate_shipping().
	 * @see WC_Shipping::get_packages().
	 */
	public function ajax_get_shipping_options() {
		check_ajax_referer( 'wc-gateway-bluesnap-ajax-get_shipping_options', 'security' );

		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

		try {
			// Set the shipping package.
			$posted = json_decode( base64_decode( $_POST['address'] ), true );

			$posted = $this->normalize_contact( $posted );

			$this->calculate_shipping( apply_filters( 'wc_gateway_bluesnap_payment_request_shipping_posted_values', $posted ) );

			// Set the shipping options.
			$data     = array();
			$packages = WC()->shipping->get_packages();

			if ( ! empty( $packages ) && WC()->customer->has_calculated_shipping() ) {
				foreach ( $packages as $package_key => $package ) {
					if ( empty( $package['rates'] ) ) {
						throw new Exception( __( 'Unable to find shipping method for address.', 'woocommerce-bluesnap-gateway' ) );
					}

					foreach ( $package['rates'] as $key => $rate ) {
						$data['shippingMethods'][] = array(
							'identifier' => $rate->id,
							'label'      => $rate->label,
							'detail'     => '',
							'amount'     => bluesnap_format_decimal( $rate->cost ),
						);
					}
				}
			} else {
				throw new Exception( __( 'Unable to find shipping method for address.', 'woocommerce-bluesnap-gateway' ) );
			}

			$chosen_now = array();
			if ( ! empty( $chosen_shipping_methods ) ) {
				$available = wp_list_pluck( $data['shippingMethods'], 'identifier' );

				$chosen_now = array_values( array_intersect( $available, $chosen_shipping_methods ) );

				if ( count( $chosen_now ) > 1 ) {
					$chosen_now = array( $chosen_now[0] );
				}

				foreach ( $data['shippingMethods'] as $i => $this_data ) {
					if ( $chosen_now[0] === $this_data['identifier'] ) {
						unset( $data['shippingMethods'][ $i ] );
						array_unshift( $data['shippingMethods'], $this_data );
						$data['shippingMethods'] = array_values( $data['shippingMethods'] );
						break;
					}
				}
			}

			if ( empty( $chosen_now ) && isset( $data['shippingMethods'][0] ) ) {
				$chosen_now = array( $data['shippingMethods'][0]['identifier'] );
			}

			if ( ! empty( $chosen_now ) ) {
				// Auto select the first shipping method.
				WC()->session->set( 'chosen_shipping_methods', $chosen_now );
			}

			WC()->cart->calculate_totals();

			$data += $this->build_display_items();

			wp_send_json_success( $data );
		} catch ( Exception $e ) {
			$data             += $this->build_display_items();
			$data['errorCode'] = 'invalid_shipping_address';
			$data['message']   = $e->getMessage();

			wp_send_json_error( $data );
		}
	}

	/**
	 * Update shipping method.
	 */
	public function ajax_update_shipping_method() {
		check_ajax_referer( 'wc-gateway-bluesnap-ajax-update_shipping_method', 'security' );

		if ( ! defined( 'WOOCOMMERCE_CART' ) ) {
			define( 'WOOCOMMERCE_CART', true );
		}

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$shipping_method         = filter_input( INPUT_POST, 'method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( is_array( $shipping_method ) ) {
			foreach ( $shipping_method as $i => $value ) {
				$chosen_shipping_methods[ $i ] = wc_clean( $value );
			}
		}

		WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );

		WC()->cart->calculate_totals();

		$data  = array();
		$data += $this->build_display_items();

		wp_send_json_success( $data );
	}

	/**
	 * Calculate and set shipping method.
	 *
	 * @since 1.2.0
	 * @param array $address
	 */
	protected function calculate_shipping( $address = array() ) {
		global $states;

		$country   = $address['country'];
		$state     = $address['state'];
		$postcode  = $address['postcode'];
		$city      = $address['city'];
		$address_1 = $address['address_1'];
		$address_2 = $address['address_2'];

		$country_class = new WC_Countries();
		$country_class->load_country_states();

		/**
		 * In some versions of Chrome, state can be a full name. So we need
		 * to convert that to abbreviation as WC is expecting that.
		 */
		if ( 2 < strlen( $state ) ) {
			$state = array_search( ucfirst( strtolower( $state ) ), $states[ $country ] );
		}

		WC()->shipping->reset_shipping();

		if ( $postcode && WC_Validation::is_postcode( $postcode, $country ) ) {
			$postcode = wc_format_postcode( $postcode, $country );
		}

		if ( $country ) {
			WC()->customer->set_location( $country, $state, $postcode, $city );
			WC()->customer->set_shipping_location( $country, $state, $postcode, $city );
		} else {
			version_compare( WC_VERSION, '3.0', '<' ) ? WC()->customer->set_to_base() : WC()->customer->set_billing_address_to_base();
			version_compare( WC_VERSION, '3.0', '<' ) ? WC()->customer->set_shipping_to_base() : WC()->customer->set_shipping_address_to_base();
		}

		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			WC()->customer->calculated_shipping( true );
		} else {
			WC()->customer->set_calculated_shipping( true );
			WC()->customer->save();
		}

		$packages = array();

		$packages[0]['contents']                 = WC()->cart->get_cart();
		$packages[0]['contents_cost']            = 0;
		$packages[0]['applied_coupons']          = WC()->cart->applied_coupons;
		$packages[0]['user']['ID']               = get_current_user_id();
		$packages[0]['destination']['country']   = $country;
		$packages[0]['destination']['state']     = $state;
		$packages[0]['destination']['postcode']  = $postcode;
		$packages[0]['destination']['city']      = $city;
		$packages[0]['destination']['address']   = $address_1;
		$packages[0]['destination']['address_2'] = $address_2;

		foreach ( WC()->cart->get_cart() as $item ) {
			if ( $item['data']->needs_shipping() ) {
				if ( isset( $item['line_total'] ) ) {
					$packages[0]['contents_cost'] += $item['line_total'];
				}
			}
		}

		$packages = apply_filters( 'woocommerce_cart_shipping_packages', $packages );

		WC()->shipping->calculate_shipping( $packages );
	}

	/**
	 * Sets the WC customer session if one is not set.
	 * This is needed so nonces can be verified by AJAX Request.
	 *
	 * @since 1.2.0
	 */
	public function set_session() {
		if ( ! is_product() || ( isset( WC()->session ) && WC()->session->has_session() ) ) {
			return;
		}

		$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
		$wc_session    = new $session_class();

		if ( version_compare( WC_VERSION, '3.3', '>=' ) ) {
			$wc_session->init();
		}

		$wc_session->set_customer_session_cookie( true );
	}

	/**
	 * Check Apple Pay availability.
	 *
	 * @since 1.3.0
	 */
	private function get_apple_pay_available_gateway() {
		global $post;

		$gateways = WC()->payment_gateways->get_available_payment_gateways();

		if ( ! isset( $gateways[ WC_BLUESNAP_GATEWAY_ID ] ) ) {
			return;
		}

		if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
			return;
		}

		if ( is_checkout() && ! apply_filters( 'wc_gateway_bluesnap_show_payment_request_on_checkout', true, $post ) ) {
			return;
		}

		if ( is_cart() && ! apply_filters( 'wc_gateway_bluesnap_show_payment_request_on_cart', true, $post ) ) {
			return;
		}

		if ( ! $this->allowed_items_in_cart() ) {
			return;
		}

		return $gateways[ WC_BLUESNAP_GATEWAY_ID ];
	}

	/**
	 * Display the payment request button.
	 *
	 * @since 1.2.0
	 */
	public function display_payment_request_button_html() {
		$bluesnap_gateway = $this->get_apple_pay_available_gateway();
		if ( ! $bluesnap_gateway ) {
			return;
		}

		if ( $this->apple_pay_maybe_require_account() ) {
			?>
			<div id="wc-bluesnap-apple-pay-wrapper" style="clear:both;padding-top:1.5em;text-align:center;display:none;">
				<div id="wc-bluesnap-apple-pay-button-cont">
					<?php esc_html_e( 'Apple Pay is available, but an account is required.', 'woocommerce-bluesnap-gateway' ); ?><br/>
					<a href="<?php echo esc_url( add_query_arg( array( 'bs_apple_pay_signup' => '' ), wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Log In or Register', 'woocommerce-bluesnap-gateway' ); ?></a>.
				</div>
			</div>
			<?php
			return;
		}
		?>
		<div id="wc-bluesnap-apple-pay-wrapper" style="clear:both;padding-top:1.5em;text-align:center;display:none;">
			<div id="wc-bluesnap-apple-pay-button-cont">
				<a href="#" class="apple-pay-button apple-pay-button-black"></a>
				<?php $bluesnap_gateway->render_fraud_kount_iframe(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display payment request button separator.
	 *
	 * @since 1.2.0
	 */
	public function display_payment_request_button_separator_html() {
		$bluesnap_gateway = $this->get_apple_pay_available_gateway();
		if ( ! $bluesnap_gateway ) {
			return;
		}
		?>
		<p id="wc-bluesnap-apple-pay-button-separator" style="margin-top:1.5em;text-align:center;display:none;">&mdash; <?php esc_html_e( 'OR', 'woocommerce-bluesnap-gateway' ); ?> &mdash;</p>
		<?php
	}

	/**
	 * Filters the gateway title to reflect Payment Request type
	 *
	 */
	public function filter_gateway_title( $title, $id ) {
		global $post;

		if ( ! is_object( $post ) ) {
			return $title;
		}

		$order        = wc_get_order( $post->ID );
		$method_title = is_object( $order ) ? $order->get_payment_method_title() : '';

		if ( WC_BLUESNAP_GATEWAY_ID === $id && ! empty( $method_title ) && self::APPLE_PAY_METHOD_TITLE === $method_title ) {
			return $method_title;
		}

		return $title;
	}

	/**
	 * Removes postal code validation from WC.
	 *
	 * @since 1.2.0
	 */
	public function postal_code_validation( $valid, $postcode, $country ) {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();

		if ( ! isset( $gateways[ WC_BLUESNAP_GATEWAY_ID ] ) ) {
			return $valid;
		}

		$payment_request_type = isset( $_POST['payment_request_type'] ) ? wc_clean( $_POST['payment_request_type'] ) : ''; // WPCS: CSRF ok.

		if ( 'apple_pay' !== $payment_request_type ) {
			return $valid;
		}

		/**
		 * Currently Apple Pay truncates postal codes from UK and Canada to first 3 characters
		 * when passing it back from the shippingcontactselected object. This causes WC to invalidate
		 * the order and not let it go through. The remedy for now is just to remove this validation.
		 * Note that this only works with shipping providers that don't validate full postal codes.
		 */
		if ( 'GB' === $country || 'CA' === $country ) {
			return true;
		}

		return $valid;
	}

	/**
	 * Add needed order meta
	 *
	 * @since 1.2.0
	 * @param int $order_id
	 */
	public function add_order_meta( $order_id ) {
		if ( empty( $_POST['payment_request_type'] ) ) { // WPCS: CSRF ok.
			return;
		}

		$order = wc_get_order( $order_id );

		$payment_request_type = wc_clean( $_POST['payment_request_type'] ); // WPCS: CSRF ok.

		if ( 'apple_pay' === $payment_request_type ) {
			$order->set_payment_method_title( self::APPLE_PAY_METHOD_TITLE );
			$order->save();
		}
	}

	/**
	 * Checks to make sure product type is supported.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public function supported_product_types() {
		return apply_filters(
			'wc_gateway_bluesnap_payment_request_supported_types',
			array(
				'simple',
				'variable',
				'variation',
				'subscription',
				'subscription_variation',
				'booking',
				'bundle',
				'composite',
				'mix-and-match',
			)
		);
	}

	/**
	 * Checks the cart to see if all items are allowed to used.
	 *
	 * @since 1.2.0
	 * @return bool
	 */
	public function allowed_items_in_cart() {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( ! in_array( $_product->get_type(), $this->supported_product_types() ) ) {
				return false;
			}

			if ( in_array( $_product->get_type(), array( 'subscription', 'subscription_variation' ) ) && $_product->needs_shipping() ) {
				return false;
			}
		}

		return true;
	}

	private function clear_country_code( $country ) {
		$clean_country_codes = array(
			'australia'             => 'AU',
			'austria'               => 'AT',
			'canada'                => 'CA',
			'schweiz'               => 'CH',
			'deutschland'           => 'DE',
			'hongkong'              => 'HK',
			'saudiarabia'           => 'SA',
			'espaa'                 => 'ES',
			'singapore'             => 'SG',
			'us'                    => 'US',
			'usa'                   => 'US',
			'unitedstatesofamerica' => 'US',
			'unitedstates'          => 'US',
			'england'               => 'GB',
			'gb'                    => 'GB',
			'uk'                    => 'GB',
			'unitedkingdom'         => 'GB',
		);

		$country = preg_replace( '/[^a-z]+/', '', strtolower( $country ) );

		return isset( $clean_country_codes[ $country ] ) ? $clean_country_codes[ $country ] : null;
	}

	private function normalize_contact( $contact ) {
		if ( empty( $contact['countryCode'] ) && isset( $contact['country'] ) ) {
			$contact['countryCode'] = $this->clear_country_code( $contact['country'] );
		}

		$wc_contact = array(
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'email'      => '',
			'phone'      => '',
			'country'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
		);

		if ( ! empty( $contact['givenName'] ) && is_string( $contact['givenName'] ) ) {
			$wc_contact['first_name'] = $contact['givenName'];
		}

		if ( ! empty( $contact['familyName'] ) && is_string( $contact['familyName'] ) ) {
			$wc_contact['last_name'] = $contact['familyName'];
		}

		if ( ! empty( $contact['emailAddress'] ) && is_string( $contact['emailAddress'] ) ) {
			$wc_contact['email'] = $contact['emailAddress'];
		}

		if ( ! empty( $contact['phoneNumber'] ) && is_string( $contact['phoneNumber'] ) ) {
			$wc_contact['phone'] = $contact['phoneNumber'];
		}

		if ( ! empty( $contact['countryCode'] ) && is_string( $contact['countryCode'] ) ) {
			$wc_contact['country'] = $contact['countryCode'];
		}

		if ( ! empty( $contact['addressLines'] ) && is_array( $contact['addressLines'] ) ) {
			$lines                   = $contact['addressLines'];
			$wc_contact['address_1'] = array_shift( $lines );
			$wc_contact['address_2'] = implode( ', ', $lines );
		}

		if ( ! empty( $contact['locality'] ) && is_string( $contact['locality'] ) ) {
			$wc_contact['city'] = $contact['locality'];
		}

		if ( ! empty( $contact['administrativeArea'] ) && is_string( $contact['administrativeArea'] ) ) {
			$wc_contact['state'] = $contact['administrativeArea'];
		}

		if ( ! empty( $contact['postalCode'] ) && is_string( $contact['postalCode'] ) ) {
			$wc_contact['postcode'] = $contact['postalCode'];
		}

		$wc_contact = $this->normalize_state( $wc_contact );

		return $wc_contact;
	}

	private function fill_contact_variables( $type, $wc_contact ) {
		foreach ( $wc_contact as $prop => $val ) {
			$_POST[ $type . '_' . $prop ] = $val;
		}
	}

	/**
	 * Handles converting posted data
	 *
	 * @since 1.2.0
	 */
	public function normalize_posted_data_for_order( $posted_data ) {
		$billing  = $this->normalize_contact( $posted_data['billingContact'] );
		$shipping = $this->normalize_contact( isset( $posted_data['shippingContact'] ) ? $posted_data['shippingContact'] : array() );

		$billing['email'] = $shipping['email'];
		unset( $shipping['email'] );
		$billing['phone'] = $shipping['phone'];
		unset( $shipping['phone'] );

		$this->fill_contact_variables( 'billing', $billing );
		$this->fill_contact_variables( 'shipping', $shipping );

		$_POST['order_comments']            = '';
		$_POST['payment_method']            = WC_BLUESNAP_GATEWAY_ID;
		$_POST['ship_to_different_address'] = '1';
		$_POST['terms']                     = '1';
	}

	/**
	 * Normalizes the state/county field because in some
	 * cases, the state/county field is formatted differently from
	 * what WC is expecting and throws an error. An example
	 * for Ireland the county dropdown in Chrome shows "Co. Clare" format
	 *
	 * @since 1.2.0
	 */
	public function normalize_state( $contact ) {
		$billing_country = ! empty( $contact['country'] ) ? wc_clean( $contact['country'] ) : '';
		$billing_state   = ! empty( $contact['state'] ) ? wc_clean( $contact['state'] ) : '';

		if ( $billing_state && $billing_country ) {
			$valid_states = WC()->countries->get_states( $billing_country );

			// Valid states found for country.
			if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
				foreach ( $valid_states as $state_abbr => $state ) {
					if ( preg_match( '/' . preg_quote( $state ) . '/i', $billing_state ) ) {
						$contact['state'] = $state_abbr;
					}
				}
			}
		}

		return $contact;
	}

	public function try_alternate_payment( $attempt, $order, $payload, $gateway ) {
		if ( ! isset( $_POST['payment_request_type'] ) ) { // WPCS: CSRF ok.
			return $attempt;
		}

		$test = ( ! empty( WC_Bluesnap()->get_option( 'testmode' ) && 'yes' === WC_Bluesnap()->get_option( 'testmode' ) ) ) ? true : false;

		if ( 'apple_pay' == $_POST['payment_request_type'] && isset( $_POST['decoded_payment_token'] ) ) { // WPCS: CSRF ok.
			if ( $test && self::APPLE_PAY_SIMULATOR_TX_IDENTIFIER === $_POST['decoded_payment_token']['token']['transactionIdentifier'] ) { // WPCS: CSRF ok.
				$gateway->add_order_success_note( $order, self::APPLE_PAY_SIMULATOR_TX_IDENTIFIER );
				$order->payment_complete( self::APPLE_PAY_SIMULATOR_TX_IDENTIFIER );
				return false; // return false to avoid other attempts
			}
		}

		return $attempt;
	}

	public function validate_alternate_payment( $errors ) {
		if ( ! isset( $_POST['decoded_payment_token'] ) ) { // WPCS: CSRF ok.
			return $errors;
		}

		if ( $errors->get_error_message( 'card_info_invalid' ) ) {
			$errors->remove( 'card_info_invalid' );
		}

		return $errors;
	}

	/**
	 * Builds the line items to pass to Payment Request
	 *
	 * @since 1.2.0
	 */
	protected function build_display_items( $show_shipping = true ) {
		if ( ! defined( 'WOOCOMMERCE_CART' ) ) {
			define( 'WOOCOMMERCE_CART', true );
		}

		WC()->payment_gateways();

		$items     = array();
		$subtotal  = 0;
		$discounts = 0;

		// Default show only subtotal instead of itemization.
		if ( apply_filters( 'wc_gateway_bluesnap_payment_request_show_itemization', true ) ) {
			WC()->cart->calculate_totals();
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$amount         = $cart_item['line_subtotal'];
				$subtotal      += $cart_item['line_subtotal'];
				$quantity_label = 1 < $cart_item['quantity'] ? ' (x' . $cart_item['quantity'] . ')' : '';

				$product_name = version_compare( WC_VERSION, '3.0', '<' ) ? $cart_item['data']->post->post_title : $cart_item['data']->get_name();

				$item = array(
					'label'  => $product_name . $quantity_label,
					'amount' => bluesnap_format_decimal( $amount ),
				);

				$items_to_add = apply_filters( 'wc_gateway_bluesnap_payment_request_cart_item_line_items', array( $item ), $item, $cart_item );

				foreach ( $items_to_add as $item ) {
					$items[] = $item;
				}
			}
		}

		if ( version_compare( WC_VERSION, '3.2', '<' ) ) {
			$discounts = wc_format_decimal( WC()->cart->get_cart_discount_total(), WC()->cart->dp );
		} else {
			$applied_coupons = array_values( WC()->cart->get_coupon_discount_totals() );

			foreach ( $applied_coupons as $amount ) {
				$discounts += (float) $amount;
			}
		}

		$discounts   = wc_format_decimal( $discounts, WC()->cart->dp );
		$tax         = wc_format_decimal( WC()->cart->tax_total + WC()->cart->shipping_tax_total, WC()->cart->dp );
		$shipping    = wc_format_decimal( WC()->cart->shipping_total, WC()->cart->dp );
		$items_total = wc_format_decimal( WC()->cart->cart_contents_total, WC()->cart->dp ) + $discounts;
		$order_total = version_compare( WC_VERSION, '3.2', '<' ) ? wc_format_decimal( $items_total + $tax + $shipping - $discounts, WC()->cart->dp ) : WC()->cart->get_total( false );

		if ( wc_tax_enabled() ) {
			$items[] = array(
				'label'  => esc_html( __( 'Tax', 'woocommerce-bluesnap-gateway' ) ),
				'amount' => bluesnap_format_decimal( $tax ),
			);
		}

		if ( $show_shipping && WC()->cart->needs_shipping() ) {
			$items[] = array(
				'label'  => esc_html( __( 'Shipping', 'woocommerce-bluesnap-gateway' ) ),
				'amount' => bluesnap_format_decimal( $shipping ),
			);
		}

		if ( WC()->cart->has_discount() ) {
			$items[] = array(
				'label'  => esc_html( __( 'Discount', 'woocommerce-bluesnap-gateway' ) ),
				'amount' => bluesnap_format_decimal( $discounts ),
			);
		}

		if ( version_compare( WC_VERSION, '3.2', '<' ) ) {
			$cart_fees = WC()->cart->fees;
		} else {
			$cart_fees = WC()->cart->get_fees();
		}

		// Include fees and taxes as display items.
		foreach ( $cart_fees as $key => $fee ) {
			$items[] = array(
				'label'  => $fee->name,
				'amount' => bluesnap_format_decimal( $fee->amount ),
			);
		}

		$items = apply_filters( 'wc_gateway_bluesnap_payment_request_items', $items, $order_total );

		return array(
			'lineItems' => $items,
			'total'     => array(
				'label'  => get_option( 'blogname' ),
				'amount' => bluesnap_format_decimal( max( 0, apply_filters( 'wc_gateway_bluesnap_payment_request_calculated_total', $order_total, $order_total, WC()->cart ) ) ),
				'type'   => 'final',
			),
		);
	}

	public function payment_request_payment_payload( $ret, $order ) {
		if ( ! isset( $_POST['payment_request_type'] ) ) { // WPCS: CSRF ok.
			return $ret;
		}

		if ( 'apple_pay' == $_POST['payment_request_type'] && isset( $_POST['decoded_payment_token'] ) ) { // WPCS: CSRF ok.
			$ret->type    = 'payment_request_' . $_POST['payment_request_type']; // WPCS: CSRF ok.
			$ret->payload = array_merge(
				$ret->payload,
				array(
					'wallet' => array(
						'applePay' => array(
							'encodedPaymentToken' => base64_encode( wp_json_encode( $_POST['decoded_payment_token'] ) ), // WPCS: CSRF ok.
						),
					),
				)
			);
		}

		return $ret;
	}

	public function adapted_payload_for_ondemand_wallet( $ret ) {
		if ( ! isset( $_POST['payment_request_type'] ) ) { // WPCS: CSRF ok.
			return $ret;
		}

		if ( 'apple_pay' == $_POST['payment_request_type'] && isset( $_POST['decoded_payment_token'] ) ) { // WPCS: CSRF ok.
			$ret->payload['paymentSource'] = array(
				'wallet' => $ret->payload['wallet'],
			);
			unset( $ret->payload['wallet'] );
		}

		return $ret;
	}

	public function maybe_add_redirect_field() {
		if ( ! isset( $_REQUEST['bs_apple_pay_signup'] ) ) {
			return;
		}
		?>
		<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'checkout' ) ); ?>" />
		<?php
	}

	public function apple_pay_maybe_require_account() {
		if ( is_user_logged_in() ) { // we're logged in, we don't care for the cart contents at this point
			return false;
		}

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( in_array( $_product->get_type(), array( 'subscription', 'subscription_variation' ) ) ) {
				// we're not logged in, and we found a subscription, so we need to display the login form
				return true;
			}
		}
		return false;
	}

}

return new WC_Bluesnap_Apple_Pay();
