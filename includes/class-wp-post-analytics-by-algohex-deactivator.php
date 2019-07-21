<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://algohex.com
 * @since      1.0.0
 *
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/includes
 * @author     Algohex Web Developer Team <algohex@gmail.com>
 */
class Wp_Post_Analytics_By_Algohex_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		global $wpdb;
		$wpdb->query( "DROP table IF EXISTS " . $this->post_analytics_table() );
	}

	public function post_analytics_table() {
		global $wpdb;
		return $wpdb->prefix . "algo_post_analytics";
	}

}
