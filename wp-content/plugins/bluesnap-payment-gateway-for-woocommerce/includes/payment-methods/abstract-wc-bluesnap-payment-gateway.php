<?php // @codingStandardsIgnoreLine
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract class that will be inherited by all payment methods.
 *
 * @extends WC_Abstract_Bluesnap_Payment_Gateway
 *
 * @since 1.0.0
 */
abstract class WC_Abstract_Bluesnap_Payment_Gateway extends WC_Payment_Gateway_CC {

	/**
	 * Notices (array)
	 * @var array
	 */
	public $notices = array();

	/**
	 * Is test mode active?
	 *
	 * @var bool
	 */
	public $testmode;

	/**
	 * Is logging active?
	 *
	 * @var bool
	 */
	public $logging;

	/**
	 * @var string
	 */
	public $api_username;

	/**
	 * @var string
	 */
	public $api_password;

	/**
	 * @var bool
	 */
	public $_3D_secure; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

	/**
	 * @var bool
	 */
	public $saved_cards;

	/**
	 * @var string
	 */
	public $fraud_id;

}

