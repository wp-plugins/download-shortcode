=== Download Shortcode ===
Contributors: DrewAPicture
Donate link: http://www.werdswords.com
Tags: downloads, shortcode, force download
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.2.3
License: GPLv2

Allows you to wrap file links in a shortcode that will force a download when clicked

== Description ==

**PLEASE NOTE:** For those of you who were experiencing white screen issues with 0.2 or 0.2.1, you'll need to replace the old force-download.php file in wp-content with the new one in version 0.2.3!

Sometimes you want to force users to download files from a link without having those files opened by the browser.
This plugin introduces the [download] shortcode that wraps links in your content and does just that. 

Two things:
1. You **MUST** manually upload force-download.php into your wp-content directory.
2. Files **MUST** be uploaded via the WordPress uploader for the shortcode to work.

The shortcode can be used in multiple ways:

`[download label="My Label"]http://example.com/wp-content/uploads/my_song.mp3[/download]` would show as a link titled `My Label`

`[download]http://example.com/wp-content/uploads/my_song.mp3[/download]` would show as a link titled `http://example.com/wp-content/uploads/my_song.mp3`

If you wanted to use this in a php file, you could call something like `<?php do_shortcode( '[download label="My Label"]http://example.com/wp-content/uploads/my_song.mp3[/download]' ); ?>`

== Installation ==

1. Upload the entire `download-shortcode` folder to the `/wp-content/plugins/` directory
2. Move/upload the 'force-download.php' file to your wp-content directory.
3. Activate the plugin through the 'Plugins' menu in the Dashboard.

== Frequently Asked Questions ==

= Which file formats does this work with? =

Currently, this plugin can force downloads for pdf, mp4, mp3, gif, png, jpg and jpeg files

= When I click links from the front-end, all I get is a 404 Page Not Found error. What gives? =

You probably didn't manually upload the force-downloads.php script to your site's wp-content directory.

= How can I style download links differently? =

There is a built-in filter hook you can use to add a class to the link tags the shortcode produces, `ww_download_class`.

This example filter adds the 'downloads' class:

`
function filter_download_links( $class ) {
	$class = 'downloads';
	return $class;
}
add_filter( 'ww_download_class', 'filter_download_links' );
`

== Changelog ==

= 0.1 = First version

= 0.2 = Fix security vulnerability which exposed php core files to direct download, docblocking and other tweaks. 

= 0.2.2 = Remove faulty strlen check on filenames in force-download.php. Fixes WSOD issues.

= 0.2.3 = Update readme.txt with note about replacing force-download.php in wp-content with the new one

== Upgrade Notice ==

= 0.1 = Initial submission

= 0.2 = Security Fix

= 0.2.3 = Make sure you replace force-download.php in your wp-content folder with the new version!

== Screenshots ==

1. No screenshots.