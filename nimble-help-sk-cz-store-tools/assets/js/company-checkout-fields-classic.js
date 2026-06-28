jQuery( function( $ ) {
	function nimbleToggleCompanyFields() {
		var isCompany = $( '#billing_buying_as_company' ).is( ':checked' );

		if ( isCompany ) {
			$( '#billing_company_field' ).show();
			$( '#billing_ico_field' ).show();
			$( '#billing_dic_field' ).show();
			$( '#billing_ic_dph_field' ).show();
		} else {
			$( '#billing_company_field' ).hide();
			$( '#billing_ico_field' ).hide();
			$( '#billing_dic_field' ).hide();
			$( '#billing_ic_dph_field' ).hide();
		}
	}

	nimbleToggleCompanyFields();

	$( document.body ).on( 'change', '#billing_buying_as_company', function() {
		nimbleToggleCompanyFields();
	} );

	$( document.body ).on( 'updated_checkout', function() {
		nimbleToggleCompanyFields();
	} );
} );
