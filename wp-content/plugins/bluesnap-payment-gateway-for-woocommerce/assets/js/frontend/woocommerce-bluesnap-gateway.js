(function( $ ) {
	'use strict';

	var card_thumbs = {
		'AmericanExpress': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/amex.png',
		'DinersClub': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/diners.png',
		'Discover': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/discover.png',
		'JCB': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/jcb.png',
		'MaestroUK': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/maestro.png',
		'MasterCard': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/mastercard.png',
		'Visa': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/visa.png',
		'Solo': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/solo.png',
		'CarteBleue': woocommerce_bluesnap_gateway_general_params.domain + '/services/hosted-payment-fields/cc-types/cb.png',
		'generic' : woocommerce_bluesnap_gateway_general_params.generic_card_url
	};

	// Holds all validation messages from Bluesnap.
	var validation_messages = {};

	var clear_token_fn = debounce(function(){
		$.ajax(
			{
				url: getAjaxUrl('clear_hpf'),
				method: "POST",
			}
		).always(function(){
			$( document.body ).trigger( 'update_checkout' );
		});
	}, 100);

	var bsObj = {
		onFieldEventHandler: {
			onFocus: function(tagId) {
				$( "[data-bluesnap='" + tagId + "']" ).removeClass('input-div-error input-div-valid');
			},
			onBlur: function(tagId) {
			},
			onError: function(tagId, errorCode, errorDescription) {
				switch( errorCode + '' ) {
					case '14040':
						clear_token_fn();
						break;
				}
				$( "[data-bluesnap='" + tagId + "']" ).addClass('input-div-error').removeClass('input-div-valid');
				validation_messages[errorCode] = {
					tagId: tagId,
					message: woocommerce_bluesnap_gateway_general_params.errors[errorCode]
				};
				submit_error_efficient();
			},
			onType: function(tagId, cardType) {
				var card_url = card_thumbs[cardType];
				$( "#card-logo > img" ).attr( "src", (card_url) ? card_url : card_thumbs['generic'] );
			},
			onValid: function(tagId) {
				$( "[data-bluesnap='" + tagId + "']" ).removeClass('input-div-error').addClass('input-div-valid');
				wc_bluesnap_form.get_form().find( '.woocommerce-NoticeGroup-checkout[data-bluesnap-tag="' + tagId + '"]' ).remove();
				$.each(validation_messages, function(errorCode, data) {
					if(data.tagId === tagId) {
						delete validation_messages[errorCode];
					}
				});
			},
		},
		style: {
			".invalid": {
				"color": "red"
			},
			":focus": {
				"color": "inherit"
			}
		},
		ccnPlaceHolder: "•••• •••• •••• ••••",
		cvvPlaceHolder: "CVC",
		expDropDownSelector: false,
		'3DS': is_3d_secure()
	};

	// On Checkout form.
	$( document.body ).on(
		'updated_checkout', function() {
			// If is bluesnap method checked, init it.
			if (wc_bluesnap_form.is_bluesnap_selected()) {
				wc_bluesnap_form.init_bluesnap( 'updated_checkout' );
			}
		}
	);

	// On Add Payment Method form.
	$( 'form#add_payment_method' ).on(
		'click payment_methods', function() {
			if (wc_bluesnap_form.is_bluesnap_selected()) {
				wc_bluesnap_form.init_bluesnap();
			}
		}
	);

	// On Pay for order form.
	$( 'form#order_review' ).on(
		'click', function() {
			if (wc_bluesnap_form.is_bluesnap_selected()) {
				wc_bluesnap_form.init_bluesnap();
			}
		}
	);

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};

	function getAjaxUrl(method) {
		return woocommerce_bluesnap_gateway_general_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'bluesnap_' + method )
	}

	function clear_errors() {
		var errors = wc_bluesnap_form.get_form().find( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' );
		var code = {};
		errors.filter("[data-bluesnap-error]").each(function(){
			code[$(this).data('bluesnap-error')] = true;
		})
		errors.remove();
		return code;
	}

	function submit_error() {
		var codes = clear_errors();
		var scroll = false;
		$.each(
			validation_messages, function(errorCode, data) {
				if( typeof codes[errorCode] === "undefined" ) {
					scroll = true;
				}
				wc_bluesnap_form.get_form().prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout" data-bluesnap-error="' + errorCode + '" data-bluesnap-tag="' + data.tagId + '"><div class="woocommerce-error">' + data.message + '</div></div>' );
			}
		);
		wc_bluesnap_form.get_form().removeClass( 'processing' ).unblock();
		wc_bluesnap_form.get_form().find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).blur();
		if( scroll ) {
			scroll_to_notices();
		}
		$( document.body ).trigger( 'checkout_error' );
	}

	var submit_error_efficient = debounce(submit_error, 100);

	function scroll_to_notices() {
		var scroll_element = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );
		if ( ! scroll_element.length ) {
			scroll_element = $( '.form.checkout' );
		}
		$.scroll_to_notices( scroll_element );
	}

	/**
	 * Object to handle bluesnap wc forms.
	 * @type {{input_card_info_id: string, $checkout_form: (*|HTMLElement), $add_payment_form: (*|HTMLElement), credentials_requested: boolean, form: boolean, init: init, on_change: on_change, is_bluesnap_selected: (function(): *), init_bluesnap: init_bluesnap, on_bluesnap_selected: on_bluesnap_selected, secure_3d_object: (function()), submit_credentials: (function(): boolean), success_callback: success_callback, error_callback: error_callback, submit_form: submit_form, block_form: block_form, unblock_form: unblock_form, get_form: (function(): (*|HTMLElement))}}
	 */
	var wc_bluesnap_form = {
		input_card_info_id : '#bluesnap_card_info',
		$checkout_form: $( 'form.woocommerce-checkout' ),
		$add_payment_form: $( 'form#add_payment_method' ),
		$order_review_form: $( 'form#order_review' ),
		credentials_requested: false,
		is_checkout: false,
		is_order_review_form: false,
		form: null,
		submitted: false,
		init_bluesnap_counter: 0,
		force_submit: false, 
		/**
		 * Interrupts normal checkout flow. Delay submission.
		 */
		init: function() {
			// Checkout Page
			if ( this.$checkout_form.length ) {
				this.form        = this.$checkout_form;
				this.is_checkout = true;
				this.form.on( 'checkout_place_order', this.submit_credentials );
			}

			// Add payment Page
			if ( this.$add_payment_form.length ) {
				this.form                  = this.$add_payment_form;
				this.is_add_payment_method = true;
				this.form.on( 'add_payment_method', this.submit_credentials );
				this.$add_payment_form.on( 'submit', this.add_payment_method_trigger );
			}

			// Pay for order Page (change subs method)
			if ( this.$order_review_form.length ) {
				this.form                 = this.$order_review_form;
				this.is_order_review_form = true;
				this.form.on( 'add_payment_method', this.submit_credentials );
				this.$order_review_form.on( 'submit', this.add_payment_method_trigger );
			}

			// tokenization script initiation for change payment method page and pay for order page.
			if (this.is_add_payment_method || this.is_order_review_form) {
				$( document.body ).trigger( 'wc-credit-card-form-init' );
			}

			if (this.form) {
				this.form.on( 'change', this.on_change );
			}

			$('body').on('click', function(e) {
				var $target = $( e.target );
				if( $target.parents().andSelf().filter('.bluesnap-cvc-tooltip-trigger').length ) {
					return;
				}
				var $tooltips = $('.bluesnap-cvc-tooltip');
				var $thisParents = $target.parents( $tooltips );
				$tooltips.not( $thisParents ).removeClass('bs-tooltip-visible')
			});
		},
		/**
		 * Triggered on form changes.
		 */
		on_change: function() {
			$( "[name='payment_method']" ).on(
				'change', function() {
					wc_bluesnap_form.on_bluesnap_selected();
				}
			);
		},
		/**
		 * Checks if input bluesnap is checked.
		 * @returns bool
		 */
		is_bluesnap_selected: function() {
			return $( '#payment_method_bluesnap' ).is( ":checked" );
		},
		is_bluesnap_saved_token_selected: function() {
			return ( $( '#payment_method_bluesnap' ).is( ':checked' ) && ( $( 'input[name="wc-bluesnap-payment-token"]' ).is( ':checked' ) && 'new' !== $( 'input[name="wc-bluesnap-payment-token"]:checked' ).val() ) );
		},
		/**
		 * Trigger BlueSnap authentication.
		 * @param event
		 */
		init_bluesnap: function( event ) {
			// Only add attribute first time we show the form
			if (is_3d_secure() && this.init_bluesnap_counter === 0) {
				$( "[name='woocommerce_checkout_place_order']" ).attr( 'data-bluesnap', 'submitButton' );
			}

			if ( ! this.credentials_requested || 'updated_checkout' === event) {
				bluesnap.hostedPaymentFieldsCreation( woocommerce_bluesnap_gateway_general_params.token, bsObj );
				this.credentials_requested = true;
			}

			$('.bluesnap-cvc-tooltip-trigger').not('.bs-tooltip-initalized').on('click', function(e){
				e.preventDefault();
				$(this).next('.bluesnap-cvc-tooltip').toggleClass('bs-tooltip-visible');
			});
			// init_bluesnap can be triggered multiple times.
			this.init_bluesnap_counter++;
		},
		/**
		 * Triggered when BlueSnap is selected from the payment list.
		 */
		on_bluesnap_selected: function() {
			if (wc_bluesnap_form.is_bluesnap_selected()) {
				wc_bluesnap_form.init_bluesnap();
			} else {
				$( "[name='woocommerce_checkout_place_order']" ).removeAttr( 'data-bluesnap' );
			}
		},

		secure_3d_field: function(id) {
			var field = $( '#' + id );
			if( field.length && $.trim( field.val() ).length ) {
				return field.val();
			} else if (typeof woocommerce_bluesnap_gateway_general_params[id] !== 'undefined' ) {
				return woocommerce_bluesnap_gateway_general_params[id];
			} else {
				return '';
			}
		},
		/**
		 * 3D Secure Object with transaction data.
		 */
		secure_3d_object: function() {
			var threeDSecureObj = {};
			if (is_3d_secure()) {
				threeDSecureObj = {
					'amount' : parseFloat( woocommerce_bluesnap_gateway_general_params.total_amount ),
					'currency' : woocommerce_bluesnap_gateway_general_params.currency,
					'billingFirstName' : wc_bluesnap_form.secure_3d_field( 'billing_first_name' ),
					'billingLastName' : wc_bluesnap_form.secure_3d_field( 'billing_last_name' ),
					'billingCountry' : wc_bluesnap_form.secure_3d_field( 'billing_country' ),
					'billingState' : '',
					'billingCity' : wc_bluesnap_form.secure_3d_field( 'billing_city' ),
					'billingAddress' : $.trim( wc_bluesnap_form.secure_3d_field( 'billing_address_1' ) + ' ' + wc_bluesnap_form.secure_3d_field( 'billing_address_2' ) ),
					'billingZip' : wc_bluesnap_form.secure_3d_field( 'billing_postcode' ),
					'email' : wc_bluesnap_form.secure_3d_field( 'billing_email' ),
				};

				switch( threeDSecureObj.billingCountry ) {
					case 'US':
					case 'CA':
						threeDSecureObj.billingState = wc_bluesnap_form.secure_3d_field( 'billing_state' );
						break;
				}
			}
			return threeDSecureObj;
		},
		/**
		 * Submitting Credentials to BlueSnap.
		 * @returns {boolean}
		 */
		submit_credentials: function(e) {
			if( wc_bluesnap_form.force_submit ) {
				return;
			}

			e.preventDefault();

			if ( ! wc_bluesnap_form.is_bluesnap_selected()) {
				return;
			}

			if (wc_bluesnap_form.is_order_review_form && wc_bluesnap_form.is_bluesnap_saved_token_selected()) {
				wc_bluesnap_form.submit_form();
			}

			wc_bluesnap_form.block_form();
			clear_errors();

			if ( wc_bluesnap_form.is_bluesnap_saved_token_selected() ) {
				return true;
			}

			bluesnap.submitCredentials(
				function(res) {
					if (res.cardData) {
						wc_bluesnap_form.success_callback( res.cardData );
					} else if( res.error ) {
						var errorArray = res.error;
						for (var i in errorArray) {
							bsObj.onFieldEventHandler.onError.apply(bsObj.onFieldEventHandler, [errorArray[i].tagId, errorArray[i].errorCode, errorArray[i].errorDescription])
						}
					} else {
						wc_bluesnap_form.error_callback();
					}
				}, wc_bluesnap_form.secure_3d_object()
			);
			validation_messages = {};
			return false;
		},
		add_payment_method_trigger: function() {
			if( wc_bluesnap_form.force_submit ) {
				return;
			}
			
			wc_bluesnap_form.form.trigger( 'add_payment_method' );
			return false;
		},
		/**
		 * Callback on Success to set credentials into checkout form.
		 * @param data
		 */
		success_callback : function( data ) {
			$( this.input_card_info_id ).val( JSON.stringify( data ) );
			wc_bluesnap_form.submit_form();
		},
		/**
		 * Callback on Failure.
		 * Submit formal as normal and reset it.
		 */
		error_callback: function() {
			wc_bluesnap_form.submit_form();
			wc_bluesnap_form.unblock_form();
		},
		/**
		 * Deactivate submit_credentials function event and submit the form again.
		 */
		submit_form: function() {
			wc_bluesnap_form.force_submit = true;
			wc_bluesnap_form.form.submit();
			wc_bluesnap_form.force_submit = false;
		},
		block_form: function() {
			wc_bluesnap_form.form.block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
		},
		unblock_form: function() {
			wc_bluesnap_form.form.unblock();
		},
		/**
		 * Form Element getter.
		 * @returns {*|HTMLElement}
		 */
		get_form: function() {
			return this.form;
		}
	};

	$(
		function() {
				wc_bluesnap_form.init();
		}
	);

	/**
	 * @returns {boolean}
	 */
	function is_3d_secure() {
		return ! ! + woocommerce_bluesnap_gateway_general_params._3d_secure;
	}

	/**
	 * @returns {boolean}
	 */
	function is_sandbox() {
		return ! ! + woocommerce_bluesnap_gateway_general_params.is_sandbox;
	}

})( jQuery );

//# sourceMappingURL=../../source/_maps/js/frontend/woocommerce-bluesnap-gateway.js.map
