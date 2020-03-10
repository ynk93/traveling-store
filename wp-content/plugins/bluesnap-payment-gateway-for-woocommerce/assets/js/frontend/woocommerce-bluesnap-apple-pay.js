(function( $ ) {

	var wc_bluesnap_payment_request = {
		type: 'apple_pay',
		initialized: false,
		session: null, 
		init: function(){
			if( wc_bluesnap_payment_request.initialized ) {
				return;
			}
			if ( typeof window.ApplePaySession === 'undefined' ) {
				// Device doesn't support Apple Pay
				return;
			}

			var cartOrCheckout = $( 'form.woocommerce-cart-form, form.checkout' );
			if( ! cartOrCheckout.length ) {
				// not in cart or checkout
				return;
			}

			wc_bluesnap_payment_request.initialized = true;

			if( 0 == woocommerce_bluesnap_apple_pay_params.cart_compatible_apple_pay ) {
				wc_bluesnap_payment_request.showError( '<div class="woocommerce-info">' + woocommerce_bluesnap_apple_pay_params.i18n.apple_pay.not_compatible_with_cart + '</div>' );
				return;
			} 
			
			if ( ! wc_bluesnap_payment_request.cartCanWorkWithApplePay() ) {
				wc_bluesnap_payment_request.showError( '<div class="woocommerce-info">' + woocommerce_bluesnap_apple_pay_params.i18n.apple_pay.device_not_compat_with_cart + '</div>' );
				return;
			}

			if (ApplePaySession.canMakePayments() ) {
				$('<style></style>')
					.attr('id', 'wc-bluesnap-apple-pay-css')
					.html('#wc-bluesnap-apple-pay-wrapper, #wc-bluesnap-apple-pay-button-separator { display: block !important; }')
					.appendTo('body');

				$(document).on('click', '#wc-bluesnap-apple-pay-wrapper a.apple-pay-button', wc_bluesnap_payment_request.applePayClicked );
			} else {
				wc_bluesnap_payment_request.showError( '<div class="woocommerce-info">' + woocommerce_bluesnap_apple_pay_params.i18n.apple_pay.not_able_to_make_payments + '</div>' );
			}
		},
		cartCanWorkWithApplePay: function(e) {
			return window.ApplePaySession !== undefined && ApplePaySession.supportsVersion( parseInt( woocommerce_bluesnap_apple_pay_params.version_required ) );
		},
		applePayClicked: function(e) {
			e.preventDefault();
			if( wc_bluesnap_payment_request.session !== null ) {
				return;
			}

			wc_bluesnap_payment_request.blockForm();

			wc_bluesnap_payment_request.type = 'apple_pay';
			wc_bluesnap_payment_request.getRequestData().then(function(request){
				var apple_session = wc_bluesnap_payment_request.session = new ApplePaySession( woocommerce_bluesnap_apple_pay_params.version_required, request );

				apple_session.onvalidatemerchant = wc_bluesnap_payment_request.applePayValidateMerchant;

				apple_session.onshippingcontactselected = wc_bluesnap_payment_request.applePayUpdateShippingInfo;

				apple_session.onshippingmethodselected = wc_bluesnap_payment_request.applePayUpdateShippingMethod;

				apple_session.onpaymentauthorized = wc_bluesnap_payment_request.applePayPaymentAuthorized;

				apple_session.oncancel = wc_bluesnap_payment_request.handleCancel;

				apple_session.begin();
			}, function() {
				alert('Apple Pay could not be initialized. Try an alternate form of payment');
			});
		},
		applePayValidateMerchant: function (event) {
			$.ajax(
				{
					url: wc_bluesnap_payment_request.getAjaxUrl('create_apple_wallet'),
					method: "POST",
					dataType: 'json',
					data: {
						security: woocommerce_bluesnap_apple_pay_params.nonces.create_apple_wallet,
						validation_url: event.validationURL,
						payment_request_type: wc_bluesnap_payment_request.type
					},
				}
			).then(
				function ( res ) {
					if( ! res.success ) {
						wc_bluesnap_payment_request.handleError( res );
						return;
					}
					var decoded_token = window.atob( res.data.walletToken );
					wc_bluesnap_payment_request.session.completeMerchantValidation( JSON.parse( decoded_token ) );
				}, wc_bluesnap_payment_request.handleError
			);
		},
		applePayUpdateShippingInfo: function( event ) {
			var address = event.shippingContact;

			$.ajax(
				{
					url: wc_bluesnap_payment_request.getAjaxUrl('get_shipping_options'),
					method: "POST",
					dataType: 'json',
					data: {
						security: woocommerce_bluesnap_apple_pay_params.nonces.get_shipping_options,
						address:   window.btoa( JSON.stringify( address ) ),
						payment_request_type: wc_bluesnap_payment_request.type
					},
				}
			).then(
				function ( res ) {
					var data = res.data;
					if( ! res.success ) {
						var err = new ApplePayError("shippingContactInvalid", "postalAddress", data.message);
						wc_bluesnap_payment_request.session.completeShippingContactSelection({
							status: ApplePaySession.STATUS_FAILURE,
							errors: [ err ],
							newShippingMethods: [],
							newLineItems: data.lineItems,
							newTotal: data.total
						});
						return;
					}

					wc_bluesnap_payment_request.session.completeShippingContactSelection({
						status: ApplePaySession.STATUS_SUCCESS,
						newShippingMethods: data.shippingMethods,
						newLineItems: data.lineItems,
						newTotal: data.total
					});
				}, wc_bluesnap_payment_request.handleError
			);
		},
		applePayUpdateShippingMethod: function( event ) {
			var method = event.shippingMethod;

			$.ajax(
				{
					url: wc_bluesnap_payment_request.getAjaxUrl('update_shipping_method'),
					method: "POST",
					dataType: 'json',
					data: {
						security: woocommerce_bluesnap_apple_pay_params.nonces.update_shipping_method,
						method:   [ method.identifier ],
						payment_request_type: wc_bluesnap_payment_request.type
					},
				}
			).then(
				function ( res ) {
					var data = res.data;
					if( ! res.success ) {
						wc_bluesnap_payment_request.session.completeShippingMethodSelection({
							status: ApplePaySession.STATUS_FAILURE,
							newLineItems: data.lineItems,
							newTotal: data.total
						});
						return;
					}

					wc_bluesnap_payment_request.session.completeShippingMethodSelection({
						status: ApplePaySession.STATUS_SUCCESS,
						newLineItems: data.lineItems,
						newTotal: data.total
					});
				}, wc_bluesnap_payment_request.handleError
			);
		},
		applePayPaymentAuthorized: function (event) {
			var paymentToken = event.payment;
			//https://developers.bluesnap.com/docs/apple-pay#section-step-5-set-up-the-onpaymentauthorized-callback
			$.ajax(
				{
					url: wc_bluesnap_payment_request.getAjaxUrl('create_apple_payment'),
					method: "POST",
					dataType: 'json',
					data: {
						_wpnonce: woocommerce_bluesnap_apple_pay_params.nonces.checkout,
						payment_token: window.btoa( JSON.stringify( paymentToken ) ),
						payment_request_type: wc_bluesnap_payment_request.type
					},
				}
			).then(
				function (res) {
					if( typeof res.success !== 'undefined' && false === res.success ) {
						wc_bluesnap_payment_request.handleError( res );
						return;
					}
					if( typeof res.result !== 'undefined' ) {
						wc_bluesnap_payment_request.handleWCResponse( res );
						return;
					}
					console.log( res );
				}, wc_bluesnap_payment_request.handleError
			);
		},
		getAjaxUrl: function(method) {
			return woocommerce_bluesnap_apple_pay_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'bluesnap_' + method )
		},
		getRequestData: function() {
			var ret = $.Deferred();
			$.ajax(
				{
					url: wc_bluesnap_payment_request.getAjaxUrl('get_payment_request'),
					method: "POST",
					dataType: 'json',
					data: {
						security: woocommerce_bluesnap_apple_pay_params.nonces.get_payment_request,
						payment_request_type: wc_bluesnap_payment_request.type
					},
					async: false
				}
			).done( function( res ) {
				if( res.success ) {
					ret.resolve( res.data )
				} else {
					ret.reject();
				}
			}).fail(function() {
				ret.reject();
			});
			return ret.promise();
		},
		handleError: function(error) {
			switch( wc_bluesnap_payment_request.type ) {
				case 'apple_pay':
					wc_bluesnap_payment_request.session.completePayment( ApplePaySession.STATUS_FAILURE );
					break;
			}
			wc_bluesnap_payment_request.session = null;
			wc_bluesnap_payment_request.unblockForm();
			$( document.body ).trigger( 'update_checkout' );
		},
		handleSuccess: function() {
			switch( wc_bluesnap_payment_request.type ) {
				case 'apple_pay':
					wc_bluesnap_payment_request.session.completePayment( ApplePaySession.STATUS_SUCCESS );
					break;
			}
			wc_bluesnap_payment_request.session = null;
		},
		handleCancel: function() {
			switch( wc_bluesnap_payment_request.type ) {
				case 'apple_pay':
					break;
			}
			wc_bluesnap_payment_request.session = null;
			wc_bluesnap_payment_request.unblockForm();
			$( document.body ).trigger( 'update_checkout' );
		},
		handleWCResponse: function(result) {
			try {
				if ( 'success' === result.result ) {
					wc_bluesnap_payment_request.handleSuccess();

					if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
						window.location = result.redirect;
					} else {
						window.location = decodeURI( result.redirect );
					}
				} else if ( 'failure' === result.result ) {
					throw 'Result failure';
				} else {
					throw 'Invalid response';
				}
			} catch( err ) {
				// Reload page
				if ( true === result.reload ) {
					window.location.reload();
					return;
				}

				// Trigger update in case we need a fresh nonce
				if ( true === result.refresh ) {
					$( document.body ).trigger( 'update_checkout' );
				}

				// Add new errors
				if ( result.messages ) {
					wc_bluesnap_payment_request.showError( result.messages );
				} else {
					wc_bluesnap_payment_request.showError( '<div class="woocommerce-error">' + woocommerce_bluesnap_apple_pay_params.i18n.checkout_error + '</div>' );
				}


			}
		},
		getForm: function() {
			var form = $( 'form.woocommerce-checkout' );
			if( ! form.length ) {
				form = $( 'form#add_payment_method' );
			}
			if( ! form.length ) {
				form = $( 'form#order_review' );
			}
			if( ! form.length ) {
				form = $( 'form.woocommerce-cart-form' );
				if( form.length ) {
					form = form.closest(".woocommerce");
				}
			}
			return form;
		},
		blockForm: function() {
			wc_bluesnap_payment_request.getForm().addClass('processing').block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
		},
		unblockForm: function() {
			wc_bluesnap_payment_request.getForm().removeClass( 'processing' ).unblock();
		},
		showError: function( error_message ) {
			var form = wc_bluesnap_payment_request.getForm();
			if( ! form.length ) {
				console.error( error_message );
				return;
			}

			$( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove();
			form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>' );
			wc_bluesnap_payment_request.unblockForm();
			
			var scroll_element = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );
			if ( ! scroll_element.length ) {
				scroll_element = $( form );
			}
			$.scroll_to_notices( scroll_element );

			$( document.body ).trigger( 'checkout_error' );
		}
	}

	// On Checkout form.
	$( document.body ).on(
		'updated_checkout', function() {
			wc_bluesnap_payment_request.init();
		}
	);

	$(wc_bluesnap_payment_request.init); // on ready

})( jQuery );

//# sourceMappingURL=../../source/_maps/js/frontend/woocommerce-bluesnap-apple-pay.js.map
