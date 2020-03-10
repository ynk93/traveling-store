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
 * Class WC_Bluesnap_Multicurrency
 */
class WC_Bluesnap_Multicurrency {

	/**
	 * Cookie name.
	 */
	const COOKIE_CURRENCY_NAME = 'wc_bluesnap_currency';

	/**
	 * Transient rates
	 */
	const CURRENCY_RATES_TRANSIENT = 'wc_bluesnap_currency_rates';

	/**
	 * @var string
	 */
	protected static $currency_selected;

	/**
	 * @var string
	 */
	public static $original_currency;

	/**
	 * @var array
	 */
	protected $conversion_rates_cache;

	protected static $currency_config;

	/**
	 * WC_Bluesnap_Multicurrency constructor.
	 */
	public function __construct() {
		// Add multicurrency functionality if multicurrency is enabled and there is some currency selected.
		if ( 'yes' === WC_Bluesnap()->get_option( 'multicurrency' ) && ! empty( self::get_currencies_setting_selected() ) ) {
			// Adds shortcode and widget.
			add_action( 'widgets_init', array( $this, 'add_multicurrency_widget' ) );

			// Get all currency prices html on frontend to avoid caching issues
			add_filter( 'woocommerce_get_price_html', array( $this, 'enrich_woocommerce_get_price_html' ), 1, 2 );

			// WC Hooks to implement currency conversion
			add_filter( 'woocommerce_product_get_price', array( $this, 'convert_currency_prices' ), 1, 2 );
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_currency_prices' ), 1, 2 );
			add_filter( 'woocommerce_product_sales_price', array( $this, 'convert_currency_prices' ), 1, 2 );
			add_filter( 'woocommerce_shipping_rate_cost', array( $this, 'convert_shipping_rate_cost' ) );

			// Adapt WC Config accordingly on the fly
			add_filter( 'woocommerce_currency', array( $this, 'convert_currency_symbol' ) );
			add_filter( 'pre_option_woocommerce_currency_pos', array( $this, 'convert_currency_pos' ) );
			add_filter( 'wc_get_price_thousand_separator', array( $this, 'convert_thousand_sep' ) );
			add_filter( 'wc_get_price_decimal_separator', array( $this, 'convert_decimal_sep' ) );
			add_filter( 'wc_get_price_decimals', array( $this, 'convert_num_decimals' ) );

			// Get latest rates on woocommerce_checkout_update_order_review
			add_action( 'woocommerce_checkout_update_order_review', array( $this, 'update_currency_rates_on_checkout' ) );

			// User Cookie handling.
			add_action( 'wc_ajax_bluesnap_set_multicurrency', array( $this, 'change_user_currency' ) );
			// Set original cookie without filter interference.
			add_action( 'init', array( $this, 'set_original_currency' ), -10 );

			add_filter( 'woocommerce_shipping_free_shipping_is_available', array( $this, 'free_shipping_is_available' ), 10, 3 );
			add_filter( 'woocommerce_shipping_legacy_free_shipping_is_available', array( $this, 'free_shipping_is_available' ), 10, 3 );
		}

		// Shortcode
		add_shortcode( 'bluesnap_multicurrency', array( $this, 'add_multicurrency_shortcode' ) );

		// admin
		add_filter( 'get_bluesnap_supported_currency_list', array( $this, 'get_bluesnap_supported_currency_list' ) );
		add_filter( 'wc_bluesnap_settings', array( $this, 'disable_multicurrency_field_if_not_enabled' ) );

		// Subscriptions specific.
		add_action( 'woocommerce_setup_cart_for_subscription_renewal', array( $this, 'set_cookie_on_subscription_renewal' ) );

		// Admin hook, we get latest rates when getting settings actions
		add_action( 'wc_gateway_bluesnap_latest_currencies', array( $this, 'update_currency_rates' ) );
	}

