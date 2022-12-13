<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    components
 * @subpackage components/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    components
 * @subpackage components/admin
 * @author     Arnau <arnaudagger@gmail.com>
 */
class Components_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Components_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Components_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/components-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Components_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Components_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/components-admin.js', array( 'jquery' ), $this->version, FALSE );
		wp_localize_script( $this->name, "components_ajax_url", array( admin_url( "admin-ajax.php" ) ) );

	}

	/**
	 * Add a custom tab to the Products Data metabox
	 *
	 * @since    1.0.0
	 */
	function components_add_tab( $components_data_tab ) {
		$components_data_tab['components-tab'] = array(
			'label' => __( 'Component', 'my_text_domain' ),
			'target' => 'components_data',
		);
		return $components_data_tab;
	}


	/**
	 * Add custom fields to the added Components tab under Components metabox
	 *
	 * @since    1.0.0
	 */
	function components_create_tab(){
		global $woocommerce, $post;
		
		?>
		<div id="components_data" class="panel woocommerce_options_panel">
				<?php
				woocommerce_wp_text_input( 
					array( 
						'id'          => '_component_name', 
						'name'		  => 'component_name',
						'label'       => __( 'Descripción', 'woocommerce' ), 
						'desc_tip'    => 'true',
						'description' => __( 'Introduce el nombre completo del componente', 'woocommerce' )
					)
				);
				woocommerce_wp_text_input( 
					array( 
						'id'          			=> '_component_item', 
						'name' 					=> 'component_item',
						'label'       			=> __( 'Item', 'woocommerce' ), 
						'desc_tip'    			=> 'true',
						'value' 				=> '',
						'description' 			=> __( 'Introduce el número de ítem de este componente', 'woocommerce' ),
						'type'		  			=> 'number',
						'custom_attributes' 	=> array(
													'step' => 'any',
													'min'  => '1'
													)
					)
				);
				woocommerce_wp_text_input( 
					array( 
						'id'          			=> '_component_quantity', 
						'name' 					=> 'component_quantity',
						'label'       			=> __( 'Cantidad', 'woocommerce' ), 
						'desc_tip'    			=> 'true',
						'value' 				=> '1',
						'description' 			=> __( 'Cantidad total presente de este componente en el recambio', 'woocommerce' ),
						'type'		  			=> 'number',
						'custom_attributes' 	=> array(
													'step' => 'any',
													'min'  => '1'
													)
					)
				);
				woocommerce_wp_text_input( 
					array( 
						'id'          => '_component_sku', 
						'name'		  => 'component_sku',
						'label'       => __( 'SKU', 'woocommerce' ), 
						'desc_tip'    => 'true',
						'description' => __( 'La referencia SKU de este componente', 'woocommerce' )
					)
				);
				?>
				<button class="button button-large" id="_component_insert" name="component_insert" type="button" style="margin-left: 10px;">Añadir Componente</button>
			<hr>
			<div id="components_list" style="padding:5px 20px 5px 13px;">
			<?php
				/**
				 * Dynamically generate the components list by taking all the entries asociated to the actual
				 * product page ID inside the database.
				 */
				$components_array = self::components_initialize_list($post->ID);
				if ( $components_array ){
					foreach ( $components_array as $component ){
						echo "<p>Item: " . $component['item'] . " | Descripción: " . $component['component_name'] . " | Cantidad: " . $component['quantity'] . " | Referencia: "
						 . $component['component_sku'] . "<button type='button' id='component-meta-id_" . $component['meta_id'] . "' class='component_listItem_delete'></button></p>";
					}
				}	
				?>
			</div>
		</div>
	<?php
	}

	/**
	 * Gets all the components registered in the datrabase for the current product
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    int        $parent_id    The id of the current product page
	 * @return   array      $result       The list of all the components related to the actual product
	 */
	private static function components_initialize_list($parent_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'components';
		$result = $wpdb->get_results( "SELECT * FROM $table_name WHERE parent_id = $parent_id ORDER BY item ASC", ARRAY_A );
		return $result;
	}

	/**
	 * Handles AJAX call to delete a component
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      int    $meta_id    The ID of the row in the database to be deleted
	 * @return   array  $response   The result of the function to be passed to the jQuery AJAX caller
	 */
	public function components_delete_item_at_database(){
		$meta_id = isset( $_REQUEST['button_meta_id'] ) ? $_REQUEST['button_meta_id'] : "";
		$response = array( 'status'=>4,'msg'=>'Parametro invalido' );
		if( !empty( $meta_id ) ){
			global $wpdb;
			$table_name = $wpdb->prefix . 'components';
			$status = $wpdb->delete(
				$table_name,
				array(
					'meta_id' => $meta_id
				)
			);
			if( $status ) {
				$response = array( 'status'=>1,'msg'=>'Se ha borrado correctamente el componente en la base de datos' );
			} else {
				$response = array( 'status'=>2,'msg'=>'Ha ocurrido un error al borrar el componente en la base de datos' );
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Handles AJAX call to insert a component
	 * @since 1.0.0
	 * @access public
	 * @var      int    	$parent_id    	The product id number (Parent product in which the components will be associated)
	 * @var      string     $name    		The component's name
	 * @var      int    	$item    		The component's item number
	 * @var      int    	$quantity    	The component's quantity number
	 * @var      string     $sku    		The component's SKU code
	 * @var		 int		$meta_id		The ID of the row in the database that associates the product with the component.
	 * @return   array  	$response   	The result of the function to be passed to the jQuery AJAX caller
	 */
	public function components_insert_manage(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'components';
		$parent_id = isset( $_REQUEST['parent_id'] ) ? $_REQUEST['parent_id'] : "";
		$name = isset( $_REQUEST['name'] ) ? $_REQUEST['name'] : "";
		$item = isset( $_REQUEST['item'] ) ? $_REQUEST['item'] : "";
		$quantity = isset( $_REQUEST['quantity'] ) ? $_REQUEST['quantity'] : "";
		$sku = isset( $_REQUEST['sku'] ) ? $_REQUEST['sku'] : "";
		$response = array( 'status'=>1,'msg'=>'Parametro invalido' );
		// CHECK IF A COMPONENT WITH SAME ITEM NUMBER IS ALREADY IN DATABASE
		$result = $wpdb->get_row( "SELECT EXISTS (SELECT 1 FROM $table_name 
									WHERE parent_id=$parent_id AND 
									item=$item 
									);", ARRAY_N );
		$result_num = $result[0];
		// ITEM NUMBER DOES NOT EXIST IN DATABASE
		if($result_num == 0){
			$table_postmeta = $wpdb->prefix . 'postmeta';
			// GET COMPONENT PRODUCT_ID FROM DATABASE
			$component_id = $wpdb->get_var('SELECT post_id FROM '. $table_postmeta .' WHERE meta_key="_sku" AND meta_value="'. $sku .'";');
			// COMPONENT EXISTS, PROCEEDING TO INSERT THE COMPONENT INTO THE CURRENT PRODUCT
			if( isset($component_id) ){
				$dataToInsert = array( 'parent_id'  =>  $parent_id, 'component_id'		=>	$component_id,
									   'item'	  	=>	$item,		'component_name'	=>	$name,
								       'quantity'	=>	$quantity,	'component_sku'		=>  $sku
				);
				$insert = $wpdb->insert( $table_name, $dataToInsert, array( '%d', '%d', '%d', '%s', '%d', '%s' )  );
				// INSERT SUCCESS
				if( isset( $insert ) ){
					$response = array( 'status'=>5,'msg'=>'Éxito: Componente insertado satisfactoriamente');
					// GET META_ID TO RETURN IT TO THE JQUERY FILE TO ADD IT TO THE LIST
					$meta_id = $wpdb->get_var('SELECT meta_id FROM '. $table_name .' WHERE parent_id='.$parent_id.' 
												AND component_id='.$component_id.' AND item='.$item.' 
												AND quantity='.$quantity.' 
												AND component_sku="'.$sku.'" 
												AND component_name="'.$name.'";'
					);
					// GET META_ID SUCCESSFUL, PASSING VALUES TO JQUERY
					if( isset( $meta_id ) ){
						$response = array(  'status'				=>	6,
											'msg'					=>	'Éxito: Componente insertado', 
											'component_item'		=>	$item,
											'component_name'		=>	$name,
											'component_quantity'	=>	$quantity,
											'component_sku'			=>	$sku,
											'component_meta_id'		=>	$meta_id
						);
					} 
					// GET META_ID FAILED
					else{ $response = array( 'status'=>7,'msg'=>'Error: No se ha podido obtener el meta_id'); }
					
				} 
				// INSERT FAILS
				else {
					$response = array( 'status'=>4,'msg'=>'Error al insertar el componente');
				}
			} 
			// COMPONENT DOES NOT EXIST
			else {
				$response = array( 'status'=>3,'msg'=>'Error: Este componente con código SKU: '. $sku .' ; No existe. Crea primero el producto del componente para poder asociarlo.');
				
			}
		}
		//ITEM NUMBER ALREADY EXISTS IN DATABASE 
		else{ $response = array( 'status'=>2,'msg'=>'Error: Ya existe un componente con número de Item: ' . $item ); }
		
		echo json_encode( $response );
		wp_die();
	}

}