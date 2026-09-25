/* Aperçu serveur des blocs dynamiques du thème dans l'éditeur (pas de build). */
( function ( blocks, element, ServerSideRender ) {
	[ 'asaka/site-header', 'asaka/site-footer' ].forEach( function ( name ) {
		blocks.registerBlockType( name, {
			edit: function () {
				return element.createElement( ServerSideRender, { block: name } );
			},
			save: function () {
				return null;
			},
		} );
	} );
} )( window.wp.blocks, window.wp.element, window.wp.serverSideRender );
