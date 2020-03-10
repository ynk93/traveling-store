<?php
/**
 * Installation related functions and actions.
 *
 * @author   SAU/CAL
 * @category Core
 * @package  Woocommerce_Bluesnap_Gateway
 * @version  1.3.3
 */

if ( ! class_exists( 'Woocommerce_Bluesnap_Gateway' ) ) :

	final class Woocommerce_Bluesnap_Gateway {

		/**
		 * Woocommerce_Bluesnap_Gateway version.
		 *
		 * @var string
		 */
		public $version = '1.1.0';

		/**
		 * The single instance of the class.
		 *
		 * @var Woocommerce_Bluesnap_Gateway
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		protected static $_initialized = false;

		/**
		 * WC_Bluesnap_Gateway instance.
		 *
		 * @var WC_Bluesnap_Gateway
		 */
		public static $bluesnap_gateway;

		/**
		 * Main Woocommerce_Bluesnap_Gateway Instance.
		 *
		 * Ensures only one instance of Woocommerce_Bluesnap_Gateway is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see WC_Bluesnap()
		 * @return Woocommerce_Bluesnap_Gateway - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->initalize_plugin();
			}
			return self::$_instance;
		}

		/**
		 * Gets options from instanced WC_Bluesnap_Gateway if instanced by WC already.
		 *
		 * @param $key
		 *
		 * @return mixed|null
		 */
		public function get_option( $key ) {
			$bluesnap_settings = get_option( 'woocommerce_bluesnap_settings' );
			return isset( $bluesnap_settings[ $key ] ) ? $bluesnap_settings[ $key ] : null;
		}

		/**
		 * Cloning is forbidden.
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woocommerce-bluesnap-gateway' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woocommerce-bluesnap-gateway' ), '1.0.0' );
		}

		/**
		 * Woocommerce_Bluesnap_Gateway Initializer.
		 */
		public function initalize_plugin() {
			if ( self::$_initialized ) {
				_doing_it_wrong( __FUNCTION__, esc_html__( 'Only a single instance of this class is allowed. Use singleton.', 'woocommerce-bluesnap-gateway' ), '1.0.0' );
				return;
			}
			self::$_initialized = true;

			add_action( 'plugins_loaded', array( $this, 'maybe_continue_init' ), -1 );
		}

		/**
		 * Actually do initialization if plugin dependencies are met.
		 */
		public function maybe_continue_init() {
			if ( ! $this->check_dependencies() ) {
				add_action( 'admin_notices', array( $this, 'dependency_notice' ) );
				return;
			}

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'woocommerce_bluesnap_gateway_loaded' );
		}

		/**
		 * Returns true if dependencies are met, false if not.
		 *
		 * @return bool
		 */
		public function check_dependencies() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				return false;
			}
			return true;
		}

		/**
		 * Prints notice about requirements not met.
		 *
		 * @return void
		 */
		public function dependency_notice() {
			?>
			<div class="notice notice-error">
				<p><strong><?php echo esc_html__( 'WooCommerce Bluesnap Gateway is inactive because WooCommerce is not installed and active.', 'woocommerce-bluesnap-gateway' ); ?></strong></p>
			</div>
			<?php
		}

		/**
		 * Define WC_Bluesnap Constants.
		 */
		private function define_constants() {
			$upload_dir = wp_upload_dir();

			$this->define( 'WC_BLUESNAP_PLUGIN_BASENAME', plugin_basename( WC_BLUESNAP_PLUGIN_FILE ) );
			$this->define( 'WC_BLUESNAP_VERSION', $this->version );
			$this->define( 'WC_BLUESNAP_GATEWAY_ID', 'bluesnap' );
			$this->define( 'REFUND_REASON_PREFIX', 'wc_reason:' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			include_once 'includes/class-wc-bluesnap-autoloader.php';
			include_once 'includes/woocommerce-bluesnap-gateway-core-functions.php';
			include_once 'includes/class-wc-bluesnap-install.php';

			if ( $this->is_request( 'admin' ) ) {
				include_once 'includes/admin/class-wc-bluesnap-admin.php';
			}

			if ( $this->is_request( 'frontend' ) ) {
				include_once 'includes/class-wc-bluesnap-frontend-assets.php'; // Frontend Scripts
				include_once 'includes/class-wc-bluesnap-api.php';
				include_once 'includes/class-wc-bluesnap-api-exception.php';
				include_once 'includes/class-wc-bluesnap-logger.php';
				include_once 'includes/class-wc-bluesnap-shopper.php';
				include_once 'includes/class-wc-payment-token-bluesnap-cc.php';
				include_once 'includes/class-wc-bluesnap-token.php';
				include_once 'includes/class-wc-bluesnap-ipn-webhooks.php';
			}

			// Widgets
			include_once 'includes/widgets/class-wc-bluesnap-widget-multicurrency.php';
			// Include multicurrency support
			include_once 'includes/class-wc-bluesnap-multicurrency.php';
			// Include Apple Pay support
			include_once 'includes/class-wc-bluesnap-apple-pay.php';

			//todo: remove if not used
			//$this->customizations_includes();
		}

		/**
		 * Include required customizations files.
		 */
		private function customizations_includes() {
			$customizations = array(
				'acf',
			);

			foreach ( $customizations as $customization ) {
				include_once 'includes/customizations/class-wc-bluesnap-' . $customization . '-hooks.php';
			}
		}

		/**
		 * Hook into actions and filters.
		 * @since  1.0.0
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'init' ), 0 );
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		}

		/**
		 * Init Woocommerce_Bluesnap_Gateway when WordPress Initialises.
		 */
		public function init() {
			// Before init action.
			do_action( 'before_woocommerce_bluesnap_gateway_init' );

			// Set up localisation.
			$this->load_plugin_textdomain();

			// Init action.
			do_action( 'woocommerce_bluesnap_gateway_init' );
		}

		/**
		 * Hooks when plugin_loaded
		 */
		public function plugins_loaded() {
			$this->payment_methods_includes();
			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
			add_filter( 'woocommerce_email_classes', array( $this, 'add_custom_emails' ) );
			add_action( 'wc_ajax_bluesnap_clear_hpf', array( 'WC_Bluesnap_Gateway', 'hpf_clean_transaction_token_session' ) );
		}

		/**
		 * Include required payment-methods files.
		 */
		public function payment_methods_includes() {

			// Include its abstract
			include_once 'includes/payment-methods/abstract-wc-bluesnap-payment-gateway.php';

			$methods = array(
				'',
			);
			foreach ( $methods as $method ) {
				include_once 'includes/payment-methods/class-wc-bluesnap-gateway' . $method . '.php';
			}

			// Include gateway addons
			include_once 'includes/class-wc-bluesnap-gateway-addons.php';
		}

		/**
		 * Add the gateways to WooCommerce.
		 * @param array $methods
		 *
		 * @return array
		 */
		public function add_gateways( $methods ) {
			$methods[] = 'WC_Bluesnap_Gateway_Addons';
			return $methods;
		}

		/**
		 * Add the custom emails from Bluesnap to WooCommerce.
		 * @param array $emails
		 *
		 * @return array
		 */
		public function add_custom_emails( $emails ) {
			$emails['WC_Bluesnap_Email_Chargeback_Order'] = include_once 'includes/class-wc-bluesnap-email-chargeback-order.php';
			return $emails;
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/woocommerce-bluesnap-gateway/woocommerce-bluesnap-gateway-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/woocommerce-bluesnap-gateway-LOCALE.mo
		 */
		private function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-bluesnap-gateway' );

			load_textdomain( 'woocommerce-bluesnap-gateway', WP_LANG_DIR . '/woocommerce-bluesnap-gateway/woocommerce-bluesnap-gateway-' . $locale . '.mo' );
			load_plugin_textdomain( 'woocommerce-bluesnap-gateway', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * @param string $image
		 *
		 * @return string
		 */
		public function images_url( $image = '' ) {
			return $this->plugin_url() . '/assets/images/' . $image;
		}

		/**
		 * Get the template path.
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'woocommerce_bluesnap_gateway_template_path', 'woocommerce-bluesnap-gateway/' );
		}

		/**
		 * Get Ajax URL.
		 * @return string
		 */
		public function ajax_url() {
			return admin_url( 'admin-ajax.php', 'relative' );
		}
	}

endif;
