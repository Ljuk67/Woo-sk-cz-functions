document.addEventListener( 'DOMContentLoaded', function() {
	document.querySelectorAll( '#mainform input[data-wscf-child-setting]' ).forEach( function( input ) {
		var row = input.closest( 'tr' );

		if ( row ) {
			row.classList.add( 'wscf-child-setting-row' );
		}
	} );
} );
