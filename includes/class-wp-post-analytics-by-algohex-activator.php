<?php

/**
 * Fired during plugin activation
 *
 * @link       https://algohex.com
 * @since      1.0.0
 *
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/includes
 * @author     Algohex Web Developer Team <algohex@gmail.com>
 */
class Wp_Post_Analytics_By_Algohex_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {

		include_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;
		if ( count( $wpdb->get_var( "Show table like '" . $this->post_analytics_table() . "'" ) ) == 0 ) {
			$sqlQuery = 'CREATE TABLE `' . $this->post_analytics_table() . '` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`visitor_ip` INT NOT NULL ,
				`post_id` INT NOT NULL ,
				PRIMARY KEY (`id`)
			) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;';
			dbDelta( $sqlQuery );
		}
	}

	public function post_analytics_table() {
		global $wpdb;
		return $wpdb->prefix . "algo_post_analytics";
	}

}
