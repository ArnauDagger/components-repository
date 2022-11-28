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
		create_components_table();
	}
	function create_components_table(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'components';
        $sql="CREATE TABLE Zi6w2ai2T_components (
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
