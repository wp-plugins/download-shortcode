<?php
/**
 * Plugin Name: Download Shortcode
 * Plugin URI: http://www.werdswords.com
 * Description: Allows you to wrap uploaded file links in a shortcode that will force a download when clicked
 * Author: Drew Jaynes (DrewAPicture)
 * Author URI: http://www.werdswords.com
 * Version: 1.2-beta
 */

/**
 * Class Download_Shortcode_Setup
 */
class Download_Shortcode_Setup {

	/**
	 * Static instance.
	 *
	 * @static
	 * @access private
	 * @var Download_Shortcode_Setup
	 */
	public static $_instance = null;

	/**
	 * Whether the back-compat force-download.php file needs to be deleted.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $force_file = false;

	/**
	 * Deliberately empty constructor :-)
	 */
	public function __construct() {}

	/**
	 * Instance.
	 *
	 * @static
	 * @access public
	 *
	 * @return Download_Shortcode_Setup
	 */
	public static function init() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new Download_Shortcode_Setup();

			// Textdomain and requires.
			self::$_instance->startup();

			// Core functionality.
			self::$_instance->shortcode = new Download_Shortcode();

			// Clean up legacy force-download script.
			self::$_instance->cleanup = new Download_Shortcode_Cleanup();
		}
		return self::$_instance;
	}

	/**
	 * Start up tasks.
	 *
	 * @access protected
	 */
	protected function startup() {
		// Translations.
		load_plugin_textdomain( 'force_download_shortcode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Requires.
		require_once( __DIR__ . '/includes/class-download-shortcode.php' );
		require_once( __DIR__ . '/includes/class-download-shortcode-cleanup.php' );
	}

}
add_action( 'plugins_loaded', array( 'Download_Shortcode_Setup', 'init' ) );
