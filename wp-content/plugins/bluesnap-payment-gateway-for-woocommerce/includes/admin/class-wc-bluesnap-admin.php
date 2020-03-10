<?php
/**
 * WordPress Plugin Boilerplate Admin
 *
 * @class    WC_Bluesnap_Admin
 * @author   SAU/CAL
 * @category Admin
 * @package  Woocommerce_Bluesnap_Gateway/Admin
 * @version  1.3.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Bluesnap_Admin class.
 */
class WC_Bluesnap_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->includes();
		add_action( 'current_screen', array( $this, 'conditional_includes' ) );
		add_filter( 'plugin_action_links_' . WC_BLUESNAP_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 999, 2 );
		add_action( 'wp_ajax_bluesnap_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
		add_action( 'admin_notices', array( $this, 'show_notices' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once 'woocommerce-bluesnap-gateway-admin-functions.php';
		include_once 'class-wc-bluesnap-admin-assets.php';
	}

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		switch ( $screen->id ) {
			case 'dashboard':
			case 'options-permalink':
			case 'users':
			case 'user':
			case 'profile':
			case 'user-edit':
		}
	}

	/**
	 * Adds plugin action links.
	 */
	public function plugin_action_links( $links, $test ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=bluesnap' ) . '">' . esc_html__( 'Settings', 'woocommerce-bluesnap-gateway' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	public function get_admin_notices_dismissed() {
		$notices = get_user_meta( get_current_user_id(), 'bluesnap_admin_notices_dismissed', true );
		$notices = ! empty( $notices ) ? $notices : array();
		$notices = is_array( $notices ) ? $notices : array( $notices );
		return $notices;
	}

	public function is_admin_notice_active( $notice ) {
		$notices = $this->get_admin_notices_dismissed();
		return ! isset( $notices[ $notice ] );
	}

	public function dismiss_admin_notice() {
		$notice = $_GET['notice-id'];
		if ( empty( $notice ) ) {
			return;
		}

		$notices = $this->get_admin_notices_dismissed();
		$notices[ $notice ] = 1;
		update_user_meta( get_current_user_id(), 'bluesnap_admin_notices_dismissed', $notices );

		die( 1 );
	}

	public function show_notices() {
		$enabled = WC_Bluesnap()->get_option( 'enabled' ) === 'yes';
		if ( ! $enabled ) {
			return;
		}

		$testmode = 'yes' === WC_Bluesnap()->get_option( 'testmode' );

		if ( ! $testmode && ! wc_checkout_is_https() && $this->is_admin_notice_active( 'bluesnap-notice-https' ) ) {
			?>
			<div data-dismissible="bluesnap-notice-https" class="notice notice-warning is-dismissible">
				<?php /* translators: 1) link */ ?>
				<p><?php echo wp_kses_post( sprintf( __( 'Bluesnap is enabled, but a SSL certificate is not detected. Your checkout may not be secure! Please ensure your server has a valid <a href="%1$s" target="_blank">SSL certificate</a>', 'woocommerce-bluesnap-gateway' ), 'https://en.wikipedia.org/wiki/Transport_Layer_Security' ) ); ?></p>
			</div>
			<?php
		}
	}

}

return new WC_Bluesnap_Admin();
