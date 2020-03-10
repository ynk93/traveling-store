(function( $ ) {
	'use strict';

	// Multicurrency form submit
	$( '.bluesnap-multicurrency' ).on(
		'change', 'select.bluesnap_currency_selector', function() {
			$( this ).closest( 'form' ).submit();
		}
	);

	/**
	 * In case there is cache on the site, and backend hooks are not represented on frontend, we have to handle the currency changes on frontend level.
	 * @type {{cookie_name: *, init: init, get_cookie: (function(*): any), get_shown_currency_price: (function(): *), adjust_frontend: adjust_frontend}}
	 */
	var cookie_handler = {
		cookie_name: woocommerce_bluesnap_multicurrency_params.cookie_name,
		init: function() {
			var currency_cookie = this.get_cookie( this.cookie_name );
			var currency_shown  = this.get_shown_currency_price();
			if ( currency_cookie && currency_cookie != currency_shown ) {
				this.adjust_frontend( currency_cookie );
			}
		},
		get_cookie: function(name) {
			var v = document.cookie.match( '(^|;) ?' + name + '=([^;]*)(;|$)' );
			return v ? v[2] : null;
		},
		/**
		 * Get the first price shown, and takes it currency.
		 * @returns {string}
		 */
		get_shown_currency_price: function() {
			var first_price = $( '.currency-show' ).first();
			return $( first_price ).attr( 'currency' );
		},
		/**
		 * Adjust all frontend according to the real cookie value.
		 * @param currency
		 */
		adjust_frontend: function( currency ) {
			$( '.bluesnap-multicurrency-html' ).each(
				function(){
					if ( $( this ).attr( 'currency' ) == currency ) {
						$( this ).removeClass( 'currency-hide' ).addClass( 'currency-show' );
					} else {
						$( this ).removeClass( 'currency-show' ).addClass( 'currency-hide' );
					}
				}
			);
			$( ".bluesnap_currency_selector" ).val( currency );
		}
	};
	cookie_handler.init();

})( jQuery );