	private static function get_currency_settings() {
		if ( isset( self::$currency_config ) ) {
			return self::$currency_config;
		}

		$currency_code = self::get_currency_user_selected();

		$locale_info = include WC()->plugin_path() . '/i18n/locale-info.php';
		$currency_config = array();
		$default_data = array(
			'currency_code'  => $currency_code,
			'currency_pos'   => 'left',
			'decimal_sep'    => '.',
			'num_decimals'   => 2,
			'thousand_sep'   => ',',
		);

		foreach ( array( 'CLP', 'JPY', 'ISK', 'KRW', 'VND', 'XOF' ) as $no_dec_curr ) {
			$currency_config[ $no_dec_curr ] = $default_data;
			$currency_config[ $no_dec_curr ]['num_decimals'] = 0;
		}

		foreach ( $locale_info as $country => $data ) {
			$currency_config[ $data['currency_code'] ] = array_intersect_key(
				wp_parse_args(
					$data,
					$default_data
				),
				$default_data
			);
		}

		$currency_config = isset( $currency_config[ $currency_code ] ) ? $currency_config[ $currency_code ] : $default_data;

		self::$currency_config = $currency_config;

		return $currency_config;
	}

	/**
	 * When a subscription is forced to Renew Now, we have to change cookie in case it is using another currency.
	 *
	 * @param WC_Subscription $subscription
	 * @param $cart_item_data
	 */
	public function set_cookie_on_subscription_renewal( $subscription ) {
		$subscription_renewal_currency = $subscription->get_currency();
		if ( self::$original_currency != $subscription_renewal_currency ) {
			self::set_cookie( self::COOKIE_CURRENCY_NAME, $subscription_renewal_currency );
		}
	}

	/**
	 * Multicurrency Selector Shortcode. Avalaible only when multicurrency is ready.
	 *
	 * @return string
	 */
	public function add_multicurrency_shortcode() {
		if ( 'yes' === WC_Bluesnap()->get_option( 'multicurrency' ) && ! empty( self::get_currencies_setting_selected() ) ) {
			return woocommerce_bluesnap_gateway_get_template_html(
				'multicurrency-selector.php',
				array(
					'options'           => self::get_currencies_setting_selected(),
					'original_currency' => self::$original_currency,
				)
			);
		}
		return '';
	}

	/**
	 * Registering multicurrency widget class.
	 */
	public function add_multicurrency_widget() {
		register_widget( 'WC_Bluesnap_Widget_Multicurrency' );
	}

	public static function nonce_field() {
		add_filter( 'nonce_user_logged_out', '__return_zero' );
		wp_nonce_field( 'bluesnap-multicurrency-nonce' );
		remove_filter( 'nonce_user_logged_out', '__return_zero' );
	}

	public static function verify_nonce() {
		add_filter( 'nonce_user_logged_out', '__return_zero' );
		$ret = wp_verify_nonce( $_REQUEST['_wpnonce'], 'bluesnap-multicurrency-nonce' );
		remove_filter( 'nonce_user_logged_out', '__return_zero' );
		return $ret;
	}

