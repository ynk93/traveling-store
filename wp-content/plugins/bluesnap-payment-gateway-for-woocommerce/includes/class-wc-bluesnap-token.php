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
 * Class WC_Bluesnap_Token
 */
class WC_Bluesnap_Token {
	/**
	 * @var WC_Bluesnap_Token
	 */
	private static $_this;

	/**
	 * Bluesnap to wc type
	 */
	const SUPPORTED_CC_TYPES = array(
		'mastercard' => 'mastercard',
		'visa'       => 'visa',
		'discover'   => 'discover',
		'amex'       => 'american express',
		'jcb'        => 'jcb',
	);

	/**
	 * @var string
	 */
	protected static $gateway_id;

	/**
	 * @var WC_Payment_Token_Bluesnap_CC
	 */
	public static $wc_token;

	/**
	 * WC_Bluesnap_Token constructor.
	 */
	public function __construct() {
		self::$_this = $this;
		add_action( 'wc_gateway_bluesnap_delete_cc_from_my_account', array( __CLASS__, 'woocommerce_payment_token_deleted' ), 10, 3 );
		add_filter( 'woocommerce_payment_methods_list_item', array( __CLASS__, 'get_saved_bluesnap_cc_tokens' ), 10, 2 );
	}

	/**
	 * Public access to instance object.
	 *
	 * @param $gateway_id
	 * @param bool $token_id
	 *
	 * @return WC_Bluesnap_Token
	 */
	public static function get_instance( $gateway_id, $token_id = false ) {
		self::$gateway_id = $gateway_id;
		self::set_wc_token( $token_id );
		return self::$_this;
	}

	/**
	 * @return false|string
	 */
	public function get_card_type() {
		$cc_type = self::$wc_token->get_card_type();
		return self::get_bluesnap_card_type( $cc_type );
	}

	/**
	 * @return string
	 */
	public function get_last4() {
		return self::$wc_token->get_last4();
	}

	/**
	 * @return string
	 */
	public function get_exp() {
		return self::$wc_token->get_expiry_month() . '/' . self::$wc_token->get_expiry_year();
	}

	/**
	 * Given a transaction, save card on Payment Token API.
	 *
	 * @param array $transaction
	 * @param int $vaulted_shopper_id
	 *
	 * @return int|bool
	 * @throws WC_Bluesnap_API_Exception
	 */
	public static function set_payment_token_from_transaction( $transaction, $vaulted_shopper_id ) {
		$vaulted_shopper = WC_Bluesnap_API::retrieve_vaulted_shopper( $vaulted_shopper_id );
		return self::set_payment_token_from_vaulted_shopper(
			$vaulted_shopper,
			$transaction['creditCard']['cardLastFourDigits'],
			$transaction['creditCard']['cardType']
		);
	}

	/**
	 * @param array $vaulted_shopper
	 * @param string $last_four_digits
	 * @param string $cc_type
	 *
	 * @return bool|int
	 */
	public static function set_payment_token_from_vaulted_shopper( $vaulted_shopper, $last_four_digits, $cc_type ) {
		$payment_sources = $vaulted_shopper['paymentSources']['creditCardInfo'];
		foreach ( $payment_sources as $payment_source ) {
			$cc = $payment_source['creditCard'];

			// Compare to get the proper 4 last digits coming from bluesnap.
			if ( $cc['cardLastFourDigits'] !== $last_four_digits ) {
				continue;
			}
			// Compare to get the proper cc type coming from bluesnap.
			if ( $cc['cardType'] !== $cc_type ) {
				continue;
			}

			return self::create_wc_token( $cc );
		}
		return false;
	}

	public static function create_wc_token( $cc ) {
		$token = new WC_Payment_Token_Bluesnap_CC();
		$token->set_token( md5( json_encode( $cc ) ) );
		$token->set_gateway_id( self::$gateway_id );
		$token->set_card_type( self::SUPPORTED_CC_TYPES[ strtolower( $cc['cardType'] ) ] );
		$token->set_last4( $cc['cardLastFourDigits'] );
		$token->set_expiry_month( $cc['expirationMonth'] );
		$token->set_expiry_year( $cc['expirationYear'] );
		$token->set_user_id( get_current_user_id() );
		return $token->save();
	}

	/**
	 * @param string $cc_type
	 *
	 * @return bool
	 */
	public static function is_cc_type_supported( $cc_type ) {
		return ( null !== self::SUPPORTED_CC_TYPES[ strtolower( $cc_type ) ] );
	}

	/**
	 * @param $wc_card_type
	 *
	 * @return false|string
	 */
	protected static function get_bluesnap_card_type( $wc_card_type ) {
		return strtoupper( array_search( strtolower( $wc_card_type ), self::SUPPORTED_CC_TYPES ) );
	}

	/**
	 * Get Customer Tokens for a given gateway.
	 *
	 * @return array
	 */
	protected static function get_customer_tokens() {
		$customer_token = array();
		$tokens         = WC_Payment_Tokens::get_customer_tokens( get_current_user_id() );
		foreach ( $tokens as $token ) {
			if ( $token->get_gateway_id() === self::$gateway_id ) {
				$customer_token[] = $token;
				break;
			}
		}
		return $customer_token;
	}

	/**
	 * @param $token
	 */
	public static function set_wc_token( $token ) {
		if ( is_a( $token, 'WC_Payment_Token_Bluesnap_CC' ) ) {
			self::$wc_token = $token;
		} else {
			self::$wc_token = new WC_Payment_Token_Bluesnap_CC( $token );
		}
		return self::$_this;
	}

	/**
	 * @param string $token_id
	 * @param WC_Payment_Token_Bluesnap_CC $token
	 *
	 * @return bool
	 * @throws WC_Bluesnap_API_Exception
	 */
	public static function woocommerce_payment_token_deleted( $ret, $token, $force_delete ) {
		if ( 'bluesnap' !== $token->get_gateway_id() ) {
			return $ret;
		}

		$shopper            = new WC_Bluesnap_Shopper();
		$vaulted_shopper_id = $shopper->get_bluesnap_shopper_id();

		try {
			$vaulted_shopper    = WC_Bluesnap_API::retrieve_vaulted_shopper( $vaulted_shopper_id );
			$token_instance     = self::set_wc_token( $token );

			WC_Bluesnap_API::delete_vaulted_credit_card(
				$vaulted_shopper_id,
				$vaulted_shopper['firstName'],
				$vaulted_shopper['lastName'],
				strtoupper( $token_instance->get_card_type() ),
				$token_instance->get_last4()
			);
		} catch ( WC_Bluesnap_API_Exception $e ) {
			// Avoid blank screen.
			// There is not much to do, since token is already gone from DB.
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			wc_add_notice( $e->getMessage(), 'error' );
			return false;
		}
	}

	public static function get_saved_bluesnap_cc_tokens( $item, $payment_token ) {
		if ( 'bluesnap_cc' !== strtolower( $payment_token->get_type() ) ) {
			return $item;
		}

		$card_type               = $payment_token->get_card_type();
		$item['method']['last4'] = $payment_token->get_last4();
		$item['method']['brand'] = ( ! empty( $card_type ) ? ucfirst( $card_type ) : esc_html__( 'Credit card', 'woocommerce-bluesnap-gateway' ) );
		$item['expires']         = $payment_token->get_expiry_month() . '/' . substr( $payment_token->get_expiry_year(), -2 );
		return $item;
	}
}

new WC_Bluesnap_Token();
