<?php
/**
 * Class Download_Shortcode_Cleanup
 */
class Download_Shortcode_Cleanup {

	/**
	 * Whether the user needs to delete the force-download.php file.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $force_file = false;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->cleanup();
	}

	/**
	 * Delete old options and force-download.php file from /wp-content.
	 *
	 * @access protected
	 */
	protected function cleanup() {
		$file = WP_CONTENT_DIR . '/force-download.php';

		/*
		 * If the file exists and can't be deleted, overwrite the contents
		 * as empty and warn the user they need to delete the file manually.
		 */
		if ( file_exists( $file ) && ! @unlink( $file ) ) {
			@file_put_contents( $file, '' );
			$this->force_file = true;
		}

		// Delete the old FDS version.
		delete_option( 'fds_version' );
	}

	/**
	 * Handle notifying the user that the legacy force-download script needs to be deleted.
	 *
	 * @access protected
	 */
	protected function notify_user() {
		if ( $this->force_file && current_user_can( 'manage_options' ) ) {
			// Do notification stuff.
		}
	}

}
