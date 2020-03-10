(function( $ ) {
	'use strict';

	$(document).on('click', '.notice-dismiss', function(e) {
		var noticeID = $(this).closest('.notice').data('dismissible');
		if( !noticeID ) {
			return;
		}

		$.ajax({
			url: woocommerce_bluesnap_gateway_admin_params.ajax_url,
			data: {
				'action': 'bluesnap_dismiss_admin_notice',
				'notice-id': noticeID,
			}
		});
	});

})( jQuery );

//# sourceMappingURL=../../source/_maps/js/admin/woocommerce-bluesnap-gateway-admin.js.map
