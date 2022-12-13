<?php

/**
* The public-facing functionality of the plugin.
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Components
* @subpackage Components/includes
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the dashboard-specific stylesheet and JavaScript.
*
* @package    Components
* @subpackage Components/admin
* @author     Your Name <arnaudagger@gmail.com>
*/
class Components_Public {
	
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
	* @var      string    $name       The name of the plugin.
	* @var      string    $version    The version of this plugin.
	*/
	public function __construct( $name, $version ) {
		
		$this->name = $name;
		$this->version = $version;
		
	}
	
	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		
		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Components_Public_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Components_Public_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/
		
		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/components-public.css', array(), $this->version, 'all' );
		
	}
	
	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		
		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Components_Public_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Components_Public_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/
		
		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/components-public.js', array( 'jquery' ), $this->version, FALSE );
		
	}
	/**
	 * Creates the "Components" data tab on product's front page
	 */
	function components_new_product_tab( $tabs ){
		global $product;
		$hasComponents = check_components( $product );
		if($hasComponents == true){
			$tabs['test_tab'] = array(
				'title' 	=> __( 'Components', 'woocommerce' ),
				'priority' 	=> 1,
				'callback' 	=> 'components_product_tab_content'
			);
		}
		return $tabs;
	}
	
	
}
/**
 * Checks if the product has components associated to it
 * @param	array	$product	The product to be checked
 * @return	boolean	
 */
function check_components( $product ){
	global $wpdb;
	$table_name = $wpdb->prefix . 'components';
	$product_id = $product->get_id();
	$query = "SELECT COUNT(*) FROM $table_name WHERE parent_id=$product_id";
	$hasComponents = $wpdb->get_var( $query );
	if( $hasComponents != 0 ){
		return true;
	}
	else { return false; }
}

/**
 * Creates the components table by calling every needed method
 */
function components_product_tab_content() {
	// The new tab content
	global $product;
	$product = wc_get_product( get_the_id() );
	$components = getComponentsArray( $product );
	echo createComponentsTableNoSale( $components );
}

/**
 * Returns the list with all the components
 * @param	array	$product			The product to get it's component's list
 * @return	array	$componentsArray	The list of all the components
 */
function getComponentsArray($product)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'components';
	$product_id = $product->get_id();
	$itemFields = $nameFields = $skuFields = $quantityFields = $componentId = $componentsArray = [];
	$query = "SELECT component_id, item, component_name,
	quantity, component_sku FROM $table_name WHERE 
	parent_id=$product_id ORDER BY item ASC;";
	$result = $wpdb->get_results($query, ARRAY_A);
	if( isset( $result ) ){
		foreach($result as $x){
			array_push($componentId, $x['component_id'] );
			array_push($itemFields, $x['item'] );
			array_push($nameFields, $x['component_name'] );
			array_push($skuFields, $x['component_sku'] );
			array_push($quantityFields, $x['quantity'] );
		}
		$componentsArray = array_map('map_Component', $itemFields , $nameFields, $quantityFields, $skuFields, $componentId);
		return $componentsArray;
	}
	else { return; }
}

/**
 * Maps every component into a unique array
 * @param	array	$item		Every component's item numbers
 * @param	array	$name		Every component's names
 * @param	array	$quantity	Every component's quantities
 * @param	array	$sku		Every the component's SKU codes
 * @param	array	$id			Every component's id
 */
function map_Component(int $item, string $name, int $quantity, string $sku, int $id){
	return["item" => $item,"name" => $name, "quantity" => $quantity, "sku" => $sku, "component_id" => $id];
}

/**
 * Returns the HTML code that creates the components table
 * Does NOT include "Add to cart" button
 * @param 	array	$componentsArray	Every component to be listed
 * @return	$setComponentsTable			The HTML code that creates the table
 */
function createComponentsTableNoSale($componentsArray){
	$setComponentsTable = 
	'<table class="setPartsTable table table-striped">
	<thead>
	<tr>
	<td>Item</td>
	<td>Description</td>
	<td>Quantity</td>
	<td>SKU</td>
	</tr>
	</thead>
	<tbody>';
	
	foreach($componentsArray as $x){
		$setComponentsTable .=
		"<tr>
		<td class='itemCell'>" . $x['item'] . "</td>
		<td class='descriptionCell'> <a href='" . get_permalink($x['component_id']) . "'>" . $x['name'] . "</a></td>
		<td class='quantityCell'>" . $x['quantity'] . "</td>
		<td class='skuCell'>" . $x['sku'] . "</td>
		</tr>";
	}    
	$setComponentsTable .= 
	'</tbody>
	</table>';
	
	return $setComponentsTable;
}