=== Download Shortcode ===
Contributors: DrewAPicture
Donate link: http://www.werdswords.com
Tags: downloads, shortcode, force download
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.2
License: GPLv2

Allows you to wrap file links in a shortcode that will force a download when clicked

== Description ==

Sometimes you want to force users to download files from a link without having those files opened by the browser.
This plugin introduces the [download] shortcode that wraps links in your content and does just that. 

Two things:
*   You **MUST** manually upload force-download.php into your wp-content directory.
*   Files **MUST** be uploaded via the WordPress uploader for the shortcode to work.

The shortcode can be used in multiple ways:

`[download label="My Label"]http://example.com/wp-content/uploads/my_song.mp3[/download]` would show as a link titled `My Label`

`[download]http://example.com/wp-content/uploads/my_song.mp3[/download]` would show as a link titled `http://example.com/wp-content/uploads/my_song.mp3`

If you wanted to use this in a php file, you could call something like `<?php do_shortcode( '[download label="My Label"]http://myuploadedfile.mp3[/download]' ); ?>`

== Installation ==

1. Upload the entire `download-shortcode` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Which file formats does this work with? =

Currently, this plugin can force downloads for pdf, mp4, mp3, gif, png, jpg and jpeg files

= When I click links from the front-end, all I get is a 404 Page Not Found error. What gives? =

You probably didn't manually upload the force-downloads.php script to your site's wp-content directory.

= How can I style download links differently?

There is a built-in filter hook you can use to add a class to the link tags the shortcode produces, `ww_download_class`.

This example filter adds the 'downloads' class:

`
function filter_download_links() {
	return 'downloads';
}
add_filter( 'ww_download_class', 'filter_download_links' );
`

== Changelog ==

= 0.1 = First version

= 0.2 = Fix security vulnerability which exposed php core files to direct download, docblocking and other tweaks. 

== Upgrade Notice ==

= 0.1 = Initial submission

= 0.2 = Security Fix

== Screenshots ==

1. No screenshots.