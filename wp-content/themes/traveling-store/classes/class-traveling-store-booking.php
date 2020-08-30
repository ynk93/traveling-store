<?php
	/**
	 * Traveling Store booking class.
	 *
	 * @package Traveling Store
	 */

	defined( 'ABSPATH' ) || exit;

	class Traveling_Store_Booking {

		/**
		 * Hook in methods.
		 */
		public static function init() {
//			add_action( 'wp_loaded', array( __CLASS__, 'update_cart_action' ), 20 );
//			add_action( 'template_redirect', array( __CLASS__, 'save_account_details' ) );
		}

		public static function createBookingTable() {



		}

	}

	Traveling_Store_Booking::init();