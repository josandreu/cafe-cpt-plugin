<?php
/**
 * Plugin Name:       Café Custom Post Type
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Café Custom Post Type constructor plugin.
 * Version:           1.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jose A. Andreu
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       jaandreu-cafe-cpt
 * Domain Path:       /languages
 */

/**
 * Sets up and initializes the Café plugin.
 *
 * @return void
 * @since  1.0.0
 * @access public
 */
final class Cafe_CPT {

	/**
	 * Holds the instances of this class.
	 *
	 * @var object
	 */
	private static $instance;


	/**
	 * Sets up needed actions/filters for the plugin to initialize.
	 */
	private function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );

		/* Load admin scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );

		/* Load the admin files. */
		// add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		/* Load frontend scripts */
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
	}

	/**
	 * Defines constants for the plugin.
	 *
	 * @return void
	 */
	public function constants(): void {
		define( 'CAFE_VERSION', '1.0.0' );
		define( 'CAFE_DB_VERSION', '1' );
		define( 'CAFE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'CAFE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Loads files from the '/inc' folder.
	 *
	 * @return void
	 */
	public function includes(): void {
		require_once CAFE_DIR . 'inc/core.php';
		require_once CAFE_DIR . 'inc/post-type.php';
		require_once CAFE_DIR . 'inc/taxonomies.php';
		require_once CAFE_DIR . 'inc/custom-fields.php';
		require_once CAFE_DIR . 'inc/blocks/blocks.php';
		require_once CAFE_DIR . 'inc/blocks/block-patterns.php';
	}

	/**
	 * Loads admin files.
	 *
	 * @return void
	 */
	public function admin(): void {
		if ( is_admin() ) {
			require_once CAFE_DIR . 'admin/class-cafe-cpt-admin.php';
			// require_once __DIR__ . '/admin/class-cafe-cpt-admin.php';
		}
	}

	/**
	 * Admin styles
	 *
	 * @return void
	 */
	public function admin_scripts(): void {
		wp_enqueue_style( 'cafe-cpt-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), CAFE_VERSION, 'all' );
	}

	/**
	 * Loads front scripts
	 *
	 * @return false|void
	 */
	public function front_scripts() {
		$killswitch = apply_filters( 'cafe_cpt_killswitch', false );

		if ( $killswitch ) {
			return false;
		}

		wp_enqueue_style( 'cafe-cpt', plugins_url( 'assets/css/custom.css', __FILE__ ), array(), CAFE_VERSION, 'all' );

	}

	/**
	 * Loads the translation files.
	 *
	 * @return void
	 */
	public function i18n(): void {
		load_plugin_textdomain( 'cafe-cpt', false, 'cafe-cpt/languajes' );
	}

	/**
	 * On plugin activation, add custom capabilities to the 'administrator' role.
	 *
	 * @return void
	 */
	public function activation(): void {
		$role = get_role( 'administrator' );

		if ( ! empty( $role ) ) {
			$role->add_cap( 'manage_cafe_cpt' );
			$role->add_cap( 'create_cafe_cpt_items' );
			$role->add_cap( 'edit_cafe_cpt_items' );
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @return object
	 */
	public static function get_instance(): object {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Cafe_CPT::get_instance();
