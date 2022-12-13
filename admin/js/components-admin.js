(function( $ ) {
	'use strict';
	$( window ).load( function() {
		/**
		 * Components Inputs handling + AJAX call
		 * Function: Insert a component associated to the current product
		 */
		$( '#_component_insert' ).on( 'click', function(){
			var component_name = $( '#_component_name' );
			var component_item = $( '#_component_item' );
			var component_quantity = $( '#_component_quantity' );
			var component_sku = $( '#_component_sku' );
			var component_parent_id = jQuery("#post_ID").val();
			if(components_inputs_validate(component_name.val(),component_item.val(),
										  component_quantity.val(),component_sku.val()))
			{
				var data = {
					'action': 'components_insert_manage',
					'parent_id': component_parent_id,
					'name': component_name.val(),
					'item': component_item.val(),
					'quantity': component_quantity.val(),
					'sku': component_sku.val()
				};
	
				$.ajax({
					url: components_ajax_url,
					type: 'POST',
					dataType: "json",
					data: data,
					success: function(response){
						if( response.status == 6 ){
							console.log(response.msg);
							$( "#components_list" ).append("<p>Item: " + response.component_item + " | Descripción: " + response.component_name + " | Cantidad: " 
								+ response.component_quantity + " | Referencia: "
								+ response.component_sku + "<button type='button' id='component-meta-id_"
								+ response.component_meta_id + "' class='component_listItem_delete'></button></p>");
							var count_components = $( "#components_list" ).children().length + 1;
							component_name.val( "" );
							component_item.val( count_components );
							component_quantity.val( "1" );
							component_sku.val( "" );
						} else {
							alert( response.msg ); 
						}
					}
				});
			}
		} );
		
	});

	/**
	 * Validate INPUTS
	 * @param {string} 	name 		- Component's Name
	 * @param {int} 	item 		- Component's Item number
	 * @param {int} 	quantity 	- Component's quantity number
	 * @param {string} 	sku 		- Component's SKU number
	 * @returns bool - True on Success ; False on failure
	 */
	function components_inputs_validate(name,item,quantity,sku){
		var status = true;
		var errors = "";
		if(name == ""){
			errors += ("Error: Nombre del componente no introducido\n");
			status = false;
		}
		if(item < 0 || item == "" || item == 0){
			errors += ("Error: El número de Item del componente no es correcto\n");
			status = false;
		}
		if(quantity < 0 || quantity == ""){
			errors += ("Error: La cantidad del componente no es correcta\n");
			status = false;
		}
		if(sku == ""){
			errors += ("Error: Código SKU del componente no introducido\n");
			status = false;
		}
		if(errors != ""){
			alert(errors);
		}
		return status;
	}


	/**
	 * Button "X" in the components list onClick definition
	 * Function: Delete component from list and database
	 */
	$( document ).on( 'click', '.component_listItem_delete', function() {
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
					var count_components = $( "#components_list" ).children().length + 1;
					$( '#_component_item' ).val( count_components );
				} else { alert( "DELETE ERROR: " + data.msg ); }
			}
		});
	});

})( jQuery );