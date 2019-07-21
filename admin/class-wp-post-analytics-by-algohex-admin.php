<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://algohex.com
 * @since      1.0.0
 *
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Post_Analytics_By_Algohex
 * @subpackage Wp_Post_Analytics_By_Algohex/admin
 * @author     Algohex Web Developer Team <algohex@gmail.com>
 */
class Wp_Post_Analytics_By_Algohex_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Post_Analytics_By_Algohex_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Post_Analytics_By_Algohex_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-post-analytics-by-algohex-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Post_Analytics_By_Algohex_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Post_Analytics_By_Algohex_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-post-analytics-by-algohex-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * @since 1.0.0
	 */
	public function post_analytics_menu_sections() {
		global $wpdb;
		add_menu_page(
			  "Post Analytics"
			, "Post Analytics"
			, "manage_options"
			, "algohex-post-analytics"
			, array( $this, 'post_analytics_dashbaord_page' )
			, "dashicons-chart-line"
			, 7
		);

		add_submenu_page(
			  "algohex-post-analytics"
			, "Setting"
			, "Setting"
			, "manage_options"
			, "algohex-post-analytics-setting"
			, array( $this, 'post_analytics_setting_page' )
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function post_analytics_dashbaord_page() {
		echo "this is dashboard page";
	}

	/**
	 * @since 1.0.0
	 */
	public function post_analytics_setting_page() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'administrator' ) ) {
			return;
		}
		$errors = new WP_Error();

		// preapre form action url
		$formActionUrl = add_query_arg(
			[
				'action' => 'update_post_analytics_option',
				'nonce'  => wp_create_nonce('update_post_analytics_option'),
			],
			esc_url( admin_url('admin.php') . '?page=algohex-post-analytics-setting' )
		);

		// update detail if form submitted
		if (
				! empty( $_POST )
			&& isset( $_GET['nonce'] )
			&& $_GET['action'] == 'update_post_analytics_option'
			&& wp_verify_nonce( $_GET['nonce'], 'update_post_analytics_option' )
			&& check_admin_referer( 'update_post_analytics_option', 'nonce' )
		) {
			if ( ! empty( $_POST[ 'deleteDataOption' ] ) ) {
				$deleteDataOption = sanitize_option( 'delete_data_when_deactivated',$_POST['deleteDataOption'] );
				if ( $deleteDataOption == 'yes' || $deleteDataOption == 'no' ) {
					update_option( 'delete_data_when_deactivated', $deleteDataOption );
				} else {
					$errors->add( 'page_url_error', __( '<strong>Notice</strong>: The value is invalid.' ) );
				}
			}
		}
		?>
		<div class="wrap">
			<h1>Post Analytics Settings</h1>
			<small>- by Algohex -</small>

			<form method="post" action="<?php esc_html_e( $formActionUrl ) ; ?>">
				<table class="form-table">
					<tbody>
						<tr>
							<td width="20%">
								<label>Delete data after deactivated?</label>
							</td>
							<td>
								<select name="deleteDataOption">
									<option value="no" <?php if ( get_option( 'delete_data_when_deactivated' ) == 'no' ) echo 'selected'; ?>>No, please keep the data.</option>
									<option value="yes" <?php if ( get_option( 'delete_data_when_deactivated' ) == 'yes' ) echo 'selected'; ?>>Yes, please delete.</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * @since 1.0.0
	 */
	public function add_visitor_count_for_post_load( $content ) {
		// get user ip
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						$user_ip = $ip;
					}
				}
			}
		}

		global $wpdb;
		if ( ! is_admin() ) {
			$post_id = get_post()->ID;
			if ( !get_current_user_id() ) {
				$wpdb->insert( $wpdb->prefix . "algo_post_analytics",
					array(
						  'visitor_ip'     => $user_ip
						, 'post_id'        => $post_id
						, 'visit_datetime' => current_time( 'mysql' )
					)
				);
			}

			return get_post()->post_content;
		}
	}

}
