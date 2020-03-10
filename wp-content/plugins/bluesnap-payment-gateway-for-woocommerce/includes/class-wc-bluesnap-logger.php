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
 * BlueSnap Logger
 *
 * Class WC_Bluesnap_Logger
 */
class WC_Bluesnap_Logger {

	public static $logger;

	/**
	 * Always log errors, debug only when is on the settings.
	 *
	 * @param $message
	 * @param string $level
	 */
	public static function log( $message, $level = 'debug', $file = null ) {

		$logging = ( 'error' === $level ) ? 'yes' : false;
		$logging = ( $logging ) ? $logging : WC_Bluesnap()->get_option( 'logging' );
		if ( empty( $logging ) || 'yes' !== $logging ) {
			return;
		}

		if ( ! self::$logger ) {
			self::$logger = wc_get_logger();
		}

		$handler = array( 'source' => ! empty( $file ) ? 'bluesnap-' . $file . '-logs' : 'bluesnap-logs' );

		self::$logger->log( $level, $message, $handler );
	}

	/**
	 * Logger for requests, unset auth to don't compromise data.
	 *
	 * @param $url
	 * @param $args
	 * @param string $level
	 */
	public static function log_request( $url, $args, $level = 'debug' ) {
		unset( $args['headers'] );
		$method = isset( $args['method'] ) ? $args['method'] : 'POST';
		$data = ! empty( $args['body'] ) ? $args['body'] : '--- EMPTY STRING ---';
		self::log( "{$method} Request: " . $url . "\n\n" . $data . "\n", $level );
	}

	/**
	 * Logger for responses.
	 *
	 * @param $url
	 * @param $args
	 * @param string $level
	 */
	public static function log_response( $response, $level = 'debug' ) {
		$data = $response['http_response']->get_response_object()->raw;
		self::log( 'Response: ' . "\n\n" . $data . "\n", $level );
	}

}

