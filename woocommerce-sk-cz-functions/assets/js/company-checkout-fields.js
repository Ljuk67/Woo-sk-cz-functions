( function( $ ) {
	'use strict';

	var toggleSelector = '#billing_wscf_is_company';
	var companyFieldSelectors = '#billing_company_field, #billing_ico_field, #billing_dic_field, #billing_ic_dph_field';

	function updateCompanyFieldsVisibility() {
		var isCompany = $( toggleSelector ).is( ':checked' );
		var $companyFields = $( companyFieldSelectors );

		if ( ! $companyFields.length ) {
			return;
		}

		$companyFields.toggle( isCompany );

		if ( ! isCompany ) {
			$companyFields.find( 'input, textarea, select' ).val( '' ).trigger( 'change' );
		}
	}

	function bindEvents() {
		$( document.body ).on( 'change', toggleSelector, updateCompanyFieldsVisibility );
		$( document.body ).on( 'updated_checkout', updateCompanyFieldsVisibility );
	}

	$( function() {
		bindEvents();
		updateCompanyFieldsVisibility();
	} );
}( jQuery ) );
