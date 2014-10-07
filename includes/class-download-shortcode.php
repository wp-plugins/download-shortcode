<?php
/**
 * Class Download_Shortcode
 */
class Download_Shortcode {

	/**
	 * Path to the file.
	 *
	 * This is used to keep a canonical record of the original file path.
	 *
	 * @access protected
	 * @var string
	 */
	protected $file_path;

	/**
	 * Uploads path.
	 *
	 * @access protected
	 * @var string
	 */
	protected $uploads_path;

	/**
	 * Downloads path.
	 *
	 * @access protected
	 * @var string
	 */
	protected $download_path;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		/**
		 * Filter the uploads path.
		 *
		 * @param string $path Uplaods path. Default WP_CONTENT_DIR . '/uploads'.
		 */
		$this->uploads_path = apply_filters( 'fds_upload_path', 'uploads' );

		/**
		 * Filter the download rewrite URI segment.
		 *
		 * @param string $directory Download directory. Default 'download'.
		 */
		$this->download_path = apply_filters( 'fds_download_path', 'download' );

		// Add the shortcode.
		add_shortcode( 'download', array( $this, 'make_download_link' ) );

		// Register the query variable.
		add_filter( 'query_vars',    array( $this, 'query_vars' ) );

		// Register the rewrites.
		add_action( 'init',          array( $this, 'init' ) );

		// Handle the request.
		add_action( 'parse_request', array( $this, 'parse_request' ) );
	}

	/**
	 * Register the query var.
	 *
	 * @access public
	 *
	 * @param array $query_vars Query vars
	 * @return array Filtered array of query vars.
	 */
	public function query_vars( $query_vars ) {
		$query_vars[] = 'fds_file';
		return $query_vars;
	}

	/**
	 * Register rewrites.
	 *
	 * @access public
	 */
	public function init() {
		add_rewrite_rule( '^' . $this->download_path . '/(.*)?', 'index.php?fds_file=$matches[1]', 'top' );
		add_rewrite_tag( '%fds_file%', '([^&]+)' );
	}

	/**
	 * Route the download.
	 *
	 * @access public
	 *
	 * @param WP $wp Request.
	 */
	public function parse_request( $wp ) {
		if ( ! empty( $wp->query_vars['fds_file'] ) ) {
			$this->filename = $this->uploads_path . $wp->query_vars['fds_file'];

			$this->deliver_download();
		} else {
			return;
		}
	}

	/**
	 * Deliver the file for download.
	 *
	 * @access public
	 */
	public function deliver_download() {
		// Grab the file extension.
		$file_ext = strtolower( substr( strrchr( $this->filename, "." ), 1 ) );

		/**
		 * Filter the list of file types and their corresponding content types.
		 *
		 * @param array $file_types Key/value pairs of extensions and content times.
		 */
		$file_types = apply_filters( 'fds_file_types', array(
			'pdf'  => 'application/pdf',
			'mp4'  => 'application/octet-stream',
			'mp3'  => 'application/octet-stream',
			'gif'  => 'image/gif',
			'png'  => 'image/png',
			'jpg'  => 'image/jpg',
			'jpeg' => 'image/jpg',
		) );

		// Absolutely do not allow adding PHP, htaccess, or web.config file types via the filter.
		foreach ( array( 'php', 'htaccess', 'web.config' ) as $ext ) {
			if ( array_key_exists( $ext, $file_types ) ) {
				unset( $file_types[ $ext ] );
			}
		}

		// Validate the file path, or bail if the extension isn't supported.
		if ( 0 !== validate_file( $this->filename ) || false == array_key_exists( $file_ext, $file_types ) ) {
			exit();
		}

		// Send the headers.
		nocache_headers();
		header( "Cache-Control: private", false );
		header( "Content-Type: {$file_types[ $file_ext ]}" );
		header( "Content-Disposition: attachment; filename=\"" . basename( $this->filename ) . "\";" );
		header( "Content-Transfer-Encoding: binary" );
		header( "Content-Length: ". @filesize( realpath( $this->filename ) ) );

		exit();
	}

	/**
	 * Make the download URL.
	 *
	 * @access public
	 *
	 * @param string $url URL.
	 * @return string Properly-formed URL.
	 */
	public function make_url( $url ) {
		$url = str_replace( content_url( "/{$this->uploads_path}/" ), '', $url );

		$this->file_path = empty( $url ) ? '' : $url;

		if ( empty( $this->file_path ) ) {
			return '';
		} else {
			return $this->get_download_uri( '/' ) . $this->file_path;
		}

	}

	/**
	 * Get download URI.
	 *
	 * @access public
	 *
	 * @param null $path Path.
	 * @return string Download URI.
	 */
	public function get_download_uri( $path = null ) {
		global $wp_rewrite;

		if ( ! is_null( $path ) && is_string( $path ) ) {
			$path = '/' . ltrim( $path, '/' );
		} else {
			$path = '';
		}

		/**
		 * Filter whether to allow permalinks.
		 *
		 * @param bool $rewrites Whether to allow permalinks.
		 */
		if ( $wp_rewrite->using_mod_rewrite_permalinks() && apply_filters( 'fds_rewrite_urls', true ) ) {
			$url = home_url( $this->download_path . $path );
		} else {
			$url = home_url( '/?fds_file=' );
		}

		return $url;
	}

	public function get_upload_uri( $path = null ) {

	}

	protected function handle_path( $path = null ) {

	}

	/**
	 * Make download link - shortcode callback.
	 *
	 * @access public
	 *
	 * @param $attr
	 * @return string
	 */
	public function make_download_link( $attr, $content ) {
		$url = $this->make_url( $content );

		if ( empty( $url ) ) {
			return '';
		}

		if ( isset( $attr['label'] ) && ! empty( $attr['label'] ) ) {
			// If we have a link label and it's not empty, assign it
			$label = esc_html( $attr['label'] );
		} else {
			// If no label, use the URL derived above
			$label = $url;
		}

		// Class is empty by default, but make it filterable
		$class = apply_filters( 'fds_download_link_class', '' );

		/**
		 * Filter the download link.
		 *
		 * The download link contains three specifiers that must be present
		 * in the filter return:
		 * - %1$s: URL
		 * - %2$s: Class(es)
		 * - %3$s: Label
		 *
		 * @param string $link  HTML markup containing specifiers for the $url, $class, and $label.
		 * @param string $url   Download link URL.
		 * @param string $class Download link class(es).
		 * @param string $label Download link label.
		 */
		$download_link = apply_filters( 'fds_download_link', '<a href="%1$s" class="%2$s">%3$s</a>', $url, $class, $label );

		// Return the linked anchor
		if (
			strpos( $download_link, '%1$s' )
			&& strpos( $download_link, '%2$s' )
			&& strpos( $download_link, '%3$s' )
		) {
			return sprintf( '<a href="%1$s" class="%2$s">%3$s</a>', esc_url( $url ), esc_attr( $class ), esc_html( $label ) );
		} else {
			return new WP_Error( 'invalid', __( 'The download link must contain all three specifiers', 'fds' ), $download_link );
		}
	}


} // Download_Shortcode
