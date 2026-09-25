/* Asaka — interactions légères du site public (menu mobile, sélecteur de devise). */
( function () {
	'use strict';

	function setMenu( button, open ) {
		var drawer = document.getElementById( button.getAttribute( 'aria-controls' ) );
		if ( ! drawer ) {
			return;
		}
		button.setAttribute( 'aria-expanded', String( open ) );
		drawer.hidden = ! open;
		button.querySelector( '[data-asa-menu-open]' ).hidden = open;
		button.querySelector( '[data-asa-menu-close]' ).hidden = ! open;
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-asa-menu]' );
		if ( button ) {
			setMenu( button, button.getAttribute( 'aria-expanded' ) !== 'true' );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}
		document.querySelectorAll( '[data-asa-menu][aria-expanded="true"]' ).forEach( function ( button ) {
			setMenu( button, false );
			button.focus();
		} );
	} );

	// Prix par pays de facturation : <div data-asa-prices='{"XOF":"…"}'> + <select data-asa-currency>.
	document.addEventListener( 'change', function ( event ) {
		var select = event.target.closest( '[data-asa-currency]' );
		var box = select && select.closest( '[data-asa-prices]' );
		if ( ! box ) {
			return;
		}
		var prices = JSON.parse( box.getAttribute( 'data-asa-prices' ) );
		var amount = box.querySelector( '[data-asa-amount]' );
		if ( amount && prices[ select.value ] ) {
			amount.textContent = prices[ select.value ];
		}
	} );
} )();
