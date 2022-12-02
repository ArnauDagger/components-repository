(function( $ ) {
	'use strict';
	$( window ).load( function() {
		/**
		 * Button "X" in the components list onClick definition
		 * Function: Delete component from list and database
		 */
		$( '.component_listItem_delete').on( 'click', function() {
			const clicked_element = $( this ).parent( 'p' );
			let text = $( this ).attr( 'id' );
			const splitter = text.split( "_" );
			var id = splitter[1];
			var data = {
				'action': 'components_delete_item_at_database',
				'button_meta_id': id
			};
			// AJAX call to delete the component at the database table
			$.ajax({
				url: components_ajax_url,
				type: 'POST',
				dataType: "json",
				data: data,
				success: function(response){
					if( response.status == 1 ){
						console.log( "Se ha borrado correctamente el componente en la base de datos" );
						clicked_element.remove(); 
						console.log( "Se ha borrado correctamente el componente de la lista" );
					} else { alert( "DELETE ERROR: " + data.msg ); }
				}
			});

		});
		
	});
	/**
	 * All of the code for your Dashboard-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

})( jQuery );
