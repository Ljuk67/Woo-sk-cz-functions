( function() {
	function appendPrivacyPolicyLink( input ) {
		var privacyPolicyUrl;
		var linkText;
		var label;
		var link;
		var textContainer;


		if ( ! input || input.dataset.wscfPrivacyPolicyLinked === '1' ) {
			return;
		}		

		privacyPolicyUrl = input.dataset.wscfPrivacyPolicyUrl || '';
		linkText = input.dataset.wscfPrivacyPolicyLinkText || '';

		if ( ! privacyPolicyUrl || ! linkText ) {
			return;
		}

		label = input.closest( 'label' );

		if ( ! label ) {
			return;
		}

		textContainer = label.querySelector( '.wc-block-components-checkbox__label' );

		if ( ! textContainer || textContainer.querySelector( '.wscf-privacy-policy-link' ) ) {
			return;
		}

		link = document.createElement( 'a' );
		link.className = 'wscf-privacy-policy-link';
		link.href = privacyPolicyUrl;
		link.target = '_blank';
		link.rel = 'noopener';
		link.textContent = linkText;

		textContainer.appendChild( document.createTextNode( ' ' ) );
		textContainer.appendChild( link );
		input.dataset.wscfPrivacyPolicyLinked = '1';
	}

	function processPrivacyPolicyFields() {
		var fields = document.querySelectorAll( '[data-wscf-privacy-policy-field="1"]' );

		fields.forEach( appendPrivacyPolicyLink );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', processPrivacyPolicyFields );
	} else {
		processPrivacyPolicyFields();
	}

	new MutationObserver( processPrivacyPolicyFields ).observe( document.body, {
		childList: true,
		subtree: true
	} );
}() );
