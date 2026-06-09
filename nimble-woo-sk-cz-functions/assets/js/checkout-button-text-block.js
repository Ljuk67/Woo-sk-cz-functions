( function() {
	var attempts = 0;
	var maxAttempts = 20;
	var extensionName = 'nimble-woo-sk-cz-functions';
	var settings = window.wscfCheckoutButtonText || {};

	function registerFilter() {
		var blocksCheckout = window.wc && window.wc.blocksCheckout;

		if ( ! blocksCheckout || ! blocksCheckout.registerCheckoutFilters ) {
			attempts += 1;

			if ( attempts < maxAttempts ) {
				window.setTimeout( registerFilter, 100 );
			}

			return;
		}

		blocksCheckout.registerCheckoutFilters( extensionName, {
			placeOrderButtonLabel: function( value ) {
				return settings.text || value;
			}
		} );
	}

	registerFilter();
}() );
