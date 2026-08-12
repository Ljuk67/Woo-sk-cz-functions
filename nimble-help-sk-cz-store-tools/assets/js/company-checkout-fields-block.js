( function() {
	function getFieldWrapper( input ) {
		if ( ! input ) {
			return null;
		}

		return input.closest( '.wc-block-components-text-input, .wc-block-components-checkbox, .wc-block-components-select' );
	}

	function removeStatusElements( wrapper ) {
		var indicators;

		if ( ! wrapper ) {
			return;
		}

		indicators = wrapper.querySelectorAll(
			'span.optional, span.required, abbr.optional, abbr.required, small.optional, small.required, span[class*="optional"], span[class*="required"], abbr[class*="optional"], abbr[class*="required"], small[class*="optional"], small[class*="required"]'
		);

		indicators.forEach( function( indicator ) {
			if ( indicator.closest( '.wscf-company-toggle-helper' ) ) {
				return;
			}

			indicator.remove();
		} );
	}

	function removeTrailingStatusText( wrapper ) {
		var labels;

		if ( ! wrapper ) {
			return;
		}

		labels = wrapper.querySelectorAll( 'label, .wc-block-components-text-input__label, .wc-block-components-checkbox__label' );

		labels.forEach( function( label ) {
			label.childNodes.forEach( function( node ) {
				if ( Node.TEXT_NODE !== node.nodeType ) {
					return;
				}

				node.textContent = node.textContent.replace( /\s*\([^()]*\)\s*$/, '' );
			} );
		} );
	}

	function removeCompanyFieldStatusLabels( wrapper ) {
		removeStatusElements( wrapper );
		removeTrailingStatusText( wrapper );
	}

	function appendCompanyHelperText( toggleInput ) {
		var label;
		var textContainer;
		var helperText;
		var helper;

		if ( ! toggleInput || '1' === toggleInput.dataset.wscfCompanyHelperBound ) {
			return;
		}

		helperText = toggleInput.dataset.wscfCompanyHelperText || '';

		if ( ! helperText ) {
			return;
		}

		label = toggleInput.closest( 'label' );

		if ( ! label ) {
			return;
		}

		textContainer = label.querySelector( '.wc-block-components-checkbox__label' );

		if ( ! textContainer || textContainer.querySelector( '.wscf-company-toggle-helper' ) ) {
			return;
		}

		helper = document.createElement( 'span' );
		helper.className = 'wscf-company-toggle-helper';
		helper.textContent = helperText;
		helper.style.display = 'block';
		helper.style.marginTop = '4px';
		helper.style.fontSize = '0.875em';
		helper.style.opacity = '0.8';

		textContainer.appendChild( helper );
		toggleInput.dataset.wscfCompanyHelperBound = '1';
	}

	function toggleCompanyFields( toggleInput ) {
		var isCompany = !! toggleInput && toggleInput.checked;
		var fields = document.querySelectorAll( '[data-wscf-company-dependent="1"]' );

		fields.forEach( function( field ) {
			var wrapper = getFieldWrapper( field );
			var isRequired = '1' === field.dataset.wscfCompanyRequired;

			if ( wrapper ) {
				wrapper.style.display = isCompany ? '' : 'none';
				removeCompanyFieldStatusLabels( wrapper );
			}

			field.required = isCompany && isRequired;
			field.setAttribute( 'aria-required', field.required ? 'true' : 'false' );
		} );
	}

	function bindToggle( toggleInput ) {
		if ( ! toggleInput || '1' === toggleInput.dataset.wscfCompanyToggleBound ) {
			return;
		}

		toggleInput.addEventListener( 'change', function() {
			toggleCompanyFields( toggleInput );
		} );

		toggleInput.dataset.wscfCompanyToggleBound = '1';
	}

	function processCompanyFields() {
		var toggleInput = document.querySelector( '[data-wscf-company-toggle="1"]' );

		if ( ! toggleInput ) {
			return;
		}

		bindToggle( toggleInput );
		appendCompanyHelperText( toggleInput );
		toggleCompanyFields( toggleInput );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', processCompanyFields );
	} else {
		processCompanyFields();
	}

	new MutationObserver( processCompanyFields ).observe( document.body, {
		childList: true,
		subtree: true
	} );
}() );