	/**
	 * Change cookie currency.
	 */
	public function change_user_currency() {
		if ( isset( $_REQUEST['action'] ) &&
			 'bluesnap_multicurrency_action' === $_REQUEST['action'] &&
			 self::verify_nonce()
		) {
			self::set_currency_user_selected( $_REQUEST['bluesnap_currency_selector'], true );
			WC()->cart->calculate_totals();

			$referer = '';
			if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
				$referer = wp_unslash( $_SERVER['HTTP_REFERER'] );
			}

			wp_safe_redirect( ! empty( $referer ) ? $referer : home_url() );
			exit;
		}
	}

	/**
	 * It return a specific rate or a full list of rates.
	 * If $conversion_rates_cache exists, it is an updated rate for checkout.
	 *
	 * @param null|string $currency
	 *
	 * @return array|float
	 */
	public function get_currency_rates( $currency = null ) {
		if ( isset( $this->conversion_rates_cache ) ) {
			$rates = $this->conversion_rates_cache;
		} elseif ( $rates = get_transient( self::CURRENCY_RATES_TRANSIENT ) ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments, Generic.CodeAnalysis.EmptyStatement
			// rates are coming from transient, and variable is set inside IF
		} else {
			$rates = $this->update_currency_rates();
		}

		if ( ! isset( $this->conversion_rates_cache ) ) {
			$this->conversion_rates_cache = $rates;
		}

		if ( $currency ) {
			if ( isset( $rates ) && is_array( $rates ) && isset( $rates[ $currency ] ) ) {
				return $rates[ $currency ];
			} else {
				return false;
			}
		} else {
			return $rates;
		}
	}

	/**
	 * Update currency rates into our rate transient.
	 * Updates each hour on demand, or forced when settings on the gateway changes.
	 *
	 * @param array|null $updated_rates updated rates injected.
	 *
	 * @return array
	 */
	public function update_currency_rates( $updated_rates = null ) {
		$conversions = array();
		try {
			if ( $updated_rates ) {
				$rates = $updated_rates;
			} else {
				$rates = WC_Bluesnap_API::retrieve_conversion_rate( self::$original_currency );
			}

			foreach ( $rates['currencyRate'] as $currency ) {
				$conversions[ $currency['quoteCurrency'] ] = $currency['conversionRate'];
			}
			// Add base rate 1 to 1 to avoid extra checks.
			$conversions[ $rates['baseCurrency'] ] = 1;
			set_transient( self::CURRENCY_RATES_TRANSIENT, $conversions, HOUR_IN_SECONDS );
		} catch ( WC_Bluesnap_API_Exception $e ) {
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
			return false;
		}
		return $conversions;
	}

	private $should_convert_flag = false;

	private $contains_renewal_curr = false;

	private function contains_renewal() {
		if ( $this->contains_renewal_curr ) {
			return $this->contains_renewal_curr;
		}
		foreach ( WC()->session->cart as $key => $item ) {
			if ( isset( $item['subscription_renewal'] ) ) {
				/**
				 * Disclaimer: this is a known drawback of not using objects, or proper WC things,
				 * but this is required in order to access metadata before the post types are defined.
				 *
				 * There's no way around this. We'll revisit this when WC provides
				 * better multicurrency support.
				 */
				$curr = get_post_meta( $item['subscription_renewal']['subscription_id'], '_order_currency', true );
				$this->contains_renewal_curr = $curr;
				return $this->contains_renewal_curr;
			}
		}

		return false;
	}

	private function _should_convert( $type = null ) {
		if ( is_bool( $this->get_currency_rates() ) ) {
			return false;
		}

		if ( is_admin() && ! wp_doing_ajax() ) {
			return false;
		}

		$renewal_curr = $this->contains_renewal();
		if ( $renewal_curr ) {
			self::$currency_selected = $renewal_curr;
			switch ( $type ) {
				case 'currency_prices':
				case 'shipping_rate_cost':
				case 'enrich_html':
					return false;
					break;
				default:
					return true;
					break;
			}
		}

		return true;
	}

	private function should_convert( $type = null ) {
		if ( $this->should_convert_flag ) {
			return false;
		}

		$this->should_convert_flag = true;
		$ret = $this->_should_convert( $type );
		$this->should_convert_flag = false;
		return $ret;
	}

	/**
	 * Hooks to change currency. Only on the frontend. Dashboard is left intact.
	 * @param $currency
	 *
	 * @return string
	 */
	public function convert_currency_symbol( $val ) {
		if ( ! $this->should_convert( 'currency_symbol' ) ) {
			return $val;
		}
		$currency_settings = self::get_currency_settings();
		return $currency_settings['currency_code'];
	}

	public function convert_currency_pos( $val ) {
		if ( ! $this->should_convert( 'currency_pos' ) ) {
			return $val;
		}
		$currency_settings = self::get_currency_settings();
		return $currency_settings['currency_pos'];
	}

	public function convert_thousand_sep( $val ) {
		if ( ! $this->should_convert( 'thousand_sep' ) ) {
			return $val;
		}
		$currency_settings = self::get_currency_settings();
		return $currency_settings['thousand_sep'];
	}

	public function convert_decimal_sep( $val ) {
		if ( ! $this->should_convert( 'decimal_sep' ) ) {
			return $val;
		}
		$currency_settings = self::get_currency_settings();
		return $currency_settings['decimal_sep'];
	}

	public function convert_num_decimals( $val ) {
		if ( ! $this->should_convert( 'num_decimals' ) ) {
			return $val;
		}
		$currency_settings = self::get_currency_settings();
		return $currency_settings['num_decimals'];
	}

	/**
	 * It converts prices, it always takes original prices from data.
	 * This way we avoid unwanted prices converted already from subscriptions (Renew Now) for instance.
	 *
	 * @param string $price
	 * @param WC_Product_Subscription $product
	 *
	 * @return float
	 */
	public function convert_currency_prices( $price, $product ) {
		if ( ! $this->should_convert( 'currency_prices' ) ) {
			return $price;
		}

		$product_data = $product->get_data();

		switch ( current_filter() ) {
			case 'woocommerce_product_get_price':
				$original_price = isset( $product_data['price'] ) ? $product_data['price'] : $price;
				break;
			case 'woocommerce_product_get_regular_price':
				$original_price = isset( $product_data['regular_price'] ) ? $product_data['regular_price'] : $price;
				break;
			case 'woocommerce_product_sales_price':
				$original_price = isset( $product_data['sale_price'] ) ? $product_data['sale_price'] : $price;
				break;
		}

		return $this->conversion_price_to( $original_price, self::get_currency_user_selected() );
	}

	public function convert_shipping_rate_cost( $cost ) {
		if ( ! $this->should_convert( 'shipping_rate_cost' ) ) {
			return $cost;
		}

		return $this->conversion_price_to( $cost, self::get_currency_user_selected() );
	}

	public function free_shipping_is_available( $is_available, $package, $instance ) {
		$current_filter = current_filter();
		remove_filter( $current_filter, array( $this, 'free_shipping_is_available' ), 10 );
		$old_min              = $instance->min_amount;
		$instance->min_amount = $this->conversion_price_to( $old_min, self::get_currency_user_selected() );
		$is_available         = $instance->is_available( $package );
		add_filter( $current_filter, array( $this, 'free_shipping_is_available' ), 10, 3 );
		$instance->min_amount = $old_min;
		return $is_available;
	}

	/**
	 * As there may be some cache on the site, we need to inject all prices converted on the frontend, to be hadled by js
	 * in case is needed.
	 *
	 * @param string $price
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public function enrich_woocommerce_get_price_html( $price, $product ) {
		if ( ! $this->should_convert( 'enrich_html' ) ) {
			return $price;
		}

		$selected_price = $this->wrap_currency_html( self::get_currency_user_selected(), $price );

		$product_data           = $product->get_data();
		$original_regular_price = $product_data['regular_price'];
		$original_sale_price    = $product_data['sale_price'];
		$original_price         = $product_data['price'];

		// Get currencies we need to add to html, add the original one and remove the selected one.
		$currencies_selected = self::get_currencies_setting_selected();
		array_push( $currencies_selected, self::$original_currency );
		$currencies_needed = array_diff( $currencies_selected, array( self::get_currency_user_selected() ) );

		foreach ( $currencies_needed as $currency ) {
			$converted_regular_price = $this->conversion_price_to( $original_regular_price, $currency );
			$converted_sale_price    = $this->conversion_price_to( $original_sale_price, $currency );
			$converted_price_price   = $this->conversion_price_to( $original_price, $currency );

			if ( '' === $product->get_price() ) {
				$price = apply_filters( 'woocommerce_empty_price_html', '', $product );
			} elseif ( $product->is_on_sale() ) {
				$price = wc_format_sale_price(
					wc_get_price_to_display( $product, array( 'price' => $converted_regular_price ) ),
					wc_get_price_to_display( $product, array( 'price' => $converted_sale_price ) )
				) . $product->get_price_suffix();

				// Unfortunately is not possible to inject currency into wc_format_sale_price as it is possible on wc_price
				$pattern     = '#<span class="woocommerce-Price-currencySymbol">(.+?)</span>#';
				$replacement = '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $currency ) . '</span>';
				$price       = preg_replace( $pattern, $replacement, $price );
			} else {
				$price = wc_price(
					wc_get_price_to_display( $product, array( 'price' => $converted_price_price ) ),
					array( 'currency' => $currency )
				) . $product->get_price_suffix();
			}
			$selected_price .= $this->wrap_currency_html( $currency, $price, true );
		}

		return $selected_price;
	}

	/**
	 * Html wrapper for frontend output.
	 *
	 * @param $currency
	 * @param $html_price
	 * @param bool $hide
	 *
	 * @return string
	 */
	private function wrap_currency_html( $currency, $html_price, $hide = false ) {
		return woocommerce_bluesnap_gateway_get_template_html(
			'multicurrency-wrapper.php',
			array(
				'currency' => $currency,
				'html'     => $html_price,
				'hide'     => $hide,
			)
		);
	}

	/**
	 * @param $name
	 * @param $value
	 * @param int $duration
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function set_cookie( $name, $value, $duration = 0, $path = '/' ) {
		$_COOKIE[ $name ] = $value;
		return setcookie( $name, $value, $duration, $path );
	}

	/**
	 * @param $price
	 * @param $to
	 *
	 * @return float
	 */
	protected function conversion_price_to( $price, $to ) {
		return (float) $price * $this->get_currency_rates( $to );
	}

	/**
	 * On checkout, update rates and save it on internal cache.
	 */
	public function update_currency_rates_on_checkout() {
		$this->conversion_rates_cache = $this->update_currency_rates();
	}

	/**
	 * Retrieves list of selected currencies selected on the plugin settings.
	 *
	 * @return array
	 */
	public static function get_currencies_setting_selected() {
		$currencies = WC_Bluesnap()->get_option( 'currencies_supported' );
		return $currencies ? $currencies : array();
	}

	/**
	 * Get original currency without our filter.
	 */
	public function set_original_currency() {
		remove_filter( 'woocommerce_currency', array( $this, 'convert_currency_symbol' ) );
		self::$original_currency = get_woocommerce_currency();
		add_filter( 'woocommerce_currency', array( $this, 'convert_currency_symbol' ) );
	}

	private static function set_currency_user_selected( $selected, $set_cookie = false ) {
		self::$currency_selected = $selected;
		$available               = self::get_currencies_setting_selected();
		array_push( $available, self::$original_currency );
		if ( false === array_search( self::$currency_selected, $available ) ) {
			self::$currency_selected = self::$original_currency;
			$set_cookie              = true;
		}
		if ( $set_cookie ) {
			self::set_cookie( self::COOKIE_CURRENCY_NAME, self::$currency_selected );
		}
		self::$currency_config = null;
	}

	/**
	 * @return string
	 */
	public static function get_currency_user_selected() {
		if ( isset( self::$currency_selected ) ) {
			return self::$currency_selected;
		} elseif ( isset( $_COOKIE[ self::COOKIE_CURRENCY_NAME ] ) ) {
			self::set_currency_user_selected( $_COOKIE[ self::COOKIE_CURRENCY_NAME ] );
		} else {
			self::set_currency_user_selected( self::$original_currency );
		}

		return self::$currency_selected;
	}

	/**
	 * Remove default currency from currency list for admin settings.
	 * @param $supported_currencies
	 *
	 * @return array
	 */
	public function remove_default_currency( $supported_currencies ) {
		return array_diff( $supported_currencies, array( self::$original_currency ) );
	}

	protected function is_multicurrency_enabled() {
		return in_array( WC_Bluesnap()->get_option( 'multicurrency' ), array( 'yes', null ), true );
	}

	/**
	 * Getting updated currency list supported by Bluesnap. (only when API credentials are set).
	 *
	 * @return array
	 */
	public function get_bluesnap_supported_currency_list() {
		$conversions = array();

		if ( empty( WC_Bluesnap()->get_option( 'api_username' ) || empty( WC_Bluesnap()->get_option( 'api_password' ) ) ) ) {
			return $conversions;
		}

		if ( ! $this->is_multicurrency_enabled() ) {
			return $conversions;
		}

		try {
			$rates = WC_Bluesnap_API::retrieve_conversion_rate( get_woocommerce_currency() );
			do_action( 'wc_gateway_bluesnap_latest_currencies', $rates );
			foreach ( $rates['currencyRate'] as $currency ) {
				$conversions[ $currency['quoteCurrency'] ] = $currency['quoteCurrency'];
			}
		} catch ( WC_Bluesnap_API_Exception $e ) {
			WC_Bluesnap_Logger::log( $e->getMessage(), 'error' );
		}
		return $conversions;
	}

	public function disable_multicurrency_field_if_not_enabled( $fields ) {
		if ( ! $this->is_multicurrency_enabled() ) {
			unset( $fields['currencies_supported'] );
		}
		return $fields;
	}

}

new WC_Bluesnap_Multicurrency();
