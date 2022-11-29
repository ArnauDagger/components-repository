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
		<div id="components_data" class="panel woocommerce_options_panel" style="border-bottom: 1px solid #eee;">
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
					'placeholder' 			=> '1',
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
					'placeholder' 			=> '1',
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
				<p>Item: 1 | Descripción: Hitch A01-68-80 | Cantidad: 1  Referencia: 11-03-10.R1<button id="component_item_1" class="component_listItem_delete"></button></p>
				<p>Item: 2 | Descripción: Shaft sleeve | Cantidad: 1  Referencia: 11-01-28-02.R1<button id="component_item_2" class="component_listItem_delete"></button></p>
				<p>Item: 3 | Descripción: Screw M6x16 | Cantidad: 3  Referencia: DIN7991-M6x16<button id="component_item_3" class="component_listItem_delete"></button></p>
				<p>Item: 4 | Descripción: Screw M20x70 | Cantidad: 8  Referencia: DIN912-M20x70_8.8<button id="component_item_4" class="component_listItem_delete"></button></p>
				<p>Item: 5 | Descripción: Split lock washer 127B | Cantidad: 8  Referencia: DIN127B-M20<button id="component_item_5" class="component_listItem_delete"></button></p>
			</div>
		</div>
	<?php
	}

}