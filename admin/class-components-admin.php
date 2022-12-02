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
		wp_localize_script( $this->name, "components_ajax_url", admin_url( "admin-ajax.php" ) );

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
					'label'       => __( 'Descripción', 'woocommerce' ), 
					'desc_tip'    => 'true',
					'description' => __( 'Introduce el nombre completo del componente', 'woocommerce' )
				)
			);
			woocommerce_wp_text_input( 
				array( 
					'id'          			=> '_component_item', 
					'label'       			=> __( 'Item', 'woocommerce' ), 
					'desc_tip'    			=> 'true',
					'value' 				=> '1',
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
					'label'       => __( 'SKU', 'woocommerce' ), 
					'desc_tip'    => 'true',
					'description' => __( 'La referencia SKU de este componente', 'woocommerce' )
				)
			);
			?>
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
	 * Handles AJAX call to delete a component
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      int    $meta_id    The meta_id number from the component to be deleted
	 * @var      array  $response   The result of the function to be passed to the jQuery AJAX caller
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
			if($status) {
				$response = array( 'status'=>1,'msg'=>'Se ha borrado correctamente el componente en la base de datos' );
			} else {
				$response = array( 'status'=>2,'msg'=>'Ha ocurrido un error al borrar el componente en la base de datos' );
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Gets all the components registered in the datrabase for the current product
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    int        $parent_id    The id of the current product page
	 * @var      array      $result       The list of all the components related to the actual product
	 */
	private static function components_initialize_list($parent_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'components';
		$result = $wpdb->get_results( "SELECT * FROM $table_name WHERE parent_id = $parent_id ORDER BY item ASC", ARRAY_A );
		return $result;
	}

}


