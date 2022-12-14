<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Components
 * @subpackage Components/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Components
 * @subpackage Components/includes
 * @author     Your Name <arnaudagger@gmail.com>
 */
class Components {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Components_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $components_plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $components_plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->components_plugin_name = 'Components plugin';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
        
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Components_Loader. Orchestrates the hooks of the plugin.
	 * - Components_i18n. Defines internationalization functionality.
	 * - Components_Admin. Defines all hooks for the dashboard.
	 * - Components_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-components-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-components-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-components-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-components-public.php';

		$this->loader = new Components_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Components_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Components_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Components_Admin( $this->get_plugin_name(), $this->get_version() );
		//Enqueue scripts and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//Enqueue functions to create "Components" custom tab at product's admin page
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'components_add_tab', 99 , 1 );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'components_create_tab' );
		// Ajax request handlers
		$this->loader->add_action( "wp_ajax_components_delete_item_at_database", $plugin_admin, "components_delete_item_at_database" );
		$this->loader->add_action( "wp_ajax_components_insert_manage", $plugin_admin, "components_insert_manage" );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Components_Public( $this->get_plugin_name(), $this->get_version() );
		//Enqueue scripts and styles
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//Enqueue filter to modify product's description
		$this->loader->add_filter( 'woocommerce_product_tabs', $plugin_public, 'components_new_product_tab', 98 );
		//Enqueue filter to make products purchasable or not
		$this->loader->add_filter( 'woocommerce_is_purchasable', $plugin_public, 'purshasabale_course', 10, 2 );
		//Enqueue filters to hide prices to users with role NoSale
		$this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'hide_prices' );
		//Enqueue action to apply discounts per user role
		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'discount_based_on_user_role', 20, 1 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->components_plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Components_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}