<?php
/**
 * Plugin Name: Force Download Shortcode
 * Plugin URI: http://www.werdswords.com
 * Description: Allows you to wrap uploaded file links in a shortcode that will force a download when clicked
 * Author: Drew Jaynes (DrewAPicture)
 * Author URI: http://www.werdswords.com
 * Version: 0.2.2
 */

class Download_Shortcode {
	
	function __construct() {
		add_shortcode( 'download', array( $this, 'ww_download_cb' ) );
	}
	
	function ww_download_cb( $attr, $content ) {
		if ( empty( $content ) ) {
			return;
		} else {						
			$content_pre = trailingslashit( content_url() );
			$content = esc_url( $content );
			
			if ( isset( $attr['label'] ) ) {
				$label = esc_html( $attr['label'] );
			} else {
				$label = $content;
			}			
			
			$content = str_replace( $content_pre, '', $content );
			
			$content = $content_pre . 'force-download.php?file=' . $content;

			$class = '';
			$class = apply_filters( 'ww_download_class', $class );

			return '<a href="' . $content . '" class="' . $class . '">' . $label . '</a>';
		}
	}
}

new Download_Shortcode;
?>