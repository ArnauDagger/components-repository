<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Components
 * @subpackage Components/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Components
 * @subpackage Components/includes
 * @author     Your Name <arnaudagger@gmail.com>
 */
class Components_Activator {

    /**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::components_create_databaseTable(); 
        self::components_create_user_roles();
	}

    /**
     * Creates 5 user roles with the same capabilities than customer
     */
    private static function components_create_user_roles(){
        add_role( 'descuento10', 'Descuento 10', get_role( 'customer' )->capabilities );
        add_role( 'descuento15', 'Descuento 15', get_role( 'customer' )->capabilities );
        add_role( 'descuento20', 'Descuento 20', get_role( 'customer' )->capabilities );
        add_role( 'descuento25', 'Descuento 25', get_role( 'customer' )->capabilities );
        add_role( 'descuento30', 'Descuento 30', get_role( 'customer' )->capabilities );
    }

    /**
     * Creates the database table needed for the plugin to work
     */
    private static function components_create_databaseTable(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'components';
        $sql="CREATE TABLE IF NOT EXISTS $table_name (
            meta_id BIGINT(20) NOT NULL AUTO_INCREMENT,
            parent_id BIGINT(20) NOT NULL DEFAULT '0',
            component_id BIGINT(20) NOT NULL DEFAULT '0',
            item INT,
            component_name LONGTEXT COLLATE utf8mb4_unicode_520_ci,
            quantity INT,
            component_sku LONGTEXT COLLATE utf8mb4_unicode_520_ci,
            PRIMARY KEY (meta_id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

}
